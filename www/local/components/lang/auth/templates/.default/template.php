<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
?>

<div class="lang-auth--content w-100 d-f jc-c">
	<div class="w-100 w-100 sm:mw-540px p-48 sm:p-64 d-f fd-c rg-32 bc-w r-24">

		<div clas="w-100">
			<h1 class="mb-0 ff-ud f-24 ftw-6 tt-l ls--1 lh-1.125 ta-c">Вход в личный кабинет</h1>
		</div>

		<form class="lang-auth--form w-100 d-f fd-c rg-24" method="post">
			<?=bitrix_sessid_post();?>
			<input type="hidden" name="AUTH_FORM" value="Y" />
			<input type="hidden" name="TYPE" value="AUTH" />

			<?if ($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>

			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>

			<?if(!empty($arResult['error'])):?>
				<div class="w-100 d-f fd-c rg-8">
					<?foreach($arResult['error'] as $mError):?>
						<div class=""><?=$mError?></div>
					<?endforeach;?>
				</div>
			<?endif;?>

			<div class="w-100 d-f fd-c rg-16">
				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="email" name="email" maxlength="255" value="<?=$arResult["login"]?>" placeholder="Email" required>
				</div>

				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="password" name="password" maxlength="255" autocomplete="off" placeholder="Пароль" required>
				</div>

				<div class="d-f cg-24 f-14">
					<div class="fg-1">
						<label for="USER_REMEMBER" class="mb-0 d-f cg-8">
							<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />
							<div class="ws-nw"><?=Loc::getMessage("AUTH_REMEMBER_ME")?></div>
						</label>
					</div>
					<div class="">
						<a class="lang-form--forgot m-0 p-0 bc-t bw-0 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 cr-p"><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_2")?></a>
					</div>
				</div>
			</div>

			<div class="d-f jc-c">
				<button class="<?=comp::get("btnPurpleLg")?>" type="submit" name="Login" value="<?=Loc::getMessage("AUTH_AUTHORIZE")?>"><?=Loc::getMessage("AUTH_AUTHORIZE")?></button>
			</div>

		</form>

		<div class="w-100 d-f ai-c jc-c cg-24 lh-1">
			<div class="f-12 sm:f-14">Нет аккаунта?</div>
			<button class="lang-form--reg m-0 p-0 bc-t bw-0 ff-ud f-14 sm:f-16 ftw-6 ls--1 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3"><?=Loc::getMessage("AUTH_REGISTER")?></button>
		</div>

		<script>
			<?if ($arResult["LAST_LOGIN"] <> ''):?>
				try{document.form_auth.email.focus();}catch(e){}
			<?else:?>
				try{document.form_auth.password.focus();}catch(e){}
			<?endif?>
		</script>

		<?if($arResult["AUTH_SERVICES"]):?>
			<?$APPLICATION->IncludeComponent(
				"bitrix:socserv.auth.form",
				"",
				array(
					"AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
					"CURRENT_SERVICE" => $arResult["CURRENT_SERVICE"],
					"AUTH_URL" => $arResult["AUTH_URL"],
					"POST" => $arResult["POST"],
					"SHOW_TITLES" => $arResult["FOR_INTRANET"]?'N':'Y',
					"FOR_SPLIT" => $arResult["FOR_INTRANET"]?'Y':'N',
					"AUTH_LINE" => $arResult["FOR_INTRANET"]?'N':'Y',
				),
				$component,
				array("HIDE_ICONS"=>"Y")
			);?>
		<?endif?>
	</div>
</div>
