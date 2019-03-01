<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'contactenos':
			
		$menuroot['ruta'] = array("Formulario de contacto"=>"#");

		$data['rows'] = $H_DB->GetAll("SELECT en.* FROM h_email_notif en
								ORDER BY en.id ASC;");
																	 														 
		break;

		
	default:
		$v	=	'index';
		break;
}

$view->Load('header');
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('formularios/'.$v, $data);
//$view->Load('footer');
?>
