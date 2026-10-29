<?php if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED!==true)die();?>

<div id="asd-chat">
	<?foreach ($arResult['items'] as $item):?>
		в <b><?= $item['DATE_CREATE']?></b>
		от <b><?= htmlspecialcharsbx($item['AUTHOR_LOGIN'])?></b>
		<?= htmlspecialcharsbx($item['MESSAGE'])?>
		<br/>
	<?endforeach;?>
</div>

<input type="text" id="asd-chat-message" />
<input type="button" id="asd-chat-send" value="Отправить" />

