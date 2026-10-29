<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($shareNameButton)):
    $shareLink = Util::getElementsObject($textTableString, ['select' => ['NAME', 'link', 'svg'], 'filter' => ['IBLOCK_SECTION_ID' => $shareNameButton['ID']]]);

    if(!empty($shareLink)):
        $linkArticle = "{$region->getDomain()->getValue()}{$detailPageUrl}";
        $idShare = rand();
        ?>

        <div class="pn-r f-12 xl:f-14">

            <div class="d-f cg-16 ai-c cr-p pt-8 sm:pt-12 pr-16 sm:pr-24 pb-8 sm:pb-12 pl-12 sm:pl-24 ftw-7 ta-c lh-1 bc-dt c-w bw-1 bs-s bcr-dt h:bcr-w h:bc-w h:c-dt a:tm-s0.9 tn-all-3" onclick="langShareList<?=$idShare?>.classList.toggle('d-n')" role="button" title="<?=$shareNameButton->getName()?>">
                <?if(!empty($shareNameButton->getUfSvg())):
                    $svg = \CFile::GetPath($shareNameButton->getUfSvg());
                    ?>
                    <div class="d-f"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$svg}")?></div>
                <?endif;?>
                <div class=""><?=$shareNameButton->getName()?></div>
            </div>

            <div class="w-100 d-f d-n fd-c cg-8 pn-a t-100% rt-0 bc-dt c-w" id="langShareList<?=$idShare?>">

                <div class="w-100 d-f cg-8 ai-c pt-8 sm:pt-12 pr-16 sm:pr-24 pb-8 sm:pb-12 pl-12 sm:pl-24 h:bc-w h:c-dt btw-1 bts-s btc-w hover:tm-c-110 tn-all-3 cr-p" title="Скопировать" onclick="navigator.clipboard.writeText('<?=$linkArticle?>')">
                    <div class="d-f">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M4 10c0-4.941 1.059-6 6-6s6 1.059 6 6-1.059 6-6 6-6-1.059-6-6"></path><path d="M10 17c-.5 0-1 .5-1 1 0 2 3.898 2 5 2 4.941 0 6-1.059 6-6 0-1.102 0-5-2-5-.53.042-1 .5-1 1 0 5.5-1.5 7-7 7"></path></svg>
                    </div>
                    <div class="">Скопировать</div>
                </div>

                <?foreach($shareLink as $share):?>
                    <a class="w-100 d-f cg-8 ai-c pt-8 sm:pt-12 pr-16 sm:pr-24 pb-8 sm:pb-12 pl-12 sm:pl-24 h:bc-w h:c-dt btw-1 bts-s btc-w hover:tm-c-110 tn-all-3" href="<?=$share->getLink()->getValue()?><?=$linkArticle?>" title="<?=$share->getName()?>" rel="nofollow" target="_blank">
                        <?if(!empty($share->getSvg())):
                            $svg = \CFile::GetPath($share->getSvg()->getValue());
                            ?>
                            <div class="d-f"><?=file_get_contents("{$_SERVER['DOCUMENT_ROOT']}{$svg}")?></div>
                        <?endif;?>
                        <div class=""><?=$share->getName()?></div>
                    </a>
                <?endforeach;?>
            </div>

        </div>
    <?endif;?>
<?endif;?>