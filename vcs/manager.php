<?php
	include 'default.php';
	include 'html-head-top.php';
?>
<script>
$chat_user_name = "<?=$_COOKIE['chat_user_name']?>";
</script>
<?
	include 'html-head-video-chat-api.php';
	include 'html-head-bottom.php';
	include 'menu.php';
?>
You can TEST on video chat room management.<br>
You can do,<br>
Set name, set room name, room list, user list, enter room.<br>
TODO: Change name, change room name, set password, set owner, change owner, limit no of user<br>
Refer doc : <a href='https://docs.google.com/document/d/1KjTuSAdInlAsEC2kkbUn7mp-CTw7b22-VL7AE7pXFZE/edit#heading=h.qsoo2obbzm64' target='_blank'>https://docs.google.com/document/d/1KjTuSAdInlAsEC2kkbUn7mp-CTw7b22-VL7AE7pXFZE/edit#heading=h.qsoo2obbzm64</a>


	<form class='chat'>
		ID or Name: <input type='text' name='user_name' id='chat-user-name' value='<?=$_COOKIE['chat_user_name']?>' style='width:100px;'>
		
		<br>
		
		ROOM NAME : <input type='text' name='room_name' id='chat-room-name' value='' style='width:100px;'><br>
		
		
		<span class='chat-room-join'>[ Room Join of above ROOM ]</span>
		
		
		<span class='chat-room-join-lobby'>[ Leave the room and move to Lobby ]</span><br>
		<span class='chat-user-list-all'>[ User List ALL ]</span><br>
		
		<div class='chat-room-list'>[Room List]</div>
		
	</form>
	
	<div class='chat-display'></div>
	
	<div id='room-list'></div>

<?
	include 'footer.php';
?>