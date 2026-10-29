<?if(!defined ('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

// .statWork.php
use Lang\Css\Component as comp;
use Bitrix\Main\ORM\Query\Join;
use Bitrix\Sale\Order as BXOrder;
use Lang\Main\Iblock\Util as Data;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\ORM\Fields\Relations\Reference;

$lang = Data::getLang();
$langText = "LangText{$lang}";
$langService = "LangServiceOrder{$lang}";

$orderStat = BXOrder::getList([
    'select' => [
        'STATUS_ID',
        new ExpressionField('cn', 'COUNT(%s)', ['STATUS_ID']),
    ],
    'filter' => ['USER_ID' => $user->getId(), "STATUS_ID" => ["F", 'CN']],
    "group" => ["USER_ID", "STATUS_ID"],
    'cache' => Data::getCache(3600000)
])->fetchAll();

$workStat = Data::getTableString($langService)::query()
    ->addSelect('STATUS.STATUS_ID', 'STATUS_ID')
    ->addSelect('cn')
    ->where('ACTIVE', 'Y')
    ->where('uex.VALUE', $user->getId())
    ->addFilter('STATUS.STATUS_ID', ['RN', 'CP', 'F'])
    ->registerRuntimeField((new Reference('STATUS', BXOrder::class, Join::on('this.order.VALUE', 'ref.ID')))->configureJoinType(Join::TYPE_LEFT))
    ->registerRuntimeField(new ExpressionField('cn', 'COUNT(%s)', ['STATUS.STATUS_ID']))
    ->addGroup('uex.IBLOCK_GENERIC_VALUE')
    ->addGroup('STATUS.STATUS_ID')
    ->setCacheTtl(3600000)
    ->cacheJoins(true)
    ->exec()
    ->fetchAll();

$orderCount = [];
$workCount = [];

foreach($orderStat as $status) {
    $orderCount[$status['STATUS_ID']] = $status['cn'];
}

foreach($workStat as $statusWork) {
    $workCount[$statusWork['STATUS_ID']] = $statusWork['cn'];
}

$orderWorkList = [
    'order' => [
        'name' => 'Заказы',
        'item' => [
            ['name' => 'Выполнены', 'value' => $orderCount['F'] ?? 0],
            ['name' => 'Отменены', 'value' => $orderCount['CN'] ?? 0],
        ],
        'modifier' => [
            'col' => 'sm:w-6/12 lg:w-6/12',
        ],
    ],
    'work' => [
        'name' => 'Работы',
        'item' => [
            ['name' => 'На выполнении', 'value' => $workCount['RN'] ?? 0],
            ['name' => 'На проверке', 'value' => $workCount['CP'] ?? 0],
            ['name' => 'Выполнены', 'value' => $workCount['F'] ?? 0],
        ],
        'modifier' => [
            'col' => 'sm:w-6/12 lg:w-6/12',
        ],
    ],
    // 'click' => [
    //     'name' => 'Отклики',
    //     'value' => 5000,
    //     'item' => [
    //         ['name' => 'Выбран', 'value' => 100],
    //         ['name' => 'Не выбран', 'value' => 5],
    //     ]
    // ]
];

$orderTitle = Data::getElementObject($langText, ['NAME'], ['ID' => 260]);
?>

<div class="w-100 d-f fd-c rg-24 xl:rg-32">
    <?if(!empty($orderTitle)):?>
        <h2 class="mb-0 ff-ud f-16 sm:f-20 tt-l ftw-6" <?=Data::iblockElementEditAddId($orderTitle)?>><?=$orderTitle->getName()?></h2>
    <?endif;?>
    <div class="w-100">
        <div class="<?=comp::get("row")?> rg-32">
            <?foreach($orderWorkList as $order):?>
                <div class="w-100 <?=$order['modifier']['col']?> <?=comp::get("setcol")?>">
                    <div class="w-100 h-100 p-32 sm:p-24 md:p-32 d-f fd-c rg-24 r-12 bc-w">
                        <div class="ftw-6 fg-1 lh-1 d-f">
                            <div class="fg-1"><?=$order['name']?></div>
                            <?if(!empty($order['value'])):?>
                                <div class="ftw-6 bi-st bgc-t tf-t"><?=$order['value']?></div>
                            <?endif?>
                        </div>
                        <div class="w-100 d-f fd-c rg-8">
                            <?foreach($order['item'] as $item):?>
                                <div class="w-100 d-f cg-8">
                                    <div class="f-12 fg-1"><?=$item['name']?></div>
                                    <div class="f-14 md:f-16 ftw-6 bi-st bgc-t tf-t lh-1"><?=$item['value']?></div>
                                </div>
                            <?endforeach;?>
                        </div>
                    </div>
                </div>
            <?endforeach?>
        </div>
    </div>
</div>