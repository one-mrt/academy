<?
use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    'lang.academy',
    [
        "Lang\Academy\Util" => "lib/academy/util.php",
        "Lang\Academy\PropertyPaySystem" => "lib/academy/propertyPaySystem.php",
        "Lang\Academy\Messages" => "lib/academy/messages.php",
        "Lang\Academy\Rest" => "lib/academy/rest.php",
        "Lang\Academy\NotifyMessage" => "lib/academy/notifyMessage.php",
        "Lang\Academy\Notify" => "lib/academy/notify.php",
        "Lang\Academy\Rating" => "lib/academy/rating.php",
        "Lang\Academy\Offer" => "lib/academy/offer.php",
        "Lang\Academy\Order" => "lib/academy/order.php",
        "Lang\Academy\UsersChatProvider" => "lib/academy/usersChatProvider.php",
        "Lang\Academy\ImUsersFilter" => "lib/academy/imUsersFilter.php",
        "Lang\Academy\CodeEmail" => "lib/academy/codeEmail.php",
        "Lang\Academy\ReviewRating" => "lib/academy/reviewRating.php",
        "Lang\Academy\Agent\OfferStatus" => "lib/academy/agent/offerStatus.php",
    ]
);
?>