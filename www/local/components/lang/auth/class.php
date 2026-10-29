<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Server;
use Bitrix\Main\Context;
use Bitrix\Main\Web\Uri;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Application;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\UserPhoneAuthTable;
use \Bitrix\Iblock\ORM\PropertyValue;
use Bitrix\Main\Text\HtmlFilter;
use Bitrix\Main\UserTable;

class LangAuth extends CBitrixComponent
{
    public function onPrepareComponentParams($arParams)
    {
        return $arParams;
    }

    public function prepareData()
    {
        $request = Context::getCurrent()->getRequest();
        $this->arResult['login'] = htmlspecialcharsbx($request->getPost("email"));
        $this->arResult['pass'] = htmlspecialcharsbx($request->getPost("password"));

        if(empty($this->arResult['login'])) {
            $this->arResult['error'][] = 'Введите почту';
        }  else {
            if(!check_email($this->arResult['login'])) {
                $this->arResult['error'][] = "Не верный формат Email";
            }
        }

        if(empty( $this->arResult['pass'])) {
            $this->arResult ['error'][] = 'Пароль не может быть пустым';
        }
    }

    public function isSubmit() : bool
    {
        $request = Context::getCurrent()->getRequest();

        $this->arResult['post'] = $request->isPost() ? 'Y' : 'N';

        return ($request->isPost() && !empty($request->getPost('email'))) ? true : false;
    }

    public function setAuthParam()
    {
        $this->arResult['auth'] = 'y';
        $this->arResult['authUrl'] = '/lk/';
    }

    public function executeComponent()
    {
        $user = new \CUser;
        $request = Context::getCurrent()->getRequest();

        if(!$user->IsAuthorized()) {

            if($this->isSubmit()) {
                if(check_bitrix_sessid()) {
                    $this->prepareData();

                    if(empty($this->arResult['error'])) {
                        $isUser = UserTable::getList([
                            'select' => ['ID', 'LOGIN'],
                            'filter' => ['EMAIL' => $this->arResult['login']]
                        ])->fetchObject();

                        if(!empty($isUser) && !empty($isUser->getId())) {
                            $auth = $user->Login($isUser->getLogin(),  $this->arResult['pass'], 'Y');

                            if($auth === true) {
                                $this->setAuthParam();
                            } else {
                                $this->arResult['error'][] = $auth['MESSAGE'];
                            }
                        } else {
                            $this->arResult['error'][] = "Неверный email или пароль";
                        }
                    }
                } else {
                    $this->arResult["error"][] = "Перезагрузите страницу и попробуйте еще раз";
                }
            }

            $this->IncludeComponentTemplate();
        } else {
            $this->setAuthParam();
        }
    }

}