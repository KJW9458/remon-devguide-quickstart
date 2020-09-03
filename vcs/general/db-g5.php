<?php

function db_connect()
{
}


function db_query( $q )
{
	return sql_query( $q );
}

/**
 *  @short returns only the first row
 *  
 *  @code
 *  	di ( db_row("SELECT * FROM g5_member") );
 *	@endcode
 *  
 *  
 */
function db_row( $q )
{
	return sql_fetch( $q );
}

/**
 *  @short returns the rows of the query
 *  
 *  @code
 *  	di ( db_rows("SELECT * FROM g5_member") );
 *  @endcode
 */
function db_rows( $q )
{
	$result = sql_query( $q );
	$rows = array();
	while ($row = sql_fetch_array($result)) {
		$rows[] = $row;
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
	$result = mysql_query( $q );
	$row = mysql_fetch_row( $result );
	return $row[0];
}



function db_insert_id()
{
	return mysql_insert_id();
}