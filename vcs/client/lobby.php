<!doctype html>
<html>
<head>
<link type='text/css' href='../simple.css' rel='stylesheet' />
<link type='text/css' href='lobby.css' rel='stylesheet' />
<script type='text/javascript' src='../jquery-2.1.1.min.js'></script>
<script src="https://ontue.com/~videochat2/video-chat-server-2.0.3.js"></script>
<!--script src="//work.org/video-chat-server-2/video-chat-server-2.0.3.js"></script-->
<script>
function callback_connect_success( my_rtc_id )
{
	$('body').append("<div id='waiting-for-someone'>Waiting for someone enter ...</div>");
}

function callback_video_chat_start()
{
	video_chat_room_button_attach();
	video_chat_room_chat_box_attach();
}

function callback_create_video_element()
{
	var count_other = $('.slot.other').length;
	if ( count_other ) {
		if ( $('#waiting-for-someone').length ) $('#waiting-for-someone').remove();
	}
}


function callback_whiteboard_open()
{
	whiteboard_rect( 260, 30, 600, 800 );
	$('#video-chat-room').addClass('on-whiteboard');
}

function callback_whiteboard_close()
{
	$('#video-chat-room').removeClass('on-whiteboard');
}


</script>
</head>
<body>

<div id='video-chat-user-name'>
User Name : <input type='text' name='chat_user_name' value=""> <span class='cbutton save-user-name'>Save My Name</span>
</div>
<div id='video-chat-enter-room'>

Room : <input type='text' name='chat_room_name' value="" placeholder="Room Name">
<input type='text' name='chat_room_user_list' value="" placeholder="User List">
<input type='text' name='chat_room_max_no_of_user' value="" placeholder="Max No of User" style='width:100px;'>
<span class='cbutton enter-room'>Join ROOM</span>
</div>

<div id='video-chat-room'></div>
<div style='clear:both;'></div>


<div id='video-chat-room-list'>
<div id='lobby-chat-box'></div>
<div id='room-list'></div>
</div>


</body>
</html>


