<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];
# Role
$r = $_REQUEST['r'];


switch($v){
/***********************/
	case 'actualizacion':
		
		$alumnos = $H_DB->GetAll("SELECT DISTINCT userid, courseid, periodo, date, takenby, COUNT(*) AS total
															FROM h_cuotas h
															WHERE insc_id = 0 AND cuota = 1
															GROUP BY userid, courseid, periodo, cuota;");
		
		foreach($alumnos as $alum):
			if($alum['total']==1):
				$insc->userid = $alum['userid'];
				$insc->courseid = $alum['courseid'];
				$insc->periodo = $alum['periodo'];
				$insc->date = $alum['date'];
				$insc->takenby = $alum['takenby'];
				
				show_array($insc);
				$insc->id = $H_DB->insert("h_inscripcion", $insc);
				
				$H_DB->update("h_cuotas", array("insc_id" => $insc->id), "userid={$insc->userid} AND courseid={$insc->courseid} AND periodo={$insc->periodo}");
				unset($insc->id);
			endif;
		endforeach;
		die();
		
	case 'actualizar_comi':
		
		$inscs = $H_DB->GetAll("SELECT id, userid, courseid FROM h_inscripcion WHERE comisionid=0;");
		
		foreach($inscs as $insc):
		
			echo "UserID: ".$insc['userid']."<br />";
			
			// Traigo la comisión en el LMS
			$comi = $LMS->GetAll("SELECT c.id 
														FROM mdl_course c INNER JOIN mdl_context ctx ON c.id=ctx.instanceid
														INNER JOIN mdl_role_assignments ra ON ctx.id=ra.contextid
														WHERE c.from_courseid={$insc['courseid']} AND ra.userid={$insc['userid']}
														AND ra.roleid=5;");
														
			if(count($comi)>1){ 
				echo "Tiene más de una comision<br /><br />";
			}else{
				echo "Comi: ".$comi[0]['id']."<br /><br />";
				$H_DB->update("h_inscripcion", array("comisionid" => $comi[0]['id']), "id={$insc['id']}");
			}
																	
		endforeach;
	
		die();


	default:
		break;
}	

$view->Load('header',$data);
$view->Load('menu');
$view->Load('admin/'.$v,$data);	
$view->Load('footer');

?>
