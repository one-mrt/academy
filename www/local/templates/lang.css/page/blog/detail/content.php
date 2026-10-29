<?
use Lang\Main\Iblock\Util;
use Bitrix\Main\Type\DateTime;
use Lang\Css\Component as comp;


if(!empty($element->getPreviewPicture()))
{
    $previewPicture = \CFile::GetPath($element->getPreviewPicture());
}
?>

<style>
    .lang-blog--text-content table {
        width: 100%;
        overflow-x: auto;
    }
    /*.font-proxima h2,
    .font-proxima h3,
    .font-proxima h4,
    .font-proxima h5,
    .font-proxima h6 {
        font-family: var(--proxima)
    }
    .sert--detail .main-link {
        color:#bb9b8c!important;
        background-image: linear-gradient(to right, #bb9b8c 50%, transparent 50%);
    }
    .sert--detail .main-link:hover {
        color: #bb9b8c;
        background-image: linear-gradient(to right, rgb(187 155 140 / 30%) 50%, transparent 50%);
    }*/

    .luciano--btn-up {
        background-color: var(--dark-biege);
        width: 60px;
        height: 60px;
        z-index: 42;
        bottom: 100px;
        left: 50px;
    }
    /* ol li::before {padding-right: 1rem}

    .link-h2::before{color:#bb9b8c}
    .link-h3{counter-increment: new 1}
    .link-h3::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;}
    .link-h4{counter-increment: new 1}
    .link-h4::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;}
    .link-h5{counter-increment: new 1}
    .link-h5::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;}
    .link-h6{counter-increment: new 1}
    .link-h6::before{content: counter(ordered-list) "." counter(new);color:#bb9b8c;} */

    .lang-blog--text-content img {
        width: 100%;
        height: auto;
        margin-bottom: 0;
    }
    .lang-blog--text-content p {
        margin-bottom: 0;
    }
</style>

