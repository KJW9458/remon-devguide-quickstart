<?php

$_log_file = null;
$_count_log = 0;
function log_file( $file )
{
	global $_db_log;
	$_db_log = $file;
}
function debug( $msg ) { return dog( $msg ); }
function dog( $msg )
{
	global $_db_log, $_count_db_log;
	if ( $_db_log ) {
		$_count_db_log ++;
		if ( is_numeric( $msg ) || is_string( $msg ) ) {
			
		}
		else {
			ob_start();
			print_r($msg);
			$msg = ob_get_clean();
		}
		file::append( $_db_log, "[ $_count_db_log ] $msg\n" );
	}
}


function fv( $str )
{
	return htmlspecialchars( $str );
}

/**
 *  사용자 브라우저의 언어를 두글자로 리턴한다.
 */
function browser_language()
{
	return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}

/**
 *  사용자 언어를 두글자로 리턴한다.
 *  
 *  사용자 언어란, 쿠키에 보관된 언어 코드가 있으면 그것을 사용하고,
 *  
 *  없으면 브라우저의 것을 사용한다.
 */
function user_language()
{
	return browser_language();
}


function load_language_pack( $folder )
{
	global $language_code;
	if ( ! isset( $language_code ) ) $language_code = array();
	else if ( ! is_array( $language_code ) ) {
		// 에러. 언어 변수는 배열이어야 한다.
	}
	
	include $folder . '/en.php';
	$ln = browser_language();
	if ( $ln != 'en' ) include "$folder/$ln.php";	
}


/**
	 *
		@code
			return lang($code, $args);
		@endcode
		
		@note 언어 파일에는
			
			대소문자를 그대로 쓰거나 또는 모두 소문자로 쓰면 된다.
			예를 들어
			lang('OK') 를 호출 할 때,
			
			언어 파일에서는
				'OK' 또는 'ok' 두가지 방식으로 기록 할 수 있다.
		
		@attention 숫자 코드
			
			그냥 숫자를 문자로 사용해되 된다.
			
		@param $args
		
			언어 코드에 내용을 패치할 수 있다.
			
			예를 들어
			
				$sys['language']['abc'] = "my no. #no";
			
			와 같이 언어 파일에 기록되어져 있다면,
			
				lang( 'abc', array( 'no' => 123 ) );
			
			와 같이 호출하면 결과가
			
				"my no. 123"
				
			와 같이 된다.

	 */
	function ln($code, $args=array())
	{
		global $language_code;
		
		$code_lowered = strtolower($code);
		
		if ( isset($language_code[$code]) ) $ln = $language_code[$code];
		else if ( isset($language_code[$code_lowered]) ) $ln = $language_code[$code_lowered];
		else $ln = $code;
		
		if ( $args ) {
			foreach ( $args as $k => $v ) {
				$ln = str_replace('#'.$k, $v, $ln);
			}
		}
		return $ln;
	}

/**
 *  이 함수를 호출하면 웹 브라우저에서 HTTP AUTH 창을 열어서 아이디와 비번을 입력받아 전송한다.
 *  
 *  ID 와 Password 가 틀리면 맞을 때 까지 계속 연다.
 */
function http_auth_check( $id, $password, $title )
{
	if ( isset($_SERVER['PHP_AUTH_USER']) ) {
		if ( $_SERVER['PHP_AUTH_USER'] == $id && $_SERVER['PHP_AUTH_PW'] == $password ) return true;
	}
	header("WWW-Authenticate: Basic realm=\"$title\"");
	header('HTTP/1.0 401 Unauthorized');
	jsReload();
	exit;
}



/// https://docs.google.com/a/withcenter.com/document/d/1DpRH9ogpA2doUUissVQvq6zDwtT2-tgubCxvDbVSMd4/edit#heading=h.cvgyrrqsls5a

/// https://docs.google.com/document/d/1cqG9sghuNGyrSKsZBaV4dmretcA6tb_WfOD1jlyldLk/edit#heading=h.ucyyvje7bu6x




$_TLD_1 = array(
	'kr',
);
$_TLD_2 = array(
	'co',
	'or',
	'go',
	'pe',
);



/**
 *
 *  
 *  @short returns the base domain (domain.tld)
 *  
 *  
 *  
 *  
 *  @code
 *  	base_domain($_SERVER['HTTP_HOST'])
 *  	base_domain("asdfasdfa.asdfasdf.asdfasdf.asdfasd.31413241234.123.4123.4.1234.1324.abc.com"); => abc.com
 *  @endcode
 *  
 *  @return
 *  	www.abc.com				=>		abc.com
 *  	www.work.go.kr			=>		work.go.kr
 */
	function base_domain( $domain = null )
	{
	  
		if ( $domain === null ) {
			$domain = $_SERVER['HTTP_HOST'];
		}
		
		$parts = array_reverse(explode('.', $domain));
		
		if ( in_array( $parts[0], $GLOBALS['_TLD_1'] ) ) {
			if ( in_array( $parts[1], $GLOBALS['_TLD_2'] ) ) {
				return $parts[2] . '.' . $parts[1] . '.' . $parts[0];
			}
			else return $parts[1] . '.' . $parts[0];
		}
		
		return __base_domain( $domain );
	}
	function __base_domain( $full_domain )
	{

	  // generic tlds (source: http://en.wikipedia.org/wiki/Generic_top-level_domain)
	  $G_TLD = array(
		'biz','com','edu','gov','info','int','mil','name','net','org',
		'aero','asia','cat','coop','jobs','mobi','museum','pro','tel','travel',
		'arpa','root',
		'berlin','bzh','cym','gal','geo','kid','kids','lat','mail','nyc','post','sco','web','xxx',
		'nato',
		'example','invalid','localhost','test',
		'bitnet','csnet','ip','local','onion','uucp',
		'co'   // note: not technically, but used in things like co.uk
	  );
	  
	  // country tlds (source: http://en.wikipedia.org/wiki/Country_code_top-level_domain)
	  $C_TLD = array(
		// active
		'ac','ad','ae','af','ag','ai','al','am','an','ao','aq','ar','as','at','au','aw','ax','az',
		'ba','bb','bd','be','bf','bg','bh','bi','bj','bm','bn','bo','br','bs','bt','bw','by','bz',
		'ca','cc','cd','cf','cg','ch','ci','ck','cl','cm','cn','co','cr','cu','cv','cx','cy','cz',
		'de','dj','dk','dm','do','dz','ec','ee','eg','er','es','et','eu','fi','fj','fk','fm','fo',
		'fr','ga','gd','ge','gf','gg','gh','gi','gl','gm','gn','gp','gq','gr','gs','gt','gu','gw',
		'gy','hk','hm','hn','hr','ht','hu','id','ie','il','im','in','io','iq','ir','is','it','je',
		'jm','jo','jp','ke','kg','kh','ki','km','kn','kr','kw','ky','kz','la','lb','lc','li','lk',
		'lr','ls','lt','lu','lv','ly','ma','mc','md','mg','mh','mk','ml','mm','mn','mo','mp','mq',
		'mr','ms','mt','mu','mv','mw','mx','my','mz','na','nc','ne','nf','ng','ni','nl','no','np',
		'nr','nu','nz','om','pa','pe','pf','pg','ph','pk','pl','pn','pr','ps','pt','pw','py','qa',
		're','ro','ru','rw','sa','sb','sc','sd','se','sg','sh','si','sk','sl','sm','sn','sr','st',
		'sv','sy','sz','tc','td','tf','tg','th','tj','tk','tl','tm','tn','to','tr','tt','tv','tw',
		'tz','ua','ug','uk','us','uy','uz','va','vc','ve','vg','vi','vn','vu','wf','ws','ye','yu',
		'za','zm','zw',
		// inactive
		'eh','kp','me','rs','um','bv','gb','pm','sj','so','yt','su','tp','bu','cs','dd','zr'
		);
	  
	  
	  
	  // now the fun
	  
		// break up domain, reverse
		$DOMAIN = explode('.', $full_domain);
		$DOMAIN = array_reverse($DOMAIN);
		
		// first check for ip address
		if ( count($DOMAIN) == 4 && is_numeric($DOMAIN[0]) && is_numeric($DOMAIN[3]) )
		{
		  return $full_domain;
		}
		
		// if only 2 domain parts, that must be our domain
		if ( count($DOMAIN) <= 2 ) return $full_domain;
		
		/* 
		  finally, with 3+ domain parts: obviously D0 is tld 
		  now, if D0 = ctld and D1 = gtld, we might have something like com.uk
		  so, if D0 = ctld && D1 = gtld && D2 != 'www', domain = D2.D1.D0
		  else if D0 = ctld && D1 = gtld && D2 == 'www', domain = D1.D0
		  else domain = D1.D0
		  these rules are simplified below 
		*/
		if ( in_array($DOMAIN[0], $C_TLD) && in_array($DOMAIN[1], $G_TLD) && $DOMAIN[2] != 'www' )
		{
		  $full_domain = $DOMAIN[2] . '.' . $DOMAIN[1] . '.' . $DOMAIN[0];
		}
		else
		{
		  $full_domain = $DOMAIN[1] . '.' . $DOMAIN[0];;
		}
	  // did we succeed?  
	  return $full_domain;
	}

/**
 *  IE 이면 참을 리턴한다.
 */
