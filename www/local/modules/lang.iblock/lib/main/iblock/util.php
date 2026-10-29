<?
namespace Lang\Main\Iblock;

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Iblock\Component\Tools;
use Bitrix\Main\Engine\CurrentUser;

Loader::includeModule('iblock');

class Util {

    public const MODEL_SECTION = "\Bitrix\Iblock\Model\Section";
    public const IBLOCK_TABLE = "\Bitrix\Iblock\IblockTable";
    public const IBLOCK_ELEMENTS = "\Bitrix\Iblock\Elements\Element";
    public const SEO_PROPERTY_TABLE = "\Bitrix\Iblock\InheritedProperty";
    public const ENUM_PROPERTY_TABLE = "\Bitrix\Iblock\PropertyEnumerationTable";

    private function __construct()
    {
        
    }

    public static function getLang() : string
    {
        return (SITE_ID == 'en' || SITE_ID == 'tt') ? ucfirst(SITE_ID) : '';
    }

    /**
     * Возвращает класс таблицы инфоблока по api коду
     *
     * @param string $apiCode - api код инфоблока
     * @return string
     */
    public static function getTableString(string $apiCode) : string
    {
        $elements = self::IBLOCK_ELEMENTS;

        return  "{$elements}{$apiCode}Table";
    }

    /**
     * Возвращает таблицу инфоблока
     * @return string
     */
    public static function getTextTable() : string
    {
        $lang = self::getLang();

        return self::getTableString("LangText{$lang}");
    }

