<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Util::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $helpText = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'link'], ['ID' => $arParams['elementId']]);

    if(!empty($helpText) && !empty($helpText->getId())):?>
        <section class="w-100">
            <div class="<?=comp::get('container')?> d-f fd-c rg-32" <?=Data::iblockElementEditAddId($helpText)?>>

                <div class="w-100 xl:w-8/12 d-f fd-c rg-16">
                    <h2 class="<?=comp::get("h2")?>"><?=$helpText->getName()?></h2>
                    <?if(!empty($helpText->getPreviewText())):?>
                        <div class="f-14 xl:f-16"><?=$helpText->getPreviewText()?></div>
                    <?endif;?>
                </div>

                <div class="w-100 d-f fd-c">
                    <?if(!empty($helpText->getDetailText())):?>
                        <div class="lang-seo--block w-100 tp-h tnd-300ms of-h mxh-120px pn-r">
                            <div class="lang-seo--text w-100"><?=$helpText->getDetailText()?></div>
                            <div class="seo--wrap w-100 h-100 pn-a t-0 rt-0 b-0 l-0 bi-ht d-n"></div>
                        </div>
                    <?endif?>

                    <?if(!empty($helpText->getLink()) && !empty($helpText->getLink()->getDescription())):?>
                        <div class="more--block d-f d-n jc-s ai-c cr-p c-724A98 h:c-dt tn-all-3">
                            <button class="seo--more bc-t m-0 p-0 d-f cg-12 ai-c jc-s bw-0" title="<?=strip_tags($helpText->getLink()->getDescription())?>">
                                <div class="ff-ud tt-l ls--1 ftw-6 lh-1 f-20"><?=$helpText->getLink()->getDescription()?></div>
                                <div class="d-f">
                                    <svg width="7" height="14" viewBox="0 0 7 14" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M1.00002 1L6.00002 6.83333L1.00002 12.6667" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                </div>
                            </button>
                        </div>
                    <?endif?>
                </div>
            </div>
        </section>

        <script data-sckip-moving="true">
            function hideShowSeoBlock() {
                let blockSeo = document.querySelector('.lang-seo--block')
                let blockText = document.querySelector('.lang-seo--text')
                let blockWrap = document.querySelector('.seo--wrap')
                let blockMore = document.querySelector('.more--block')
                let btnMore = document.querySelector('.seo--more')
                let blockHeight = blockText?.clientHeight

                showText = () => {
                    blockSeo.classList.remove('mxh-120px')
                    blockSeo.style.height = `${blockHeight}px`;
                    blockMore.classList.add('d-n')
                    blockWrap.classList.add('d-n')

                    setTimeout(blockSeo.removeAttribute('style'), 300)
                }

                if(blockHeight > 120) {
                    blockMore.classList.remove('d-n')
                    blockWrap.classList.remove('d-n')
                    blockSeo.style.height = "120px"

                    btnMore?.addEventListener('click', showText.bind(false))
                }
            }

            document.addEventListener('DOMContentLoaded', hideShowSeoBlock.bind(false))
        </script>

    <?endif?>
<?endif?>