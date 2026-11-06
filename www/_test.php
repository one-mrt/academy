<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Главная");


use Bitrix\Im\Chat;
use Bitrix\Main\Loader;
use Bitrix\Im\V2\Chat as v2Chat;
use Bitrix\Main\UserTable;
use Bitrix\Sale\Order as bxOrder;
use Bitrix\Sale\OrderBase;
use Lang\Css\Component as comp;
use Lang\Main\Iblock\Util as Data;
use \Bitrix\Im\Bot\Keyboard;

use Lang\Academy\Order;

Loader::includeModule('im');
Loader::includeModule('sale');
Loader::includeModule('lang.academy');

use Bitrix\Main\Engine\CurrentUser;
// CurrentUser::get()->getId();

// if(intval(CurrentUser::get()->getId()) > 0) {
//     \CPullWatch::Add(CurrentUser::get()->getId(), 'LangOrderOfferUpdate');
// }

// echo '<pre>', print_r(\CPullWatch::getUsersByTag("LangOrderOfferUpdate"), 1), '</pre>';

// Order::reSelectAnswer(922);

// echo '<pre>', print_r(\Bitrix\Im\Counter::get(), 1), '</pre>';


use Bitrix\UI\EntitySelector\Configuration;


// $extensions = Configuration::getExtensions();

// foreach (\Bitrix\Main\ModuleManager::getInstalledModules() as $moduleId => $moduleDesc)
// {
//     $settings = \Bitrix\Main\Config\Configuration::getInstance($moduleId)->get('ui.entity-selector');
//     echo $moduleId . '<br>';
//     echo '<pre>', print_r($settings, 1), '</pre>';
//     // if (empty($settings) || !is_array($settings))
//     // {
//     //     continue;
//     // }
// }

// $settings = \Bitrix\Main\Config\Configuration::getInstance("im")->get('ui.entity-selector');

// echo '<pre>', print_r($settings, 1), '</pre>';

// \CIMSettings::SetSetting(\CIMSettings::SETTINGS, [
//         'sendByEnter' => 'Y',
//         'privacyMessage' => 'all',
//         'privacyCall' => 'all',
//         'privacyChat' => 'all',
//         'privacySearch' => 'all',
//         'enableDarkTheme' => 'light',
//         'notifyScheme' => 'expert',
//         'notifyAutoRead' => false,
//         'viewBirthday' => false,
//         'notifySchemeSendSite' => true,
//         'notifySchemeSendEmail' => true
//     ], 10);

$toUser = 1;
$fromUser = 7;
$orderId = 110;
$serviceId = 810;
$orderCode = '110-privatnyy-zakaz-003006';

// Удалить заказ и связанные элементы заказы чаты
// Data::getTableString("LangServiceOrder")::query()
//     ->setSelect(["ID", 'order.VALUE', 'chat.VALUE'])->fetchCollection()
//     ->walk(static function($service, $key) use (&$processedKeys) {

//         if(!empty($service?->getOrder()?->getValue())) {
//             Order::delete($service->getOrder()->getValue());
//         }

//         if(!empty($service?->getChat()?->getValue())) {
//             CIMChat::deleteChat(['ID' => $service->getChat()->getValue()]);
//         }

//         Data::getTableString("LangOrderOffer")::query()
//             ->setSelect(['ID'])
//             ->where('ofr.VALUE', $service->getId())
//             ->fetchCollection()
//             ->walk(static function($offer, $k) use (&$processedK) {
//                 $offer->delete();
//             });

//         $service->delete();
        
//     });



$keyboard = new Keyboard(111);

