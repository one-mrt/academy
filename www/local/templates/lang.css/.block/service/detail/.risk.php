<?
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";


$riskOffTitle = Data::getSectionObject($langText, ['select'=>['NAME', 'DESCRIPTION'],'filter'=>['ID' => $arParams['sectionId']]]);
$riskOffList = Data::getElementsObject($langText, ['select'=>['NAME', 'svg', 'text'],'filter'=>['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

if(!empty($riskOffList) && !empty($riskOffList->getIdList())):
    $riskOffIndex = 1;
    ?>
    <section class="w-100">
        <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">

            <?if(!empty($riskOffTitle) && !empty($riskOffTitle->getId())):?>
                <div class="w-100 xl:w-8/12" <?=Data::iblockSectionEditAddId($riskOffTitle)?>>
                    <h2 class="<?=comp::get("h2")?>"><?=$riskOffTitle->getName()?></h2>
                    <?if(!empty($riskOffTitle->getDescription())):?>
                        <div class="f-14"><?=$riskOffTitle->getDescription()?></div>
                    <?endif?>
                </div>
            <?endif?>


            <div class="w-100">
                <div class="<?=comp::get("row")?> rg-32">
                    <?foreach($riskOffList as $risk):
                        $modRiskColor = [
                            'title' => "c-888888",
                            'border' => "bcr-999999"
                        ];
                        if($riskOffIndex % 2 == 0) {
                            $modRiskColor = [
                                'title' => "bi-pyg bgc-t tf-t",
                                'border' => "bcr-21A78E"
                            ];
                        }
                        ?>
                        <div class="w-100 md:w-6/12 xl:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-32">
                            <div class="w-100 h-100 p-32 xl:p-48 d-f fd-c rg-32 bc-w bw-2 bs-s <?=$modRiskColor['border']?> r-12 lh-1" <?=Data::iblockElementEditAddId($risk)?>>

                                <div class="f-20 ftw-6 ff-ud <?=$modRiskColor['title']?>"><?=$risk->getName()?></div>

                                <?if(!empty($risk->getText()) && !empty(!empty($risk->getText()->getAll()))):
                                    if(!empty($risk->getSvg())) {
                                        $svg = file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($risk->getSvg()->getValue()));
                                    }
                                    ?>
                                    <div class="w-100 d-f fd-c rg-16">
                                        <?foreach($risk->getText()->getAll() as $text):?>
                                            <div class="d-f cg-16 ai-c md:f-12 lg:f-14 xl:f-18 ftw-5">
                                                <?if(!empty($svg)):?>
                                                    <div class="d-f"><?=$svg?></div>
                                                <?endif?>
                                                <div class=""><?=$text->getValue()?></div>
                                            </div>
                                        <?endforeach?>
                                    </div>
                                <?endif?>
                            </div>
                        </div>
                        <?$riskOffIndex++?>
                    <?endforeach?>
                </div>
            </div>
        </div>
    </section>
<?endif?>