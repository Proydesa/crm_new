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
				'Fin' =>    $fin=== '' ? '' : strtotime ($fechaReverse ." ".$fin)
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
					 Asistencia
					FROM $HULK->dbname.h_asistencia_instructor 
					WHERE idComision={$idComision} AND idAcademia={$idAcademia} AND FROM_UNIXTIME(fecha,'%Y-%m-%d')='{$fechaReverse}';"
				);
			}
			$data['cursosDelDia']=$cursoQueseDictan;
		}
			$menuroot['ruta'] = array("Asistencia Instructores"=>"asistencia.php?v=view",$data['row']['name']=>"#");
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
