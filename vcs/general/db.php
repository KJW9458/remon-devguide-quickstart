<?php
/**
 *  
 *  
 *  @code example code
 *  
		$up = array();
		$up['category'] = "Let's go 3";
		db_insert( 'bank', $up );
		db_delete( 'bank', array('category'=>'Hi, Long time no see') );
		di($up);
	@endcode
	
 */
/// https://docs.google.com/a/withcenter.com/document/d/1wEFNihzY7SXCAoKvj5IRlLuFnhs1QPxziblc0HKIUGY/edit#heading=h.6kqkb3eti53q

$frame_type = null;

if ( defined('G5_URL') ) include 'db-g5.php';
else include 'db-mysqli.php';


function db_log_query( $msg )
{
	dog( $msg );
}


	
/* @short Inserts a record into a table.
 * 
 * @note
		Do not use 'REPLACE INTO'
		
 *
 * @param string $table_name
		table name
 * @param associative-array $values
		fields and its values.
 * @return
		true on success,
		false on fail.
		
		This is the same as PDO_STATEMENT::execute
	@code
		$db->insert('config', array('code'=>'insert_test', 'data'=>'abc def', 'stamp'=>time()));
	@endcode
	
	
 *
 */
function db_insert($table_name, $kvs) {
	foreach($kvs as $key => $val) {
		$key_list[] = $key;
		$val_list[] = db_escape($val);
	}
	$keys = "`".implode("`,`",$key_list)."`";
	$vals = "'".implode("','",$val_list)."'";
	$q = "INSERT INTO `{$table_name}` ({$keys}) VALUES ({$vals})";
	return db_query($q);
}



/** @short updates table
 *
	@param $table the name of the table to be updated.
	@param $kvs the array of field & value.
	@param $conds array of condition for WHERE statement.
	@code
		$db->update('test', array('id'=>'id1-updated', 'name'=>'name2'), array('id'=>'id1'));
	@endcode
 */
function db_update($table, $kvs, $conds)
{
	foreach($kvs as $k => $v) {
		$v = db_escape($v);
		$sets[] = "`$k`='$v'";
	}
	$set = implode(", ", $sets);
	foreach($conds as $k => $v )
	{
		$v = db_escape($v);
		$arc[] = "`$k`='$v'";
	}
	$cond = implode(" AND ", $arc);
	$q = "UPDATE $table SET $set WHERE $cond";
	return db_query($q);
}
	


	/** @short deletes record(s).
	 *
	 * @note it will cause error if the $conds is empty.
	 * Try to use this function whenever you need to delete.
	 *
	 * @param $table_name table name
	 * @param $conds conditions for WHERE clause to delete records.
		@code
			$db->delete('test', array('id'=>'test_id'));
		@endcode
		
	 *
	 */
	function db_delete($table_name, $conds) {
		foreach($conds as $k => $v )
		{
			$arc[] = "`$k`='$v'";
		}
		$cond = implode(" AND ", $arc);
		$q = "DELETE FROM `{$table_name}` WHERE $cond";
		return db_query($q);
	}
	
//
/** @file sql.php
	@short This 'SQL' class helps to construct easy and safe SQL query.
	Use this SQL methods as much as you want.
	@details When you query to database, it might produce unpredicted hidden error and vernarable for SQL Injection.
		This class provides easy and safe constructions of SQL query.
	
	 * @code example 1
			$from = mktime(0, 0, 0, 8, 1, 2013);
			$to = mktime(0, 0, 0, 9, 1, 2013) -1;
			$sql['select'] = "COUNT(*)";
			$sql['cond'][] = sql::exp('gender','M');
			$sql['cond'][] = sql::exp('idx','>','10');
			$sql['cond'][] = sql::between('stamp', $from, $to);
			$sql['cond'][] = sql::ors( array( sql::exp('email', 'LIKE', 'a%'), sql::exp('nickname', 'LIKE', 'b%') ) );
			sql::exp('id', 'LIKE', "$in[key]%"),
			echo sql::select(MEMBER_TABLE, $sql);
			echo sql::row(MEMBER_TABLE, $sql);
		* @endcode
		
	 @code example 2
	 
			$sql = array();
			$sql['select'] = 'idx';
			$sql['cond'][] = sql::ors( array(sql::exp('id',$uid),sql::exp('nickname',$uid),sql::exp(lms::class_id,$uid)) );
			return $sys->db->_result( sql::select(MEMBER_TABLE, $sql) );
	
	@endcode
	
	@code example 3. LIKE 와 OR 사용법. LIKE 를 여러개 쓸 때 OR 사용법. OR 연결 방법.
		$o['where'][] = 
			where( 'id', 'LIKE', "$in[member]%" ) .
			' OR ' .
			where( 'nickname', 'LIKE', "$in[member]%" );
	@endcode
	
	@code
		$option = array();
		$option[]= sql::exp('date_payment', '>=', $in['date_begin']);
		$option[]= sql::exp('date_payment', '<=', $in['date_end']);
		$option[]= sql::between('date_payment', $in['date_begin'],$in['date_end']);
		$option[]= sql::like('date_payment', $in['date_begin'],$in['date_end']);
	@endcode
	
	@code
		$payment = $adv->payment( array(
		'cond'=> array(
			$q_uid,
			sql::between('date_payment', $in['date_begin'], $in['date_end'])
		)
	)
);
	@endcode
	
	@note You can construct the full SQL query like below.
	@code
		$q_where = sql::where($cond);
		$q_order_by = sql::order_by($option['orderby']);
		$q_limit = sql::limit($option['limit']);
		$q = "SELECT $option[fields] FROM ad_client $q_where $q_order_by $q_limit";
	@endcode
	
	
 *//**
 *
 *
 *
 
$o = array();
$o['table'] = MEMBER_TABLE;
$o['where'][] = where( 'type', TYPE_TEACHER_MANAGER );
$o['where'][] = where( 'branch', $in['branch'] );
$db->row( sql::select( $o ) );

 */
