<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase manejo de usuarios

*/
class H_User {

 var $dbTable			= 'mdl_user';
 var $sessionVariable	= 'userid';
 var $tbFields = array(
  	'userID'=> 'id',
  	'login' => 'username',
  	'pass'  => 'password',
  	'email' => 'email',
  	'active'=> 'active'
  );
  /**
   * Cuando el usuario quiere ser recordado por el sistema. Por cuanto tiempo configuramos la cookie? (seconds)
   * var int
   */
  var $remTime = 2592000;//One month
  /**
   * El nombre de la cookie que nos sirve si el usuario quiere ser recordado por el sistema
   * var string
   */
  var $remCookieName = 'ProydesaCRM';
  /**
   * El dominio de la cookie
   * var string
   */
  var $remCookieDomain = 'proydesa.org/crm';
  /**
   * The metodo de encriptacion de contraseña. Puede ser sha1, md5 o nothing (no encryption)
   * var string
   */
  var $passMethod = 'md5';
  /**
   * Ver errores?
   * var bool
   */
  var $displayErrors = true;

  /*Do not edit after this line*/
  var $userID;

	/*Variable para guardar que estoy logeado como*/
  var $loggedas;

  var $id;

  // La academia por defecto es proydesa, sin elegir academia si tengo rol en proydesa ya podria entrar
  var $academyid=1;
  var $dbConn;
  var $userData=array();
  /**
   * Class Constructure
   *
   * @param string $dbConn
   * @param array $settings
   * @return void
   */
  var $LMS,$H_DB;

  function H_User($dbConn = '', $settings = '')
  {
		global $LMS,$H_DB,$HULK;
		$this->LMS = $LMS;
		$this->H_DB = $H_DB;
		// Reconfiguro las opciones si existen
	    if ( is_array($settings) ){
		    foreach ( $settings as $k => $v ){
				    if ( !isset( $this->{$k} ) ) die('Property '.$k.' does not exists. Check your settings.');
				    $this->{$k} = $v;
			}
	    }
	    $this->remCookieDomain = $this->remCookieDomain == '' ? $_SERVER['HTTP_HOST'] : $this->remCookieDomain;

	    if( !isset( $_SESSION ) ) {
			ini_set('session.save_handler', 'files');
			ini_set('session.save_path', "{$HULK->dataroot}/temp");
			if(@session_start() == false){session_destroy();session_start();}
		}

	    if ( !empty($_SESSION[$this->sessionVariable]) )
	    {
			//Si existe sesion retomo el usuario.
		    $this->loadUser( $_SESSION[$this->sessionVariable] );
	    }
	    //Maybe there is a cookie?
	    if ( isset($_COOKIE[$this->remCookieName]) && !$this->is_loaded()){
	      //echo 'I know you<br />';
	      $u = unserialize(base64_decode($_COOKIE[$this->remCookieName]));
	      $this->login($u['uname'], $u['password']);
	    }
  }

