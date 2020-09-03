<div class='design-room-config-wrapper'>
<table width='100%' border='0' cellpadding='0' cellspacing='0'>
	<tr valign='top'>
		<td width='20%'><?=ln('room name')?></td>
		<td width='30%'><input type='text' name='chat_room_name' value="" placeholder="<?=ln('room name placeholder')?>"></td>
		<td width='50%'>
			Example of room name<br>
			I want to teach English<br>
			I want to learn English<br>
		</td>
	</tr>
	<tr>
		<td><?=ln('room user list')?></td>
		<td><input type='text' name='chat_room_user_list' value="" placeholder="<?=ln('room user list placeholder')?>"></td>
		<td>
			Input user names who can enter the room.
		</td>
	</tr>
	<tr>
		<td><?=ln('max no of user')?></td>
		<td><input type='text' name='chat_room_max_no_of_user' value="" placeholder="<?=ln('room max user placeholder')?>"></td>
	</tr>
	<tr>
		<td></td>
		<td><span class='cbutton enter-room'><?=ln('enter room')?></span></td>
	</tr>
</table>
</div>