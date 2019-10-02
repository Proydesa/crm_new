<?php

require_once '../../config.php';
require_once '../../libraries/Input.php';
require_once '../../libraries/Calendar.php';
//$H_USER->require_login();

header("Content-Type: application/json; charset=utf-8", true);

if(!Input::exists()) die(json_encode(array('status'=>'fail','message'=>'faltan parámetros')));

$Calendar = new Calendar();

switch(Input::get('mode')) {

	case 'save':

		if(!$H_USER->has_capability('calendario/edit')) die(json_encode(array('status'=>'restricted','message'=>'No tienes los permisos para hacer esta operación.')));

		if(Input::get('type') == 'holiday' && !$H_USER->has_capability('calendario/edit/holidays')){
			die(json_encode(array('status'=>'restricted','message'=>'No tienes los permisos para hacer esta operación.')));
		}

		if($unavailable = $Calendar->check_attendance(Input::get('courses'))) die(json_encode(array('status'=>'unavailable','message'=>'Ya hay una clase creada para la fecha '.$unavailable->date.' para este curso','course'=>$unavailable->course)));

		if(!$Calendar->save()) die(json_encode(array('status'=>'fail','message'=>'No tienes los permisos para hacer esta operación.')));

		if(!$Calendar->change_attendance(
			Input::get('courses'),
			Input::get('date'),
			Input::get('type'),
			Input::get('comments')
		)) die(json_encode(array('status'=>'fail','message'=>'Hubo un error al mover las fechas')));

		echo json_encode(array('status'=>'ok'));
		break;

	case 'savecourse':
		if(!$Calendar->savecourse()) die(json_encode(array('status'=>'fail','message'=>'error en la base de datos')));
		echo json_encode(array('status'=>'ok'));
		break;

	case 'get':
		$calendar = $Calendar->get();
		echo json_encode(array('status'=>'ok','get'=>$calendar ));
		break;

	case 'deletecourse':
		$Calendar->deletecourse(Input::get('id'));
		echo json_encode(array('status'=>'ok'));
		break;

	case 'getcourses':
		$courses = $Calendar->getcourses();
		echo json_encode(array('status'=>'ok','results'=>$courses));
		break;


	case 'generate_image':
		if(!$H_USER->has_capability('calendario/cronograma')) die(json_encode(array('status'=>'fail','message'=>'No tienes permiso para realizar esta operación')));
		$filename = $Calendar->generate_image();
		echo json_encode(array('status'=>'ok','filename'=>$filename));
		break;

	case 'delete_image':
		if(!$H_USER->has_capability('calendario/cronograma')) die(json_encode(array('status'=>'fail','message'=>'No tienes permiso para realizar esta operación')));
		if(!$Calendar->delete_image(Input::get('id'))) die(json_encode(array('status'=>'fail')));
		echo json_encode(array('status'=>'ok'));
		break;


	case 'get_courses_by_day':


		$courses = $Calendar->get_courses_by_date(Input::get('date'));
		echo json_encode(array('status'=>'ok','results'=>$courses,'date'=>Input::get('date')));
		break;

	default:
		echo json_encode(array('status'=>'fail','message'=>'faltan parámetros'));
		break;
}

?>