<?php
/**
 * Скрипт копирования инфоблока в Битрикс D7
 * Копирует инфоблок со всеми настройками, свойствами элементов и пользовательскими свойствами разделов
 */

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');

use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\PropertyTable;
use Bitrix\Iblock\SectionPropertyTable;
use Bitrix\Iblock\InheritedProperty\IblockValues;
use Bitrix\Iblock\InheritedProperty\IblockTemplates;
use Bitrix\Main\UserFieldTable;
use Bitrix\Main\UserFieldLangTable;
use Bitrix\Iblock\IblockSiteTable;

// Подключаем модули
if (!Loader::includeModule('iblock'))
{
    die('Модуль iblock не установлен');
}

// $enums = \Bitrix\Iblock\PropertyEnumerationTable::getList([
//             'filter' => ['PROPERTY_ID' => 2631],
//             'order' => ['SORT' => 'ASC']
//         ]);
// echo '<pre>';
// print_r($enums->fetchAll());
// echo '</pre>';

/**
 * Копирование инфоблока
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param string $newIblockCode Код нового инфоблока
 * @param string $newIblockName Название нового инфоблока
 * @return int|false ID нового инфоблока или false в случае ошибки
 */
function copyInfoblock($sourceIblockId, $iblockType, $iblockApiCode)
{
    // Получаем исходный инфоблок
    $sourceIblock = IblockTable::getById($sourceIblockId)->fetch();
    
    if (!$sourceIblock || empty($iblockType))
    {
        echo "Ошибка: Инфоблок с ID {$sourceIblockId} не найден.\n";
        return false;
    }

    $newIblockData = [
        'IBLOCK_TYPE_ID' => $iblockType,
        'API_CODE' => $iblockApiCode,
        'LID' => $sourceIblock['LID'],
        'CODE' => $sourceIblock['CODE'],
        'NAME' => $sourceIblock['NAME'],
        'ACTIVE' => $sourceIblock['ACTIVE'],
        'SORT' => $sourceIblock['SORT'],
        'LIST_PAGE_URL' => $sourceIblock['LIST_PAGE_URL'],
        'SECTION_PAGE_URL' => $sourceIblock['SECTION_PAGE_URL'],
        'DETAIL_PAGE_URL' => $sourceIblock['DETAIL_PAGE_URL'],
        'CANONICAL_PAGE_URL' => $sourceIblock['CANONICAL_PAGE_URL'],
        'PICTURE' => $sourceIblock['PICTURE'],
        'DESCRIPTION' => $sourceIblock['DESCRIPTION'],
        'DESCRIPTION_TYPE' => $sourceIblock['DESCRIPTION_TYPE'],
        'RSS_TTL' => $sourceIblock['RSS_TTL'],
        'RSS_ACTIVE' => $sourceIblock['RSS_ACTIVE'],
        'RSS_FILE_ACTIVE' => $sourceIblock['RSS_FILE_ACTIVE'],
        'RSS_FILE_LIMIT' => $sourceIblock['RSS_FILE_LIMIT'],
        'RSS_FILE_DAYS' => $sourceIblock['RSS_FILE_DAYS'],
        'RSS_YANDEX_ACTIVE' => $sourceIblock['RSS_YANDEX_ACTIVE'],
        'XML_ID' => $sourceIblock['XML_ID'],
        'TMP_ID' => $sourceIblock['TMP_ID'],
        'INDEX_ELEMENT' => $sourceIblock['INDEX_ELEMENT'],
        'INDEX_SECTION' => $sourceIblock['INDEX_SECTION'],
        'WORKFLOW' => $sourceIblock['WORKFLOW'],
        'BIZPROC' => $sourceIblock['BIZPROC'],
        'SECTION_CHOOSER' => $sourceIblock['SECTION_CHOOSER'],
        'LIST_MODE' => $sourceIblock['LIST_MODE'],
        'RIGHTS_MODE' => $sourceIblock['RIGHTS_MODE'],
        'SECTION_PROPERTY' => $sourceIblock['SECTION_PROPERTY'],
        'PROPERTY_INDEX' => $sourceIblock['PROPERTY_INDEX'],
        'LAST_CONV_ELEMENT' => $sourceIblock['LAST_CONV_ELEMENT'],
        'SOCNET_GROUP_ID' => $sourceIblock['SOCNET_GROUP_ID'],
        'EDIT_FILE_BEFORE' => $sourceIblock['EDIT_FILE_BEFORE'],
        'EDIT_FILE_AFTER' => $sourceIblock['EDIT_FILE_AFTER'],
        'SECTIONS_NAME' => $sourceIblock['SECTIONS_NAME'],
        'SECTION_NAME' => $sourceIblock['SECTION_NAME'],
        'ELEMENTS_NAME' => $sourceIblock['ELEMENTS_NAME'],
        'ELEMENT_NAME' => $sourceIblock['ELEMENT_NAME'],
        'ELEMENTS_NAME_2' => $sourceIblock['ELEMENTS_NAME_2'],
        'ELEMENT_NAME_2' => $sourceIblock['ELEMENT_NAME_2'],
        'EXTERNAL_ID' => $sourceIblock['EXTERNAL_ID'],
        'LANG_DIR' => $sourceIblock['LANG_DIR'],
        'SERVER_NAME' => $sourceIblock['SERVER_NAME'],
        'ELEMENT_ADD' => $sourceIblock['ELEMENT_ADD'],
        'ELEMENT_EDIT' => $sourceIblock['ELEMENT_EDIT'],
        'ELEMENT_DELETE' => $sourceIblock['ELEMENT_DELETE'],
        'SECTION_ADD' => $sourceIblock['SECTION_ADD'],
        'SECTION_EDIT' => $sourceIblock['SECTION_EDIT'],
        'SECTION_DELETE' => $sourceIblock['SECTION_DELETE'],
    ];
    
    echo "Начинаем копирование инфоблока: {$sourceIblock['NAME']} (ID: {$sourceIblockId})\n";
    
    // Создаем новый инфоблок
    $result = IblockTable::add($newIblockData);
    
    if (!$result->isSuccess())
    {
        echo "Ошибка при создании инфоблока: " . implode(', ', $result->getErrorMessages()) . "\n";
        return false;
    }
    
    $newIblockId = $result->getId();
    echo "Инфоблок создан с ID: {$newIblockId}\n";

    // Копируем привязку инфоблока к сайтам
    iblockSite($sourceIblock, $newIblockId);

    // Копируем SEO
    iblockSeo($sourceIblockId, $newIblockId);
    
    // Копируем свойства элементов
    copyElementProperties($sourceIblockId, $newIblockId);
    
    // Копируем пользовательские свойства разделов (UF_)
    copySectionUserFields($sourceIblockId, $newIblockId);
    
    // Копируем настройки свойств разделов (SectionProperty)
    copySectionProperties($sourceIblockId, $newIblockId);

    // Копируем настройки полей элементов инфоблока
    copyIblockFields($sourceIblockId, $newIblockId);
    
    // Копируем настройки полей разделов инфоблока
    // copyIblockSectionFields($sourceIblockId, $newIblockId);

    // Копируем права доступа
    copyAccessRights($sourceIblockId, $newIblockId);

    \CUserOptions::SetOption("form", "form_element_{$newIblockId}", \CUserOptions::GetOption('form', "form_element_{$sourceIblockId}", 'list'), true);
    \CUserOptions::SetOption("form", "form_section_{$newIblockId}", \CUserOptions::GetOption('form', "form_section_{$sourceIblockId}", 'list'), true);
    
    echo "Копирование инфоблока завершено успешно!\n";
    return $newIblockId;
}

