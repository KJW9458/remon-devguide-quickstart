
	
/** defines */
var NO_ROOM_NAME = -1001;
var NO_USER_NAME = -1002;
var NO_VIDEO_SLOT_AVAILABLE = -1003;



/** define variables */
var chat_socket;
var chat_my_room;
var trace_count = 0;
var $connected = null;			// connected users in the room
var update_room_list = false;

var $chat_user_name = false;
var $chat_room_name = false;
var $chat_observer = false;
var $chat_whiteboard = false;
var timer_resize = -1;
/** initialize codes */

/** loading scripts */
var url_video_chat_server = "https://ontue.com:8443";
document.write("<script type='text/javascript' src='"+url_video_chat_server+"/easyrtc/easyrtc.js'></script>");
document.write("<script type='text/javascript' src='"+url_video_chat_server+"/socket.io/socket.io.js'></script>");



$(function(){

	/** 
	 *  Initailzation condition
	 *  
	 */
	// get user name check if
	var user_name = $.cookie('chat_user_name');
	if ( user_name != '' ) {
		$chat_user_name = user_name;
		$("[name='chat_user_name']").val( $chat_user_name );
	}
	
	// get check room name if
	var room_name = $.cookie('chat_room_name');
	if ( room_name != '' ) $chat_room_name = room_name;
	
	
	if ( typeof $chat_user_name == 'undefined' ) {
		if ( $chat_user_name == '' ) {
			alert("Error: $chat_user_name must have value");
			return;
		}
	}
	else {
		if ( $chat_user_name === false ) {
			trace("$chat_user_name is false");
		}
	}
	
	/** windows init */
	
	$(window).resize(function(){
		clearTimeout(timer_resize);
		timer_resize = setTimeout(function(){
			alert('50');
			if ( typeof callback_window_resized == 'function' ) callback_window_resized();
		}, 500);
	});

	/** easyrtc initialization */
	easyrtc.setOnError( function( e ) { trace( 'easyrtc.setOnError() : ' + e.errorText); } );
	easyrtc.setSocketUrl( url_video_chat_server );
	easyrtc.setStreamAcceptor( streamAcceptor );
	easyrtc.setOnStreamClosed( streamClosed );
	easyrtc.setAcceptChecker( streamChecker );
	

	
		try {
			chat_socket = io.connect( url_video_chat_server );
		}
		catch ( e ) {
			alert ( e.message );
		}
		
		chat_socket.on('chat', chat_recv);
		chat_socket.on('connect', socket_connect);
		chat_socket.on('connecting', socket_connecting);
		chat_socket.on('disconnect', socket_disconnect);
		chat_socket.on('connect_failed', socket_connect_failed);
		chat_socket.on('error', socket_error);
		chat_socket.on('reconnect_failed', socket_reconnect_failed);
		chat_socket.on('reconnect', sooket_reconnect);
		chat_socket.on('reconnecting', socket_reconnecting);
		
		
	
	/** chat room management */
	$('body').on('click', '.room-name', on_room_name);
	$('body').on('click', '.enter-room', on_enter_room);
	$('.save-user-name').click( on_save_user_name );
	
	
	
	/** chat management test
	 *  
	 */
	//$('.chat-enter').click( function(){ chat_enter(); } );
	$('.chat-room-join').click( chat_room_join );
	$('.chat-room-join-lobby').click( chat_room_join_lobby );
	$('.chat-room-list').click( chat_room_list );
	$('.chat-user-list').click( chat_user_list );
	$('.chat-user-list-all').click( chat_user_list_all );
	
	

	
	/**
	 *  @warning this code must be at the end of init.
	 */
	if ( $chat_user_name && $chat_room_name ) {
		setTimeout( function() { enter_room(); }, 100 );
	}
});

function on_room_name()
{
	if ( $chat_room_name ) return alert("Please leave the room - " + $chat_room_name);
	if ( $chat_user_name == '' ) return alert("Please input name");
	var room_name = $(this).text();
	$chat_room_name = room_name;
	enter_room();
}
function on_enter_room()
{
	if ( $chat_room_name ) return alert("Please leave the room - " + $chat_room_name);
	if ( $chat_user_name == '' ) return alert("Please input name");
	var room_name = $("[name='chat_room_name']").val();
	if ( room_name == '' ) return alert("Input room name");
	$chat_room_name = room_name;
	enter_room();
}
function enter_room()
{
	$("#video-chat-user-name").hide();
	$('#video-chat-enter-room').hide();
	$('#vide-chat-room-list').hide();
	video_chat_start( $chat_user_name, $chat_room_name, false, false );
}