  /**
  	* Login function
  	* @param string $uname
  	* @param string $password
  	* @param bool $loadUser
  	* @return bool
  */
  function login($uname, $password, $remember = false, $loadUser = true)
  {
  		global $HULK;
    	$uname    = $this->escape($uname);
    	$password = $originalPassword = $this->escape($password);

        if (!$user = $this->LMS->GetRow("SELECT * FROM `{$this->dbTable}`
			WHERE `{$this->tbFields['login']}` = '{$uname}' LIMIT 1")) {
            return false;
        }
        if (!$this->validate_internal_user_password($user, $password)) {
            return false;
        }

		if ( $loadUser )
		{
			if (!$this->get_role('id',$user[$this->tbFields['userID']]))
			{
				show_error('Acceso','Su usuario no tiene role asignado en esta plataforma.');
				die();
			}
			$this->userData = $user;
			$this->id = $this->userID = $this->userData[$this->tbFields['userID']];
			$_SESSION[$this->sessionVariable] = $this->userID;
			if ( $remember )
			{
			  $cookie = base64_encode(serialize(array('uname'=>$uname,'password'=>$originalPassword)));
			  $a = setcookie($this->remCookieName, $cookie,time()+$this->remTime, '/', $this->remCookieDomain);
			}
		}
		return true;
  }
 /**
 * Compare password against hash stored in user object to determine if it is valid.
 *
 * If necessary it also updates the stored hash to the current format.
 *
 * @param stdClass $user (Password property may be updated).
 * @param string $password Plain text password.
 * @return bool True if password is valid.
 */
  function validate_internal_user_password($user, $password) {
    global $HULK;

    // If hash isn't a legacy (md5) hash, validate using the library function.
    if (!$this->password_is_legacy_hash($user['password'])) {
        return $this->password_verify($password, $user['password']);
    }

    // Otherwise we need to check for a legacy (md5) hash instead. If the hash
    // is valid we can then update it to the new algorithm.

    $sitesalt = isset($HULK->passwordsaltmain) ? $HULK->passwordsaltmain : '';
    $validated = false;

    if ($user['password'] === md5($password.$sitesalt)
            or $user->password === md5($password)
            or $user->password === md5(addslashes($password).$sitesalt)
            or $user->password === md5(addslashes($password))) {
        // Note: we are intentionally using the addslashes() here because we
        //       need to accept old password hashes of passwords with magic quotes.
        $validated = true;

    }

    return $validated;
}
/**
 * Check a password hash to see if it was hashed using the legacy hash algorithm (md5).
 *
 * @param string $password String to check.
 * @return boolean True if the $password matches the format of an md5 sum.
 */
function password_is_legacy_hash($password) {
    return (bool) preg_match('/^[0-9a-f]{32}$/', $password);
}
/**
 * Verify a password against a hash using a timing attack resistant approach
 *
 * @param string $password The password to verify
 * @param string $hash     The hash to verify against
 *
 * @return boolean If the password matches the hash
 */
function password_verify($password, $hash) {
	if (!function_exists('crypt')) {
		trigger_error("Crypt must be loaded for password_verify to function", E_USER_WARNING);
		return false;
	}
	$ret = crypt($password, $hash);
	if (!is_string($ret) || strlen($ret) != strlen($hash) || strlen($ret) <= 13) {
		return false;
	}

	$status = 0;
	for ($i = 0; $i < strlen($ret); $i++) {
		$status |= (ord($ret[$i]) ^ ord($hash[$i]));
	}

	return $status === 0;
}
  /**
  	* Logout function
  	* param string $redirectTo
  	* @return bool
  */
  function logout($redirectTo = '')
  {
    setcookie($this->remCookieName, '', time()-3600 ,'/', $this->remCookieDomain);
    $_SESSION[$this->sessionVariable] = '';
    $this->userData = '';
    $this->id = $this->userID   = '';
    $_SESSION['loggedas'] =	$this->loggedas   = '';

	unset($_SESSION);
	session_destroy();
	if ( $redirectTo != '' && !headers_sent()){
		header('Location: '.$redirectTo );
	  	exit;//To ensure security
	}
  }

  /**
  	* Function to determine if a property is true or false
  	* param string $prop
  	* @return bool
  */
  function is($prop){
  	return $this->get_property($prop)==1?true:false;
  }

    /**
  	* Get a property of a user. You should give here the name of the field that you seek from the user table
  	* @param string $property
  	* @return string
  */
  function get_property($property)
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if (!isset($this->userData[$property])) $this->error('Unknown property <b>'.$property.'</b>', __LINE__);
    return $this->userData[$property];
  }
  function getName()
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    return $this->userData['firstname'].' '.$this->userData['lastname'];
  }

  /**
  	* Is the user an active user?
  	* @return bool
  */
  function is_active()
  {
    return $this->userData[$this->tbFields['active']];
  }

  /**
   * Is the user loaded?
   * @ return bool
   */
  function is_loaded()
  {
    return empty($this->userID) ? false : true;
  }
  /**
  	* Activates the user account
  	* @return bool
  */
  function activate()
  {
    if (empty($this->userID)) $this->error('No user is loaded', __LINE__);
    if ( $this->is_active()) $this->error('Allready active account', __LINE__);
    $res = $this->query("UPDATE `{$this->dbTable}` SET {$this->tbFields['active']} = 1
	WHERE `{$this->tbFields['userID']}` = '".$this->escape($this->userID)."' LIMIT 1");
    if (@mysql_affected_rows() == 1)
	{
		$this->userData[$this->tbFields['active']] = true;
		return true;
	}
	return false;
  }
  /*
   * Creates a user account. The array should have the form 'database field' => 'value'
   * @param array $data
   * return int
   */
  function insertUser($data){
    if (!is_array($data)) $this->error('Data is not an array', __LINE__);
    switch(strtolower($this->passMethod)){
	  case 'sha1':
	  	$password = "SHA1('".$data[$this->tbFields['pass']]."')"; break;
	  case 'md5' :
	  	$password = "MD5('".$data[$this->tbFields['pass']]."')";break;
	  case 'nothing':
	  	$password = $data[$this->tbFields['pass']];
	}
    foreach ($data as $k => $v ) $data[$k] = "'".$this->escape($v)."'";
    $data[$this->tbFields['pass']] = $password;
    $this->query("INSERT INTO `{$this->dbTable}` (`".implode('`, `', array_keys($data))."`) VALUES (".implode(", ", $data).")");
    return (int)mysql_insert_id($H_DB->con);
  }
  /*
   * Creates a random password. You can use it to create a password or a hash for user activation
   * param int $length
   * param string $chrs
   * return string
   */
  function randomPass($length=10, $chrs = '1234567890qwertyuiopasdfghjklzxcvbnm'){
    for($i = 0; $i < $length; $i++) {
        $pwd .= $chrs{mt_rand(0, strlen($chrs)-1)};
    }
    return $pwd;
  }
  ////////////////////////////////////////////
  // PRIVATE FUNCTIONS
  ////////////////////////////////////////////

  /**
  	* SQL query function
  	* @access private
  	* @param string $sql
  	* @return string
  */
  function query($sql, $line = 'Uknown')
  {
    //if (defined('DEVELOPMENT_MODE') ) echo '<b>Query to execute: </b>'.$sql.'<br /><b>Line: </b>'.$line.'<br />';
	$res = $this->LMS->Execute( $sql);
	if ( !res )
		$this->error(mysql_error($H_DB->con), $line);
	return $res->GetRows();
  }

  /**
  	* A function that is used to load one user's data
  	* @access private
  	* @param string $userID
  	* @return bool
  */
  function loadUser($userID)
  {
	$res = $this->LMS->Execute("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '".$this->escape($userID)."' LIMIT 1");
    if ( !$res )   	return false;
    $this->userData = $res->fields;
    $this->userID = $userID;
    $_SESSION[$this->sessionVariable] = $this->userID;
    return true;
  }

  /**
  	* Produces the result of addslashes() with more safety
  	* @access private
  	* @param string $str
  	* @return string
  */
  function escape($str) {
    global $H_DB;

    $str = get_magic_quotes_gpc()?stripslashes($str):$str;
    // $str = mysql_real_escape_string($str); // Comento por que no es compatible si estoy usando ADODB con otra base de datos
    return $str;
  }

  /**
  	* Error holder for the class
  	* @access private
  	* @param string $error
  	* @param int $line
  	* @param bool $die
  	* @return bool
  */
	function error($error, $line = '', $die = false) {
		if ( $this->displayErrors )
			echo '<b>Error: </b>'.$error.'<br /><b>Line: </b>'.($line==''?'Unknown':$line).'<br />';
		if ($die) exit;
		return false;
	}

	//TODO: Requiere logearse, si no manda a pagina de login.
	function require_login() {
		if (!$this->is_loaded())
		{
			if ($_SERVER['QUERY_STRING'])
				$_SERVER['QUERY_STRING']="?".$_SERVER['QUERY_STRING'];
			$_SESSION['login_redirect']= $_SERVER['PHP_SELF'].$_SERVER['QUERY_STRING'];
			header('Location: login.php');
			exit;
		}
		if(!$this->get_role()){
			$this->require_academy();
		}
		return true;
	}

	// Hay paginas que requieren academia
	function require_academy(){
		if($this->academyid==0){
			header('Location: login.php');
			exit;
		}
	}
	// Hay partes que necesitan academia
	function has_academy(){
		if($this->academyid==0){
			return false;
		}
		return true;
	}

	//TODO:
	function get_role($field='id',$user=NULL,$academy=NULL) {

		global $HULK;

		if ( !$user ){
			if ( $this->loggedas>0 ){
				$user=$this->loggedas;
			}else{
				$user=$this->userID;
			}
		}

		if(!$academy) $academy=$this->academyid;

		if($role = $this->H_DB->GetOne("SELECT r.{$field} FROM h_role r
									INNER JOIN h_role_assignments ra ON ra.roleid=r.id
									WHERE ra.userid={$user} LIMIT 1")){
			return $role;
		}elseif($role = $this->LMS->GetOne("SELECT r.{$field} FROM mdl_role r
									INNER JOIN mdl_role_assignments ra ON ra.roleid=r.id
									WHERE ra.userid={$user} LIMIT 1")){
			//Si no existe el role para este usuario para esta academia en el hulk lo sincronizo.
			if ($role==1){
				$rolehulk=1;
			}elseif($role==2){
				$rolehulk=2;
			}elseif(in_array($role, array(8,9))){
				$rolehulk=8;
			}

			if ($rolehulk>0 AND $academy>0){
				$ra->roleid=$rolehulk;
				$ra->userid=$user;
				$ra->academyid=$academy;
				$this->H_DB->INSERT("h_role_assignments",$ra);
				return $rolehulk;
			}
		}

		return false;
	}

	// Old function
	function get_academys() {
		$academys	= $this->LMS->GetAll("SELECT a.id
											FROM mdl_proy_academy a
											WHERE a.deleted=0
											ORDER BY a.id");
		return $academys;
	}

	function getAcademys($view='view') {

		if($this->academyid==0){
			$view="all";
		}

		if ($view=='view'){
			// Devuelve las academias a ver en base a la que está logeado
			// si es proydesa le pasa los id de ias e ift
			// si no tiene academia la academia por defecto es proydesa
			if ($this->academyid==1 or $this->academyid==0){
				$return='1,2,3';
			}else{
				$return=$this->academyid;
			}
		}else if($view=='all'){
			// Devuelve todas las academias del usuario.
			$return	= $this->LMS->GetOne("SELECT GROUP_CONCAT(DISTINCT a.id) as ids
											FROM mdl_proy_academy a
											WHERE a.deleted=0
											GROUP BY a.deleted ORDER BY shortname");
		}else if($view=='loggedin'){
			// Devuelve especificamente la academia donde está logeado el usuario
			$return=$this->academyid;
		}
		return $return;
	}
	// Devuelve las academias en las que el usuario tiene el permiso recibido.
	function get_academys_cap($capability)
	{
		$sql= "SELECT GROUP_CONCAT(DISTINCT ra.academyid) as ids FROM h_role_assignments ra
			   INNER JOIN h_role_capabilities rc ON ra.roleid=rc.roleid
			   WHERE ra.userid={$this->userID} AND rc.capability='{$capability}';";
		$return	= $this->H_DB->GetOne($sql);

		return $return;

	}
	// Devuelve los usuarios que tienen el permiso recibido.
	function get_users_cap($capability)
	{
		$sql= "SELECT GROUP_CONCAT(DISTINCT ra.userid) as ids FROM h_role_capabilities rc
											INNER JOIN  h_role r ON r.id = rc.roleid
											INNER JOIN h_role_assignments ra ON ra.roleid = rc.roleid
											WHERE rc.capability='{$capability}';";
		$return	= $this->H_DB->GetOne($sql);

		return $return;

	}

	//TODO: Si tiene el permiso devuelve true si no devuelve false
	function has_capability($capability,$user=NULL,$academy=NULL) {
		global $HULK;

		$role = $this->get_role('id',$user,$academy);
		if ($role){
			$capa = $this->H_DB->GetRow("SELECT * FROM h_role_capabilities rc
									WHERE  rc.roleid={$role} AND rc.capability='{$capability}'
									LIMIT 1");
			if ($capa){
				return true;
			}
		}
		return false;
	}

	//TODO: Si tiene el permiso devuelve true si no carga pagina de error
	function require_capability($capability,$user=NULL,$academy=NULL) {
		global $HULK;

		$role = $this->get_role('id',$user,$academy);
		if ($role){
			$capa = $this->H_DB->GetRow("SELECT * FROM h_role_capabilities rc
									WHERE  rc.roleid={$role} AND rc.capability='{$capability}'
									LIMIT 1");
			if ($capa){
				return true;
			}
		}
		show_error('Error de acceso','No tenes permisos para acceder.');
		die();
	}



	function loginas($user){

		if ($this->has_capability('site/loginas',$this->userID)){
			if($user==$this->userID){
		    $_SESSION['loggedas'] =	$this->loggedas   = '';
			}else{
		    $_SESSION['loggedas'] =	$this->loggedas   = $user;
			}
			$res = $this->LMS->Execute("SELECT * FROM `{$this->dbTable}` WHERE `{$this->tbFields['userID']}` = '".$this->escape($user)."' LIMIT 1");
   		if ( !$res )   	return false;
   		$this->userData = $res->fields;
		}
	}

}
?>