function iblockSite($sourceIblockId, $newIblockId)
{
    $siteList = IblockSiteTable::getList(['filter' => ['IBLOCK_ID' => $sourceIblockId]])->fetchCollection();
    foreach($siteList as $site)
    {
        IblockSiteTable::add(['IBLOCK_ID' => $newIblockId, 'SITE_ID' => $site->getSiteId()]);
    }
}

function iblockSeo($sourceIblockId, $newIblockId)
{
    $seoIblockTemp = new IblockTemplates($sourceIblockId)->findTemplates();

    $newSeo = new IblockTemplates($newIblockId);
    $listFieldSeo = [];

    foreach($seoIblockTemp as $seo)
    {
        $listFieldSeo[$seo['CODE']] = $seo['TEMPLATE'];
    }

    $newSeo->set($listFieldSeo);
}

/**
 * Копирование свойств элементов
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param int $newIblockId ID нового инфоблока
 */
function copyElementProperties($sourceIblockId, $newIblockId) {
    echo "\nКопирование свойств элементов...\n";
    
    $properties = PropertyTable::getList([
        'filter' => ['IBLOCK_ID' => $sourceIblockId],
        'order' => ['SORT' => 'ASC']
    ]);
    
    $count = 0;
    while ($property = $properties->fetch()) {
        // Подготовка данных для нового свойства
        $newPropertyData = [
            'IBLOCK_ID' => $newIblockId,
            'NAME' => $property['NAME'],
            'ACTIVE' => $property['ACTIVE'],
            'SORT' => $property['SORT'],
            'CODE' => $property['CODE'],
            'PROPERTY_TYPE' => $property['PROPERTY_TYPE'],
            'MULTIPLE' => $property['MULTIPLE'],
            'IS_REQUIRED' => $property['IS_REQUIRED'],
            'SEARCHABLE' => $property['SEARCHABLE'],
            'FILTRABLE' => $property['FILTRABLE'],
            'WITH_DESCRIPTION' => $property['WITH_DESCRIPTION'],
            'MULTIPLE_CNT' => $property['MULTIPLE_CNT'],
            'HINT' => $property['HINT'],
            'ROW_COUNT' => $property['ROW_COUNT'],
            'COL_COUNT' => $property['COL_COUNT'],
            'LIST_TYPE' => $property['LIST_TYPE'],
            'FILE_TYPE' => $property['FILE_TYPE'],
            'LINK_IBLOCK_ID' => $property['LINK_IBLOCK_ID'],
            'DEFAULT_VALUE' => $property['DEFAULT_VALUE'],
            'XML_ID' => $property['XML_ID'],
            'FILE_EXT' => $property['FILE_EXT'],
            'TMP_ID' => $property['TMP_ID'],
            'USER_TYPE' => $property['USER_TYPE'],
            'USER_TYPE_SETTINGS' => $property['USER_TYPE_SETTINGS'],
        ];
        
        $result = PropertyTable::add($newPropertyData);
        
        if ($result->isSuccess()) {
            $newPropertyId = $result->getId();
            echo "  - Свойство '{$property['NAME']}' скопировано (ID: {$property['ID']} -> {$newPropertyId})\n";
            
            // Копируем значения списка (для свойств типа LIST)
            if ($property['PROPERTY_TYPE'] == 'L') {
                copyPropertyEnumValues($property['ID'], $newPropertyId);
            }
            $count++;
        } else {
            echo "  - Ошибка при копировании свойства '{$property['NAME']}': " . 
                 implode(', ', $result->getErrorMessages()) . "\n";
        }
    }
    
    echo "Скопировано свойств элементов: {$count}\n";
}