function on_save_user_name()
{
	trace("save-user-name()");
	var name = $("[name='chat_user_name']").val();
	if ( name == '' ) return alert("Please input user name");
	$.cookie('chat_user_name', name);
	$chat_user_name = name;
	trace("$.cookie('chat_user_name', "+name+");");
}


/**
 *  	VIDEO CHAT starter funcition
 */
function video_chat_start( user_name, room_name, observer, whiteboard ) {
	
	$chat_room_name = room_name;
	$chat_observer = observer;
	$chat_whiteboard = whiteboard;
	
	
	easyrtc.setRoomOccupantListener( roomListener );
	easyrtc.setUsername( $chat_user_name );
	easyrtc.setPeerListener( peerListener );	// for easyrtc data & chat stream ( chat message and whiteboard message )
	
	create_self_video_element( );
	
	// @todo observer
	
	
	trace("Initializing... room-name:" + $chat_room_name + ", user:" + $chat_user_name );
	
	
	
	easyrtc.setVideoDims( 320, 240 ); // pixels. This is video resolution.
	
	
	easyrtc.initMediaSource( initMediaSuccess, initMediaFailure );
	
}
function initMediaSuccess() {
		trace("initMediaSuccess...");
		easyrtc.joinRoom( $chat_room_name ); //added join room
		easyrtc.connect( "DefaultApplicationName", connectSuccess, initMediaFailure );
	}
/** 
 *   when your own mediea set
 */
function connectSuccess( caller_rtc_id) {


	trace("connectSuccess() : Entering room only for myself : ["+ $chat_room_name +"] rtc_id='"+ caller_rtc_id +"'...");	
	
	chat_room_join();
	
	
	if ( $("video[rtc-id='" + caller_rtc_id + "']").length ) {//if self video already exists.
		var selfVideo = $("video[rtc-id='"+caller_rtc_id+"']")[0];//to avoid self video duplication on server connection lost.
	}
	else {
		var selfVideo = create_video_element( caller_rtc_id );
	}
	
	easyrtc.setVideoObjectSrc(selfVideo, easyrtc.getLocalStream() );
	
	selfVideo.muted = true; // @todo check 'muted' attribute is added in DOM.
	
	/**
	 *  after join the video chat room
	 */
	$.cookie('chat_room_name', $chat_room_name);
	if ( typeof callback_video_chat_room_entered == 'function' ) callback_video_chat_room_entered( caller_rtc_id, rtc_name( caller_rtc_id ) );
}

/**
 *
 * checks if the there is any available video element ( not occupid yet or closed by other user. )
 *
 * @attention before it returns the element, it marks as occupied.
 * @return null if there is no empty slot. Need to do error process.
 * 			or a video element.
 */
function create_video_element( caller_rtc_id )
{
	trace("Creating a video for " + rtc_name( caller_rtc_id ) );
	$("#video-box").append("<div class='slot'><video rtc_id='"+caller_rtc_id+"' rtc_name='"+rtc_name(caller_rtc_id)+"'></video><div class='video-button'><span class='name'>"+rtc_name(caller_rtc_id)+"</span><span class='cbutton big'>Big</span><span class='cbutton kickout'>Kickout</span></div></div>");
	return $("#video-box video[rtc_id='"+caller_rtc_id+"']")[0];
}

function rtc_name ( caller_rtc_id )
{
	return easyrtc.idToName( caller_rtc_id );
}




function initMediaFailure( errmsg ) {
	alert("initMediaFailure");
	alert( errmsg );
}

/**
 *  easyrtc data stream. 
 *  
 *  For chat message and whiteboard message.
 */
function peerListener( caller_rtc_id, data )
{
	trace("peerListener( " + caller_rtc_id + " )  : " );
	data = JSON.parse( data );
	trace_object( data );
	
	if ( data.action == 'chat' ) {
		video_chat_message_recv( caller_rtc_id, data );
	}
}

