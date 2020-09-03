/** define variables */
//var chat_socket;
var trace_count = 0;


var selfEasyrtcid = '';
var isConnected = false;

var call_other_users = false;




var $chat_room_name = false;
var $chat_user_name = false;
var $chat_user_password = false;
var $chat_observer = false;
var $chat_whiteboard = false;

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
	
	$chat_user_password = '1234';
	
	/** initialization */
	easyrtc.setSocketUrl( url_video_chat_server );
	easyrtc.setOnError( function( e ) { alert( 'easyrtc.setOnError() : ' + e.errorText); } );
	easyrtc.setPeerListener( peerListener );	// for easyrtc data & chat stream ( chat message and whiteboard message )
	easyrtc.setRoomOccupantListener( occupantListener );
	easyrtc.setRoomEntryListener(roomEntryListener);
	easyrtc.setDisconnectListener(function() {
        alert("disconnect listener fired");
    });
	
	easyrtc.setUsername( $chat_user_name );
	easyrtc.setCredential({password: $chat_user_password});
	
	
	easyrtc.connect("default", loginSuccess, loginFailure);

	
	
	
	$('body').on('click', '.enter-room', on_enter_room);
	$('.save-user-name').click( on_save_user_name );
});

function refreshRoomList() {
    if( isConnected) {
        easyrtc.getRoomList( callback_room_list, callback_room_list_error );
    }
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


////////////////////////////////	Callbacks		/////////////////////////////////

/**
 *  방 목록을 한 경우, 콜백
 */
function callback_room_list( roomList )
{

	for ( roomName in roomList ) {
		var no = roomList[ roomName ].numberClients;
		trace( "room : " + roomName + ', no: ' + no );
		trace_object( roomList[ roomName ] );
		video_chat_room_add( roomName, no );
		var occupants = easyrtc.getRoomOccupantsAsMap(roomName);
		video_chat_room_user_add( roomName, occupants );
	}
}
/**
 *  방 목록에 방을 추가한다.
 *  
 *  이미 방이 만들어져 있으면, 사용자를 삭제하고, 방인원수를 업데이트 한 다음 리턴한다.
 *  
 */
function video_chat_room_add( roomName, no )
{

	var id = get_room_id( roomName );
	var title = "<span class='room-name'>" + roomName + "</span><span class='user-count'>"+no+"</span>";
	if ( $('#' + id).length ) {
		$('#' + id).html( title );
	}
	else {
		var html = "<div class='room' id='"+id+"'>" + title + "</div>";
		$('#room-list').append(html);
	}
}

function video_chat_room_user_add( roomName, occupants )
{
	var id = get_room_id( roomName );
	var html = "<span class='room-user'>";
    var i;
    for ( i in occupants ) {
		var user = occupants[i];
        console.log(  user.username + ' (id: ' + user.easyrtcid + ') has joined at ' + user.roomJoinTime + " is in the room");
		html += "<span class='user' rtc-id='" + user.easyrtcid + "'>" + user.username + "</span>";
    }
	html += "</span>";
	$('#' + id).append( html );
}


function callback_room_list_error()
{
	easyrtc.showError(errorCode, errorText);
}


function loginSuccess(easyrtcid) {
    selfEasyrtcid = easyrtcid;
									trace("loginSuccess("+easyrtcid+")");
    isConnected = true;
    refreshRoomList();
}

function loginFailure(errorCode, message) {
    alert("LOGIN-FAILURE: " + message);
}




function on_enter_room()
{
	// if ( $chat_room_name ) return alert("Please leave the room - " + $chat_room_name);
	if ( $chat_user_name == '' ) return alert("Please input name");
	var roomName = $("[name='chat_room_name']").val();
	if ( roomName == '' ) return alert("Input room name");
	$chat_room_name = roomName;
	
	
	// @todo 여기서 이전에 들어가 있는 방은 퇴장을 해야 한다. 기본 방은 default
	easyrtc.joinRoom( roomName, null,
		function( roomName ) {
			console.log("I'm now in room " + roomName);
		},
		function(errorCode, errorText, roomName) {
			console.log("had problems joining " + roomName);
		}
	);
}




////////////////////////////////	Listeners		//////////////////////////////////


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



/**
 *  setRoomEnterListener 의 callback
 *  
 *  방에 입장하는 자신만 호출된다.
 *  
 */
function roomEntryListener( entered, roomName)
{
	trace("I joined into : " + roomName);
	call_other_users = true;

	/*
    if ( entered ) { // entered a room
        console.log("saw add of room " + roomName);
        addRoom(roomName, false);
    }
    else {
		alert(' Why this is called? ');
    }
	
    refreshRoomList();
	*/
}



/**
 *  다른 사람이 접속을 할 때, 호출되는 함수.
 *  
 *  내 방이 아니라, 다른 방에 다른 사람이 접속해도 호출되는가?
 */
function occupantListener(roomName, otherPeers) {

	if ( roomName == null ) return;
	trace("occupantListener("+roomName+") :");
	trace_object( otherPeers );
	
	var room_id = get_room_id( roomName );
	var count_connected = 0;
	for ( var id in otherPeers ) {
		count_connected ++;
		trace("User " + count_connected + ': ' + rtc_name(id) + ', ID:' + id);
	}
	
	video_chat_room_add( roomName, count_connected );
	video_chat_room_user_add( roomName, otherPeers );
	
	if ( call_other_users == false ) return;
	
	
	// performCall() here.
	
}


function get_room_id(roomName) {
    return 'room-id-' + $.md5( roomName );
}




function addRoom( roomName, userAdded )
{
	trace("addRoom( "+roomName + ',' + userAdded + ")");
	
}





function rtc_name ( caller_rtc_id )
{
	return easyrtc.idToName( caller_rtc_id );
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



/**
     * jQuery MD5 hash algorithm function
     * 
     *  <code>
     *          Calculate the md5 hash of a String 
     *          String $.md5 ( String str )
     *  </code>
     * 
     * Calculates the MD5 hash of str using the » RSA Data Security, Inc. MD5 Message-Digest Algorithm, and returns that hash. 
     * MD5 (Message-Digest algorithm 5) is a widely-used cryptographic hash function with a 128-bit hash value. MD5 has been employed in a wide variety of security applications, and is also commonly used to check the integrity of data. The generated hash is also non-reversable. Data cannot be retrieved from the message digest, the digest uniquely identifies the data.
     * MD5 was developed by Professor Ronald L. Rivest in 1994. Its 128 bit (16 byte) message digest makes it a faster implementation than SHA-1.
     * This script is used to process a variable length message into a fixed-length output of 128 bits using the MD5 algorithm. It is fully compatible with UTF-8 encoding. It is very useful when u want to transfer encrypted passwords over the internet. If you plan using UTF-8 encoding in your project don't forget to set the page encoding to UTF-8 (Content-Type meta tag). 
     * This function orginally get from the WebToolkit and rewrite for using as the jQuery plugin.
     * 
     * Example
     *  Code
     *          <code>
     *                  $.md5("I'm Persian."); 
     *          </code>
     *  Result
     *          <code>
     *                  "b8c901d0f02223f9761016cfff9d68df"
     *          </code>
     * 
     * @alias Muhammad Hussein Fattahizadeh < muhammad [AT] semnanweb [DOT] com >
     * @link http://www.semnanweb.com/jquery-plugin/md5.html
     * @see http://www.webtoolkit.info/
     * @license http://www.gnu.org/licenses/gpl.html [GNU General Public License]
     * @param {jQuery} {md5:function(string))
     * @return string
     */
    
    (function($) {
    
      var rotateLeft = function(lValue, iShiftBits) {
          return (lValue << iShiftBits) | (lValue >>> (32 - iShiftBits));
          }
          
          
          
      var addUnsigned = function(lX, lY) {
          var lX4, lY4, lX8, lY8, lResult;
          lX8 = (lX & 0x80000000);
          lY8 = (lY & 0x80000000);
          lX4 = (lX & 0x40000000);
          lY4 = (lY & 0x40000000);
          lResult = (lX & 0x3FFFFFFF) + (lY & 0x3FFFFFFF);
          if (lX4 & lY4) return (lResult ^ 0x80000000 ^ lX8 ^ lY8);
          if (lX4 | lY4) {
            if (lResult & 0x40000000) return (lResult ^ 0xC0000000 ^ lX8 ^ lY8);
            else return (lResult ^ 0x40000000 ^ lX8 ^ lY8);
          } else {
            return (lResult ^ lX8 ^ lY8);
          }
          }
          
          
          
      var F = function(x, y, z) {
          return (x & y) | ((~x) & z);
          }
          
          
          
      var G = function(x, y, z) {
          return (x & z) | (y & (~z));
          }
          
          
          
      var H = function(x, y, z) {
          return (x ^ y ^ z);
          }
          
          
          
      var I = function(x, y, z) {
          return (y ^ (x | (~z)));
          }
          
          
          
      var FF = function(a, b, c, d, x, s, ac) {
          a = addUnsigned(a, addUnsigned(addUnsigned(F(b, c, d), x), ac));
          return addUnsigned(rotateLeft(a, s), b);
          };
    
      var GG = function(a, b, c, d, x, s, ac) {
          a = addUnsigned(a, addUnsigned(addUnsigned(G(b, c, d), x), ac));
          return addUnsigned(rotateLeft(a, s), b);
          };
    
      var HH = function(a, b, c, d, x, s, ac) {
          a = addUnsigned(a, addUnsigned(addUnsigned(H(b, c, d), x), ac));
          return addUnsigned(rotateLeft(a, s), b);
          };
    
      var II = function(a, b, c, d, x, s, ac) {
          a = addUnsigned(a, addUnsigned(addUnsigned(I(b, c, d), x), ac));
          return addUnsigned(rotateLeft(a, s), b);
          };
    
      var convertToWordArray = function(string) {
          var lWordCount;
          var lMessageLength = string.length;
          var lNumberOfWordsTempOne = lMessageLength + 8;
          var lNumberOfWordsTempTwo = (lNumberOfWordsTempOne - (lNumberOfWordsTempOne % 64)) / 64;
          var lNumberOfWords = (lNumberOfWordsTempTwo + 1) * 16;
          var lWordArray = Array(lNumberOfWords - 1);
          var lBytePosition = 0;
          var lByteCount = 0;
          while (lByteCount < lMessageLength) {
            lWordCount = (lByteCount - (lByteCount % 4)) / 4;
            lBytePosition = (lByteCount % 4) * 8;
            lWordArray[lWordCount] = (lWordArray[lWordCount] | (string.charCodeAt(lByteCount) << lBytePosition));
            lByteCount++;
          }
          lWordCount = (lByteCount - (lByteCount % 4)) / 4;
          lBytePosition = (lByteCount % 4) * 8;
          lWordArray[lWordCount] = lWordArray[lWordCount] | (0x80 << lBytePosition);
          lWordArray[lNumberOfWords - 2] = lMessageLength << 3;
          lWordArray[lNumberOfWords - 1] = lMessageLength >>> 29;
          return lWordArray;
          };
    
      var wordToHex = function(lValue) {
          var WordToHexValue = "",
              WordToHexValueTemp = "",
              lByte, lCount;
          for (lCount = 0; lCount <= 3; lCount++) {
            lByte = (lValue >>> (lCount * 8)) & 255;
            WordToHexValueTemp = "0" + lByte.toString(16);
            WordToHexValue = WordToHexValue + WordToHexValueTemp.substr(WordToHexValueTemp.length - 2, 2);
          }
          return WordToHexValue;
          };
    
      var uTF8Encode = function(string) {
          string = string.replace(/\x0d\x0a/g, "\x0a");
          var output = "";
          for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
              output += String.fromCharCode(c);
            } else if ((c > 127) && (c < 2048)) {
              output += String.fromCharCode((c >> 6) | 192);
              output += String.fromCharCode((c & 63) | 128);
            } else {
              output += String.fromCharCode((c >> 12) | 224);
              output += String.fromCharCode(((c >> 6) & 63) | 128);
              output += String.fromCharCode((c & 63) | 128);
            }
          }
          return output;
          };
    
      $.extend({
        md5: function(string) {
          var x = Array();
          var k, AA, BB, CC, DD, a, b, c, d;
          var S11 = 7,
              S12 = 12,
              S13 = 17,
              S14 = 22;
          var S21 = 5,
              S22 = 9,
              S23 = 14,
              S24 = 20;
          var S31 = 4,
              S32 = 11,
              S33 = 16,
              S34 = 23;
          var S41 = 6,
              S42 = 10,
              S43 = 15,
              S44 = 21;
          string = uTF8Encode(string);
          x = convertToWordArray(string);
          a = 0x67452301;
          b = 0xEFCDAB89;
          c = 0x98BADCFE;
          d = 0x10325476;
          for (k = 0; k < x.length; k += 16) {
            AA = a;
            BB = b;
            CC = c;
            DD = d;
            a = FF(a, b, c, d, x[k + 0], S11, 0xD76AA478);
            d = FF(d, a, b, c, x[k + 1], S12, 0xE8C7B756);
            c = FF(c, d, a, b, x[k + 2], S13, 0x242070DB);
            b = FF(b, c, d, a, x[k + 3], S14, 0xC1BDCEEE);
            a = FF(a, b, c, d, x[k + 4], S11, 0xF57C0FAF);
            d = FF(d, a, b, c, x[k + 5], S12, 0x4787C62A);
            c = FF(c, d, a, b, x[k + 6], S13, 0xA8304613);
            b = FF(b, c, d, a, x[k + 7], S14, 0xFD469501);
            a = FF(a, b, c, d, x[k + 8], S11, 0x698098D8);
            d = FF(d, a, b, c, x[k + 9], S12, 0x8B44F7AF);
            c = FF(c, d, a, b, x[k + 10], S13, 0xFFFF5BB1);
            b = FF(b, c, d, a, x[k + 11], S14, 0x895CD7BE);
            a = FF(a, b, c, d, x[k + 12], S11, 0x6B901122);
            d = FF(d, a, b, c, x[k + 13], S12, 0xFD987193);
            c = FF(c, d, a, b, x[k + 14], S13, 0xA679438E);
            b = FF(b, c, d, a, x[k + 15], S14, 0x49B40821);
            a = GG(a, b, c, d, x[k + 1], S21, 0xF61E2562);
            d = GG(d, a, b, c, x[k + 6], S22, 0xC040B340);
            c = GG(c, d, a, b, x[k + 11], S23, 0x265E5A51);
            b = GG(b, c, d, a, x[k + 0], S24, 0xE9B6C7AA);
            a = GG(a, b, c, d, x[k + 5], S21, 0xD62F105D);
            d = GG(d, a, b, c, x[k + 10], S22, 0x2441453);
            c = GG(c, d, a, b, x[k + 15], S23, 0xD8A1E681);
            b = GG(b, c, d, a, x[k + 4], S24, 0xE7D3FBC8);
            a = GG(a, b, c, d, x[k + 9], S21, 0x21E1CDE6);
            d = GG(d, a, b, c, x[k + 14], S22, 0xC33707D6);
            c = GG(c, d, a, b, x[k + 3], S23, 0xF4D50D87);
            b = GG(b, c, d, a, x[k + 8], S24, 0x455A14ED);
            a = GG(a, b, c, d, x[k + 13], S21, 0xA9E3E905);
            d = GG(d, a, b, c, x[k + 2], S22, 0xFCEFA3F8);
            c = GG(c, d, a, b, x[k + 7], S23, 0x676F02D9);
            b = GG(b, c, d, a, x[k + 12], S24, 0x8D2A4C8A);
            a = HH(a, b, c, d, x[k + 5], S31, 0xFFFA3942);
            d = HH(d, a, b, c, x[k + 8], S32, 0x8771F681);
            c = HH(c, d, a, b, x[k + 11], S33, 0x6D9D6122);
            b = HH(b, c, d, a, x[k + 14], S34, 0xFDE5380C);
            a = HH(a, b, c, d, x[k + 1], S31, 0xA4BEEA44);
            d = HH(d, a, b, c, x[k + 4], S32, 0x4BDECFA9);
            c = HH(c, d, a, b, x[k + 7], S33, 0xF6BB4B60);
            b = HH(b, c, d, a, x[k + 10], S34, 0xBEBFBC70);
            a = HH(a, b, c, d, x[k + 13], S31, 0x289B7EC6);
            d = HH(d, a, b, c, x[k + 0], S32, 0xEAA127FA);
            c = HH(c, d, a, b, x[k + 3], S33, 0xD4EF3085);
            b = HH(b, c, d, a, x[k + 6], S34, 0x4881D05);
            a = HH(a, b, c, d, x[k + 9], S31, 0xD9D4D039);
            d = HH(d, a, b, c, x[k + 12], S32, 0xE6DB99E5);
            c = HH(c, d, a, b, x[k + 15], S33, 0x1FA27CF8);
            b = HH(b, c, d, a, x[k + 2], S34, 0xC4AC5665);
            a = II(a, b, c, d, x[k + 0], S41, 0xF4292244);
            d = II(d, a, b, c, x[k + 7], S42, 0x432AFF97);
            c = II(c, d, a, b, x[k + 14], S43, 0xAB9423A7);
            b = II(b, c, d, a, x[k + 5], S44, 0xFC93A039);
            a = II(a, b, c, d, x[k + 12], S41, 0x655B59C3);
            d = II(d, a, b, c, x[k + 3], S42, 0x8F0CCC92);
            c = II(c, d, a, b, x[k + 10], S43, 0xFFEFF47D);
            b = II(b, c, d, a, x[k + 1], S44, 0x85845DD1);
            a = II(a, b, c, d, x[k + 8], S41, 0x6FA87E4F);
            d = II(d, a, b, c, x[k + 15], S42, 0xFE2CE6E0);
            c = II(c, d, a, b, x[k + 6], S43, 0xA3014314);
            b = II(b, c, d, a, x[k + 13], S44, 0x4E0811A1);
            a = II(a, b, c, d, x[k + 4], S41, 0xF7537E82);
            d = II(d, a, b, c, x[k + 11], S42, 0xBD3AF235);
            c = II(c, d, a, b, x[k + 2], S43, 0x2AD7D2BB);
            b = II(b, c, d, a, x[k + 9], S44, 0xEB86D391);
            a = addUnsigned(a, AA);
            b = addUnsigned(b, BB);
            c = addUnsigned(c, CC);
            d = addUnsigned(d, DD);
          }
          var tempValue = wordToHex(a) + wordToHex(b) + wordToHex(c) + wordToHex(d);
          return tempValue.toLowerCase();
        }
      });
    })(jQuery);
	