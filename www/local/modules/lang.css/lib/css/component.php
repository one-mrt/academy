<?
namespace Lang\Css;

class Component {

    public function __construct()
    {
        
    }

    public static function includeComponents() : array
    {
        return include __DIR__ . "/component/component.php";
    }

    /**
     * Добавление модификаторов в компонент
     * 
     * @param string $component компонент
     * @param string $add модификаторы
     * @return string возвращает отредактированный компонент
     */
    public static function addModifierComponent(string $component, string $add) : string
    {
        $returnModifier = $component;

        if(empty($component) || empty($add))
        {
            return $returnModifier;
        }

        $modifierAdder = explode(' ', $add);
        $modifierComponent = explode(' ', $component);

        foreach($modifierAdder as $modAdd)
        {
            if(!empty($modAdd))
            {
                if(!in_array($modAdd, $modifierComponent))
                {
                    if(empty($returnModifier))
                    {
                        $returnModifier = "{$modAdd}";
                    }
                    else
                    {
                        $returnModifier .= " {$modAdd}";
                    }
                }
            }
        }

        return $returnModifier;
    }

    /**
     * Удаление модификаторов из компонента
     * 
     * @param string $component компонент
     * @param string $delete модификаторы
     * @return string возвращает отредактированный компонент
     */
    public static function deleteModifierComponent(string $component, string $delete) : string
    {
        $returnModifier = $component;

        if(empty($component) || empty($delete))
        {
            return $returnModifier;
        }

        $modifierDeleter = explode(' ', $delete);
        $modifierComponent = explode(' ', $component);

        $returnModifier = implode(" ", array_diff($modifierComponent, $modifierDeleter));

        return $returnModifier;
    }

    public static function get(string $component, ?string $add = "", ?string $delete = "") : string
    {
        $mod = "";
        $components = self::includeComponents();

        foreach($components as $comp => $m)
        {
            if($comp == $component)
            {
                $mod = self::deleteModifierComponent($m, $delete);
                $mod = self::addModifierComponent($mod, $add);
            }
        }

        return $mod;
    }
}