<?
use Lang\Region;

$phone = Region::getCurrent(['select' => ['phone']])->getPhone();

if(!empty($phone)):?>
    <div class="d-n md:d-b">
        <?foreach($phone->getAll() as $value):?>
            <a class="ftw-5 f-14 h:tdl-u tdt-1 tuo-2 h:c-py tn-all-3 ws-nw" href="tel:<?=$value->getValue()?>"><?=$value->getDescription()?></a>
            <?break;?>
        <?endforeach;?>
    </div>
<?endif;?>