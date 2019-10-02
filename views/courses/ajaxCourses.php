<?php

require_once '../../config.php';
require_once '../../libraries/Input.php';
require_once '../../libraries/Courses.php';
require_once '../../libraries/Responses.php';

if(!$H_USER->is_loaded()) Responses::response('restricted');

header("Content-Type: application/json; charset=utf-8", true);

if(!Input::exists()) die(Responses::response('fail'));

$Courses = new Courses();

switch(Input::get('mode')):

	case 'change_cancelled_description':

		$Courses->update_cancelled_class_description(Input::get('id'),Input::get('description'));

		echo Responses::response('ok');

		break;

	default:
		echo Responses::response('fail');
		break;

endswitch;
