<?
namespace Lang\Academy;

use Bitrix\Main\Engine\CurrentUser;

use Bitrix\UI\EntitySelector\BaseProvider;
use Bitrix\UI\EntitySelector\Dialog;
use Bitrix\UI\EntitySelector\Item;
use Bitrix\UI\EntitySelector\SearchQuery;
use Bitrix\UI\EntitySelector\Tab;

use Bitrix\Main\EO_User;
use Bitrix\Main\UserTable;
use Bitrix\Main\EO_User_Collection;
use Bitrix\Main\UserUtils;
use Bitrix\Main\UserIndexTable;

use Bitrix\Socialnetwork\Integration\HumanResources\EmployeeHelper;
use Bitrix\Main\Entity\Query;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Filter;
use Bitrix\Main\Search\Content;
use Bitrix\Main\ORM\Fields\Relations\Reference;

use Bitrix\Extranet\Enum\User\ExtranetRole;
use Bitrix\Extranet\Model\ExtranetUserTable;
use Bitrix\Extranet\Service\ServiceContainer;
use Bitrix\HumanResources\Compatibility\Utils\DepartmentBackwardAccessCode;
use Bitrix\HumanResources\Model\NodeMemberTable;
use Bitrix\HumanResources\Model\NodeTable;
use Bitrix\HumanResources\Type\MemberEntityType;
use Bitrix\HumanResources\Type\NodeEntityType;
use Bitrix\Intranet\Integration\Mail\EmailUser;
use Bitrix\Intranet\Internals\InvitationTable;
use Bitrix\Intranet\Invitation;
use Bitrix\Intranet\UserAbsence;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\DB\SqlExpression;
use Bitrix\Main\Engine\Router;
use Bitrix\Main\Engine\UrlManager;
use Bitrix\Main\Loader;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Query\Filter\ConditionTree;
use Bitrix\Socialnetwork\Collab\CollabFeature;
use Bitrix\Socialnetwork\UserToGroupTable;
use Bitrix\Socialnetwork\WorkgroupSiteTable;

use Bitrix\Im\User as ImUser;
use Bitrix\Im\Integration\UI\EntitySelector\Helper\User as ImHelperUser;

final class UsersChatProvider extends BaseProvider
{
    protected const ENTITY_ID = 'lang-user';
    private const TAB = "user";
    protected const MAX_USERS_IN_RECENT_TAB = 50;
	protected const SEARCH_LIMIT = 100;
    // protected array $options = [];
    private ?int $currentUser;
    private ?object $users;
    private ?object $query;

    public function __construct(array $options = [])
    {
        parent::__construct();

        // $this->options = $options;
        $this->prepareOptions($options);

        $this->currentUser = intval(CurrentUser::get()->getId());
        $this->query = UserTable::query();
            // ->setSelect([
            //     'ID', 'NAME', 'LAST_NAME', 'PERSONAL_PHOTO', 'IS_ONLINE', 'GROUPS.GROUP_ID', 'LOGIN'
            // ])
            // ->where('ACTIVE', 'Y')
            // ->where('GROUPS.GROUP_ID', 5)
            // ->setCacheTtl(3600)
            // ->cacheJoins(true)
            // ->setLimit(100);

        // $this->users = $this->query->fetchCollection();
    }

    protected function prepareOptions(array $options = []): void
	{
        $this->options['analyticsSource'] = 'userChatProvider';
        $this->options['fillDialog'] = true;
        $this->options['maxUsersInRecentTab'] = self::MAX_USERS_IN_RECENT_TAB;
        $this->options['searchLimit'] = self::SEARCH_LIMIT;

		if (isset($options['nameTemplate']) && is_string($options['nameTemplate'])) {
			preg_match_all(
				'/#NAME#|#LAST_NAME#|#SECOND_NAME#|#NAME_SHORT#|#SECOND_NAME_SHORT#|\s|,/',
				urldecode($options['nameTemplate']),
				$matches,
			);

			$this->options['nameTemplate'] = implode('', $matches[0]);
		} else {
			$this->options['nameTemplate'] = \CSite::getNameFormat(false);
		}

		if (isset($options['analyticsSource'])) {
			$this->options['analyticsSource'] = $options['analyticsSource'];
		}

		if (isset($options['selectFields']) && is_array($options['selectFields'])) {
			$selectFields = [];
			$allowedFields = $this->getAllowedFields();

			foreach ($options['selectFields'] as $field) {
				if (is_string($field) && array_key_exists($field, $allowedFields)) {
					$selectFields[] = $field;
				}
			}

			$this->options['selectFields'] = array_unique($selectFields);
		}

		if (isset($options['fillDialog']) && is_bool($options['fillDialog'])) {
			$this->options['fillDialog'] = $options['fillDialog'];
		}

		if (isset($options['maxUsersInRecentTab']) && is_int($options['maxUsersInRecentTab'])) {
			$this->options['maxUsersInRecentTab'] = max(
				1,
				min($options['maxUsersInRecentTab'], self::MAX_USERS_IN_RECENT_TAB),
			);
		}

		if (isset($options['searchLimit']) && is_int($options['searchLimit'])) {
			$this->options['searchLimit'] = max(1, min($options['searchLimit'], self::SEARCH_LIMIT));
		}
	}

