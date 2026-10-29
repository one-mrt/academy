<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;

$lang = Data::getLang();
$langText = "LangText{$lang}";

$priceTitle = Data::getSectionObject($langText, ['select' => ['NAME', 'DESCRIPTION', 'PICTURE'],'filter' => ['ID' => $arParams['sectionId']]]);

if(!empty($priceTitle) && !empty($priceTitle->getId())):
    $priceRow = Data::getSectionsObject($langText, ['select' => ['NAME', 'UF_TR'],'filter' => ['IBLOCK_SECTION_ID' => $arParams['sectionId']]]);

    if(!empty($priceRow) && !empty($priceRow->getIdList())):
        $priceList = Data::getElementsObject($langText, [
            'select' => ['NAME', 'IBLOCK_SECTION_ID', 'text'],
            'filter' => ['IBLOCK_SECTION_ID' => $priceRow->getIdList()]
        ]);
        $indexRow = 0;
        $modRow = comp::get("row", 'fw-n xl:fw-w', 'fw-w');
        $modColName = "w-250px sm:w-300px md:w-40";
        $modColData = "w-200px md:w-20";
        ?>
        <section class="w-100">
            <div class="<?=comp::get("container-full")?>">
                <div class="w-100 pn-r of-h pix-32 pby-48 lg:pby-64 xxl:pix-0 xxl:pby-86 d-f fd-c rg-32 r-24 bc-w">

                    <?if(!empty($priceTitle->getPicture())):?>
                        <?=Data::getImageId($priceTitle->getPicture(), [
                            "title" => strip_tags($priceTitle->getName()),
                            "alt" => strip_tags($priceTitle->getName()),
                            "loading" => "lazy",
                            "class" => "pn-a t-0 rt-0 b-0 l-0 w-100 h-100 oft-cv op-c r-12",
                        ])?>
                    <?endif?>

                    <div class="<?=comp::get('container-p')?> d-f fd-c rg-32 pn-r">

                        <div class="w-100 md:w-10/12 lg:w-8/12 d-f fd-c rg-16" <?=Data::iblockSectionEditAddId($priceTitle)?>>
                            <h2 class="<?=comp::get("h2")?> bi-pyg bgc-t tf-t"><?=$priceTitle->getName()?></h2>
                            <?if(!empty($priceTitle->getDescription())):?>
                                <div class="f-12 lg:f-14"><?=$priceTitle->getDescription()?></div>
                            <?endif?>
                        </div>

                        <div class="w-100 ofx-a">
                            <div class="w-850px sm:w-900px md:w-100 d-f fd-c rg-16 lh-1">
                            
                                <?foreach($priceRow as $row):?>
                                    <?if($indexRow == 0):?>
                                        <div class="xl:mnw-100 pix-24 md:pix-24 xl:pix-32" <?=Data::iblockSectionEditAddId($row)?>>
                                            <div class="<?=$modRow?>">
                                                <div class="<?=$modColName?> <?=comp::get("setcol")?>">
                                                    <div class="c-py f-12 md:f-10 lg:f-12 xl:f-14 tt-l ftw-6"><?=$row->getName()?></div>
                                                </div>

                                                <?foreach($row->getUfTr() as $text):?>
                                                    <div class="<?=$modColData?> <?=comp::get("setcol")?>">
                                                        <div class="c-py f-12 md:f-10 lg:f-12 xl:f-14 tt-l ftw-6"><?=$text?></div>
                                                    </div>
                                                <?endforeach?>
                                            </div>
                                        </div>
                                    <?else:?>
                                        <div class="lang-price--item xl:mnw-100 xl:of-h d-f fd-c bc-w h:bc-py h:c-w r-12 tn-all-3">
                                            <div class="lang-price--title w-100 p-24 md:p-24 xl:p-32 cr-p" <?=Data::iblockSectionEditAddId($row)?>>
                                                <div class="<?=$modRow?>">
                                                    <div class="<?=$modColName?> <?=comp::get("setcol")?> d-f cg-12 lh-1">
                                                        <div class="d-f r-100 bc-w">
                                                            <svg class="lang-price--icon tn-all-3" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M0 9.003C0 7.759 0.236 6.589 0.708 5.493C1.18067 4.39767 1.822 3.44467 2.632 2.634C3.442 1.82333 4.394 1.18167 5.488 0.709C6.582 0.236333 7.75167 0 8.997 0C10.2423 0 11.4123 0.236333 12.507 0.709C13.6023 1.181 14.5553 1.82133 15.366 2.63C16.1767 3.43867 16.8183 4.391 17.291 5.487C17.7637 6.583 18 7.753 18 8.997C18 10.241 17.7637 11.411 17.291 12.507C16.819 13.6023 16.1783 14.5553 15.369 15.366C14.5597 16.1767 13.6073 16.8183 12.512 17.291C11.4167 17.7637 10.247 18 9.003 18C7.759 18 6.589 17.764 5.493 17.292C4.397 16.82 3.444 16.1787 2.634 15.368C1.824 14.5573 1.18233 13.6053 0.709 12.512C0.235666 11.4187 -0.000667572 10.249 0 9.003ZM8.5 10.412L6.734 8.646C6.64067 8.55267 6.52933 8.50933 6.4 8.516C6.27067 8.522 6.159 8.572 6.065 8.666C5.97167 8.75933 5.925 8.875 5.925 9.013C5.925 9.151 5.97167 9.26433 6.065 9.353L8.435 11.723C8.59633 11.885 8.78467 11.966 9 11.966C9.21533 11.966 9.40367 11.885 9.565 11.723L11.954 9.335C12.0473 9.24167 12.094 9.13 12.094 9C12.094 8.87 12.0473 8.75867 11.954 8.666C11.8607 8.57333 11.7447 8.52633 11.606 8.525C11.4673 8.52367 11.354 8.57033 11.266 8.665L9.5 10.412V6C9.5 5.858 9.452 5.73933 9.356 5.644C9.26 5.54867 9.141 5.50067 8.999 5.5C8.857 5.49933 8.73833 5.54733 8.643 5.644C8.54767 5.74067 8.5 5.85933 8.5 6V10.412Z" fill="url(#paint0_linear_105_1029)"/>
                                                                <defs>
                                                                    <linearGradient id="paint0_linear_105_1029" x1="9" y1="0" x2="9" y2="18" gradientUnits="userSpaceOnUse">
                                                                        <stop stop-color="#A16ABB"/><stop offset="1" stop-color="#724A98"/>
                                                                    </linearGradient>
                                                                </defs>
                                                            </svg>
                                                        </div>
                                                        <div class="ff-ud f-14 xl:f-16 tt-l ftw-6"><?=$row->getName()?></div>
                                                    </div>

                                                    <?foreach($row->getUfTr() as $text):?>
                                                        <div class="<?=$modColData?> <?=comp::get("setcol")?>">
                                                            <div class="ff-ud f-14 xl:f-16 tt-l ftw-6"><?=$text?></div>
                                                        </div>
                                                    <?endforeach?>
                                                </div>
                                            </div>

                                            <?if(in_array($row->getId(), $priceList->getIblockSectionIdList())):?>
                                                <div class="lang-price--description w-100 d-n d-f fd-c rg-12 pix-24 md:pix-24 xl:pix-32 pb-24 md:pb-24 xl:pb-32">
                                                    <?foreach($priceList as $price):?>
                                                        <div class="w-100" <?=Data::iblockElementEditAddId($price)?>>
                                                            <div class="<?=$modRow?>">
                                                                <div class="<?=$modColName?> <?=comp::get("setcol")?> d-f cg-12 lh-1">
                                                                    <div class="f-12 xl:f-14 tt-l ftw-5 pl-24 md:pl-24 xl:pl-32"><?=$price->getName()?></div>
                                                                </div>

                                                                <?foreach($price->getText()->getAll() as $text):?>
                                                                    <div class="<?=$modColData?> <?=comp::get("setcol")?>">
                                                                        <div class="f-12 xl:f-14 tt-l ftw-5"><?=$text->getValue()?></div>
                                                                    </div>
                                                                <?endforeach?>
                                                            </div>
                                                        </div>
                                                    <?endforeach?>
                                                </div>
                                            <?endif;?>

                                        </div>
                                    <?endif?>

                                    <?$indexRow++?>
                                <?endforeach;?>

                                <script>
                                    document?.querySelectorAll('.lang-price--item')?.forEach((item) => {
                                        item.querySelector('.lang-price--title')?.addEventListener('click', (e) => {
                                            item.querySelector('.lang-price--icon')?.classList.toggle('tm-r180');
                                            item.querySelector('.lang-price--description')?.classList.toggle('d-n');
                                        })
                                    });
                                </script>

                            </div>
                        </div>

                    </div>

                </div>
            </div>
        </section>
    <?endif;?>
<?endif;?>