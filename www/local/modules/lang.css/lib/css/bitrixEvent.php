<?
namespace Lang\Css;

use Bitrix\Main\Application;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class BitrixEvent {

    public static function addBtnUpdateStyle()
    {
        global $APPLICATION;

        $link = $APPLICATION->GetCurPageParam("langUpdate=y", ["langUpdate"]);

        $APPLICATION->AddPanelButton([
            "TEXT"      => "Обновить стили",
            "HREF"      => $link,
            "SRC"       => "/local/templates/lang.css/img/panel.icon.svg",
            "ALT"       => "Обновить стили lang.css", 
            "MAIN_SORT" => 300, 
            "SORT"      => 10
        ]);
    }
}