/**
 * Копирование значений списка для свойств типа LIST
 * 
 * @param int $sourcePropertyId ID исходного свойства
 * @param int $newPropertyId ID нового свойства
 */
function copyPropertyEnumValues($sourcePropertyId, $newPropertyId) {
    if (!Loader::includeModule('iblock')) {
        return;
    }
    
    $enumTable = \Bitrix\Iblock\PropertyEnumerationTable::class;
    
    if (!class_exists($enumTable)) {
        // Используем старый API если новый недоступен
        $dbEnums = CIBlockProperty::GetPropertyEnum($sourcePropertyId);
        while ($enum = $dbEnums->Fetch()) {
            $newEnum = new CIBlockPropertyEnum();
            $newEnum->Add([
                'PROPERTY_ID' => $newPropertyId,
                'VALUE' => $enum['VALUE'],
                'DEF' => $enum['DEF'],
                'SORT' => $enum['SORT'],
                'XML_ID' => $enum['XML_ID'],
            ]);
        }
    } else {
        // Используем D7 API
        $enums = \Bitrix\Iblock\PropertyEnumerationTable::getList([
            'filter' => ['PROPERTY_ID' => $sourcePropertyId],
            'order' => ['SORT' => 'ASC']
        ]);
        
        while ($enum = $enums->fetch()) {
            // $newEnumData = [
            //     'PROPERTY_ID' => $newPropertyId,
            //     'VALUE' => $enum['VALUE'],
            //     'DEF' => $enum['DEF'],
            //     'SORT' => $enum['SORT'],
            //     'XML_ID' => $enum['XML_ID'],
            // ];
            
            $en = \Bitrix\Iblock\PropertyEnumerationTable::createObject()
                ->setPropertyId($newPropertyId)
                ->setValue($enum['VALUE'])
                ->setDef($enum['DEF'])
                ->setSort($enum['SORT'])
                ->setXmlId($enum['XML_ID']);

            
            $result = $en->save();

            if ($result->isSuccess()) {
                echo 'Создан элемент с ID: ' . $en->getId();
            } else {
                $newEnum = new CIBlockPropertyEnum();
                $newEnum->Add([
                    'PROPERTY_ID' => $newPropertyId,
                    'VALUE' => $enum['VALUE'],
                    'DEF' => $enum['DEF'],
                    'SORT' => $enum['SORT'],
                    'XML_ID' => $enum['XML_ID'],
                ]);
                // print_r($result->getErrorMessages());
            }
        }
    }
}

