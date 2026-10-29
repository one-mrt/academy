<?
use Bitrix\Main\Application;
use Bitrix\Main\Web\Uri;
$page = $APPLICATION->GetCurPage(false);
$fullPath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
?>

<script>
    LG.adaptiveClearBlock = () => {
        if(window.innerWidth < 1200)
        {
            document.querySelectorAll('.lang-block--desctop').forEach(i => {
                i.remove();
            });
        }
        else
        {
            document.querySelectorAll('.lang-block--mobile').forEach(i => {
                i.remove();
            });
        }
    }
    
    LG.adaptiveClearBlock();
</script>

<?if(!empty($_GET['utm_source']) || !empty($_GET['utm_medium']) || !empty($_GET['utm_campaign']) || !empty($_GET['utm_term']) || !empty($_GET['utm_content'])):?>
    <script>
        <?
        $request = Application::getInstance()->getContext()->getRequest();
        $uriString = $request->getRequestUri();
        $uri = new Uri($uriString);
        $uri->addParams($_GET);
        $redirect = $uri->getUri();
        ?>

        window.addEventListener('DOMContentLoaded', function(e) {

            let notLink = [
                    "mailto:", // добавьте или удалите домены (без https и косой черты в конце)
                    "tel:",
                    "javascript:"
                ],
                utmRegExp = /(\&|\?)utm_[A-Za-z]+=[A-Za-z0-9]+/gi,
                links = document.querySelectorAll('a:not([href^="tel:"]),form,button[data-src]'),
                utms = [
                    <?if(!empty($_GET['utm_medium'])):?>"utm_medium={{utm_medium}}",<?endif;?>
                    <?if(!empty($_GET['utm_source'])):?>"utm_source={{utm_source}}",<?endif;?>
                    <?if(!empty($_GET['utm_campaign'])):?>"utm_campaign={{utm_campaign}}",<?endif;?>
                    <?if(!empty($_GET['utm_term'])):?>"utm_term={{utm_term}}",<?endif;?>
                    <?if(!empty($_GET['utm_content'])):?>"utm_content={{utm_content}}"<?endif;?>
                ];

                

            for(var index = 0; index < links.length; index++)
            {
                let isLinkEnabled = true;

                if(links[index].tagName == 'FORM')
                {
                    var tempLink = links[index].action,
                            tempParts;

                    if(links[index].action)
                    {
                        tempParts = tempLink.split("#");
                        tempParts[0] += '?<?=$uri->getQuery()?>';
                        tempLink = tempParts.join("#");
                        links[index].action = tempLink;
                    }
                }
                else if(links[index].tagName == 'BUTTON')
                {
                    if(links[index].getAttribute('data-src'))
                    {
                        for(let k = 0; k < notLink.length; k++)
                        {
                            if(links[index].getAttribute('data-src').indexOf(notLink[k]) > 0)
                            {
                                isButtonFancyBoxEnabled = false;
                            }
                        }
                    }
                    else
                    {
                        isButtonFancyBoxEnabled = false;
                    }

                    if(isButtonFancyBoxEnabled)
                    {
                        var tempLink = links[index].getAttribute('data-src'),
                            tempParts;

                        if(tempLink)
                        {
                            tempParts = tempLink.split("#");
                            tempParts[0] += '?<?=$uri->getQuery()?>';
                            tempLink = tempParts.join("#");
                            links[index].setAttribute('data-src', tempLink);
                        }
                        
                    }
                }
                else
                {
                
                    for(let k = 0; k < notLink.length; k++)
                    {
                        if (links[index].href.indexOf(notLink[k]) > 0)
                        {
                            isLinkEnabled = false;
                        }
                    }

                    if(isLinkEnabled)
                    {
                        
                        var tempLink = links[index].href,
                            tempParts;

                        if(links[index].href)
                        {
                            tempParts = tempLink.split("#");
                            tempParts[0] += '?<?=$uri->getQuery()?>';
                            tempLink = tempParts.join("#");
                            links[index].href = tempLink;
                        }
                        
                    }    
                }
            }
        });
    </script>
<?endif;?>