<section class="w-100 pb-11 xl:pb-13 d-f fd-c rg-32 xl:rg-64">

    <?
    $oldNews = Util::getElementsObject($newsTableString, [
        'order' => ['ACTIVE_FROM' => 'DESC'],
        'select' => ['NAME', 'PREVIEW_TEXT', 'CODE', 'IBLOCK_SECTION_ID'],
        'filter' => ['!CODE' => $elementCode],
        'limit' => 3
    ]);
    ?>

    <div class="<?=comp::get("container")?>">
        <div class="<?=comp::get("row")?> rg-32">

            <div class="w-100 lg:w-6/12 pr-16 pl-16 fg-0 fs-0 o-2 lg:o-1">
                <div class="lang-blog--menu-page w-100 d-f fd-c rg-24 bi-b p-32 r-12">
                    <div class="ftw-5 f-18 xl:f-24">Содержание</div>
                </div>
            </div>

            <?if(!empty($oldNews)):?>
                <div class="w-100 lg:w-6/12 pr-16 pl-16 fg-0 fs-0 d-f fd-c rg-24 o-1 lg:o-2">

                    <div class="ftw-5 f-18 xl:f-24">Другие статьи</div>

                    <div class="w-100 d-f fd-c rg-24">
                        <?foreach($oldNews as $item):?>
                            <a class="d-f rg-16" href="<?=Util::getDetailPageUrl($newsIblock->getDetailPageUrl(), $item)?>">
                                <div class="w-100 d-f fd-c rg-16">
                                    <div class=""><?=mb_ucfirst(mb_strtolower(strip_tags($item->getName())))?></div>
                                    <?if(!empty($item->getPreviewText())):?>
                                        <div class=""><?=$item->getPreviewText()?></div>
                                    <?endif;?>
                                </div>
                                <div class="d-f">
                                    <svg width="24" height="24" viewBox="0 0 23 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect class="fil-py" width="23" height="23" rx="5" fill="white"></rect>
                                        <path class="fil-w" d="M6.89902 6.08594L6.08594 6.90127L6.49248 7.30781L10.6859 11.5013L6.49248 15.6947L6.08594 16.1013L6.89902 16.9166L7.30557 16.5078L12.3144 11.5013L7.30557 6.49473L6.89902 6.08594ZM11.4968 6.08594L10.6837 6.90127L11.0902 7.30781L15.2837 11.5013L11.0902 15.6947L10.6837 16.1013L11.4968 16.9166L11.9033 16.5078L16.9121 11.5013L11.9033 6.49473L11.4968 6.08594Z"></path>
                                    </svg>
                                </div>
                            </a>
                        <?endforeach;?>
                    </div>
                </div>
            <?endif;?>
        </div>
    </div>

    <div class="w-100 sm:mw-540px md:mw-720px lg:mw-960px pr-16 pl-16 mr-a ml-a">
        <div class="lang-blog--text-content w-100 ofw-ao flex f-col rg-24 f-14 md:f-16"><?=$element->getDetailText()?></div>
    </div>

    <?if(!empty($element->getMorePhoto()->getAll())):?>
        <div class="<?=comp::get("container")?>">
            <div class="swiper lang-img--blog<?=$element->getId()?> of-h">
                <div class="swiper-wrapper d-f">
                    <?foreach($element->getMorePhoto()->getAll() as $img):
                        $view = CFile::ResizeImageGet($img->getValue(), ["width" => 600, "height" => 600], BX_RESIZE_IMAGE_PROPORTIONAL);
                        $imgF = \CFile::GetFileArray($img->getValue());
                        ?>
                        <button data-fancybox="langPhoto<?=$element->getId()?>" data-src="<?=$imgF['SRC']?>" title="<?=$imgF['DESCRIPTION'] ?: $element->getName()?>"
                            class="swiper-slide lg:w-4/12 ar-16x9 m-0 p-0 bw-0 d-f r-20 bc-w bgs-cr bgr-nr bp-c bi-load lazyload" data-bg="<?=$view['src']?>"></button>
                    <?endforeach;?>
                </div>
            </div>
        </div>

        <script>
            initImgCard = (id) => {
                let viewIminSpec_<?=$element->getId()?> = new Swiper(
                    id,
                    {
                        lazy: false,
                        slidesPerView: 3,
                        spaceBetween: 32,
                        navigation: {
                            nextEl: '.gallery-select--next--<?=$element->getId()?>',
                            prevEl: '.gallery-select--prev--<?=$element->getId()?>',
                            disabledClass : 'd-none',
                            hiddenClass : 'gallery--disabled',
                            lockClass : 'gallery--disabled',
                        },
                        breakpoints: {
                            320: {
                                slidesPerView: 1.2,
                                spaceBetween: 8
                            },
                            576: {
                                slidesPerView: 1.5,
                                spaceBetween: 8
                            },
                            768: {
                                slidesPerView: 2.5,
                                spaceBetween: 8
                            },
                            992: {
                                slidesPerView: 2.5,
                                spaceBetween: 32
                            },
                            1200: {
                                slidesPerView: 3,
                                spaceBetween: 32
                            },
                            1400: {
                                slidesPerView: 3,
                                spaceBetween: 32
                            }
                        }
                    }
                );

                var curHeight = 0;

                for(var k = 0; k < viewIminSpec_<?=$element->getId()?>.slides.length; k++)
                {
                    if(viewIminSpec_<?=$element->getId()?>.slides[k].clientHeight > curHeight)
                    {
                        curHeight = viewIminSpec_<?=$element->getId()?>.slides[k].clientHeight
                    }
                }
                for(var k = 0; k < viewIminSpec_<?=$element->getId()?>.slides.length; k++)
                {
                    viewIminSpec_<?=$element->getId()?>.slides[k].style.height = curHeight + 'px';
                }
            }

            try {
                if(Swiper)
                {
                    window.addEventListener('DOMContentLoaded', initImgCard.bind(false, '.lang-img--blog<?=$element->getId()?>'));
                }
            }
            catch(e) {
                window.addEventListener('onLangSwiperScriptLoad', initImgCard.bind(false, '.lang-img--blog<?=$element->getId()?>'));
            }
        </script>
    <?endif;?>

    <div class="<?=comp::get("container")?>">
        <div class="w-100 d-f cg-24 f-14 lh-1">
            <?@include "share.php";?>

            <div class="d-f ai-c cg-8 lh-1">
                <div class="d-f">
                    <svg width="19" height="14" viewBox="0 0 19 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="M19 6.53125C19 6.53125 15.4375 0 9.5 0C3.5625 0 0 6.53125 0 6.53125C0 6.53125 3.5625 13.0625 9.5 13.0625C15.4375 13.0625 19 6.53125 19 6.53125ZM1.39294 6.53125C1.96711 5.65858 2.62751 4.84581 3.36419 4.10519C4.8925 2.5745 6.9825 1.1875 9.5 1.1875C12.0175 1.1875 14.1063 2.5745 15.637 4.10519C16.3737 4.84581 17.0341 5.65858 17.6082 6.53125C17.5394 6.63456 17.4634 6.74856 17.3767 6.87325C16.9789 7.44325 16.3911 8.20325 15.637 8.95731C14.1063 10.488 12.0163 11.875 9.5 11.875C6.9825 11.875 4.89369 10.488 3.363 8.95731C2.62633 8.21668 1.96712 7.40391 1.39294 6.53125Z"/><path d="M9.5 3.5625C8.71264 3.5625 7.95753 3.87528 7.40078 4.43203C6.84403 4.98878 6.53125 5.74389 6.53125 6.53125C6.53125 7.31861 6.84403 8.07372 7.40078 8.63047C7.95753 9.18722 8.71264 9.5 9.5 9.5C10.2874 9.5 11.0425 9.18722 11.5992 8.63047C12.156 8.07372 12.4688 7.31861 12.4688 6.53125C12.4688 5.74389 12.156 4.98878 11.5992 4.43203C11.0425 3.87528 10.2874 3.5625 9.5 3.5625ZM5.34375 6.53125C5.34375 5.42894 5.78164 4.37179 6.56109 3.59234C7.34054 2.81289 8.39769 2.375 9.5 2.375C10.6023 2.375 11.6595 2.81289 12.4389 3.59234C13.2184 4.37179 13.6562 5.42894 13.6562 6.53125C13.6562 7.63356 13.2184 8.69071 12.4389 9.47016C11.6595 10.2496 10.6023 10.6875 9.5 10.6875C8.39769 10.6875 7.34054 10.2496 6.56109 9.47016C5.78164 8.69071 5.34375 7.63356 5.34375 6.53125Z"/></svg>
                </div>
                <div class=""><?=$element->getShowCounter() ?: 0?> просмотров</div>
            </div>

        </div>
    </div>