$command = 'notifyConfirm';
$keyboard->addButton(
    [
        'TEXT' => 'Открыть заказ',
        // 'COMMAND' => $command,
        // 'COMMAND_PARAMS' => $notifyItem['ID'].'|'.$button['VALUE'],
        'LINK' => "/order/{$orderCode}/",
        'TEXT_COLOR' => '#fff',
        'BG_COLOR' => '#8BC84B', // $button['TYPE'] === 'accept' ? '#8BC84B' : '#ef4b57',
        'DISPLAY' => 'LINE'
    ]
);
$keyboard->addButton(
    [
        'TEXT' => 'Отказаться',
        'COMMAND' => $command,
        'COMMAND_PARAMS' => "614|Y",
        // 'LINK' => "/order/{$orderCode}/",
        'TEXT_COLOR' => '#fff',
        'BG_COLOR' => '#ef4b57', // $button['TYPE'] === 'accept' ? '#8BC84B' : '#ef4b57',
        'DISPLAY' => 'LINE',
        "BLOCK" => "Y",
        "OFF_BG_COLOR" => "#8c94a8",
        "OFF_TEXT_COLOR" => "#000",
    ]
);

$message = [
    "TO_USER_ID" => $toUser, // Кому
    "FROM_USER_ID" => $fromUser, // Кто
    "MESSAGE_TYPE" => IM_MESSAGE_SYSTEM,
    "NOTIFY_TYPE" => IM_NOTIFY_SYSTEM,
    "NOTIFY_MODULE" => "im",
    "NOTIFY_TAG" => "order|private|{$orderId}|{$fromUser}|{$toUser}|0|{$serviceId}|".rand(),
    "NOTIFY_MESSAGE" => "Вам поступил личный заказ [url=/order/{$orderCode}/]№{$orderId}[/url]",
    "NOTIFY_MESSAGE_OUT" => "",
    "NOTIFY_LINK" => "/order/{$orderCode}/",
    // "NOTIFY_BUTTONS" => [
    //     [
    //         'TITLE' => 'Просмотр',
    //         'VALUE' => 'link',
    //         'TYPE' => 'accept',
    //         'URL' => "https://work.acm.tw1.su/order/{$orderCode}/?CONFIRM=Y",
    //         'LINK' => "https://work.acm.tw1.su/order/{$orderCode}/?CONFIRM=Y"
    //     ],
    //     ['TITLE' => 'Отказаться', 'VALUE' => 'N', 'TYPE' => 'cancel']
    // ],
    "KEYBOARD" => $keyboard
];

// \CIMNotify::Add($message);
// \CIMMessage::Add(array(  
//     'FROM_USER_ID' => $fromUser,  
//     'TO_USER_ID' => $toUser, 
//     'MESSAGE' => 'Пишу в чат тебе', 
//     'PUSH' => 'Y',
//     "KEYBOARD" => $keyboard,
// ));
?>

<section class="w-100">
    <div class="<?=comp::get("container-full")?>" id="application">
        <input type="text" value="" name="text" class="form-order--text">
    </div>
    <div class="<?=comp::get("container-full")?>">
        <div id="mess1"></div>
    </div>
</section>

<style>
    .bx-im-next-layout-dialog-messages > div {
        position: relative;
    }
</style>

