<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\UserTable;
use Bitrix\Main\Mail\Event;

use Lang\Academy\CodeEmail;

Loader::includeModule('sale');

class LangReg extends CBitrixComponent
{
    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    public function isSubmit() : bool
    {
        $this->arResult['post'] = $this->request->isPost() ? 'y' : 'n';

        return ($this->request->isPost() && (!empty($this->request->getPost('email')) || !empty($this->request->getPost("confirmCode")))) ? true : false;
    }

    private function prepareData()
    {
        $this->arResult['email'] = htmlspecialcharsbx($this->request->getPost("email"));
        $this->arResult['password'] = htmlspecialcharsbx($this->request->getPost("password"));
        $this->arResult['confirmCode'] = htmlspecialcharsbx($this->request->getPost('confirmCode'));
        $this->arResult['newCode'] = htmlspecialcharsbx($this->request->getPost('newCode'));
        $this->arResult['typeForm'] = "reg";
        $this->arResult['showFormCode'] = 'y';

        if($this->request->getPost("reg") == 'y') {
            $this->arResult['form'] = "reg";
        } elseif($this->request->getPost("code") == 'y') {
            $this->arResult['form'] = "code";
        }

        if(empty($this->arResult['email'])) {
            $this->arResult['error'][] = "Поле Email является обязательным";
        } else {
            if(!check_email($this->arResult['email'])) {
                $this->arResult['error'][] = "Не верный формат Email";
            }
        }

        if($this->arResult['form'] == "reg" && empty($this->arResult['password'])) {
            $this->arResult['error'][] = "Поле Пароль является обязательным";
        }
    }

    public function executeComponent()
    {
        if($this->isSubmit()) {
            if(check_bitrix_sessid()) {
                $this->prepareData();

                if(empty($this->arResult['error'])) {
                    new CodeEmail($this->arResult);
                }
            } else {
                $this->arResult["error"][] = "Перезагрузите страницу и попробуйте еще раз";
            }
        }

        if($this->arResult['showFormCode'] == 'y') {
            $this->includeComponentTemplate('code');
        } else {
            $this->includeComponentTemplate();
        }
    }
}