</section>

<script>
    let container = document.querySelector('.lang-blog--menu-page');
    let wrapper = document.createElement('ul');
    let headers = document.querySelector('.lang-blog--text-content').querySelectorAll('h2,h3,h4,h5,h6');
    // let title = document.createElement('div');
    // title.classList.add('f-29','md:f-42','mb-11','ta-l','w-100');
    // title.innerText = 'Содержание';
    // wrapper.style.listStyleType = 'none';
    wrapper.classList.add('m-0','p-0','d-f','fd-c', 'list-ne', 'g-16', 'f-16', 'md:f-18');
    // wrapper.append(title);
    
    headers.forEach((item,k) => {

        switch(item.tagName)
        {
            case 'H2':
                break;
            case 'H3':
                break;
            case 'H4':
                break;
            case 'H5':
                break;
            case 'H6':
                break;
        }

        item.id = `luciano--text-title-${k}`;
        item.classList.add('mb-0', 'f-20', 'xl:f-24', 'ftw-5');

        let it = document.createElement('li');
        let link = document.createElement('a');
        link.classList.add('lh-sd', 'flex','tn-all-3', 'c-0187AC', 'ftw-4', 'hover:tdl-u', 'tuo-4', 'hover:tdc-py');

        switch(item.tagName)
        {
            case 'H2':
                it.classList.add('link-h2');
                break;
            case 'H3':
                it.classList.add('link-h3');
                // link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
                break;
            case 'H4':
                it.classList.add('link-h4');
                // link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
                break;
            case 'H5':
                it.classList.add('link-h5');
                // link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
                break;
            case 'H6':
                it.classList.add('link-h6');
                // link.classList.add('hover:bg-F9F9F9', 'tn-all-3');
                break;
        }

        
        // link.classList.add('main-link','main-link--1');
        link.innerText = item.innerText;
        link.href = `#${item.id}`;
        it.append(link);
        wrapper.append(it);
        //link[item.tagName].push(item);
    });

    document.querySelectorAll('.lang-blog--text-content img').forEach(img => {
        img.setAttribute('data-fancybox', "galleryNewsDetail");
        img.classList.add('w-100', 'h-ao', 'r-12');
    });

    document.querySelectorAll('.lang-blog--text-content a').forEach(link => {
        link.classList.add('tdl-u', 'tuo-4', 'tdc-py', 'c-pr', 'tdt-1', 'hover:c-sy2', 'tn-all-3');
    })

    if(headers.length > 0)
    {
        // container.appendChild(title);
        container.appendChild(wrapper);
        // wrapper.style.height = wrapper.clientHeight + 'px';
        // wrapper.style.top = document.querySelector('header').clientHeight + 'px';

        if(window.location.hash == "")
        {
            if(wrapper !== null)
            {
                let navA = wrapper.querySelector('a');
                if(navA !== null)
                {
                    wrapper.querySelector('a').classList.add('border-gray-6');
                }   
            } 
        }
    }
    else
    {
        container.parentNode.classList.add('d-n');
    }
</script>