<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

$request = Application::getInstance()->getContext()->getRequest();
?>

<div class="w-100 d-f jc-c">

	<div class="w-100 w-100 sm:mw-540px p-32 md:p-64 d-f fd-c rg-32 bc-w r-24">

		<div clas="w-100">
			<h1 class="mb-0 ff-ud xl:f-24 ftw-6 tt-l ls--1 lh-1.125">Вход в личный кабинет</h1>
		</div>

		<?if (!empty($arParams["~AUTH_RESULT"])):?>
			<div class="w-100 d-f fd-c rg-8">
				<div class="f-14 c-f00"><?=$arParams["~AUTH_RESULT"]['MESSAGE']?></div>
			</div>
		<?endif;?>

		<?if (!empty($arResult['ERROR_MESSAGE'])):?>
			<div class="w-100 d-f fd-c rg-8">
				<?foreach($arResult['ERROR_MESSAGE'] as $mError):?>
					<div class=""><?=$mError?></div>
				<?endforeach;?>
			</div>
		<?endif;?>

		<form class="w-100 d-f fd-c rg-24" name="form_auth" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">

			<input type="hidden" name="AUTH_FORM" value="Y" />
			<input type="hidden" name="TYPE" value="AUTH" />

			<?= bitrix_sessid_post(); ?>

			<?if ($arResult["BACKURL"] <> ''):?>
				<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
			<?endif?>

			<?foreach ($arResult["POST"] as $key => $value):?>
				<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
			<?endforeach?>

			<div class="w-100 d-f fd-c rg-16">
				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="text" name="USER_LOGIN" maxlength="255" value="<?=$arResult["LAST_LOGIN"]?>" placeholder="Email">
				</div>

				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="password" name="USER_PASSWORD" maxlength="255" autocomplete="off" placeholder="Пароль">
				</div>

				<div class="d-f cg-24 f-14">
					<div class="fg-1">
						<label for="USER_REMEMBER" class="mb-0 d-f cg-8">
							<input type="checkbox" id="USER_REMEMBER" name="USER_REMEMBER" value="Y" />
							<div class="ws-nw"><?=Loc::getMessage("AUTH_REMEMBER_ME")?></div>
						</label>
					</div>
					<div class="">
						<?if($arParams["NOT_SHOW_LINKS"] != "Y"):?>
							<noindex>
								<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow" class="h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3"><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_2")?></a>
							</noindex>
						<?endif?>
					</div>
				</div>
			</div>

			<div class="d-f jc-c">
				<button class="pt-16 pr-32 pb-16 pl-32 lg:pt-24 lg:pr-48 lg:pb-24 lg:pl-48 bi-st bw-0 bs-s bcr-527CC8 r-12 c-w h:c-dt f-18 ff-ud ftw-6 ta-c tt-l lh-1 tn-all-3"
					type="submit" name="Login" value="<?=Loc::getMessage("AUTH_AUTHORIZE")?>"><?=Loc::getMessage("AUTH_AUTHORIZE")?></button>
			</div>

		</form>

		<?if($arParams["NOT_SHOW_LINKS"] != "Y" && $arResult["NEW_USER_REGISTRATION"] == "Y"):?>
			<div class="w-100 d-f ai-c jc-c cg-24 lh-1">
				<div class="f-14">Нет аккаунта?</div>
				<noindex><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow" class="ff-ud ftw-6 ls--1 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3"><?=Loc::getMessage("AUTH_REGISTER")?></a></noindex>
			</div>
		<?endif?>

		<script>
			<?if ($arResult["LAST_LOGIN"] <> ''):?>
				try{document.form_auth.USER_PASSWORD.focus();}catch(e){}
			<?else:?>
				try{document.form_auth.USER_LOGIN.focus();}catch(e){}
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