function is_ie()
{
	if ( strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") ) return true;
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Trident/7.0; rv:11.0') !== false) return true;
	
}


	/** @short determins whether the system is windows or not.
	 @return true if the system wherein the PHP script is running is window.
	 */
	function is_window()
	{
		if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') return true;
		return false;
	}
	
	
/// https://docs.google.com/a/withcenter.com/document/d/19xSd2ChA2QO1_ty3A6QMgsuOWLcjZl9EUDG-G8Im4z4/edit#


class stamp
{


	
	/// returns the begining stamp of today.
	/// 0 hour 0 minutes 0 second of the beginning day.
	static function today()
	{
		return mktime(0, 0, 0, date('m'), date('d'), date('Y'));
	}
	/**
		you can input -1, +5 to get the stamp of the day from today.
		*/
	static function mkstamp($days)
	{
		return self::today() + $days * 60 * 60 * 24;
	}
	

	/** @short returns UTC stamp.
	 *
	 * It add/deduct seconds on the user's local time zone.
	 *
	 * 현재 로컬 시간에 해당하는 stamp 를 입력 받아서, UTC 0 의 stamp 를 리턴한다.
	 *
	 * 즉, 로컬 시간의 stamp 에 맞는 UTC 0 의 stamp 를 리턴하는 것이다.
	 *
	 * @attention stamp 를 입력 받아서 stamp 를 리턴한다.
	 * @attention 입력 받는 stamp 는 현재 시간대(local time zone) 값이다. 다른 시간대 값을 가질 수는 없다.
		
		예를 들어, 현재 시간이 UTC+9 2013-11-01 20:30 이고 stamp 가 12345 라면,
		
		이 12345 를 UTC+9 과 UTC+0 의 차이를 계산해서 UTC+0 에 해당하는 stamp 값인 10000 으로 리턴한다.
		
		
		예를 들어,
		
			date("Ymd", time::utc());
			
		와 같이 사용을 하면, 현재 시간의 'Ymd' 값이 아닌, UTC+0 의 'Ymd' 를 리턴한다.
		
		
	 *
	 * @param $stamp is user's local time zone stamp.
		if it is omitted, it will take the current time stamp by default.
		
		@code How to get the UTC+0 stamp of current time.
			$stamp_local = time();						// get the local stamp
			$stamp_utc = time::utc( $stamp_local );		// convert it to UTC+0 stamp.
		@endcode
		
		@code This code is the same as above.
			$stamp_utc = time::utc();
		@endcode
		
		@code How to get UTC+0 from a time like "2pm" of current timezone. 현재 타임존의 오후 2시에 해당하는 UTC+0 값 얻기.
			$stamp_local = mktime(14, 0, 0, date('m'), date('d'), date('Y'));
			$stamp_utc_0 = time::utc($stamp_local);
			echo date('ha', $stamp_utc_0
		@endcode
		
		@code How to get date() of UTC+0 from current timezone. 현재 타임존에서 UTC+0 값 날짜 구하기
			date("Ymd", time::utc());
		@endcode
		
		@code 특정 local timezone 의 날짜의 UTC+0 으로 바꾸는 코드
			$stamp = time::ymd( "$in[date_begin]" );				// 이것 자체가 UTC 시간이다.
															// date('r', ...) 하면 UTC +9000 과 같이 로컬 타임존으로 나오지만, 실제 시간 값은 UTC 의 값이다.
			$utc = time::utc( $stamp );
			$date_begin = date('Ymd', time::utc($stamp) );
			$Hi_begin = date('Hi', time::utc($stamp) );
		@endcode
		
		@code 오늘 날짜 UTC+0 으로 구하기
		
			$date = date('Ymd', time::utc() );
			
		
		
	 */
	static function utc($local_stamp=0)
	{
		if ( $local_stamp == 0 ) $local_stamp = time();
		return $local_stamp - date('Z');
	}
	static function utc_stamp( $local_stamp = 0 )
	{
		return self::utc( $local_stamp );
	}
	/** @short UTC+0 타임존의 stamp 를 입력 받아서 local timezone 의 stamp 로 리턴한다.
	
		@note get UTC and return local timezone stamp.
	 *
	 * @code
	 	echo date("r");
	echo "<hr>";
	echo date("r", time::utc());
	echo "<hr>";
	echo date("r", time::utc_to_localstamp(time::utc()));
		@endcode
	 */
	static function utc_to_local_stamp($utc)
	{
		return $utc + date('Z');
	}
	static function local_stamp( $utc )
	{
		return self::utc_to_local_stamp($utc);
	}
	
	/** @short returns the time differrence in second from one to the other.
	 *
	 * 두개의 타임존을 입력 받아 그 차이를 초 단위로 리턴한다.
	 * 이 것은 date('Z') 와 비슷한데, 차이점은 두개의 시간을 비교해서 차이 값을 리턴한다는 것이다.
	 *
	 */
	static function offset( $remote_tz, $origin_tz )
	{
		$origin_dtz = new DateTimeZone($origin_tz);
		$remote_dtz = new DateTimeZone($remote_tz);
		$origin_dt = new DateTime("now", $origin_dtz);
		$remote_dt = new DateTime("now", $remote_dtz);
		$offset = $origin_dtz->getOffset($origin_dt) - $remote_dtz->getOffset($remote_dt);
		
		/** @note 아래의 코드는 위의 코드와 동일한 역활을 한다.
			다만 위 timezone 을 변경하지 않고 timezonze 클래스를 통해서 처리를 한다.
			어느 것이 효율적인지는 확인해 보지 않았다.
			
		$default_tz = date_default_timezone_get();
		date_default_timezone_set($remote_tz);
		$z1 = date("Z");
		date_default_timezone_set($origin_tz);
		$z2 = date("Z");
		date_default_timezone_set($default_tz);
		$offset = $z2 - $z1;
		*/
		
		
		return $offset;
	}
	
	/** @short return true if the stamp - $now is between $begin and $end
	*
	
	
	 *
	 *
	 */
	static function in ( $now, $begin, $end ) {
		if ( $now < $begin ) return false;
		else if ( $now > $end ) return false;
		else return true;
	}
}


class time extends stamp {
	const ONEDAY = 86400;
	
	
	
	
	
/** @short timezone array generated by etc/misc/tz.php
		http://work.org/x4/etc/misc/tz.php
		
 *  @code
 *  <?=time::$timezone[ etc::session_get('tz') ]?>
 *  @endcode
 *
 *	 @note 이 타임존 값 설정이 좀 어렵다. 컴퓨터 잘 맞지 않는 것 같다. 확실하게 체크를 해 볼 필요가 있다.
 *
 */
static $timezone = 
array(
'-12'=>'Etc/GMT+12',
 '-11'=>'US/Samoa',
 '-10'=>'US/Hawaii',
 '-9'=>'US/Aleutian',
 '-8'=>'US/Alaska',
 '-7'=>'US/Pacific-New',
 '-6'=>'US/Mountain',
 '-5'=>'US/Indiana-Starke',
 '-4'=>'US/Michigan',
 '-3'=>'Etc/GMT+3',
 '-2'=>'Etc/GMT+2',
 '-1'=>'Etc/GMT+1',
 '0'=>'UTC',
 '1'=>'Portugal',
 '2'=>'WET',
 '3'=>'Turkey',
 '4'=>'W-SU',
 '5'=>'Indian/Maldives',
 '6'=>'Asia/Dhaka',
 '7'=>'Indian/Christmas',
 '8'=>'Singapore',
 '9'=>'ROK',
 '10'=>'Pacific/Yap',
 '11'=>'Pacific/Ponape',
 '12'=>'Pacific/Wallis',
 '13'=>'Pacific/Tongatapu',
 '14'=>'Pacific/Kiritimati',
 );
 
 
/**
 *  @code
 *  <?=time::$timezone_country[ etc::session_get('tz') ]?>
 *  @endcode
 */
static $timezone_country = 
array(
'-12'=>'Etc/GMT+12',
 '-11'=>'US/Samoa',
 '-10'=>'US/Hawaii',
 '-9'=>'US/Aleutian',
 '-8'=>'US/Alaska',
 '-7'=>'US/Pacific-New',
 '-6'=>'US/Mountain',
 '-5'=>'US/Indiana-Starke',
 '-4'=>'US/Michigan',
 '-3'=>'Etc/GMT+3',
 '-2'=>'Etc/GMT+2',
 '-1'=>'Etc/GMT+1',
 '0'=>'UTC+0 Zulu',
 '1'=>'Portugal',
 '2'=>'WET',
 '3'=>'Turkey',
 '4'=>'W-SU',
 '5'=>'Indian/Maldives',
 '6'=>'Asia/Dhaka',
 '7'=>'Indian/Christmas',
 '8'=>'Philippiines, China, Singapore',
 '9'=>'Korea, Japan',
 '10'=>'Pacific/Yap',
 '11'=>'Pacific/Ponape',
 '12'=>'Pacific/Wallis',
 '13'=>'Pacific/Tongatapu',
 '14'=>'Pacific/Kiritimati',
 );
 




	/**
	 *  @short 일~토 까지 영어단어
	 *  
	 *  @note 숫자를 기반으로 날짜를 추출하는 방법
	 *  
	 *  	ucfirst(array_search( $d, time::$day ))
	 */
	static $day = array(
		'sunday'		=> 0,
		'monday'		=> 1,
		'tuesday'		=> 2,
		'wednesday'		=> 3,
		'thursday'		=> 4,
		'friday'		=> 5,
		'saturday'		=> 6,
	);
	
	
	static $month  = array(
		'1' => 'January',
		'2' => 'February',
		'3' => 'March',
		'4' => 'April',
		'5' => 'May',
		'6' => 'June',
		'7' => 'July',
		'8' => 'August',
		'9' => 'September',
		'10' => 'October',
		'11' => 'November',
		'12' => 'December'
	);
	
		
		
	static function display($s) { return self::elapsed($s); }
	static function elapsed( $stamp )
	{
		$period = NULL;
		$secsago   =   time() - $stamp;
		
		if ($secsago < 60) {
			$w1 = etc::multi_language(' second', '초전');
			$w2 = etc::multi_language(' seconds', '초전');
			$period = $secsago == 1 ? '1 ' . $w1    : $secsago . $w2 ;
		}
		else if ($secsago < 3600) {
			$w1 = etc::multi_language(' minute', ' 분전');
			$w2 = etc::multi_language(' minutes', ' 분전');
			$period    =   round($secsago/60);
			$period    =   $period == 1 ? '1' . $w1 : $period . $w2;
		}
		else if ($secsago < 86400) {
			$w1 = etc::multi_language(' hour', '시간전');
			$w2 = etc::multi_language(' hours', '시간전');
			$period    =   round($secsago/3600);
			$period    =   $period == 1 ? '1' . $w1   : $period .  $w1;
		}
		else if ($secsago < 604800) {
			$w1 = etc::multi_language(' day', '일전');
			$w2 = etc::multi_language(' days', '일전');
			$period    =   round($secsago/86400);
			$period    =   $period == 1 ? '1'. $w1    : $period . $w2;
		}
		else if ($secsago < 2419200) {
			$w1 = etc::multi_language(' week', '주전');
			$w2 = etc::multi_language(' weeks', '주전');
			$period    =   round($secsago/604800);
			$period    =   $period == 1 ? '1' . $w1   : $period . $w2;
		}
		else if ($secsago < 29030400) {
			$w1 = etc::multi_language(' month', '달전');
			$w2 = etc::multi_language(' months', '달전');
			$period    =   round($secsago/2419200);
			$period    =   $period == 1 ? '1' . $w1   : $period . $w2;
		}
		else {
			$w1 = etc::multi_language(' year', '년전');
			$w2 = etc::multi_language(' years', '년전');
			$period    =   round($secsago/29030400);
			$period    =   $period == 1 ? '1' . $w1   : $period . $w2;
		} 
		return $period;
	}
	
	
/**
 * 날짜 YYYYMMDD 형식의 8자리 숫자를 입력 받아 UNIX tImestamp 로 리턴한다.
 
 * @param $datetime YYYMMDD 의 값
 *
 * 주의 : 입력된 날의 첫 초(시작 초)를 가르킨다. 따라서 2012년 5월 1일까지의 unix time stamp 를 얻으려 한다면, 20120502 로 입력되어져야 한다.
 */
static function ymd($datetime) {
	
	if ( strlen($datetime) != 8 ) return false;

	$Y = substr($datetime, 0, 4);
	$m = substr($datetime, 4, 2);
	$d = substr($datetime, 6, 2);
	$stamp = @mktime(0,0,0,$m,$d,$Y);
	return $stamp; 
}

/** @short returns stamp of date-time which is formatted by YYYYMMDDHHIISS.
 *
 * @note it must be 14 digits.
 * @return unix timestamp
 * YYYYMMDDHHIISS 값을 받아서 stamp 로 리턴한다.
 *  converts human readable date to UNIX Timestamp which is formatted by YYYYMMDDHHIISS.
 * 
 * 스케쥴에서 수업 시간의 stamp 를 편하게 구할 수 있다.
 * @ex) 
    $stamp = date_to_stamp("$row[date]$row[class_start]00");
 */
static function ymdhis($datetime)
{
  $Y = substr($datetime, 0, 4);
  $m = substr($datetime, 4, 2);
  $d = substr($datetime, 6, 2);
  $h = substr($datetime, 8, 2);
  $i = substr($datetime, 10, 2);
  $s = substr($datetime, 12, 2);
  return mktime($h, $i, $s,$m,$d,$Y);
}

	/** 날자를 짧게 표시한다.
		오늘 이면, HH-ii 로 표시하고
		오늘이 아니면 Y-m-d 로 표시하도록 값을 리턴한다.
		*/
	static function short($stamp)
	{
		$Ymd = date("Ymd", $stamp);
		if ( $Ymd == date("Ymd") ) return date("H:i", $stamp);
		else return date('y-m-d', $stamp);
	}
	
	
	/** 입력한 $date 에 $add (일,날) 만큼 추가한다.
		@param $date YYYYMMDD
		@code
		$col_date = etc::date_add(date('Ymd'), 11);
		@endcode
	 */
	static function day_add($date, $add)
	{
		$yyyy = substr($date, 0, 4);
		$mm = substr($date, 4, 2);
		$dd = substr($date, 6, 2);
		$stamp = mktime(0,0,0,$mm,$dd,$yyyy) + 60 * 60 * 24 * $add;
		return date("Ymd", $stamp);
	}
	
	/** 입력된 stamp 에 맞는 요일을 리턴한다. 각 언어 설정 파일에서 언어가 설정되어져 있어야 한다.
	 *
	 * @code
		$day = time::day( time::ymd($post['int_1']) );
		@endcode
	 *
	 */
	static function day( $stamp )
	{
		return ln(date('D', $stamp));
	}
	
	
/** 입력된 UNIX TIME STAMP 가 토요일 또는 일요일이라면, 참을 리턴한다.
 *
 *
 */
	static function is_weekend($stamp)
	{
		$w = date("w", $stamp);
		if ( $w == 0 || $w == 6 ) return true;
	}
	
	/** UNIX TIME STAMP 를 입력하면, 그 stamp 가 속한 '주'의 첫째 날(일요일)의 새벽 0시, 0분, 0초의 stamp 를 리턴한다.
	 *
	 
		예)
			$stamp_week_begin = time::stamp_first_day_of_week( mktime(5, 5, 5, 12, 13, 2012 ) );
	
	
		예) 이번주 일요일 0시 부터 부터 토요일 밤 12 직전까지.
		
		
								$stamp_week_begin = time::stamp_first_day_of_week( );
								$stamp_week_end = $stamp_week_begin + time::ONEDAY * 7 - 1;
								echo date("Y-m-d, D, H:i:s A", $stamp_week_begin);
								echo " ~ ";
								echo date("Y-m-d, D, H:i:s A", $stamp_week_end);

		예) 이전주를 계산하려면 7일을 빼면 된다.
		
			$stamp = time() - time::ONEDAY * 7;							
			$stamp_week_begin = time::stamp_first_day_of_week( $stamp );
			$stamp_week_end = $stamp_week_begin + time::ONEDAY * 7 - 1;
			echo date("Y-m-d, D, H:i:s A", $stamp_week_begin);
			echo " ~ ";
			echo date("Y-m-d, D, H:i:s A", $stamp_week_end);
							
	
	/// 다음 주
	$stamp_1week = time::ONEDAY * 7;
	
	/// 다 다음 주
	$stamp_2week = time::ONEDAY * 7 * 2;
	
	echo "<p>";	
	echo date("Y-m-d, D, H:i:s A", $stamp_week_begin + $stamp_1week);
	echo " ~ ";
	echo date("Y-m-d, D, H:i:s A", $stamp_week_begin + $stamp_2week -1 );
	
	 *
	 */
	static function stamp_first_day_of_week($stamp=0)
	{
		if ( empty($stamp) ) $stamp = time();
		$w = date('w', $stamp);
		$stamp_sunday = $stamp - (self::ONEDAY * $w);
		
		return mktime(0, 0, 0, date('m', $stamp_sunday), date('d', $stamp_sunday), date('Y', $stamp_sunday));
	}
	static function stamp_sunday($stamp=0) { return self::stamp_first_day_of_week($stamp); }
	
	
	/** 날짜 형식이 맞는 값이라면, 참을 리턴한다.
		날짜 형식에는 아래와 같은 여러가지 형식일 수 있다.
			YYYY-MM-DD
			YYYY/MM/DD
			
		날짜 형식이 맞으면,
			array(YYYY,MM,DD) 로 값을 리턴한다.
	 */
	static function get_date($d)
	{
		if ( strlen($d) == 10 ) {
			if ( $d[4] == '-' && $d[7] == '-' ) {
				$d = str_replace('-', '', $d);
				if ( strlen($d) == 8 && is_numeric($d) ) {
					$y = substr($d, 0, 4);
					$m = substr($d, 4, 2);
					$d = substr($d, 6, 2);
					return array($y,$m,$d);
				}
			}
		}
		return array();
	}
	
	
	/** 입력된 stamp 에서 $n 일 수 만큼 지났으면 참을 리턴한다.
	 *
	 * @code 아래의 예제는 18 일이 지났으면 참을 리턴한다.
		time::past_days($p['stamp'], 18)
		@endcode
	 *
	 */
	static function past_days($stamp, $n)
	{
		return $stamp + time::ONEDAY * $n < time();
	}

	/** 시,분,초 두 가지를 입력 받아서, 그 차이(분)을 리턴한다.
	 *
	 * 
		@code 예제
			time::minutes(134000,140500);
		@endcode
		@return 분이 리턴된다. 몇 분의 차이가 나는지 도는 몇분이 남았는지, 몇분이 지났는지 등...
		
		
		@note 2014-07-24 버그 발견 : 밤 12 시를 포함하면 음수 값이 나온다.
		
			예를 들어서 1 만 분이면??
			
			밤 12 시를 기점으로 1 분이 지났는지, 하루 하고 1 분이 지났는지, 1 년하고 1분이 지났는지?
			
			하지만 이 버그는 수정하지 않는다. 사실 정말... 머리가 아픈 문제이다.
			
			다만, 규칙을 세운다.
			
			
	 */
	static function minutes($ahead, $behind) {
		$ss = self::his($ahead);
		$se = self::his($behind);
		/*
		*added by benjamin
		*if the time exceeds the day, it will give the wrong number of minutes
		*because the self::his() function does not add another day in case the time inputted makes a day go by.
		*For example at 11:00pm 08-13-14 and I have a 2h(120 mins) class then it means that 
		*the class will end at 1:00am 08-14-14. In the code above the variable
		*$ss ignores day changes making it seem like the end of class is at 1:00am 08-13-14
		*instead of 1:00am 08-14-14, resulting to a negative minute value.
		*/
			$ms = date("H", $ss) * 60 + date("i", $ss);
			$me = date("H", $se) * 60 + date("i", $se);
			
			if( $ss > $se ) {
				$days_passed = ceil( ( $ss-$se )/( 24 * 60 * 60 ) );				
				$me = $me + ( $days_passed * 24 * 60 );
			}
						
			/*
			*TO CHANGE THE CODE BACK TO THE ORIGINAL ONE:
			*Remove if( $ss > $se )code above.			
			*/			

		$mins = $me - $ms;
		return "$mins";
	}


	
/** 시,분,초 6자리를 입력 받아서 (현재 날짜 기준) unix time stamp 로 리턴을 한다.
 *
 * @code
		his("131016");
	@endcode
	
	@return "시분초" 6 자리에 해당하는 오늘 "년/월/일"의 stamp 값을 리턴한다.
		즉, 오늘 날짜의 "시분초"를 리턴하는 것이다.
 */
	static function his($datetime)
	{
		if ( strlen($datetime) != 6 ) return false;
		$h = substr($datetime, 0, 2);
		$i = substr($datetime, 2, 2);
		$s = substr($datetime, 4, 2);
		$Y = date("Y");
		$m = date("m");
		$d = date("d");
		$stamp = mktime($h, $i, $s,$m,$d,$Y);
		return $stamp;
	}
	/** '시분' 4자리 숫자를 입력 받아서, 오늘 날자의 stamp 를 리턴한다.
	 *
	 * @code
		time::hi($schedule['class_begin'])
		@endcode
		
	 */
	static function hi($hi)
	{
		return self::his($hi.'00');
	}
	
	


	
	/** 8자리 숫자의 입력 값을 날짜 형식으로 변환해서 리턴한다.
	 *
		@code 예제
			fv_date("20131019"); /// 리턴 값은 2013-10-19 이다.
		@endcode
	 *
	 *
	 */
	static function fv_date($date)
	{
		return preg_replace("/(\d{4})(\d{2})(\d{2})/", "$1-$2-$3", "$date");
	}
	
	
	
	

/** @short returns 사용자 로컬 시간대의 4자리 '시분'의 값을 입력 받아서 UTC 값으로 리턴한다.
 * https://docs.google.com/a/withcenter.com/document/d/19xSd2ChA2QO1_ty3A6QMgsuOWLcjZl9EUDG-G8Im4z4/edit#heading=h.x417j0wzpp35
 *
 * it gets time value like '1234' as "Hour & Minute" and returns converted time value of UTC+0.
	for example '1234' has input and the user time is Manila, then it will return '0434'.
	
 *
 * @note
	입력 값으로 4자리 수 '1234' (12시 34분) 와 같이 값을 입력 받는다.
	입력 값은 로컬 시간의 시/분이며 이를 UTC+0 시간으로 변경해서 리턴한다.
	예를 들어 마닐라 시간으로 '1234' 를 입력했으면, 리턴값은 '0434' 가 된다.
	
 * @attention
 
	이 값을 DB 에 저장한 다음 보여 줄 때에는 UTC+0 의 시간을 적절한 로컬 시간으로 변환해서 보여주어야 한다.
	
	이 역활을 하는 함수가 local_hi() 이다.
	
	
 * @code
		time::utc_hi("$in[begin_hour]$in[begin_minute]");
	@endcode
		
 */
	static function utc_hi($hi)
	{
		$local_stamp = self::hi($hi);				/// 입력받은 로컬 시간의 시/분을 stamp 로 변환한다.
		
		debug("utc_hi(): local stamp: " . date('r', $local_stamp));
		
		$utc_stamp = self::utc($local_stamp);		/// 로컬 시간의 stamp 를 UTC+0 의 stmap 로 변환한다.
		
		debug("utc_hi(): UTC stamp: " . date('r', $utc_stamp));
		
		$new_hi = date("Hi", $utc_stamp);
		return $new_hi;
		
	}
	
	/** @short 로컬 요일을 UTC 요일로 변경해서 리턴한다.
	 *  
	 *  
	 *  https://docs.google.com/a/withcenter.com/document/d/16vwz4I74vDkfJ3oYHl5YiHxaoAFuLD3CzCbbXZuADo0/edit#heading=h.ds9g1ebhh6ov
	 *  
	 *  
	 *  @code
	 *  
			$local_timezone = '5';						/// 특정 타임존으로 변경 ( 예: UTC+0500 )
			time::timezone_set( $local_timezone );		
			$hi = "0200";								/// 특정 타임존의 로컬 시/분. 이 로컬 시간에 뭘 하겠다. UTC+0 시간은 다른 시간. 특정타임존이 음수이면, 이 값에 타임존 offset 을 더해야 UTC+0 이 된다.
			$local_stamp = time::hi( $hi );				/// 로컬 stamp 로 변경,
			echo date('r', $local_stamp) . "\n";		/// 로컬 시간 출력
			echo "time::utc_day: " . time::utc_day( $hi, 'thursday' ) . "\n";		/// 이 타임존의 (로컬 시간) 요일에 뭘 하겠다. UTC+0 으로 바꾸면 무슨 요일?

		@endcode
	 *	
	 
	 *  
	 *  @param $local_hi '시/분' 4자리 값을 입력하는데, UTC+0 의 값이 아닌, 로컬 timezone 의 값이어야 한다.
	 *  @param $l ( lowercase of 'L' ) 은 monday 와 같이 전체 요일 단어가 들어와야 한다.
	 */
	static function utc_day( $local_hi, $l )
	{
		$local_stamp = time::hi( $local_hi );
										//echo "INPUT DAY : $l\n";
										//echo "local_stamp r : " . date('r', $local_stamp) . "\n";
		$w = date( 'w', $local_stamp );
										//echo "day of week: $w\n";
										//echo "input day of week : " . time::$day[$l] . "\n";
		$day_diff = time::$day[$l] - $w;		
										//echo "day of diff : $day_diff\n";
		$local_stamp += time::ONEDAY * $day_diff;
										// echo "local_stamp r : " . date('r', $local_stamp) . "\n";
		$utc_stamp = time::utc_stamp( $local_stamp );
		$l3 = strtolower( date('l', $utc_stamp ) );
										// echo "RETURN DAY: $l3\n";
		return $l3;
	}
	
	
	/**
	 *  @note UTC+0 의 요일을
	 *  
	 *  로컬 시간으로 변경했을 때, 무슨 요일인지를 리턴한다.
	 *  
	 *  utc_day() 의 반대 역활을 하는 함수 이다.
	 *  
	 *  https://docs.google.com/a/withcenter.com/document/d/16vwz4I74vDkfJ3oYHl5YiHxaoAFuLD3CzCbbXZuADo0/edit#heading=h.ds9g1ebhh6ov
	 *  
	 */
	static function local_day( $utc_hi, $l )
	{
		$utc_stamp = time::hi( $utc_hi );
		$w = date( 'w', $utc_stamp );
		$day_diff = time::$day[$l] - $w;		
		$utc_stamp += time::ONEDAY * $day_diff;
		$local_stamp = time::local_stamp( $utc_stamp );
		$l3 = strtolower( date('l', $local_stamp ) );
		return $l3;
	}
	
	
	/** @short 
	 *  
	 *  UTC 0 에 해당하는 4 자리 '시분'을 입력받아서
	 *  
	 *  로컬 타임의 '시분'으로 변환 한 다음 리턴한다.
	 
		예를 들어, UTC+0 의 '0000' 을 입력하면,

		현재 시간대의 타임 존을 알 수 있다.
		
		한국이면 '0900' 이 리턴되고,
		
		필리핀이면 '0800' 이 리턴된다.
		
	 
		utc_hi() 의 반대 함수이다.
		
		
		https://docs.google.com/a/withcenter.com/document/d/19xSd2ChA2QO1_ty3A6QMgsuOWLcjZl9EUDG-G8Im4z4/edit#heading=h.dlxmodm1kkv0
	 */
	static function local_hi( $hi )
	{
		$utc_stamp = self::hi($hi);
		return self::local_date('Hi', $utc_stamp);
	}
	
	/**
	 * local_hi() 의 결과에서 시와 분을 두개의 요소로 나누어서 리턴한다.
	 */
	static function local_hi_list( $hi )
	{
		$local_hi = self::local_hi( $hi );
		$ret = array();
		$ret[] = substr( $local_hi, 0, 2);
		$ret[] = substr( $local_hi, 2, 2);
		return $ret;
	}
	
	static function utc_timezone_hi($hi)
	{
		return self::local_hi( $hi );
	}
	static function utc_timezone_hi_list( $hi )
	{
		return self::local_hi_list( $hi );
	}
	
	
	
	
	
	
	/** @short UTC 0 stamp 를 입력 받아서, local 
		
		즉, 입력되는 $stamp 는 UTC 0 의 값이어야 하며,
		
			리턴되는 값은 UTC 0 의 값이 아닌, UTC+9 등의 사용자 시간 값으로 처리되어서 리턴된다.
		
			만약, UTC 0 의 값이 필요하다면, 그냥 아래와 같이 UTC 0 으로 date() 함수를 호출해야 한다.
		
			date("...", UTC-0-STAMP)
			
	 @note 만약 Local Time Zone 의 값을 입력 받아서 UTC 로 변환을 해야한다면,
		이 함수를 쓰면 안된다.
		
		만약 사용자 시간이 +9 이라면, UTC +0 에서 +9 더한 UTC+9 이 리턴된다.
		
		UTC+9 을 입력 받아서 UTC+0 의 값을 저장해야한다면, offset 을 더하는 것이 아닌 빼주는 것을 해야 한다.
		
		
		
	 * returns the date of user local time zone.
	 
	 * https://docs.google.com/a/withcenter.com/document/d/19xSd2ChA2QO1_ty3A6QMgsuOWLcjZl9EUDG-G8Im4z4/edit#heading=h.ocbal64a2rtv
	 * 
	 * @param $format date() 함수와 동일하다.
	 *
	 * @param $stamp UTC UNIX TIMESTAMP 이다.
	 
		만약 이 값이 지정되지 않았다면, 현재 time() 의 값을 사용하며,
		
		
	 * @param $tz 표현 할 시간대의 timezone 이다. 생략하면 사용자의 timezone 으로 표현한다.
	 
	 
	 @주의 : 이 함수는 그냥 date 함수와는 틀리다.
	 
	 UTC+0 의 stamp 값을 입력 받아서, date() 에 그대로 적용하면, UTC+0 에 해당하는 현재 날짜값이 나온다.
	 
	이 함수는 UTC+0 의 stamp 값에 현재 timezone 의 offset 을 구해서 그 차를 계산하여 date 결과를 리턴한다.
	 
	
		
		
	 *
	 */

	static function local_date( $format, $stamp = 0, $tz = null )
	{
		if ( empty($stamp) ) $stamp = time();
		if ( empty($tz) ) $tz = date_default_timezone_get();
		$off = time::offset('UTC', $tz);
		return date($format, $stamp + $off );
	}
	
	

	/** 시, 분 그리고 추가 할 분을 입력 받아서 "HHII" 형태로 리턴한다.
	 *
	 * @note
		시간 값에 특정 시간을 추가 할 경우 사용하면 된다.
		60 분 단위로 시와 분이 바뀐다.
		
	 *
	 * @code 예를 들어
			mk_Hi(10, 20, 50) 으로 입력하면 시간 값을 올리고 분을 다시 계산하여 "1110" 으로 리턴한다.
	 * @endcode
	 
		밤 12시를 가로 지르게 되면 "0010" 와 같이 리턴 될 수 있다.

	 * returns hours and minutes and adds the last parameter.
	 * @param integer $h - hour
	 * @param integer $i - min
	 * @param integer $p - additional minutes
	 */
	static function add_minute($h, $i, $p)
	{
		return date("Hi", mktime($h, $i + $p));
	}
	/** @short 특정 날짜에 일을 추가해서 리턴한다.
	 * @note
			YYYYMMDD 형태의 8 자리 숫자 값을 입력 받아서
			$day 만큼 일 수를 추가한 다음
			YYYYMMDD 형태의 값으로 리턴한다.
			
	 *
	 *
	 */
	static function add_day($date, $day)
	{
		return date('Ymd', self::ymd($date) + time::ONEDAY * $day );
	}
	
	/** @short 'YYYYMMDD' 값을 입력 받아서 보기 좋게 출력을 해 준다.
	 *
	 */
	static function date_display($Ymd)
	{
		return date("Y-m-d", self::Ymd($Ymd));
	}
	/** @short '시분', 'HHII' 값을 입력 받아서 보기 좋게 출력을 해 준다.
	 *
	 */
	static function Hi_display($Hi)
	{
		return date("h:i a", self::Hi($Hi));
	}
	
	
	
	/**
	 *  
	 *  timezone 설정을 한다.
	 *  
	 *  
	 *  @code 아래와 같이 숫자로 입력 할 때에는 따옴표로 감싸야 한다.
	 * 	 time::timezone_set( '9.5' );
	 *  @code
	 */
	static function timezone_set( $tz )
	{
		if ( is_numeric( $tz ) ) {
			$tz = self::$timezone[$tz];
		}
		date_default_timezone_set( $tz );
		debug("time::timezone_set( $tz )");
	}
	
	
	/**
	 *  
	 *  
	 *  @short 특정 주의 특정 일의 날짜 포멧을 리턴한다.
	 *  
	 *  예: 지난 주의 마지막 요일 날짜 포멧.
	 *  
	 *  예: 지지난 주의 화요일 날짜 포멧.
	 *	

		@param $format date('...') 에 사용되는 날짜 포멧
		
		@param $day 0 ~ 6 까지, 일~토
		
		@param $stmap stamp 값으로 특정 주를 가르킨다.
		
		
		
	 *  
	 *  time::day_of_week('Ymd', 6, time() - time::ONEDAY * 7 )
	 *  
	 *  @code 이번주 일요일을 구하는 방법
	 *  	di ( time::day_of_week() );
	 *  @endcode
	 *  
	 *  @code 이전주 값을 구하는 방법
	 *  	$last_week = time::day_of_week('Ymd', 0, time() - time::ONEDAY * 7 ) . '-' . time::day_of_week('Ymd', 6, time() - time::ONEDAY * 7 );
	 *  @endcode
	 *  
	 *  @code 위 주(이 전주)의 이전주를 구하는 방법
	 *  	$pre_week = time::day_of_week('Ymd', 0, $week_stamp - time::ONEDAY * 7 ) . '-' . time::day_of_week('Ymd', 6, $week_stamp - time::ONEDAY * 7 );
	 *  @endcde
	 *  
	 *  @code 주의 시작(일요일)과 끝(토요일)을 구하는 방법
			$begin_day_of_next_week_of_last_paid = time::day_of_week('Ymd', 0, $last_begin_stamp + time::ONEDAY * 7 )
			$end_day_of_next_week_of_last_paid = time::day_of_week('Ymd', 6, $last_begin_stamp + time::ONEDAY * 7 )
		@endcode
		
		@code 특정 날짜의 다음 주를 구하는 방법
			$stamp = time::ymd($begin);
			return time::day_of_week('Ymd', 0, $stamp + time::ONEDAY * 7 ) . '-' . time::day_of_week('Ymd', 6, $stamp + time::ONEDAY * 7 );
		@endcode
	 */
	static function day_of_week( $format='Ymd', $day=0, $stamp=0 )
	{
		if ( empty($stamp) ) $stamp = time();
		
		$w = date('w', $stamp);
		return date( $format, $stamp - ( time::ONEDAY * $w ) + ( time::ONEDAY * $day ) );
	}
	
	static function first_day_of_last_week($format='Ymd', $base=0)
	{
		if ( empty($base) ) $base = time();
		return date( $format, $base - 7 * 60 * 60 * 24);
	}
	static function first_day_of_next_week($format='Ymd', $base=0)
	{
		if ( empty($base) ) $base = time();
		return date( $format, $base + 7 * 60 * 60 * 24);
	}

	/**
	 *  @short 이전 달의 1일의 stamp 로 date('...') 포멧 형식으로 리턴한다.
	 */
	static function first_day_of_last_month($format='Ymd', $base=0)
	{
		if ( empty($base) ) $base = time();
		return date( $format, mktime( 0, 0, 0, date('m',$base)-1, 1, date('Y',$base) ) );
	}
	
	/**
	 *  @short 입력된 stamp 의 다음 달(1 일을 기준)의 date('...') 포멧으로 리턴한다.
	 *  
	 *  @code
	 *  	di(time::first_day_of_next_month('r',time::ymd("{$last_paid_month}01")));
	 *  @endcode
	 */
	static function first_day_of_next_month($format='Ymd', $base=0)
	{
		if ( empty($base) ) $base = time();
		return date( $format, mktime( 0, 0, 0, date('m',$base)+1, 1, date('Y',$base) ) );
	}
	
	
	
} // eo time class

/**
 *  @short is_bot() checks if the client is a robot.
 *  
 *  @return true if the client is a robot.
 */
function is_bot($user_agent) {
	return preg_match('/(abot|dbot|ebot|hbot|kbot|lbot|mbot|nbot|obot|pbot|rbot|sbot|tbot|vbot|ybot|zbot|bot\.|bot\/|_bot|\.bot|\/bot|\-bot|\:bot|\(bot|crawl|slurp|spider|seek|accoona|acoon|adressendeutschland|ah\-ha\.com|ahoy|altavista|ananzi|anthill|appie|arachnophilia|arale|araneo|aranha|architext|aretha|arks|asterias|atlocal|atn|atomz|augurfind|backrub|bannana_bot|baypup|bdfetch|big brother|biglotron|bjaaland|blackwidow|blaiz|blog|blo\.|bloodhound|boitho|booch|bradley|butterfly|calif|cassandra|ccubee|cfetch|charlotte|churl|cienciaficcion|cmc|collective|comagent|combine|computingsite|csci|curl|cusco|daumoa|deepindex|delorie|depspid|deweb|die blinde kuh|digger|ditto|dmoz|docomo|download express|dtaagent|dwcp|ebiness|ebingbong|e\-collector|ejupiter|emacs\-w3 search engine|esther|evliya celebi|ezresult|falcon|felix ide|ferret|fetchrover|fido|findlinks|fireball|fish search|fouineur|funnelweb|gazz|gcreep|genieknows|getterroboplus|geturl|glx|goforit|golem|grabber|grapnel|gralon|griffon|gromit|grub|gulliver|hamahakki|harvest|havindex|helix|heritrix|hku www octopus|homerweb|htdig|html index|html_analyzer|htmlgobble|hubater|hyper\-decontextualizer|ia_archiver|ibm_planetwide|ichiro|iconsurf|iltrovatore|image\.kapsi\.net|imagelock|incywincy|indexer|infobee|informant|ingrid|inktomisearch\.com|inspector web|intelliagent|internet shinchakubin|ip3000|iron33|israeli\-search|ivia|jack|jakarta|javabee|jetbot|jumpstation|katipo|kdd\-explorer|kilroy|knowledge|kototoi|kretrieve|labelgrabber|lachesis|larbin|legs|libwww|linkalarm|link validator|linkscan|lockon|lwp|lycos|magpie|mantraagent|mapoftheinternet|marvin\/|mattie|mediafox|mediapartners|mercator|merzscope|microsoft url control|minirank|miva|mj12|mnogosearch|moget|monster|moose|motor|multitext|muncher|muscatferret|mwd\.search|myweb|najdi|nameprotect|nationaldirectory|nazilla|ncsa beta|nec\-meshexplorer|nederland\.zoek|netcarta webmap engine|netmechanic|netresearchserver|netscoop|newscan\-online|nhse|nokia6682\/|nomad|noyona|nutch|nzexplorer|objectssearch|occam|omni|open text|openfind|openintelligencedata|orb search|osis\-project|pack rat|pageboy|pagebull|page_verifier|panscient|parasite|partnersite|patric|pear\.|pegasus|peregrinator|pgp key agent|phantom|phpdig|picosearch|piltdownman|pimptrain|pinpoint|pioneer|piranha|plumtreewebaccessor|pogodak|poirot|pompos|poppelsdorf|poppi|popular iconoclast|psycheclone|publisher|python|rambler|raven search|roach|road runner|roadhouse|robbie|robofox|robozilla|rules|salty|sbider|scooter|scoutjet|scrubby|search\.|searchprocess|semanticdiscovery|senrigan|sg\-scout|shai\'hulud|shark|shopwiki|sidewinder|sift|silk|simmany|site searcher|site valet|sitetech\-rover|skymob\.com|sleek|smartwit|sna\-|snappy|snooper|sohu|speedfind|sphere|sphider|spinner|spyder|steeler\/|suke|suntek|supersnooper|surfnomore|sven|sygol|szukacz|tach black widow|tarantula|templeton|\/teoma|t\-h\-u\-n\-d\-e\-r\-s\-t\-o\-n\-e|theophrastus|titan|titin|tkwww|toutatis|t\-rex|tutorgig|twiceler|twisted|ucsd|udmsearch|url check|updated|vagabondo|valkyrie|verticrawl|victoria|vision\-search|volcano|voyager\/|voyager\-hc|w3c_validator|w3m2|w3mir|walker|wallpaper|wanderer|wauuu|wavefire|web core|web hopper|web wombat|webbandit|webcatcher|webcopy|webfoot|weblayers|weblinker|weblog monitor|webmirror|webmonkey|webquest|webreaper|websitepulse|websnarf|webstolperer|webvac|webwalk|webwatch|webwombat|webzinger|wget|whizbang|whowhere|wild ferret|worldlight|wwwc|wwwster|xenu|xget|xift|xirq|yandex|yanga|yeti|yodao|zao\/|zippp|zyborg|\.\.\.\.)/i', $user_agent);
}





function di( $v )
{
	echo "<xmp>";
		print_r($v);
	echo "</xmp>";
}


// 자바 스크립트의 alert 에서 출력이 가능한 메세지를 만든다.
// 라인피드, 쌍따옴표 등을 ESCAPE 한다.
	function jsmessage($msg)
	{

  /*
  $msg_en = "PhilGO.COM";
  $msg_ko = "필고";
  
  $h = ln($msg_ko, $msg_en);
  
  $msg = "$h\r\n\r\n$msg";
  */
  

	$msg = str_replace("\\", "\\\\", $msg);
	$msg = str_replace("\n", "\\n", $msg);
	$msg = str_replace("\r", "\\r", $msg);
	$msg = str_replace("\"", "'", $msg);


	return $msg;
}
function html_header()
{
	echo<<<EOH
<!DOCTYPE HTML>
<html>
<head>
	<meta charset="utf-8">
</head>
<body>
EOH;
}


/** 
 */
function jsGo($url, $message=null, $target=null)
{
		if ( $message ) $message = jsmessage($message);
		echo html_header();
		$out = "<script>";
		if ( $message ) $out.= "alert(\"$message\");";
		if ( $target ) $target = "$target.";
		$out.= "
			{$target}location.href='$url';
			</script>
		";
		//debug_log($out);
		echo $out;
}

/** 
 */
function jsBack($message=null)
{

	echo html_header();
	if ( $message ) $message = jsmessage($message);
	echo "<script>";
	if ( $message ) echo "alert(\"$message\");";
	echo "
		history.go(-1);
		</script>
	";
}

function jsAlert($message=null)
{
	if ( $message ) $message = jsmessage($message);
	if ( $message ) {
		echo "<script>";
		echo "alert(\"$message\");";
		echo "</script>";
	}
}

function jsReload($target='')
{
	if ( $target ) {
		$target .= '.';
	}
	echo "
	
		<script>
			{$target}location.reload(true);
		</script>
	";
	
}




function session_get($k)
{
	if ( isset($_COOKIE[$k]) ) return $_COOKIE[$k];
	else return NULL;	
}

/** @brief 쿠키에 값을 저장한다.
 * @note
	NULL 이나 공백, empty, 0 의 값을 집어 넣을 수 있다.
	@param $domain 쿠키를 저장 할 도메인
	@attention 만약 이 $domain 값이 NULL 로 입력되면 기본적으로 모든 2차(하위)도메인에서 쿠키 값이 공유가 되도록 쿠키를 저장한다.
		
 */
function session_set($k, $v=NULL, $exp=0, $domain=NULL)
{
	$dir = '/';
	if ( empty($domain) ) $domain = domain_name();
	
	/// cookie for that domain olnly
	setcookie($k, $v, $exp, $dir, $domain);
	
	/// cookie for all sub domain.
	$top_domain = base_domain($domain);
	$sub_domain = ".$top_domain";
	setcookie($k, $v, $exp, $dir, $sub_domain);
	
 }

function session_delete($k, $domain=NULL)
{
	$dir = '/';
	if ( empty($domain) ) $domain = domain_name();
	setcookie($k, NULL, time()-3600*24*30, $dir, "$domain");
	
	
	
	$top_domain = base_domain($domain);
	$sub_domain = ".$top_domain";
	setcookie($k, NULL, time()-3600*24*30, $dir, $sub_domain);
	
}



	
	/**
	 * @short 도메인을 소문자로 리턴한다.
	 *
	 * 2차, 3차, 4차 도메인을 인정한다.
	 리턴 값 예:
		abc.123.456.com
		www.abc.com
		abc.com
		
	 */

	function domain_name()
	{
		if ( isset( $_SERVER['HTTP_HOST'] ) ) {
			$domain = $_SERVER['HTTP_HOST'];
			$domain = strtolower($domain);
			return $domain;
		}
		else return NULL;
	}
	
	
	
		/** @short returns a UNIQUE ID
		알아보기 어려운 고유 번호를 리턴한다.
	*/
	function uniq_id()
	{
		return md5(uniqid(rand(), true) . client_ip());
	}
	
	function client_ip()
	{
		if ( isset($_SERVER['REMOTE_ADDR']) ) return $_SERVER['REMOTE_ADDR'];
		else return NULL;
	}

	
	
	
/**
 *  ------------------- file class ----------------------
 */
 
/**
 *
 *
 * @warning PHP 5.3 and above needed to make all methods function. 
 *
 */
class file {
	const NO_UPLOAD_FILE = -1113;
	const FILE_NOT_FOUND	= -403216;
	const FILE_SIZE_ZERO = -1110;
	const FILE_SIZE_TOO_LARGE = -1111;
	const CANNOT_MOVE_UPLOAD_FILE = - 1112;
	const UPLOAD_FAILED = -1114;
	const THUMBNAIL_FAILED_ON_ORG_IMAGE = -1115;
	const THUMBNAIL_FAILED = - 1116;
	const UPLOAD_OK = 200;
	static $error_message;									/// 에러가 있으면 이 변수에 기록이 된다.
	
	
	
	/**
	 *  @brief returns the content of the file.
	 *  
	 *  @param [in] $filename file path
	 *  @return string file content
	 *  FILE_NOT_FOUND if there is not file by $filename
	 *  
	 *  @details The code of this function is from PHP doc.
	 *  @warning the return value has changed since jan 23, 2014
	 *  @code
	 *  	$data = file::read($dir_root . '/head.sub2.php');
	 *  	if ( empty($data) &&  $global_file_error_code == file::FILE_NOT_FOUND ) return $data;
	 *  @endcode
	 */
	static function read($filename)
	{
		global $global_file_error_code;
		@$handle = fopen($filename, "rb");
		if ( ! $handle ) {
			$global_file_error_code = self::FILE_NOT_FOUND;
			return null;
		}
		$contents = fread($handle, filesize($filename));
		fclose($handle);
		return $contents;
	}
	/**
	 *  @brief append some content into file
	 *  
	 *  @param [in] $filename file name(path) to append
	 *  @param [in] $somecontent some content to append
	 *  @return 0 if success otherwise false.
	 *  
	 *  @details This code is coming from PHP document.
	 */
	static function append($filename, $somecontent)
	{
			// In our example we're opening $filename in append mode.
			// The file pointer is at the bottom of the file hence
			// that's where $somecontent will go when we fwrite() it.
			if (!$handle = fopen($filename, 'a')) {
				 return -1;
			}

			// Write $somecontent to our opened file.
			if (fwrite($handle, $somecontent) === FALSE) {
				return -2;
			}

			// echo "Success, wrote ($somecontent) to file ($filename)";

			fclose($handle);
			return 0;
	}
	
	/**
	 *  @brief Overwrite to a file.
	 *  
	 *  @param [in] $filename file path
	 *  @param [in] $somecontent file data to save
	 *  @return 0 if success
	 *  
	 *  @details This code is coming from PHP Doc
	 */
	static function write($filename, $somecontent)
	{
		// Let's make sure the file exists and is writable first.
		
			if (!$handle = fopen($filename, 'wb')) {
				return -1;
			}
			// Write $somecontent to our opened file.
			if (fwrite($handle, $somecontent) === FALSE) {
				return -2;
			}
			fclose($handle);
			return 0;
			
	}
	
	
	/** @short returns file list in an array.
	 *
	 * @param [in] $re if set true, then it searches recursively.
	 * @param [in] $dir directory path
	 @code
	 
		di( getFiles($dir, false) );

	@endcode

		@code how to use pattern
			$files = file::getFiles(DIR_MODULE, true, "/.php/");
		@endcode
		
		@code
			$files = file::getFiles(DIR_MODULE, true, "/admin\.menu\.php/");
		@endcode
		
		@code
			$files = file::getFiles( x::dir() . '/skin/latest', true, "/preview\.png/");
			di($files);
		@code
		

	 */
 
	static function getFiles($dir, $re=true, $pattern=null)
	{
		
		$tmp = array();
		if ($handle = opendir($dir)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$file_path = $dir . DIRECTORY_SEPARATOR . $file;
					if ( is_dir($file_path) ) {
						if ( $re ) {
							$tmp2 = self::getFiles($file_path, $re, $pattern);
							if ( $tmp2 ) $tmp = array_merge($tmp, $tmp2);
						}
					}
					else {
						if ( $pattern ) {
							if ( preg_match($pattern, $file) ) {
							}
							else continue;
						}
						array_push($tmp, $dir . DIRECTORY_SEPARATOR . $file);
					}
				}
			}
			closedir($handle);
			return $tmp;
		}
	}

	
	

	/**
	 *  @brief returns directories (of a directory)
	 *  
	 *  @param [in] $directory is a path(folder) to get directory list inside of it.
	 *  @return array.
	 *  
	 *  @details returns directoris. It does not search recursively.
	 *  @code
	 *  	$dirs = file::getDirs(DIR_THEME);
	 *  @endcode
	 *  @code
			$dirs = file::getDirs( x::dir() . '/skin/latest' );
			di( $dirs );
	 *	 @endcode
	 */
	static function getDirs($directory) {

		// Create an array for all files found
		$tmp = Array();

		// Try to open the directory
		if($dir = opendir($directory)) {
			
			// read the files
			while($file = readdir($dir)) {
				// Make sure the file exists
				if($file != "." && $file != ".." && $file[0] != '.') {
					// If it's a directiry, 
					if(is_dir($directory . "/" . $file))
					{
						$tmp[] = $file;
					}
				}
			}
			
			// Finish off the function
			closedir($dir);
			return $tmp;
		}
	}

	/** @short Deletes all files in a folder and its sub folders.
	 *
	 *
		@code
			file::delete_folder($folder);
		@endcode
	 */
	/*
	static function delete_folder($folder)
	{
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
			$path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
			// msg("Deleting: " . $path->getPathname());
		}
		rmdir($folder);
	}
	*/
	static function delete_folder($dirPath) {
    if (is_dir($dirPath)) {
        $objects = scandir($dirPath);
        foreach ($objects as $object) {
            if ($object != "." && $object !="..") {
                if (filetype($dirPath . DIRECTORY_SEPARATOR . $object) == "dir") {
                    self::delete_folder($dirPath . DIRECTORY_SEPARATOR . $object);
                } else {
                    unlink($dirPath . DIRECTORY_SEPARATOR . $object);
                }
            }
        }
    reset($objects);
    rmdir($dirPath);
    }
}

	
	
	/** @brief 파일을 검색해서 리턴한다.
		재귀 함수를 사용하지 않는다.
	 *
	 * @param $dir 검색할 디렉토리
	 * @param $pattern 검색할 regular expression pattern.
	 * if $pattern is ommited, then it returns all files under $dir foler or else it does pattern matches.
	 *
	 * @return 패턴 매칭 결과를 리턴한다.
	 * @example
		$ret = file::files(PATH_CORE.'/module/message',  "list.(.*).php");
		$opt_skin = array();
		foreach ( $ret as $re ) {
			$opt_skin[] = $re[1];
		}	
	 */
	static function files($dir, $pattern=null)
	{
		$files = file::getFiles($dir, false);
		$ret = array();
		foreach ( $files as $file ) {
			if ( empty($pattern) ) $ret[] = $file;
			else {
				if ( preg_match("/$pattern/", $file, $m) ) {
					$ret[] = $file;
				}
			}
		}
		return $ret;
	}
	
	/** @short returns file list of a folder and its subfolders.
	 *
	 */
	static function get_files_recursively($dir)
	{
		$ret = array();
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($folder, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
			// $path->isFile() ? unlink($path->getPathname()) : rmdir($path->getPathname());
			$ret[] = $path->getPathname();
		}
		return $ret;
	}
	
	
	
	/** @short copies a directory and its subfolders and all contents.
	 *
	 *
	 * @code
			file::recursive_copy("src-directory", "dst-directoy");
			file::recursive_copy($src, $target_folder);
		@endcode
		
	 * @todo make it option to pass some files.
	 */
	static function recursive_copy($src, $dst)
	{
		$sp = DIRECTORY_SEPARATOR;
		foreach(new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src, FilesystemIterator::SKIP_DOTS), RecursiveIteratorIterator::CHILD_FIRST) as $path) {
			$file = $path->getPathname();
			$pi = pathinfo($file);
			// make it option.
			if ( preg_match("/^\./", $pi['filename']) ) continue;
			if ( preg_match("/CVS/", $file) ) continue;
			
				if ( $path->isFile() ) {
					// msg("file: $file");
					$dir = "$dst{$sp}$pi[dirname]";
					if ( file_exists($dir) ) {
					}
					else {
						mkdir($dir, 0777, true);
					}
					$fp = "$dst$sp$file";
					// msg("To:$dst$sp$file");
					copy($file, $dst . $sp . $file);
				}
				
		}
	}
	/** 
	* Add files and sub-directories in a folder to zip file. 
	* @param string $folder 
	* @param ZipArchive $zipFile 
	* @param int $exclusiveLength Number of text to be exclusived from the file path. 
	*/ 
	private static function folderToZip($folder, &$zipFile, $exclusiveLength) {
		$handle = opendir($folder);
		while (false !== $f = readdir($handle)) {
			if ($f != '.' && $f != '..') {
				$filePath = "$folder/$f";
				// Remove prefix from file path before add to zip.
				$localPath = substr($filePath, $exclusiveLength);
				if (is_file($filePath)) {
					$zipFile->addFile($filePath, $localPath);
				}
				else if (is_dir($filePath)) {
					// Add sub-directory.
					$zipFile->addEmptyDir($localPath);
					self::folderToZip($filePath, $zipFile, $exclusiveLength);
				}
			}
		}
		closedir($handle);
	} 
	/** @short zips a folder (including the folder name itself)
	 * @warning Be sure you are not saving the zip file into the zip folder. Save the zip file outside of the folder that are zipped up.
	 * @주의 : 압축 파일을 저장 할 때, 압축하는 폴더 안에 저장하지 않도록 주의 한다.
	 * @code The zip file to be created must be outside of the folder being zipped up.
		$zip_path	= "$dir_tmp/x-$version.zip";
		file::zipDir($dir_x, $zip_path);
		@endcode
	* 
	* @param string $sourcePath Path of directory to be zip. 
	* @param string $outZipPath Path of output zip file. 
	* @code
		file::zipDir($working_directory, "$target_directory{$sp}sapcms-1.2.2.zip");
		@endcode
	*/ 
	static function zipDir($sourcePath, $outZipPath)
	{
		$pathInfo = pathInfo($sourcePath);
		$parentPath = $pathInfo['dirname'];
		$dirName = $pathInfo['basename'];
		$z = new ZipArchive();
		$z->open($outZipPath, ZIPARCHIVE::CREATE);
		$z->addEmptyDir($dirName);
		self::folderToZip($sourcePath, $z, strlen("$parentPath/"));
		$z->close();
	}
	/** @short it does tar and gzip a folder and its sub folders with all the content.
	 *
	 * @note use this function when you need to create 'tar' with 'gz' file on a folder.
	 *
		@code
			file::tarGzipDir("/tmp/abc", "/tmp/abc.tar", "1.2.3.tar.gz");
		@endcode
		@param $working_direcoty is the source files folder
		@param $tar is a path of tar file which will tar all the files in the $working_directory.
		@param $ext is the extention of the tar file name.
		@warning $ext must be a file name. not a full path.
		
		
	 */
	static function tarGzipDir($src, $tar, $ext=null)
	{
		/// msg("FileName: $dst");
		try {
			$a = new PharData($tar);
			$a->buildFromDirectory($src);
			$a->compress(Phar::GZ, $ext);
		}
		catch (Exception $e) {
			echo "Exception : " . $e;
		}
	}
	
	/** @short returns content of the url in binary safe
	 *
	 */
	static function download( $url )
	{
		$file = fopen($url, 'rb');
		$content = null;

		while ( !feof ( $file ) ) {
			$content .= fread($file, 4096);
		}
		fclose($file);
		return $content;
	}
	
	

