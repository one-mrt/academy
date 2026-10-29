<script data-skip-moving="">
    var LG = LG || {};
    LG.langAssetBlock = LG.langAssetBlock || {};

    LG.send = function(event, obj) {
        if(document.createEvent) {
            var e = document.createEvent("Events");
            e.initEvent(event, true, false);
        } 
        else if(document.createEventObject()) {
            var e = document.createEventObject();
        } else return;

        if(obj.dispatchEvent)
            obj.dispatchEvent(e);
        else if(obj.fireEvent) obj.fireEvent(event, e);
    }

    LG.receive = function(event, el, handler) {
        if(window.addEventListener) el.addEventListener(event, handler, false);
    }

    LG.isScript = (src) => {
        let allScript = document.querySelectorAll('script');

        for(var k = 0; k < allScript.length; k++)
        {
            let srcCurrent = allScript[k].getAttribute('src');

            if(srcCurrent)
            {
                if(srcCurrent.indexOf(src) !== -1)
                {
                    return allScript[k];
                }
            }
        }

        return false;
    }

    LG.isStyle = function(src)
    {
        let allScript = document.querySelectorAll('link[rel="stylesheet"]');
        
        for(var k = 0; k < allScript.length; k++)
        {
            let srcCurrent = allScript[k].getAttribute('href');
            if(srcCurrent) if(srcCurrent.indexOf(src) !== -1) return allScript[k];
        }

        return false;
    }

    LG.loadJS = (src, handler = () => {}, async = true, area='head') => {
        if(!LG.isScript(src))
        {
            let script = document.createElement('script');

            script.src = src;
            script.setAttribute("async", "");

            script.addEventListener('load', addJs = () => {
                this.removeEventListener('load', addJs, false);
                LG.send('onLangScriptLoad', window);
                handler();
            })

            switch(area)
            {
                case 'head':
                    document.head.append(script);
                    break;
                case 'body':
                    document.body.append(script);
                    break;
                default:
                    document.head.append(script);
                    break;
            }
        }
        else
        {
            try {
                handler();
            } catch(e) {
                LG.receive('onLangScriptLoad', window, handler.bind(false));
            }
            
        }
    }

    LG.loadCss = (href, handler=()=>{},area='head') => {
        let style = document.createElement('link');

        style.href = href;
        style.type = "text/css";
        style.rel = "stylesheet";
        style.addEventListener('load', addCss = () => {
            this.removeEventListener('load', addCss, false);
            handler();
            LG.send('onLangStyleLoad', window);
        });
        
        if(!LG.isStyle(href))
        {
            switch(area)
            {
                case 'head':
                    document.head.append(style);
                    break;
                case 'body':
                    document.body.append(style);
                    break;
                default:
                    document.head.append(style);
                    break;
            }
        }
        else
        {
            try {
                handler();
            } catch(e) {
                LG.receive('onLangStyleLoad', window, handler.bind(false));
            }
        }
    }

    LG.blockVisible = (i,block, handlerStart = (...argArray) => {}, handlerEnd = (...argArray) => {}) => {
        let iPosition = {
            top: window.pageYOffset + i.getBoundingClientRect().top,
            left: window.pageXOffset + i.getBoundingClientRect().left,
            right: window.pageXOffset + i.getBoundingClientRect().right,
            bottom: window.pageYOffset + i.getBoundingClientRect().bottom
        };
        let windowPosition = {
            top: window.pageYOffset,
            left: window.pageXOffset,
            right: window.pageXOffset + document.documentElement.clientWidth,
            bottom: window.pageYOffset + document.documentElement.clientHeight
        };

        if(iPosition.bottom > windowPosition.top && iPosition.top < windowPosition.bottom && iPosition.right > windowPosition.left && iPosition.left < windowPosition.right)
        {
            if(!LG.langAssetBlock[block])
            {
                LG.langAssetBlock[block] = true;
                handlerStart();
            }
            // else
            // {
            //     handlerEnd();
            // }
        }
        else
        {

        }
    }

    LG.addSwiperFunction = (handler = (...argArray) => {}) => {
        try {
			if(Swiper)
			{
				//window.addEventListener('DOMContentLoaded', () => {
                    handler();
                //});
			}
		}
		catch(e) {
			window.addEventListener('onLangSwiperScriptLoad', () => {
                handler();
            });
		}
    }

    LG.navigationSlider = (nav) => {
        let catalogNav = document.querySelector(nav);

        if (window.innerWidth <= 1399)
        {
            catalogNav.querySelectorAll('[data-level]').forEach(item => {
                let link = item.querySelector('[data-link]');

                if(link)
                {
                    link.addEventListener('click', (e) => {
                        let sub = item.querySelector('[product-level]');

                        if(sub)
                        {
                            e.preventDefault();
                            sub.classList.toggle('right-0');
                        }
                    });
                }
            });

            catalogNav.querySelectorAll('[class^="nav-btn--back-"]').forEach((item) => {
                item.addEventListener('click', (e) => {
                    console.log(document.querySelector(`.nav--level-${item.getAttribute('nav-level')}`))
                    document.querySelector(`.nav--level-${item.getAttribute('nav-level')}`).classList.remove('rt-0');
                })
            })
        }
    }
</script>