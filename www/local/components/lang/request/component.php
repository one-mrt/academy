<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Main\Crm;
use Lang\Main\Curl;
use Bitrix\Main\Loader;
use Bitrix\Main\Web\Uri;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');
Loader::includeModule('lang.iblock');

$iblock = Util::getIblock(['ID'], ['API_CODE' => $arParams['API_CODE']]);
$request = Application::getInstance()->getContext()->getRequest();

$arResult["PARAMS_HASH"] = md5(serialize($arParams).$this->GetTemplateName());

$arParams["USE_CAPTCHA"] = (($arParams["USE_CAPTCHA"] != "N" && !$USER->IsAuthorized()) ? "Y" : "N");
$arParams["EVENT_NAME"] = trim($arParams["EVENT_NAME"]);

if($arParams["EVENT_NAME"] == '')
{
	$arParams["EVENT_NAME"] = "FEEDBACK_FORM";
}

if($_SERVER["REQUEST_METHOD"] == "POST" && $_REQUEST["submit"] <> '' && (!isset($_REQUEST["PARAMS_HASH"]) || $arResult["PARAMS_HASH"] === $_REQUEST["PARAMS_HASH"]))
{
    $arResult["ERROR_MESSAGE"] = [];

	if(check_bitrix_sessid())
	{
		if(empty($arParams["REQUIRED_FIELDS"]) || !in_array("NONE", $arParams["REQUIRED_FIELDS"]))
		{
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("COMPANY", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_company"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_COMPANY");	
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("NAME", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_name"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_NAME");		
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("POSITION", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_position"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_POSITION");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("EMAIL", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_email"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_EMAIL");
            if((empty($arParams["REQUIRED_FIELDS"]) || in_array("PHONE", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["user_phone"]) <= 1)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_PHONE");
			if((empty($arParams["REQUIRED_FIELDS"]) || in_array("MESSAGE", $arParams["REQUIRED_FIELDS"])) && strlen($_POST["MESSAGE"]) <= 3)
				$arResult["ERROR_MESSAGE"][] = GetMessage("MF_REQ_MESSAGE");
		}

		if(strlen($_POST["user_email"]) > 1 && !check_email($_POST["user_email"]))
            $arResult["ERROR_MESSAGE"][] = GetMessage("MF_EMAIL_NOT_VALID");
            
            

		if(empty($arResult["ERROR_MESSAGE"]))
        {
            function getCaptcha($SecretKey)
            {
                $url = "https://www.google.com/recaptcha/api/siteverify?secret=6LfAGSMsAAAAADBGDFHKy2MX5jlUfKczTySDudaa&response={$SecretKey}";

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_VERBOSE, 1);
                curl_setopt($curl, CURLOPT_POST, false);
                curl_setopt($curl, CURLOPT_URL, $url);

                return json_decode(curl_exec($curl));
            }

            if ($arParams['GOOGLE_RECAPTCHA'] == 'Y')
            {
                $Return = getCaptcha($request->get("g-recaptcha-response-faq"));
            }
            else
            {
                $Return = (object) ['success' => true, 'score' => 0.6];
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
            
            if($Return->success == true && $Return->score >= 0.6)
            {
                $uriString = $request->getRequestUri();
                $uri = new Uri($uriString);
                $uri->addParams(["success" => $arResult["PARAMS_HASH"]]);
                $redirectUrl = $uri->getUri();

                $el = new CIBlockElement;

                $arProperties = [
                    "email" => $request->get("user_email"),
                    "phone" => $request->get("user_phone"),
                    "date" => $request->get("date"),
                    "call" => $request->get("call"),
                    "paket" => $request->get("paket"),
                    "prog" => $request->get("prog"),
                    "variant" => $request->get("variant"),
                    "sum" => $request->get("sum"),
                    "page" => $CUR_URL,
                    'utm' => $utm_soruce,
                ];

                if(!empty($request->get("day")))
                {
                    $arProperties['day'] = ['VALUE' => $request->get("day")];
                }

                if(!empty($request->get("service")))
                {
                    foreach($request->get("service") as $idService => $service)
                    {
                        if(!empty($service['id']))
                        {
                            $arProperties['day'][] = ['VALUE' => $service['id']];
                        }
                    }
                }

                $textTelegram = "";
                $textTelegram .= "Заявка: Калькулятор - Стоимость оздоровительной программы \r\n\n";

                $textTelegram .= "Имя: {$request->get('user_name')} \r\n";
                $textTelegram .= "Телефон: {$request->get('user_phone')} \r\n";
                $textTelegram .= "Email: {$request->get('user_email')} \r\n";
                $textTelegram .= "Предпочитаемый способ связи: {$request->get('call')} \r\n";
                $textTelegram .= "Дата: {$request->get("date")} \r\n";

                $textTelegram .= "==========\r\n";

                if(!empty($request->get("paket")))
                {
                    $paket = Util::getSection($calcTableString, ["NAME"], ["ID" => $request->get("paket")]);
                    
                    if(!empty($paket))
                    {
                        $textTelegram .= "Пакет: {$paket['NAME']} \r\n";
                    }
                }

                if(!empty($request->get("prog")))
                {
                    $prog = Util::getSection($calcTableString, ["NAME"], ["ID" => $request->get("prog")]);
                    
                    if(!empty($prog))
                    {
                        $textTelegram .= "Программа: {$prog['NAME']} \r\n";
                    }
                }

                if(!empty($request->get("day")))
                {
                    $day = Util::getElement($calcTableString, ["NAME"], ["ID" => $request->get("day")]);
                    
                    if(!empty($day))
                    {
                        $textTelegram .= "Дней: {$day['NAME']} \r\n";
                    }
                }

                if(!empty($request->get("service")))
                {
                    foreach($request->get("service") as $idService => $service)
                    {
                        if(!empty($service['id']))
                        {
                            $serv = Util::getElement($calcTableString, ["NAME"], ["ID" => $service['id']]);

                            if(!empty($serv))
                            {
                                $textTelegram .= "Услуга: {$serv['NAME']} - кол. {$service['count']} \r\n";
                            }
                        }
                    }
                }

                if(!empty($request->get("variant")))
                {
                    $variant = Util::getElement($textTableString, ["NAME"], ["ID" => $request->get("variant")]);
                    
                    if(!empty($variant))
                    {
                        $textTelegram .= "Варианты размещения: {$variant['NAME']} \r\n";
                    }
                }

                $textTelegram .= "Сумма: {$request->get("sum")} \r\n";
                $textTelegram .= "==========\r\n";
                $textTelegram .= "Страница: {$CUR_URL} \r\n";
                $textTelegram .= "ЮТМ: {$utm_soruce}";

                // $curl = curl_init();
                // curl_setopt_array(
                //     $curl,
                //     [
                //         CURLOPT_URL => 'https://api.telegram.org/bot8429152409:AAGU2_pq7pqzfX-UM3CcOyZreEXYmV6zeig/sendMessage',
                //         CURLOPT_POST => TRUE,
                //         CURLOPT_RETURNTRANSFER => TRUE,
                //         CURLOPT_TIMEOUT => 10,
                //         CURLOPT_POSTFIELDS => [
                //             'chat_id' => '-5016801493',
                //             'text' => $textTelegram,
                //         ],
                //     ]
                // );

                //  8429152409:AAGU2_pq7pqzfX-UM3CcOyZreEXYmV6zeig
                // 5016801493

                // $curlResult = curl_exec($curl);

                $arLoadProductArray = [
                    "IBLOCK_ID" => $iblock["ID"],
                    "PROPERTY_VALUES" => $arProperties,
                    "ACTIVE" => "Y",
                    "NAME" => $request->get("user_name"),
                    "PREVIEW_TEXT" => $request->get("MESSAGE"),
                ];

                // $PRODUCT_ID = $el->Add($arLoadProductArray);

                // $contactAddName = explode(' ', $_REQUEST['user_name']);
                // $crmLastName = Crm::ruUcfirst($contactAddName[0]);
                // $crmName = Crm::ruUcfirst($contactAddName[1]);
                // $crmSecondName = Crm::ruUcfirst($contactAddName[2]);

                // $contactAddCrm = [
                //     "LAST_NAME" => $crmLastName,
                //     'NAME' => $crmName,
                //     "SECOND_NAME" => $crmSecondName,
                //     'SOURCE_ID' => 'UC_9F07MP',
                //     "TYPE_ID" => "CLIENT",
                //     "OPENED" => 'Y', # Открыто всем
                //     "EXPORT" => 'N',
                //     'EMAIL' => [['VALUE' => $_REQUEST["user_email"], "VALUE_TYPE" => "WORK"]],
                //     'PHONE' => [['VALUE' => $_REQUEST["user_phone"],"VALUE_TYPE" => "WORK"]],
                //     //'TRACE' => 3,
                //     "CREATED_BY_ID" => 21,
                //     "ASSIGNED_BY_ID" => 22,
                //     'UTM_SOURCE' => ''
                // ];

                // $contactAddResult = Crm::postCrm($contactAddCrm, [], 'crm.contact.add');

                // $lucianoCrm = [
                //     'TITLE' => $arParams['TITLE_CRM_LEAD'] ? $arParams['TITLE_CRM_LEAD'] : "Заявка с сайта med.luciano.ru",
                //     "LAST_NAME" => $crmLastName,
                //     'NAME' => $crmName,
                //     "SECOND_NAME" => $crmSecondName,
                //     'SOURCE_ID' => 'UC_9F07MP',
                //     'EMAIL' => [['VALUE' => $_REQUEST["user_email"], "VALUE_TYPE" => "WORK"]],
                //     'PHONE' => [['VALUE' => $_REQUEST["user_phone"], "VALUE_TYPE" => "WORK"]],
                //     "COMMENTS" => $_REQUEST["MESSAGE"],
                //     "UF_CRM_1678710549116" => "{$CUR_URL}",
                //     "WEB" => [
                //         'VALUE' => "{$CUR_URL}",
                //         "VALUE_TYPE" => "WORK"
                //     ],
                //     "CREATED_BY_ID" => 21,
                //     'ASSIGNED_BY_ID' => 22,
                //     // 'TRACE' => 3,
                //     // "TRACKING_SOURCE_ID" => 3,
                //     'CONTACT_ID' => $contactAddResult['result'],
                //     #'UTM_SOURCE' => 'med.luciano.ru'
                // ];

                // Crm::postCrm($lucianoCrm, [], "crm.lead.add");
                
                $arFields = Array(
                    "page" =>  $CUR_URL,
                    'error' => $el->LAST_ERROR,
                    "urm" => $utm_soruce,
                    "fio" => $_REQUEST["user_name"],
                    "email" => $_REQUEST["user_email"],
                    "phone" => $_REQUEST["user_phone"],
                    "message" => $_REQUEST["MESSAGE"],
                );
                
                // if(!empty($arParams["EVENT_MESSAGE_ID"]))
                // {
                //     foreach($arParams["EVENT_MESSAGE_ID"] as $v) if(IntVal($v) > 0)
                //     {
                //         CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields, "N", IntVal($v));
                //     }
                // }
                // else
                // {
                //     CEvent::Send($arParams["EVENT_NAME"], SITE_ID, $arFields);
                // }
                
                
                $_SESSION["MF_NAME"] = htmlspecialcharsbx($_REQUEST["user_name"]);
                $_SESSION["MF_EMAIL"] = htmlspecialcharsbx($_REQUEST["user_email"]);
                $_SESSION["MF_PHONE"] = htmlspecialcharsbx($_REQUEST["user_phone"]);
                                
                LocalRedirect("{$redirectUrl}");
                
            }
            else
            {
                $arResult["ERROR_MESSAGE"][] = "Вы не прошли проверку. Обновите страницу и попробуйте еще раз.";
            }
		}
		
		$arResult["MESSAGE"] = htmlspecialcharsbx($_REQUEST["MESSAGE"]);
		$arResult["AUTHOR_NAME"] = htmlspecialcharsbx($_REQUEST["user_name"]);
		$arResult["AUTHOR_EMAIL"] = htmlspecialcharsbx($_REQUEST["user_email"]);
        $arResult["AUTHOR_PHONE"] = htmlspecialcharsbx($_REQUEST["user_phone"]);
	}
	else
    {
        $arResult["ERROR_MESSAGE"][] = GetMessage("MF_SESS_EXP");
    }
}
elseif($_REQUEST["success"] == $arResult["PARAMS_HASH"])
{
	$arResult["OK_MESSAGE"] = true;
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