<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

if(!empty($element)):
    $det = "";
    $modPicture = "";
    if(!empty($element->getDetailPicture()))
    {
        $det = 'data-bg="' . CFile::GetPath($element->getDetailPicture()) . '"';
        $modPicture = "bgs-cr bgr-nr bp-c bi-load lazyload";
    }
    ?>

    <section class="w-100 pn-r">
        <div class="<?=comp::get("container")?>">
            <div class="w-100 pn-r sm:ar-100x125 md:ar-10x8 lg:ar-10x6 xl:ar-10x4 of-h d-f fd-c r-24 xl:r-48 p-32 xl:p-64 bc-w bi-b <?=$modPicture?>" <?=$det?>>

                <div class="<?=comp::get("row")?> h-100 pn-r z-1 rg-32 fg-1">

                    <div class="w-100 pr-16 pl-16 fg-1 fs-0 d-f fd-c jc-s c-w">
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

                    <div class="w-100 lg:w-4/12 xl:w-8/12 pr-16 pl-16 fg-1 xl:fg-0 fs-0 d-f fd-c rg-16 jc-e c-w pt-32 lg:pt-0">
                        <h1 class="mb-0 f-24 sm:f-32 md:f-48 xl:f-60 ftw-5 lh-1 ff-ot"><?=$APPLICATION->ShowTitle(false)?></h1>
                        <?if(!empty($element->getDetailText())):?>
                            <div class="f-12 xl:f-16"><?=$element->getDetailText()?></div>
                        <?endif;?>
                    </div>

                    <div class="w-100 lg:w-4/12 xl:w-4/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-24 ai-e jc-e">

                        <?if(!empty($element->getLv())):?>
                            <a href="<?=$element->getLv()->getValue()?>" rel="nofollow" target="_blank" class="w-100 m-0 d-f ai-c cg-16 pt-12 pr-24 pb-12 pl-24 lg:pt-16 lg:pr-32 lg:pb-16 lg:pl-32 f-14 lg:f-16 ftw-5 lg:ftw-7 ta-c lh-1 bc-py c-w bw-1 bs-s bcr-py r-12 h:bc-w h:c-dt a:tm-s0.9 tn-all-3">
                                <div class="fg-1 ta-l"><?=$element->getLv()->getDescription()?></div>
                                <div class="d-f">
                                    <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                        <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                    </svg>
                                </div>
                            </a>
                        <?endif;?>

                        <?if(!empty($element->getLban()->getAll())):
                            foreach($element->getLban()->getAll() as $lValue):?>
                                <a href="<?=$lValue->getValue()?>" class="<?=comp::get("btnPy", "w-100 bc-w c-py h:bc-py h:c-w r-12 d-f ai-c cg-16", "bc-py c-w h:bc-w h:c-dt r-999")?> ws-nw" rel="nofollow" target="_blank">
                                    <div class="fg-1 ta-l"><?=$lValue->getDescription()?></div>
                                    <div class="d-f">
                                        <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <rect class="fil-w" width="23" height="23" rx="5"></rect>
                                            <path class="fil-py" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                        </svg>
                                    </div>
                                </a>
                            <?endforeach;?>
                        <?endif;?>
                    </div>
                </div>

                <?if(!empty($det)):?>
                    <div class="pn-a i-0 bi-lg180b"></div>
                <?endif;?>
            </div>
        </div>
    </section>
<?endif;?>