/** @short 파일 업로드
	@note Uploads file.
 * @note
		주의 해야 할 점으로,
		thumbnail 파일을 만들 때, 경로는 파일 이름의 끝에 "_thumbnail" 을 자동적으로 붙인다.
	
	@note
		thumbnail='no' 이면 썸네일 하지 않는다.
		resize='no' 이면 원본 이미지를 리사이즈 하지 않는다.
		
	
	@note
		파일들을 한 폴더에 몰아서 넣는다.
		파일의 개수가 10 만개 이상 늘어나면 속도가 느릴 수도 있다.
		하지만 www.philgo.com 만 보더라도 굉장히 많은 파일들이 한 폴더에 저장되어져 있는데, 끄덕없다.
		따라서 그러한 염려는 하지 않는다.
		
	@note
		파일 저장 예.
		별도의 DB 테이블로 정보를 보관하지 않는다.
		한 페이지에 파일의 갯수를 제한 해 놓고, 파일이 존재하는지를 체크하여 업로드 되었는지 그렇지 않은지를 판별한다.
	
	@note
		예제)
		for($i=1; $i<=3; $i++) {
			file::upload(
				array(
					'form_name'=>"file$i",
					'path'=>"message_{$no}_$i",
					'limit'=>3000000
				)
			)
		}
		
		

	@param array
		'resize_width' 이 값이 있으면, 원본 사진을 이 크기로 줄인다.
		'resize_height' 이 값이 있으면, 원본 사진을 이 크기로 줄인다.
		'thumbnail_width' 이 값은 thumbnail 의 너비
		'thumbnail_height' 이 값은 thumbnail 의 높이
		'limit' 업로드되는 크기를 제한 할 값.
		'jpeg_quality' JPEG 의 경우 quality 지정.
		thumbnail_resizing=>'adaptiveResize' 는 썸네일일 경우만 width 과 height 을 그대로 자른다.

		'do not resize org gif' = 1 이면 GIF 원본 파일을 썸네일 하지 않는다.
		
	@note 완전한 예제는 x4/module/member/primary_photo_submit.php 를 참고한다.
	
	@note 썸네일을 만들지 말고, 원본 파일 자체를 특정 사이즈로 만드는 것이 좋다.
		예를 들면 아래와 같다.
	@code
		$o = array();
		$o['form_name'] = 'file';
		$o['path'] = member_photo_path();
		$o['thumbnail'] = 'no';								/// 썸네일을 만들지 않음.
		$o['resize_width'] = 100;							/// 원본 파일 자체의 크기를 줄임.
		$o['resize_height'] = 120;						///
		$o['limit'] = 5000000;								/// 큰 용량의 이미지를 입력 받아, 원본 파일 자체를 줄인다.
		$re = file::upload( $o );
	@endcode
	
	@code 간단한 예
		$opt = array();
		$opt['form_name'] = $n;
		$opt['path'] = $p;
		$opt['thumbnail'] = 'no';
		$opt['resize'] = 'no';
		$opt['limit'] = 9876000;
		file::upload($opt);
	@endcode
			
	@example 일반 예
	
		$re = file::upload(
			array(
				'form_name'=>'photo1',
				'path'=> $sf_file->upload_path_photo( $filename ),
				'limit'=>3000000,
				'resize_width'=>600,
				'resize_height'=>600,
				'thumbnail_width'=>140,
				'thumbnail_height'=>160,
			)
		);	
		if ( $re ) { html::jsBack($re); return; }



	@return
		업로드 된 파일이 없는 경우 null 이 리턴되며 파일 업로드를 성공한 경우, 0 이 리턴된다.
		따라서 파일이 올바로 업로드 되었는지 확인을 하기 위해서는 "=== 0" 으로 검사를 해야 한다.
		@code		
			if ( empty($ret ) ) {
				if ( $ret === null ) {
					/// 파일이 업로드 되지 않은 경우,
				}
				else if ( $ret === 0 ) {
					/// 파일 업로드 성공
				}
			}
		@endcode
		
		파일의 크기가 0 인경우, 1110 을 리턴
		파일의 크키가 php.ini 보가 큰 경우, UPLOAD_ERR_INI_SIZE 를 리턴
		파일의 크기가 입력된 옵션 값 보다 큰 경우, 1111 을 리턴
		주의 : 파일이 업로드 되지 않은 경우, 그냥 0 을 리턴한다. 이것은 성공을 했을 때 리턴하는 값과 동일한다.
	
*/