    public static function getSectionModel(string $apiCode) : string
    {
        $modelString = self::MODEL_SECTION;
        $model = $modelString::compileEntityByIblock($apiCode);

        return $model ?: "";
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

    /**
     * Редактирование элемента в публичной части
     *
     * @param array $item массив редактируемого элемента
     * @param object $component this компонента
     * @return void
     */
    public static function iblockElementEditAdd($item) : string
    {
        if(gettype($item) == 'array')
        {
            $editData = $item;

        }
        elseif(gettype($item) == 'object')
        {
            $editData["ID"] = $item->getId();
            $editData["IBLOCK_ID"] = $item->getIblockId();
        }

        $component = new \CBitrixComponent;
        $edit = \CIBlock::GetPanelButtons($editData["IBLOCK_ID"], $editData['ID'], 0, ["SESSID" => false]);
        $editData['EDIT_LINK'] = $edit['edit']['edit_element']['ACTION_URL'];
        $editData['DELETE_LINK'] = $edit['edit']['delete_element']['ACTION_URL'];
    
        $component->AddEditAction($editData['ID'], $editData['EDIT_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "ELEMENT_EDIT"));
        $component->AddDeleteAction($editData['ID'], $editData['DELETE_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

        return $component->GetEditAreaId($editData['ID']);
    }

    public static function iblockElementEditAddId(array|object $item) : string
    {
        $user = CurrentUser::get();

        if(gettype($item) == 'array')
        {
            $editData = $item;

        }
        elseif(gettype($item) == 'object')
        {
            $editData["ID"] = $item->getId();
            $editData["IBLOCK_ID"] = $item->getIblockId();
        }
        else
        {
            return "";
        }

        $component = new \CBitrixComponent;
        $edit = \CIBlock::GetPanelButtons($editData["IBLOCK_ID"], $editData['ID'], 0, ["SESSID" => false]);
        $editData['EDIT_LINK'] = $edit['edit']['edit_element']['ACTION_URL'];
        $editData['DELETE_LINK'] = $edit['edit']['delete_element']['ACTION_URL'];
    
        $component->AddEditAction($editData['ID'], $editData['EDIT_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "ELEMENT_EDIT"));
        $component->AddDeleteAction($editData['ID'], $editData['DELETE_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "ELEMENT_DELETE"), ["CONFIRM" => Loc::getMessage('CT_BNL_ELEMENT_DELETE_CONFIRM')]);

        if(in_array(13, $user->getUserGroups()) || in_array(1, $user->getUserGroups()))
        {
            $id = $component->GetEditAreaId($editData['ID']);
            return "id=\"{$id}\"";
        }
        else
        {
            return "";
        }
    }

    /**
     * Редактирование раздела в публичной части
     *
     * @param array $item массив редактируемого раздела
     * @param object $component this компонента
     * @return void
     */
    public static function iblockSectionEditAdd(array|object $item) : string
    {
        if(gettype($item) == 'array')
        {
            $editData = $item;

        }
        elseif(gettype($item) == 'object')
        {
            $editData["ID"] = $item->getId();
            $editData["IBLOCK_ID"] = $item->getIblockId();
        }

        $component = new \CBitrixComponent;
        $edit = \CIBlock::GetPanelButtons($editData["IBLOCK_ID"], 0, $editData['ID'], ["SESSID" => false]);
        $editData['EDIT_LINK'] = $edit['edit']['edit_section']['ACTION_URL'];
        $editData['DELETE_LINK'] = $edit['edit']['delete_section']['ACTION_URL'];
    
        $component->AddEditAction($editData['ID'], $editData['EDIT_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "SECTION_EDIT"));
        $component->AddDeleteAction($editData['ID'], $editData['DELETE_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "SECTION_DELETE"), ["CONFIRM" => Loc::getMessage('CT_BNL_SECTION_DELETE_CONFIRM')]);

        return $component->GetEditAreaId($editData['ID']);
    }

    public static function iblockSectionEditAddId($item) : string
    {
        global $USER;

        if(gettype($item) == 'array')
        {
            $editData = $item;

        }
        elseif(gettype($item) == 'object')
        {
            $editData["ID"] = $item->getId();
            $editData["IBLOCK_ID"] = $item->getIblockId();
        }

        $component = new \CBitrixComponent;
        $edit = \CIBlock::GetPanelButtons($editData["IBLOCK_ID"], 0, $editData['ID'], ["SESSID" => false]);
        $editData['EDIT_LINK'] = $edit['edit']['edit_section']['ACTION_URL'];
        $editData['DELETE_LINK'] = $edit['edit']['delete_section']['ACTION_URL'];
    
        $component->AddEditAction($editData['ID'], $editData['EDIT_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "SECTION_EDIT"));
        $component->AddDeleteAction($editData['ID'], $editData['DELETE_LINK'], \CIBlock::GetArrayByID($editData["IBLOCK_ID"], "SECTION_DELETE"), ["CONFIRM" => Loc::getMessage('CT_BNL_SECTION_DELETE_CONFIRM')]);

        if($USER->IsAdmin())
        {
            $id = $component->GetEditAreaId($editData['ID']);
            return "id=\"{$id}\"";
        }
        else
        {
            return "";
        }
    }

    /**
     * Возвращает ссылку на страницу по шаблону
     *
     * @param string $temp шаблон ссылки
     * @param array $item массив полей элемента (ID, IBLOCK_ID, CODE, IBLOCK_SECTION_ID)
     * @param bool $siteDir - флаг включения сайта в путь
     * @param string $type - тип ссылки, E - элемент, S - раздел, false - универсальная
     * @return void
     */
    public static function getDetailPageUrl(string $temp, array|object $item, bool $siteDir = true, string $type = "E") : string
    {
        if(gettype($item) == 'object')
        {
            $item = [
                "ID" => $item->getId(),
                'IBLOCK_ID' => $item->getIblockId(),
                'IBLOCK_SECTION_ID' => $item->getIblockSectionId(),
                'CODE' => $item->getCode()
            ];
        }

        return \CIBlock::ReplaceDetailUrl($temp, $item, $siteDir, $type);
    }

    /**
     * Возвращает ссылку на страницу по API коду
     * @param string $apiCode - API код инфоблока
     * @param string $item - массив полей элемента (ID, IBLOCK_ID, CODE, IBLOCK_SECTION_ID)
     * @param bool $siteDir - флаг включения сайта в путь
     * @param string $type - тип ссылки, E - элемент, S - раздел, false - универсальная
     * @return string ссылка на страницу
     */
    public static function getDetailPageUrlApiCode(string $apiCode, array|object $item, bool $siteDir = true, string $type = "E") : string
    {
        if($type == "E")
        {
            $field = "DETAIL_PAGE_URL";
        }
        else
        {
            $field = "SECTION_PAGE_URL";
        }

        if(gettype($item) == 'object')
        {
            $item = [
                "ID" => $item->getId(),
                'IBLOCK_ID' => $item->getIblockId(),
                'IBLOCK_SECTION_ID' => $item->getIblockSectionId(),
                'CODE' => $item->getCode()
            ];
        }

        $iblock = self::getIblock([$field], ['API_CODE' => $apiCode]);

        return \CIBlock::ReplaceDetailUrl($iblock[$field], $item, $siteDir, $type);
    }

    /**
     * Возвращает инфоблок по фильтру
     *
     * @param array $select - список полей
     * @param array $filter - фильтр
     * @param integer|null $cache
     * @return array
     */
    public static function getIblock(array $select, array $filter, ?int $cache = 36000000) : array
    {
        $table = self::IBLOCK_TABLE;
        $result = $table::getList(['select' => $select,'filter' => $filter,'cache' => self::getCache($cache)])->fetch();

        return $result ?: [];
    }

    public static function getIblockObject(int|string $iblock, array $param = []) : array | object
    {
        if(intval($iblock) > 0)
        {
            $param['filter']['ID'] = intval($iblock);
        }
        else
        {
            $param['filter']['API_CODE'] = $iblock;
        }

        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        $result = (self::IBLOCK_TABLE)::getList($param)->fetchObject();

        return $result ?: [];
    }

    /**
     * Выборка раздела с результатом в массиве
     *
     * @param integer $iblockId id инфоблока
     * @param array $select массив полей для выборки
     * @param array $filter массив полей для фильтрации
     * @param integer|null $cache время кэширования
     * @return array результат выборки
     */
    public static function getSection(int|string $iblock, array $select, array $filter, ?int $cache=36000000) : array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $iblockId = intval($iblock);
        }
        else
        {
            $iblockId = self::getIblock(['ID'], ['API_CODE' => $iblock])['ID'];
        }
        
        $modelString = self::MODEL_SECTION;
        $model = $modelString::compileEntityByIblock($iblockId);
        $select = array_merge($select, ['ACTIVE', 'ID', 'IBLOCK_ID']);
        $filter = array_merge($filter, ['ACTIVE' => 'Y']);

        $section = $model::getList(['select' => $select,'filter' => $filter,'cache' => self::getCache($cache)])->fetch();

        return $section ?: [];
    }

        /**
     * Выборка раздела с результатом в массиве
     *
     * @param integer $iblockId id инфоблока
     * @param array $select массив полей для выборки
     * @param array $filter массив полей для фильтрации
     * @param integer|null $cache время кэширования
     * @return array результат выборки
     */
    public static function getSectionObject(int|string $iblock, array $param) : object | array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $iblockId = intval($iblock);
        }
        else
        {
            $iblockId = self::getIblock(['ID'], ['API_CODE' => $iblock])['ID'];
        }
        
        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        return self::MODEL_SECTION::compileEntityByIblock($iblockId)::getList($param)->fetchObject() ?: [];
    }

    /**
     * Выборка разделов с результатом в массиве
     *
     * @param integer $iblockId id инфоблока
     * @param array $order массив полей для сортировки
     * @param array $select массив полей для выборки
     * @param array $filter массив полей для фильтрации
     * @param integer|null $cache время кэширования
     * @return array результат выборки
     */
    public static function getSections(int|string $iblock, array $param) : array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $iblockId = intval($iblock);
        }
        else
        {
            $iblockId = self::getIblock(['ID'], ['API_CODE' => $iblock])['ID'];
        }

        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        return self::MODEL_SECTION::compileEntityByIblock($iblockId)::getList($param)->fetchAll();
    }

    public static function getSectionsObject(int|string $iblock, array $param) : object | array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $iblockId = intval($iblock);
        }
        else
        {
            $iblockId = self::getIblock(['ID'], ['API_CODE' => $iblock])['ID'];
        }

        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        return self::MODEL_SECTION::compileEntityByIblock($iblockId)::getList($param)->fetchCollection() ?: [];
    }

    /**
     * Получение элемента с результатом в массиве
     *
     * @param string $apiCode апи код инфоблока
     * @param array $select массив полей для выборки
     * @param array $filter массив полей для фильтрации
     * @param integer|null $cache время кэширования
     * @return array результат выборки
     */
    public static function getElement(int|string $iblock, array $select, array $filter, ?int $cache=36000000) : array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $apiCode = self::getIblock(['API_CODE'], ['ID' => $iblock])['API_CODE'];
        }
        else
        {
            $apiCode = $iblock;
        }

        $select = array_merge($select, ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $filter = array_merge($filter, ['ACTIVE' => 'Y']);
        $element = self::getTableString($apiCode)::getList(['select'=>$select,'filter'=>$filter,'cache'=>self::getCache($cache)])->fetch();

        return $element ?: [];
    }

    public static function getElements(int|string $iblock, array $param) : array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $apiCode = self::getIblock(['API_CODE'], ['ID' => $iblock])['API_CODE'];
        }
        else
        {
            $apiCode = $iblock;
        }

        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        $elements = self::getTableString($apiCode)::getList($param)->fetchAll();

        return $elements ?: [];
    }

    public static function getElementsObject(int|string $iblock, array $param) : object | array
    {
        if(empty($iblock))
        {
            return [];
        }

        if(intval($iblock) > 0)
        {
            $apiCode = self::getIblock(['API_CODE'], ['ID' => $iblock])['API_CODE'];
        }
        else
        {
            $apiCode = $iblock;
        }

        $param['order'] = array_merge($param['order'] ?: [], ['SORT' => 'ASC', 'ID' => 'DESC']);
        $param['select'] = array_merge($param['select'] ?: [], ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $param['filter'] = array_merge($param['filter'] ?: [], ['ACTIVE' => 'Y']);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        $elements = self::getTableString($apiCode)::getList($param)->fetchCollection();

        return $elements ?: [];
    }

    /**
     * Получение элемента с результатом в объекте
     * 
     * @param string $apiCode апи код инфоблока
     * @param array $select массив полей для выборки
     * @param array $filter массив полей для фильтрации
     * @param integer|null $cache время кэширования
     * @return object результат выборки
     */
    public static function getElementObject(string $apiCode, array $select, array $filter, ?int $cache=36000000) : object | array
    {
        $select = array_merge($select, ['ID', 'IBLOCK_ID', 'ACTIVE']);
        $filter = array_merge($filter, ['ACTIVE' => 'Y']);

        $element = self::getTableString($apiCode)::getList(['select'=>$select,'filter'=>$filter,'cache'=>self::getCache($cache)])->fetchObject();

        return $element ?: [];
    }

    /**
     * Получение SEO свойств раздела
     * @param string $apiCode - API код инфоблока
     * @param string $id - ID раздела
     * @return array
     */
    public static function getSeoPropertySection(string $apiCode, string $id) : array
    {
        $propTable = self::SEO_PROPERTY_TABLE;
        $propTable = "{$propTable}\SectionValues";
        $iblock = self::getIblock(['ID'], ['API_CODE' => $apiCode]);

        if(!empty($iblock))
        {
            $seoProperty = new $propTable($iblock["ID"], $id);

            return $seoProperty->getValues();
        }
        else
        {
            return [];
        }
    }

    public static function getSeoPropertySectionObject(string $apiCode, string $id) : ?object
    {
        $propTable = self::SEO_PROPERTY_TABLE;
        $propTable = "{$propTable}\SectionValues";
        $iblock = self::getIblock(['ID'], ['API_CODE' => $apiCode]);

        if(!empty($iblock))
        {
            $seoProperty = new $propTable($iblock["ID"], $id);

            return $seoProperty->fetch();
        }
        else
        {
            return null;
        }
    }

    public static function getSeoPropertyElement(string $apiCode, string $id) : array
    {
        $propTable = self::SEO_PROPERTY_TABLE;
        $propTable = "{$propTable}\ElementValues";
        $iblock = self::getIblock(['ID'], ['API_CODE' => $apiCode]);

        if(!empty($iblock))
        {
            $seoProperty = new $propTable($iblock["ID"], $id);

            return $seoProperty->getValues();
        }
        else
        {
            return [];
        }
    }

    public static function getEnumElementObject(array $param = []) : ?object
    {
        $enumTable = self::ENUM_PROPERTY_TABLE;

        if(empty($param))
        {
            return null;
        }

        $param['order'] = array_merge(['SORT' => 'ASC', 'ID' => 'DESC'], $param['order'] ?: []);
        $param['select'] = array_merge(['ID', 'PROPERTY.ACTIVE'], $param['select'] ?: []);
        $param['filter'] = array_merge(['PROPERTY.ACTIVE' => 'Y', ], $param['filter'] ?: []);

        if(empty($param['cache']))
        {
            $param['cache'] = self::getCache();
        }

        $values = $enumTable::getList($param)->fetchCollection();

        return $values ?: null;
    }

    public static function get404()
    {
        Tools::process404(
            trim($arParams["MESSAGE_404"] ?? '') ?: Loc::getMessage("BLOG_MESSAGE_404"),
            true,
            true,
            true,
            SITE_TEMPLATE_PATH . "/page/404.php"
        );
    }

    public static function getImage(array $param = []) : string
    {
        $img = "<img ";

        foreach($param as $attribute => $value) {
            $img .= "{$attribute}=\"{$value}\" ";
        }

        $img .= ">";

        return $img;
    }

    public static function getImageId(int $id, array $param = []) : string
    {
        $image = \CFile::GetFileArray($id);
        $img = "<img src=\"{$image['SRC']}\" width=\"{$image['WIDTH']}\" height=\"{$image['HEIGHT']}\" ";

        foreach($param as $attribute => $value) {
            $img .= "{$attribute}=\"{$value}\" ";
        }

        $img .= ">";

        return $img;
    }
}

?>