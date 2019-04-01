<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

$v();

function asistencia(){

	require_once 'config.php';
	global $HULK,$LMS,$H_DB,$H_USER,$view;
	$data['v'] = $v = $_REQUEST['v'];
	$menuroot['ruta'] = array("Reporte de asistencias"=>"reportes.php?v=asistencia");

	if($_REQUEST['academias']){
			$WHERE .= " AND c.academyid IN (".implode(",", $_REQUEST['academias']).")";
			$data['acad_sel'] = $_REQUEST['academias'];
		}else{
			$data['acad_sel'] = array(0);
		}
		if($_REQUEST['periodos']){
			$WHERE .= " AND c.periodo IN (".implode(",", $_REQUEST['periodos']).")";
			$data['periodos_sel'] = $_REQUEST['periodos'];
		}else{
			$WHERE .= " AND c.periodo IN ({$HULK->periodo})";			
			$data['periodos_sel'] = array($HULK->periodo);
		}

		


		$sql		= $LMS->Execute("SELECT GROUP_CONCAT(u1.lastname SEPARATOR ' / ') AS Titulares, c.fullname, atts.sessdate AS Clase,
														 CONCAT(u.firstname,' ',u.lastname) AS Tomada_por, atts.lasttaken AS timetaken,
														 u1.id AS titular_id, u.id as tomada_por_id, c.academyid, c.periodo
														 FROM mdl_attendance_sessions atts
														 INNER JOIN mdl_user u ON atts.lasttakenby=u.id
														 INNER JOIN mdl_attendance at ON at.id=atts.attendanceid
														 INNER JOIN mdl_course c ON at.course=c.id
														 INNER JOIN mdl_context ctx ON c.id=ctx.instanceid
														 INNER JOIN mdl_role_assignments ra ON ctx.id=ra.contextid
														 INNER JOIN mdl_user u1 ON ra.userid=u1.id
														 WHERE ra.roleid!=5 {$WHERE}
														 GROUP BY c.id , atts.lasttaken
														 ORDER BY c.periodo DESC, c.fullname, Clase DESC, timetaken DESC;");

		$data['rows']		= $sql->GetRows();
		$data['academias_user'] = $LMS->getAcademys();
		$data['periodos_user'] = $LMS->getPeriodos();

	$HULK->SELF = $_SERVER['PHP_SELF']."?p={$p}&v={$v}";
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes_lms/'.$v, $data);

}
function segAsistenciaParaInstructores(){
	require_once 'config.php';
	global $HULK,$LMS,$H_DB,$H_USER,$view;
	$data['v'] = $v = $_REQUEST['v'];
	$menuroot['ruta'] = array("Reporte de seguimiento de asistencias (Instructores)"=>"reportes.php?v=segAsistenciaParaInstructores");
	$data['ejecuto']=0;
	$data['academies']		= $LMS->getAcademys();
	if (isset($_POST['startdate']) && isset($_POST['idAcademia'])){
		$data['ejecuto']=1;
		$data['dia']		= $_POST['startdate'];
		$idAcademia = $_POST['idAcademia'];
		$asistenciaTotales = $LMS->getTotalesAsistenciaEnAcademiaParaFecha($idAcademia,$data['dia']);
		$total=0;
		$presentes=0;
		$enrolados=0;
		$ausentes=0;
		
		foreach($asistenciaTotales as $row){
			$total = $total + $row['total'];
			if ($row['acronym']=='P'|| $row['acronym']=='T' ){
				$presentes = $presentes + $row['total']; 
			}
			if ($row['acronym']=='A'){
				$ausentes = $ausentes + $row['total']; 
			}
			if ($row['acronym']=='E'){
				$enrolados = $enrolados + $row['total']; 
			}
		}	
		$data['totalDeAlumnos']=$total;
		$data['presentes']=$presentes;
		$data['ausentes']=$ausentes;
		$data['enrolados']=$enrolados;
		$alumnos1Inasistencia=array();
		$alumnos2Inasistencia=array();
		$alumnos3Inasistencia=array();
		$alumnosPorCurso=array();
		$alumnosAusentes = $LMS->getAlumnosAusentesEnAcademiaEnFechaConTipo($idAcademia,$data['dia']);
		$alumnosAusetesPorCurso=array();
		for($i=0;count($alumnosAusentes)>$i;$i++){
			$idCurso = $alumnosAusentes[$i]['idCurso'];
			$alumnosAusentes[$i]['docente']=$LMS->getCourseInstructors($idCurso)[0];
			$alumnosAusentes[$i]['docente']['mailDocente']=$H_DB->GetAll("SELECT email FROM {$HULK->lms_dbname}.mdl_user where id =".$alumnosAusentes[$i]['docente']['id']);
			$idAlumno = $alumnosAusentes[$i]['idAlumno'];
			$alumnosAusentes[$i]['cantidadTotalAusentes']= $LMS->getTotalesAsistenciaEnCursoParaAlumnoConTipo($idCurso,$idAlumno,"'A'")[0]['total'];
			$cuotas=$H_DB->GetAll("SELECT DISTINCT(c.cuota) FROM {$HULK->dbname}.h_cuotas c LEFT JOIN {$HULK->dbname}.h_inscripcion h ON h.courseid=c.courseid WHERE h.comisionid={$idCurso} ORDER BY c.cuota");
			$debe="";
			$alumnosAusentes[$i]['coutas']=$coutas;
			if(count($cuotas)>0){
				foreach($cuotas as $cuota){
					$difcuota = $LMS->getValorCuota($idCurso,$idAlumno,$cuota['cuota']);
		//					$alumnosAusentes[$i][$cuota['cuota']]['difcuota']=$difcuota;
					if ($difcuota != ''){
						if ($difcuota < 0) {
					 		$debe.=" ".$cuota['cuota'] ;   
						}
					}
				}
			}
			$alumnosAusentes[$i]['debe']= ($debe=='' ? '': 'DEBE CUOTA '.$debe);	
			$alumnosPorCurso[$idCurso]['idCurso']= $idCurso;
			$alumnosPorCurso[$idCurso]['nombre']= $alumnosAusentes[$i]['docente']['fullname'];
			$alumnosPorCurso[$idCurso]['mailDocente']= $alumnosAusentes[$i]['docente']['mailDocente']['0']['email'];
			$alumnosPorCurso[$idCurso]['curso']= $alumnosAusentes[$i]['nombre_curso'];
			$alumnosPorCurso[$idCurso][($alumnosAusentes[$i]['cantidadTotalAusentes'] > 3 ? 3 : $alumnosAusentes[$i]['cantidadTotalAusentes'] ) ][]=$alumnosAusentes[$i];
			$alumnosPorCurso[$idCurso]['mayorColumna']=max(count($alumnosPorCurso[$idCurso][1]),count($alumnosPorCurso[$idCurso][2]),count($alumnosPorCurso[$idCurso][3]));

		}
		$data['alumnosPorCurso']=$alumnosPorCurso;
	}
	$asistenciaInstructores=$H_DB->GetAll("SELECT a.id,c.id id_comision, c.fullname nombre_comision, 
	(SELECT concat(lastname,' ',firstname) nombre FROM {$HULK->lms_dbname}.mdl_user where id=IFNULL(a.idInstructorReemplazo,a.idInstructor) ) nombre_instructor,
	date_format(FROM_UNIXTIME(a.Inicio),'%H:%i') inicio,
	(select inicio from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id AND
cconfig.dias like (SELECT  case  
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Saturday' THEN '%S%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Sunday' THEN '%D%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Monday' THEN '%L%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Tuesday' THEN '%M%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%X%'
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
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%X%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Thursday' THEN '%J%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Friday' THEN '%V%'

	END) ) deberia_finalizar,
	a.Observacion
	 FROM {$HULK->dbname}.h_asistencia_instructor a, {$HULK->lms_dbname}.mdl_course c WHERE a.idComision= c.id and date_format(FROM_UNIXTIME(a.fecha),'%d-%m-%Y')= '".$data['dia']."'");
		$data['asistenciaInstructores']=$asistenciaInstructores;
	$HULK->SELF = $_SERVER['PHP_SELF']."?p={$p}&v={$v}";
	
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes_lms/'.$v, $data);
	$view->Load('footer');

}
function segAsistenciaParaInstructoresXLS(){
	require_once 'config.php';
	global $HULK,$LMS,$H_DB,$H_USER,$view;
	$data['v'] = $v = $_REQUEST['v'];
	$menuroot['ruta'] = array("Reporte de seguimiento de asistencias (Instructores)"=>"reportes.php?v=segAsistenciaParaInstructores");
	$data['ejecuto']=0;
	$data['academies']		= $LMS->getAcademys();
	if (isset($_POST['startdate']) && isset($_POST['idAcademia'])){
		$data['ejecuto']=1;
		$data['dia']		= $_POST['startdate'];
		$idAcademia = $_POST['idAcademia'];
		$asistenciaTotales = $LMS->getTotalesAsistenciaEnAcademiaParaFecha($idAcademia,$data['dia']);
		$total=0;
		$presentes=0;
		$enrolados=0;
		$ausentes=0;
		
		foreach($asistenciaTotales as $row){
			$total = $total + $row['total'];
			if ($row['acronym']=='P'|| $row['acronym']=='T' ){
				$presentes = $presentes + $row['total']; 
			}
			if ($row['acronym']=='A'){
				$ausentes = $ausentes + $row['total']; 
			}
			if ($row['acronym']=='E'){
				$enrolados = $enrolados + $row['total']; 
			}
		}	
		$data['totalDeAlumnos']=$total;
		$data['presentes']=$presentes;
		$data['ausentes']=$ausentes;
		$data['enrolados']=$enrolados;
		$alumnos1Inasistencia=array();
		$alumnos2Inasistencia=array();
		$alumnos3Inasistencia=array();
		$alumnosPorCurso=array();
		$alumnosAusentes = $LMS->getAlumnosAusentesEnAcademiaEnFechaConTipo($idAcademia,$data['dia']);
		$alumnosAusetesPorCurso=array();
		for($i=0;count($alumnosAusentes)>$i;$i++){
			$idCurso = $alumnosAusentes[$i]['idCurso'];
			$alumnosAusentes[$i]['docente']=$LMS->getCourseInstructors($idCurso)[0];
			$alumnosAusentes[$i]['docente']['mailDocente']=$H_DB->GetAll("SELECT email FROM {$HULK->lms_dbname}.mdl_user where id =".$alumnosAusentes[$i]['docente']['id']);
			$idAlumno = $alumnosAusentes[$i]['idAlumno'];
			$alumnosAusentes[$i]['cantidadTotalAusentes']= $LMS->getTotalesAsistenciaEnCursoParaAlumnoConTipo($idCurso,$idAlumno,"'A'")[0]['total'];
			$cuotas=$H_DB->GetAll("SELECT DISTINCT(c.cuota) FROM {$HULK->dbname}.h_cuotas c LEFT JOIN {$HULK->dbname}.h_inscripcion h ON h.courseid=c.courseid WHERE h.comisionid={$idCurso} ORDER BY c.cuota");
			$debe="";
			$alumnosAusentes[$i]['coutas']=$coutas;
			if(count($cuotas)>0){
				foreach($cuotas as $cuota){
					$difcuota = $LMS->getValorCuota($idCurso,$idAlumno,$cuota['cuota']);
		//					$alumnosAusentes[$i][$cuota['cuota']]['difcuota']=$difcuota;
					if ($difcuota != ''){
						if ($difcuota < 0) {
					 		$debe.=" ".$cuota['cuota'] ;   
						}
					}
				}
			}
			$alumnosAusentes[$i]['debe']= ($debe=='' ? '': 'DEBE CUOTA '.$debe);	
			$alumnosPorCurso[$idCurso]['idCurso']= $idCurso;
			$alumnosPorCurso[$idCurso]['nombre']= $alumnosAusentes[$i]['docente']['fullname'];
			$alumnosPorCurso[$idCurso]['mailDocente']= $alumnosAusentes[$i]['docente']['mailDocente']['0']['email'];
			$alumnosPorCurso[$idCurso]['curso']= $alumnosAusentes[$i]['nombre_curso'];
			$alumnosPorCurso[$idCurso][($alumnosAusentes[$i]['cantidadTotalAusentes'] > 3 ? 3 : $alumnosAusentes[$i]['cantidadTotalAusentes'] ) ][]=$alumnosAusentes[$i];
			$alumnosPorCurso[$idCurso]['mayorColumna']=max(count($alumnosPorCurso[$idCurso][1]),count($alumnosPorCurso[$idCurso][2]),count($alumnosPorCurso[$idCurso][3]));

		}
		$data['alumnosPorCurso']=$alumnosPorCurso;
	}
	$asistenciaInstructores=$H_DB->GetAll("SELECT a.id,c.id id_comision, c.fullname nombre_comision, 
	(SELECT concat(lastname,' ',firstname) nombre FROM {$HULK->lms_dbname}.mdl_user where id=IFNULL(a.idInstructorReemplazo,a.idInstructor) ) nombre_instructor,
	date_format(FROM_UNIXTIME(a.Inicio),'%H:%i') inicio,
	(select inicio from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id AND
cconfig.dias like (SELECT  case  
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Saturday' THEN '%S%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Sunday' THEN '%D%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Monday' THEN '%L%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Tuesday' THEN '%M%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%X%'
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
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Wednesday' THEN '%X%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Thursday' THEN '%J%'
		WHEN DAYNAME(date_format(FROM_UNIXTIME(a.Inicio),'%Y-%m-%d') )='Friday' THEN '%V%'

	END) ) deberia_finalizar,
		a.Observacion
	 FROM {$HULK->dbname}.h_asistencia_instructor a, {$HULK->lms_dbname}.mdl_course c WHERE a.idComision= c.id and date_format(FROM_UNIXTIME(a.fecha),'%d-%m-%Y')= '".$data['dia']."'");
		$data['asistenciaInstructores']=$asistenciaInstructores;
	$HULK->SELF = $_SERVER['PHP_SELF']."?p={$p}&v={$v}";
	
		$view->Load('reportes_lms/'.$v, $data);

}
function segAsistencia(){
	
	require_once 'config.php';
	global $HULK,$LMS,$H_DB,$H_USER,$view;
	$data['v'] = $v = $_REQUEST['v'];
	$menuroot['ruta'] = array("Reporte de seguimiento de asistencias"=>"reportes.php?v=segAsistencia");
	$data['ejecuto']=0;
	$data['academies']		= $LMS->getAcademys();
	if (isset($_POST['startdate']) && isset($_POST['idAcademia'])){
		$data['ejecuto']=1;
		$data['dia']		= $_POST['startdate'];
		$idAcademia = $_POST['idAcademia'];
		$asistenciaTotales = $LMS->getTotalesAsistenciaEnAcademiaParaFecha($idAcademia,$data['dia']);
		$total=0;
		$presentes=0;
		$enrolados=0;
		$ausentes=0;
		
		foreach($asistenciaTotales as $row){
			$total = $total + $row['total'];
			if ($row['acronym']=='P'|| $row['acronym']=='T' ){
				$presentes = $presentes + $row['total']; 
			}
			if ($row['acronym']=='A'){
				$ausentes = $ausentes + $row['total']; 
			}
			if ($row['acronym']=='E'){
				$enrolados = $enrolados + $row['total']; 
			}
		}
		
		$data['totalDeAlumnos']=$total;
		$data['presentes']=$presentes;
		$data['ausentes']=$ausentes;
		$data['enrolados']=$enrolados;
		$alumnos1Inasistencia=array();
		$alumnos2Inasistencia=array();
		$alumnos3Inasistencia=array();

		$alumnosAusentes = $LMS->getAlumnosAusentesEnAcademiaEnFechaConTipo($idAcademia,$data['dia']);
		$alumnosAusetesPorCurso=array();
		for($i=0;count($alumnosAusentes)>$i;$i++){
			$idCurso = $alumnosAusentes[$i]['idCurso'];
			$alumnosAusentes[$i]['docente']=$LMS->getCourseInstructors($idCurso);
			$idAlumno = $alumnosAusentes[$i]['idAlumno'];
			$alumnosAusentes[$i]['cantidadTotalAusentes']= $LMS->getTotalesAsistenciaEnCursoParaAlumnoConTipo($idCurso,$idAlumno,"'A'")[0]['total'];
			$cuotas=$H_DB->GetAll("SELECT DISTINCT(c.cuota) FROM {$HULK->dbname}.h_cuotas c LEFT JOIN {$HULK->dbname}.h_inscripcion h ON h.courseid=c.courseid WHERE h.comisionid={$idCurso} ORDER BY c.cuota");
			$debe="";
			$alumnosAusentes[$i]['coutas']=$coutas;
			if(count($cuotas)>0){
				foreach($cuotas as $cuota){
					$difcuota = $LMS->getValorCuota($idCurso,$idAlumno,$cuota['cuota']);
					$alumnosAusentes[$i][$cuota['cuota']]['difcuota']=$difcuota;
					if ($difcuota != ''){
						if ($difcuota < 0) {
					 		$debe.=" ".$cuota['cuota'] ;   
						}
					}
				}
			}
			$alumnosAusentes[$i]['debe']= ($debe=='' ? '': 'DEBE CUOTA '.$debe);	
			if ($alumnosAusentes[$i]['cantidadTotalAusentes'] == 1 ){
				$alumnos1Inasistencia[]=$alumnosAusentes[$i];}
			if ($alumnosAusentes[$i]['cantidadTotalAusentes'] == 2 ){
				$alumnos2Inasistencia[]=$alumnosAusentes[$i];}
			if ($alumnosAusentes[$i]['cantidadTotalAusentes'] >= 3 ){
				$alumnos3Inasistencia[]=$alumnosAusentes[$i];}
		}
		$data['alumnos1Inasistencia']=$alumnos1Inasistencia;
		$data['alumnos2Inasistencia']=$alumnos2Inasistencia;
		$data['alumnos3Inasistencia']=$alumnos3Inasistencia;
		$data['mayorColumna']=max(count($alumnos1Inasistencia),count($alumnos2Inasistencia),count($alumnos3Inasistencia));

		$asistenciaInstructores=$H_DB->GetAll("SELECT a.id,c.id id_comision, c.fullname nombre_comision, 
		(SELECT concat(lastname,' ',firstname) nombre FROM {$HULK->lms_dbname}.mdl_user where id=IFNULL(a.idInstructorReemplazo,a.idInstructor) ) nombre_instructor,
		date_format(FROM_UNIXTIME(a.Inicio),'%H:%i') inicio,
		(select inicio from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id) deberia_iniciar,
		date_format(FROM_UNIXTIME(a.Fin),'%H:%i') fin,
		(select fin from {$HULK->dbname}.h_course_config cconfig, {$HULK->dbname}.h_horarios h where cconfig.horarioid= h.id and cconfig.courseid=c.id) deberia_finalizar
		 FROM {$HULK->dbname}.h_asistencia_instructor a, {$HULK->lms_dbname}.mdl_course c WHERE a.idComision= c.id and date_format(FROM_UNIXTIME(a.fecha),'%d-%m-%Y')= '".$data['dia']."'");
		$data['asistenciaInstructores']=$asistenciaInstructores;
	}
	$HULK->SELF = $_SERVER['PHP_SELF']."?p={$p}&v={$v}";
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes_lms/'.$v, $data);

}
function listados_difusion(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;
	require_once 'config.php';
	$data['v'] = $v = $_REQUEST['v'];
	$data['q'] = $q = $_REQUEST['q'];

		if($_REQUEST['periodos']){
			$data['periodos_sel'] = $_REQUEST['periodos']; $PERIODOS = implode(',',$_REQUEST['periodos']);
		}else{
			$data['periodos_sel'] = array($HULK->periodo); $PERIODOS = $HULK->periodo;
		}

		if($_REQUEST['academias']){
			$data['acad_sel'] = $_REQUEST['academias']; $ACADEMIAS = implode(',',$_REQUEST['academias']);
		}else{
			$data['acad_sel'] = array(0);	$ACADEMIAS = "0";
		}

		if($_REQUEST['cursos']){
			$data['cursos_sel'] = $_REQUEST['cursos']; $CURSOS=implode(',',$_REQUEST['cursos']);
		}else{
			$data['cursos_sel'] = array(0);	$CURSOS="0";
		}

		if($_REQUEST['roles']){
			$data['roles_sel'] = $_REQUEST['roles']; $ROLES=implode(',',$_REQUEST['roles']);
		}else{
			$data['roles_sel'] = array(5);	$ROLES="5";
		}

		if($_REQUEST['status']){
			$data['status_sel'] = $_REQUEST['status']; $STATUS=implode(',',$_REQUEST['status']);
		}else{
			$data['status_sel'] = array(0);	$STATUS="0";
		}
		if (in_array(0,$data['status_sel'])){
 			$ORSTATUSNULL = " or insc.finalgrade is NULL";
		}
		if($_REQUEST['noperiodos']){
			$data['noperiodos_sel'] = $_REQUEST['noperiodos']; $NOPERIODOS = implode(',',$_REQUEST['noperiodos']);
		}else{
			$data['noperiodos_sel'] = array(0); $NOPERIODOS = "0";
		}

		if($_REQUEST['noacademias']){
			$data['noacad_sel'] = $_REQUEST['noacademias']; $NOACADEMIAS = implode(',',$_REQUEST['noacademias']);
		}else{
			$data['noacad_sel'] = array(0);	$NOACADEMIAS = "0";
		}

		if($_REQUEST['nocursos']){
			$data['nocursos_sel'] = $_REQUEST['nocursos']; $NOCURSOS=implode(',',$_REQUEST['nocursos']);
		}else{
			$data['nocursos_sel'] = array(0);	$NOCURSOS="0";
		}
		if($_REQUEST['noroles']){
			$data['noroles_sel'] = $_REQUEST['noroles']; $NOROLES=implode(',',$_REQUEST['noroles']);
		}else{
			$data['noroles_sel'] = array(0);	$NOROLES="0";
		}
		if($_REQUEST['nostatus']){
			$data['nostatus_sel'] = $_REQUEST['nostatus']; $NOSTATUS=implode(',',$_REQUEST['nostatus']);
		}else{
			$data['nostatus_sel'] = array(0);	$NOSTATUS="0";
		}
		if (in_array(0,$data['nostatus_sel'])){
 			$NOORSTATUSNULL = " or insc2.finalgrade is NULL";
		}

		$data['sql'] = "SELECT  DISTINCT u.firstname ,u.lastname , g.name as empresa, insc.shortname AS Comision, insc.intensivo, u.id AS Roster,u.email, u.phone1, u.phone2, r.name as role, insc.finalgrade as status {$SELECT}
			FROM mdl_user as u
			LEFT JOIN {$HULK->dbname}.h_grupos_users gu ON u.id = gu.userid
			LEFT JOIN {$HULK->dbname}.h_grupos g ON gu.grupoid = g.id
			INNER JOIN {$HULK->dbname}.vw_enrolados as insc on insc.userid=u.id
			INNER JOIN mdl_role as r on r.id=insc.roleid
			where insc.roleid in ({$ROLES})
			and insc.modelid in ({$CURSOS})
			and insc.periodo in ({$PERIODOS})
			and insc.academy in ({$ACADEMIAS})
			and (u.noquierospam = 0 or u.noquierospam is null)
			and (insc.finalgrade in ({$STATUS}) {$ORSTATUSNULL} )
			AND u.id not in (SELECT distinct insc2.userid
			FROM {$HULK->dbname}.vw_enrolados as insc2
			where insc2.roleid in ({$NOROLES})
			and insc2.modelid in ({$NOCURSOS})
			and insc2.periodo in ({$NOPERIODOS})
			and insc2.academy in ({$NOACADEMIAS})
			and (insc2.finalgrade in ({$NOSTATUS}) {$NOORSTATUSNULL}));";

		$sql		= $LMS->Execute($data['sql']);
		$data['rows']		= $sql->GetRows();

		$data['academias_user'] = $LMS->getAcademys();
		$data['periodos_user'] = $LMS->getPeriodos();
		$data['cursos'] = $LMS->getCursosModelo();
		$data['roles_user'] =	array('Estudiante' => 5, 'Instructor' => 3, 'Instructor Secundario' => 4);

	$HULK->SELF = $_SERVER['PHP_SELF']."?p={$p}&v={$v}";
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes_lms/'.$v, $data);
}
?>
