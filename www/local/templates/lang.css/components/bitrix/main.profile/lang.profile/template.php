<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
?>


<?
ShowError($arResult["strProfileError"]);

// if (isset($arResult['DATA_SAVED']) && $arResult['DATA_SAVED'] == 'Y') {
// 	LocalRedirect($APPLICATION->GetCurPage().'?save=y');
// }

// if($_GET['save'] == 'y') {
	// ShowNote(Loc::getMessage('PROFILE_DATA_SAVED'));
// }

?>


<section class="w-100 d-f fd-c rg-32">

	<?=$arResult["BX_SESSION_CHECK"]?>
	<input type="hidden" name="lang" value="<?=LANG?>" />
	<input type="hidden" name="ID" value="<?=$arResult["ID"]?>" />
	<input type="hidden" name="save" value="y" />

	<div class="w-100 d-f cg-16 md:cg-24 bc-w r-12 p-24 sm:p-32">
		<div class="d-f ai-c cg-16 md:cg-24 fg-1">
			<?if(!empty($arResult['arUser']['PERSONAL_PHOTO'])):?>
				<?=Data::getImageId($arResult['arUser']['PERSONAL_PHOTO'], [
					"alt" => $arResult['arUser']['NAME'],
					"fetchpriority" => "high",
					"class" => "w-60px h-60px md:w-90px md:h-90px r-100 oft-cv op-c",
				])?>
			<?else:?>
				<div class="w-60px h-60px md:w-90px md:h-90px bc-w bw-1 bs-s bcr-636363 r-100"></div>
			<?endif?>

			<div class="d-f fd-c rg-24 fg-1">
				<div class="d-f fd-c rg-8 fg-1">
					<div class="d-f fd-c ff-ud ftw-6 f-14 md:f-20"><?=$arResult['arUser']['NAME']?> <?=$arResult['arUser']['LAST_NAME']?></div>
					<div class="ftw-5 c-636363 f-12 md:f-14">Зарегистрирован <?=FormatDate('j F Y', strtotime($arResult['arUser']['DATE_REGISTER']))?></div>
				</div>
			</div>
		</div>
		<div class="d-f ai-s jc-e">
			<div class="lang-image--edit bc-t bw-0 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12 sm:f-14 ftw-5 cr-p ws-nw">Загрузить фото</div>
		</div>
	</div>

	<div class="w-100 xl:f-14 xxl:f-16">
		<div class="<?=comp::get("row")?> rg-32">

			<div class="w-100 lg:w-6/12 <?=comp::get("setcol")?> d-f fd-c rg-32">
				<div class="w-100 h-100 d-f fd-c rg-24 bc-w r-12 p-32">
					<div class="ff-ud xxl:f-18 ftw-6 tt-l lh-1">Личная информация</div>
					<div class="w-100 d-f fd-c rg-16 fg-1">

						<?
						$fieldList = ['LOGIN', 'NAME', 'LAST_NAME'];

						foreach($fieldList as $field):?>
							<div class="w-100 d-f">
								<div class="w-4/12 d-f ai-c ftw-6">
									<div class="lh-1"><?=Loc::getMessage($field)?></div>
								</div>
								<div class="w-8/12">
									<div class="w-100 pn-r h:c-py h:bcr-py">
										<input id="<?=strtolower($field)?>" class="lang-profile--input w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
											type="text" name="<?=$field?>" maxlength="50" value="<?=$arResult["arUser"][$field]?>">
										<label for="<?=strtolower($field)?>" class="lang-profile--edit pn-a t-0 rt-0 b-0 d-f ai-c pt-12 pr-24 pb-12 mb-0 cr-p">
											<svg class="tn-all-3" width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 11.0837V14H2.91627L11.5173 5.39895L8.60106 2.48268L0 11.0837ZM13.7726 3.1437C13.8447 3.07175 13.9019 2.98629 13.9409 2.89222C13.9799 2.79814 14 2.69729 14 2.59544C14 2.49359 13.9799 2.39274 13.9409 2.29866C13.9019 2.20458 13.8447 2.11912 13.7726 2.04718L11.9528 0.227426C11.8809 0.155333 11.7954 0.0981367 11.7013 0.059112C11.6073 0.0200872 11.5064 0 11.4046 0C11.3027 0 11.2019 0.0200872 11.1078 0.059112C11.0137 0.0981367 10.9282 0.155333 10.8563 0.227426L9.43316 1.65057L12.3494 4.56684L13.7726 3.1437Z"/></svg>
										</label>
									</div>
								</div>
							</div>
						<?endforeach;?>

						<div class="w-100 d-f">
							<div class="w-4/12 d-f ai-c ftw-6">
								<div class="lh-1"><?=Loc::getMessage("USER_GENDER")?></div>
							</div>
							<div class="w-8/12">
								<div class="w-100 pn-r h:c-py h:bcr-py">
									<select name="PERSONAL_GENDER" class="lang-profile--input w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n">
										<option value=""><?=Loc::getMessage("USER_DONT_KNOW")?></option>
										<option value="M" <?=$arResult["arUser"]["PERSONAL_GENDER"] == "M" ? "selected" : ""?>><?=Loc::getMessage("USER_MALE")?></option>
										<option value="F" <?=$arResult["arUser"]["PERSONAL_GENDER"] == "F" ? "selected" : ""?>><?=Loc::getMessage("USER_FEMALE")?></option>
									</select>
								</div>
							</div>
						</div>

						<div class="w-100 d-f">
							<div class="w-4/12 d-f ai-c ftw-6">
								<div class="lh-1"><?=Loc::getMessage("USER_COUNTRY")?></div>
							</div>
							<div class="w-8/12">
								<div class="w-100 pn-r h:c-py h:bcr-py">
									<?=preg_replace('/class=\'/i', 'class=\'lang-profile--input w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 ap-n ', $arResult["COUNTRY_SELECT"])?>
								</div>
							</div>
						</div>

					</div>
				</div>
			</div>

			<div class="w-100 lg:w-6/12 <?=comp::get("setcol")?> d-f fd-c rg-32">
				<div class="w-100 d-f fd-c rg-24 bc-w r-12 p-32 fg-1">
					<div class="ff-ud xxl:f-18 ftw-6 tt-l lh-1">Контактная информация</div>
					<div class="w-100 d-f fd-c rg-16 fg-1">

						<div class="w-100 d-f">
							<div class="w-4/12 d-f ai-c ftw-6">
								<div class="lh-1"><?=Loc::getMessage("EMAIL")?></div>
							</div>
							<div class="w-8/12">
								<div class="w-100 pn-r h:c-py h:bcr-py">
									<input id="<?=strtolower("EMAIL")?>" class="lang-profile--input w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
										type="text" name="EMAIL" maxlength="50" value="<?=$arResult["arUser"]["EMAIL"]?>">
									<label for="<?=strtolower("EMAIL")?>" class="pn-a t-0 rt-0 b-0 d-f ai-c pt-12 pr-24 pb-12 mb-0 cr-p">
										<svg class="tn-all-3" width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 11.0837V14H2.91627L11.5173 5.39895L8.60106 2.48268L0 11.0837ZM13.7726 3.1437C13.8447 3.07175 13.9019 2.98629 13.9409 2.89222C13.9799 2.79814 14 2.69729 14 2.59544C14 2.49359 13.9799 2.39274 13.9409 2.29866C13.9019 2.20458 13.8447 2.11912 13.7726 2.04718L11.9528 0.227426C11.8809 0.155333 11.7954 0.0981367 11.7013 0.059112C11.6073 0.0200872 11.5064 0 11.4046 0C11.3027 0 11.2019 0.0200872 11.1078 0.059112C11.0137 0.0981367 10.9282 0.155333 10.8563 0.227426L9.43316 1.65057L12.3494 4.56684L13.7726 3.1437Z"/></svg>
									</label>
								</div>
							</div>
						</div>

						<div class="w-100 d-f">
							<div class="w-4/12 d-f ai-c ftw-6">
								<div class="lh-1"><?=Loc::getMessage("USER_PHONE")?></div>
							</div>
							<div class="w-8/12">
								<div class="w-100 pn-r h:c-py h:bcr-py">
									<input id="<?=strtolower("PERSONAL_PHONE")?>" class="lang-profile--input w-100 pt-12 pr-48 pb-12 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
										type="text" name="PERSONAL_PHONE" maxlength="50" value="<?=$arResult["arUser"]["PERSONAL_PHONE"]?>">
									<label for="<?=strtolower("PERSONAL_PHONE")?>" class="pn-a t-0 rt-0 b-0 d-f ai-c pt-12 pr-24 pb-12 mb-0 cr-p">
										<svg class="tn-all-3" width="14" height="14" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M0 11.0837V14H2.91627L11.5173 5.39895L8.60106 2.48268L0 11.0837ZM13.7726 3.1437C13.8447 3.07175 13.9019 2.98629 13.9409 2.89222C13.9799 2.79814 14 2.69729 14 2.59544C14 2.49359 13.9799 2.39274 13.9409 2.29866C13.9019 2.20458 13.8447 2.11912 13.7726 2.04718L11.9528 0.227426C11.8809 0.155333 11.7954 0.0981367 11.7013 0.059112C11.6073 0.0200872 11.5064 0 11.4046 0C11.3027 0 11.2019 0.0200872 11.1078 0.059112C11.0137 0.0981367 10.9282 0.155333 10.8563 0.227426L9.43316 1.65057L12.3494 4.56684L13.7726 3.1437Z"/></svg>
									</label>
								</div>
							</div>
						</div>

					</div>
				</div>
				<div class="w-100 d-f fd-c rg-24 bc-w r-12 p-32">
					<div class="w-100 d-f jc-b">
						<div class="ff-ud xxl:f-18 ftw-6 tt-l lh-1 fg-1">Пароль</div>
						<div class="lang-password--edit bc-t bw-0 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12 sm:f-14 ftw-5 cr-p">Изменить</div>
					</div>
				</div>
				<div class="w-100 d-f fd-c rg-24 bc-w r-12 p-32">
					<div class="w-100 d-f jc-b">
						<div class="ff-ud xxl:f-18 ftw-6 tt-l lh-1 fg-1">Уведомления</div>
						<div class="lang-notify--edit bc-t bw-0 h:c-py tdt-1 tuo-4 tdc-py tdl-u tn-all-3 f-12 sm:f-14 ftw-5 cr-p">Редактировать</div>
					</div>
				</div>
			</div>

		</div>
	</div>
