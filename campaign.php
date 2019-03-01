<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'new':
				$data['academias'] = $LMS->GetAll("SELECT a.id, a.name	FROM mdl_proy_academy a;");

		break;
/************************/		
	case 'list':

		$id = $_REQUEST['id'];

		if ($id){
			$WHERE = "WHERE a.contactid={$id}";
		}
		$data['activitys']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as type 
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																		   {$WHERE}
																		   ORDER BY a.startdate,a.id;");
		break;
/*****************/
	default:
		$v	=	'index';
		break;
}	
$view->Load('header',$data);
$view->Load('menu');
$view->Load('campaign/'.$v,$data);	
$view->Load('footer');

?>