function create_self_video_element( )
{	
	$('#video-chat-room').html(
		"<div id='video-chat-room-button'>"+
			"<span class='camera cbutton on'>Camera</span>" +
			"<span class='sound cbutton on'>Mute</span>" +
			"<span class='leave cbutton'>Leave</span>" +
			"<span class='password cbutton '>Password</span>" +
			"<span class='room-list cbutton '>Room List</span>" +
			"<span class='layout1 cbutton '>Layout1</span>" +
			"<span class='layout2 cbutton '>Layout2</span>" +
			"<span class='whiteboard cbutton '>Whiteboard</span>" +
		"</div>" +
		"<div id='video-box'></div>" +
		"<div id='chat-box'>" +
			"<div id='chat-message'></div>" +
			"<div id='chat-input'><input name='message' placeholder=' Input chat message'><span class='send cbutton'>SEND</span></div>" +
		"</div>"
	);
	
	/** video chat room chat-box */
	$('body').on("keypress", "#chat-input [name='message']", function(e) {
		if (e.which == 13) {
			e.preventDefault();
			video_chat_message_send();
		}
	});
	$('body').on('click', '#chat-input .send', video_chat_message_send );
	/** EO **/
	
	$('body').on('click', '#video-chat-room-button .camera', video_chat_room_camera );
	$('body').on('click', '#video-chat-room-button .sound', video_chat_room_sound );
	$('body').on('click', '#video-chat-room-button .leave', video_chat_room_leave );
	$('body').on('click', '#video-chat-room-button .password', video_chat_room_password );

	/// @todo white-board
	
	if ( $chat_whiteboard == true ) {
		$('#video-chat').append("<div id='white-board'><div id='white-board-menu'></div><div id='white-board-canvas'></div></div>");
		//var menu = get_whiteboard();
		//$('#white-board-menu').html(menu);
		//initialize_whiteboard();
	}

}

function video_chat_room_button_toggle_status( $this )
{
	if ( $this.hasClass("on") ) {
		$this.removeClass("on").addClass("off");
		return false;
	}
	else {
		$this.removeClass("off").addClass("on");
		return true;
	}
}

function video_chat_room_camera()
{
	var status = video_chat_room_button_toggle_status( $(this) );
	var vidtracks = easyrtc.getLocalStream().getVideoTracks();
	if ( vidtracks ) {
		for (var i = 0; i < vidtracks.length; i++) {
			var vidtrack = vidtracks[i];
			vidtrack.enabled = status;
		}
	}
}

function video_chat_room_sound()
{
	var status = video_chat_room_button_toggle_status( $(this) );
	var mictracks = easyrtc.getLocalStream().getAudioTracks();
	if (mictracks) {
		for (var i = 0; i < mictracks.length; i++) {
			var mictrack = mictracks[i];
			mictrack.enabled = status;
		}
	}
}

function video_chat_room_leave()
{
	$.removeCookie('chat_room_name');
	location.reload();
}
function video_chat_room_password()
{
	//여기서부터
}


function video_chat_message_send( data )
{
	var $input = $("#chat-input [name='message']");
	var text = $input.val();
	if ( text == '' ) {
		trace('empty chat message');
		return false;
	}
	text = text.replace(/&/g,"&amp;").replace(/</g,"&lt;").replace(/>/g,"&gt;"); //to avoid php and js scripts on chatbox
	
	var data = {};
	data.action = 'chat';
	data.name = $chat_user_name;
	data.message = text;
	
	var d = JSON.stringify(data);
	trace("video_chat_message_send():");
	trace_object( d );
	
	for ( var b in $connected ) {
		trace("b: " + b);
		easyrtc.sendData( b, d );
	}
	
	$input.val('');
	video_chat_message_recv( easyrtc.myEasyrtcid, data );
}

function video_chat_message_recv( rtc_id, data  )
{
	var name = data.name;
	var message = data.message;
	var cls;
	if ( name == $chat_user_name ) cls = 'you';
	else cls = 'other';
	
	
	var $box = $("#chat-message");
	$box.append("<div class='chat-message-line " + cls + "' rtc_id='"+rtc_id+"'><span class='name'>"+name+"</span><span class='separator'>:</span><span name='message'>" + message + "</span></div>");
	$box.animate( { scrollTop: $box.prop("scrollHeight") - $box.height() }, 150);
}




