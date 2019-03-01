<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

$v();

function comisiones(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';
		
	if($_REQUEST['q']){
		$WHERE .= " AND c.fullname LIKE '%{$_REQUEST['q']}%'";
		$data['q'] = $_REQUEST['q'];
	}		
	if($_REQUEST['carreras']){
		$WHERE .= " AND cm.id IN(".implode(",",$_REQUEST['carreras']).")";
		$data['carreras_sel'] = $_REQUEST['carreras'];
	}else{
		$data['carreras_sel'] = array(0);
	}
	if($_REQUEST['periodos']){
		$WHERE .= "AND c.periodo IN('".implode("','",$_REQUEST['periodos'])."')";
		$data['periodos_sel'] = $_REQUEST['periodos'];
	}else{
		$data['periodos_sel'] = array(0);
	}
	if($_REQUEST['ctrlf']){
		$WHERE .= " AND cm.fullname LIKE '%Control F%'";
		$data['ctrlf'] = "ctrlf";
	}else{
		$WHERE .= " AND cm.fullname NOT LIKE '%Control F%'";	
		$data['ctrlf'] = "";		
	}
	if($_REQUEST['bridge']){
		$WHERE .= " AND cm.fullname LIKE '%Bridge%'";
		$data['bridge'] = "bridge";
	}else{
		$WHERE .= " AND cm.fullname NOT LIKE '%Bridge%'";	
		$data['bridge'] = "";		
	}
		
	$data['rows'] = $LMS->GetAll("SELECT c.id, c.shortname AS comi, cm.fullname AS course, c.periodo,
																(SELECT GROUP_CONCAT(CONCAT(lastname, ', ',firstname) SEPARATOR ' / ')
																 FROM mdl_user u INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																 INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																 WHERE ra.roleid=4 AND ctx.instanceid=c.id) AS instructor,
																(SELECT COUNT(*)
																 FROM mdl_user u INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																 INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																 WHERE ra.roleid=5 AND ctx.instanceid=c.id) AS cursantes,
																c.startdate, c.enddate
																FROM mdl_course c INNER JOIN mdl_course cm ON c.from_courseid=cm.id
																WHERE cm.fullname LIKE '%Instructores%' AND c.academyid=1 {$WHERE};");
	
	$data['carreras'] = $LMS->GetAll("SELECT DISTINCT cm.id, cm.fullname, cm.shortname
																		FROM mdl_course c INNER JOIN mdl_course cm ON c.from_courseid=cm.id
																		WHERE cm.fullname LIKE '%Instructores%' AND c.academyid=1
																		ORDER BY shortname;");
																
	$data['periodos'] = $LMS->GetAll("SELECT DISTINCT c.periodo 
																		FROM mdl_course c INNER JOIN mdl_course cm ON c.from_courseid=cm.id
																		WHERE cm.fullname LIKE '%Instructores%' AND c.academyid=1;");
																		
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/comisiones', $data);
	//$view->Load('footer');
	
}
	
function instructores(){
	
	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';

	if($_REQUEST['academias']){
		$WHERE .= " AND a.id IN(".implode(",",$_REQUEST['academias']).")";
		$data['academias_sel'] = $_REQUEST['academias'];
	}else{
		$data['academias_sel'] = array(0);
	}

	$data['rows'] = $LMS->getInstructores();
																
	$data['academias'] = $LMS->getAcademys();

	//$data['carreras'] = $LMS->GetAll("");												 
	
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/instructores', $data);
	$view->Load('footer');
}

function agregar(){
	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';


	//$data['carreras'] = $LMS->GetAll("");												 
	
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/agregar', $data);
	$view->Load('footer');

}
		
		
function cargar_notas(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';
		
	$id = $_REQUEST['id'];
	
	$data['comision'] = $LMS->GetRow("SELECT * FROM mdl_course WHERE id={$id};");
	
	// Traigo los exámenes que tiene asignado esa comisión
	$data['examenes'] = $LMS->GetAll("SELECT * FROM mdl_grade_items 
																		WHERE itemmodule IN('quiz', 'assignment') AND courseid={$id}
																		ORDER BY sortorder;");
//show_array($data['examenes']);die();																			
	// Traigo todos los enrolados
	$instructores = $LMS->GetAll("SELECT u.id, CONCAT(u.firstname, ' ', u.lastname) AS inst
																FROM mdl_user u 
																INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																WHERE ra.roleid=5 AND ctx.instanceid={$id}
																ORDER BY u.lastname, u.firstname;");

	foreach($instructores as $instructor){
		// Recorro todas las notas
		foreach($data['examenes'] as $examen){
			// Traigo las notas de ese instructor en esa comisión
			$nota = $LMS->GetRow("SELECT * FROM mdl_grade_grades 
														WHERE userid={$instructor['id']} AND itemid={$examen['id']};");
														 
			if(strpos($examen['itemname'], "Graduaci")===false){
				$instructor['notas'][$examen['id']] = $nota['finalgrade'];
			}else{
				$instructor['graduacion_id'] = $examen['id'];
				$instructor['graduacion_nota'] = $nota['finalgrade'];
			}
		}
		$data['instructores'][$instructor['id']] = $instructor;
	}
	show_array($data['examenes']);
	show_array($data['instructores']);
	
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/cargar_notas', $data);
	//$view->Load('footer');	

}
	
		
function contactos(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';
			
	if($_POST['convenios']){
		$convenios = implode(',',$_POST['convenios']);
		$data['convsel'] = $_POST['convenios'];
	}else{
		$data['convsel']=array(0);
	}
			
	if($_POST['convenios']){
																						 
		$data['mcs'] = $LMS->GetAll("SELECT u.id, CONCAT(lastname, ', ', firstname) AS mc, u.email, 
																 r.name AS role, a.name AS acad
																 FROM mdl_user u 
																 INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																 INNER JOIN mdl_role r ON ra.roleid=r.id
																 INNER JOIN mdl_proy_academy a ON ra.academyid=a.id
																 WHERE r.id IN(8,9) 
																 AND a.id IN(SELECT DISTINCT academyid
																						 FROM mdl_proy_academy_convenio 
																						 WHERE convenioid IN({$convenios})
																						 AND academyid NOT IN(1,2,3,50))
																 ORDER BY acad, lastname, firstname;");
	}
	
	$data['convenios']=$LMS->GetAll("SELECT	co.id, co.name
																	 FROM mdl_convenios co
																	 WHERE co.name NOT LIKE '%Instructores%' AND deleted=0 
																	 ORDER BY co.name");		
	
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/contactos', $data);
	//$view->Load('footer');
	
}
	
	
function capacitados(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';
	
	if($_POST['periodos']){		
		
		$data['periodos_sel'] = $_POST['periodos'];
		
		$periodos = implode(',',$_POST['periodos']);
		
		$data['instructores'] = $LMS->GetAll("SELECT CONCAT(u.lastname, ' ', u.firstname) AS inst, cm.fullname AS curso, 
																					c.fullname AS comi, c.id AS comiid, u.id AS userid
																					FROM mdl_user u
																					INNER JOIN mdl_grade_grades gg ON gg.userid=u.id
																					INNER JOIN mdl_grade_items gi ON gi.id=gg.itemid
																					INNER JOIN mdl_course c ON gi.courseid=c.id
																					INNER JOIN mdl_course cm ON cm.id=c.from_courseid
																					WHERE gi.itemname LIKE '%Graduaci%' AND gg.finalgrade='3' 
																					AND cm.fullname LIKE '%Instructores%' AND c.periodo IN({$periodos})
																					ORDER BY curso, comi, inst;");
	}else{
		$data['periodos_sel'] = array(0);
	}
	
	
	$data['periodos_user'] = $LMS->GetAll("SELECT DISTINCT periodo 
																				 FROM mdl_course c 
																				 WHERE c.periodo != ''
																				 AND c.academyid IN ({$H_USER->getAcademys('view')})
																				 ORDER BY periodo DESC;");
	
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/capacitados', $data);
	//$view->Load('footer');

}

//alan	
function convacad(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;

	require_once 'config.php';
	
	$data['convenios']=$LMS->GetAll("SELECT	co.id, co.name
																	 FROM mdl_convenios co
																	 WHERE co.deleted!=1
																	 ORDER BY co.name");
																	 
	$data['academias']=$LMS->GetAll("SELECT	ac.id, ac.name
																	 FROM mdl_proy_academy ac	
																	 WHERE ac.deleted!=1 AND ac.id!=50																	 
																	 ORDER BY ac.name
																	 ");
	$data['academiasolas']=$_POST['academias'];
	$data['conveniosolos']=$_POST['convenios'];
	if($_POST['convenios']){
		$idconvenio = implode(',',$_POST['convenios']);
		$data['convsel'] = $_POST['convenios'];
		
		$data['academy'][$idconvenio]=$LMS->GetAll("SELECT ac.id,ac.name, co.id
			FROM mdl_proy_academy ac
			INNER JOIN mdl_proy_academy_convenio AcC ON AcC.academyid=ac.id
			INNER JOIN mdl_convenios co ON co.id=AcC.convenioid
			WHERE AcC.convenioid IN ({$idconvenio}) AND acC.academyid!=50");
	}else{
		//$idconvenio=array(0);
		$data['convsel']=array(0);
		$data['conveniosolos']=array(0);
	}
	

	if($_POST['academias']){
		$idacademia = implode(',',$_POST['academias']);
		$data['acadsel'] = $_POST['academias'];
		
		$data['conveny'][$idacademia]=$LMS->GetAll("SELECT co.id,co.name, ac.id
			FROM mdl_convenios co 
			INNER JOIN mdl_proy_academy_convenio AcC ON AcC.convenioid=co.id
			INNER JOIN mdl_proy_academy ac ON ac.id=AcC.academyid
			WHERE AcC.academyid IN ({$idacademia})");
	}else{
		$data['acadsel']=array(0);
		$data['academiasolas']=array(0);
	}
	
	$view->Load('header');
	$view->Load('menu');
	$view->Load('capacitacion/convacad', $data);
	//$view->Load('footer');	
																	 
}
//alan

?>
