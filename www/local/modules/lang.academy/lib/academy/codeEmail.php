<?
namespace Lang\Academy;

use Bitrix\Main\Loader;
use Bitrix\Main\Web\Uri;
use Bitrix\Main\UserTable;
use Bitrix\Main\Mail\Event;

Loader::includeModule('sale');
Loader::includeModule('im');

class CodeEmail
{
    private const int TIME_CODE = 900;
    private const int REPEAT_TIME_CODE = 120;
    private const int MESSAGE_ID = 135;
    private const string EVENT_EMAIL = "codeUserEvent";
    private const array SETTING_MESSAGE = [
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

    private ?object $user;
    private ?object $newUser;
    private ?int $userId;
    private ?string $email;
    private ?string $password;
    private ?string $code;
    private ?string $form;
    private ?string $typeForm;

    public ?array $result;
    private ?string $confirmCode;
    private ?string $newCode;
    private ?int $timeCodeCurrent;

    public function __construct(?array &$param)
    {
        $this->result =& $param;
        $this->userId = intval($param['userId']);
        $this->email = htmlspecialcharsbx($param['email']);
        $this->password = htmlspecialcharsbx($param['password']);
        $this->form = htmlspecialcharsbx($param['form']);
        $this->typeForm = htmlspecialcharsbx($param['typeForm']);
        $this->confirmCode = htmlspecialcharsbx($param['confirmCode']);
        $this->newCode = htmlspecialcharsbx($param['newCode']);
        
        if($this->isCode()) {
            $this->confirmCode();
        } elseif($this->isReg() || $this->isOrder()) {
            $this->addNewUser();
        }
    }

    private function isReg(): bool
    {
        if($this->form == 'reg') {
            return true;
        }

        return false;
    }

    private function isOrder(): bool
    {
        if($this->form == 'order') {
            return true;
        }

        return false;
    }

    private function isCode(): bool
    {
        if($this->form == 'code') {
            return true;
        }

        return false;
    }

    private function getCodeSms()
    {
        $this->code = random_int(100000, 999999);
    }

    private function setRegParam()
    {
        $this->result['reg'] = 'y';
        $this->result['regUrl'] = '/lk/';
    }

    private function setOrderParam()
    {
        $this->result['order'] = 'y';
        
        $uri = new Uri('/lk/order/add/');
        $uri->addParams([
            'name' => $this->result['name'],
            'type' => $this->result['type']
        ]);

        $this->result['orderUrl'] = $uri->getUri();
    }

    private function checkTime()
    {
        $currentTime = strtotime(date('d.m.Y H:i:s'));
        $this->code = $this->user->getUfCode();
        $this->result['dateCode'] = $this->user->getUfCodeData();
        $this->timeCodeCurrent = $currentTime - strtotime($this->result['dateCode']);


        $dayMillisec = strtotime('+'.self::REPEAT_TIME_CODE.' seconds', strtotime($this->result['dateCode']));
        $date = new \DateTime(date('Y-m-d H:i:s'));
        $date2 = new \DateTime(date('Y-m-d H:i:s', $dayMillisec));
        $dateDiff = $date->diff($date2);

        if(!empty($dateDiff) && empty($dateDiff->invert) && ((intval($dateDiff->i) > 0) || intval($dateDiff->s) > 0)) {
            $this->result['timeM'] = intval($dateDiff->i);
            $this->result['timeS'] = intval($dateDiff->s);
        }

        $this->result['timer'] = $dateDiff;
    }

    private function sendCode()
    {
        $this->newUser = new \CUser();
        $this->getCodeSms();

        $paramEmail = [
            'email' => $this->email,
            'code' => $this->code,
        ];

        Event::send([
            "EVENT_NAME" => self::EVENT_EMAIL,
            'MESSAGE_ID' => self::MESSAGE_ID,
            "LID" => "ru",
            "C_FIELDS" => $paramEmail,
        ]);

        $userCodeAuth = $this->newUser->Update($this->userId, [
            "UF_CODE" => $this->code,
            "UF_CODE_DATA" => date('d.m.Y H:i:s'),
        ]);

        $this->result['showFormCode'] = 'y';

        $this->user = UserTable::getList([
            'select' => ['ID', 'UF_CODE', 'UF_CODE_DATA'],
            'filter' => ['EMAIL' => $this->email]
        ])->fetchObject();

        $this->checkTime();
    }

    private function confirmCode()
    {
        $this->newUser = new \CUser();

        $this->user = UserTable::getList([
            'select' => ['ID', 'UF_CODE', 'UF_CODE_DATA'],
            'filter' => ['EMAIL' => $this->email]
        ])->fetchObject();

        if(!empty($this->user) && !empty($this->user->getId())) {
            $this->code = $this->user->getUfCode();
            $this->userId = $this->user->getId();

            $this->checkTime();

            if($this->timeCodeCurrent > self::REPEAT_TIME_CODE && $this->newCode == 'y') {
                $this->sendCode();
            } else {
                if($this->code == $this->confirmCode) {

                    if($this->typeForm == "reg") {
                        $this->setRegParam();
                    } elseif($this->typeForm == "order") {
                        $this->setOrderParam();
                    }

                    $this->newUser->Update($this->userId, ["ACTIVE" => "Y", "UF_CODE" => "", "UF_CODE_DATA" => ""]);
                    $this->newUser->Authorize($this->userId);

                    if(!\CSaleUserAccount::GetByUserID($this->userId, "RUB")) {
                        \CSaleUserAccount::Add([
                            "USER_ID" => $this->userId,
                            "CURRENCY" => "RUB",
                            "CURRENT_BUDGET" => 0
                        ]);
                    }

                    \CIMSettings::SetSetting(\CIMSettings::SETTINGS, self::SETTING_MESSAGE, $this->userId);

                } else {
                    $this->result['error'][] = 'Не верный код подтверждения';
                    $this->result['showFormCode'] = 'y';
                }
            }
        }
    }

    private function updateCode()
    {
        $this->newUser = new \CUser();
        $this->getCodeSms();

        $userCodeAuth = $this->newUser->Update($this->userId, [
            "UF_CODE" => $this->code,
            "UF_CODE_DATA" => date('d.m.Y H:i:s'),
        ]);
    }

    private function addNewUser()
    {
        $this->newUser = new \CUser();
        $this->user = UserTable::getList([
            'select' => ['ID', 'ACTIVE', 'UF_CODE', 'UF_CODE_DATA'],
            'filter' => ['EMAIL' => $this->email]
        ])->fetchObject();

        if(!empty($this->user) && !empty($this->user->getId())) {
            if(!$this->user->getActive()) {
                $this->checkTime();
                $this->userId = $this->user->getId();

                if(($this->timeCodeCurrent > self::TIME_CODE) || (empty($this->user->getUfCode()))) {
                    $this->sendCode();
                } elseif($this->timeCodeCurrent > self::REPEAT_TIME_CODE && $this->newCode == 'y') {
                    $this->sendCode();
                }

                $this->result['showFormCode'] = 'y';
            } else {
                $this->result['showFormCode'] = 'n';
                $this->result['error'][] = "Пользователь с таким email уже зарегистрирован. Попробуйте восстановить пароль или зарегистрироваться под другим email";
            }
        } else {

            if($this->isOrder()) {
                $this->password = random_int(1000000000, 9999999999);
            }

            $idLogin = rand();
            $name = "user{$idLogin}";
            $field = [
                "LID" => "ru",
                "ACTIVE" => "N",
                "GROUP_ID" => [5,6],
                "EMAIL" => $this->email,
                "NAME" => $name,
                "LOGIN" => $name,
                "PASSWORD" => $this->password,
                "CONFIRM_PASSWORD" => $this->password,
                "WORK_POSITION" => "Пользователь",
            ];

            $ID = $this->newUser->Add($field);

            if($ID > 0) {
                $this->userId = $ID;
                $name = "user{$this->userId}";

                $this->newUser->Update($this->userId, ["LOGIN" => $name, 'NAME' => $name]);
                $this->sendCode();
            } else {
                $this->result['showFormCode'] = 'n';
                $this->result['error'][] = $this->newUser->LAST_ERROR;
            }
        }
    }
}