/**
 * Копирование пользовательских свойств разделов (UF_)
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param int $newIblockId ID нового инфоблока
 */
function copySectionUserFields($sourceIblockId, $newIblockId) {
    echo "\nКопирование пользовательских свойств разделов (UF_)...\n";
    
    // Формируем entity_id для пользовательских полей разделов
    $sourceEntityId = "IBLOCK_{$sourceIblockId}_SECTION";
    $newEntityId = "IBLOCK_{$newIblockId}_SECTION";
    
    $userFields = UserFieldTable::getList([
        'filter' => ['ENTITY_ID' => $sourceEntityId],
        'order' => ['SORT' => 'ASC']
    ]);
    
    $count = 0;
    while ($userField = $userFields->fetch()) {
        // Подготовка данных для нового пользовательского поля
        $newUserFieldData = [
            'ENTITY_ID' => $newEntityId,
            'FIELD_NAME' => $userField['FIELD_NAME'],
            'USER_TYPE_ID' => $userField['USER_TYPE_ID'],
            'XML_ID' => $userField['XML_ID'],
            'SORT' => $userField['SORT'],
            'MULTIPLE' => $userField['MULTIPLE'],
            'MANDATORY' => $userField['MANDATORY'],
            'SHOW_FILTER' => $userField['SHOW_FILTER'],
            'SHOW_IN_LIST' => $userField['SHOW_IN_LIST'],
            'EDIT_IN_LIST' => $userField['EDIT_IN_LIST'],
            'IS_SEARCHABLE' => $userField['IS_SEARCHABLE'],
            'SETTINGS' => $userField['SETTINGS'],
        ];
        
        // Добавляем языковые названия
        // $langFields = [];
        // $langData = CUserTypeEntity::GetList([], ['FIELD_ID' => $userField['ID']]);
        // while ($lang = $langData->Fetch()) {
        //     $langFields[$lang['LANGUAGE_ID']] = [
        //         'NAME' => $lang['NAME'],
        //         'HELP_MESSAGE' => $lang['HELP_MESSAGE'],
        //     ];
        // }

        $langData = UserFieldLangTable::getList([
            'select' => ['*'],
            'filter' => ['USER_FIELD_ID' => $userField['ID']]
        ])->fetchAll();

        foreach($langData as $lData)
        {
            $newUserFieldData["EDIT_FORM_LABEL"][$lData["LANGUAGE_ID"]] = $lData['EDIT_FORM_LABEL'];
            $newUserFieldData["LIST_COLUMN_LABEL"][$lData["LANGUAGE_ID"]] = $lData['LIST_COLUMN_LABEL'];
            $newUserFieldData["LIST_FILTER_LABEL"][$lData["LANGUAGE_ID"]] = $lData['LIST_FILTER_LABEL'];
            $newUserFieldData["ERROR_MESSAGE"][$lData["LANGUAGE_ID"]] = $lData['ERROR_MESSAGE'];
            $newUserFieldData["HELP_MESSAGE"][$lData["LANGUAGE_ID"]] = $lData['HELP_MESSAGE'];
        }
        // while ($lang = $langData->fetch()) {
        //     $langFields[$lang['LANGUAGE_ID']] = [
        //         'NAME' => $lang['NAME'],
        //         'HELP_MESSAGE' => $lang['HELP_MESSAGE'],
        //     ];
        //     echo '<pre>';print_r($lang);echo '</pre>';
        // }
        
        // Создаем поле через старый API для корректной работы с языковыми настройками
        $obUserField = new CUserTypeEntity();
        $newUserFieldId = $obUserField->Add($newUserFieldData);
        
        if ($newUserFieldId) {
            // Добавляем языковые настройки
            // foreach ($langFields as $langId => $langField) {
            //     $langTable = new CUserTypeEntityLang();
            //     $langTable->Add([
            //         'USER_FIELD_ID' => $newUserFieldId,
            //         'LANGUAGE_ID' => $langId,
            //         'NAME' => $langField['NAME'],
            //         'HELP_MESSAGE' => $langField['HELP_MESSAGE'],
            //     ]);
            // }
            
            echo "  - Пользовательское поле '{$userField['FIELD_NAME']}' скопировано (ID: {$userField['ID']} -> {$newUserFieldId})\n";
            $count++;
        } else {
            echo "  - Ошибка при копировании пользовательского поля '{$userField['FIELD_NAME']}': " . 
                 $obUserField->LAST_ERROR . "\n";
        }
    }
    
    echo "Скопировано пользовательских свойств разделов: {$count}\n";
}