    private function getUserId(int $id): ?object
    {
        foreach($this->users as $user) {
            if($user->getId() == $id) {
                return $user;
            }
        }

        return null;
    }

    public function isAvailable(): bool
    {
        if($this->currentUser <= 0) {
            return false;
        }

        return true;
    }

    public function shouldFillDialog(): bool
	{
		return $this->getOption('fillDialog', true);
	}

    public function getItems(array $ids): array
    {
        if (!$this->shouldFillDialog()) {
			return [];
		}

        return $this->getUserItems([
			'userId' => $ids,
		]);
    }

    public function getSelectedItems(array $ids): array
	{
		return $this->getUserItems(['userId' => $ids]);
	}

    public function getUserItems(array $options = []): array
	{
		return $this->makeUserItems($this->getUserCollection($options), $options);
	}

	public function makeUserItems(EO_User_Collection $users, array $options = []): array
	{
		return $this->makeItems($users, array_merge($this->getOptions(), $options));
	}

	public function makeItems(EO_User_Collection $users, array $options = []): array
	{
		$result = [];

		foreach ($users as $user) {
			$result[] = $this->makeItem($user, $options);
		}

		return $result;
	}

    public function fillDialog(Dialog $dialog): void
    {
        if (!$this->shouldFillDialog()) {
			return;
		}

        // Preload first 50 users ('doSearch' method has to have the same filter).
		$preloadedUsers = $this->getPreloadedUsersCollection();

		if ($preloadedUsers->count() < $this->options['maxUsersInRecentTab']) {
			// Turn off the user search
			$entity = $dialog->getEntity(self::ENTITY_ID);

			if ($entity) {
				$entity->setDynamicSearch(false);
			}
		}

		$recentUsers = new EO_User_Collection();

		// Recent Items
		$recentItems = $dialog->getRecentItems()->getEntityItems(self::ENTITY_ID);
		$recentIds = array_map('intval', array_keys($recentItems));
		$this->fillRecentUsers($recentUsers, $recentIds, $preloadedUsers);

		// Global Recent Items
		if ($recentUsers->count() < $this->options['maxUsersInRecentTab']) {
			$recentGlobalItems = $dialog->getGlobalRecentItems()->getEntityItems(self::ENTITY_ID);
			$recentGlobalIds = [];

			if (!empty($recentGlobalItems)) {
				$recentGlobalIds = array_map('intval', array_keys($recentGlobalItems));
				$recentGlobalIds = array_values(array_diff($recentGlobalIds, $recentUsers->getIdList()));
				$recentGlobalIds = array_slice($recentGlobalIds, 0, $this->options['maxUsersInRecentTab'] - $recentUsers->count());
			}

			$this->fillRecentUsers($recentUsers, $recentGlobalIds, $preloadedUsers);
		}

		// The rest of preloaded users
		foreach ($preloadedUsers as $preloadedUser) {
			$recentUsers->add($preloadedUser);
		}

		$dialog->addRecentItems($this->makeUserItems($recentUsers));
    }

    private function fillRecentUsers(EO_User_Collection $recentUsers, array $recentIds, EO_User_Collection $preloadedUsers): void
	{
		if (count($recentIds) < 1) {
			return;
		}

		$ids = array_values(array_diff($recentIds, $preloadedUsers->getIdList()));

		if (!empty($ids)) {
			$users = $this->getUserCollection(['userId' => $ids]);

			foreach ($users as $user) {
				$preloadedUsers->add($user);
			}
		}

		foreach ($recentIds as $recentId) {
			$user = $preloadedUsers->getByPrimary($recentId);

			if ($user) {
				$recentUsers->add($user);
			}
		}
	}

    protected function getPreloadedUsersCollection(): EO_User_Collection
	{
		return $this->getUserCollection([
			'order' => ['ID' => 'asc'],
			'limit' => $this->options['maxUsersInRecentTab'],
		]);
	}

