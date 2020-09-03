<?php
	include 'default.php';

	
	if ( empty($in['width_layout']) ) $in['width_layout'] = '600';
	if ( empty($in['width_video_on_whiteboard']) ) $in['width_video_on_whiteboard'] = '180';
	if ( empty($in['width_margin_on_whiteboard']) ) $in['width_margin_on_whiteboard'] = '4';

	$x_whiteboard = $in['width_video_on_whiteboard'] + $in['width_margin_on_whiteboard'];
	
	/**
	 *  -2 는 border
	 */
	$width_whiteboard = $in['width_layout'] - $in['width_video_on_whiteboard'] - $in['width_margin_on_whiteboard'] - 2;
	

	/** 언어 설정
	 *  
	 */
	load_language_pack('language');


	$http_vars = http_build_query( $in );

?><!doctype html>
<html>
<head>
<meta charset='utf-8'>
<link type='text/css' href='../simple.css' rel='stylesheet' />
<link type='text/css' href='iframe.css' rel='stylesheet' />
<style>
<? if ( is_ie() ) { ?>
#lobby-chat-box .send {
	padding: 8px 3px 0 3px;
	height: 20px;
}
<? } else { ?>
<? } ?>
<? if ( $in['width_layout'] ) { ?>
.video-chat-2 {
	width: <?=$in['width_layout']?>px;
}
<? } ?>

.on-whiteboard  #video-box, .on-whiteboard .chat-box, .on-whiteboard .chat-input {
	width: <?=$in['width_video_on_whiteboard']?>px;
}

<?=strip_tags($in['extra_css'])?>

</style>
<script type='text/javascript' src='../jquery-2.1.1.min.js'></script>
<? if ( is_window() ) { ?>
<!--script src="https://ontue.com/~videochat2/video-chat-server-2.0.3.js"></script-->
<script src="//work.ontue.com/video-chat-server-2/video-chat-server-2.0.3.js"></script>
<? } else { ?>
<script src="https://ontue.com/~videochat2/video-chat-server-2.0.3.js"></script>
<? } ?>
<script>
init_opt( 'url leave', 'iframe.php?<?=$http_vars?>' );
init_opt( 'lang input chat user name', '대화명을 입력하십시오.' );
init_opt( 'lang cannot enter lobby', '로비 ( 대기실 ) 에는 들어 갈 수 없습니다.' );
init_opt( 'lang guest', '손님' );
<? if ( $in['dont_change_user_name'] == 1 ) { /* 대화명을 변경하지 못하는 경우, 대화명이 입력되지 않았다면, 무조건 이름을 empty 로 만든다. */ ?>
	set_chat_user_name('<?=$in['user_name']?>' );
<? } else { /* 대화명을 바꿀 수 있는 경우, 입름이 입력되면 이름을 지정 */ ?>
	<? if ( ! empty($in['user_name']) ) { ?>
		set_chat_user_name('<?=$in['user_name']?>' );
	<? } ?>
<? } ?>

$(function(){
	<? if ( user_language() == 'ko' ) { ?>
		$("[name='message']").attr('placeholder', '채팅 메세지를 입력하세요.');
		$(".send").text("전송");
	<? } ?>
});

function callback_input_chat_user_name()
{
	$("[name='chat_user_name']").css('background-color', 'yellow').focus();
}

function callback_connect_success( my_rtc_id )
{
	$('#video-chat-room').append("<div id='room-message'><?=ln('waiting for someone')?></div>");
}

function callback_video_chat_start()
{
	video_chat_room_button_attach();
	video_chat_room_chat_box_attach();
	
	<? if ( user_language() == 'ko' ) { ?>
	$('#video-chat-room-button .camera').text('영상중지');
	$('#video-chat-room-button .sound').text('음소거');
	$('#video-chat-room-button .leave').text('퇴장');
	$('#video-chat-room-button .roomlist').text('방목록');
	$('#video-chat-room-button .whiteboard').text('전자칠판');
	$("[name='message']").attr('placeholder', '채팅 메세지를 입력하세요.');
	$(".send").text(" 전송 ");
	<? } ?>
}

function callback_create_video_element()
{
	var count_other = $('.slot.other').length;
	if ( count_other ) {
		$('#room-message').text( $chat_room_name ).addClass('room-message-room-name');
	}
}


function callback_whiteboard_open()
{
	whiteboard_rect( <?=$x_whiteboard?>, 26, <?=$width_whiteboard?>, 600 );
	$('#video-chat-room').addClass('on-whiteboard');
}

function callback_whiteboard_close()
{
	$('#video-chat-room').removeClass('on-whiteboard');
}


</script>
</head>
<body>
	<div class='video-chat-2'>

		<? if ( $in['dont_change_user_name'] != 1 ) { ?>
			<div id='video-chat-user-name'>
				<?=ln('user name')?> : <input type='text' name='chat_user_name' value=""> <span class='cbutton save-user-name'><?=ln('save')?></span>
			</div>
		<? } else {
			if ( empty($in['user_name']) ) {
				echo $in['message_no_user_name'];
			}
		}
		?>
		
			<div id='video-chat-enter-room'>
				<?=ln('room name')?> : <input type='text' name='chat_room_name' value="" placeholder="<?=ln('room name placeholder')?>">
				
				<input type='text' name='chat_room_user_list' value="" placeholder="<?=ln('room user list placeholder')?>">
				<input type='text' name='chat_room_max_no_of_user' value="" placeholder="<?=ln('room max user placeholder')?>">
				
				<span class='cbutton enter-room'><?=ln('enter room')?></span>
			</div>
			
			<div id='video-chat-room'></div>
			
			<div id='video-chat-room-list'>
				<div id='lobby-chat-box'></div>
				<div id='room-list'></div>
			</div><!--#video-chat-room-list-->
	</div>
</body>
</html>