/**
 * Копирование настроек свойств разделов (SectionProperty)
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param int $newIblockId ID нового инфоблока
 */
function copySectionProperties($sourceIblockId, $newIblockId) {
    echo "\nКопирование настроек свойств разделов...\n";
    
    if (!Loader::includeModule('iblock')) {
        return;
    }
    
    // Получаем все свойства исходного инфоблока с настройками разделов
    $properties = PropertyTable::getList([
        'filter' => ['IBLOCK_ID' => $sourceIblockId],
        'select' => ['ID', 'CODE']
    ]);
    
    $sourcePropertyIds = [];
    $propertyMap = []; // Карта соответствия старых ID -> новых ID
    
    while ($property = $properties->fetch()) {
        $sourcePropertyIds[] = $property['ID'];
        
        // Находим соответствующее свойство в новом инфоблоке
        $newProperty = PropertyTable::getList([
            'filter' => [
                'IBLOCK_ID' => $newIblockId,
                'CODE' => $property['CODE']
            ],
            'select' => ['ID']
        ])->fetch();
        
        if ($newProperty) {
            $propertyMap[$property['ID']] = $newProperty['ID'];
        }
    }
    
    // Проверяем наличие таблицы SectionProperty
    if (class_exists('\Bitrix\Iblock\SectionPropertyTable')) {
        $sectionProperties = \Bitrix\Iblock\SectionPropertyTable::getList([
            'filter' => ['PROPERTY_ID' => $sourcePropertyIds]
        ]);
        
        $count = 0;
        while ($sectionProperty = $sectionProperties->fetch()) {
            if (isset($propertyMap[$sectionProperty['PROPERTY_ID']])) {
                $newSectionPropertyData = [
                    'IBLOCK_ID' => $newIblockId,
                    'SECTION_ID' => $sectionProperty['SECTION_ID'],
                    'PROPERTY_ID' => $propertyMap[$sectionProperty['PROPERTY_ID']],
                    'SMART_FILTER' => $sectionProperty['SMART_FILTER'],
                    'DISPLAY_TYPE' => $sectionProperty['DISPLAY_TYPE'],
                    'DISPLAY_EXPANDED' => $sectionProperty['DISPLAY_EXPANDED'],
                    'FILTER_HINT' => $sectionProperty['FILTER_HINT'],
                ];
                
                $result = \Bitrix\Iblock\SectionPropertyTable::add($newSectionPropertyData);
                if ($result->isSuccess()) {
                    $count++;
                }
            }
        }
        
        if ($count > 0) {
            echo "Скопировано настроек свойств разделов: {$count}\n";
        }
    }
}