static function upload($opt)
{
	
	self::$error_message = null;
	
	$file = $_FILES[$opt['form_name']];
	if ( $file['error'] == UPLOAD_ERR_NO_FILE ) {
		debug("file::upload() : No file uploaded");
		return NO_FILE;
	}

	
	if ( $file['error'] != UPLOAD_ERR_OK ) {
	
		self::$error_message = "Error No.: " . $file['error'] . ", FIle Name: $file[name], Form Name: $opt[form_name]";
		
		return self::UPLOAD_FAILED;
	}
		// return "errorcode=$file[error] : form_name=$opt[form_name] $message";
	
	if ( $file['size'] == 0 ) return self::FILE_SIZE_ZERO; 					// return "File upload error : file size is 0. file name=$file[name]";
	if ( $file['size'] > $opt['limit'] ) return self::FILE_SIZE_TOO_LARGE; 		// "File size is too big";
	
	
	debug("file::upload() : file path to be saved : " . $opt["path"]);
	
	if ( empty($opt['thumbnail_width']) ) $opt['thumbnail_width'] = 100;
	if ( empty($opt['thumbnail_height']) ) $opt['thumbnail_height'] = 100;
	

	
	if (! move_uploaded_file($file['tmp_name'], $opt['path']) ) {
		return self::CANNOT_MOVE_UPLOAD_FILE;
		debug( "file::upload() : cannot move file: from->$file[tmp_name] : to->$opt[path]" );
		//debug_log("File upload failed: cannot move file: from: $file[tmp_name] to: $opt[path]");
		return;
	}
	debug("file::upload() : file saved at : $opt[path] (from: $file[tmp_name])");
	
	if ( preg_match("/^image/", $file['type'] ) ) {
		/// 원본 이미지 리사이징.
		if ( $opt['resize'] == 'no' ) {
			debug("file::upload() : skip resizing original image");
		}
		/// do not resize org gif
		else if ( $opt['do not resize org gif'] && $file['type'] == 'image/gif' ) {
			debug("file::upload() : GIF image cannot be resized due to its animation function.");
		}
		else {
			/// Resize original image
			if ( $opt['resize_width'] && $opt['resize_height'] ) {
				$re = file::make_thumbnail($opt['path'], $opt['resize_width'], $opt['resize_height'], $opt['path'], null,  $opt['jpeg_quality']);
				if ( $re ) {
					return self::THUMBNAIL_FAILED_ON_ORG_IMAGE;
					// jsAlert( lang( MAKE_THUMBNAIL_FAILED ) );
					debug("file::upload() : thumbnail creation failed");
				}
				else debug("file::upload() : original image resized by $opt[resize_width] x $opt[resize_height] with jpeg_quality=$opt[jpeg_quality]");
			}
		}

		if ( $opt['thumbnail'] == 'no' ) { }
		else {
			/// creating thumnail...
			$path_thumbnail = $opt['path'] . '_thumbnail';
			//debug_log("Creating thumbnail at : $path_thumbnail");
			file::make_thumbnail($opt['path'], $opt['thumbnail_width'], $opt['thumbnail_height'], $path_thumbnail, $opt['thumbnail_resizing'], $opt['jpeg_quality']);
			if ( $re ) {
				return self::THUMBNAIL_FAILED;
				// jsAlert( lang( MAKE_THUMBNAIL_FAILED ) );
				debug("file::upload() : thumbnail creation failed");
			}
			else debug("file::upload() : thumbnail created by $opt[resize_width] x $opt[resize_height] with jpeg_quality=$opt[jpeg_quality]");
		}
	}
	
	return self::UPLOAD_OK;
} // eo file upload



