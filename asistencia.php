<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){

/************************/
	case 'list':

		
		$HULK->SELF = $_SERVER['PHP_SELF']."?v={$v}&p={$p}";
		break;
/***********************/
	case 'save':
		global $LMS;
		$fechaReverse = substr($_POST['dia'],-4).'-'.substr($_POST['dia'],3,2).'-'.substr($_POST['dia'],0,2);
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		for($i=0;$i<count($_POST['idsCursos']);$i++){
			$idCurso = $_POST['idsCursos'][$i];
			$idRegistro = $_POST['idsRegistro'][$i];
			$idDocente = $_POST['idsDocente'][$i];
			$idDocenteReemplazo = $_POST['reemplazo'][$i];
			$inicio = $_POST['inicio'][$i];
			$fin = $_POST['fin'][$i];
			$observacion = $_POST['observacion'][$i];
			$asistio = false;
			
			foreach ($_POST['asiste'] as $id){
				if ($id == $idCurso ){
					$asistio = true;
				}
			}
			$nuevaAsistencia = array(
				'idAcademia' => $_POST['idAcademia'],
				'idComision' => $idCurso, //creates a portable date
				'fecha' => strtotime ($fechaReverse),
				'idInstructor' => $idDocente,
				'Inicio' => $inicio=== '' ? '' : strtotime ($fechaReverse." ".$inicio),
				'Fin' =>    $fin=== '' ? '' : strtotime ($fechaReverse ." ".$fin),
				'Observacion' =>    $observacion
			);
			if (!$asistio && $idDocenteReemplazo ==''){
				$nuevaAsistencia['Asistencia'] ='AUSENTE';
			}else{
				$nuevaAsistencia['Asistencia'] ='PRESENTE';	
			}
			if ($idDocenteReemplazo!=''){
				$nuevaAsistencia['idInstructorReemplazo'] =$idDocenteReemplazo;
			}	
			if ($idRegistro ==''){
				$H_DB->insert('h_asistencia_instructor',$nuevaAsistencia);
			}else{
				$H_DB->update('h_asistencia_instructor',$nuevaAsistencia,"id = {$idRegistro}");
			}
		}
		header('Location: ./asistencia.php?v=view');
		break;
/***********************/
	case 'view':
		$data['academies']		= $LMS->getAcademys();
		$data['ejecuto']=0;
		
		if (isset($_POST['startdate']) && isset($_POST['idAcademia'])){
			$data['ejecuto']=1;
			$data['dia']= $_POST['startdate'];
			$idAcademia = $_POST['idAcademia'];
			$cursoQueseDictan = $LMS->getCursosEnAcademiaQueSeDictanEnDia($idAcademia,$data['dia']);
			for($i=0;$i<count($cursoQueseDictan);$i++){
				$idComision=$cursoQueseDictan[$i]['id'];
				$cursoQueseDictan[$i]['Instructores']= $LMS->getCourseInstructors($idComision);
				$fechaReverse = substr($data['dia'],-4).'-'.substr($data['dia'],3,2).'-'.substr($data['dia'],0,2);
				$cursoQueseDictan[$i]['asistencia']	= $H_DB->GetAll(
					"SELECT id,idInstructor,idInstructorReemplazo,
					 CASE WHEN Inicio =0 THEN '' ELSE FROM_UNIXTIME(Inicio,'%H:%i') END Inicio ,
					 CASE WHEN Fin    =0 THEN '' ELSE FROM_UNIXTIME(Fin,'%H:%i')    END Fin,
					 Asistencia,
					 Observacion
					FROM $HULK->dbname.h_asistencia_instructor 
					WHERE idComision={$idComision} AND idAcademia={$idAcademia} AND FROM_UNIXTIME(fecha,'%Y-%m-%d')='{$fechaReverse}';"
				);
			}
			$data['cursosDelDia']=$cursoQueseDictan;
		}
			$menuroot['ruta'] = array("Asistencia Instructores"=>"asistencia.php?v=view",$data['row']['name']=>"#");
	break;
	case 'reporte':
		$data['instructores']= $LMS->getAcademyInstructor(1);
	
		$data['ejecuto']=0;
		
		if (isset($_POST['startdate']) && isset($_POST['enddate'])){
			$data['ejecuto']=1;
			$data['diaInicio']= $_POST['startdate'];
			$data['diaFin']= $_POST['enddate'];
			$fechaInicioReverse = substr($data['diaInicio'],-4).'-'.substr($data['diaInicio'],3,2).'-'.substr($data['diaInicio'],0,2);
			$fechaInicio = strtotime($fechaInicioReverse);
			$fechaFinReverse = substr($data['diaFin'],-4).'-'.substr($data['diaFin'],3,2).'-'.substr($data['diaFin'],0,2);
			$fechaFin = strtotime($fechaFinReverse);
			$idInstructor = $_POST['idInstructor'];
			
			$asistenciaInstructores=$H_DB->GetAll("SELECT a.id,date_format(FROM_UNIXTIME(a.fecha),'%d-%m-%Y') fecha,c.id id_comision, c.fullname nombre_comision, a.idInstructor,
		(SELECT concat(lastname,' ',firstname) nombre FROM {$HULK->lms_dbname}.mdl_user where id=IFNULL(a.idInstructorReemplazo,a.idInstructor) ) nombre_instructor,
		date_format(FROM_UNIXTIME(a.Inicio),'%H:%i') inicio,
		(select inicio from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id AND
	cconfig.dias like (SELECT  case  
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Saturday' THEN '%S%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Sunday' THEN '%D%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Monday' THEN '%L%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Tuesday' THEN '%M%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%W%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Thursday' THEN '%J%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Friday' THEN '%V%'

		END) ) deberia_iniciar,
		date_format(FROM_UNIXTIME(a.Fin),'%H:%i') fin,
		(select fin from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id AND
	cconfig.dias like (SELECT  case  
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Saturday' THEN '%S%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Sunday' THEN '%D%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Monday' THEN '%L%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Tuesday' THEN '%M%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%W%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Thursday' THEN '%J%'
			WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Friday' THEN '%V%'

		END) ) deberia_finalizar,
		a.Observacion,
		a.Asistencia
		FROM {$HULK->dbname}.h_asistencia_instructor a, {$HULK->lms_dbname}.mdl_course c WHERE a.idComision= c.id and a.fecha>=".$fechaInicio." and a.fecha<= ".$fechaFin." and ((a.idinstructor = $idInstructor and $idInstructor != 0 ) or $idInstructor = 0 )");
			
			//show_array($asistenciaInstructores);
			
		$data['asistenciaInstructores']=$asistenciaInstructores;
			
		}
			$menuroot['ruta'] = array("Asistencia Instructores"=>"asistencia.php?v=view",$data['row']['name']=>"#");
	break;
	case 'reporteXLS':
	$data['instructores']= $LMS->getAcademyInstructor(1);
	$data['ejecuto']=0;
	
	if (isset($_POST['startdate']) && isset($_POST['enddate'])){
		$data['ejecuto']=1;
		$data['diaInicio']= $_POST['startdate'];
		$data['diaFin']= $_POST['enddate'];
		$fechaInicioReverse = substr($data['diaInicio'],-4).'-'.substr($data['diaInicio'],3,2).'-'.substr($data['diaInicio'],0,2);
		$fechaInicio = strtotime($fechaInicioReverse);
		$fechaFinReverse = substr($data['diaFin'],-4).'-'.substr($data['diaFin'],3,2).'-'.substr($data['diaFin'],0,2);
		$fechaFin = strtotime($fechaFinReverse);
		$idInstructor = $_POST['idInstructor'];
		$asistenciaInstructores=$H_DB->GetAll("SELECT a.id,date_format(FROM_UNIXTIME(a.fecha),'%d-%m-%Y') fecha,c.id id_comision, c.fullname nombre_comision, 
	(SELECT concat(lastname,' ',firstname) nombre FROM {$HULK->lms_dbname}.mdl_user where id=IFNULL(a.idInstructorReemplazo,a.idInstructor) ) nombre_instructor,
	date_format(FROM_UNIXTIME(a.Inicio),'%H:%i') inicio,
	(select inicio from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id AND
cconfig.dias like (SELECT  case  
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Saturday' THEN '%S%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Sunday' THEN '%D%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Monday' THEN '%L%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Tuesday' THEN '%M%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%W%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Thursday' THEN '%J%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Friday' THEN '%V%'

	END) ) deberia_iniciar,
	date_format(FROM_UNIXTIME(a.Fin),'%H:%i') fin,
	(select fin from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id AND
cconfig.dias like (SELECT  case  
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Saturday' THEN '%S%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Sunday' THEN '%D%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Monday' THEN '%L%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Tuesday' THEN '%M%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%W%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Thursday' THEN '%J%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Friday' THEN '%V%'

	END) ) deberia_finalizar,
	a.Asistencia,
	a.Observacion
	FROM {$HULK->dbname}.h_asistencia_instructor a, {$HULK->lms_dbname}.mdl_course c WHERE a.idComision= c.id and a.fecha>=".$fechaInicio." and a.fecha<= ".$fechaFin." and ((a.idinstructor = $idInstructor and $idInstructor != 0 ) or $idInstructor = 0 )");
	$data['asistenciaInstructores']=$asistenciaInstructores;
		
	}
		$menuroot['ruta'] = array("Asistencia Instructores"=>"asistencia.php?v=view",$data['row']['name']=>"#");
		$view->Load('asistencia/'.$v,$data);
		die();
break;
	default:
		$v	=	'index';
		break;
}

$view->Load('header',$data);
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('asistencia/'.$v,$data);
$view->Load('footer');

?>
