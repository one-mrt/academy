<?
global $APPLICATION;

use Lang\Route\Page;
use Bitrix\Main\Loader;
use Bitrix\Main\Routing\RoutingConfigurator;
use Bitrix\Main\Routing\Controllers\PublicPageController as PPage;

Loader::includeModule('lang.route');
Loader::includeModule('lang.iblock');

return static function(RoutingConfigurator $routes)
{
    $routes->prefix("service")->group(static function(RoutingConfigurator $routes) {
        $routes->get('', [Page::class, 'list']);

        $routes->prefix("{code}")->where('code', '([a-z0-9_-]+)')
            ->group(static function(RoutingConfigurator $routes) {
                $routes->get('', [Page::class, 'detail']);
            });
    });

    $routes->prefix("expert")->group(static function(RoutingConfigurator $routes) {

        $routes->prefix("{code}")->where('code', '([a-z0-9_-]+)')
            ->group(static function(RoutingConfigurator $routes) {
                $routes->get('', [Page::class, 'detailExpert']);
            });
    });

    $routes->prefix("experts")->group(static function(RoutingConfigurator $routes) {

        $routes->prefix("{login}")->where('login', '([a-z0-9_-]+)')
            ->group(static function(RoutingConfigurator $routes) {
                $routes->get('', [Page::class, 'getDetailExperts']);
            });
    });

    $routes->prefix("blog")->group(static function(RoutingConfigurator $routes) {

        $routes->prefix("{code}")->where('code', '([a-z0-9_-]+)')
            ->group(static function(RoutingConfigurator $routes) {
                $routes->get('', [Page::class, 'detailBlog']);
            });
    });

    $routes->prefix("help")->group(static function(RoutingConfigurator $routes) {

        $routes->prefix("{code}")->where('code', '([a-z0-9_-]+)')
            ->group(static function(RoutingConfigurator $routes) {
                $routes->get('', [Page::class, 'detailHelp']);
            });
    });

    $routes->any("/rest/{method}", new PPage('/bitrix/services/rest/index.php'))
        ->where('method', '([a-z0-9\.]+)');

    $routes->prefix('order')->group(function (RoutingConfigurator $routes) {
        $routes->any('{order}/', [Page::class, "getOrder"])
            ->where("order", "([a-z0-9-]+)");
    });

    $routes->prefix('lk')->group(static function (RoutingConfigurator $routes) {
        $routes->any('', [Page::class, 'profileLk']);

        $routes->prefix('setting')->group(static function (RoutingConfigurator $routes) {
            $routes->any('', [Page::class, 'setLk']);
        });

        $routes->prefix('order')->group(static function (RoutingConfigurator $routes) {
            $routes->any('', [Page::class, 'orderLk']);

            $routes->prefix('add')->group(static function (RoutingConfigurator $routes) {
                $routes->any('', [Page::class, 'orderAddLk']);
            });

            $routes->any('{order}/', [Page::class, 'orderLkDetail'])
                ->where('order', '([a-z0-9-]+)');
        });

        $routes->prefix('balans')->group(static function (RoutingConfigurator $routes) {
            $routes->any('', [Page::class, 'balansLk']);

            $routes->get('in/', [Page::class, 'balansLkIn']);
            $routes->get('out/', [Page::class, 'balansLkOut']);
        });
    });
};