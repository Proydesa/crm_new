<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase manejo de usuarios

*/
define('ADODB_ASSOC_CASE', 2);

class H_LMS_CONN {


var $dbtype		= 'mysqli';
var $dbpersist	=  false;
// dbdebug para la base de datos (true/false)
var $dbdebug	=  false;
var $prefix		= 'mdl_';

static private $adodb = false;
static private $instance = NULL;

	public static function getInstance(){
		global $HULK;

		if (self :: $instance === NULL)
		{
			self :: $instance = new H_LMS(); //create class instance
			include_once("{$HULK->libdir}/adodb5/adodb-exceptions.inc.php" ); //include exceptions for php5
			include_once("{$HULK->libdir}/adodb5/adodb.inc.php" );
			self :: $adodb = NewADOConnection(self :: $instance->dbtype);
			self :: $adodb->debug = self :: $instance->dbdebug;
			@self :: $adodb->Connect($HULK->lms_dbhost,$HULK->lms_dbuser,$HULK->lms_dbpass,$HULK->lms_dbname);
			$ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
			self :: $adodb -> Execute("SET character_set_results=utf8", $dbLink);
		}
		return  self :: $instance;
	}
  /**
  * Insert the array into table
  *
  * $H_DB -> insert('users',array('user' => 'insert_test','name'=>'James','surname'=>'Baker','user_level' => 5));
  * $H_DB -> insert('users',$_POST);
  *
  **/
	function insert($tablename,$record){
	  	$record = (array) $record;

		$rs = self :: $adodb->Execute("SELECT * FROM ".$tablename." LIMIT 1");
		$insert_sql =  self :: $adodb->GetInsertSQL($rs,$record);

		self :: $adodb->Execute( $insert_sql );
		return $this->GetField_sql("SELECT last_insert_id()");
	}
	/**
	* Update table
	*
	* $H_DB -> update('useri',array('user' => 'update_test','name'=>'James','surname'=>'Baker','phone' => 04656454),'id=75');
	* $H_DB -> update('users',$_POST,'id=75');
	*
	*/
	function update($tablename,$record,$where = null,$data = null){
	  $record = (array) $record;
		if ($where !== Null) {
			$rs = self :: $adodb->Execute("SELECT * FROM ".$tablename." WHERE ".$where." LIMIT 1",$data);
		} else {
			$rs = self :: $adodb->Execute("SELECT * FROM ".$tablename." LIMIT 1",$data);
		}

		$update_sql = self :: $adodb->GetUpdateSQL($rs, $record);
		/** To Log**/
		global $H_DB;
		$datos_actualizados = substr($update_sql,strpos($update_sql,"SET")+3,strpos($update_sql,"WHERE")-4-strpos($update_sql,"SET"));
		$H_DB->setActivityLog(0,"UPDATE -> {$tablename}",$datos_actualizados);
		/***********/
		if ($update_sql != '') {
			return self :: $adodb->Execute($update_sql);
		}
		return true;
	}
	/* Devuelve solo el primer campo*/
	function GetField_sql($sql){
		if ($sql)	$rs = self :: $adodb->getCol($sql);
		if ($rs) return $rs[0];
		return false;
	}
	function GetField($table,$field,$value,$id='id'){
		if ($value)	$rs = self :: $adodb->getCol("SELECT {$field} FROM {$table} WHERE {$id}='{$value}'");
		if ($rs) return $rs[0];
		return false;
	}
	function record_exists_sql($sql){

		if ($sql)	$rs = self :: $adodb->getCol($sql);
		if ($rs) return true;
		return false;

	}
	function record_exists($tablename,$value,$field='id'){

		return $this->record_exists_sql("SELECT {$field} FROM {$tablename} WHERE {$field}='{$value}' LIMIT 1;");

	}

	function delete( $tablename, $where, $params="" ){
		try
		{
			$result = self :: $adodb -> Execute( 'DELETE FROM '.$tablename.' WHERE '.$where , $params);
		}
		catch ( exception $e )
		{
			if (constant( 'DEBUG' ) === true)
			{
				print_r($e);
			}
		}
		return result;
	}
	/**
	 * Returns a prepared query . Works just like execute , only instead of running the prepared query it returns it
	 *
	 * @param unknown_type $str
	 * @param unknown_type $arr
	 * @return unknown
	 */
	public function returnPreparedQuery($str,$arr) {
		$temp = explode('?',$str);
		$size = count($temp);
		for ($x=0;$x<$size;$x++) {
			if (($temp[$x]!='') && ($arr[$x])!='') {
				$temp[$x] .=  ' '. self ::$instance->qstr($arr[$x]);
			}
		}
		return implode ( ' ' , $temp ) ;
	}
	function __call($method, $args){
		return call_user_func_array(array(self :: $adodb, $method),$args);
	}
	function __get($property){
		return self :: $adodb -> $property;
	}
	function __set($property, $value){
		self :: $adodb[$property] = $value;
	}
	private function __clone(){
	}
}
?>