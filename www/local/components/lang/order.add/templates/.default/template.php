<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Lang\Css\Component as comp;
use Bitrix\Main\Localization\Loc;
?>


<div class="lang-order-add--content w-100 h-100 d-f ai-c jc-c bc-w">
	<div class="w-100 sm:mw-540px p-32 sm:p-64 d-f fd-c rg-32 r-24">

		<div class="w-100 d-f fd-c rg-16">
			<h1 class="mb-0 ff-ud f-24 ftw-6 tt-l ls--1 lh-1.125 ta-c">Создание заказа</h1>
			<div class="f-12 sm:f-14 ta-c">
				наши эксперты могут помочь как написать работу с нуля по вашим требованиям, так и помочь с уже начатой работой. Создайте заказ и опишите все пожелания, наши специалисты в ближайшее время примут заказ
			</div>
		</div>

		<?if(!empty($arResult['error'])):?>
			<div class="w-100 d-f fd-c rg-8">
				<?foreach($arResult['error'] as $mError):?>
					<div class=""><?=$mError?></div>
				<?endforeach;?>
			</div>
		<?endif;?>

		<form class="w-100 d-f fd-c rg-24" method="post" action="<?=$arResult["AUTH_URL"]?>">

			<?=bitrix_sessid_post();?>

			<input type="hidden" name="order" value="y">

			<div class="w-100 d-f fd-c rg-16">
				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3" required
						type="text" name="name" maxlength="255" value="<?=$arResult["name"]?>" placeholder="Тема работы">
				</div>

				<div class="lang-select--list w-100 pn-r">

					<input class="lang-select--text w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3 pn-r z-1"
						type="text" name="typeV" maxlength="255" value="" placeholder="тип работы" required>

					<div class="lang-select--close w-100 h-100 pn-f t-0 rt-0 b-0 l-0 d-n"></div>

					<div class="lang-select--items w-100 mxh-200px ofw-h r-8 d-n d-f fd-c pn-a t-0 l-0 pt-52 bc-C5C5C5 bw-1 bs-s bcr-C5C5C5">
						<div class="w-100 h-100 d-f fd-c ofy-a f-14">
							<?foreach($arResult['type'] as $type):?>
								<label for="type<?=$type['id']?>" class="lang-select--item m-0 pby-8 pix-24 cr-p h:bc-w tn-all-3">
									<input class="d-n" type="radio" name="type" value="<?=$type['id']?>" id="type<?=$type['id']?>" required>
									<div class="lang-select--name"><?=$type['name']?></div>
								</label>
							<?endforeach?>
						</div>
					</div>
				</div>

				<div class="">
					<input class="w-100 pt-16 pr-24 pb-16 pl-24 r-8 bw-1 bs-s bcr-C5C5C5 h:bcr-py c-dt tn-all-3"
						type="text" name="email" maxlength="255" value="<?=$arResult["email"]?>" placeholder="Email" required>
				</div>
			</div>

			<div class="d-f jc-c">
				<button class="<?=comp::get("btnPurpleLg")?>"type="submit" name="add" value="Создать заказ">Создать заказ</button>
			</div>

		</form>
		<script>
			LG.selectList = class {
				select
				fieldText
				itemsContainer
				itemList
				close
				searchResultIndex = 0

				constructor(select) {
					this.select = select
					this.initSelect()
				}

				initSelectElement() {
					this.fieldText = this.select.querySelector('.lang-select--text')
					this.itemsContainer = this.select.querySelector('.lang-select--items')
					this.itemList = this.select.querySelectorAll('.lang-select--item')
					this.close = this.select.querySelector('.lang-select--close')
				}

				closeList() {
					if(!this.itemsContainer.classList.contains('d-n')) {
						this.close.classList.add('d-n')
						this.itemsContainer.classList.add('d-n')
					}
				}

				show() {
					if(this.itemsContainer.classList.contains('d-n')) {
						this.close.classList.remove('d-n')
						this.itemsContainer.classList.remove('d-n')
					}
				}

				initClose() {
					this.close.addEventListener('click', this.closeList.bind(this))
				}

				searchItem(item) {
					let searchText = this.fieldText.value.toUpperCase()
					let text = item.querySelector('.lang-select--name')
					let currentText = text.textContent || text.innerText

					if (currentText.toUpperCase().indexOf(searchText) > -1) {
						item.classList.remove('d-n')
					} else {
						item.classList.add('d-n')
					}
				}

				search() {
					this.searchResultIndex = 0;
					this.itemList?.forEach(item => this.searchItem(item))
				}

				initFieldText() {
					this.fieldText.addEventListener('input', this.search.bind(this))
					this.fieldText.addEventListener('focusin', this.show.bind(this))
				}

				initItem(text) {
					this.fieldText.value = text.innerText
					this.closeList()
				}

				initList() {
					this.itemList?.forEach(item => {
						item.querySelector('input')
							?.addEventListener('change', this.initItem.bind(this, item.querySelector('.lang-select--name')))
					})
				}

				initSelect() {
					this.initSelectElement()
					this.initClose()
					this.initFieldText()
					this.initList()
				}
			}

			document.querySelectorAll('.lang-order-add--content .lang-select--list')
				?.forEach(select => new LG.selectList(select))
		</script>
	</div>
</div>