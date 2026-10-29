<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();

use Bitrix\Main\Loader;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

Loader::includeModule('lang.css');

$lang = Data::getLang();
$langText = "LangText{$lang}";
$likeList = Data::getElementsObject($langText, ['select' => ['NAME', 'svg'],'filter' => ['IBLOCK_SECTION_ID' => 64]]);
?>

<?if(!empty($arResult['expert']) && !$arResult['expert']->isEmpty()):?>
	<?foreach ($arResult['expert'] as $user):
        if(!empty($user->getName()) || !empty($user->getLastName())) {
            $title = "{$user->getName()} {$user->getLastName()}";
        } else {
            $title = $user->getLogin();
        }
        ?>
        <a href="/experts/<?=$user->getLogin()?>/" class="w-100 p-24 sm:p-32 bw-1 bs-s bcr-CECECE h:bcr-py r-12 bc-w tn-all-3">
            <div class="<?=comp::get("row")?>">
                <div class="w-100 xl:w-6/12 <?=comp::get("setcol")?>">
                    <div class="w-100 h-100 d-f ai-c cg-24">

                        <div class="d-f pn-r">
                            <?if(!empty($user->getPersonalPhoto())):?>
                                <?=Data::getImageId($user->getPersonalPhoto(), [
                                    "alt" => $title,
                                    "fetchpriority" => "high",
                                    "class" => "w-60px h-60px sm:w-90px sm:h-90px oft-cv op-c bw-1 bs-s bcr-CECECE r-100"
                                ])?>
                            <?else:?>
                                <div class="w-60px h-60px sm:w-90px sm:h-90px oft-cv op-c bw-1 bs-s bcr-CECECE r-100"></div>
                            <?endif?>
                            <div class="d-f pn-a rt-6px b-0 sm:rt-0 sm:b-16px c-<?=(!empty($user->getIsOnline())) ? "05c936" : "c90536"?>">
                                <svg width="12" height="12" viewBox="0 0 8 8" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><circle cx="4" cy="4" r="4"/></svg>
                            </div>
                        </div>

                        <div class="d-f fd-c rg-12 fg-1">
                            <div class="f-18 xl:f-20 ftw-6"><?=$title?></div>
                            <div class="c-636363 f-14 lh-1 ftw-5 d-n md:d-b"><?=(!empty($user->getIsOnline())) ? "Сейчас онлайн" : "Сейчас офлайн"?></div>
                            <?if(!empty($likeList) && !empty($likeList->getIdList())):?>
                                <div class="d-f cg-32">
                                    <?foreach($likeList as $like):?>
                                        <div class="d-f ai-c cg-8" title="<?=$like->getName()?>" <?=Data::iblockElementEditAddId($like)?>>
                                            <?if(!empty($like->getSvg())):?>
                                                <div class="d-f"><?=file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($like->getSvg()->getValue()))?></div>
                                            <?endif;?>
                                            <div class="xl:f-20 ftw-6 bi-st bgc-t tf-t lh-1">500</div>
                                        </div>
                                    <?endforeach?>
                                </div>
                            <?endif?>
                        </div>
                    </div>
                </div>
                <div class="w-100 xl:w-6/12 <?=comp::get("setcol")?>">
                    <div class="w-100 h-100 d-f fd-c rg-24">
                        <div class="fg-1"></div>
                        <div class="w-100 ta-r f-12 ftw-5 lh-1 h:c-py tdt-1 tuo-4 tdc-py tn-all-3 h:tdl-u">Открыть профиль</div>
                    </div>
                </div>
            </div>
        </a>
	<?endforeach;?>

	<?if($arResult['nav']->getPageCount() > $arResult['nav']->getCurrentPage()):?>
		<div class="lang-order--nav w-100 d-f jc-c">
			<button class="<?=comp::get("btnGreenSm")?>">Показать еще</button>
		</div>
	<?endif;?>
<?else:?>
	<div class=""><?=$arResult['message']?></div>
<?endif?>