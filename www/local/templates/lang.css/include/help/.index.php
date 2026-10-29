<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Application;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langHelp = "LangHelp{$lang}";
$langBlog = "LangBlog{$lang}";
$templatePath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
$session = Application::getInstance()->getSession();
$sessionFilter = $session->get('helpFilter');

?>

<h1 class="d-n"><?=$APPLICATION->ShowTitle(false)?></h1>

<section class="w-100">
    <div class="lang--filter <?=comp::get('container')?> d-f fd-c rg-32">

        <div class="w-100">
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

        <div class="w-100 p-24 lg:p-32 d-f fd-c rg-16 bi-pyg r-12">

            <div class="w-100 d-f cg-16">
                <div class="fg-1">
                    <input name="name" class="c-dt ap-n w-100 pt-16 pr-32 pb-16 pl-32 lg:pt-24 lg:pr-48 lg:pb-24 lg:pl-48 bc-w bw-1 bs-s bcr-C5C5C5 h:bcr-py r-12 f-16 tn-all-3"
                        type="search" placeholder="Поиск по ключевым словам" value="<?=$sessionFilter['name']?>">
                </div>
                <div class="d-n md:d-f">
                    <button class="<?=comp::get("btnGreenLg")?>">Найти</button>
                </div>
            </div>

            <?/*<div class="w-100 d-n ai-c cg-24 c-w">
                <div class="xl:f-16 ftw-6">Популярные статьи:</div>
                <div class="d-f cg-24">
                    <a href="/" class="f-14 ">Как разместить заказ?</a>
                    <a href="/" class="f-14 ">Как разместить заказ?</a>
                    <a href="/" class="f-14 ">Как разместить заказ?</a>
                </div>
            </div>*/?>
        </div>
    </div>
</section>


<?
$helpList = Data::getElementsObject($langHelp, ['select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID', 'art.VALUE']]);

if(!empty($helpList) && !empty($helpList->getIdList())):
    $articleList = [];

    if(!empty($helpList->getArtList())) {
        foreach ($helpList->getArtList() as $values) {
            if(!empty($values->getValueList())) {
                foreach($values->getValueList() as $value) {
                    $articleList[$value] = $value;
                }
            }
        }

        $artList = Data::getElementsObject($langBlog, [
            'order' => ['NAME' => 'ASC'],
            'select' => ['NAME', 'CODE', 'IBLOCK_SECTION_ID'],
            'filter' => ['ID' => $articleList],
            'limit' => 4
        ]);
    }
    ?>
    <section class="w-100">
        <div class="<?=comp::get("container")?>">
            <div class="<?=comp::get("row")?>">
                <?foreach($helpList as $iHelp):?>
                    <div class="w-100 md:w-6/12 xl:w-4/12 <?=comp::get("setcol")?>">
                        <a href="<?=Data::getDetailPageUrlApiCode($langHelp, $iHelp)?>" class="w-100 h-100 p-32 d-f fd-c rg-32 bi-sbb h:bi-hb r-12 tn-all-3" <?=Data::iblockElementEditAddId($iHelp)?>>
                            <div class="w-100 d-f ai-c cg-24 c-w">
                                <div class="f-18 xl:f-20 fg-1 <?=comp::get("fontUd")?>"><?=$iHelp->getName()?></div>
                                <div class="d-f">
                                    <svg width="9" height="16" viewBox="0 0 9 16" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M0.329505 0.329505C0.768845 -0.109835 1.48116 -0.109835 1.9205 0.329505L8.6705 7.0795C9.10983 7.51884 9.10983 8.23116 8.6705 8.6705L1.9205 15.4205C1.48116 15.8598 0.768845 15.8598 0.329505 15.4205C-0.109835 14.9812 -0.109835 14.2688 0.329505 13.8295L6.28401 7.875L0.329505 1.9205C-0.109835 1.48116 -0.109835 0.768845 0.329505 0.329505Z"/></svg>
                                </div>
                            </div>

                            <?$limit = 1?>
                            <?if(!empty($iHelp->getArt()) && !empty($iHelp->getArt()->getValueList())):?>
                                <object class="w-100 d-f fd-c rg-16 c-w f-12 xl:f-14">
                                    <?foreach($artList as $art):?>
                                        <?if(in_array($art->getId(), $iHelp->getArt()->getValueList())):?>
                                            <a class="tdt-1 tuo-2 h:tdl-u tt-l" href="<?=Data::getDetailPageUrlApiCode($langBlog, $art)?>" target="_blank"><?=$art->getName()?></a>
                                            <?
                                            $limit++;
                                            if($limit == 4) {
                                                break;
                                            }
                                            ?>
                                        <?endif;?>
                                    <?endforeach;?>
                                </object>
                            <?endif;?>
                            <?$limit = 1?>
                        </a>
                    </div>
                <?endforeach;?>
            </div>
        </div>
    </section>
<?endif?>

<?$APPLICATION->IncludeComponent(
    "lang:block","",
    ["elementId"=>226,"path"=>"main/.banner.bottom.php"],
    false,['HIDE_ICONS'=>'Y']
);?>