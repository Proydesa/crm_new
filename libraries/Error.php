<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Error functions y Log class

*/

	function show_404($page = '')
	{
		$heading = "404 Page Not Found";
		$message = "The page you requested was not found.";

		show_error($heading, $message, 'error_404', 404);
		exit;
	}

	function show_array($message,$exit=false)
	{
		$heading = "Muestra de array";

		show_error($heading, $message, 'error_array', 500);
		if($exit)
			exit;
	}


	function show_error($heading, $message, $template = 'error_general', $status_code = 500)
	{
		global $view,$H_DB;
		//$H_DB->setActivityLog(0,$heading,$message);
		if ($template!='error_array')
			$message = '<p>'.implode('</p><p>', ( ! is_array($message)) ? array($message) : $message).'</p>';

		if (ob_get_level() > ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($HULK->dirroot.'errors/'.$template.EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
		return true;
	}


	function show_php_error($severity, $message, $filepath, $line)
	{
				$levels = array(
						E_ERROR				=>	'Error',
						E_WARNING			=>	'Warning',
						E_PARSE				=>	'Parsing Error',
						E_NOTICE			=>	'Notice',
						E_CORE_ERROR		=>	'Core Error',
						E_CORE_WARNING		=>	'Core Warning',
						E_COMPILE_ERROR		=>	'Compile Error',
						E_COMPILE_WARNING	=>	'Compile Warning',
						E_USER_ERROR		=>	'User Error',
						E_USER_WARNING		=>	'User Warning',
						E_USER_NOTICE		=>	'User Notice',
						E_STRICT			=>	'Runtime Notice'
					);

		$severity = ( ! isset($levels[$severity])) ? $severity : $levels[$severity];

		$filepath = str_replace("\\", "/", $filepath);

		// For safety reasons we do not show the full file path
		if (FALSE !== strpos($filepath, '/'))
		{
			$x = explode('/', $filepath);
			$filepath = $x[count($x)-2].'/'.end($x);
		}

		if (ob_get_level() > $this->ob_level + 1)
		{
			ob_end_flush();
		}
		ob_start();
		include($HULK->dirroot.'errors/error_php'.EXT);
		$buffer = ob_get_contents();
		ob_end_clean();
		echo $buffer;
	}


//Multiline error log class
// ersin g�ven� 2008 eguvenc@gmail.com
//For break use "\n" instead '\n'

Class log {
  //
  const DEV_LOG_DIR = './errors/crm_desarrollo.log';
  const GENERAL_ERROR_DIR = './errors/crm_General_errors.log';

  /*
   User Errors...
  */
    public function vector($vector)
    {
    $date = date('d.m.Y h:i:s');
    $log = " LOG VAR |  Date:  ".$date." \n";
	$log .= print_r($vector,1);
    $log .= " LOG VAR |  Date:  ".$date." \n";
    error_log($log, 3, self::DEV_LOG_DIR);
    }

    public function msg($msg)
    {
    $date = date('d.m.Y h:i:s');
    $log = "LOG MSG: ".$msg." |  Date:  ".$date." \n";
    error_log($log, 3, self::DEV_LOG_DIR);
    }

    /*
   General Errors...
  */
    public function error($msg)
    {
    $date = date('d.m.Y h:i:s');
    $log = $msg."   |  Date:  ".$date."\n";
    error_log($msg."   |  Tarih:  ".$date, 3, self::GENERAL_ERROR_DIR);
    }

}

$log = new log();


?>