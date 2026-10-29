<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\Mail\Event;

Loader::includeModule('sale');

class LangReg extends CBitrixComponent
{
    private int $timeCode = 900;
    private string $eventMail = "codeUserEvent";
    private int $messageId = 135;
    private array $settingMessage = [
        'sendByEnter' => 'Y',
        'privacyMessage' => 'all',
        'privacyCall' => 'all',
        'privacyChat' => 'all',
        'privacySearch' => 'all',
        'enableDarkTheme' => 'light',
        'notifyScheme' => 'expert',
        'notifyAutoRead' => false,
        'viewBirthday' => false,
        'notifySchemeSendSite' => true,
        'notifySchemeSendEmail' => true
    ];

    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    public function isSubmit() : bool
    {
        $request = Context::getCurrent()->getRequest();

        $this->arResult['post'] = $request->isPost() ? 'Y' : 'N';

        return ($request->isPost() && (!empty($request->getPost('email')) || !empty($request->getPost("confirmCode")))) ? true : false;
    }

    private function prepareData()
    {
        $request = Context::getCurrent()->getRequest();

        if(!empty($request->getPost("reg"))) {
            $this->arResult['form'] = "reg";
        } elseif(!empty($request->getPost("code"))) {
            $this->arResult['form'] = "code";
        }

        if($this->arResult['form'] == "reg") {
            $this->arResult['email'] = htmlspecialcharsbx($request->getPost("email"));
            $this->arResult['password'] = htmlspecialcharsbx($request->getPost("password"));

            if(empty($this->arResult['email'])) {
                $this->arResult['error'][] = "Поле Email является обязательным";
            } else {
                if(!check_email($this->arResult['email'])) {
                    $this->arResult['error'][] = "Не верный формат Email";
                }
            }

            if(empty($this->arResult['password'])) {
                $this->arResult['error'][] = "Поле Пароль является обязательным";
            }
        } elseif($this->arResult['form'] == "code") {
            $this->arResult['confirmCode'] = htmlspecialcharsbx($request->getPost('confirmCode'));
            $this->arResult['email'] = htmlspecialcharsbx($request->getPost("email"));

            if(empty($this->arResult['confirmCode'])) {
                $this->arResult['error'][] = 'Код подтверждения не указан';
            }
        }
    }

    private function getCodeSms()
    {
        $this->arResult["code"] = random_int(100000, 999999); // mb_strtoupper(substr(md5(uniqid(rand(), true)), 0, 6));
    }

    public function setRegParam()
    {
        $this->arResult['reg'] = 'y';
        $this->arResult['regUrl'] = '/lk/';
    }

    private function updateCode()
    {
        $user = new CUser();
        $this->getCodeSms();

        $userCodeAuth = $user->Update($this->arResult['userId'], [
            "UF_CODE" => $this->arResult["code"],
            "UF_CODE_DATA" => date('d.m.Y H:i:s'),
        ]);
    }

    private function sendCode()
    {
        $user = new CUser();
        $this->getCodeSms();

        $paramEmail = [
            'email' => $this->arResult['email'],
            'code' => $this->arResult["code"],
        ];

        Event::send([
            "EVENT_NAME" => $this->eventMail,
            'MESSAGE_ID' => $this->messageId,
            "LID" => "ru",
            "C_FIELDS" => $paramEmail,
        ]);

        $userCodeAuth = $user->Update($this->arResult['userId'], [
            "UF_CODE" => $this->arResult["code"],
            "UF_CODE_DATA" => date('d.m.Y H:i:s'),
        ]);

        $this->arResult['template'] = "code";
        $this->arResult['showFormCode'] = 'y';
    }