/** @brief 썸네일 생성

 * @note
	phpthumb 은 좋은 썸네일 소스 코드이다.
	자세한 것은 개발 홈페이지를 참고한다.
		http://phpthumb.gxdlabs.com/
		https://github.com/masterexploder/PHPThumb/wiki
	
 * @note
	이미지가 아닌 파일의 경우, 에러가 나야하는데, 에러를 찾지 못하겠음
	
 @note
	사용법
	make_thumbnail("원본이미지파일경로", 너비, 높이, "저장할썸네일파일경로");
	file::make_thumbnail($opt['path'], $opt['resize_width'], $opt['resize_height'], $opt['path']);
	
	원본 너비와 높이가 큰 경우, 가장 잘 보이게 썸네일한다.
	이 말은, 너비와 높이가 입력된 숫자와 정확하게 일치하지 않을 수 있다.
	
 * @param $resizing 썸네일 리사이징 하는 방법
	https://github.com/masterexploder/PHPThumb/wiki/Basic-Usage 를 참고한다.
	
	
	resize, adaptiveResize 두가지를 사용 할 수 있다. 다른 resize 방식도 있지만, 좌표를 지정해야하므로 사용하기 어렵다.
	
	
	기본은 adaptiveResize 이며 정확히 width 과 height 크기로 리사이즈가 된다.
	
	resize 로 하면, 입력한 width 과 height 의 크기로 나오지 않을 수 있다.
	
	 */
	static function make_thumbnail($source_path, $width, $height, $thumbnail_path, $resizing=NULL, $jq=100)
	{
		require_once 'phpthumb/ThumbLib.inc.php';
		if ( empty($jq) ) $jq = 100;
		$options = array('jpegQuality' => $jq);
		debug("file::make_thumbail() : options:");
		debug($options);
		try
		{
			$thumb = PhpThumbFactory::create($source_path, $options);
		}
		catch (Exception $e)
		{
			$msg = "file::make_thumbnail() : error :" .  $e->getMessage();
			self::$error_message = $msg;
			debug($msg);
			return -1;
		}
		if ( $resizing == 'resize' ) {
			$thumb->adaptiveResize($width, $height);
		}
		else {
			$thumb->adaptiveResize($width, $height);
		}
		$thumb->save($thumbnail_path);
		
	 } // eo make_thumbnail
	 


} // eo file class
/** --------------------------- eo file class -------------------- */



/**
 *  @short 웹브라우저로 현재 접속한 페이지의 URL 경로를 얻는다.
 *  
 */
function url_path_current()
{
	$pageURL = 'http';
         if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
         $pageURL .= "://";
         if ($_SERVER["SERVER_PORT"] != "80") {
         $pageURL .=          
         $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
         } 
         else {
         $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
         }
    return $pageURL;
}
