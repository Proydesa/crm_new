<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];


$H_USER->require_capability('ftp/view');

switch($v){
/***********************/
	case 'links':
	
		$view->Load('header',$data);

		$view->Load('ftp/links',$_GET);

		$view->Load('footer');	
		break;
	default:
		$view->Load('header',$data);
		$view->Load('menu',$data);

		include 'libraries/phpWebFTP/index.php';

		$view->Load('footer');
		break;
}
?>