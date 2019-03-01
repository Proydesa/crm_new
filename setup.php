<?php

/// If there are any errors in the standard libraries we want to know!
	error_reporting(E_ALL ^ E_NOTICE);

/*
|---------------------------------------------------------------
| DEFINE APPLICATION CONSTANTS
|---------------------------------------------------------------
|
| EXT		- The file extension.  Typically ".php"
| SELF		- The name of THIS file (typically "index.php")
| FCPATH	- The full server path to THIS file
| BASEPATH	- The full server path to the dir root
|
*/

// Admin directory
$HULK->admin     = 'admin';
$HULK->dbpersist =  false;
// dbdebug para la base de datos (true/false)
$HULK->dbdebug 	=  false;
$HULK->dirroot	 = '';
$HULK->directorypermissions = 00777;
$HULK->lms_lib   = '../lms/lib';
$HULK->lms_dbpersist= false;


	define('EXT', '.php');
	define('SELF', pathinfo(__FILE__, PATHINFO_BASENAME));
	define('FCPATH', str_replace(SELF, '', __FILE__));

// Just in case it isn't defined i config.php
	if ((!isset($HULK->dirroot) OR $HULK->dirroot=='') AND function_exists('realpath') AND @realpath(dirname(__FILE__)) !== FALSE) {
		$HULK->dirroot=str_replace("\config","",realpath(dirname(__FILE__)));
		define('BASEPATH', $HULK->dirroot);
	}
	if (!isset($HULK->prefix)) $HULK->prefix = '';
	if (!isset($HULK->wwwroot)) {
		trigger_error('Fatal: $HULK->wwwroot is not configured! Exiting.');
		die;
	}

	$HULK->libdir  		= $HULK->dirroot .'/libraries';
	$HULK->langlocate	= $HULK->dirroot .'/language';
	$HULK->javascript	= $HULK->wwwroot .'/libraries/javascript';
	$HULK->SELF = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
	$HULK->STANDARD_SELF =	$_SERVER['PHP_SELF'].'?v='.$_REQUEST['v'].'&id='.$_REQUEST['id'];
	$HULK->FILE = basename($_SERVER['PHP_SELF'], ".php");

	global $HULK;
	global $SESSION;
/// Load basic libraries
	require_once "{$HULK->libdir}/Load".EXT;

/// View
	global $view;
	$view	= new H_View;
/// Database
	global $H_DB;
	$H_DB =  H_Database::getInstance();
/// LMS Database
	global $LMS;
	$LMS =  H_LMS::getInstance();
/// User
	global $H_USER;
	$H_USER	= new H_User();

/// First try to detect some attacks on older buggy PHP versions
    if (isset($_REQUEST['GLOBALS']) || isset($_COOKIE['GLOBALS']) || isset($_FILES['GLOBALS'])) {
        die('Fatal: Illegal GLOBALS overwrite attempt detected!');
    }

/// Just say no to link prefetching (Moz prefetching, Google Web Accelerator, others)
/// http://www.google.com/webmasters/faq.html#prefetchblock
    if (!empty($_SERVER['HTTP_X_moz']) && $_SERVER['HTTP_X_moz'] === 'prefetch'){
        header($_SERVER['SERVER_PROTOCOL'] . ' 404 Prefetch Forbidden');
        trigger_error('Prefetch request forbidden.');
        exit;
    }

/// Load up any configuration from the config table
	$HULK = $H_DB->get_config();

	@ini_set('memory_limit', '64M');
	@set_time_limit(3600);

/// If we want to display  errors, then try and set PHP errors to match
    if (!isset($HULK->debugdisplay)) {
        //keep it as is during installation
    } else if (empty($HULK->debugdisplay)) {
        @ini_set('display_errors', '0');
        @ini_set('log_errors', '1');
    } else {
        @ini_set('display_errors', '1');
    }

/// File permissions on created directories in the $HULK->dataroot
    if (empty($HULK->directorypermissions)) {
        $HULK->directorypermissions = 0777;      // Must be octal (that's why it's here)
    }

