<?
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;
?><section class="w-100 pt-16">
    <div class="<?=comp::get("container")?>">
        <div class="w-100 p-32 xl:p-64 bc-w r-48 d-f fd-c ai-c rg-32">
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

            <div class="<?=comp::get("row")?> rg-32 jc-c">
                <div class="w-100 pr-16 pl-16 fg-0 fs-0">
                    <h1 class="<?=comp::get("titleh1")?>"><?=$APPLICATION->ShowTitle(false)?></h1>
                </div>

                <?if(!empty($section) && !empty($section->getUfIfs())):
                    $docList = Util::getElementsObject($textSiteTableString, [
                        'select' => ['NAME', 'PREVIEW_TEXT', 'link', 'file'],
                        'filter' => ['IBLOCK_SECTION_ID' => $section->getUfIfs()]
                    ]);

                    if(!empty($docList->getIdList())):?>
                        <?foreach($docList as $doc):
                            $link = "";
                            $name = $doc->getName();
                            $description = $doc->getPreviewText();

                            if(!empty($doc->getLink()))
                            {
                                $link = $doc->getLink()->getValue();
                                if(!empty($doc->getLink()->getDescription()))
                                {
                                    $name = $doc->getLink()->getDescription();
                                }
                            }

                            if(!empty($doc->getFile()->getAll()))
                            {
                                foreach($doc->getFile()->getAll() as $file)
                                {
                                    $link = \CFile::GetPath($file->getValue());
                                    break;
                                }
                            }

                            if(!empty($description)): ?>
                                <div class="w-100 pr-16 pl-16 fg-0 fs-0 doc-item-wrapper xl:w-8/12">
                                    <div class="doc-accordion--item r-12 cg-16 pt-16 pr-24 pb-16 pl-24 bc-EDF0F9" <?=Util::iblockElementEditAddId($doc)?> style="cursor:pointer;">
                                        <div class="d-f ai-c cg-16">
                                            <span class="fg-1 ftw-5 f-14 lg:f-16 xl:f-18 h:underline" style="cursor:pointer;">
                                                <?=$name;?>
                                            </span>
                                            <div class="doc-accordion--icon fg-0 d-f r-100% bc-dt h:bc-py c-w p-8 tn-all-3">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" stroke="currentColor" xmlns="http://www.w3.org/2000/svg">
                                                    <line y1="8" x2="16" y2="8"/>
                                                    <line x1="8" y1="16" x2="8"/>
                                                </svg>
                                            </div>
                                        </div>
                                        <div class="doc-accordion--description d-n pt-16 f-14" style="border-top:1px solid rgba(0,0,0,0.1); margin-top:16px;">
                                            <?=$description?>
                                        </div>
                                    </div>
                                </div>
                            <?else: ?>
                                <div class="w-100 xl:w-8/12 pr-16 pl-16 fg-0 fs-0 d-f ai-c">
                                    <a class="w-100 h-100 d-f ai-c cg-16 r-12 p-24 bc-EDF0F9 lh-1 h:bc-dt h:c-w tn-all-3" href="<?=$link?>" <?=Util::iblockElementEditAddId($doc)?> <?=(!empty($doc->getFile()->getAll())) ? "target=\"_blank\"" : ""?>>
                                        <div class="fg-1 ftw-5 f-14 lg:f-16 xl:f-18"><?=$name;?></div>
                                        <div class="d-f">
                                            <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                                <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                            </svg>
                                        </div>
                                    </a>
                                </div>
                            <?endif;?>
                        <?endforeach;?>
                    <?endif;?>
                <?endif;?>
            </div>
        </div>
    </div>
</section>

<script>
(function() {
    const TRANSITION_DURATION = 300;

    document.querySelectorAll('.doc-accordion--item').forEach(item => {
        const wrapper = item.closest('.doc-item-wrapper');
        if (!wrapper) return;

        const icon = item.querySelector('.doc-accordion--icon');
        const desc = item.querySelector('.doc-accordion--description');
        if (!icon || !desc) return;

        let isOpen = false;
        let animating = false;

        item.addEventListener('click', (e) => {
            if (animating) return;

            if (!isOpen) {
                animating = true;
                wrapper.classList.add('expanded');
                setTimeout(() => {
                    desc.classList.remove('d-n');
                    icon.classList.add('tm-r135');
                    isOpen = true;
                    animating = false;
                }, TRANSITION_DURATION);
            } else {
                animating = true;
                desc.classList.add('d-n');
                icon.classList.remove('tm-r135');
                setTimeout(() => {
                    wrapper.classList.remove('expanded');
                    isOpen = false;
                    animating = false;
                }, TRANSITION_DURATION);
            }
        });
    });
})();
</script>

<style>
.doc-item-wrapper {
    transition: width 0.3s ease-in-out;
    width: 100%;
}
@media (min-width: 1280px) {
    .doc-item-wrapper:not(.expanded) {
        width: 66.666667%;
    }
    .doc-item-wrapper.expanded {
        width: 100%;
    }
}
.tm-r135 { transform: rotate(135deg); }
.doc-accordion--icon { transition: transform 0.3s ease; }
.doc-accordion--description {
    transition: opacity 0.2s;
}
.doc-accordion--description.d-n {
    display: none;
    opacity: 0;
}
.doc-accordion--description:not(.d-n) {
    display: block;
    opacity: 1;
}
</style>