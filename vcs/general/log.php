<?php

$log_on_different_domain = true;
$log_on_advertiser = true;


function log_access()
{
	global $log_on_different_domain, $log_on_advertiser;
	
	
	if ( is_bot( $_SERVER['HTTP_USER_AGENT'] ) ) {
		return;
	}
	
	$domain_to = log_domain_name();
	$ref = $_SERVER['HTTP_REFERER'];
	if ( empty($ref) ) return;
	
	$pu = parse_url( $ref );
	$domain_from = $pu['host'];
	if ( empty($domain_from) ) return;
	$domain_from = strtolower( $domain_from );
	
	$advertiser = null;
	$qs = $_SERVER['QUERY_STRING'];
	if ( strpos( $qs, '&' ) === false ) {
		$p = "/^[a-zA-Z][0-9a-zA-Z_\.]{2,15}+$/";
		if ( preg_match( $p, $qs ) ) {
			$advertiser = $qs;
		}
	}
	
	
	if ( $log_on_different_domain && $domain_from != $domain_to ) {
	}
	else if ( $log_on_advertiser && $advertiser ) {
	}
	else return;
	
	
	if ( isset($_COOKIE['lavc']) && is_numeric($_COOKIE['lavc']) ) {
		$idx = $_COOKIE['lavc'];
		db_query("UPDATE log_access SET visit_count=visit_count+1 WHERE idx=$idx");
		return;
	}
	
	
	$base_domain_from = base_domain( $domain_from );
	
	$ip = log_client_ip();
	$stamp = time();
	
	$q = "
		INSERT INTO log_access
			SET
				domain_from = '$domain_from',
				base_domain_from = '$base_domain_from',
				domain_to = '$domain_to',
				visit_count = 1,
				advertiser='$advertiser',
				`url` = '$ref',
				`ip` = '$ip',
				`stamp` = $stamp,
				user_agent = '$_SERVER[HTTP_USER_AGENT]'
		";
	
	
	
	db_query( $q );
	$idx = db_insert_id();
	
	setcookie("lavc", $idx, time() + 60 * 60 * 24 * 365, "/");
	
	
	
	
}





function log_domain_name()
{
	if ( isset( $_SERVER['HTTP_HOST'] ) ) {
		$domain = $_SERVER['HTTP_HOST'];
		$domain = strtolower($domain);
		return $domain;
	}
	else return NULL;
}

function log_client_ip()
	{
		if ( isset($_SERVER['REMOTE_ADDR']) ) return $_SERVER['REMOTE_ADDR'];
		else return NULL;
	}
	