    public function doSearch(SearchQuery $searchQuery, Dialog $dialog): void
    {
        $atom = '=_0-9a-z+~\'!\$&*^`|\\#%/?{}-';
		$isEmailLike = (bool)preg_match('#^['.$atom.']+(\\.['.$atom.']+)*@#i', $searchQuery->getQuery());

		if ($isEmailLike)
		{
			$items = $this->getUserItems([
				'searchByEmail' => $searchQuery->getQuery(),
				'myEmailUsers' => false,
				'limit' => $this->options['searchLimit'],
			]);
		}
		else
		{
			$items = $this->getUserItems([
				'searchQuery' => $searchQuery->getQuery(),
				'limit' => $this->options['searchLimit'],
			]);
		}

		$limitExceeded = ($this->options['searchLimit'] <= count($items));
		if ($limitExceeded)
		{
			$searchQuery->setCacheable(false);
		}

		$dialog->addItems($items);
    }

    public function makeItem(EO_User $user, array $options = []): Item
	{
		$customData = [];

		foreach(['name', 'lastName', 'secondName', 'login'] as $field) {
			if (!empty($user->{'get'.$field}())) {
				$customData[$field] = $user->{'get'.$field}();
			}
		}

		if (!empty($user->getPersonalGender())) {
			$customData['gender'] = $user->getPersonalGender();
		}

		if (!empty($user->getWorkPosition())) {
			$customData['position'] = $user->getWorkPosition();
		}

		$userType = $this->getUserType($user, $options);

		if ($user->getConfirmCode() && in_array($userType, ['employee', 'integrator'])) {
			$customData['invited'] = true;
		}

		if (isset($options['selectFields']) && is_array($options['selectFields'])) {
			$userData = $user->collectValues();
			$allowedFields = $this->getAllowedFields();

			foreach ($options['selectFields'] as $field) {
				if (!is_string($field)) {
					continue;
				}

				$dbName = $allowedFields[$field] ?? null;
				$value = $userData[$dbName] ?? null;

				if (!empty($value)) {
					if ($field === 'country' || $field === 'workCountry') {
						$value = UserUtils::getCountryValue(['VALUE' => $value]);
					}

					$customData[$field] = $value;
				}
			}
		}

		if (!empty($options['departmentMap']) && isset($options['departmentMap'][$user->getId()])) {
			$customData['nodeId'] = $options['departmentMap'][$user->getId()];
		}

		$item = new Item([
			'id' => $user->getId(),
			'entityId' => static::ENTITY_ID,
			'entityType' => $userType,
			'title' => $this->formatUserName($user, $options),
			'avatar' => $this->makeUserAvatar($user),
			'customData' => $customData,
			'tabs' => $this->getTabsNames(),
		]);

        // $customData['imUser'];

        if ($item->getId() === ImHelperUser::getCurrentUserId()) {
            $item->addBadges([[
                'id' => 'IT_IS_YOU',
                'title' => "Это вы",
            ]]);
        }

        $customData = $item->getCustomData();
        $userInfo = ImUser::getInstance($item->getId())->getArray();
        $customData->set('imUser', $userInfo);

		return $item;
	}

    protected static function getTabsNames(): array
	{
		return [static::TAB];
	}

    public function formatUserName(EO_User $user, array $options = []): string
	{
		return \CUser::formatName(
			!empty($options['nameTemplate']) ? $options['nameTemplate'] : \CSite::getNameFormat(false),
			[
				'NAME' => $user->getName(),
				'LAST_NAME' => $user->getLastName(),
				'SECOND_NAME' => $user->getSecondName(),
				'LOGIN' => $user->getLogin(),
				// 'EMAIL' => $user->getEmail(),
				'TITLE' => $user->getTitle(),
			],
			true,
			false,
		);
	}

    public function makeUserAvatar(EO_User $user): ?string
	{
		if (empty($user->getPersonalPhoto())) {
			return null;
		}

		$avatar = \CFile::resizeImageGet(
			$user->getPersonalPhoto(),
			['width' => 100, 'height' => 100],
			BX_RESIZE_IMAGE_EXACT,
			false,
		);

		return !empty($avatar['src']) ? $avatar['src'] : null;
	}

    public function getUserType(EO_User $user, array $options = []): string
	{
		return 'user';
	}

