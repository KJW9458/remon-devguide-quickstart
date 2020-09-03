var https	= require("https");
var fs		= require("fs");
var express	= require("express");
var io		= require("socket.io");
var easyrtc	= require("easyrtc");
var util	= require("util");
var md5		= require("MD5");


var httpApp = express();
httpApp.use(express.static(__dirname + "/static/"));
var webServer = https.createServer( /* SSL Web Server port 8443 */
{
    key:  fs.readFileSync('/etc/httpd/ssl/multi.key'),
    cert: fs.readFileSync('/etc/httpd/ssl/multi.crt')
},
httpApp).listen(8443);
var myIceConfig = [
  {"url":"stun:ontue.com:3478"},
  {
    "url":"turn:ontue.com:3478",
    "username":"my_username",
    "credential":"my_password"
  }
];
easyrtc.setOption("appIceServers", myIceConfig);
var socketServer = io.listen(webServer, {"log level":1});

socketServer.enable('browser client minification');  // send minified client
socketServer.enable('browser client etag');          // apply etag caching logic based on version number
socketServer.enable('browser client gzip');          // gzip the file
socketServer.set('log level', 1);                    // reduce logging
socketServer.set('transports', [                     // enable all transports (optional if you want flashsocket)
    'websocket'
  , 'flashsocket'
  , 'htmlfile'
  , 'xhr-polling'
  , 'jsonp-polling'
]);

var rtc = easyrtc.listen(httpApp, socketServer); /* Start EasyRTC server */




socketServer.sockets.on('connection', on_connect);
function on_connect( socket )
{
	console.log("on_connect(" + socket + ")");
	socket.on( 'chat', function(data) {
		chat( socket, data );
	});
	socket.on('disconnect', function() {
		chat( socket, {action:'disconnect'} );
	});
}






var chat_user_info = {};
var chat_socket_id = new Array();
var chat_room_setting = {};

function chat( socket, data )
{
	//console.log( "from client: " + util.inspect(data) );



	if ( data.action == 'room list' ) {
		var rooms = new Array();
		for ( var x in socketServer.sockets.manager.rooms ) {
			if ( x ) {
				var room_name = x.replace(/^\//, '');
				console.log("room list : " + room_name);
				var room = { 'room' : room_name };
				room.md5 = md5( room_name );
				if ( typeof chat_room_setting[ room_name ] != 'undefined' && typeof chat_room_setting[ room_name ]['max no of user'] != 'undefined' ) room.max_no_of_user = chat_room_setting[ room_name ]['max no of user'];
				rooms.push(room);
			}
		}
		socket.emit('chat', {action: 'room list', value: JSON.stringify(rooms)});
	}

	else if ( data.action == 'user list' ) {
		console.log("user list data:");
		console.log( util.inspect( data ) );
		socket.emit('chat', { action: 'user list', 'users': JSON.stringify(get_user_list(''))});
	}
	else if ( data.action == 'message' ) {
		var c = get_chat_user_info(socket, data);
		c.action = 'message';
		c.message = data.message;
		socketServer.sockets.emit('chat', c ); // broadcasting
	}
	else if ( data.action == 'join' ) {

		var c = get_chat_user_info(socket, data);

		socket.join(data.room);
		chat_user_info[socket.id].room = data.room;
		c.action = 'join';
		c.room = data.room;
		c.md5 = md5( c.room );
		// 여기에서 최대 사용자 수를 전송해야 모든 클라이언트에 업데이트를 한다.
		if ( typeof chat_room_setting[ data.room ] != 'undefined' && typeof chat_room_setting[ data.room ]['max no of user'] != 'undefined' ) c.max_no_of_user = chat_room_setting[ data.room ]['max no of user'];


		socketServer.sockets.emit('chat', c ); /* 입장 브로드 캐스팅 */
		
		
		if ( typeof chat_room_setting[ data.room ] == 'undefined' ) chat_room_setting[ data.room ] = {};

		chat_room_setting[ data.room ]['user list'] = JSON.stringify( get_user_list( data.room ) );

			var setting = chat_room_setting[ data.room ];
			//socketServer.sockets.in(data.room).emit('chat', {action: 'setting', 'setting': setting } ); /* 방 설정 브로드캐스트 */
			socket.emit('chat', {action: 'setting', 'setting': setting } );
	}
	else if ( data.action == 'disconnect' ) {
		var c = get_chat_user_info(socket, data);
		c.action = 'disconnect';
		c.md5 = md5( c.room );
		console.log("disconnect --------------------------------------------------------------");
		socketServer.sockets.emit('chat', c ); /* 퇴장 브로드캐스팅 */
	
		// delete socket info
		var i = chat_socket_id.indexOf(socket.id);
		chat_socket_id.splice(i, 1)
	}
	else if ( data.action == 'setting' ) {
		if ( typeof chat_room_setting[ data.room ] == 'undefined' ) chat_room_setting[ data.room ] = {};
		chat_room_setting[ data.room ][ data.key ] = data.value;
		console.log( 'setting' );
		console.log( util.inspect( chat_room_setting ) );
	}
}






/** @short store user information
 *
 * 모든 액션에서 이 함수를 통해서 사용자 정보를 얻는다.
 * 즉, 어떤 액션이든 이름을 변경 할 수 있다.
 * connection_count 은 한 아이디가 몇 번을 재 접속하는지 카운트를 한다.
 */
function get_chat_user_info(socket, data)
{
	var client = {};
	var sid = socket.id;
	client.room = data.room;
	if ( typeof data.id != 'undefined' ) client.id = data.id;
	if ( typeof data.room != 'undefined' ) client.room = data.room;
	client.md5 = md5( data.room );
	client.sid = sid;

	var i = chat_socket_id.indexOf(sid);
	if ( i == -1 ) { // new connection
		console.log("get_chat_user_inf() : new user");
	}
	else {
		console.log("get_chat_user_inf() : user exists");
	}

	chat_socket_id.push( sid );
	chat_user_info[ sid ] = client;

	console.log( util.inspect( client ) );
	return client;
}

function get_user_list( room )
{
	var clients = new Array();
	var users;

	if ( room != '' ) room = '/' + room;
	users = socketServer.sockets.manager.rooms[ room ];
	console.log("user list of : " + room );
	console.log(util.inspect(users));

	var found = new Array();
	for ( var u in users ) {
		var sid = users[u];
		var c = chat_user_info[sid];
		if ( c ) {
			console.log("FOUND socket id:" + sid + " User ID:" + c.id);
			if ( found.indexOf( sid ) == -1 ) {
				found.push( sid );
				clients.push(c);
			}
			else {
				console.log("FOUND & PUSHED ID already: is it error? socket id:" + sid + " User ID:" + c.id);
			}
		}
	}
	return clients
}