/**
 * Копирование настроек полей элементов инфоблока
 * (настройки символьного кода, обязательности полей и т.д.)
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param int $newIblockId ID нового инфоблока
 */
function copyIblockFields($sourceIblockId, $newIblockId) {
    echo "\nКопирование настроек полей элементов инфоблока...\n";
    
    if (!Loader::includeModule('iblock')) {
        return;
    }
    
    try {
        // Получаем настройки полей исходного инфоблока
        $sourceFields = CIBlock::GetFields($sourceIblockId);
        
        if (empty($sourceFields)) {
            echo "Настройки полей элементов не найдены.\n";
            return;
        }
        
        // Устанавливаем настройки полей для нового инфоблока
        CIBlock::SetFields($newIblockId, $sourceFields);
    } catch (Exception $e) {
        echo "Ошибка при копировании настроек полей элементов: " . $e->getMessage() . "\n";
    }
}

/**
 * Копирование настроек полей разделов инфоблока
 * (настройки символьного кода, обязательности полей и т.д.)
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param int $newIblockId ID нового инфоблока
 */
// function copyIblockSectionFields($sourceIblockId, $newIblockId) {
//     echo "\nКопирование настроек полей разделов инфоблока...\n";
    
//     if (!Loader::includeModule('iblock')) {
//         return;
//     }
    
//     try {
//         // Получаем настройки полей разделов исходного инфоблока
//         $sourceFields = CIBlock::GetSectionFields($sourceIblockId);
        
//         if (empty($sourceFields)) {
//             echo "Настройки полей разделов не найдены.\n";
//             return;
//         }
        
//         // Устанавливаем настройки полей разделов для нового инфоблока
//         $result = CIBlock::SetSectionFields($newIblockId, $sourceFields);
//     } catch (Exception $e) {
//         echo "Ошибка при копировании настроек полей разделов: " . $e->getMessage() . "\n";
//     }
// }

/**
 * Копирование прав доступа к инфоблоку
 * 
 * @param int $sourceIblockId ID исходного инфоблока
 * @param int $newIblockId ID нового инфоблока
 */
function copyAccessRights($sourceIblockId, $newIblockId) {
    echo "\nКопирование прав доступа...\n";
    
    if (!Loader::includeModule('iblock'))
    {
        return;
    }
    
    try {
        if($permissionsIblock = CIBlock::GetGroupPermissions($sourceIblockId))
        {
            CIBlock::SetPermission($newIblockId, $permissionsIblock);
        }
    } catch (Exception $e) {
        echo "Ошибка при копировании прав доступа: " . $e->getMessage() . "\n";
    }
}