class SQL {
	
	
	/*
		만약 $value 가 NULL 이면,
		$field 는 필드
		$condition 은 값이 된다.
		그리고 기본 조건식은 "=" 를 사용한다.
		
		@cond
		$option[]= sql::exp('date_payment', '>=', $in['date_begin']);
		@endcond
		
		@cond
			$sql['cond'] = array(
				sql::ors(
					sql::exp('id', 'LIKE', "$in[key]%"),
					sql::exp('name', 'LIKE', "$in[key]%"),
					sql::exp('nickname', 'LIKE', "$in[key]%"),
					sql::exp('email', 'LIKE', "$in[key]%")
				)
			);
		@endcond
	
		
		*/
	static function exp($field, $condition, $value='_NO_VALUE_')
	{
		return self::expression($field,$condition,$value);
	}
	
	/** post class 에서 사용하는 경우 */
	static function cond_exp($f, &$o)
	{
	
				
		/** @2014-05-14
		 * 검색을 하니 에러가 난다. 그래서 아래의 구문을 새로 추가했는데,
		 * SQL 구문에서 에러가 나면 아래의 코드를 살펴 보아야 한다.
		 * SAP_CMS 를 쓰고 있으면  mysqli 를 이용해서 escape 한다.
		 */
		$value = $o[$f];
		if ( isset($GLOBALS['sys']) ) {
			$value = $GLOBALS['sys']->db->real_escape_string($value);
		}
		else {
			$value = addslashes( $value );
		}
		
		if ( isset($o["{$f}_exp"]) ) return $f. ' ' . $o["{$f}_exp"] . " '".$value."'";
		else  return "$f='".$value."'";
	}

	
	static function expression($field, $condition, $value='_NO_VALUE_')
	{
		if ( $value === '_NO_VALUE_' ) {
			$value = $condition;
			$condition = '=';
		}
		
		/** @2014-01-08
		 * 검색을 하니 에러가 난다. 그래서 아래의 구문을 새로 추가했는데,
		 * SQL 구문에서 에러가 나면 아래의 코드를 살펴 보아야 한다.
		 * SAP_CMS 를 쓰고 있으면  mysqli 를 이용해서 escape 한다.
		 */
		
		if ( isset($GLOBALS['db']) ) {
			$value = $GLOBALS['db']->escape($value);
		}
		else {
			$value = addslashes( $value );
		}
		
		return self::field($field)." $condition '$value'";
	}
	/**
	 *
	 
	 * @code
		sql::between('date_payment', $in['date_begin'], $in['date_end'])
		@endcode
		
	 *
	 */
	static function between($f, $b, $e)
	{
		return self::field($f)." BETWEEN $b AND $e";
	}
	static function like($f, $v)
	{
		return self::field($f) ." LIKE '%$v%'";
	}
	
