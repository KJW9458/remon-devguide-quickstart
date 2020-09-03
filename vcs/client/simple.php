<!doctype html>
<html>
<head>
<link type='text/css' href='../simple.css' rel='stylesheet' />
<script type='text/javascript' src='../jquery-2.1.1.min.js'></script>
<script src="https://ontue.com/~videochat2/video-chat-server-2.0.3.js"></script>
<!--script src="//work.org/video-chat-server-2/video-chat-server-2.0.3.js"></script-->
<script>
$(function(){
	video_chat_start( "<?=$_GET['username']?>", "Test Room Name" );
});
function callback_video_chat_start()
{
	video_chat_room_button_attach();
	video_chat_room_chat_box_attach();
}
</script>
</head>
<body>
<div id='video-chat-room'></div>
</body>
</html>
