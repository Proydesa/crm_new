<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase que conecta a la base de datos, hace las consultas...

Hay muchos drivers para usar, lo hacemos caserito y despues vemos que podemos usar que se adapte a varias bases
de datos y sea un poco mas robusta.
*/
define('ADODB_ASSOC_CASE', 2);

class H_Database_Conn {

  static private $adodb = false;
  static private $instance = NULL;


  public static function getInstance()
  {
    global $HULK;

    if (self :: $instance === NULL)
    {
      self :: $instance = new H_Database(); //create class instance
      include("{$HULK->libdir}/adodb5/adodb-exceptions.inc.php" ); //include exceptions for php5
      include("{$HULK->libdir}/adodb5/adodb.inc.php" );
      self :: $adodb = NewADOConnection($HULK->dbtype);
      self :: $adodb -> debug = $HULK->dbdebug;
      @self :: $adodb -> Connect($HULK->dbhost, $HULK->dbuser, $HULK->dbpass, $HULK->dbname);
      $ADODB_FETCH_MODE = ADODB_FETCH_ASSOC;
      self :: $adodb -> Execute("SET character_set_results=utf8", $dbLink);
    }

    return  self :: $instance;
  }

  function get_config()
  {
    global $HULK;

    $rs= self :: $adodb -> Execute("SELECT * FROM {$HULK->prefix}config;");
    $result =  $rs -> GetRows();
    foreach($result as $row):
      $HULK->{$row['name']}=$row['value'];
    endforeach;

    $rs= self :: $adodb -> Execute("SELECT * FROM {$HULK->prefix}config_vectores ORDER BY nombre, peso, indice, id;");
    $result =  $rs -> GetRows();
    foreach($result as $row):
      $HULK->{$row['nombre']}[$row['indice']]=$row['valor'];
    endforeach;

    $HULK->formas_de_pago = array("por_alumno"=>"Alumnos regulares","por_comision"=>"Fee por comisión","plan_social"=>"Plan social","fee_anual"=>"Fee anual","instructor"=>"Instructores");

    return $HULK;
  }


     /**
      * Insert the array into table
      *
      * @param string $tablename
      * @param array $record
      *
      * Example:
      *
      * $H_DB -> insert('users',array('user' => 'insert_test','name'=>'James','surname'=>'Baker','user_level' => 5));
      * $H_DB -> insert('users',$_POST);
      *
      */
    function insert($tablename,$record)
    {
      $record = (array) $record;
      $record = array_map('utf8_decode', $record);

      $rs = self :: $adodb->Execute("SELECT * FROM ".$tablename." LIMIT 1");
      $insert_sql =  self :: $adodb->GetInsertSQL($rs,$record);

      self :: $adodb->Execute( $insert_sql );
      return $this->GetField_sql("SELECT last_insert_id()");
    }
    function select($tablename,$id)
    {

      $rs = self :: $adodb->getRow('SELECT * FROM '.$tablename.' WHERE id='.$id.' LIMIT 1');

      return $rs;
    }
        /**
         * Update table
         *
         * @param string $tablename
         * @param $record $array
         * @param integer $where
         * @return result
     *
         * Example:
         *
         * $H_DB -> update('useri',array('user' => 'update_test','name'=>'James','surname'=>'Baker','phone' => 04656454),'id=75');
         * $H_DB -> update('users',$_POST,'id=75');
         *
         */
    function update($tablename,$record,$where = null,$data = null)
    {
      $record = (array) $record;
      $record = array_map('utf8_decode', $record);
            
      if ( $where !== Null )
      {
        $rs = self :: $adodb -> Execute( 'SELECT * FROM ' . $tablename . ' WHERE ' . $where .' LIMIT 1',$data);
      } else
      {
        $rs = self :: $adodb -> Execute( 'SELECT * FROM ' . $tablename .' LIMIT 1',$data);
      }

      $update_sql = self :: $adodb->GetUpdateSQL($rs, $record);

      if ( $update_sql != '')
      {
              return self :: $adodb -> Execute( $update_sql );
      }
      return true;
    }

  function record_exists_sql($sql){

    if ($sql) $rs = self :: $adodb->getCol($sql);
    if ($rs) return true;
    return false;

  }
  function record_exists($tablename,$value,$field='id'){

    return $this->record_exists_sql("SELECT {$field} FROM {$this->prefix}{$tablename} WHERE {$field}={$value} LIMIT 1;");

  }

  /* Devuelve solo el primer campo*/
  function GetField_sql($sql)
  {
    if ($sql) $rs = self :: $adodb->getCol($sql);
    if ($rs) return $rs[0];
    return false;
  }

  function GetField($table,$field,$value,$id='id')
  {
    if ($value) $rs = self :: $adodb->getCol("SELECT {$field} FROM {$table} WHERE {$id}={$value}");
    if ($rs) return $rs[0];
    return false;
  }

  /**
   * Delete record from table
   *
   * @param string $tablename
   * @param string $where
   * @return result
   *
   * Example:
   *
   * $H_DB -> delete('useri','id=75');
   *
   */
  function delete( $tablename, $where, $params=false )
  {
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
          return $result;
  }

  /**
   * Returns a prepared query . Works just like execute , only instead of running the prepared query it returns it
   *
   * @param unknown_type $str
   * @param unknown_type $arr
   * @return unknown
   */
  public function returnPreparedQuery ( $str , $arr )
  {
          $temp = explode ( '?' , $str ) ;
          $size = count ($temp) ;
          for ($x=0;$x<$size;$x++)
          {
                  if ( ($temp[$x] != '') && ( $arr[$x]) != '' )
                  {
                          $temp[$x] .=  ' ' .  self ::$instance -> qstr ($arr[$x]) ;
                  }
          }
          return implode ( ' ' , $temp ) ;
  }

  function __call($method, $args)//call adodb methods
  {
          return call_user_func_array(array(self :: $adodb, $method),$args);
  }

  function __get($property)
  {
          return self :: $adodb -> $property;
  }

  function __set($property, $value)
  {
          self :: $adodb[$property] = $value;
  }

  private function __clone()//do not allow clone
  {
  }

}

?>