function roomListener(roomName, otherPeers) {
	
	// @todo double check if the code below is really working like that.
	for ( var id in otherPeers) {		// someone (including yourself) is connected
		if ( $chat_user_name == rtc_name( id ) ) {	// and you are inside the room already.
			if ( $connected == null ) {		// but you are entering the room for the first time. It happens when someone is using same name.
				alert('Duplicate user name in for ' + $chat_user_name + ' in \nRoom: ['+ roomName +']\nYou will not be able to call or recall other clients.\nPlease close this window or log out.');
				easyrtc.disconnect();
				return;
			}
		}
	}
	$connected = otherPeers;
	
	// @test
	trace("roomListener() User list: ");
	var count_connected = 0;
	for ( var id in $connected ) {
		count_connected ++;
		trace("User " + count_connected + ': ' + rtc_name(id) + ', ID:' + id);
	}
	trace("There are (" + count_connected + ") users");
	
	for ( var id in $connected ) {
		// if one's video is already open, do not connect.
		if ( $("video[rtc_id='" + id + "']").length ) {
			trace("ID of " + rtc_name(id) + " already exists. skipping performCall()");
		}
		else {
			trace("Connecting with " + rtc_name(id) + "...")
			performCall( id );
			
			/*send current status of mode,line color, line width, and font size*/
			/*send old image to new clients...*/
			/* @todo white board check.
			data = {};
			data.type = "whiteboard";
			data.currentstatus = true;
			data.sender = $user_name;
			data.mode = mode;
			data.linecolor = line_color;
			data.linewidth = line_width;
			data.fontsize = font_size;
			easyrtc.sendData( id, JSON.stringify(data) );
			//////////////////////////////////////
			
			setTimeout(function(){
			if( old_image ){
					data = {};
					data.type = "whiteboard";
					data.oldimage = old_image;
					easyrtc.sendData( id, JSON.stringify(data) );
					trace("OLD IMAGE is :" + old_image);
				}
			},3000);
			*/
		}
	}
	
}
function performCall(easyrtcid) {
	trace("performCall(" + rtc_name( easyrtcid ) + ")");
	easyrtc.call(
		easyrtcid,
		function(easyrtcid) { trace("completed call to " + easyrtcid);},
		function(errorMessage) { trace("performCall() callback -> errorMessage() :" + errorMessage);},
		function(accepted, bywho) {
			trace((accepted?"accepted":"rejected")+ " by " + bywho);
		}
	);
}


	/** @short this reads stream from outsite
	 *
	 * @note this callback is being called every time a user login and initialize their video or when a user gets out of the room.
	 */
function streamAcceptor( caller_rtc_id, stream )
{
alert('hi');
	var video = create_video_element( caller_rtc_id );	
	
	// @todo find it the best volume.
	video.volume = 0.8;

	if( video == null) { //just don't call if null
		// @todo try to set the stream awhile later.
		return warning( NO_VIDEO_SLOT_AVAILABLE );
	}
	else {
		easyrtc.setVideoObjectSrc( video, stream );
		trace("streamAcceptor() : some body comes in : " + rtc_name(caller_rtc_id) );
	}
}
function streamClosed( caller_rtc_id ) {
	trace( 'streamClosed() : ' + caller_rtc_id + " has closed his/her stream...");
	
	trace("Removing video element for : " + caller_rtc_id );
	$("#video-box video[rtc_id='"+caller_rtc_id+"']").parent().remove();
	
}
function streamChecker( caller_rtc_id, acceptor )
{
}
	

/** chatting */


function chat_recv( data )
{
	trace("Chat Received:");
	trace_object(data);
	
	
	/** 개설된 대화방 목록 표시 */
	if ( data.action == 'room list' ) {
		rooms = JSON.parse(data.value);
		
		$('#room-list').html('');
		chat_notice("Chat Room List", '');
		
		for ( var x in rooms ) {
			chat_room_add( rooms[x] );
		}
		chat_user_list_all();
	}
	
	
	else if ( data.action == 'join' ) {
	
		var md5 = data.md5;
		var sid = data.sid;
		var id = data.id;
		var room = data.room;
		
		
		room_user_delete( sid );
		
		chat_room_add( data );
		room_user_add( md5, id, sid );
		room_user_count( md5, 1 );
		
		
		
		
	
		// test
		chat_notice(data.id, " has joined into " + data.room);
	}
	
	else if ( data.action == 'disconnect' ) {
		var md5 = data.md5;
		var sid = data.sid;
		room_user_count( md5, -1 );
		room_user_delete( sid );
	}
	
	
	/** 사용자 목록 */
	else if ( data.action == 'user list' ) {
		
		var users = JSON.parse(data.users);
		var len = users.length;
		trace("No. of Users: " + len);
		if ( len ) {
			for ( var i in users ) {
				var u = users[i];
				room_user_add( u.md5, u.id, u.sid );
				room_user_count( u.md5, +1 );
			}
		}
	}
}



