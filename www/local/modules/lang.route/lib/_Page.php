<?
namespace Lang\Route;

use Exception;
use Lang\Route;
use Bitrix\Main\Error;
use Bitrix\Main\Loader;
use Bitrix\Main\Context;
use Bitrix\Main\Web\JWT;
use Lang\Main\Iblock\Util;
use Bitrix\Main\UserTable;
use Bitrix\Main\Mail\Event;
use Bitrix\Main\Application;
use Bitrix\Main\Engine\Controller;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\ActionFilter;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\ActionFilter\Scope;
use Bitrix\Main\Engine\ActionFilter\HttpMethod;
use Bitrix\Main\Engine\ActionFilter\Attribute\Rule\Prefilters;

use Bitrix\Sale\Order;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Entity\ReferenceField;
use Bitrix\Main\ORM\Fields\Relations\Reference;

use Bitrix\Main\Engine\Response\Redirect;

// use Bitrix\Main\Engine\ActionFilter\Attribute\Rule\HttpMethod as HttpMethodAttr;
// use Bitrix\Main\Engine\ActionFilter\Attribute\Rule\Authentication;
// use Bitrix\Main\Engine\ActionFilter\Attribute\Rule\Csrf;

final class Page extends Controller {

    private string $pathTemplate = SITE_TEMPLATE_PATH;
    private string $path404 = "/page/404.php";

    public function listAction()
    {
        $documentRoot = $_SERVER['DOCUMENT_ROOT'];
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $templatePath = "{$documentRoot}{$siteTemplatePath}";

        return $this->renderView("{$documentRoot}/service/index.php", [], true);
    }

    public function detailAction()
    {
        $code = htmlspecialcharsbx($_REQUEST['code']);
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $serviceList = Util::getElementsObject("LangService", ['select' => ['ID'], 'filter' => ['CODE' => $code]]);

        if(!empty($serviceList) && !empty($serviceList->getIdList())) {
            $urlServiceDetail = "/service/.detail.php";
        } else {
           $urlServiceDetail = "{$siteTemplatePath}/page/404.php";
        }

        return $this->renderView($urlServiceDetail, ['code' => $code], true);
    }

    public function detailExpertAction()
    {
        $code = htmlspecialcharsbx($_REQUEST['code']);
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $isExpert = Util::getElementObject("LangExpert", ['ID'], ['CODE' => $code]);

        if(!empty($isExpert) && !empty($isExpert->getId())) {
            $urlDetail = "/expert/.detail.php";
        } else {
           $urlDetail = "{$siteTemplatePath}/page/404.php";
        }

        return $this->renderView($urlDetail, ['code' => $code], true);
    }

    public function detailBlogAction()
    {
        $code = htmlspecialcharsbx($_REQUEST['code']);
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $isExpert = Util::getElementObject("LangBlog", ['ID'], ['CODE' => $code]);

        if(!empty($isExpert) && !empty($isExpert->getId())) {
            $urlDetail = "/blog/.detail.php";
        } else {
           $urlDetail = "{$siteTemplatePath}/page/404.php";
        }

        return $this->renderView($urlDetail, ['code' => $code], true);
    }

    public function detailHelpAction()
    {
        $code = htmlspecialcharsbx($_REQUEST['code']);
        $siteTemplatePath = SITE_TEMPLATE_PATH;
        $isExpert = Util::getElementObject("LangHelp", ['ID'], ['CODE' => $code]);

        if(!empty($isExpert) && !empty($isExpert->getId())) {
            $urlDetail = "/help/.detail.php";
        } else {
           $urlDetail = "{$siteTemplatePath}/page/404.php";
        }

        return $this->renderView($urlDetail, ['code' => $code], true);
    }

    private function redirectLk307()
    {
        new Redirect("/", true)
            ->setStatus("307 Temporary Redirect")
            ->send();
    }

    public function includeFileLk(string $path, string $file)
    {
        $user = CurrentUser::get();

        if(empty($user->getId())) {
            return $this->renderComponent('lang:auth', '', []);
        } else {
            return $this->renderView($path, ['file' => $file, 'order' => 1], true);
            // return $this->renderComponent('lang:include', '', [
            //     "AREA_FILE_SHOW" => "file",
            //     "AREA_FILE_SUFFIX" => "inc",
            //     "EDIT_TEMPLATE" => "",
            //     "PATH" => '/lk/.index.php',
            //     "content" => $file
            // ]);
        }
    }

    public function profileLkAction()
    {
        return $this->includeFileLk("/lk/index.php", ".view.profile.php");
    }

    public function mesLkAction()
    {
        return $this->includeFileLk("/lk/index.php", ".mes.php");
    }

    public function specLkAction()
    {
        return $this->includeFileLk("/lk/index.php", ".edit.spec.php");
    }

    public function setLkAction()
    {
        return $this->includeFileLk("/lk/index.php", ".setting.php");
    }

    public function orderLkAction()
    {
        return $this->includeFileLk("/lk/order/index.php", ".order.php");
    }

