<?
namespace Lang\Academy;

class PropertyPaySystem
{
    /**
     * Описание кастомного типа свойства инфоблока.
     */
    public static function GetUserTypeDescription(): array
    {
        return [
            'PROPERTY_TYPE'       => 'S',                 // базовый тип: строка
            'USER_TYPE'           => 'PaySystem',         // уникальный идентификатор
            'DESCRIPTION'         => 'Привязка к платёжной системе (sale)',
            'GetPropertyFieldHtml' => [__CLASS__, 'GetPropertyFieldHtml'],
            'GetAdminListViewHTML' => [__CLASS__, 'GetAdminListViewHTML'],
            'GetPublicViewHTML'    => [__CLASS__, 'GetPublicViewHTML'],
            'GetPublicEditHTML'    => [__CLASS__, 'GetPublicEditHTML'],
            'ConvertToDB'          => [__CLASS__, 'ConvertToDB'],
            'ConvertFromDB'        => [__CLASS__, 'ConvertFromDB'],
            'CheckFields'          => [__CLASS__, 'CheckFields'],
        ];
    }

    /**
     * Список активных платёжных систем модуля sale.
     */
    private static function getPaySystems(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $cache = [];
        if (\Bitrix\Main\Loader::includeModule('sale')) {
            $res = \Bitrix\Sale\PaySystem\Manager::getList([
                'select' => ['ID', 'NAME', 'CODE'],
                'filter' => ['=ACTIVE' => 'Y'],
                'order'  => ['SORT' => 'ASC', 'NAME' => 'ASC'],
            ]);
            while ($row = $res->fetch()) {
                $title = $row['NAME'] ?: $row['CODE'];
                $cache[$row['ID']] = '[' . $row['ID'] . '] ' . $title;
            }
        }

        return $cache;
    }

    /**
     * Форма редактирования в админке (и публичном редактировании).
     */
    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName): string
    {
        $options = self::getPaySystems();
        $current = (int)$value['VALUE'];

        $html  = '<select name="' . htmlspecialcharsbx($strHTMLControlName['VALUE']) . '">';
        $html .= '<option value="">— не выбрано —</option>';
        foreach ($options as $id => $title) {
            $selected = ($current === (int)$id) ? ' selected' : '';
            $html .= '<option value="' . $id . '"' . $selected . '>'
                . htmlspecialcharsbx($title) . '</option>';
        }
        $html .= '</select>';

        return $html;
    }

    public static function GetPublicEditHTML($arProperty, $value, $strHTMLControlName): string
    {
        return self::GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName);
    }

    /**
     * Отображение значения в списках админки.
     */
    public static function GetAdminListViewHTML($arProperty, $value, $strHTMLControlName): string
    {
        $id = (int)$value['VALUE'];
        return $id > 0
            ? htmlspecialcharsbx(self::getPaySystems()[$id] ?? ('PS #' . $id))
            : '';
    }

    /**
     * Отображение на публичной части.
     */
    public static function GetPublicViewHTML($arProperty, $value, $strHTMLControlName): string
    {
        return self::GetAdminListViewHTML($arProperty, $value, $strHTMLControlName);
    }

    /**
     * Перед сохранением: приводим к числу.
     */
    public static function ConvertToDB($arProperty, $value)
    {
        if ($value['VALUE'] !== '' && $value['VALUE'] !== null) {
            $value['VALUE'] = (int)$value['VALUE'];
        }
        return $value;
    }

    /**
     * После выборки из БД.
     */
    public static function ConvertFromDB($arProperty, $value)
    {
        return $value;
    }

    /**
     * Валидация: выбранная платёжная система должна существовать и быть активной.
     */
    public static function CheckFields($arProperty, $value): array
    {
        $errors = [];
        if ($value !== '' && $value !== null) {
            $id = (int)$value;
            if (!isset(self::getPaySystems()[$id])) {
                $errors[] = 'Выбранная платёжная система не найдена или отключена (ID: ' . $id . ')';
            }
        }
        return $errors;
    }
}