/*chat add message*/
function chat_add_message( rtc_id, name, message, you )
{		
	if ( you ) {
		cls = 'you';
		name = '';
	}
	else cls = 'other';
	
	var $box = $("#chat-message");
	$box.append("<div class='line "+cls+"' rtc_id='"+rtc_id+"'><span class='name'>"+name+"</span><span class='separator'>:</span><span name='message'>" + message + "</span></div>");
	$box.animate( { scrollTop: $box.prop("scrollHeight") - $box.height() }, 150);
}
/*----------------*/
function chat_room_add( room )
{
	$room = $("[room-md5='"+room.md5+"']");
	if ( $room.length ) return;
	html = "<div class='room'><span class='room-name' room-md5='"+room.md5+"'>" + room.room + "</span><span class='user-count'></span><span class='users'></span></div>";
	$('#room-list').append(html);
}

function room_user_delete( sid )
{
	$user = $("[sid='"+sid+"']");
	if ( $user.length ) {							// 사용자가 있는가?
		var md5 = get_room_md5_of_user( sid );		// 그렇다면 사용자 방 md5
		$user.remove();								// 사용자 삭제
		var cnt = room_user_count( md5, -1 );		// 사용자 방 인원수 -1
		if ( cnt <= 0 ) {							// 방 인원이 없으면,
			room_delete( md5 );						// 방 삭제
		}
	}
}
function get_room_md5_of_user( sid )
{
	$user = $("[sid='"+sid+"']");
	var md5 = $user.parent().parent().find('.room-name').attr('room-md5');
	trace("get_room_md5_of_user( "+sid+" ) : " + md5);
	return md5;
}

function room_delete( md5 )
{
	$("[room-md5='"+md5+"']").parent().remove();
}
function room_user_add( md5, id, sid ) {
	var html = "<span sid='"+sid+"'>" + id + "</span>";
	trace(html);
	$("[room-md5='"+md5+"']").parent().children('.users').append(html);
}
function room_user_count( md5, n )
{
	x = get_room_user_count( md5 );
	var r = x + n;
	$("[room-md5='"+md5+"']").parent().children('.user-count').text( r );
	return r;
}

function get_room_user_count( md5 )
{
	$obj = $("[room-md5='"+md5+"']").parent().children('.user-count');
	var x = $obj.text();
	x = parseInt(x) || 0;
	trace("get_room_user_count( " + md5 + " ) : " + x );
	return x;
}


function chat_line(msg)
{
	trace(msg);
}

function chat_notice(id, msg)
{
	trace(id + ':' + msg);
}




function get_user_name()
{
	return $chat_user_name;
}

function get_chat_room_name()
{
	var rn;
	
	if ( $('#chat-room-name').length ) rn = $('#chat-room-name').val();
	else if ( typeof $chat_room_name != 'undefined' ) rn = $chat_room_name;
	
	if ( rn == '' ) rn = 'lobby';
	return rn;
}


function chat_send(data)
{
	trace('Chat Send: ');
	trace_object(data);
	chat_socket.emit('chat', data);
}



function socket_connect ()
{
	trace("Connected successfully...");
	
	video_chat_lobby();
}
function socket_connecting () {
	trace("Trying to connect...");
}
function socket_disconnect() {
	trace("Disconnected....");
}
function socket_connect_failed() {
	trace("Connection failed...");
}
function socket_error() {
	trace("Error...!");
}
function socket_reconnect_failed() {
	trace("Reconnection failed...");
}

function sooket_reconnect() {
	trace("Reconnection succefully...");
}

function socket_reconnecting() {
	trace("Reconnecting....");
}


/////////////
function trace(str)
{
	trace_count ++;
	if ( typeof console === "undefined" || typeof console.log === "undefined" ) { }
	else console.log("TRACE[" + trace_count + "] : " + str);
}
function trace_object(data)
{
	console.log(data);
}

function chat_room_list()
{
	var msg = {};
	msg['action'] = 'room list';
	msg['id'] = get_user_name();
	chat_send(msg);
}

function chat_user_list()
{
	var msg = {};
	msg['action'] = 'user list';
	msg['id'] = get_user_name();
	msg['value'] = get_chat_room_name();
	chat_send(msg);
}
function chat_user_list_all()
{
	var msg = {};
	msg['action'] = 'user list';
	msg['id'] = get_user_name();
	msg['value'] = '';
	chat_send(msg);
}

