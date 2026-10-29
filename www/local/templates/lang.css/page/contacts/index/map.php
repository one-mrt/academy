<?
use Lang\Region;
use Lang\Main\Iblock\Util;
use Lang\Css\Component as comp;

$ID = rand();
$region = Region::getAll(['select' => ['NAME', 'map']]);
$title = Util::getElementObject($textTableString, ['NAME'], ['ID' => 205072]);
?>

<section class="lang-ruk--map w-100">
    <div class="<?=comp::get("container")?> d-f fd-c rg-32 xl:rg-48">
        
        <?if(!empty($title)):?>
            <div class="w-100">
                <h2 class="<?=comp::get('titleh2')?>"><?=$title->getName()?></h2>
            </div>
        <?endif;?>

        <div class="dom--ymap jkevo--map w-100 ar-10x8 md:ar-10x6 lg:ar-10x4 pn-r r-24 of-h bc-w" id="jkevo--map-<?=$ID?>">
            <div class="a2-lazy-preloader animation-none image-load--animation w-100 h-100 d-f fd-c m-0 z-1 ai-c jc-c bc-t pn-a t-0 rt-0 b-0 l-0 bw-1 b-sd bcr-F9F9F9 bc-w of-h">
                <style>@-webkit-keyframes side-panel-loader-rotate{100% {-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@keyframes side-panel-loader-rotate{100%{-webkit-transform: rotate(360deg);transform: rotate(360deg)}}@-webkit-keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}@keyframes side-panel-loader-dash{0%{stroke-dasharray:1,200;stroke-dashoffset:0}50%{stroke-dasharray:89,200;stroke-dashoffset:-35px}100%{stroke-dasharray:89,200;stroke-dashoffset:-124px}}.loader--animation {-webkit-animation: side-panel-loader-rotate 2s linear infinite;animation: side-panel-loader-rotate 2s linear infinite;-webkit-transform-origin: center center;transform-origin: center center}.loader--path{stroke: #bfc3c8;stroke-width: 1.5;stroke-dasharray: 20, 200;stroke-dashoffset: 0;-webkit-animation: side-panel-loader-dash 1.5s ease-in-out infinite;animation: side-panel-loader-dash 1.5s ease-in-out infinite;stroke-linecap: round}</style>
                <svg style="max-width:100px;max-height:100px!important" class="loader--animation w-100 h-100" viewBox="25 25 50 50"><circle class="loader--path" cx="50" cy="50" r="20" fill="none" stroke-miterlimit="10"></circle></svg>
                <div class="mt-8 f-12 md:f-14 xl:f-16">Загрузка карты</div>
            </div>
        </div>
        <script>
            ymapsLoad<?=$ID?> = () => {
                try {
                    ymaps.load(initYMap_<?=$ID?>);
                } catch(ex) {
                    console.log(ex);
                }
            }
            loadYandexMaps<?=$ID?> = () => {LG.loadJS("https://api-maps.yandex.ru/2.1/?lang=ru-RU&load=package.full&apikey=598a645e-4b4c-4f99-94d3-b59857eb56c0", ymapsLoad<?=$ID?>, true, 'body')}

            if(document.querySelector('.lang-ruk--map'))
            {
                window.addEventListener('scroll', () => {
                    document.querySelectorAll('.lang-ruk--map').forEach(e => {
                        LG.blockVisible(e, 'langYMap', () => {
                            setTimeout(loadYandexMaps<?=$ID?>, 3000)
                        });
                    });
                });

                window.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('.lang-ruk--map').forEach(e => {
                        LG.blockVisible(e, 'langYMap', () => {
                            setTimeout(loadYandexMaps<?=$ID?>, 3000)
                        });
                    });
                });
            }

            initYMap_<?=$ID?> = () => {
                var map_10 = new ymaps.Map('jkevo--map-<?=$ID?>', {
                    center: [55.893354, 37.725765],
                    zoom: 16,
                    controls: ['zoomControl'],
                });

                map_10.setType('yandex#map');
                map_10.behaviors
                    .disable('scrollZoom')
                    .disable(['drag', 'rightMouseButtonMagnifier']);

                let coords = {
                    <?foreach($region as $reg):
                        if(!empty($reg->getMap()->getAll())):
                            $index = 0;
                            foreach($reg->getMap()->getAll() as $coord):?>
                                <?=$reg->getId()?><?=$index?>: {
                                    'cord': [<?=$coord->getValue()?>],
                                    'name': '<?=$coord->getDescription() ?: $reg->getName()?>'
                                },
                            <?
                            $index++;
                            endforeach;?>
                        <?endif?>
                    <?endforeach?>
                };

                let myCoolectionP = new ymaps.GeoObjectCollection({}, {
                    preset: 'islands#blueEducationCircleIcon',
                    iconColor: '#DD445A'
                });

                for (let k in coords) {
                    myCoolectionP.add(new ymaps.Placemark(coords[k].cord, {
                        iconCaption: `${coords[k].name}`,
                        balloonContentHeader: `${coords[k].name}`,
                    }));
                }

                map_10.geoObjects.add(myCoolectionP);
                map_10.setBounds(map_10.geoObjects.getBounds(),{checkZoomRange:true, zoomMargin:9});
                document.querySelector('#jkevo--map-<?=$ID?>').querySelector('.a2-lazy-preloader').remove()
            }
        </script>
    </div>
</section>