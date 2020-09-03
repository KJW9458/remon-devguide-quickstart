<?php
	include 'default.php';

	/** 언어 설정
	 *  
	 */
	load_language_pack('language');
?><!doctype html>
<html>
<head>
<meta charset='utf-8'>
<link type='text/css' href='responsive.css' rel='stylesheet' />
<style>
<? if ( is_ie() ) { ?>
#lobby-chat-box .send {
	padding: 8px 3px 0 3px;
	height: 20px;
}
<? } else { ?>
<? } ?>
.on-whiteboard  #video-box, .on-whiteboard .chat-box {
	width: 240px!important;
}
</style>
<script type='text/javascript' src='../jquery-2.1.1.min.js'></script>
<? if ( is_window() ) { ?>
<!--script src="https://ontue.com/~videochat2/video-chat-server-2.0.5.js"></script-->
<script src="//work.ontue.com/video-chat-server-2/video-chat-server-2.0.5.js"></script>
<? } else { ?>
<script src="https://ontue.com/~videochat2/video-chat-server-2.0.5.js"></script>
<? } ?>
<script>
init_opt( 'url leave', '<?=$_SERVER['PHP_SELF']?>?<?=$http_vars?>' );
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
	$('.video-chat').addClass('start');
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
	$video_chat_room.addClass('on-whiteboard');
	whiteboard_resize();
}

function callback_whiteboard_close()
{
	$video_chat_room.removeClass('on-whiteboard');
}


function callback_window_resized()
{
	if ( typeof $whiteboard == 'undefined' || $whiteboard == '' ) return;
	if ( ! $whiteboard.length ) return;
	whiteboard_resize();
}


</script>
</head>
<body>
	<div class='video-chat'>
		<? if ( $header ) include $header ?>
		<div class='video-chat-room'></div>
		<div class='video-chat-lobby'>
			<? if ( $lobby_top ) include $lobby_top ?>
			<div class='user-config'>
				<table width='100%' cellpadding='0' cellspacing='0' border='0'>
					<tr>
						<td nowrap><span class='text-user-name'><?=ln('user name')?></span></td>
						<td>:</td>
						<td width='99%'><input type='text' name='chat_user_name' value=""></td>
						<td nowrap><span class='cbutton save-user-name'><?=ln('save')?></span></td>
					</tr>
				</table>
			</div>
			<div class='room-config'>
				<? if ( $html_room_config ) include $html_room_config; else { ?>
					<?=ln('room name')?> :
					<input type='text' name='chat_room_name' value="" placeholder="<?=ln('room name placeholder')?>">
					<input type='text' name='chat_room_user_list' value="" placeholder="<?=ln('room user list placeholder')?>">
					<input type='text' name='chat_room_max_no_of_user' value="" placeholder="<?=ln('room max user placeholder')?>">
					<span class='cbutton enter-room'><?=ln('enter room')?></span>
				<? } ?>
			</div>
			<div class='chat-box-wrapper'><div class='chat-box'></div></div>
			<div class='room-list-wrapper'><div class='room-list'></div></div>
		</div>
	</div>
</body>
</html>