    public function orderLkDetailAction()
    {
        $user = CurrentUser::get();

        if(empty($user->getId())) {
            return $this->renderComponent('bitrix:system.auth.authorize', 'lang.auth', []);
        } else {
            $code = htmlspecialcharsbx($_REQUEST['order']);
            $code = htmlspecialcharsbx($code);
            $langServiceOrder = "LangServiceOrder";
            $path404 = "{$this->pathTemplate}{$this->path404}";

            if(!empty($code)) {
                $order = Util::getElementObject($langServiceOrder, ['uex.VALUE', 'uow.VALUE'], ['CODE' => $code]);

                if(!empty($order) && !empty($order?->getId()) && ($user->getId() == $order->getUow()?->getValue() || $user->getId() == $order->getUex()?->getValue())) {
                    $urlDetail = "/lk/order/.detail.php";
                    // $urlDetail = "{$this->pathTemplate}/include/lk/.detail.lk.php";
                    $code = $order->getId();
                    // return $this->renderComponent('lang:include', '', [
                    //     "AREA_FILE_SHOW" => "file",
                    //     "AREA_FILE_SUFFIX" => "inc",
                    //     "EDIT_TEMPLATE" => "",
                    //     "PATH" => '/lk/.detail.lk.php',
                    //     "order" => $order->getId()
                    // ]);
                } else {
                    $urlDetail = $path404;
                }
            } else {
                $urlDetail = $path404;
            }

            return $this->renderView("{$urlDetail}", ['order' => $code], true);
        }
    }

    public function getOrderAction()
    {
        $code = htmlspecialcharsbx($_REQUEST['order']);
        $code = htmlspecialcharsbx($code);

        $langServiceOrder = "LangServiceOrder";
        $path404 = "{$this->pathTemplate}{$this->path404}";

        if($code > 0) {
            Loader::includeModule('sale');

            $user = CurrentUser::get();
            $filter = Query::filter()->where('CODE', $code);

            if(!empty($user->getId())) {
                $filter
                    ->addCondition(
                        Query::filter()->logic('or')
                            ->where('status.STATUS_ID', 'N')
                            ->where('uex.VALUE', $user->getId())
                            ->where('status.USER_ID', $user->getId())
                    );
            } else {
                $filter->where('status.STATUS_ID', 'N');
            }
            
            $order = Data::getTableString($langServiceOrder)::getList([
                'select' => ['ID', 'order', 'status', 'uex'],
                'filter' => $filter,
                'runtime' => [
                    (new ReferenceField(
                        'status',
                        Order::class,
                        Join::on('this.order.VALUE', 'ref.ID'),
                    ))->configureJoinType('left'),
                ],
            ])->fetchObject();

            if(!empty($order) && !empty($order->getId())) {
                return $this->renderComponent('lang:include', '', [
                    "AREA_FILE_SHOW" => "file",
                    "AREA_FILE_SUFFIX" => "inc",
                    "EDIT_TEMPLATE" => "",
                    "PATH" => '/order/.main.php',
                    "content" => ".detail.php",
                    "param" => [
                        "breadcrumb" => "y",
                        "bannerBottom" => "n"
                    ],
                    "order" => $order->getId()
                ]);
            } else {
                return $this->renderView("{$path404}", [], true);
            }
        } else {
            return $this->renderView("{$path404}", [], true);
        }
    }
    
    public function balansLkAction()
    {
        return $this->includeFileLk("/lk/index.php", ".balans.php");
    }

    public function balansLkInAction()
    {
        return $this->includeFileLk("/lk/index.php", ".balansIn.php");
    }

    public function balansLkOutAction()
    {
        return $this->includeFileLk("/lk/index.php", ".balansOut.php");
    }

    public function orderAddLkAction()
    {
        return $this->includeFileLk("/lk/index.php", ".orderAdd.php");
    }

    private function includeFileExpert(string $file, array $param = [])
    {
        return $this->renderComponent('lang:include', '', [
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "inc",
            "EDIT_TEMPLATE" => "",
            "PATH" => '/experts/.main.php',
            "content" => $file,
            'param' => $param
        ]);
    }

    public function getDetailExpertsAction()
    {
        $path404 = "{$this->pathTemplate}{$this->path404}";
        $login = htmlspecialcharsbx($_REQUEST['login']);
        
        if(!empty($login)) {
            $user = UserTable::getList(['select' => ['ID'],'filter' => ['LOGIN' => $login],'cache' => Util::getCache(0)])->fetchObject();

            if(!empty($user) && !empty($user->getId())) {
                return $this->includeFileExpert(".detail.php", [
                    'userId' => $user->getId(),
                    'breadcrumb' => 'y',
                    'bannerBottom' => 'n',
                ]);
            } else {
                return $this->renderView("{$path404}", [], true);
            }
        } else {
            return $this->renderView("{$path404}", [], true);
        }
    }

    protected function getDefaultPreFilters()
    {
        return [];
    }

    public function getPrimaryAutoWiredParameter()
    {
		// return new \Bitrix\Main\Engine\AutoWire\ExactParameter(
		// 	Page::class,
		// 	'folder',
		// 	function($className, $id) {
		// 		return Folder::loadById($id);
		// 	}
		// );
	}
}