/// Calculate and set $HULK->ostype to be used everywhere. Possible values are:
/// - WINDOWS: for any Windows flavour.
/// - UNIX: for the rest
/// Also, $HULK->os can continue being used if more specialization is required
    if (stristr(PHP_OS, 'win') && !stristr(PHP_OS, 'darwin')) {
        $HULK->ostype = 'WINDOWS';
    } else {
        $HULK->ostype = 'UNIX';
    }
    $HULK->os = PHP_OS;

/// Hacer de cache el directorio de uploads. se configura en el htaccess. Ej: http://phpxref.com/xref/elgg/lib/uploadlib.php.source.html#l749

/// Configure ampersands in URLs

    @ini_set('arg_separator.output', '&amp;');

/// Work around for a PHP bug

    @ini_set('pcre.backtrack_limit', 20971520);  // 20 MB

/// A hack to get around magic_quotes_gpc being turned off
/// It is strongly recommended to enable "magic_quotes_gpc"!
    if (!ini_get('magic_quotes_gpc')) {
        function addslashes_deep($value) {
            $value = is_array($value) ?
                    array_map('addslashes_deep', $value) :
                    addslashes($value);
            return $value;
        }
        $_POST = array_map('addslashes_deep', $_POST);
        $_GET = array_map('addslashes_deep', $_GET);
        $_COOKIE = array_map('addslashes_deep', $_COOKIE);
        $_REQUEST = array_map('addslashes_deep', $_REQUEST);
        if (!empty($_SERVER['REQUEST_URI'])) {
            $_SERVER['REQUEST_URI'] = addslashes($_SERVER['REQUEST_URI']);
        }
        if (!empty($_SERVER['QUERY_STRING'])) {
            $_SERVER['QUERY_STRING'] = addslashes($_SERVER['QUERY_STRING']);
        }
        if (!empty($_SERVER['HTTP_REFERER'])) {
            $_SERVER['HTTP_REFERER'] = addslashes($_SERVER['HTTP_REFERER']);
        }
        if (!empty($_SERVER['PATH_INFO'])) {
            $_SERVER['PATH_INFO'] = addslashes($_SERVER['PATH_INFO']);
        }
        if (!empty($_SERVER['PHP_SELF'])) {
            $_SERVER['PHP_SELF'] = addslashes($_SERVER['PHP_SELF']);
        }
        if (!empty($_SERVER['PATH_TRANSLATED'])) {
            $_SERVER['PATH_TRANSLATED'] = addslashes($_SERVER['PATH_TRANSLATED']);
        }
    }

/// Load up theme variables (colours etc)

    if (!isset($HULK->viewdir)) {
        $HULK->viewdir = $HULK->dirroot.'/views';
    }

    if ($_GET['type']=="mobile") {
        $HULK->viewdir = $HULK->dirroot.'/mobile';
    }

/// Load up theme variables (colours etc)

    if (!isset($HULK->themedir)) {
        $HULK->themedir = $HULK->dirroot.'/themes';
    }

		$open=opendir($HULK->themedir);
		while ($files=readdir($open)){
			$filename=$HULK->themedir.'\\'.$files;
			if (!is_file($filename) AND $files!='.' AND $files!='..'){
				$HULK->themes[]=$files;
			}
		}
		closedir($open);

    if ($_REQUEST['theme']) {
    	$_SESSION['theme'] = $_REQUEST['theme'];
    }

    if ($_SESSION['theme']) {
    	$HULK->theme = $_SESSION['theme'];
    }

    if ($_REQUEST['sessperiodo']) {
        $_SESSION['sessperiodo'] = $_REQUEST['sessperiodo'];
    }

    if ($_SESSION['sessperiodo']) {
        $HULK->periodo = $_SESSION['sessperiodo'];
    }


	if ($H_USER->is_loaded()){
		if ($H_USER->has_capability('site/loginas',$H_USER->userID)){
		  if ($_REQUEST['loginas']) {
				$H_USER->loginas($_REQUEST['loginas']);
		  }

		  if ($_SESSION['loggedas']) {
				$H_USER->loginas($_SESSION['loggedas']);
		  }
		}
	}

    $HULK->lang = 'es_utf8';
	// Para probar si las fechas aparecen en español
    setlocale(LC_ALL,  array("es_ES.UTF-8","es_ES","esp"));
    $lenguage = 'es_ES.UTF-8';
	putenv("LANG=$lenguage");
	setlocale(LC_ALL, $lenguage);

?>
