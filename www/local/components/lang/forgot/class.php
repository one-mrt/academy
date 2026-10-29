<?if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Context;
use Bitrix\Main\UserTable;

class LangForgot extends CBitrixComponent
{
	public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

	private function isSubmit() : bool
    {
        $request = Context::getCurrent()->getRequest();

        $this->arResult['post'] = $request->isPost() ? 'Y' : 'N';

        return ($request->isPost() && (!empty($request->getPost('email')))) ? true : false;
    }

    private function prepareData()
    {
        $request = Context::getCurrent()->getRequest();

		$this->arResult['email'] = htmlspecialcharsbx($request->getPost("email"));

		if(empty($this->arResult['email'])) {
			$this->arResult['error'][] = "Поле Email является обязательным";
		} else {
			if(!check_email($this->arResult['email'])) {
				$this->arResult['error'][] = "Не верный формат Email";
			}
		}
    }

	private function sendPassword()
	{
		$newUser = new \CUser();
		$user = UserTable::getList([
			'select' => ['ID', 'LOGIN'],
			'filter' => ['EMAIL' => $this->arResult['email']]
		])->fetchObject();

		if(!empty($user) && !empty($user->getId())) {

			$this->arResult['login'] = $user->getLogin();
			$sendPass = $newUser::SendPassword($this->arResult['login'], $this->arResult['email'], "ru");

			if(!empty($sendPass) && $sendPass['TYPE'] == 'OK') {
				$this->arResult['success'] = $sendPass['MESSAGE'];
			} else {
				$this->arResult['error'][] = $newUser->LAST_ERROR;
			}
		}
		
	}

	public function executeComponent()
	{
		if($this->isSubmit()) {
            $this->prepareData();

            if(empty($this->arResult['error'])) {
                $this->sendPassword();
            }
        }

		$this->IncludeComponentTemplate();
	}
}