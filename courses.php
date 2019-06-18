<?php

require_once 'config.php';
require_once $HULK->libdir.'/Calendar.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

$data['_monthnames'] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
$data['_daynames'] = array('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo');

switch($v){

	case 'new':
		$data['stage'] = $stage = $_REQUEST['stage'];
		$H_USER->require_capability('course/new');

		/// Cargar custom CSS para el view ///
		$_arrcss = [
			['folder'=>'themes/','style'=>'courses','rel'=>'stylesheet']
		];

		switch($stage){
			case 0:
				// TODO Elige academia
				$jquerynew = true;
				$menuroot['ruta'] = array("Nuevo curso"=>"courses.php?v=new");
				$data['academys']	= $LMS->getAcademys();
				break;
			case 1:
				
				$jquerynew = true;
				// TODO Configura curso
				$modelid		= $_REQUEST['modid'];
				$data['academyid'] = $academyid	= $_REQUEST['academyid'];
				// Id de categoria en los cursos modelo.
				$categoryid	=	$_REQUEST['carid'];
				$data['from_course']	= $LMS->getCourse($modelid);
				$menuroot['ruta'] = array("Nuevo curso"=>"courses.php?v=new",$data['from_course']['fullname']=>"#");

				$academy	= $LMS->getAcademy($academyid);

				$data['from_course']['shortname']	=	$data['from_course']['fullname']	=	$academy['country']."-".$academy['shortname'].'-'.$data['from_course']['shortname'];

				$data['categoryid'] = $LMS->getAcademyConvenioId($data['academyid'],$categoryid);
				$data['convenioid'] = $categoryid;
				$data['forma_de_pago'] = $LMS->getField("mdl_proy_academy_convenio","forma_de_pago",$data['categoryid'],"categoryid");

				//Instructores capacitados para ese curso modelo
				$data['instructores'] = $LMS->getAcademyInstructor($academyid,$modelid);			

				//Aulas de la academia
				$data['aulas'] = $H_DB->GetAll("SELECT * FROM h_academy_aulas WHERE academyid={$academyid} ORDER BY name;");

				//Horarios
				$data['schedules'] = $H_DB->GetAll("SELECT * FROM h_horarios ORDER BY name;");

				$_calendar = new Calendar();
				$data['holidays'] = $_calendar->getholidaysbyperiod(date('Y-01-01'),'','tech');				

				break;

			case 2:
				
				$course = $_POST;

				/*echo '<pre>';
				print_r($LMS->getHolidays(strtotime(date($course['startdate'])),$course['tech'],$course['holidays']));
				print_r($course);
				echo '</pre>';

				die();*/
				
				$course['shortname'] = $course['fullname'] = $course['fullname_complete'];

				$course['periodo'] = getPeriodo($course['startdate']);
				date_default_timezone_set('America/Argentina/Buenos_Aires');
				$course['timemodified'] = time();
				$course['timecreated'] = time();	

				$course['startdate'] = strtotime(date($course['startdate']));
				$course['enrolstartdate'] = 0;
				$course['enrolenddate'] = 0;

				/// Chequear si ya existe la comisión
				$comisiones = $LMS->GetOne("SELECT shortname FROM mdl_course WHERE shortname='{$course['shortname']}'");
				if(!empty($comisiones)){
					show_error("Error", "La comisión {$course['shortname']} ya existe");
					return false;
				}

				//// Crear comisión
				if(!$courseid = $LMS->create_comision($course)){
					show_error("Error","No se pudo crear la comisión");
				}
				$course['id'] = $courseid;

				//// Enrolar instructores
				if ($course['instructor']>0){
					if(!$LMS->enrolUser($course['instructor'], $courseid, 4) ){
						show_error("Error","Error al enrolar el instructor");
					}
				}
				
				if ($course['secundario']>0){
					if(!$LMS->enrolUser($course['secundario'], $courseid, 4) ){
						show_error("Error","Error al enrolar el instructor secundario");
					}
				}

				$tech = false;
				if($course['tech']){
					$tech = true;
				}

				//// Config. del course en el crm
				if ($course['aula']>0){
					$aula = $H_DB->GetRow("SELECT id, name FROM h_academy_aulas WHERE id={$course['aula']};");
				}else{
					$aula['id']=0;
				}
				$horario = explode(',',$course['schedules']);
				$daycodes = explode(',',$course['daycodes']);
				foreach($horario as $k=>$hr){					
					if(!$H_DB->insert('h_course_config',array(
						'courseid'=>$courseid,
						'aulaid'=>$aula['id'],
						'horarioid'=>$hr,
						'dias'=>$daycodes[$k]
						))){
						show_error("Error registrar la configuración del curso.");
					}
				}

				//// Insertar la actividad de la operación
				$H_DB->setActivityLog(0,"Nueva {$course['shortname']}","Comisión: <a href='courses.php?v=view&id={$courseid}'>{$course['shortname']}</a>");


				$holidays = isset($course['holidays']) ? $course['holidays'] : array();


				$course['enddate'] = $LMS->setCourseAttendance($courseid,$course['startdate'],$course['clases'],$tech,$course['daycodes'],$course['schedules'],$holidays);
				$LMS->update('mdl_course',array('enddate'=>$course['enddate']),"id={$courseid}");

				$course['category']	=	$LMS->getAcademyCategory($course['academyid']);


				$data['course'] = $course;
				break;
		}


		break;
	/***********************/

	case 'view':
		$id = $_REQUEST['id'];
		$H_USER->require_capability('course/view');

		$data['row']			= $LMS->getCourse($id);
		
		$data['estudiantes']	= $LMS->getCourseStudents($id);
		
		$data['instructores']	= $LMS->getCourseInstructors($id);

		$data['bajas']	= $H_DB->GetAll("SELECT b.* FROM h_bajas b WHERE b.comisionid={$id} AND b.cancel=0;");
	
		$data['capacidad']		= $H_DB->GetRow("SELECT capacity FROM h_academy_aulas aa
											INNER JOIN h_course_config cah ON aa.id=cah.aulaid
											WHERE cah.courseid={$id};");
		$data['horario']		= $H_DB->GetRow("SELECT h.name FROM h_horarios h
										INNER JOIN h_course_config cah ON h.id=cah.horarioid
										WHERE cah.courseid={$id};");

		if ($data['row']['lms_version']=="LMS2"){
			$data['asistencias']	= $LMS->getCourseAttendance($id);
			$data['asistencias_canceladas']	= $LMS->getCourseAttendanceCancelled($id);
			///// Agregado Rodo 20/02/2017 /////
			$data['examenes'] = $LMS->GetAll("SELECT id, itemname FROM mdl_grade_items
											WHERE itemname IS NOT NULL AND courseid={$id}
											AND itemname != 'Asistencia' AND itemname NOT LIKE '%e-Kit%' AND itemname NOT LIKE '%Graduacion%'
											ORDER BY sortorder;");
		}
		$data['cuotas'] = $H_DB->GetAll("SELECT DISTINCT(c.cuota) FROM h_cuotas c inner JOIN h_inscripcion h ON c.insc_id = h.id and h.comisionid={$id} and c.courseid={$data['row']['from_courseid']} ORDER BY c.cuota");
		
		/*$data['cuotas'] = $H_DB->GetAll("SELECT DISTINCT(c.cuota) FROM h_cuotas c LEFT JOIN h_inscripcion h ON h.courseid=c.courseid WHERE h.comisionid={$id} ORDER BY c.cuota");*/
		
		///////// END EDIT //////////////////

		break;
	/***********************/
	case 'list':

		$data['q'] = $q = $_REQUEST['q'];
		$H_USER->require_capability('course/view');
		$WHERE = " c.id>0 ";

		$menuroot['ruta'] = array("Listado de cursos"=>"courses.php?v=list");

		if(!$_REQUEST['academias']){
			$WHERE .= " AND c.academyid > 0 ";
			$data['acad_sel'] = array(0);
		}else{
			$data['acad_sel'] = $_REQUEST['academias'];
			$acadtemp = implode(',',$_REQUEST['academias']);
			$WHERE .= " AND c.academyid IN({$acadtemp})";
		}

		if($_REQUEST['periodos']){
			$data['periodos_sel'] = $_REQUEST['periodos'];
			$pertemp = implode(',',$_REQUEST['periodos']);
			$WHERE .= " AND c.periodo IN({$pertemp})";
		}else{
			$data['periodos_sel'] = array(0);
		}

		if($_POST['pais']!=""){
			$data['paisel']=$paisel=$_POST['pais'];
		}else{
			$data['paisel']=array();
		}

		if ($_REQUEST['tipo']){
			$WHERE .= " AND (";
			foreach($_REQUEST['tipo'] as $tipo){
				$WHERE .= " cm.fullname LIKE '%{$tipo}%' OR";
			}
			$WHERE = substr($WHERE,0,strlen($WHERE)-2).")";
		}

		if($_REQUEST['tipo']){
			$data['tipo_sel'] = $_REQUEST['tipo'];
		}else{
			$data['tipo_sel'] = array("");
		}

		if ($q){
			$data['filtros'] = $search_words = explode(' ',$q);

			foreach($search_words as $word){
				$not=''; $con=' OR ';
				if (substr($word, 0, 1)=='-'){$con=' AND ';  $not=' NOT '; $word=substr($word, 1); }

				$WHERE .=" AND (c.shortname {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} c.fullname {$not} LIKE '%{$word}%' )";
			}
		}

		// Cantidad de resultados
		$max	= $LMS->Execute("SELECT COUNT(*) AS Total
													 FROM {$HULK->dbname}.vw_course c INNER JOIN {$HULK->dbname}.vw_course cm ON c.from_courseid = cm.id
													 WHERE {$WHERE};");
		$t	= $max->fields['Total'];

		// Pagina
		$p = $_REQUEST['p'];

		// Resultados
		$r = $_REQUEST['r'];

		if ($r<5) $r= 30;
		if ($p<1) $p= 1;
		$totalPag = ceil($t/$r);
		if ($p>$totalPag) $p = 1;
		$inicio		= ($p-1)*$r;


    //TODO: Armo la barra de paginación, esto bien podria estar en una libreria html pero se pierde el control desde el template.
		$x2=0;
		$data['links_pages'] = "Row: {$inicio} - ".($inicio+$r)." de {$t} | P&aacute;gina:  ";
		if ($p > 1){
			$data['links_pages'] .= "	<span id='1' class='button-seek-start pager'>&nbsp;</span>
																<span id='".($p-1)."' class='button-seek-prev pager'> Prev</span>";
			for ($x=$p-3;$x<$p;$x++){
				if ($x>0){
					$data['links_pages'] .= " <span id='".($x)."' class='button pager'>{$x}</span> ";
					$x2++;
				}
			}
		}
		$data['links_pages'] .= " <span class='ui-button ui-state-disabled'><b>{$p}</b></span> ";

		for ($x=($p+1);$x<($p+11-$x2);$x++){
			if ($x<=$totalPag){
				$data['links_pages'] .= " <span id='".($x)."' class='button pager'>{$x}</span> ";
				$x2++;
			}
		}
		if ($t > $p+$r)  $data['links_pages'] .= "<span id='".($p+1)."' class='button-seek-next pager'>Sig </span>
																							<span id='{$totalPag}' class='button-seek-end pager'>&nbsp;</span>";

		$LIMIT 	= "LIMIT {$inicio},{$r}";
		$sql		= $LMS->Execute("SELECT c.id, c.periodo, a.name AS Academia,
														(SELECT CONCAT(u1.lastname, ', ', u1.firstname) AS Nombre
														 FROM {$HULK->dbname}.vw_enrolados e1
														 INNER JOIN mdl_user u1 ON e1.userid = u1.id
														 WHERE e1.roleid = 4 AND e1.id = c.id LIMIT 1) AS Instructor,
														c.fullname AS Comision, cm.fullname AS Course,
														(SELECT COUNT(*)
														 FROM {$HULK->dbname}.vw_enrolados e
														 INNER JOIN mdl_user u ON e.userid = u.id
														 WHERE e.roleid = 5 AND e.id = c.id) AS Alumnos
														FROM {$HULK->dbname}.vw_course c
														INNER JOIN mdl_proy_academy a ON c.academyid = a.id
														LEFT JOIN mdl_course cm ON c.from_courseid = cm.id
														WHERE {$WHERE}
														ORDER BY c.id DESC, Academia, Comision {$LIMIT};");


		$data['rows']		= $sql->GetRows();

		$data['academias_user'] = $LMS->getAcademys();

		$data['periodos_user'] = $LMS->GetAll("SELECT DISTINCT periodo
												FROM mdl_course c
												WHERE c.periodo != ''
												ORDER BY periodo DESC;");

		// Si es un resultado redirecciono a ver el contacto
		if ($t == 1){		header("Location: courses.php?v=view&id={$data['rows'][0]['id']}");	die();	}

		break;

	case 'asistencia':
		$id = $_REQUEST['id'];
		$H_USER->require_capability('course/view');

		$data['rows'] = $LMS->GetAll("SELECT u.username, CONCAT(u.firstname, ' ', u.lastname) AS student, al.statusid, atts.acronym,
																	a.course, ass.sessdate, ass.lasttakenby, al.remarks
																	FROM mdl_attendance_log al
																	INNER JOIN mdl_user u ON al.studentid=u.id
																	INNER JOIN mdl_attendance_sessions ass ON al.sessionid=ass.id
																	INNER JOIN mdl_attendance a ON ass.attendanceid=a.id
																	INNER JOIN mdl_attendance_statuses atts ON atts.id=al.statusid
																	WHERE ass.id={$id};");

		$data['courseid'] = $data['rows'][0]['courseid'];

		$data['sessdate'] = $data['rows'][0]['sessdate'];

		$data['takenby'] = $data['rows'][0]['takenby'];

		$view->Load('header');
		$view->Load('courses/'.$v, $data);
		die();

		break;

	case 'asistencia-completa':

		$id = $_REQUEST['id'];
		$H_USER->require_capability('course/view');

		// Traigo todas las sesiones para esa comi
		$data['sesiones'] = $LMS->GetAll("SELECT atts.* FROM mdl_attendance_sessions atts INNER JOIN mdl_attendance a ON a.id=atts.attendanceid WHERE a.course={$id} ORDER BY atts.sessdate;");

		$tomadas = $LMS->GetOne("SELECT COUNT(*) AS tomadas FROM mdl_attendance_sessions atts INNER JOIN mdl_attendance a ON a.id=atts.attendanceid
								 WHERE a.course={$id} AND atts.lasttaken > 0 ORDER BY atts.sessdate;");

		$alumnos = $LMS->GetAll("SELECT u.id, CONCAT(u.firstname, ' ', u.lastname) AS alum
								 FROM mdl_user u
								 INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
								 INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
								 WHERE ra.roleid=5 AND ctx.instanceid={$id}
								 ORDER BY u.lastname, u.firstname;");

		if($alumnos){
			foreach($alumnos as $alumno){
				foreach($data['sesiones'] as $sesion){
					$asistencia = $LMS->GetOne("SELECT atts.acronym as status
												FROM mdl_attendance_log al
												INNER JOIN mdl_attendance_sessions ass ON al.sessionid=ass.id
												INNER JOIN mdl_attendance_statuses atts ON atts.id=al.statusid
												WHERE ass.id={$sesion['id']} AND al.studentid={$alumno['id']};");

					$alumno['asistencias'][$sesion['id']] = $asistencia;

					if($asistencia == "P"){
						$tot_p++;
					}
				}

				if($tomadas > 0){
					$alumno['porc'] = $tot_p*100/$tomadas;
				}
				$tot_p = 0;

				$alumno['baja'] = $H_DB->GetOne("SELECT id FROM h_bajas WHERE userid={$alumno['id']} AND comisionid={$id};");

				$data['rows'][] = $alumno;
			}
		}

		$data['courseid'] = $id;

		break;

	case 'edit_old':

		$id = $_REQUEST['id'];
		$H_USER->require_capability('course/new');
		$H_USER->require_capability('course/view');

		$data['course'] = $LMS->getCourse($id);
		$data['course_config'] = $H_DB->getCourseConfig($id);

		$menuroot['ruta'] = array($data['course']['fullname']=>"courses.php?v=view&id={$data['course']['id']}","Editar"=>"#");


		if($_POST['guardar']){

		}
		//Instructores capacitados para ese curso modelo
		$data['instructores'] = $LMS->getAcademyInstructor($data['course']['academyid'],$data['course']['from_courseid']);

		//Aulas de la academia
		$data['aulas'] = $H_DB->GetAll("SELECT * FROM h_academy_aulas WHERE academyid={$data['course']['academyid']} ORDER BY name;");

		//Horarios
		$data['horarios'] = $H_DB->GetAll("SELECT * FROM h_horarios ORDER BY name;");

		break;

	case 'edit':

		/// Cargar custom CSS para el view ///
		$_arrcss = [
			['folder'=>'themes/','style'=>'courses','rel'=>'stylesheet']
		];
		$jquerynew = true;

		$id = $data['id'] = $_REQUEST['id'];
		$H_USER->require_capability('course/new');
		$H_USER->require_capability('course/view');

		$data['asistencias']	= $LMS->getCourseAttendance($id);

		/////////////
		if(!empty($_POST)){

			$idaula = $_POST['aula'];
			$H_DB->update('h_course_config',array('aulaid'=>$idaula),"courseid={$id}");


			date_default_timezone_set('America/Argentina/Buenos_Aires');

			if(!empty($_POST['newdate']) && !empty($_POST['day'])){
				$dateid = $_POST['day'];
				$newdate = explode('/',$_POST['newdate']);
				$description = utf8_decode($_POST['description']);
				$att = $LMS->GetRow("SELECT attendanceid, sessdate, TIME(FROM_UNIXTIME(sessdate)) sesstime FROM mdl_attendance_sessions WHERE id={$dateid}");

				$LMS->delete('mdl_attendance_sessions',"id={$dateid}");
				$LMS->insert('mdl_attendance_sessions_cancelled',array(
					'attendanceid'=>$att['attendanceid'],
					'sessdate'=>$att['sessdate'],
					'description'=>$description
				));
				$newdate = strtotime($newdate[2].'-'.$newdate[1].'-'.$newdate[0].' '.$att['sesstime']);
				$LMS->insert('mdl_attendance_sessions',array(
					'attendanceid'=>$att['attendanceid'],
					'sessdate'=>$newdate,
					'descriptionformat'=>1
				));
				$firstday = $data['asistencias'][0]['id'];
				$lastday = $data['asistencias'][count($data['asistencias'])-1]['id'];
				if($dateid==$firstday){
					$LMS->update('mdl_course',array('startdate'=>$newdate),"id={$id}");
				}
				if($dateid==$lastday){
					$LMS->update('mdl_course',array('enddate'=>$newdate),"id={$id}");
				}
			}
		}
		////////////

		$data['course'] = $LMS->getCourse($id);
		$data['course_config'] = $H_DB->getCourseConfigFull($id);

		//Aulas de la academia
		$data['aulas'] = $H_DB->GetAll("SELECT * FROM h_academy_aulas WHERE academyid={$data['course']['academyid']} ORDER BY name;");		

		
		break;

	case 'cambio_instructor':

		$id = $_REQUEST['id'];
		$H_USER->require_capability('course/cambio_instructor');

		$data['course'] = $LMS->getCourse($id);
		$data['course_config'] = $H_DB->getCourseConfig($id);

		$menuroot['ruta'] = array($data['course']['fullname']=>"courses.php?v=view&id={$data['course']['id']}","Cambio de instructor"=>"#");

		if($_POST['add']){
			foreach($_REQUEST['addselect'] as $ins){
				if(!$LMS->enrolUser($ins, $id, 4) ){
						show_error("Error","Error al enrolar el instructor");
					}					
			}				
		}
		if($_POST['remove']){
			foreach($_REQUEST['removeselect'] as $ins){
				if(!$LMS->unenrolUser($ins, $id, 4) ){
						show_error("Error","Error al enrolar el instructor");
					}					
			}				
		}

		$data['instructores']	= $LMS->getCourseInstructors($id);
		$data['instructores_potenciales'] = $LMS->getAcademyInstructor($data['course']['academyid'],$data['course']['from_courseid']);

		break;		
	case 'inscriptos-print':

		$data['courseid'] = $_REQUEST['id'];
		$H_USER->require_capability('course/view');

		$data['comi'] = $LMS->GetRow("SELECT * FROM mdl_course WHERE id={$data['courseid']};");
		$estudiantes	= $LMS->getCourseStudents($data['courseid']);

		foreach($estudiantes as $est){

			$deuda = $H_DB->GetRow("SELECT ((c.valor_cuota-(c.valor_cuota*(i.becado/100))-c.valor_pagado)) AS deuda, c.id AS id
									FROM h_cuotas c
									INNER JOIN h_inscripcion i ON i.id=c.insc_id
									WHERE c.cuota=1 AND c.userid={$est['id']} AND c.courseid={$data['comi']['from_courseid']};");
			if($deuda){
				// Chequear que el comprobante al que está asociada la cuota no esté pendiente
				$comprobantes = $H_DB->GetAll("SELECT c.*, cc.importe AS pagado
												 FROM h_comprobantes c
												 INNER JOIN h_comprobantes_cuotas cc ON cc.comprobanteid=c.id
												 WHERE cc.cuotaid={$deuda['id']};");
				if($comprobantes){
					foreach($comprobantes as $comp){
						if($comp['pendiente']==1){
							$deuda['deuda'] += $comp['pagado'];
						}
					}
				}
			}
			$est['deuda'] = $deuda['deuda'];
			$data['estudiantes'][] = $est;
		}

		$data['instructor']	= $LMS->getCourseInstructors($data['courseid']);

		$data['capacidad'] = $H_DB->GetRow("SELECT capacity FROM h_academy_aulas aa
																				INNER JOIN h_course_config cah ON aa.id=cah.aulaid
																				WHERE cah.courseid={$data['courseid']};");
		$data['horario'] = $H_DB->GetRow("SELECT h.name FROM h_horarios h
																			INNER JOIN h_course_config cah ON h.id=cah.horarioid
																			WHERE cah.courseid={$data['courseid']};");
		$print=1;
		break;
	case 'reset_course_blocks':
		global $HULK, $CFG;

		$courseid = $_REQUEST['id'];
		if ($courseid){
			$course	= $LMS->getCourse($courseid);
			$course = (object) $course;			
			require_once($HULK->libdir.'/lms_lib/setupmoodle.php');
			require_once($HULK->lms_dirroot.'/lib/blocklib.php');
			$CFG->defaultblocks_override = 'course_proydesa,activity_modules:attendance,news_items,calendar_upcoming,recent_activity';
			$CFG->defaultblocks_site = 'site_main_menu,site_proydesa:html,calendar_month';
	 		$context = context_course::instance($course->id);
	   		blocks_delete_all_for_context($context->id);
	   		blocks_add_default_course_blocks($course);
		 	echo "Reseteando bloques por defecto de {$course->id} <br/>";
		 	ob_flush(); flush();
		}
		redireccionar("courses.php?v=view&id={$courseid}");
		die();		
		break;
	/***********************/
	default:
		$v	=	'index';
		break;
}

$view->Load('header');
if(empty($print)) $view->Load('menu',$data);
if(empty($print)) $view->Load('menuroot',$menuroot);
$view->Load('courses/'.$v, $data);
if(empty($print)) $view->Load('footer');
?>