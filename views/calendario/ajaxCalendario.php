<?php 

require_once '../../config.php';
require_once '../../libraries/Input.php';
require_once '../../libraries/Calendar.php';
//$H_USER->require_login();

header("Content-Type: application/json; charset=utf-8", true);

if(!Input::exists()) die(json_encode(array('status'=>'fail','message'=>'faltan parámetros')));

$_calendar = new Calendar();

switch(Input::get('mode')) {
	case 'save':
		
		if(!$H_USER->has_capability('calendario/edit')) die(json_encode(array('status'=>'restricted','message'=>'No tienes los permisos para hacer esta operación.')));
		
		if(Input::get('type') == 'holiday' && !$H_USER->has_capability('calendario/edit/holidays')){
			die(json_encode(array('status'=>'restricted','message'=>'No tienes los permisos para hacer esta operación.')));
		}

		if(!$_calendar->save()) die(json_encode(array('status'=>'fail','message'=>'No tienes los permisos para hacer esta operación.')));
		echo json_encode(array('status'=>'ok'));
		break;

	case 'savecourse':
		if(!$_calendar->savecourse()) die(json_encode(array('status'=>'fail','message'=>'error en la base de datos')));
		echo json_encode(array('status'=>'ok'));
		break;

	case 'get':
		$calendar = $_calendar->get();
		echo json_encode(array('status'=>'ok','get'=>$calendar ));
		break;

	case 'deletecourse':
		$_calendar->deletecourse(Input::get('id'));
		echo json_encode(array('status'=>'ok'));
		break;

	case 'getcourses':
		$courses = $_calendar->getcourses();
		echo json_encode(array('status'=>'ok','results'=>$courses));
		break;


	case 'generate_image':
		if(!$H_USER->has_capability('calendario/cronograma')) die(json_encode(array('status'=>'fail','message'=>'No tienes permiso para realizar esta operación')));
		$filename = $_calendar->generate_image();
		echo json_encode(array('status'=>'ok','filename'=>$filename));
		break;

	case 'delete_image':
		if(!$H_USER->has_capability('calendario/cronograma')) die(json_encode(array('status'=>'fail','message'=>'No tienes permiso para realizar esta operación')));
		if(!$_calendar->delete_image(Input::get('id'))) die(json_encode(array('status'=>'fail')));
		echo json_encode(array('status'=>'ok'));
		break;
	
	default:
		echo json_encode(array('status'=>'fail','message'=>'faltan parámetros'));
		break;
}

?>