<?php
if(!defined("B_PROLOG_INCLUDED")||B_PROLOG_INCLUDED!==true)die();


date_default_timezone_set('Etc/GMT-5');
/**
 * Bitrix vars
 *
 * @var array $arParams
 * @var array $arResult
 * @var CBitrixComponent $this
 * @global CMain $APPLICATION
 * @global CUser $USER
 */
use Bitrix\Main\Config\Option;

global $USER;

use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;

$request = Application::getInstance()->getContext()->getRequest();
$uriString = $request->getRequestUri();

Loader::includeModule('iblock');

$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);

if($arParams["EVENT_NAME"] == '')
{
	$arParams["EVENT_NAME"] = "FEEDBACK_FORM";
}

$arParams["EMAIL_TO"] = trim($arParams["EMAIL_TO"]);

if($arParams["EMAIL_TO"] == '')
{
	$arParams["EMAIL_TO"] = COption::GetOptionString("main", "email_from");
}

$arParams["OK_TEXT"] = trim($arParams["OK_TEXT"]);

if($arParams["OK_TEXT"] == '')
{
    $arParams["OK_TEXT"] = Loc::getMessage("MF_OK_MESSAGE");
}

if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["submit"] <> '' && (!isset($_REQUEST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_REQUEST["PARAMS_HASH"]))
{
    $arResult["ERROR_MESSAGE"] = array();

	if(check_bitrix_sessid())
	{
		if(empty($arParams["REQUIRED_FIELDS"]) || !in_array("NONE", $arParams["REQUIRED_FIELDS"]))
		{
			// if((empty($arParams["REQUIRED_FIELDS"]) || in_array("COMPANY", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_company"]) <= 1)
			// 	$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_COMPANY");	
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])) && strlen($request->get("user_name")) <= 1)
				$arResult["ERROR_MESSAGE"][] = Loc::getMessage("MF_REQ_NAME");		
			// if((empty($arParams["REQUIRED_FIELDS"]) || in_array("POSITION", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_position"]) <= 1)
			// 	$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_POSITION");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])) && strlen($request->get("user_email")) <= 1)
				$arResult["ERROR_MESSAGE"][] = Loc::getMessage("MF_REQ_EMAIL");

            $phoneNurmal = \Bitrix\Main\UserPhoneAuthTable::normalizePhoneNumber($request->get("user_phone"), 'RU');
            $phoneNurmal = preg_replace('/[^0-9]/', '', $phoneNurmal);

            if((empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])) && strlen($phoneNurmal) < 11)
            {
				$arResult["ERROR_MESSAGE"][] = Loc::getMessage("MF_REQ_PHONE");
            }

			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["MESSAGE"]) <= 3)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_MESSAGE");
		}
		if(strlen($_POST["user_email"]) > 1 && !check_email($_POST["user_email"]))
            $arResult["ERROR_MESSAGE"][] = GetMessage("MF_EMAIL_NOT_VALID");


        if($arParams['REQUIRED_FIELDS']['file'] == 'Y')
        {
            if($_FILES['file']['error'] == '1')
            {
                $arResult["ERROR_MESSAGE"][] = 'Ошибка загрузки файла';
            }

            if($_FILES['file']['size'] >= 2097152)
            {
                $arResult["ERROR_MESSAGE"][] = 'Превышен размер файла в 2Mb';
            }

            if($_FILES['file']['type'] != 'application/pdf')
            {
                $arResult["ERROR_MESSAGE"][] = 'Формат файла не соответствует PDF';
            }
        }
            
		if(empty($arResult["ERROR_MESSAGE"]))
        {
            function getCaptcha($SecretKey)
            {
                $Response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfzvL4ZAAAAAAzg64E3YY1drHnRCabtOPE6IgML&response=$SecretKey");
                $Return = json_decode($Response);

                return $Return;
            }

            if ($arParams['GOOGLE_RECAPTCHA'] == 'Y')
            {
                $Return = getCaptcha($_REQUEST["g-recaptcha-response"]);
            }
            else
            {
                $Return = (object) ['success'=>true,'scope'=>0.5];
            }

            $CUR_URL = 'http' . ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') ? 's' : '') . '://';

            if(!empty($_SERVER["HTTP_REFERER"]))
            {
                $CUR_URL = $_SERVER["HTTP_REFERER"];
            }
            
            if(empty($_SERVER["HTTP_REFERER"]))
            {
                $CUR_URL = $CUR_URL . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
            }

            if($arParams["PAGE"])
            {
                $CUR_URL = $arParams["PAGE"];
            }
            
            if(empty($_SERVER["HTTP_REFERER"]))
            {
                if($_SERVER["HTTPS"] == 'On') $http = 'https://'; else $http = 'http://';
            }

            $ref_page = $CUR_URL;
            $utm_soruce = stristr($ref_page, '?');
            $positionUTM = strpos($ref_page, "?");
            $ref_page = substr($ref_page, 0, $positionUTM);

            if(!empty($utm_soruce))
            {
                $utm_soruce = $utm_soruce;
            }
            else
            {
                $utm_soruce = 'Без меток';
            }
            
            /*if($Return->success == true && $Return->score >= 0.1)
            {*/
                $el = new CIBlockElement;
                $arCrmField = [];

                $arProperties = [
                    "phone" => $request->get("user_phone"),
                    "page" => $CUR_URL,
                    'utm' => $utm_soruce,
                ];

                if(!empty($request->get('user_email')))
                {
                    $arProperties['email'] = $request->get('user_email');
                }

                if(!empty($request->get('company')))
                {
                    $arProperties['company'] = $request->get('company');
                }

                if(!empty($request->get('product')))
                {
                    $arProperties['product'] = $request->get('product');
                }

                if(!empty($request->get('m')))
                {
                    $arProperties['m'] = $request->get('m');
                }

                if(!empty($request->get('mode')))
                {
                    $arProperties['mode'] = ['VALUE' => $request->get('mode')];
                }

                if(!empty($request->get('napr')))
                {
                    $arProperties['napr'] = ['VALUE' => $request->get('napr')];
                }

                if(!empty($request->get('exe')))
                {
                    $arProperties['exe'] = ['VALUE' => $request->get('exe')];
                }

                if(!empty($request->get('pro')))
                {
                    $arProperties['pro'] = ['VALUE' => $request->get('pro')];
                }

                if(!empty($request->get('naz')))
                {
                    $arProperties['naz'] = ['VALUE' => $request->get('naz')];
                }

                if(!empty($request->get('naz')))
                {
                    $arProperties['naz'] = ['VALUE' => $request->get('naz')];
                }

                if(!empty($request->get('vrnt')))
                {
                    $arProperties['vrnt'] = ['VALUE' => $request->get('vrnt')];
                }

                if(!empty($request->get('var')))
                {
                    $arProperties['var'] = ['VALUE' => $request->get('var')];
                }

                if(!empty($request->get('opt')))
                {
                    $arProperties['opt'] = ['VALUE' => $request->get('opt')];
                }

                if(!empty($request->get('cat')))
                {
                    $arProperties['cat'] = ['VALUE' => $request->get('cat')];
                }

                if(!empty($request->get('dt')))
                {
                    $arProperties['dt'] = ['VALUE' => ['TEXT' => $request->get('dt'), "TYPE" => "text"]];
                }

                // Array ("TEXT" => "значение", "TYPE" => "html или text")

                if (!empty($_FILES['file']['tmp_name']))
                {
                    $fileName = $_FILES['file']['name'];
                    $fileUploadDir = "{$_SERVER['DOCUMENT_ROOT']}/upload/vacancy";
                    $linkFile = move_uploaded_file($_FILES['file']['tmp_name'], "{$fileUploadDir}/{$fileName}");
                    $linkFile = 'https://' . SITE_SERVER_NAME . "/upload/vacancy/{$fileName}";
                    $arProperties['file'] = CFile::MakeFileArray("{$fileUploadDir}/{$fileName}");
                    $arCrmField["UF_CRM_1720442596105"] = ["fileData" => [$fileName, base64_encode(file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/upload/vacancy/{$fileName}"))]];
                }

                $arLoadProductArray = [
                    "IBLOCK_ID" => $arParams['IBLOCK_ID'],
                    "PROPERTY_VALUES" => $arProperties,
                    "ACTIVE" => "Y",
                    "NAME" => "{$request->get("user_name")}",
                    'PREVIEW_TEXT' => "{$request->get("MESSAGE")}",
                ];

                if(empty($request->get("user_name")))
                {
                    $arLoadProductArray['NAME'] = $arParams['lidName'];
                }
                
                if(!empty($_REQUEST['MESSAGE']))
                {
                    $arLoadProductArray['PREVIEW_TEXT'] = $_REQUEST['MESSAGE'];
                }

                // $PRODUCT_ID = $el->Add($arLoadProductArray);

                $arFields = Array(
                    "page" => $CUR_URL,
                    "link_page" => $arParams['LINK_PAGE'],
                    "utm" => $utm_soruce,
                    "fio" => $request->get("user_name"),
                    "phone" => $request->get("user_phone"),
                    'email' => $request->get('user_email'),
                    "message" => $request->get("MESSAGE"),
                    "company" => $request->get("company"),
                    "product" => $request->get('product'),
                    'file' => $linkFile,
                    'm' => $request->get('m'),
                );

                // $arCrmField["TITLE"] = ($request->get("lidName")) ? "{$request->get("lidName")} - {$request->get("user_name")}" : "Заявка с сайта zolt.pro - {$request->get("user_name")}";
                // $arCrmField["NAME"] = "{$request->get("user_name")}";
                // $arCrmField["EMAIL"] = [["VALUE" => "{$request->get('user_email')}","VALUE_TYPE" => "WORK"]];
                // $arCrmField["PHONE"] = [["VALUE" => "{$request->get("user_phone")}","VALUE_TYPE" => "WORK"]];
                // $arCrmField["COMPANY_TITLE"] = "{$request->get("company")}"; // Компания
                // $arCrmField["SOURCE_ID"] = "CALL";
                // $arCrmField["COMMENTS"] = "{$request->get("MESSAGE")}";

                // postCrm($arCrmField, [], "crm.lead.add");
                
                // if(!empty($arParams["EVENT_MESSAGE_ID"]))
                // {
                //     foreach($arParams["EVENT_MESSAGE_ID"] as $v)
                //     {
                //         if(IntVal($v) > 0)
                //         {
                //             CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", IntVal($v));
                //         }
                //     }
                // }
                // else
                // {
                //     CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields);
                // }


                // $uriString
                $uri = new Uri($uriString);//"/success/");
                $uri->addParams(["success" => $arResult["PARAMS_HASH"]]);
                $redirectUrl = $uri->getUri();
                
                
                $_SESSION["MF_NAME"] = htmlspecialcharsbx($_REQUEST["user_name"]);
                #$_SESSION["MF_COMPANY"] = htmlspecialcharsbx($_POST["user_company"]);
                #$_SESSION["MF_POSITION"] = htmlspecialcharsbx($_POST["user_position"]);
                $_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_REQUEST["user_email"]);
                $_SESSION["MF_PHONE"] = htmlspecialcharsbx($_REQUEST["user_phone"]);
                #$_SESSION["MF_SOURCE"] = htmlspecialcharsbx($_POST["source"]);

                
                                
                LocalRedirect("{$redirectUrl}");
                
           /* }
            else
            {
                echo '<div class="alert alert-danger">Ошибка. Попробуйте еще раз.</div>';
            }*/
		}
		
		$arResult["MESSAGE"] = htmlspecialcharsbx($request->get("MESSAGE"));
		$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($request->get("user_name"));
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($request->get("user_email"));
        $arResult["AUTHOR_PHONE"] = htmlspecialcharsbx($request->get("user_phone"));
        $arResult["company"] = htmlspecialcharsbx($request->get("company"));
        #$arResult["POSITION"] = htmlspecialcharsbx($_REQUEST["user_position"]);
        #$arResult["SOURCE"] = htmlspecialcharsbx($_REQUEST["source"]);
	}
	else
    {
        $arResult["ERROR_MESSAGE"][] = Loc::getMessage("MF_SESS_EXP");
    }
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = $arParams["OK_TEXT"];
}

if(empty($arResult["ERROR_MESSAGE"]))
{
	if($USER->IsAuthorized())
	{
		$arResult["AUTHOR_NAME"] = $USER->GetFormattedName(false);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($USER->GetEmail());
	}
	else
	{
		if(strlen($_SESSION["MF_NAME"]) > 0)
			$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_SESSION["MF_NAME"]);
		if(strlen($_SESSION["MF_EMAIL"]) > 0)
			$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_SESSION["MF_EMAIL"]);
        if(strlen($_SESSION["MF_COMPANY"]) > 0)
			$arResult["COMPANY"] = htmlspecialcharsbx($_SESSION["MF_COMPANY"]);
        if(strlen($_SESSION["MF_POSITION"]) > 0)
			$arResult["POSITION"] = htmlspecialcharsbx($_SESSION["MF_POSITION"]);
        if(strlen($_SESSION["MF_SOURCE"]) > 0)
			$arResult["SOURCE"] = htmlspecialcharsbx($_SESSION["MF_SOURCE"]);
	}
}

if($arParams["USE_CAPTCHA"] == "Y") $arResult["capCode"] =  htmlspecialcharsbx($APPLICATION->CaptchaGetCode());

$this->IncludeComponentTemplate();