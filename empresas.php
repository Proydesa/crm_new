<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v):
	case 'view':


		$data['groups'] = $H_DB->GetAll("SELECT * FROM h_grupos ORDER BY startdate ASC");
		$data['periods'] = $LMS->GetAll("SELECT DISTINCT periodo FROM mdl_course c WHERE c.periodo != '' ORDER BY periodo DESC");

		$report = $_POST['report'];
		$data['group_sel'] = array();
		if($report == 'a'){
			if(isset($_POST['groups'])){
				$data['group_sel'] = $_POST['groups'];
			}
		}

		break;
	default:
		$v = 'index';
		break;
endswitch;


$view->Load('header');
if(empty($print)) $view->Load('menu',$data);
if(empty($print)) $view->Load('menuroot',$menuroot);
$view->Load('empresas/'.$v, $data);
if(empty($print)) $view->Load('footer');



?>