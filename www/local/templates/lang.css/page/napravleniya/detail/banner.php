<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element)):?>
    <section class="w-100 pn-r">
        <div class="<?=comp::get("container")?>">
            <div class="<?=comp::get("row")?> rg-32">
                <div class="w-100 xl:w-7/12 pr-16 pl-16 fg-0 fs-0">
                    <div class="w-100 h-100 d-f fd-c rg-32 p-32 bc-e5ebfa99 r-24 xl:r-24">

                        <div class="fg-0">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:breadcrumb",
                                "breadcrumb.dark",
                                [
                                    "PATH" => "",
                                    "SITE_ID" => SITE_ID,
                                    "START_FROM" => "0"
                                ],
                                false,
                                ['HIDE_ICONS' => 'Y']
                            );?>
                        </div>

                        <div class="w-100 d-f fd-c rg-32 fg-1">

                            <div class="w-100 d-f fd-c rg-24 fg-1">
                                <?if(!empty($element->getStatus())):?>
                                    <div class="d-f fw-w cg-4 c-py ftw-5 ff-ot f-14 xl:f-18">
                                        <div class="">Идет набор</div>
                                        <div class="">|</div>
                                        <div class="">Старт программы <?=FormatDate('j F Y', strtotime($element->getStatus()->getValue()));?></div>
                                    </div>
                                <?endif;?>

                                <h1 class="mb-0 f-24 md:f-32 xl:f-32 ftw-5 lh-1 ff-ot"><?=$APPLICATION->ShowTitle(false)?></h1>

                                <?if(!empty($element->getDetailText())):?>
                                    <div class="f-14 xl:f-16"><?=$element->getDetailText()?></div>
                                <?endif;?>
                            </div>

                            <div class="w-100">
                                <button class="lang--dpo <?=comp::get("btnPy")?>" data-fancybox="" data-type="ajax" data-close-button="false" data-click="null"
                                    data-close-click="false"
                                    data-src="<?=SITE_TEMPLATE_PATH?>/ajax/dpo/?idir=<?=$element->getId()?>">Подать заявку</button>
                                <?
                                /*<script>
                                    document.querySelector('.lang--dpo').addEventListener('click', () => {
                                        Fancybox.show(
                                            [{
                                                src: "<?=SITE_TEMPLATE_PATH?>/ajax/dpo/?idir=<?=$element->getId()?>",
                                                type: "ajax"
                                            }],
                                            {
                                                mainClass: "lang-ruk-form--window",
                                                closeButton: false
                                            }
                                        );
                                    });
                                </script>*/
                                ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="w-100 xl:w-5/12 pr-16 pl-16 fg-0 fs-0">
                    <div class="<?=comp::get("row")?> rg-24">

                        <?
                        $listBannerText = [];

                        if(!empty($element->getTime()))
                        {
                            $listBannerText[] = [
                                'title' => 'Срок обучения',
                                'value' => $element->getTime()->getValue()
                            ];
                        }

                        if(!empty($element->getSum()))
                        {
                            $sm = number_format($element->getSum()->getValue(), 0, '.', ' ');
                            $listBannerText[] = [
                                'title' => 'Стоимость',
                                'value' => "{$sm}  ₽ (возможно деление платежа на 2 части)"
                            ];
                        }

                        if(!empty($element->getMode()))
                        {
                            $listBannerText[] = [
                                'title' => 'Режим занятий',
                                'value' => "{$element->getMode()->getValue()}  ₽ (возможно деление платежа на 2 части)"
                            ];
                        }

                        if(!empty($element->getRes()))
                        {
                            $elB = Util::getElement($textSiteTableString, ['PREVIEW_TEXT', 'DETAIL_TEXT'], ['ID' => $element->getRes()->getValue()]);

                            if(!empty($elB))
                            {
                                $listBannerText[] = [
                                    'title' => 'Результат обучения',
                                    'value' => "{$elB['PREVIEW_TEXT']}"
                                ];
                            }
                        }

                        ?>

                        <?foreach($listBannerText as $vLBT):?>
                            <div class="<?=comp::get("col")?> md:w-6/12 xl:w-100">
                                <div class="w-100 h-100 d-f fd-c rg-8 bc-w p-24 bw-1 bs-s bcr-C6C6C6 r-12">
                                    <div class="f-18 ftw-5 ff-ot"><?=$vLBT['title']?></div>
                                    <div class="f-14 xl:f-16"><?=$vLBT['value']?></div>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>

        </div>
    </section>
<?endif;?>