    private function confirmCode()
    {
        $user = new CUser();
        $isUser = UserTable::getList([
            'select' => ['ID', 'UF_CODE', 'UF_CODE_DATA'],
            'filter' => ['EMAIL' => $this->arResult['email']]
        ])->fetchObject();

        if(!empty($isUser) && !empty($isUser->getId())) {
            $this->arResult['code'] = $isUser->getUfCode();
            $this->arResult['userId'] = $isUser->getId();

            if($this->arResult['code'] == $this->arResult['confirmCode']) {
                $this->setRegParam();
                $user->Update($this->arResult['userId'], ["ACTIVE" => "Y", "UF_CODE" => "", "UF_CODE_DATA" => ""]);
                $user->Authorize($this->arResult['userId']);

                if(!\CSaleUserAccount::GetByUserID($this->arResult['userId'], "RUB")) {
                    \CSaleUserAccount::Add([
                        "USER_ID" => $this->arResult['userId'],
                        "CURRENCY" => "RUB",
                        "CURRENT_BUDGET" => 0
                    ]);
                }

                \CIMSettings::SetSetting(\CIMSettings::SETTINGS, $this->settingMessage, $this->arResult['userId']);

            } else {
                $this->arResult['error'][] = 'Не верный код подтверждения';
                $this->arResult['template'] = "code";
                $this->arResult['showFormCode'] = 'y';
            }
        }
    }

    private function addNewUser()
    {
        $user = new CUser();
        $isUser = UserTable::getList([
            'select' => ['ID', 'ACTIVE', 'UF_CODE', 'UF_CODE_DATA'],
            'filter' => ['EMAIL' => $this->arResult['email']]
        ])->fetchObject();

        if(!empty($isUser) && !empty($isUser->getId())) {
            // Показать форму кода верификации
            // Проверить время отправки кода
            if(!$isUser->getActive()) {
                $currentTime = strtotime(date('d.m.Y H:i:s'));
                $this->arResult['code'] = $isUser->getUfCode();
                $this->arResult['dateCode'] = $isUser->getUfCodeData();
                $timeCodeCurrent = $currentTime - strtotime($this->arResult["dateCode"]);
                $this->arResult['userId'] = $isUser->getId();

                if(($timeCodeCurrent > $this->timeCode) || (empty($isUser->getUfCode()))) {
                    $this->sendCode();
                } elseif($timeCodeCurrent > 60 && $this->arResult['newCode'] == 'y') {
                    $this->sendCode();
                }

                $this->arResult['showFormCode'] = 'y';
                $this->arResult['template'] = "code";
            } else {
                $this->arResult['error'][] = "Пользователь с таким email уже зарегистрирован. Попробуйте восстановить пароль или зарегистрироваться под другим email";
            }
        } else {
            $name = "user{$idLogin}";
            $idLogin = rand();
            $field = [
                "LID" => "ru",
                "ACTIVE" => "N",
                "GROUP_ID" => [5,6],
                "EMAIL" => $this->arResult['email'],
                "NAME" => $name,
                "LOGIN" => $name,
                "PASSWORD" => $this->arResult['password'],
                "CONFIRM_PASSWORD" => $this->arResult['password'],
                "WORK_POSITION" => "Пользователь",
            ];

            $ID = $user->Add($field);

            if($ID > 0) {
                $this->arResult['userId'] = $ID;
                $name = "user{$this->arResult['userId']}";

                $user->Update($this->arResult['userId'], ["LOGIN" => $name, 'NAME' => $name]);
                $this->sendCode();
            } else {
                $arResult["error"][] = $user->LAST_ERROR;
            }
        }
    }

    // confirmCode
    public function executeComponent()
    {
        $this->arResult['error'] = [];
        $this->arResult['template'] = "";

        if($this->isSubmit()) {
            $this->prepareData();

            if(empty($this->arResult['error'])) {
                if($this->arResult['form'] == 'reg') {
                    $this->addNewUser();
                } elseif($this->arResult['form'] == 'code') {
                    $this->confirmCode();
                }
            }
        }

        if($this->arResult['showFormCode'] == 'y') {
            $this->includeComponentTemplate('code');
        } else {
            $this->includeComponentTemplate();
        }

        // echo '<pre>', print_r($this->arResult, true), '</pre>';
    }
}