</section>

<div class="lang-profile--save w-200px pix-24 pby-16 bsst-8 bsr-8 f-14 bc-008000 c-w pn-f rt-0 rt--150% bxs-au tp-rt tnd-500ms d-f ai-c cg-16">
	<button class="save--close m-0 p-0 bw-0 c-w h:c-py tn-all-3 bc-t">
		<svg width="14" height="14" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
			<line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
			<line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
		</svg>
	</button>
	<div class="save--text">Сохранено</div>
</div>

<div class="lang-profile--error w-200px pix-24 pby-16 bsst-8 bsr-8 f-12 lh-1 bc-ff0000 c-w pn-f rt-0 rt--150% bxs-au tp-rt tnd-500ms d-f ai-c cg-16">
	<button class="error--close m-0 p-0 bw-0 c-w h:c-py tn-all-3 bc-t">
		<svg width="16" height="16" viewBox="0 0 13 13" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
			<line x1="0.355431" y1="11.8007" x2="11.8026" y2="0.353619"></line>
			<line x1="11.8007" y1="12.1592" x2="0.353619" y2="0.712093"></line>
		</svg>
	</button>
	<div class="error--text"></div>
</div>

<script>
	LG.ProfileEdit = class
	{
		saveMsg
		textSave
		saveClose
		errorMsg
		textError
		errorClose
		form
		formData
		lang
		userId
		inputs
		labels

		constructor() {
			this.lang = document.querySelector('[name="lang"]')?.value
			this.userId = document.querySelector('[name="ID"]')?.value
			this.saveMsg = document.querySelector('.lang-profile--save');
			this.saveClose = this.saveMsg.querySelector('.save--close');
			this.errorMsg = document.querySelector('.lang-profile--error');
			this.textError = this.errorMsg?.querySelector('.error--text');
			this.errorClose = this.errorMsg?.querySelector('.error--close');
			this.inputs = document.querySelectorAll('.lang-profile--input')
			this.labels = document.querySelectorAll('.lang-profile--edit')

			this.saveClose?.addEventListener('click', this.closeSave.bind(this))
			this.errorClose?.addEventListener('click', this.closeError.bind(this))

			this.inputs.forEach(e => e.addEventListener('change', this.save.bind(this, e)))
		}

		getFormData() {
			let formData = new FormData()

			formData.append('ID', this.userId)
			formData.append('lang', this.lang)
			formData.append('sessid', BX.bitrix_sessid())
			formData.append('save', 'y')

			return formData
		}

		closeSave() {
			this.saveMsg.classList.remove('rt-0')
			this.saveMsg.classList.add('rt--150%')
		}

		showSave() {
			this.saveMsg.classList.add('rt-0')
			this.saveMsg.classList.remove('rt--150%')

			setTimeout(this.closeSave.bind(this), 10000)
		}

		showError() {
			this.errorMsg.classList.add('rt-0')
			this.errorMsg.classList.remove('rt--150%')

			setTimeout(this.closeError.bind(this), 10000)
		}

		closeError() {
			this.errorMsg.classList.remove('rt-0')
			this.errorMsg.classList.add('rt--150%')
		}

		save(input) {
			this.formData = this.getFormData()
			switch(input.tagName) {
				case 'INPUT':
					this.formData.append(input.name, input.value)
					break;
				case 'SELECT':
					this.formData.append(input.name, input.options[input.selectedIndex].value)
					break;
			}

			this.closeSave()
			this.closeError()

			BX.ajax.runAction(`lang:ajax.page.editUserProfile`, {data: this.formData})
                .then((data) => {
					if(data.status == 'success') {
						if(data.data.componentResult.DATA_SAVED == 'Y') {
							this.showSave()
						} else if(data.data.componentResult.strProfileError != '') {
							this.textError.innerHTML = data.data.componentResult.strProfileError
							this.showError()
						} else {
							this.textError.innerText = "Что то пошло не так"
							this.showError()
						}
					}
				}, (response) => {
					// console.info(response)
					this.textError.innerText = "Что то пошло не так"
					this.showError()
				});
		}
	}
	new LG.ProfileEdit()
</script>