<script>
    // BX.Runtime.loadExtension('ui.avatar-editor').then((exports) => {
    //     console.info(exports)
    // })
    // BX.Vue3.BitrixVue.createApp({
    //     // опции компонента
    //     template: `<bx-im-component-dialog
	// 					:userId="1"
	// 					:dialogId="chat41"
	// 					:showMessageUserName="false"
	// 					:showMessageAvatar="false"
	// 				 />`
    // }).mount('#application');

    // BX.Runtime.loadExtension('im.application.notifications').then((exports) => {
    //     console.log(exports);
    //     // window.BXIM = window.BXIM || BX.IM;
    //     // var messenger = exports.Launch('messenger', {node: '#mess1'})
    //     //     .then(application => console.log('Application Sidebar v2 launched', application));

    //     // messenger

    //     // BX.ready(function() {
    //     //     var messenger = exports.Launch('notifications', {node: '#mess1'})
    //     //         .then(application => console.log('Application Sidebar v2 launched', application));
    //     // })

    //     // BX.ready(function() {
	// 	// 	BXIM = new BX.IM(BX('mess1'), {
    //     //         init: true
    //     //     })
    //     // })
    // });

    // BX.Runtime.loadExtension('ui.text-editor').then((exports) => {
    //     // console.log(exports);
    //     var edit = exports.TextEditor;
    //     // console.info(new exports.TextEditor())

    //     var editor = new exports.TextEditor(
    //         {
    //             editable: true,
    //             contentType: 'html',
    //             content: "[p]Тест[/p]",
    //             newLineMode: 'paragraph',
    //             events:  {
    //                 onUpdate: (editor) => {
    //                     console.info('update')
    //                 },
    //                 onChange: (event) => {

    //                     document.querySelector('.form-order--text').value = event.target.getText()
    //                     // console.info(event.target.getRootContainer().getChildren())
    //                     // BX.UI.Lexical.Html
    //                     // console.info(event.target.getLexicalEditor())

    //                     console.info(event.target.getText().replaceAll('[', '<').replaceAll(']', '>'))
    //                     event.target.getLexicalEditor().getEditorState().read(() => {
                            
    //                         // console.info(event.target.getLexicalEditor().$getSelection())
    //                         // console.info(BX.UI.Lexical.Core.$getSelection())
    //                         // console.info(BX.UI.Lexical.Html.$generateHtmlFromNodes(event.target.getLexicalEditor(), BX.UI.Lexical.Core.$getSelection()))
    //                     })
    //                 }
    //             },
    //             toolbar: [
    //                 // "heading",
    //                 // "paragraph",
    //                 "bold",
    //                 "italic",
    //                 "underline",
    //                 // "strike",
    //                 // "foreColor",
    //                 "code",
    //                 "link",
    //                 "numbered-list",
    //                 "bulleted-list",
    //                 // "RemoveFormat",
    //                 // "InsertOrderedList",
    //                 // "InsertUnorderedList",
    //                 // "SmileList",
    //                 // "Source",
    //                 // "separator",
	// 	            "quote",
    //                 // "spoiler",
    //                 "table",
    //                 // "image"
    //                 // "file",
    //             ],
    //             floatingToolbar: [
    //                 "bold",
    //                 "italic",
    //                 "underline",
    //                 "code",
    //                 "link",
    //                 "numbered-list",
    //                 "bulleted-list",
	// 	            "quote",
    //                 // "spoiler",
    //                 "table",
    //             ],
    //             hideToolbar: true,
    //             minHeight: 500,
    //             placeholder: 'Описание',
    //             plugins: edit.getBuiltinPlugins()
    //         }
    //     )

    //     const app = document.getElementById('application');
    //     // editor.renderTo(app)
    //     // app.append(editor.renderTo());
    //     // console.info(editor.getText())
    //     // console.info(editor.getOptions())
        

    //     editor.getLexicalEditor().getEditorState().read(() => {

    //         // console.info()
	// 		// const bbCodeAst = $exportToBBCode($getRoot(), this);

	// 		// console.log("bbCodeAst", bbCodeAst);

    //         // console.info(editor.getBBCodeScheme().encoder)

	// 		// return bbCodeAst.toString();
	// 	});
    // });

    // BX.Runtime.loadExtension('langmess.messenger').then((exports) => {
    //     // console.info(exports);

    //     var chat = new exports.MessengerApplication({node: "#mess", dialogId: "chat41"});
    //     // chat.setInitialApplicationInfo()

    //     // chat.setApplicationDialogInfo()
    //     // console.info(chat.getDialogId())

    //     // var chat = new exports.ChatService()

    //     // console.info(chat.loadChat("chat41"))
    // });

    // im.component.dialog

    // BX.Runtime.loadExtension('im.component.dialog').then((exports) => {
    //     console.info(exports);

    //     // var chat = new exports.MessengerApplication({node: "#mess", userId: 1, dialogId: "chat41"});

    //     // chat.setApplicationDialogInfo()
    //     // console.info(chat.getDialogId())

    //     // var chat = new exports.ChatService()

    //     // console.info(chat.loadChat("chat41"))
    // });

    // BX.IM.openMessengerSlider("chat41")
</script>

<script>
    window.onload = () => {
    // BX.Messenger.Application.Launch('messenger', {node: '#mess', dialogId: "1", userId: 1, isMessenger: true})
    //         .then(application => console.log('Application Sidebar v2 launched', application))
    //     ;
}
</script>


<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>