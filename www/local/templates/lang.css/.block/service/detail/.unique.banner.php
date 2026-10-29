<?
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

if(!empty($arParams['elementId'])):
    $unique = Data::getElementObject($langText, ['NAME', 'PREVIEW_TEXT', 'PREVIEW_PICTURE', 'DETAIL_PICTURE', 'text'], ['ID' => $arParams['elementId']]);

    if(!empty($unique)):?>
        <section class="w-100">
            <div class="<?=comp::get('container-full')?>">
                <div class="w-100 pn-r of-h pix-32 pby-48 xxl:pix-0 xxl:pby-86 d-f fd-c rg-32 r-24 bc-w">

                    <?if(!empty($unique->getDetailPicture())):?>
                        <?=Data::getImageId($unique->getDetailPicture(), [
                            "title" => strip_tags($unique->getName()),
                            "alt" => strip_tags($unique->getName()),
                            "loading" => "lazy",
                            "class" => "pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c r-12",
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> pn-r">
                        <div class="<?=comp::get('row')?> rg-32 xl:rg-48 jc-c lg:jc-s">

                            <div class="w-100 md:w-8/12 lg:w-8/12 xl:w-6/12 <?=comp::get('setcol')?> d-f fd-c rg-24">
                                <h2 class="<?=comp::get("h2")?>"><?=$unique->getName()?></h2>
                                <?if(!empty($unique->getPreviewText())):?>
                                    <div class="f-14 xl:f-16"><?=$unique->getPreviewText()?></div>
                                <?endif?>
                            </div>

                            <div class="w-6/12 md:w-4/12 lg:w-4/12 xl:w-6/12 <?=comp::get('setcol')?>">
                                <?if(!empty($unique->getPreviewPicture())):?>
                                    <div class="w-100 h-100 pn-r">
                                        <?=Data::getImageId($unique->getPreviewPicture(), [
                                            "title" => strip_tags($unique->getName()),
                                            "alt" => strip_tags($unique->getName()),
                                            "loading" => "lazy",
                                            "class" => "w-100 h-100 md:pn-a t-0 rt-0 b-0 l-0 oft-c op-c",
                                        ])?>
                                    </div>
                                <?endif?>
                            </div>

                            <?if(!empty($unique->getText()) && !empty($unique->getText()->getAll())):?>
                                <div class="w-100 <?=comp::get('setcol')?>">
                                    <div class="<?=comp::get("row")?> rg-16">
                                        <?foreach ($unique->getText()->getAll() as $text):?>
                                            <div class="w-100 md:w-6/12 lg:w-5/12 xl:w-5/12 <?=comp::get('setcol')?>">
                                                <div class="d-f ai-c cg-16 lh-1">
                                                    <div class="d-f">
                                                        <svg width="31" height="31" viewBox="0 0 31 31" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path fill="url(#langIcon<?=1?>)" d="M15.0312 0C6.74311 0 0 6.74311 0 15.0312C0 23.3194 6.74311 30.0625 15.0312 30.0625C23.3194 30.0625 30.0625 23.3194 30.0625 15.0312C30.0625 6.74311 23.3194 0 15.0312 0ZM22.854 9.99361L13.1415 21.5561C13.035 21.683 12.9024 21.7855 12.7528 21.8567C12.6032 21.9279 12.44 21.9661 12.2743 21.9688H12.2548C12.0927 21.9687 11.9325 21.9346 11.7845 21.8686C11.6364 21.8026 11.5039 21.7062 11.3956 21.5857L7.23307 16.9607C7.12735 16.8486 7.04512 16.7165 6.9912 16.5721C6.93728 16.4278 6.91276 16.2741 6.91908 16.1201C6.9254 15.9662 6.96243 15.815 7.028 15.6756C7.09357 15.5361 7.18636 15.4112 7.3009 15.3081C7.41545 15.205 7.54945 15.1259 7.69502 15.0753C7.84059 15.0247 7.99479 15.0038 8.14857 15.0137C8.30236 15.0236 8.45261 15.0641 8.5905 15.1329C8.7284 15.2017 8.85115 15.2973 8.95154 15.4143L12.2245 19.0507L21.0835 8.50639C21.2822 8.27664 21.5634 8.13432 21.8662 8.11019C22.169 8.08606 22.4691 8.18206 22.7017 8.37744C22.9343 8.57282 23.0807 8.85188 23.1092 9.15431C23.1377 9.45673 23.046 9.75822 22.854 9.99361V9.99361Z"/>
                                                            <defs>
                                                                <linearGradient id="langIcon<?=1?>" x1="15.0312" y1="0" x2="15.0313" y2="30.0625" gradientUnits="userSpaceOnUse">
                                                                <stop stop-color="#49BCC9"/>
                                                                <stop offset="1" stop-color="#2F65B8"/>
                                                                </linearGradient>
                                                            </defs>
                                                        </svg>
                                                    </div>
                                                    <div class="ftw-5 f-14 xl:f-18"><?=$text->getValue()?></div>
                                                </div>
                                            </div>
                                        <?endforeach?>
                                    </div>
                                </div>
                            <?endif?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>