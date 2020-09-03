<?php

$mysqli = null;
function db_connect( $host, $user, $password, $database )
{
	global $mysqli;
	
	db_log_query( "connecting $host, $user, $password, $database" );
	$mysqli = new mysqli( $host, $user, $password, $database );
	db_log_query( "SET NAMES 'utf8'" );
	$mysqli->query("SET NAMES 'utf8'");
	
	//echo $mysqli->host_info;
}
function db_query( $q )
{
	global $mysqli;
	db_log_query( $q );
	$result = $mysqli->query($q);
	if ( $result === false ) {
		echo "Error No.: " . $mysqli->errno . "\nError Message: " . $mysqli->error;
		exit;
	}
	else return $result;
}
function db_row( $q )
{
	global $mysqli;
	$row = array();
	db_log_query( $q );
	$result = $mysqli->query($q);
	if ( $result ) {
		$row = $result->fetch_assoc();
		$result->close();
	}
	return $row;
}


	 
function db_rows($q)
{
	global $mysqli;
	$rows = array();
	db_log_query( $q );
	$result = $mysqli->query($q);
	if ( $result ) {
		while ($row = $result->fetch_assoc()) {
			$rows[] = $row;
		}
		$result->close();
	}
	return $rows;
}

/**
 *  @short returns only the first element of the 1 row.
 *  
 *  @code
 *  	echo db_result("SELECT COUNT(*) FROM g5_member");
 *	@endcode
 *
 */
function db_result( $q )
{
	global $mysqli;
	db_log_query( $q );
	$result = $mysqli->query( $q );
	if ( $result ) {
		$row = $result->fetch_row( );
		return $row[0];
	}
	else return false;
}

function db_insert_id()
{
	global $mysqli;
	return $mysqli->insert_id;
}



function db_escape($data) {
	global $mysqli;
	return $mysqli->real_escape_string($data);
}

