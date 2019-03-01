<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){

/************************/
	case 'list':

		$menuroot['ruta'] = array("Listado de academias"=>"#");

		$H_USER->require_capability("academy/viewall");

		$data['rows']		= $LMS->getAcademys();

		$contar[0] = 0;
		$contar[1] = 0;
		$contar[3] = 0;
		foreach($data['rows'] as $row){
			$contar[$row['status']]+=1;
		}
		$data['contar']=$contar;
		$HULK->SELF = $_SERVER['PHP_SELF']."?v={$v}&p={$p}";
		break;
/***********************/
	case 'new':
		$id = $_REQUEST['id'];

		// Si id entonces edit
		if ($_POST){
			if ($id){
				$form_data = $_POST;
				$form_data['id']=$id;
				$LMS->setAcademy($form_data);
				header("Location: academy.php?v=view&id={$id}");
			}else{
				$form_data = $_POST;
				$form_data['name'] = utf8_decode($_POST['name']);
				$id = $LMS->newAcademy($form_data);
				header("Location: academy.php?v=view&id={$id}");
			}
		}

		break;
/***********************/
	case 'view':

		if (!$id = $_REQUEST['id']){
			show_error("Falta id","Debe seleccionar una academia para mostrar.");
		}

		$H_USER->require_capability("academy/view");

		$H_USER->require_capability("academy/viewall");

		// Modulo de activad
		if ($_REQUEST['action']=='newactivity'){
			$H_DB->setActivity($_POST);
			header("Location: {$HULK->STANDARD_SELF}");
		  exit;			
		}

		$data	=	$LMS->getAcademyActivity($id);
		if(!$data['row'] = $LMS->getAcademy($id)){
			show_404();
		}

		$menuroot['ruta'] = array("Academias"=>"academy.php?v=list",$data['row']['name']=>"#");
		break;
/*****************/
	///////////// EDIT 24.03.2017 //////////////
	case 'reportes':

		$H_USER->require_capability('academy/view');
		$H_USER->require_capability("academy/viewall");

		if(isset($_POST['fd_date_from']) && isset($_POST['fd_date_to'])){
			$from  = tounixtime(str_replace('/', '-', $_POST['fd_date_from']));
			$to = tounixtime(str_replace('/', '-', $_POST['fd_date_to']));
			// 30/08/2018 Se suma 1 dia para contemplar la fecha fin MDIAZ
			$to = strtotime('+1 day', $to);
		}else{
			$from = time()-(3*31*24*60*60);
			$to = time();
		}
		$data = $LMS->getActivitiesByGroup($from,$to,$_REQUEST['academias'],$_POST['radio_views'],$_POST['users'],$_POST['fd_date_search']);

		$data['acad_sel'] = array();
		if(isset($_REQUEST['academias'])){
			$data['acad_sel'] = $_REQUEST['academias'];
		}
		$data['user_sel'] = array();
		if(isset($_POST['users'])){
			$data['user_sel'] = $_POST['users'];
		}
		$data['academias_list'] = $LMS->getAcademys();
		$data['academias_users'] = $LMS->getUsersActivities();
		//$data['academias_user'] = $LMS->getAcademy();
	break;
/*****************/
	case 'aulas':
		$data['academyid']	= $academyid = $_REQUEST['academyid'];

		// Genero o actualizo Aulas!
		switch($_GET['action']){
			case 'new':
				if ($_POST['id']>0){
					$H_DB->update('h_academy_aulas',$_POST,"id = {$_POST['id']}");
				}else{
					$H_DB->insert('h_academy_aulas',$_POST);
				}

				break;

			case 'delete':
				if ($_GET['id']>0){
					$H_DB->delete('h_academy_aulas',"id = {$_GET['id']}",false);
				}

			break;

		}

		$data['aulas']	= $H_DB->GetAll("SELECT * FROM h_academy_aulas WHERE academyid={$academyid};");

		break;
	case 'widget-instructores':
		$id = $_REQUEST['id'];
		$data['instructores']= $LMS->getAcademyInstructor($id);
		$view->Load('academy/widget-instructores',$data);
		die();
		break;
	case 'widget-cursos':
		$data['id'] = $_REQUEST['id'];
		$data['cursos']=$LMS->getAcademyCourse($data['id'],$HULK->periodo);
		$view->Load('academy/widget-cursos',$data);
		die();
		break;
	case 'widget-convenios':
		$data['id'] = $_REQUEST['id'];
		$data['convenios']	= $LMS->getAcademyConvenios($data['id']);
		$view->Load('academy/widget-convenios',$data);
		die();
		break;
	case 'widget-table_inscriptos':

		$academys = $_REQUEST['academy'];
		$data['periodos'] = $periodos = $_REQUEST['periodo'];
		if (is_array($academys)){
			$academys = implode(",",$academys);
		}

		if (is_array($periodos)){
			$SQLperiodos = "AND c.periodo IN (";
			$SQLperiodos .= implode(",",$periodos);
			$SQLperiodos .= ")";
			sort($data['periodos']);
		}

		$inscriptos				=	$LMS->GetAll("SELECT cm.fullname AS model,c.periodo,c.intensivo,
													SUM((SELECT count(u.id) FROM mdl_role_assignments ra
														INNER JOIN mdl_user u ON ra.userid = u.id
														INNER JOIN mdl_context ctx ON ra.contextid = ctx.id
														WHERE ra.roleid = 5 AND ctx.instanceid = c.id
														AND (EXISTS(SELECT 1 AS Not_used FROM vw_gradebook
																				WHERE ctx.instanceid = vw_gradebook.courseid
																				AND u.id = vw_gradebook.userid) = 0))) as estudiantes
												FROM mdl_course c
												INNER JOIN mdl_course cm ON c.from_courseid=cm.id
												WHERE
												c.academyid IN ({$academys})
												{$SQLperiodos}
												AND cm.shortname NOT LIKE '%.I%'
												AND cm.shortname NOT LIKE '%CTRL%'
												AND cm.shortname NOT LIKE '%-%'
												GROUP BY c.intensivo,model,c.periodo ORDER BY model,c.periodo ASC;");
		if ($inscriptos){
				foreach($inscriptos as $ins){
					if($ins['intensivo']==1){
					$data['inscriptos'][$ins['model']." Intensivo"][$ins['periodo']]=$ins['estudiantes'];
					$data['inscriptos'][$ins['model']." Intensivo"]['total'] +=$ins['estudiantes'];
					}
					else{
					$data['inscriptos'][$ins['model']][$ins['periodo']]=$ins['estudiantes'];
	        $data['inscriptos'][$ins['model']]['total'] +=$ins['estudiantes'];
					}
				}
			}
			$view->Load('academy/'.$v,$data);
			die();
		break;
	case 'grilla_aulas':

		$academys = $_REQUEST['id'];

		$menuroot['ruta'] = array($LMS->getField("mdl_proy_academy","name",$academys)=>"academy.php?v=view&id=".$academys,"Grilla de aulas"=>"#");

		$data['dias']		=	array('L'=>'Lunes','M'=>'Martes','W'=>'Miercoles','J'=>'Jueves','V'=>'Viernes','S'=>'Sabados');
		//	$data['turnos']		=	array('M'=>'Mañana','T'=>'Tarde','N'=>'Noche');
		$data['aulas']	= $H_DB->GetAll("SELECT name, capacity FROM h_academy_aulas WHERE academyid={$academys};");

		$courses	= $LMS->GetAll("SELECT c.id, c.shortname, cm.shortname AS modelo FROM mdl_course c
															INNER JOIN mdl_course cm ON c.from_courseid = cm.id
															WHERE c.academyid={$academys} AND c.periodo={$HULK->periodo};");

		if ($courses){
			foreach($courses as $course){
				$courses_config	= $H_DB->GetRow("SELECT h.turno, aa.name as aula, cc.courseid FROM h_course_config cc
																				INNER JOIN h_academy_aulas aa ON cc.aulaid = aa.id
																				INNER JOIN h_horarios h ON cc.horarioid =	h.id
																				WHERE courseid={$course['id']};");
				$temp1 = explode("-",$course['shortname']);
				$temp2 = explode(".",$temp1[4]);
				unset($temp3);
				for($x=0;$x<(strlen($temp2[0])-1);$x++){
					$temp3[] = $temp2[0][$x];
				}
				$instructores	= $LMS->GetAll("SELECT lastname
																					FROM mdl_role_assignments ra
																					INNER JOIN mdl_user u ON ra.userid = u.id
																					INNER JOIN mdl_context ctx ON ra.contextid = ctx.id
																					WHERE ra.roleid IN(3,4,11) AND ctx.instanceid = {$course['id']};");
				$estudiantes	= $LMS->GetOne("SELECT COUNT(u.id) as estudiantes
																					FROM mdl_role_assignments ra
																					INNER JOIN mdl_user u ON ra.userid = u.id
																					INNER JOIN mdl_context ctx ON ra.contextid = ctx.id
																					WHERE ra.roleid = 5 AND ctx.instanceid = {$course['id']};");
				unset($tempins);
				foreach($instructores as $inst){
					$tempins[] = $inst['lastname'];
				}
				if(is_array($tempins)) $tempins = implode(",",$tempins);
				$course['instructores']=$tempins;
				$course['estudiantes']=$estudiantes;
				foreach($temp3 as $dia){
					$data['courses'][$dia][$courses_config['turno']][$courses_config['aula']][] = $course;
				}
			}
		}
		break;
	case 'agregar_convenio':

		$menuroot['ruta'] = array("Academias"=>"academy.php?v=list");

		if($_POST['guardar']==1){
			show_array($_POST);
			die();
			$_POST['startdate']= tounixtime($_POST['startdate']);
			$_POST['enddate']= tounixtime($_POST['enddate']);

			if ($_POST['id']>0){
				$_POST['categoryid'] = $LMS->setConvenio($_POST['academyid'],$_POST['convenioid']);
				$LMS->update("mdl_proy_academy_convenio",$_POST,"id = {$_POST['id']}");
				header("Location: academy.php?v=view&id={$_POST['academyid']}");
			}else{
				$_POST['categoryid'] = $LMS->setConvenio($_POST['academyid'],$_POST['convenioid']);
				$LMS->insert("mdl_proy_academy_convenio",$_POST);
				header("Location: academy.php?v=view&id={$_POST['academyid']}");
			}
		}
		$data['conv']['startdate']=fromunixtime(time());
		if($_REQUEST['id']){
			$data['conv']= $LMS->GetRow("SELECT * FROM mdl_proy_academy_convenio c WHERE c.id={$_REQUEST['id']}");
			$data['acad']['select'][$data['conv']['academyid']]="selected";
			$data['acad']['readonly']="<script> $('#academyselect').css('pointer-events','none'); </script>";
			$data['conv']['select'][$data['conv']['convenioid']]="selected";
			$data['conv']['readonly']="<script> $('#convenioselect').css('pointer-events','none'); </script>";
			$data['conv']['startdate']=fromunixtime($data['conv']['startdate']);
			$data['conv']['enddate']=fromunixtime($data['conv']['enddate']);
			$menuroot['ruta'][$LMS->getField('mdl_proy_academy','name',$data['conv']['academyid'])] = "academy.php?v=view&id={$data['conv']['academyid']}";
		}elseif($_REQUEST['academyid']){
			$data['conv']['academyid']=$_REQUEST['academyid'];
			$data['acad']['select'][$_REQUEST['academyid']]="selected";
			$data['acad']['readonly']="<script> $('#academyselect').css('pointer-events','none'); </script>";
			$menuroot['ruta'][$LMS->getField('mdl_proy_academy','name',$_REQUEST['academyid'])] = "academy.php?v=view&id={$_REQUEST['academyid']}";
		}
		$data['convenios']= $LMS->getConvenios();
		$data['academias']= $LMS->getAcademys();
		break;
	case 'bloquear_convenio':
		if($_REQUEST['academyconvenioid']){
			$categoryid = $LMS->getField("mdl_proy_academy_convenio","categoryid",$_REQUEST['academyconvenioid']);
			$LMS->update("mdl_course_categories", array("visible"=> 0),"id = {$categoryid}");
			$academyid = $LMS->getField("mdl_proy_academy_convenio","academyid",$_REQUEST['academyconvenioid']);
			header("Location: academy.php?v=view&id={$academyid}");
		}
	break;
	case 'activar_convenio':
		if($_REQUEST['academyconvenioid']){
			$categoryid = $LMS->getField("mdl_proy_academy_convenio","categoryid",$_REQUEST['academyconvenioid']);
			$LMS->update("mdl_course_categories", array("visible"=> 1),"id = {$categoryid}");
			$academyid = $LMS->getField("mdl_proy_academy_convenio","academyid",$_REQUEST['academyconvenioid']);
			header("Location: academy.php?v=view&id={$academyid}");
		}
	break;
	case 'mover_historicos':
		if($_REQUEST['id']){
			$categoryid = $LMS->getField("mdl_proy_academy_convenio","categoryid",$_REQUEST['id']);
			$cc['parent']	= $HULK->cat_acad_hist;
			$cc['depth'] 	= "2";
			$cc['path'] 	= "/".$HULK->cat_acad_hist."/".$categoryid;
			$LMS->update("mdl_course_categories", $cc,"id = {$categoryid}");
			$academyid = $LMS->getField("mdl_proy_academy_convenio","academyid",$_REQUEST['id']);
			header("Location: academy.php?v=view&id={$academyid}");
		}
	break;
	case 'bloquear_academia':
		if($_REQUEST['academyid']){
			$academyid = $_REQUEST['academyid'];
			$LMS->update("mdl_proy_academy", array("status"=> 2),"id = {$academyid}");
			header("Location: academy.php?v=view&id={$academyid}");
		}
	break;
	case 'desbloquear_academia':
		if($_REQUEST['academyid']){
			$academyid = $_REQUEST['academyid'];
			$LMS->update("mdl_proy_academy", array("status"=> 0),"id = {$academyid}");
			header("Location: academy.php?v=view&id={$academyid}");
		}
	break;
	case 'widget-graf_inscriptos':

		$academys = $_REQUEST['academy'];
		$periodos = $_REQUEST['periodo'];

		if (is_array($academys)){
			$academys = implode(",",$academys);
		}

		if (is_array($periodos)){
			$SQLperiodos = "AND c.periodo IN (";
			$SQLperiodos .= implode(",",$periodos);
			$SQLperiodos .= ")";
		}

		$inscriptos				=	$LMS->GetAll("SELECT cm.shortname AS model,c.periodo,
																								SUM((SELECT count(u.id) FROM mdl_role_assignments ra
																									INNER JOIN mdl_user u ON ra.userid = u.id
																									INNER JOIN mdl_context ctx ON ra.contextid = ctx.id
																									WHERE ra.roleid = 5 AND ctx.instanceid = c.id)) as estudiantes
																							FROM mdl_course c
																		 					INNER JOIN mdl_course cm ON c.from_courseid=cm.id
																			 				WHERE
																							c.academyid IN ({$academys})
																							{$SQLperiodos}
																							AND cm.shortname NOT LIKE '%.I%'
																							AND cm.shortname NOT LIKE '%CTRL%'
																							AND cm.shortname NOT LIKE '%-%'
																							AND cm.id IN (114,118,119,120,82,83,218,85,858)
																   						GROUP BY model,c.periodo ORDER BY c.periodo ASC, model;");
		if ($inscriptos){
			foreach($inscriptos as $ins){
				$model=str_replace(":","",$ins['model']);
				$model=str_replace(".","",$model);
				$datos_graf[$model][$ins['periodo']]=$ins['estudiantes'];
				$lineas[$model]=$model;
				$labels[$model]="{label:'".$model."'}";
			}
			$data['lineas']=implode(",",$lineas);
			$data['labels']=implode(",",$labels);

			foreach($datos_graf as $curso_name=>$periodos):
				$data['graf'] .= $curso_name." = [";
				foreach($periodos as $periodo=>$cantidad):
					$datos[]	="['".$periodo."', ".$cantidad."]";
				endforeach;
				$data['graf'] .= implode(",",$datos);
				unset($datos);
				$data['graf'] .= " ];
				";
			endforeach;
		}
		$view->Load('academy/'.$v,$data);
		die();
	break;

	default:
		$v	=	'index';
		break;
}

$view->Load('header',$data);
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('academy/'.$v,$data);
$view->Load('footer');

?>
