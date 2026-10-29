<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\Mail\Event;
use Lang\Main\Iblock\Util as Data;

class LangOrderAdd extends CBitrixComponent
{
    private int $timeCode = 900;
    private string $eventMail = "codeUserEvent";
    private int $messageId = 135;

    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function getTypeWork()
    {
        Data::getElementsObject("LangLkTypeWork", ['select' => ['NAME']])
            ?->walk(function($type) {
                $this->arResult['type'][] = [
                    'id' => $type->getId(),
                    'name' => $type->getName()
                ];
            });
    }

    private function isSubmit() : bool
    {
        $request = Context::getCurrent()->getRequest();

        $this->arResult['post'] = $request->isPost() ? 'Y' : 'N';

        return ($request->isPost() && (!empty($request->getPost('email'))  || !empty($request->getPost("confirmCode")))) ? true : false;
    }

    private function getField()
    {
        $request = Context::getCurrent()->getRequest();

        if($request->getPost("order") == 'y') {
            $this->arResult['form'] = "order";
        } elseif($request->getPost("code") == 'y') {
            $this->arResult['form'] = "code";
        }

        if($this->arResult['form'] == "order") {
            $this->arResult['name'] = htmlspecialcharsbx($request->getPost('name'));
            $this->arResult['type'] = intval($request->getPost('type'));
            $this->arResult['email'] = htmlspecialcharsbx($request->getPost('email'));

            if(empty($this->arResult['name'])) {
                $this->arResult['error'][] = 'Напишите тему работы';
            }

            if($this->arResult['type'] <= 0) {
                $this->arResult['error'][] = 'Выбирите тип работы';
            }

            if(empty($this->arResult['email'])) {
                $this->arResult['error'][] = 'Введите email';
            } else {
                if(!check_email($this->arResult['email'])) {
                    $this->arResult['error'][] = "Не верный формат Email";
                }
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
            } else {
                $this->arResult['error'][] = 'Не верный код подтверждения';
                $this->arResult['template'] = "code";
                $this->arResult['showFormCode'] = 'y';
            }
        }
    }

    public function executeComponent()
    {
        $this->arResult['error'] = [];

        $this->getTypeWork();

        if($this->isSubmit()) {
            $this->getField();

            if(empty($this->arResult['error'])) {
                // $this->sendPassword();
            }
        }

        $this->IncludeComponentTemplate();
    }
}