    public function getAllowedFields(): array
	{
		static $fields = null;

		if ($fields !== null) {
			return $fields;
		}

		$fields = [
			'lastName' => 'LAST_NAME',
			'name' => 'NAME',
			'secondName' => 'SECOND_NAME',
			'login' => 'LOGIN',
			// 'email' => 'EMAIL',
			'title' => 'TITLE',
			'position', 'WORK_POSITION',
			'lastLogin' => 'LAST_LOGIN',
			'dateRegister' => 'DATE_REGISTER',
			'lastActivityDate' => 'LAST_ACTIVITY_DATE',
			'online' => 'IS_ONLINE',
			'profession' => 'PERSONAL_PROFESSION',
			'www' => 'PERSONAL_WWW',
			'birthday' => 'PERSONAL_BIRTHDAY',
			'icq' => 'PERSONAL_ICQ',
			'phone' => 'PERSONAL_PHONE',
			'fax' => 'PERSONAL_FAX',
			'mobile' => 'PERSONAL_MOBILE',
			'pager' => 'PERSONAL_PAGER',
			'street' => 'PERSONAL_STREET',
			'city' => 'PERSONAL_CITY',
			'state' => 'PERSONAL_STATE',
			'zip' => 'PERSONAL_ZIP',
			'mailbox' => 'PERSONAL_MAILBOX',
			'country' => 'PERSONAL_COUNTRY',
			'timeZoneOffset' => 'TIME_ZONE_OFFSET',
			'company' => 'WORK_COMPANY',
			'workPhone' => 'WORK_PHONE',
			'workDepartment' => 'WORK_DEPARTMENT',
			'workPosition' => 'WORK_POSITION',
			'workCity' => 'WORK_CITY',
			'workCountry' => 'WORK_COUNTRY',
			'workStreet' => 'WORK_STREET',
			'workState' => 'WORK_STATE',
			'workZip' => 'WORK_ZIP',
			'workMailbox' => 'WORK_MAILBOX',
		];

		foreach ($fields as $id => $dbName) {
			if (mb_strpos($dbName, 'PERSONAL_') === 0) {
				$fields['personal' . ucfirst($id)] = $dbName;
			}

			$fields[$dbName] = $dbName;
		}

		return $fields;
	}

    protected function getQuery(array $options = [])
	{
		$selectFields = [
			'ID', 'ACTIVE', 'LAST_NAME', 'NAME', 'SECOND_NAME', 'LOGIN', 'EMAIL', 'TITLE',
			'PERSONAL_GENDER', 'PERSONAL_PHOTO', 'WORK_POSITION',
			'CONFIRM_CODE', 'EXTERNAL_AUTH_ID', 'GROUPS.GROUP_ID',
		];

		if (isset($options['selectFields']) && is_array($options['selectFields'])) {
			$allowedFields = $this->getAllowedFields();

			foreach ($options['selectFields'] as $field) {
				if (is_string($field) && array_key_exists($field, $allowedFields)) {
					$selectFields[] = $allowedFields[$field];
				}
			}
		}

		$this->query->setSelect(array_unique($selectFields));

        $this->query->where('ACTIVE', 'Y');
        $this->query->where('GROUPS.GROUP_ID', 5);

		if (!empty($options['searchQuery']) && is_string($options['searchQuery'])) {
			$this->query->registerRuntimeField(
				new Reference('USER_INDEX', UserIndexTable::class, Join::on('this.ID', 'ref.USER_ID'), ['join_type' => 'INNER']),
			);

			$this->query->whereMatch(
				'USER_INDEX.SEARCH_USER_CONTENT',
				Filter\Helper::matchAgainstWildcard(
					Content::prepareStringToken($options['searchQuery']), '*', 1,
				),
			);
		} else if (!empty($options['searchByEmail']) && is_string($options['searchByEmail'])) {
			$this->query->whereLike('EMAIL', $options['searchByEmail'].'%');
		}

		$this->query->where('REAL_USER', 'expr', true);

        if (!empty($options['order']) && is_array($options['order'])) {
			$this->query->setOrder($options['order']);
		} else {
			$this->query->setOrder(['LAST_NAME' => 'asc']);
		}

		if (isset($options['limit']) && is_int($options['limit'])) {
			$this->query->setLimit($options['limit']);
		} else {
			$this->query->setLimit(100);
		}
	}

    public function getUsers(array $options = []): EO_User_Collection
	{
        $this->getQuery($options);

		$result = $this->query->exec();

		return $result->fetchCollection();
	}

    public function getUserCollection(array $options = []): EO_User_Collection
	{
		$dialogOptions = $this->getOptions();
		$options = array_merge($dialogOptions, $options);

		$ignoreUserWhitelist = isset($options['ignoreUserWhitelist']) && $options['ignoreUserWhitelist'] === true;
		if (!empty($dialogOptions['userId']) && !$ignoreUserWhitelist) {
			$options['userId'] = $dialogOptions['userId'];
		}

		static $usersCache = [];
		$cacheKey = md5(serialize($options));

		if (!isset($usersCache[$cacheKey])) {
			$usersCache[$cacheKey] = $this->getUsers($options);
		}

		return $usersCache[$cacheKey];
	}

    private static function prepareUserIds($items): array
	{
		$ids = [];

		if (is_array($items) && !empty($items)) {
			foreach ($items as $id) {
				if ((int)$id > 0) {
					$ids[] = (int)$id;
				}
			}

			$ids = array_unique($ids);
		} else if (!is_array($items) && (int)$items > 0) {
			$ids = [(int)$items];
		}

		return $ids;
	}
}