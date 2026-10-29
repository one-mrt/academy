<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

global $USER_FIELD_MANAGER;

use Bitrix\Main\Context;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\UserPhoneAuthTable;
use Bitrix\Main\Controller\PhoneAuth;

class LangProfileEdit extends CBitrixComponent
{
    private object $user;
    private int $userId;
    private object $newUser;
    private object $request;
    private array $arEditFields = [
        "TITLE",
        "NAME",
        "LAST_NAME",
        "SECOND_NAME",
        "EMAIL",
        "LOGIN",
        "PERSONAL_PROFESSION",
        "PERSONAL_WWW",
        "PERSONAL_ICQ",
        "PERSONAL_GENDER",
        "PERSONAL_BIRTHDAY",
        "PERSONAL_PHONE",
        "PERSONAL_FAX",
        "PERSONAL_MOBILE",
        "PERSONAL_PAGER",
        "PERSONAL_STREET",
        "PERSONAL_MAILBOX",
        "PERSONAL_CITY",
        "PERSONAL_STATE",
        "PERSONAL_ZIP",
        "PERSONAL_COUNTRY",
        "PERSONAL_NOTES",
        "WORK_COMPANY",
        "WORK_DEPARTMENT",
        "WORK_POSITION",
        "WORK_WWW",
        "WORK_PHONE",
        "WORK_FAX",
        "WORK_PAGER",
        "WORK_STREET",
        "WORK_MAILBOX",
        "WORK_CITY",
        "WORK_STATE",
        "WORK_ZIP",
        "WORK_COUNTRY",
        "WORK_PROFILE",
        "WORK_NOTES",
        "TIME_ZONE",
        "PHONE_NUMBER",
    ];

    public function __construct($component = null)
	{
		parent::__construct($component);

        $this->request = Context::getCurrent()->getRequest();
		$this->user = CurrentUser::get();

		$this->userId = $this->user->getId();
        $this->newUser = new \CUser;

		Loc::loadMessages(__FILE__);
	}

    public function onPrepareComponentParams($arParam)
    {
        $arParams['SEND_INFO'] = isset($arParams['SEND_INFO']) && $arParams['SEND_INFO'] === 'Y' ? 'Y' : 'N';
        $arParams['CHECK_RIGHTS'] = isset($arParams['CHECK_RIGHTS']) && $arParams['CHECK_RIGHTS'] === 'Y' ? 'Y' : 'N';
        $arParams['EDITABLE_EXTERNAL_AUTH_ID'] = isset($arParams['EDITABLE_EXTERNAL_AUTH_ID']) && is_array($arParams['EDITABLE_EXTERNAL_AUTH_ID'])
	        ? $arParams['EDITABLE_EXTERNAL_AUTH_ID'] : [];

        return $arParam;
    }

    private function isPermission(): bool
    {

    }

    private function isSaveForm(): bool
    {
        return (!empty($this->request->isPost()) &&
            ($this->request->getPost('save') <> '' || $this->request->getPost('apply') <> '') &&
            check_bitrix_sessid());
    }

    private function initFormData()
    {
        $this->arResult["ID"] = intval($this->userId);
        $this->arResult["GROUP_POLICY"] = \CUser::GetGroupPolicy($this->arResult["ID"]);

        if(!($this->arParams['CHECK_RIGHTS'] == 'N' || $USER->CanDoOperation('edit_own_profile')) || $this->arResult["ID"] <= 0) {
            $this->arResult['error'][] = "Вы не авторизованы";
        }
    }

    private function encryptedUserPassword()
    {
        if(COption::GetOptionString('main', 'use_encrypted_auth', 'N') == 'Y') {
            $sec = new CRsaSecurity();

            if(($arKeys = $sec->LoadKeys())) {
                $sec->SetKeys($arKeys);
                $errno = $sec->AcceptFromForm(['NEW_PASSWORD', 'NEW_PASSWORD_CONFIRM']);

                if($errno == CRsaSecurity::ERROR_SESS_CHECK)
                    $this->arResult['error'][] = Loc::getMessage("main_profile_sess_expired");
                elseif($errno < 0)
                    $this->arResult['error'][] = Loc::getMessage("main_profile_decode_err", ["#ERRCODE#"=>$errno]);
            }
        }
    }