function chat_room_join()
{
	var msg = {};
	msg['action'] = 'join';
	msg['id'] = get_user_name();
	msg['room'] = get_chat_room_name();
	chat_send(msg);
}

function chat_room_join_lobby()
{
	var msg = {};
	msg['action'] = 'join';
	msg['id'] = get_user_name();
	msg['room'] = 'lobby';
	chat_send(msg);
}



function warning( code )
{
	var code_message = null;
	switch( code ) {
		case NO_ROOM_NAME : code_message = "No room name is provided. Please input room name."; break;
		case NO_USER_NAME : code_message = "No user name is provided. Please input user name."; break;
		case NO_VIDEO_SLOT_AVAILABLE : code_message = "streamAcceptor : No video slot available."; break;
		
		default : code_message = "Unknown Error";
	}
	alert( code_message );
	return code;
}



/**
 *  For simplicity, it does not update. it refreshes for every 8 seconds.
 */
function video_chat_lobby()
{
	trace("video_chat_lobby()");
	if ( $('#room-list').length ) {
		update_room_list = true;
		chat_room_list();
	}
}











///////////////////////////////////			jQuery cookie
/*!
 * jQuery Cookie Plugin v1.4.1
 * https://github.com/carhartl/jquery-cookie
 *
 * Copyright 2006, 2014 Klaus Hartl
 * Released under the MIT license
 */
(function (factory) {
	if (typeof define === 'function' && define.amd) {
		// AMD
		define(['jquery'], factory);
	} else if (typeof exports === 'object') {
		// CommonJS
		factory(require('jquery'));
	} else {
		// Browser globals
		factory(jQuery);
	}
}(function ($) {

	var pluses = /\+/g;

	function encode(s) {
		return config.raw ? s : encodeURIComponent(s);
	}

	function decode(s) {
		return config.raw ? s : decodeURIComponent(s);
	}

	function stringifyCookieValue(value) {
		return encode(config.json ? JSON.stringify(value) : String(value));
	}

	function parseCookieValue(s) {
		if (s.indexOf('"') === 0) {
			// This is a quoted cookie as according to RFC2068, unescape...
			s = s.slice(1, -1).replace(/\\"/g, '"').replace(/\\\\/g, '\\');
		}

		try {
			// Replace server-side written pluses with spaces.
			// If we can't decode the cookie, ignore it, it's unusable.
			// If we can't parse the cookie, ignore it, it's unusable.
			s = decodeURIComponent(s.replace(pluses, ' '));
			return config.json ? JSON.parse(s) : s;
		} catch(e) {}
	}

	function read(s, converter) {
		var value = config.raw ? s : parseCookieValue(s);
		return $.isFunction(converter) ? converter(value) : value;
	}

	var config = $.cookie = function (key, value, options) {

		// Write

		if (arguments.length > 1 && !$.isFunction(value)) {
			options = $.extend({}, config.defaults, options);

			if (typeof options.expires === 'number') {
				var days = options.expires, t = options.expires = new Date();
				t.setTime(+t + days * 864e+5);
			}

			return (document.cookie = [
				encode(key), '=', stringifyCookieValue(value),
				options.expires ? '; expires=' + options.expires.toUTCString() : '', // use expires attribute, max-age is not supported by IE
				options.path    ? '; path=' + options.path : '',
				options.domain  ? '; domain=' + options.domain : '',
				options.secure  ? '; secure' : ''
			].join(''));
		}

		// Read

		var result = key ? undefined : {};

		// To prevent the for loop in the first place assign an empty array
		// in case there are no cookies at all. Also prevents odd result when
		// calling $.cookie().
		var cookies = document.cookie ? document.cookie.split('; ') : [];

		for (var i = 0, l = cookies.length; i < l; i++) {
			var parts = cookies[i].split('=');
			var name = decode(parts.shift());
			var cookie = parts.join('=');

			if (key && key === name) {
				// If second argument (value) is a function it's a converter...
				result = read(cookie, value);
				break;
			}

			// Prevent storing a cookie that we couldn't decode.
			if (!key && (cookie = read(cookie)) !== undefined) {
				result[name] = cookie;
			}
		}

		return result;
	};

	config.defaults = {};

	$.removeCookie = function (key, options) {
		if ($.cookie(key) === undefined) {
			return false;
		}

		// Must not alter options, thus extending a fresh object...
		$.cookie(key, '', $.extend({}, options, { expires: -1 }));
		return !$.cookie(key);
	};

}));
