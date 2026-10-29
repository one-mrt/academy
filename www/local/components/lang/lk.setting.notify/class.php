<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;

class LangLkSettingNotify extends CBitrixComponent
{
    private ?object $user;
    // private ?string $documentRoot;

    private array $settingShema = [
        'notify' => [
            'email|im|message' => [
                'title' => 'Сообщения в личных чатах',
                'value' => 1,
            ],
            'email|im|chat' => [
                'title' => 'Сообщения в заказах',
                'value' => 1,
            ],
            'email|im|default' => [
                'title' => 'Прочие уведомления',
                'value' => 1,
            ],
            // 'email|im|like' => [
            //     'title' => 'Реакции на ваши сообщения',
            //     'value' => 1
            // ]
            // 'email|im|mention' => 'Вас упомянули в чате',
            // 'email|main|rating_vote' => 'Лайки в сообщениях и комментариях (мне нравится)',
            // 'email|main|rating_vote_mentioned' => 'Лайки в сообщениях, где вы упомянуты',
        ],
        'settings' => [
            'enableSound' => [
                'title' => 'Включить звуковые оповещения',
                'value' => 1,
            ]
        ]
    ];

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);
        
        // $this->documentRoot = $_SERVER["DOCUMENT_ROOT"];

        $this->user = CurrentUser::get();
        $this->arResult['userId'] = intval($this->user->getId());

        if($this->arResult['userId'] <= 0) {
            $this->arResult['error'][] = 'Нет редактирования';
        }
    }

    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function isError(): bool
    {
        if(!empty($this->arResult['error'])) {
            return true;
        }

        return false;
    }

    private function isEdit(): bool
    {
        if($this->arResult['edit'] == 'y') {
            return true;
        }

        return false;
    }

    private function getData()
    {
        $this->arResult['edit'] = htmlspecialcharsbx($this->request->getPost('edit'));

        foreach($this->settingShema as $type => $setting) {
            foreach($setting as $setg => $param) {
                if($this->request->getPost($setg) == 'on') {
                    $this->arResult['save'][$type][$setg] = 1;
                } else {
                    $this->arResult['save'][$type][$setg] = 0;
                }
            }
        }
    }

    private function getSetting()
    {
        $settings = \CIMSettings::Get($this->arResult['userId']);

        foreach($settings as $type => $setting) {
            foreach($setting as $setg => $value) {
                if(!empty($this->settingShema[$type][$setg])) {
                    $this->settingShema[$type][$setg]['value'] = $value;
                }
            }
        }

        $this->arResult = array_merge($this->arResult, $this->settingShema);
    }

    private function updateData()
    {
        $this->getSetting();
        $this->getData();
    }

    private function setSetting()
    {
        if(!empty($this->arResult['save'])) {
            foreach($this->arResult['save'] as $type => $setting) {
                \CIMSettings::SetSetting($type, $setting, $this->arResult['userId']);
            }

            $this->updateData();
        }
    }

    public function executeComponent()
    {
        if(!$this->isError()) {
            $this->updateData();
            
            if($this->isEdit()) {
                $this->setSetting();
            }

            $this->IncludeComponentTemplate();
        }
    }
}