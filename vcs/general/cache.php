<?php
/// https://docs.google.com/document/d/14vRTh_sAkJwTK9_ybLf8ii5v_oGrG6u7iLCFeBBl7PY/edit#heading=h.chmkzynfoh30


/**
 * 		@short cache_get(), cache_put(), cache_delete() 세 개의 DB 쿼리 함수는 프레임(프로젝트)마다 변경을 해야한다.
 *
 */
function cache_get( $id )
{
	$re = db_row( "SELECT * FROM db_cache WHERE `id`='$id'" );
	
	if ( ! empty( $re['data'] ) ) {
		$re['data'] = cache_unscalar( $re['data'] );
	}
	return $re;
}

/**
 *  기존 캐시를 삭제하고 새로운 캐시를 집어 넣는다.
 */
function cache_put( $_cache_id, $stamp, $data )
{
	$data = cache_scalar($data);
	db_query( "REPLACE INTO db_cache (id,created,data) VALUES('$_cache_id', '$stamp', '$data')" );
}



/// 실제 캐시 레코드를 삭제하는 것이 아니라, 삭제 카운트를 한다.
function cache_delete( $id )
{
	db_query( "UPDATE db_cache SET delete_count=delete_count+1 WHERE `id`='$id'" );
}

function cache_delete_all()
{
	db_query("TRUNCATE TABLE  `db_cache`");
}

/// 아래의 부분은 변경 할 필요 없음.
function cache( $id, $expire = 25 )
{
	$re = read_cache( $id, $expire );
	if ( $re === null ) {
		ob_start();
		return true;
	}
	else {
		$r = date('r', $re['created']);
		echo $re['data'] ."<!-- $re[id] : CACHED AT : $r -->";
		return false;
	}
}
function cache_read( $id, $expire = 25 ) { return read_cache( $id, $expire ); }
function read_cache( $id, $expire = 25 )
{
	global $_cache_id;
	
	
	$_cache_id = $id;

	$re = cache_get( $id );
	
	if ( empty($re) ) return null;
	else {
		if ( $re['created'] < time() - $expire * 60 ) {
			cache_delete( $id );
			$re['delete_count'] ++;
		}
		return $re;
	}
}

function read_cache_data( $id, $expire = 25 )
{
	$re = read_cache( $id, $expire );
	if ( $re === null ) return $re;
	else return $re['data'];
}

function cache_save( $data = null ) { return save_cache( $data ); }
function save_cache( $data = null, $id = null )
{
	global $db, $_cache_id;
	
	if ( $data === null ) {
		$ret = 0;
		$data = ob_get_clean();
	}
	else $ret = 1;
	
	if ( $id ) $_cache_id = $id;
	
	cache_put( $_cache_id, time(), $data );
	
	if ( $ret ) return $data;
	else echo $data;
}

function cache_scalar($str)
{
	return addslashes(serialize($str));
}

function cache_unscalar($str)
{
	return unserialize($str);
}
