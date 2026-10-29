<?
use Lang\Region;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\Component\Tools;

$region = Region::getCurrent(['select' => ['prefix']]);
$site = ucfirst($region->getPrefix()->getValue());
$structureTableString = "LangStructure{$site}";
$iblock = Util::getIblockObject($structureTableString, ['select' => ['SECTION_PAGE_URL', 'DETAIL_PAGE_URL']]);

if(!empty($iblock)):
    $codeList = Region::getUrlCode();

    if(!empty($codeList))
    {
        if(!empty($codeList[0]) && !empty($codeList[1]))
        {
            $section = Util::getSectionObject($structureTableString, [
                'select' => ['ID', 'NAME', 'UF_LINK'],
                'filter' => ['CODE' => $codeList[1]]
            ]);
            $element = Util::getElementObject($structureTableString, ['ID', 'NAME', 'link'], ['CODE' => $codeList[1]]);
        }
        elseif(!empty($codeList[0]) && empty($codeList[1]))
        {
            $section = Util::getSectionObject($structureTableString, [
                'select' => ['ID', 'NAME', 'UF_LINK'],
                'filter' => ['CODE' => $codeList[0]]
            ]);
            $element = Util::getElementObject($structureTableString, ['ID', 'NAME', 'link'], ['CODE' => $codeList[0]]);
        }
    }

    if(!empty($section)):
        $seoSection = Util::getSeoPropertySection($structureTableString, $section->getId());

        $APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
        $APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
        $APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
        $APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));

    elseif(!empty($element)):

        $seoElement = Util::getSeoPropertyElement($structureTableString, $element->getId());

        $APPLICATION->SetTitle(strip_tags($seoElement['ELEMENT_PAGE_TITLE']));
        $APPLICATION->SetPageProperty("title", strip_tags($seoElement['ELEMENT_META_TITLE']));
        $APPLICATION->SetPageProperty("description", strip_tags($seoElement['ELEMENT_META_DESCRIPTION']));
        $APPLICATION->SetPageProperty("keywords", strip_tags($seoElement['ELEMENT_META_KEYWORDS']));

    elseif(empty($codeList)):
        $section = Util::getSectionObject($structureTableString, ['select' => ['ID', 'NAME', 'UF_LINK'], 'filter' => ['CODE' => "glavnaya"]]);

        if(!empty($section)) {
            $seoSection = Util::getSeoPropertySection($structureTableString, $section->getId());

            $APPLICATION->SetTitle(strip_tags($seoSection['SECTION_PAGE_TITLE']));
            $APPLICATION->SetPageProperty("title", strip_tags($seoSection['SECTION_META_TITLE']));
            $APPLICATION->SetPageProperty("description", strip_tags($seoSection['SECTION_META_DESCRIPTION']));
            $APPLICATION->SetPageProperty("keywords", strip_tags($seoSection['SECTION_META_KEYWORDS']));
        }
        ?>
    <?else:?>
        <??>
    <?endif;?>

<?endif;?>