<script>

    if(document.querySelector('.swiper'))
    {
        window.addEventListener('scroll', () => {
            document.querySelectorAll('.swiper').forEach(e => {
                LG.blockVisible(e, 'langSwiperBlock', () => {
                    <?/* LG.loadCss('<?=SITE_TEMPLATE_PATH?>/css/swiper.min.css', () => {}, 'head', 'prepend');*/?>
                    LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/swiper.min.js', () => {
                        LG.send('onLangSwiperScriptLoad', window);
                    }, true, 'body', 'append');
                });
            });
        });

        window.addEventListener('DOMContentLoaded', () => {
            document.querySelectorAll('.swiper').forEach(e => {
                LG.blockVisible(e, 'langSwiperBlock', () => {
                    <?/* LG.loadCss('<?=SITE_TEMPLATE_PATH?>/css/swiper.min.css', () => {}, 'head', 'prepend');*/?>
                    setTimeout(() => {
                        LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/swiper.min.js', LG.send.bind(false, 'onLangSwiperScriptLoad', window), true, 'body', 'append');
                    }, 1500);
                });
            });
        });
    }

    setTimeout(() => {
        if(document.querySelector('.lazyload'))
        {
            LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/lazysizes.min.js', () => {}, true, 'body');
        }
    }, 1500);

    LG.fieldMaskPhone = () => {
        try {
            document.querySelectorAll('.lang-form--ajax').forEach(form => {
                let btnSubmit = form.querySelector('input[type="submit"]');

                form.querySelectorAll('.js-field-form--phone-faq').forEach(el => {
                    new Inputmask("+7 (999) 999-99-99",
                        {
                            autoclear: false,
                            placeholder: "+7 (___) ___-__-__",
                            completed: () => {
                                // $(this).addClass('b--lime-600')
                            },
                            incomplete: () => {

                            }
                        }
                    ).mask(el);
                });

                // form.querySelectorAll('.js-field-form--email-faq').forEach(el => {
                //     new Inputmask(
                //         {
                //             alias: "email",
                //             completed: () => {
                //                 // $(this).addClass('b--lime-600')
                //             },
                //             incomplete: () => {

                //             }
                //         }
                //     ).mask(el);
                // });
            });
        } catch(ex) {

        }
    }

    LG.addGoogleReCaptcha = () => {
        if(typeof grecaptcha != 'undefined')
        {
            grecaptcha.ready(() => {
                grecaptcha.execute('6LcQCtkrAAAAAGHvG6Y8uOPZfVo-PqeyxYI4SjPX', {action: 'homepage'}).then((token) => {
                    let recaptcha = document.querySelectorAll(".g-recaptcha-response-faq");
                    if(recaptcha)
                    {
                        recaptcha.forEach(i => {
                            i.value = token;
                        });
                    }
                });
            });
        }
    }

    if(document.querySelector('[data-fancybox]'))
    {
        setTimeout(() => {
            LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/fancybox.min.js', () => {

                Fancybox.bind('[data-fancybox][data-type="ajax"]', {
                    dragToClose: false,
                    type: "ajax",
                    backdropClick: false,
                    closeButton: false,
                    on: {
                        done: (fancybox, slide) => {
                            LG.loadJS('<?=SITE_TEMPLATE_PATH?>/js/inputmask.min.js', LG.fieldMaskPhone, true, 'body');
                            if(slide.el.querySelector('.g-recaptcha-response-faq'))
                            {
                                LG.loadJS("https://www.google.com/recaptcha/api.js?render=6LcQCtkrAAAAAGHvG6Y8uOPZfVo-PqeyxYI4SjPX", LG.addGoogleReCaptcha, true,'body');
                            }

                            var BX = window.BX || top.BX || false;

                            try {
                                if(!BX)
                                {
                                    LG.loadJS('/bitrix/js/main/core/core.min.js', () => {
                                        (window.BX||top.BX).message({
                                            "JS_CORE_LOADING":"Загрузка...",
                                            "JS_CORE_NO_DATA":"- Нет данных -",
                                            "JS_CORE_WINDOW_CLOSE":"Закрыть",
                                            "JS_CORE_WINDOW_EXPAND":"Развернуть",
                                            "JS_CORE_WINDOW_NARROW":"Свернуть в окно",
                                            "JS_CORE_WINDOW_SAVE":"Сохранить",
                                            "JS_CORE_WINDOW_CANCEL":"Отменить",
                                            "JS_CORE_WINDOW_CONTINUE":"Продолжить",
                                            "JS_CORE_H":"ч","JS_CORE_M":"м",
                                            "JS_CORE_S":"с","JSADM_AI_HIDE_EXTRA":"Скрыть лишние",
                                            "JSADM_AI_ALL_NOTIF":"Показать все",
                                            "JSADM_AUTH_REQ":"Требуется авторизация!",
                                            "JS_CORE_WINDOW_AUTH":"Войти",
                                            "JS_CORE_IMAGE_FULL":"Полный размер"
                                        });
                                        BX = window.BX || top.BX;

                                        BX.addCustomEvent( 'onAjaxSuccess' , () => {
                                            if(parent.bxcompajaxframeonload)
                                            {
                                                parent.bxcompajaxframeonload();
                                            }
                                        });
                                        
                                        slide.el.querySelectorAll('script').forEach(script => {
                                            eval(script.innerText);
                                        });
                                    }, true, 'body');
                                }
                                else
                                {
                                    slide.el.querySelectorAll('script').forEach(script => {
                                        eval(script.innerText);
                                    });
                                }
                            } catch(ex) {
                                console.log(ex)
                            }
                            
                        },
                    }
                });

                Fancybox.bind('[data-fancybox]:not([data-type="ajax"])', {
                    on: {
                        done: (fancybox, slide) => {
                            
                        },
                    }
                });
                // Fancybox.defaults.Image.click = "null";
                Fancybox.defaults.backdropClick = false;
                Fancybox.defaults.dragToClose = false;
                Fancybox.defaults.mainClass = "lang-form--window";
                Fancybox.defaults.buttons = ["zoom","slideShow","fullScreen","thumbs","close"];
                Fancybox.defaults.l10n = {
                    <?/*
                    // ERROR: "Запрошенный контент не может быть загружен. <br/> Пожалуйста, повторите попытку позже.",
                    // FULL_SCREEN: "Полноэкранный режим",
                    // PLAY_START: "Запустить слайд-шоу",
                    // PLAY_STOP: "Приостановить слайд-шоу",
                    // SHARE: "Поделиться",
                    // THUMBS : "Эскизы",
                    */?>

                    AJAX_FORBIDDEN: "Ошибка загрузки AJAX : Запрещено",
                    AJAX_NOT_FOUND: "Ошибка загрузки AJAX : Не найдено",
                    CLOSE: "Закрыть",
                    DOWNLOAD: "Скачать",
                    ELEMENT_NOT_FOUND: "HTML-элемент не найден",
                    ERROR: "Что-То пошло Не Так, Пожалуйста, повторите попытку позже",
                    FITX: "Устанавливайте горизонтально",
                    FITY: "Fit vertically",
                    FLIPX: "Устанавливайте вертикально",
                    FLIPY: "Переверните вертикально",
                    IFRAME_ERROR: "Ошибка загрузки страницы",
                    IMAGE_ERROR: "Изображение не найдено",
                    ITERATEZOOM: "Увеличить/Уменьшить",
                    MODAL: "Вы можете закрыть этот модальный контент с помощью клавиши ESC",
                    NEXT: "Следующий",
                    PANDOWN: "Двигайтесь вниз",
                    PANLEFT: "Переместиться влево",
                    PANRIGHT: "Двигайся вправо",
                    PANUP: "Двигаться вверх",
                    PREV: "Предыдущий",
                    RESET: "Сброс",
                    ROTATECCW: "Поверните против часовой стрелки",
                    ROTATECW: "Вращайте по часовой стрелке",
                    TOGGLE1TO1: "Переключать уровень масштабирования",
                    TOGGLEFS: "Переключение полноэкранного режима",
                    TOGGLEZOOM: "Переключать уровень масштабирования",
                    TOGGLE_FULLSCREEN: "Переключение в полноэкранный режим",
                    TOGGLE_SLIDESHOW: "Переключение слайд-шоу",
                    TOGGLE_THUMBS: "Переключать миниатюры",
                    TOGGLE_ZOOM: "Переключать уровень масштабирования",
                    ZOOMIN: "Увеличьте масштаб",
                    ZOOMOUT: "Уменьшите масштаб"
                };

            }, true,'body');

            LG.loadCss('<?=SITE_TEMPLATE_PATH?>/css/fancybox.min.css', () => {}, 'head');
            LG.send('onLoadContentFancybox', window)
        }, 1000);
    }


    LG.addSwiper = (element, param = {}, height = true) => {
        let block = new Swiper(element, param);

        if(height) {
            let currentHeight = 0;

            for(let k = 0; k < block.slides.length; k++) {
                if(block.slides[k].clientHeight > currentHeight) {
                    currentHeight = block.slides[k].clientHeight
                }
            }

            for(var k = 0; k < block.slides.length; k++) {
                block.slides[k].style.height = `${currentHeight}px`;
            }
        }
    }

    function initNavMain(e)
    {
        e.preventDefault();
        let nav = document.querySelector('.deva--navigaiton--mobile');

        nav.classList.toggle('left-0');
        document.body.classList.toggle('ofw-hn');

        nav.querySelectorAll('.navigation--btn-level').forEach(
            (item)=>
            {
                item.addEventListener('click', function(e) {

                    nav.querySelectorAll('.navigation--btn-level').forEach(
                        (it)=>
                        {
                            if(item != it)
                            {
                                it.parentNode.nextElementSibling.classList.remove('left-0');
                            }
                        }
                    );

                    item.parentNode.nextElementSibling.classList.toggle('left-0');
                });
            }
        )

        nav.querySelectorAll('[data-back-level]').forEach((btnBack) => {
            btnBack.addEventListener('click', (e) => {
                document.querySelector(`[next-level-block="${btnBack.getAttribute('data-back-level')}"]`).classList.toggle('left-0');
            });
        });
    }

    let mobileBtnShow = document.querySelector('.navigation--mobile--btn');
    let mobileBtnClose = document.querySelector('.navigation-mobile--close');

    if(mobileBtnShow)
    {
        mobileBtnShow.addEventListener('click', initNavMain);
    }

    if(mobileBtnClose)
    {
        document.querySelector('.navigation-mobile--close').addEventListener('click', initNavMain);
    }

    LG.initFixed = function(modifier=[], handlerStart= (...argArray) => {}, handlerEnd= (...argArray) => {})
    {
        let y = this.getBoundingClientRect().y;
        let top = 0;
        let navY = this.parentNode.getBoundingClientRect().y;

        if (navY < top)
        {
            for (let k = 0; k < modifier.length; k++)
            {
                this.classList.add(modifier[k]);
            }

            handlerStart();

        }
        else if (top <= navY)
        {
            for (let k = 0; k < modifier.length; k++)
            {
                this.classList.remove(modifier[k]);
            }

            handlerEnd();
        }
    }

    <?/*if($page == '/'):?>
        window.addEventListener('scroll', LG.initFixed.bind(document.querySelector('header'), [], () => {
            document.querySelector('.lang-header--main').classList.add('bc-w', 'pt-16', 'bxs-py');
        }, ()=>{
            document.querySelector('.lang-header--main').classList.remove('bc-w', 'pt-16', 'bxs-py');
        }));

        window.addEventListener('DOMContentLoaded', LG.initFixed.bind(document.querySelector('header'), [], () => {
            document.querySelector('.lang-header--main').classList.add('bc-w', 'pt-16', 'bxs-py');
        }, ()=>{
            document.querySelector('.lang-header--main').classList.remove('bc-w', 'pt-16', 'bxs-py');
        }));
    <?else:?>
        window.addEventListener('scroll',
            LG.initFixed.bind(
                document.querySelector('header'), [],
                () => {
                    document.querySelector('.lang-header--main').classList.add('bxs-py');
                },
                () => {
                    document.querySelector('.lang-header--main').classList.remove('bxs-py');
                }
        ));
    <?endif;*/?>

    // LG.receive('langMaskFormInit', window, LG.fieldMaskPhone);
    
</script>