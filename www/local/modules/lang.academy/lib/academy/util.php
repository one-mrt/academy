<?
namespace Lang\Academy;

use Bitrix\Sale\Order;
use Bitrix\Main\Loader;
use Bitrix\Main\UserTable;
use Bitrix\Sale\PaySystem;
use Bitrix\Catalog\Model\Price;
use Bitrix\Main\Localization\Loc;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\Engine\CurrentUser;
use Bitrix\Main\Engine\Response\Redirect;
use Bitrix\Sale\Internals\StatusLangTable;
use Bitrix\Pull\Event as PullEvent;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Main\ORM\Query\Query;
use Bitrix\Main\ORM\Fields\Relations\Reference;

Loc::loadMessages(__FILE__);


Loader::includeModule('lang.iblock');

class Util {

    private static string $langText = "LangText";

    public static function langWriteLog(int|string|array $data, $title = 'logs', $file = __DIR__."/app.log") : bool
    {
        $log = "\n------------------------\n";
        $log .= date("Y.m.d G:i:s") . "\n";
        $log .= (strlen($title) > 0 ? $title : 'DEBUG') . "\n";
        $log .= print_r($data, 1);
        $log .= "\n------------------------\n";
        
        file_put_contents($file, $log, FILE_APPEND);

        return true;
    }

    public static function getParser(): object
    {
        $parser = new \CTextParser();
        $parser->allow = array(
            "HTML" => "N",
            "ANCHOR" => "Y",  // ссылки
            "BIU" => "Y",    // жирный, курсив, подчеркнутый
            // "IMG" => "Y",   // изображения
            // "QUOTE" => "Y", // цитаты
            // "CODE" => "Y",   // блоки кода
            // "FONT" => "Y",   // шрифты
            "LIST" => "Y",   // списки
            // "SMILES" => "Y", // смайлики
            // "NL2BR" => "Y", // перенос строк
            // "VIDEO" => "Y", // видео
            // "TABLE" => "Y", // таблицы
            "CUT_ANCHOR" => "N", // запрет на вставку закрывающего тега ссылки
            // "ALIGN" => "Y"  // выравнивание
        );

        return $parser;
    }

    public static function getAcceptFile(): array
    {
        $fileType = [];

        $fileTypeList = Data::getElementsObject(self::$langText, [
            'select' => ['text.VALUE'],
            'filter' => ['IBLOCK_SECTION_ID' => 169]
        ]);

        if(!$fileTypeList->isEmpty()) {
            foreach($fileTypeList as $iFile) {
                foreach($iFile->getText()?->getAll() as $type) {
                    $fileType[] = $type->getValue();
                    break;
                }
            }
        }

        // return [
        //     'image/png',
        //     'image/jpeg',
        //     'application/pdf',
        //     'application/msword',
        //     'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        //     'application/vnd.ms-excel',
        //     'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        //     'application/vnd.ms-powerpoint',
        //     'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        //     'application/zip',
        //     'application/x-zip-compressed',
        //     'application/x-rar-compressed',
        // ];
        return $fileType;
    }

    public static function getAcceptFileIcon(): array
    {
        $iconFile = [];
        $fileTypeIcon = Data::getElementsObject(self::$langText, [
            'select' => ['NAME', 'text.VALUE', 'svg.VALUE'],
            'filter' => ['IBLOCK_SECTION_ID' => 169]
        ]);

        if(!$fileTypeIcon->isEmpty()) {
            foreach($fileTypeIcon as $iFile) {
                $fileType = "";

                foreach($iFile->getText()?->getAll() as $type) {
                    $fileType = $type->getValue();
                    break;
                }

                $iconFile[$fileType] = file_get_contents($_SERVER['DOCUMENT_ROOT'] . \CFile::GetPath($iFile->getSvg()?->getValue()));
            }
        }

        return $iconFile;
    }

    /**
     * Обновить статус пользователя
     * */
    public static function setUserActiveOnline()
    {
        $user = CurrentUser::get();

        if(!empty($user->getId())) {
            \CUser::SetLastActivityDate($user->getId());
        }
    }

    public static function getUserFullName(?int $userId = null, ?object $user = null): string
    {
        $userName = "";
        
        if(!empty($userId) || !empty($user)) {
            if(empty($user)) {
                $user = UserTable::getList([
                    'select' => ['ID', 'NAME', 'LAST_NAME', 'LOGIN'],
                    'filter' => ['ID' => $userId],
                    'cache' => Data::getCache(),
                ])->fetchObject();
            }

            if(!empty($user)) {
                if(!empty($user->getName())) {
                    $userName = "{$user->getName()} ";
                }

                if(!empty($user->getLastName())) {
                    $userName .= "{$user->getLastName()}";
                }

                if(empty($userName)) {
                    $userName = $user->getLogin();
                }
            }
        }

        return $userName;
    }

    public static function getBtnAuthLink(string $name = "", array $param = []): string
    {
        $user = CurrentUser::get();
        $stringParam = "";

        foreach($param as $attribute => $value) {
            $stringParam .= " {$attribute}=\"{$value}\"";
        }

        if(!empty($user->getId())) {
            $a = "<a {$stringParam}>{$name}</a>";

            return $a;
        } else {
            $btn = "<button {$stringParam}>{$name}</button>";

            return $btn;
        }
    }

    public static function onSendNotifyResponsible($module, $tags, $value, $arRes, $resultMessages) {
        $tag = explode('|', $tags);

        if(!empty($tag)) {
            if($tag[0] == 'order') {
                $paramsOrder = [
                    'type' => $tag[0],
                    'action' => $tag[1],
                    'orderId' => intval($tag[2]),
                    'fromUser' => intval($tag[3]),
                    'toUser' => intval($tag[4]),
                    'offerId' => intval($tag[5]),
                    'serviceId' => intval($tag[6]),
                    'value' => $value
                ];

                new Offer($paramsOrder);
            }
        }
    }
}

?>