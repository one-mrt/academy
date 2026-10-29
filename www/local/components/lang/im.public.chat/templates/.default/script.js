// $(document).on('ready', function(){
// 	$('#asd-chat-send').on('click', function(){
// 		$.get('/local/components/lang/im.public.chat/ajax.php', {message: $('#asd-chat-message').val()},
// 			function(){
// 				//
// 			});
// 		$('#asd-chat-message').val('');
// 	});
// });

BX.ready(function () {
	document.querySelector('#asd-chat-send').addEventListener('click', () => {
		BX.ajax.get(
			'/local/components/lang/im.public.chat/ajax.php',
			{
				message: document.querySelector('#asd-chat-message').value
			},
			() => {

			}
		);
		document.querySelector('#asd-chat-message').value = '';
	})
});

BX.addCustomEvent('onPullEvent-im', function(command, params) {
	if (command === 'messageChat') {
		doucument.querySelector('#asd-chat').innerHTML = (doucument.querySelector('#asd-chat').innerHTML + 'в <b>' + BX.date.format('d.m.Y H:i:s', params.MESSAGE.date) + '</b> ' + (params.MESSAGE.text) + '<br/>')
		// $('#asd-chat').html($('#asd-chat').html() + 'в <b>' + BX.date.format('d.m.Y H:i:s', params.MESSAGE.date) + '</b> ' + (params.MESSAGE.text) + '<br/>');
		// $('#asd-chat').animate({scrollTop: $('#asd-chat').prop('scrollHeight')}, 1000);
	}
});