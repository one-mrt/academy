<?
namespace Lang\Ajax\Controller;

use Bitrix\Main\Loader;
use Bitrix\Main\Application;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
// use Bitrix\Main\Engine\ActionFilter\Authentication;
use Bitrix\Main\Engine\ActionFilter\Attribute\Rule\Prefilters;
use \Bitrix\Main\Engine\Response\Component as ComponentResponse;

use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\Authentication;

Loader::includeModule('im');
Loader::includeModule('iblock');
Loader::includeModule('sale');
Loader::includeModule('catalog');
Loader::includeModule('lang.iblock');
Loader::includeModule('lang.css');
Loader::includeModule('lang.academy');

final class Page extends Controller
{
	// Добавить сессию - Авторизация
	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function authAction() : ComponentResponse
    {
        return $this->renderComponentAjax('lang:auth', '', [], [], ['auth', 'authUrl']);
    }

	// Добавить сессию - Регистрация
	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function regAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:reg', '', [], [], ['reg', 'regUrl']);
	}

	// Добавить сессию - Восстановление пароля
	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function forgotAction() : ComponentResponse
	{
		return $this->renderComponentAjax('lang:forgot', '', [], [], []);
	}

	// Добавить сессию - Смена пароля
	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function changeAction() : ComponentResponse
	{
		return $this->renderComponentAjax('lang:change', '', [], [], ['change']);
	}

	// Сессия, авторизация
	public function getOrderLkAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:get.order', '', ['type' => 'work', 'ajax' => 'y'], [], ['type', 'next']);
	}

	// Сессия, авторизация
	public function getOrderBuyAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:get.order', '', ['type' => 'buy', 'ajax' => 'y'], [], ['type', 'next']);
	}

	// Сессия, авторизация
	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function orderAddAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:order.add', '', [], [], ['order', 'orderUrl']);
	}

	// Сессия
	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getOrderPublicAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:get.order.public', '', ['type' => 'ajax'], [], ['next']);
	}

	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getExpertsAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:get.experts', '', ['ajax' => 'y'], [], ['next']);
	}

	// Добавить авторизацию
	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function editUserDescriptionAction(): ComponentResponse
	{
		return $this->renderComponentAjax('bitrix:main.profile', 'lang.description', [
			"CHECK_RIGHTS" => "N",
			"SEND_INFO" => "N",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => [],
			"USER_PROPERTY_NAME" => ""
		], [], ['DATA_SAVED']);
	}

	// Добавить авторизацию
	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function editUserSpecAction(): ComponentResponse
	{
		return $this->renderComponentAjax('bitrix:main.profile', 'lang.spec', [
			"CHECK_RIGHTS" => "N",
			"SEND_INFO" => "N",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => array("UF_SPEC", "UF_WORKTYPE"),
			"USER_PROPERTY_NAME" => ""
		], [], ['DATA_SAVED']);
	}

	// Добавить авторизацию
	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function editUserImageAction(): ComponentResponse
	{
		return $this->renderComponentAjax('bitrix:main.profile', 'lang.image', [
			"CHECK_RIGHTS" => "N",
			"SEND_INFO" => "N",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => [],
			"USER_PROPERTY_NAME" => ""
		], [], ['DATA_SAVED']);
	}

	// Добавить авторизацию
	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function editUserPasswordAction(): ComponentResponse
	{
		return $this->renderComponentAjax('bitrix:main.profile', 'lang.password', [
			"CHECK_RIGHTS" => "N",
			"SEND_INFO" => "N",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => [],
			"USER_PROPERTY_NAME" => ""
		], [], ['DATA_SAVED']);
	}

	// strProfileError

	// Добавить авторизацию
	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function editUserProfileAction(): ComponentResponse
	{
		return $this->renderComponentAjax('bitrix:main.profile', 'lang.profile', [
			"CHECK_RIGHTS" => "N",
			"SEND_INFO" => "N",
			"SET_TITLE" => "N",
			"USER_PROPERTY" => [],
			"USER_PROPERTY_NAME" => ""
		], [], ['DATA_SAVED', 'strProfileError']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function editLkSettingNotifyAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:lk.setting.notify', '', [], [], ['error', 'saveSuccess']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function addPrivateOrderAction() : ComponentResponse
	{
		return $this->renderComponentAjax('lang:order', '.private', [], [], ['error', 'published', 'publishedUrl']);
	}

	// #[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	// public function addOrderAction() : ComponentResponse
	// {
	// 	return $this->renderComponentAjax('lang:order', '.public', [], [], ['error', 'published', 'publishedUrl']);
	// }

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function downloadFileAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:lk.download.file', '.public', [], [], ['error', 'published', 'publishedUrl']);
	}

	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getReviewAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:get.review', '', ['type' => 'ajax'], [], ['next']);
	}

	// Сессия, авторизация
	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getUserTransactAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:transact', '', ['type' => 'ajax'], [], ['next']);
	}

	#[Prefilters([new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getBlogAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:blog', '', ['type' => 'ajax'], [], ['next']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getOrderDetailAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:lk.order.detail', '', ['type' => 'update'], [], ['next']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getOrderPayAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:lk.order.pay', '', ['type' => 'update'], [], ['paySuccess', 'error']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getOrderEditAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:lk.order.edit', '', [], [], ['error', 'saveSuccess']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getBalansOutAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:lk.pay.out', '', [], [], ['error', 'saveSuccess', 'savePaySystem', 'saveSumOut']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function getBalansInAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:account.pay', '', ["ELIMINATED_PAY_SYSTEMS" => ["1", "2"]], [], ['error', 'paySuccess']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function addOrderReviewAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:add.review', '', [], [], ['error', 'addSuccess']);
	}

	#[Prefilters([new Authentication(), new Csrf(tokenName: 'sessid', returnNew: false)])]
	public function addOrderOfferAction(): ComponentResponse
	{
		return $this->renderComponentAjax('lang:order.offer', '', [], [], ['error', 'addSuccess', 'offer', 'next', 'orderId', 'serviceId', 'offerId', 'accept', 'rrn']);
	}

	protected function getDefaultPreFilters(): array
	{
		return [];
	}
}