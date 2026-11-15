<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Lang\Main\Iblock\Util as Data;

use Lang\Academy\CodeEmail;

Loader::includeModule('sale');

class LangOrderAdd extends CBitrixComponent
{
    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function getTypeWork()
    {
        Data::getElementsObject("LangLkTypeWork", ['select' => ['NAME']])
            ?->walk(function($type) {
                $this->arResult['typeWork'][] = [
                    'id' => $type->getId(),
                    'name' => $type->getName()
                ];
            });
    }

    private function isSubmit() : bool
    {
        $this->arResult['post'] = $this->request->isPost() ? 'y' : 'n';

        return ($this->request->isPost() && (!empty($this->request->getPost('email'))  || !empty($this->request->getPost("confirmCode")))) ? true : false;
    }

    private function getField()
    {
        $this->arResult['name'] = htmlspecialcharsbx($this->request->getPost('name'));
        $this->arResult['type'] = intval($this->request->getPost('type'));
        $this->arResult['email'] = htmlspecialcharsbx($this->request->getPost('email'));
        $this->arResult['confirmCode'] = htmlspecialcharsbx($this->request->getPost('confirmCode'));
        $this->arResult['newCode'] = htmlspecialcharsbx($this->request->getPost('newCode'));
        $this->arResult['showFormCode'] = 'y';
        $this->arResult['typeV'] = htmlspecialcharsbx($this->request->getPost('typeV'));
        $this->arResult['typeForm'] = "order";

        if($this->request->getPost("order") == 'y') {
            $this->arResult['form'] = "order";
        } elseif($this->request->getPost("code") == 'y') {
            $this->arResult['form'] = "code";

        }

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
    }

    public function executeComponent()
    {
        $this->getTypeWork();

        if($this->isSubmit()) {
            if(check_bitrix_sessid()) {
                $this->getField();

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