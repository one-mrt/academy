<?
namespace Lang;

use Bitrix\Main\Loader;
use \Bitrix\Main\Web\Uri;
use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loader::includeModule('iblock');

class Region {

    private const IBLOCK_ELEMENTS = "\Bitrix\Iblock\Elements\Element";

    private function __construct()
    {
        
    }

    public static function getUrlCode() : array
    {
        $codeList = [];
        $server = Application::getInstance()->getContext()->getServer();
        $uri = new Uri($server->getRequestUri());
        $explode = explode('/', $uri->getPath());

        foreach($explode as $code)
        {
            if(!empty($code) && strpos($code, ".php") === false)
            {
                $codeList[] = $code;
            }
        }

        return $codeList;
    }

    public static function getCurrentCode()
    {
        return array_pop(self::getUrlCode());
    }

    public static function getTable() : string
    {
        $elements = self::IBLOCK_ELEMENTS;

        return  "{$elements}LangDomainTable";
    }

    /**
     * Возвраает массив для кэширования
     *
     * @param integer $cache - количество миллисекунд
     * @return array
     */
    public static function getCache(?int $cache = 36000000) : array
    {
        return ['ttl' => $cache, 'cache_joins' => true];
    }

    public static function getCurrent(array $param = []) : ?object
    {
        $serverName = Application::getInstance()->getContext()->getServer()->getServerName();

        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y', "domain.VALUE" => "https://{$serverName}"]);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        return self::getTable()::getList($param)->fetchObject();
    }

    public static function getAll(array $param = []) : ?object
    {
        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        return self::getTable()::getList($param)->fetchCollection();
    }
    
}