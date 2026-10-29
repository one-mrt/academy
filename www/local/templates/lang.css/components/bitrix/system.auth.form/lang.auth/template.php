<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

$request = Application::getInstance()->getContext()->getRequest();

CJSCore::Init();

// echo '<pre>';
// print_r($_REQUEST);
// echo '</pre>';

// echo '<pre>';
// print_r($arResult);
// echo '</pre>';
?>

<div class="w-100 d-f jc-c">

	<div class="w-100 w-100 sm:mw-540px p-32 md:p-64 d-f fd-c rg-32 bc-w r-24">

		<?if($request->get('modal') == 'auth'):?>
			<button class="pn-a t-32px rt-32px m-0 p-0 bw-0 c-dt h:c-py tn-all-3 bc-t" data-fancybox-close="">
				<svg width="24" height="24" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
					<line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
					<line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
				</svg>
			</button>
		<?endif;?>

		<div clas="w-100">
			<h1 class="mb-0 ff-ud xl:f-24 ftw-6 tt-l ls--1 lh-1.125">Вход в личный кабинет</h1>
		</div>

		<?if($arResult['SHOW_ERRORS'] === 'Y' && $arResult['ERROR'] && !empty($arResult['ERROR_MESSAGE'])):?>
			<div class="w-100 d-f fd-c rg-8">
				<?//foreach($arResult['ERROR_MESSAGE'] as $mError):?>
					<div class=""><?=$arResult['ERROR_MESSAGE']['MESSAGE']//$mError?></div>
				<?//endforeach;?>
			</div>
		<?endif;?>

		<?if($arResult["FORM_TYPE"] == "login"):?>

			<form class="w-100 d-f fd-c rg-24" name="system_auth_form<?//=$arResult["RND"]?>" method="post" action="<?=$arResult["AUTH_URL"]?>">

				<?if($arResult["BACKURL"] <> ''):?>
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
				<?endif?>

				<?foreach ($arResult["POST"] as $key => $value):?>
					<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
				<?endforeach?>

				<input type="hidden" name="AUTH_FORM" value="Y" />
				<input type="hidden" name="TYPE" value="AUTH" />
				<?= bitrix_sessid_post(); ?>

				<div class="w-100 d-f fd-c rg-16">
					<div class="">
						<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
							type="text" name="USER_LOGIN" maxlength="50" value="" size="17" placeholder="Email">
					</div>

					<div class="">
						<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
							type="password" name="USER_PASSWORD" maxlength="255" size="17" autocomplete="off" placeholder="Пароль">
					</div>

					<div class="d-f cg-24 f-14">
						<div class="fg-1">
							<label for="USER_REMEMBER_frm" class="mb-0 d-f cg-8">
								<input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />
								<div class="ws-nw"><?=Loc::getMessage("AUTH_REMEMBER_ME")?></div>
							</label>
						</div>
						<div class="">
							<noindex>
								<a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow" class="h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3"><?=Loc::getMessage("AUTH_FORGOT_PASSWORD_2")?></a>
							</noindex>
						</div>
					</div>

					<div class="d-f jc-c">
						<button class="pt-16 pr-32 pb-16 pl-32 lg:pt-24 lg:pr-48 lg:pb-24 lg:pl-48 bi-st bw-0 bs-s bcr-527CC8 r-12 c-w h:c-dt f-18 ff-ud ftw-6 ta-c tt-l lh-1 tn-all-3"
							type="submit" name="Login" value="<?=Loc::getMessage("AUTH_LOGIN_BUTTON")?>"><?=Loc::getMessage("AUTH_LOGIN_BUTTON")?></button>
					</div>

				</div>

				<script>
					BX.ready(function() {
						var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
						if (loginCookie)
						{
							var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
							var loginInput = form.elements["USER_LOGIN"];
							loginInput.value = loginCookie;
						}
					});
					console.log('--1')
				</script>

				<?/*
				<table width="95%">
					<tr>
						<td colspan="2">
						<?=GetMessage("AUTH_LOGIN")?>:<br />
						<input type="text" name="USER_LOGIN" maxlength="50" value="" size="17" />
						<script>
							BX.ready(function() {
								var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
								if (loginCookie)
								{
									var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
									var loginInput = form.elements["USER_LOGIN"];
									loginInput.value = loginCookie;
								}
							});
						</script>
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<?=GetMessage("AUTH_PASSWORD")?>:<br />
							<input type="password" name="USER_PASSWORD" maxlength="255" size="17" autocomplete="off" />
							<?if($arResult["SECURE_AUTH"]):?>
											<span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?= GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
												<div class="bx-auth-secure-icon"></div>
											</span>
											<noscript>
											<span class="bx-auth-secure" title="<?= GetMessage("AUTH_NONSECURE_NOTE")?>">
												<div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
											</span>
											</noscript>
							<script>
							document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
							</script>
							<?endif?>
						</td>
					</tr>
					<?if ($arResult["STORE_PASSWORD"] == "Y"):?>
							<tr>
								<td valign="top"><input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" /></td>
								<td width="100%"><label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?= GetMessage("AUTH_REMEMBER_SHORT")?></label></td>
							</tr>
					<?endif?>
					<?if ($arResult["CAPTCHA_CODE"]):?>
							<tr>
								<td colspan="2">
								<?= GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
								<input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"]?>" />
								<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
								<input type="text" name="captcha_word" maxlength="50" value="" /></td>
							</tr>
					<?endif?>
					<tr>
						<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
					</tr>
					<?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
							<tr>
								<td colspan="2"><noindex><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_REGISTER")?></a></noindex><br /></td>
							</tr>
					<?endif?>

					<tr>
						<td colspan="2"><noindex><a href="<?=$arResult["AUTH_FORGOT_PASSWORD_URL"]?>" rel="nofollow"><?=GetMessage("AUTH_FORGOT_PASSWORD_2")?></a></noindex></td>
					</tr>
					<?if($arResult["AUTH_SERVICES"]):?>
							<tr>
								<td colspan="2">
									<div class="bx-auth-lbl"><?=GetMessage("socserv_as_user_form")?></div>
									<?php
									$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons",
										array(
											"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
											"SUFFIX"=>"form",
										),
										$component,
										array("HIDE_ICONS"=>"Y")
									);
									?>
								</td>
							</tr>
					<?endif?>
				</table>
				*/?>

			</form>

			<div class="w-100 d-f ai-c jc-c cg-24 lh-1">
				<div class="f-14">Нет аккаунта?</div>
				<noindex><a href="<?=$arResult["AUTH_REGISTER_URL"]?>" rel="nofollow" class="ff-ud ftw-6 ls--1 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3"><?=Loc::getMessage("AUTH_REGISTER")?></a></noindex>
			</div>

			<?if($arResult["AUTH_SERVICES"]):?>
				<?$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "",
					array(
						"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
						"AUTH_URL"=>$arResult["AUTH_URL"],
						"POST"=>$arResult["POST"],
						"POPUP"=>"Y",
						"SUFFIX"=>"form",
					),
					$component,
					array("HIDE_ICONS"=>"Y")
				);?>
			<?endif?>

		<?elseif($arResult["FORM_TYPE"] == "otp"):?>

			<form name="system_auth_form<?=$arResult["RND"]?>" method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
				<?if($arResult["BACKURL"] <> ''):?>
					<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
				<?endif?>
				<input type="hidden" name="AUTH_FORM" value="Y" />
				<input type="hidden" name="TYPE" value="OTP" />
				<?= bitrix_sessid_post(); ?>
				<table width="95%">
					<tr>
						<td colspan="2">
						<?= GetMessage("auth_form_comp_otp")?><br />
						<input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" /></td>
					</tr>
					<?if ($arResult["CAPTCHA_CODE"]):?>
							<tr>
								<td colspan="2">
								<?= GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
								<input type="hidden" name="captcha_sid" value="<?= $arResult["CAPTCHA_CODE"]?>" />
								<img src="/bitrix/tools/captcha.php?captcha_sid=<?= $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
								<input type="text" name="captcha_word" maxlength="50" value="" /></td>
							</tr>
					<?endif?>
					<?if ($arResult["REMEMBER_OTP"] == "Y"):?>
							<tr>
								<td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y" /></td>
								<td width="100%"><label for="OTP_REMEMBER_frm" title="<?= GetMessage("auth_form_comp_otp_remember_title")?>"><?= GetMessage("auth_form_comp_otp_remember")?></label></td>
							</tr>
					<?endif?>
					<tr>
						<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
					</tr>
					<tr>
						<td colspan="2"><noindex><a href="<?=$arResult["AUTH_LOGIN_URL"]?>" rel="nofollow"><?= GetMessage("auth_form_comp_auth")?></a></noindex><br /></td>
					</tr>
				</table>
			</form>

		<?else:?>

			<form action="<?=$arResult["AUTH_URL"]?>">
				<table width="95%">
					<tr>
						<td align="center">
							<?=$arResult["USER_NAME"]?><br />
							[<?=$arResult["USER_LOGIN"]?>]<br />
							<a href="<?=$arResult["PROFILE_URL"]?>" title="<?=GetMessage("AUTH_PROFILE")?>"><?=GetMessage("AUTH_PROFILE")?></a><br />
						</td>
					</tr>
					<tr>
						<td align="center">
						<?foreach ($arResult["GET"] as $key => $value):?>
							<input type="hidden" name="<?=$key?>" value="<?=$value?>" />
						<?endforeach?>
						<?=bitrix_sessid_post()?>
						<input type="hidden" name="logout" value="yes" />
						<input type="submit" name="logout_butt" value="<?=GetMessage("AUTH_LOGOUT_BUTTON")?>" />
						</td>
					</tr>
				</table>
			</form>

		<?endif?>

		<style>
			.lang-form--window .fancybox__backdrop {
				background: #f4eff95e;
				backdrop-filter: blur(6px)
			}

			.lang-form--window .fancybox__content {
				max-width: 100%;
				padding: 0;
				border-radius: 1.5rem;
				background-color: #edf0f900;
				box-shadow: 0 16px 32px rgb(109 48 167 / 12%), 0 12px 24px rgb(109 48 167 / 22%);
			}

			@media (min-width: 48rem) {
				.lang-form--window .fancybox__content {
					max-width: 30rem;
					padding: 0;
					border-radius: 1.5rem;
				}
			}
		</style>

	</div>
</div>
