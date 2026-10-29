<?if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Engine\CurrentUser;

class LangLkDownloadFile extends CBitrixComponent
{
    private ?object $user;
    private ?string $documentRoot;

    public function __construct($component = null)
    {
        parent::__construct($component);
        Loc::loadMessages(__FILE__);
        
        $this->documentRoot = $_SERVER["DOCUMENT_ROOT"];

        $this->user = CurrentUser::get();
        $this->arResult['userId'] = $this->user->getId();
    }

    public function onPrepareComponentParams($arParam)
    {
        return $arParam;
    }

    private function getData()
    {
        $this->arResult['orderId'] = intval($this->request->getPost('orderId'));
        $this->arResult['id'] = intval($this->request->getPost('id'));

        if($this->arResult['id'] > 0) {
            $this->arResult['error'][] = "Нет такого файла";
        }
    }

    private function isError(): bool
    {
        if(!empty($this->arResult['error'])) {
            return true;
        }

        return false;
    }

    private function download()
    {
        $file = \CFile::GetFileArray($this->arResult['id']);

        if(!empty($file)) {
            $filePath = "{$this->documentRoot}{$file['SRC']}";

            header("X-Accel-Redirect: {$file["SRC"]}");
            header("Content-Type: application/force-download;");
            header("Content-Disposition: attachment; filename=\"{$file["FILE_NAME"]}\"");

            echo file_get_contents($filePath);
        } else {
            $this->arResult['error'][] = "Нет такого файла";
        }
    }

    public function executeComponent()
    {
        $this->getData();
        
        if(!$this->isError()) {
            $this->download();
        }
    }
}