// Или можно использовать GET/POST параметры для запуска через браузер:
if ($_GET['action'] === 'copy' && !empty($_GET["iblockType"]) && isset($_GET["iblockId"]) && !empty($_GET["selectIblock"]))
{
    $sourceIblocksId = $_GET['iblockId'];
    $iblockType = htmlspecialcharsbx($_GET["iblockType"]);
    $iblockApiCode = $_GET["apiCode"];
    $selectIblock = $_GET["selectIblock"];

    if(!empty($selectIblock)) {
        foreach ($sourceIblocksId as $key => $sourceIblockId) {
            if (!empty($iblockApiCode[$key]) && !empty($iblockType) && $sourceIblockId > 0) {
                echo "<pre>\n";
                // $result = copyInfoblock($sourceIblockId, $iblockType, $iblockApiCode[$key]);
                echo "</pre>\n";
                
                if ($result) {
                    echo "<p style='color: green;'>Инфоблок успешно скопирован! ID нового инфоблока: <strong>{$result}</strong></p>";
                } else {
                    echo "<p style='color: red;'>Ошибка при копировании инфоблока</p>";
                }
                
                echo "<hr>";
                echo "<p><a href='?'>Вернуться</a></p>";
            } else {
                echo "<p style='color: red;'>Неверные параметры</p>";
            }
        }
    }
} else {
    // Форма для запуска скрипта через браузер
    ?>
    <!DOCTYPE html>
    <html>
        <head>
            <meta charset="UTF-8">
            <title>Копирование инфоблока</title>
            <style>
                body { font-family: Arial, sans-serif; padding: 20px; max-width: 800px; margin: 0 auto; }
                .form-group { margin-bottom: 15px; }
                label { display: block; margin-bottom: 5px; font-weight: bold; }
                input[type="text"], input[type="number"] { width: 100%; padding: 8px; box-sizing: border-box; }
                button { background: #0066cc; color: white; padding: 10px 20px; border: none; cursor: pointer; }
                button:hover { background: #0052a3; }
                .info { background: #e3f2fd; padding: 15px; border-left: 4px solid #2196F3; margin-bottom: 20px; }
            </style>
        </head>
    <body>
        <?
        // IblockSiteTable::add();
        // echo '<pre>';print_r(IblockSiteTable::getList(['select' => ['*'], 'filter' => ['IBLOCK_ID' => 611]])->fetchAll());echo '</pre>';
        ?>
        <h1>Копирование инфоблока Битрикс</h1>

        <?
        // echo '<pre>';
        // print_r(\CUserOptions::GetOption('form', "form_element_614", 'list'));
        // echo '</pre>';

        // \CUserOptions::GetOption('form', "form_element_614", 'list');

        // \CUserOptions::SetOption("form", "form_element_681", \CUserOptions::GetOption('form', "form_element_614", 'list'), true);

        // echo '<pre>';
        // print_r(\CUserOptions::GetOption('form', "form_section_614", 'list'));
        // echo '</pre>';
        ?>
        
        <form method="GET">
            <input type="hidden" name="action" value="copy">

            <div class="" style="display: flex; gap:1rem">
                <div class="" style="display: flex; flex-direction:column; gap:1rem">
                    <div class="">Откуда</div>
                    <div class="form-group">
                        <?
                        $iblockType = Bitrix\Iblock\TypeTable::getList(['order' => ['ID' => 'ASC', 'SORT' => 'ASC'], 'select' => ['ID']])->fetchAll();
                        ?>
                        <select name="iblockType2" required>
                            <option <?=(empty($_GET["iblockType2"])) ? 'selected value=""' : ""?>>Не выбран</option>
                            <?foreach($iblockType as $iType):?>
                                <option <?=($_GET["iblockType2"] == $iType['ID']) ? "selected" : ""?> value="<?=$iType['ID']?>"><?=$iType['ID']?></option>
                            <?endforeach;?>
                        </select>
                    </div>

                    <?if(!empty($_GET["iblockType2"])):?>
                        <?if(!empty($_GET['iblockId'])):?>

                            <?foreach($_GET['iblockId'] as $key => $iblockId):?>
                                <?if(!empty($iblockId)):?>
                                    <div class="" style="border:1px solid #f5f5fa;padding:1rem;display: flex; flex-direction:column; gap:1rem">
                                        <div class="">
                                            <?
                                            $iblockIds = Bitrix\Iblock\IblockTable::getList([
                                                'order' => ['NAME' => 'ASC', 'SORT' => 'ASC'],
                                                'select' => ['ID', 'NAME'],
                                                'filter' => ['ACTIVE' => 'Y', 'IBLOCK_TYPE_ID' => $_GET["iblockType2"]],
                                            ])->fetchAll();
                                            ?>
                                            <select name="iblockId[<?=$iblockId?>]">
                                                <option <?=(empty($iblockId)) ? 'selected value=""' : ""?>>Не выбран</option>
                                                <?foreach($iblockIds as $iId):?>
                                                    <option <?=($iblockId == $iId['ID']) ? "selected" : ""?> value="<?=$iId['ID']?>"><?=$iId['NAME']?></option>
                                                <?endforeach;?>
                                            </select>
                                        </div>
                                        <div class="">
                                            <input type="text" name="apiCode[<?=$iblockId?>]" value="<?=$_GET['apiCode'][$key]?>" placeholder="Новый апи код инфоблока">
                                        </div>
                                    </div>
                                <?endif?>
                            <?endforeach?>

                            <div class="" style="border:1px solid #f5f5fa;padding:1rem;display: flex; flex-direction:column; gap:1rem">
                                <div class="">
                                    <?
                                    $iblockIds = Bitrix\Iblock\IblockTable::getList([
                                        'order' => ['NAME' => 'ASC', 'SORT' => 'ASC'],
                                        'select' => ['ID', 'NAME'],
                                        'filter' => ['ACTIVE' => 'Y', 'IBLOCK_TYPE_ID' => $_GET["iblockType2"]],
                                    ])->fetchAll();
                                    ?>
                                    <select name="iblockId[]">
                                        <option selected value="">Не выбран</option>
                                        <?foreach($iblockIds as $iId):?>
                                            <option value="<?=$iId['ID']?>"><?=$iId['NAME']?></option>
                                        <?endforeach;?>
                                    </select>
                                </div>
                                <div class="">
                                    <input type="text" name="apiCode[]" placeholder="Новый апи код инфоблока">
                                </div>
                            </div>

                        <?else:?>
                            <div class="" style="border:1px solid #f5f5fa;padding:1rem;display: flex; flex-direction:column; gap:1rem">
                                <div class="">
                                    <?
                                    $iblockIds = Bitrix\Iblock\IblockTable::getList([
                                        'order' => ['NAME' => 'ASC', 'SORT' => 'ASC'],
                                        'select' => ['ID', 'NAME'],
                                        'filter' => ['ACTIVE' => 'Y', 'IBLOCK_TYPE_ID' => $_GET["iblockType2"]],
                                    ])->fetchAll();
                                    ?>
                                    <select name="iblockId[]">
                                        <option selected value="">Не выбран</option>
                                        <?foreach($iblockIds as $iId):?>
                                            <option value="<?=$iId['ID']?>"><?=$iId['NAME']?></option>
                                        <?endforeach;?>
                                    </select>
                                </div>
                                <div class="">
                                    <input type="text" name="apiCode[]" placeholder="Новый апи код инфоблока">
                                </div>
                            </div>
                        <?endif;?>
                    <?endif;?>

                    <div class="form-group">
                        <label for="selectIblock">
                            <input id="selectIblock" type="checkbox" name="selectIblock">
                            Инфоблоки выбраны
                        </label>    
                    </div>
                </div>

                <div class="">
                    <div class="">Куда</div>
                    <div class="form-group">
                        <?
                        $iblockType = Bitrix\Iblock\TypeTable::getList(['order' => ['ID' => 'ASC', 'SORT' => 'ASC'], 'select' => ['ID']])->fetchAll();
                        ?>
                        <select name="iblockType" required>
                            <option <?=(empty($_GET["iblockType"])) ? "selected" : ""?>>Не выбран</option>
                            <?foreach($iblockType as $iType):?>
                                <option <?=($_GET["iblockType"] == $iType['ID']) ? "selected" : ""?> value="<?=$iType['ID']?>"><?=$iType['ID']?></option>
                            <?endforeach;?>
                        </select>
                    </div>
                </div>

            </div>
            
            <button type="submit">Копировать инфоблок</button>
        </form>
    </body>
    </html>
    <?php
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/epilog_after.php');
?>
