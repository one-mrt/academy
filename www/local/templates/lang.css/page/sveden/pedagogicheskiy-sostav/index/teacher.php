<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$teacherTableString = "LangTeacher{$site}";

$sectionList = Util::getSectionsObject($teacherTableString, ['select' => ['NAME', 'UF_SVG']]);
$tList = Util::getElementsObject($teacherTableString, [
    'select' => ['NAME', 'IBLOCK_SECTION_ID', 'file.FILE', 'link'],
    'filter' => ["!IBLOCK_SECTION_ID" => ""]
]);

$teacherList = [];

foreach($sectionList as $sec)
{
    $teacherList[$sec->getId()]['name'] = $sec->getName();
    $teacherList[$sec->getId()]['item'] = [];

    if(!empty($sec->getUfSvg()))
    {
        $teacherList[$sec->getId()]['svg'] = \CFile::GetPath($sec->getUfSvg());
    }

    foreach($tList as $iTeacher)
    {
        if($iTeacher->getIblockSectionId() == $sec->getId())
        {
            $pred =& $teacherList[$sec->getId()]['item'];
            $pred[$iTeacher->getId()]['name'] = $iTeacher->getName();

            if($iTeacher->getFile())
            {
                $pred[$iTeacher->getId()]['doc'] = \CFile::GetPath($iTeacher->getFile()->getFile()->getId());
            }

            if($iTeacher->getLink())
            {
                $pred[$iTeacher->getId()]['link'] = $iTeacher->getLink()->getValue();
            }
        }
    }
}

?>

<section class="w-100">
    <div class="<?=comp::get("container")?> d-f fd-c rg-32">

        <div class="w-100 d-f jc-c">
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

        <div class="w-100 d-f fd-c rg-32 xl:rg-48">

            <div class="w-100">
                <h1 class="<?=comp::get('titleh1')?>"><?=$APPLICATION->ShowTitle(false)?></h1>
            </div>

            <div class="<?=comp::get("row")?> rg-32">

                <?if(!empty($teacherList)):?>
                    <?foreach($teacherList as $teacher):?>
                        <div class="w-100 pr-16 pl-16 fg-0 fs-0" <?=Util::iblockSectionEditAddId($teacher)?>>
                            <div class="d-f ai-s cg-16 lh-1 pn-r">
                                
                                <div class="w-75px h-75px pn-r z-1 d-f ai-c jc-c p-16 r-12 bc-dt"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$teacher['svg']}")?></div>

                                <div class="d-f fd-c fg-1 pb-32 pn-r z-1">
                                    <div class="d-f ai-c ftw-6 f-24 h-75px">
                                        <div class=""><?=$teacher['name']?></div>
                                    </div>
                                    <div class="w-100 d-f fd-c ai-s rg-16 f-14 xl:f-16">
                                        <?foreach($teacher['item'] as $item):
                                            $link = $item['doc'] ? $item['doc'] : $item['link'];
                                            ?>
                                            <a href="<?=$link?>" <?=(!empty($item['link']) ? 'target="_blank" rel="nofollow"' : '')?> <?=Util::iblockElementEditAddId($teacher)?> class="tdl-u tdt-1 tuo-2 bc-t h:c-py tn-all-3"><?=$item['name']?></a>
                                        <?endforeach;?>
                                    </div>
                                </div>

                                <div class="w-75px pn-a t-75 b-0 l-0 ml-37 bbw-2 bbs-s bbc-dt blw-2 bls-s blc-dt"></div>
                            </div>
                        </div>
                    <?endforeach;?>
                <?endif;?>
            </div>
        </div>
    </div>
</section>