    private function prepareFieldsRequestData()
    {
        $this->arResult["PHONE_REGISTRATION"] = (\COption::GetOptionString("main", "new_user_phone_auth", "N") == "Y");
        $this->arResult["PHONE_REQUIRED"] = ($this->arResult["PHONE_REGISTRATION"] && \COption::GetOptionString("main", "new_user_phone_required", "N") == "Y");
        $this->arResult["EMAIL_REGISTRATION"] = (\COption::GetOptionString("main", "new_user_email_auth", "Y") <> "N");
        $this->arResult["EMAIL_REQUIRED"] = ($this->arResult["EMAIL_REGISTRATION"] && \COption::GetOptionString("main", "new_user_email_required", "Y") <> "N");
        $this->arResult["PHONE_CODE_RESEND_INTERVAL"] = \CUser::PHONE_CODE_RESEND_INTERVAL;
        
        if($this->isSaveForm()) {
            $this->encryptedUserPassword();

            if(empty($this->arResult['error'])) {
                $obUser = new \CUser;
                $arPERSONAL_PHOTO = $_FILES["PERSONAL_PHOTO"];
                $arWORK_LOGO = $_FILES["WORK_LOGO"];

                $rsUser = \CUser::GetByID($this->arResult["ID"]);
                $arUser = $rsUser->Fetch();

                if($arUser) {
                    $arPERSONAL_PHOTO["old_file"] = $arUser["PERSONAL_PHOTO"];
                    $arPERSONAL_PHOTO["del"] = $_REQUEST["PERSONAL_PHOTO_del"];

                    $arWORK_LOGO["old_file"] = $arUser["WORK_LOGO"];
                    $arWORK_LOGO["del"] = $_REQUEST["WORK_LOGO_del"];
                }

                $arFields = [];

                foreach($this->arEditFields as $field) {
                    if(isset($this->request->getPost[$field])) {
                        $arFields[$field] = $_REQUEST[$field];
                    }
                }

                if(isset($this->request->getPost("AUTO_TIME_ZONE"))) {
                    $arFields["AUTO_TIME_ZONE"] = ($this->request->getPost("AUTO_TIME_ZONE") == "Y" || $this->request->getPost("AUTO_TIME_ZONE") == "N" ? $this->request->getPost("AUTO_TIME_ZONE") : "");
                }

                if($this->user->isAdmin() && isset($this->request->getPost("ADMIN_NOTES"))) {
                    $arFields["ADMIN_NOTES"] = $this->request->getPost("ADMIN_NOTES");
                }

                $this->arResult['CAN_EDIT_PASSWORD'] = $arUser['EXTERNAL_AUTH_ID'] == ''
                    || in_array($arUser['EXTERNAL_AUTH_ID'], $this->arParams['EDITABLE_EXTERNAL_AUTH_ID'], true);

                if($this->request->getPost("NEW_PASSWORD") <> '' && $this->arResult['CAN_EDIT_PASSWORD']) {
                    $arFields["PASSWORD"] = $this->request->getPost("NEW_PASSWORD");
                    $arFields["CONFIRM_PASSWORD"] = $this->request->getPost("NEW_PASSWORD_CONFIRM");
                }

                $arFields["PERSONAL_PHOTO"] = $arPERSONAL_PHOTO;
                $arFields["WORK_LOGO"] = $arWORK_LOGO;

                if($arUser) {
                    if($arUser['EXTERNAL_AUTH_ID'] <> '') {
                        $arFields['EXTERNAL_AUTH_ID'] = $arUser['EXTERNAL_AUTH_ID'];
                    }
                }

                $USER_FIELD_MANAGER->EditFormAddFields("USER", $arFields);

                if($this->newUser->Update($this->arResult["ID"], $arFields)) {
                    if($this->arResult["PHONE_REGISTRATION"] == true && $arFields["PHONE_NUMBER"] <> '') {
                        if(!($phone = UserPhoneAuthTable::getRowById($this->arResult["ID"]))) {
                            $phone = ["PHONE_NUMBER" => "", "CONFIRMED" => "N"];
                        }

                        $arFields["PHONE_NUMBER"] = UserPhoneAuthTable::normalizePhoneNumber($arFields["PHONE_NUMBER"]);

                        if($arFields["PHONE_NUMBER"] <> $phone["PHONE_NUMBER"] || $phone["CONFIRMED"] <> 'Y') {
                            list($code, $phoneNumber) = CUser::GeneratePhoneCode($this->arResult["ID"]);

                            $sms = new \Bitrix\Main\Sms\Event(
                                "SMS_USER_CONFIRM_NUMBER",
                                [
                                    "USER_PHONE" => $phoneNumber,
                                    "CODE" => $code,
                                ]
                            );

                            $smsResult = $sms->send(true);

                            if(!$smsResult->isSuccess()) {
                                foreach($smsResult->getErrorMessages() as $error) {
                                    $this->arResult['error'][] = $error;
                                }
                            }

                            $this->arResult["SHOW_SMS_FIELD"] = true;
                            $this->arResult["SIGNED_DATA"] = PhoneAuth::signData(['phoneNumber' => $phoneNumber]);
                        }
                    }

                    if(empty($this->arResult['error'])) {
                        if($this->arParams['SEND_INFO'] == 'Y') {
                            $this->newUser->SendUserInfo($this->arResult["ID"], SITE_ID, GetMessage("main_profile_update"), true);
                        }

                        $this->arResult['userSave'] = 'y';
                    }

                } else {
                    $this->arResult['error'][] = $obUser->LAST_ERROR;
                }
            }
        }
    }

    public function executeComponent()
    {
        $this->initFormData();

        if(empty($this->arResult['error'])) {
            $this->prepareFieldsRequestData();
        }
    }
}