<?
use Lang\Region;
use Lang\Main\Iblock\Util;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\Component\Tools;

$region = Region::getCurrent(['select' => ['prefix']]);
$site = ucfirst($region->getPrefix()->getValue());
$structureTableString = "LangStructure{$site}";
$section = Util::getSection($structureTableString, ['ID', 'NAME', 'UF_LINK'], ['CODE' => Region::getCurrentCode()]);
$element = Util::getElement($structureTableString, ['ID', 'NAME', 'l' => 'link.VALUE'], ['CODE' => Region::getCurrentCode()]);

if(!empty($section)):

elseif(!empty($element)):

elseif(empty(Region::getCurrentCode())):?>

<?else:?>
    <?=Tools::process404(
        trim($arParams["MESSAGE_404"] ?? '') ?: Loc::getMessage("BLOG_MESSAGE_404"),
        true,
        true,
        true,
        "/404/index.php"
    );?>
<?endif;?>