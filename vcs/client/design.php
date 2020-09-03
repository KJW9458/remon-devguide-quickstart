<?php
	include 'default.php';
?><!doctype html>
<html>
<head>
<meta charset='utf-8'>
<link type='text/css' href='../simple.css' rel='stylesheet' />
<link type='text/css' href='design.css' rel='stylesheet' />
<style>
<? if ( is_ie() ) { ?>
#lobby-chat-box .send {
	padding: 8px 3px 0 3px;
	height: 20px;
}
<? } else { ?>
#lobby-chat-box .send {
	padding: 8px 3px 0 3px;
	height: 22px;
}
<? } ?>
</style>
<script type='text/javascript' src='../jquery-2.1.1.min.js'></script>
<? if ( is_window() ) { ?>
<!--script src="https://ontue.com/~videochat2/video-chat-server-2.0.3.js"></script-->
<script src="//work.ontue.com/video-chat-server-2/video-chat-server-2.0.3.js"></script>
<? } else { ?>
<script src="https://ontue.com/~videochat2/video-chat-server-2.0.3.js"></script>
<? } ?>
<script>
init_opt( 'url leave', 'design.php' );
init_opt( 'lang input chat user name', '대화명을 입력하십시오.' );
$(function(){
	$("[name='message']").attr('placeholder', '채팅 메세지를 입력하세요.');
	$("#chat-input .send").text("전송");
});
function callback_input_chat_user_name()
{
	$("[name='chat_user_name']").css('background-color', 'yellow').focus();
}
function callback_connect_success( my_rtc_id )
{
	$('#video-chat-room').append("<div id='room-message'>누군가의 접속을 기다는 중 있습니다...</div>");
}
function callback_video_chat_start()
{
	video_chat_room_button_attach();
	video_chat_room_chat_box_attach();
	$('#video-chat-room-button .camera').text('영상중지');
	$('#video-chat-room-button .sound').text('음소거');
	$('#video-chat-room-button .leave').text('퇴장');
	$('#video-chat-room-button .roomlist').text('방목록');
	$('#video-chat-room-button .whiteboard').text('전자칠판');
	$("[name='message']").attr('placeholder', '채팅 메세지를 입력하세요.');
	$("#chat-input .send").text("전송");
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
	whiteboard_rect( 250, 26, 500, 600 );
	$('#video-chat-room').addClass('on-whiteboard');
}
function callback_whiteboard_close()
{
	$('#video-chat-room').removeClass('on-whiteboard');
}
</script>
</head>
<body>
	<div id='video-chat-2'>
			<div id='video-chat-user-name'>
				대화명 : <input type='text' name='chat_user_name' value=""> <span class='cbutton save-user-name'>저장</span>
			</div>
			<div id='video-chat-enter-room'>
				방개설 : <input type='text' name='chat_room_name' value="" placeholder="방 이름 입력">
				<input type='text' name='chat_room_user_list' value="" placeholder="접속 가능한 사용자 목록. 콤마로 분리.">
				<input type='text' name='chat_room_max_no_of_user' value="" placeholder="최대인원">
				<span class='cbutton enter-room'>방 입장</span>
			</div>
			<div id='video-chat-room'></div>
			<div id='video-chat-room-list'>
				<div id='lobby-chat-box'></div>
				<div id='room-list'></div>
			</div><!--#video-chat-room-list-->
	</div>
</body>
</html>