	/** @short 필드에 점(.) 이 들어가 있으면 이스케이프를 해 준다.
	 *
	 */
	static function field($f)
	{
		if ( strpos($f, '.') ) {
			list($t,$f) = explode('.', $f);
			return "$t.`$f`";
		}
		return $f;
	}
	/** @short WHERE 조건절을 만든다.
	 * @param $ar 배열이며 모든 요소를 단순히 AND 로 묶어서 리턴한다.
	 *
	 *
	 */
	static function where($ar)
	{
		if ( empty($ar) ) return NULL;
		else {
			/** 2013-10-24 if there is an empty element in the array, it removes */
			$new_array = array();
			foreach ( $ar as $a ) {
				if ( empty($a) ) continue;
				$new_array[] = $a;
			}
			if ( $new_array ) return "WHERE (" . implode(') AND (', $new_array) . ')';
			else return NULL;
		}
	}
	/** @short ORDER BY 구문을 쉽게 작성하게 해 준다.
	 * @param $order 이 값에는 "idx asc, stamp desc" 와 같이 여러개의 정렬 값을 지정 할 수 있다.
	 
	 *
	 @code
		$order = self::order_by($option['order']);
	 @endcode
	 */
	static function order_by($order)
	{
		if ( empty($order) ) return NULL;
		else return "ORDER BY $order";
	}
	/** @short LIMIT 구문을 만들어 준다.
		
		@param $block 몇 번째 블럭에서 데이터를 가져 올지, 블럭 번호를 입력한다.
			이 값은 100 번째 레코드 부터 데이터를 뽑고 싶다고 100 의 값을 지정하면 안된다.
			20 개씩 블럭으로 묶는 다면, 100 번째 레코드를 뽑기 위해서는 5 의 값이 입력되어야 한다.
			
		@attention 블럭 번호는 1 부터 시작한다.
		
		
		@param $limit 몇 개의 레코드를 추출 할 것인지 결정한다.
			이 값이 바로 하나의 블럭에 몇개의 값이 들어가는지를 결정한다.
			
			limit(3,4) 와 같이 한다면, 하나의 블럭에는 4개의 레코드가 들어가는 것이며 3번째 블럭의 값을 가져오는 것이다.
			
		@attention 만약 이 값이 0 이면, "LIMIT $block" 과 같이 되어서, 첫번째 기록하는 수 만큼만 결과 값을 리턴한다.
		
			
			
			
		
		@code
			$limit = self::limit($option['block'], $option['limit']);
		@endcode
	 */
	static function limit($block=0, $limit=0)
	{
		
		if ( $block == 0 && $limit == 0 ) return NULL;
		if ( $limit == 0 ) return "LIMIT $block";
		$block = ($block - 1) * $limit;
		return "LIMIT $block, $limit";
	}
	static function limit_from( $from, $to ) {
		return "LIMIT $from, $to";
	}
	
	
	/** @short 데이터베이스서 select 구문을 쉽게 작성하도록 하도록 도와 준다.
	 *
	 *
		$option['cond'] 가 empty 이면, $option['where'] 를 보고, 여기에 값이 있으면
			이것을 사용한다.
			
		@code
			$o = array('table' => MEMBER_TABLE);
			$o['where'][] = sql::exp('type', 's');
		@endcode
		
	
	 
	 * @param $option['limit'] self::limit() 과 동일하다.
	 * @param $option ['order'] self::order_by 와 동일하다.
	 * @param $option['cond'] self::where 의 입력 값과 동일하다.
	 *
	 * @code 복합 쿼리 예제
			$from = mktime(0, 0, 0, 8, 1, 2013);
			$to = mktime(0, 0, 0, 9, 1, 2013) -1;
			$sql['select'] = "COUNT(*)";
			$sql['cond'][] = sql::exp('gender','M');
			$sql['cond'][] = sql::exp('idx','>','10');
			$sql['cond'][] = sql::between('stamp', $from, $to);
			$sql['cond'][] = sql::ors( array( sql::exp('email', 'LIKE', 'a%'), sql::exp('nickname', 'LIKE', 'b%') ) );
			echo sql::select(MEMBER_TABLE, $sql);
			echo sql::row(MEMBER_TABLE, $sql);
		* @endcode
		
	 
	 
		@param $optoin['orderby'], $optoin['order'], $optoin['order by']
			you can put 'order by' SQL clause in any of the param above.
		
		@attention this funcation now takes only 1 parameter.
			you had to pass 'table name' over the 1st parameter before.
			Now it is saved in $option['table'].
			but still, it support 2 parameters.
			
		@code
			sql::select($sql);
		@endcode
		
		@code
			$s = array( 'table' => 'lms_book' );
			if ( $o['idx_student'] ) $s['cond'][] = sql::exp('idx_student', $o['idx_student']);
			if ( $o['idx_teacher'] ) $s['cond'][] = sql::exp('idx_teacher', $o['idx_teacher']);
			$q = sql::select( $s );
		@endcode
		
		@attention when you limit, you can do below
		
			below will return the rows of "4,5,6"
				$option['block'] = 2;
				$option['limit'] = 3;

			below will return the row of "2,3,4"
				$option['from'] = 2;
				$option['limit'] = 3;
			
			below will return the row of '1,2,3'
				$option['limit'] = 3;
				
		
	 */
	static function select($table, $option=array())
	{
		// for compatibility
		if ( empty($option) ) {
			$option = $table;
			$table = $option['table'];
			unset($option['table']);
		}
		//
		if ( empty($option['select']) ) $option['select'] = '*';
		//
		if ( isset($option['from']) ) $limit = self::limit_from($option['from'], $option['limit']);
		else if ( isset($option['block']) ) $limit = self::limit($option['block'], $option['limit']);
		else if ( isset($option['limit']) ) {
			$limit = self::limit_from(0, $option['limit']);
		}
		
		
		if ( $option['orderby'] ) $order = self::order_by($option['orderby']);
		else if ( $option['order'] ) $order = self::order_by($option['order']);
		else if ( $option['order by'] ) $order = self::order_by($option['order by']);
		
		if ($option['where']) {
			if ( $option['cond'] ) {
				$option['cond'] = array_merge( $option['cond'], $option['where'] );
			}
			else $option['cond'] = $option['where'];
			unset( $option['where'] );
		}
		
		$where = self::where($option['cond']);
		return "SELECT $option[select] FROM $table $where $order $limit";
	}
	/** @short 하나의 행만 리턴한다. 따라서 LIMIT 옵션을 입력 할 필요가 없다.
	 *
	 *@code
	 $from = mktime(0, 0, 0, 8, 1, 2013);
	$to = mktime(0, 0, 0, 9, 1, 2013) -1;
	$sql['select'] = "COUNT(*)";
	$sql['cond'][] = sql::exp('gender','M');
	$sql['cond'][] = sql::exp('idx','>','10');
	$sql['cond'][] = sql::between('stamp', $from, $to);
	$sql['cond'][] = sql::ors( array( sql::exp('email', 'LIKE', 'a%'), sql::exp('nickname', 'LIKE', 'b%') ) );
	echo $sys->db->_result(sql::row(MEMBER_TABLE, $sql));
	*@endcoe
	@code
		$sql = array();
		$sql['select'] = 'count(*) cnt';
		$sql['cond'][] = sql::between('stamp', $yesterday, $today -1);
		$q = sql::row(LOG_REFERRAL_TABLE, $sql);
		$count = $sys->db->_result($q);
		echo "count:$count<hr>";
		$row = $sys->db->row($q);
		echo "count:$row[cnt]<hr>";
	@endcode
	
	 */
	static function row($table,$option=array())
	{
		$option['block'] = 1;
		$option['limit'] = 1;
		return self::select($table, $option);
	}
	
	
	/** @short 배열을 OR 로 묶어서 리턴한다.
	 *
	 * @param $f 만약 배열이면 모든 항목을 단순히 OR 로 연결해서 리턴한다.
	 *	만약 배열이 아니면, 필드명으로 인식하고, 모든 요소에 대해서 $exp 로 연결해서 리턴한다.
	 
	 
	 
		@note 배열을 입력받아서 SQL WHERE 문장에서 사용하기 쉽도록 OR 연산을 해서 리턴한다.

		@code 숫자를 가지는 배열만 입력하는 경우 예
			입력:
			Array( 1, 2, 3, 4 )
			출력:
			( 1 OR 2 OR 3 OR 4 )
		@endcode
		
		@code 실제 WHERE 조건에 사용할 수 있는 식을 만드는 방법
			입력 : Array( 1, 2, 3, 4 )
			호출 : sql::ors( 'idx', '=', $row );
			출력 : ( idx='1' OR idx='2' OR idx='3' OR idx='4' )
		@endcode
		
		
		@code
			di(sql::ors('post_id', '=', $pid));
		@endcode
		
	
	 *
	 */
	static function ors($f, $exp=null, $arr=null)
	{
	
		if ( empty($f) ) return NULL;
		if ( is_array($f) ) {
			return '(' . implode(' OR ', $f) .')';
		}
		else {
			$a2 = array();
			foreach ( $arr as $e ) {
				$a2[] = self::exp($f, $exp, $e);
			}
			return '(' . implode(' OR ', $a2) .')';
		}
	}
	
	
} // eo sql class
/**
 *
 * @note 중요: sql::exp() 를 더 쉽게 사용하게 한 것이다.
 *
 * $o['where'][] = where( 'idx_student', login('idx'));
 *
 *
 */
function where($field, $condition, $value='_NO_VALUE_')
{
	return sql::expression($field,$condition,$value);
}
