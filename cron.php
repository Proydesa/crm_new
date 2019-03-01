<?php

	require_once 'config.php';

	$H_USER->require_login();

//arregloLinks();
//cambioLinks();

 ob_start();

# Vista

//borrarHistorialActividades("veteran-crm"); 
//borrarHistorialActividades("crm"); 
//migrarUsuariosAdmin();
//migrarUsuarios();


//sincronizarComprobantesCuotas();
/*
sincronizarBases("h_activity");
sincronizarBases("h_bajas");
sincronizarBases("h_cobros");
sincronizarBases("h_comprobantes");
sincronizarBases("h_cuotas");
sincronizarBases("h_files");
sincronizarBases("h_grupos");
sincronizarBases("h_inscripcion");
sincronizarBases("h_rendiciones");
sincronizarBases("h_role_capabilities");
sincronizarBases("h_role_assignments");
*/
sincronizarStatus("173");
//sincronizarTimestart("161");


revisarGradebookActivity();

resetCourseBlocks(100350);
//resetSiteBlocks();

//sincronizarAcademias();
//sincronizarConvenios();
//crearHistorico();
//sincronizarUsuarios();

//migrarRolesMC();
//migrarRolesCA();
//migrarRolesInstructor();

// Actualiza la tabla h_cuotas para los cobrados
//actualizarBase();
ob_end_flush();


function revisarGradebookActivity(){
// Funcion para revisar que todos los cursos tengan la actividad de gradebook para que los usuarios puedan cargar status.
	global $HULK, $CFG, $LMS;
	$courses = $LMS->getAll("SELECT c.id,c.fullname FROM `moodle`.mdl_course c 
								LEFT JOIN `moodle`.mdl_grade_items gi 
								ON gi.courseid = c.id AND gi.itemname LIKE '%Graduaci%'
								WHERE  gi.id IS NULL AND c.from_courseid>0 ORDER BY c.fullname;");
	echo "INICIO revisarGradebookActivity()";
	foreach($courses as $course) {
		echo '<br/>Falta item graduación en: <a href="courses.php?v=view&amp;id='.$course['id'].'" target="_blank">'.$course['fullname'].'</a> - LMS import: <a href="https://www.proydesa.org/lms_new/backup/import.php?id='.$course['id'].'" target="_blank">Importar</a>';
	}
	echo "FIN revisarGradebookActivity()";

}


function sincronizarComprobantesCuotas(){
	global $H_DB;

	$comprobante_cuotas= $H_DB->getAll("SELECT DISTINCT cc.* FROM `veteran-crm`.h_comprobantes_cuotas cc;");

	foreach ($comprobante_cuotas as $cc) {

		if($H_DB->record_exists_sql("SELECT * FROM `crm`.h_comprobantes_cuotas WHERE comprobanteid={$cc['comprobanteid']} AND cuotaid={$cc['cuotaid']}")){
			echo "*";
		}else{
			echo "º";
			$H_DB->insert("h_comprobantes_cuotas",$cc);
		}
	}			
}
function migrarRolesMC(){
	global $LMS,$H_USER;

	// Cargar certificaciones.
	$result = $LMS->getAll("SELECT DISTINCT userid,academyid FROM `veteran-moodle`.mdl_role_assignments WHERE roleid=8;");
	foreach($result as $row){
		$academyctx = $LMS->getAcademyCtx($row['academyid']);
		 // Check for existing entry
		if ($academyctx){
			$ra = $LMS->GetField_sql("SELECT id FROM `moodle`.mdl_role_assignments ra WHERE roleid=9 AND userid={$row['userid']} AND contextid={$academyctx};");
			if (empty($ra)) {             // Create a new entry
				$ra['roleid']		= 9;
				$ra['contextid']	= $academyctx;
				$ra['userid']      = $row['userid'];
				$ra['hidden']      = 1;
				$ra['timestart']	= round(time(), -2);
				$ra['timeend']     = 0;
				$ra['timemodified']= 0;
				$ra['enrol']       = 'manual';
				$ra['modifierid']  = $H_USER->get_property('id');
				if (!$ra = $LMS->insert('mdl_role_assignments', $ra)) {
					echo "<br/>Error";
				}
				echo "<br/>Se agrego main contact.";
			}else{
				echo "<br/>Ya existe mc.";
			}
			if(!$LMS->enrolUser($row['userid'], 100000, 5) ){
				show_error("Error","Error al enrolar el usuario");
			}
			if(!$LMS->enrolUser($row['userid'], 100001, 5) ){
				show_error("Error","Error al enrolar el usuario");
			}						
		}
	}
}
function migrarRolesCA(){
	global $LMS,$H_USER;

	// Cargar certificaciones.
	$result = $LMS->getAll("SELECT DISTINCT userid,academyid FROM `veteran-moodle`.mdl_role_assignments WHERE roleid=9;");
	foreach($result as $row){
		$academyctx = $LMS->getAcademyCtx($row['academyid']);
		 // Check for existing entry
		if ($academyctx){
			$ra = $LMS->GetField_sql("SELECT id FROM `moodle`.mdl_role_assignments ra WHERE roleid=10 AND userid={$row['userid']} AND contextid={$academyctx};");
			if (empty($ra)) {             // Create a new entry
				$ra['roleid']		= 10;
				$ra['contextid']	= $academyctx;
				$ra['userid']      = $row['userid'];
				$ra['hidden']      = 1;
				$ra['timestart']	= round(time(), -2);
				$ra['timeend']     = 0;
				$ra['timemodified']= 0;
				$ra['enrol']       = 'manual';
				$ra['modifierid']  = $H_USER->get_property('id');
				if (!$ra = $LMS->insert('mdl_role_assignments', $ra)) {
					echo "<br/>Error";
				}
				echo "<br/>Se agrego contacto administrativo.";
			}else{
				echo "<br/>Ya existe ca.";
			}
			if(!$LMS->enrolUser($row['userid'], 100000, 5) ){
				show_error("Error","Error al enrolar el usuario");
			}
			if(!$LMS->enrolUser($row['userid'], 100001, 5) ){
				show_error("Error","Error al enrolar el usuario");
			}						
		}
	}
}
function migrarRolesInstructor(){
	global $LMS,$H_USER;

	// Cargar certificaciones.
	$result = $LMS->getAll("SELECT DISTINCT userid,academyid FROM `veteran-moodle`.mdl_role_assignments WHERE roleid=2;");
	foreach($result as $row){
		$academyctx = $LMS->getAcademyCtx($row['academyid']);
		 // Check for existing entry
		if ($academyctx){
			$ra = $LMS->GetField_sql("SELECT id FROM `moodle`.mdl_role_assignments ra WHERE roleid=2 AND userid={$row['userid']} AND contextid={$academyctx};");
			if (empty($ra)) {             // Create a new entry
				$ra['roleid']		= 2;
				$ra['contextid']	= $academyctx;
				$ra['userid']      = $row['userid'];
				$ra['hidden']      = 1;
				$ra['timestart']	= round(time(), -2);
				$ra['timeend']     = 0;
				$ra['timemodified']= 0;
				$ra['enrol']       = 'manual';
				$ra['modifierid']  = $H_USER->get_property('id');
				if (!$ra = $LMS->insert('mdl_role_assignments', $ra)) {
					echo "<br/>Error";
				}
				echo "<br/>Se agrego creador de curso.";
			}else{
				echo "<br/>Ya existe.";				
			}
			if(!$LMS->enrolUser($row['userid'], 100001, 5) ){
				show_error("Error","Error al enrolar el usuario");
			}
			if($certificaciones = $LMS->getAll("SELECT c.shortname,c.id,hcm.nuevoid
											FROM `veteran-moodle`.mdl_course c
										    INNER JOIN `crm`.h_course_modelos hcm ON c.id=hcm.viejoid
											WHERE c.need_create_courseid IN(
																		 SELECT c.from_courseid
																		 FROM `veteran-moodle`.mdl_grade_grades gg
																		 INNER JOIN `veteran-moodle`.mdl_grade_items gi ON gg.itemid=gi.id
																		 INNER JOIN `veteran-moodle`.mdl_course c ON gi.courseid=c.id
																		 INNER JOIN `veteran-moodle`.mdl_course cm ON c.from_courseid=cm.id
																		 WHERE gg.finalgrade > 2
																		 AND gi.itemname LIKE '%Graduaci%'
																		 AND cm.fullname LIKE '%Instructores%'
																		 AND gg.userid={$row['userid']}
																		)
											ORDER BY c.fullname;")){
				foreach($certificaciones as $cert){
					 $i = $LMS->GetField_sql("SELECT id FROM mdl_proy_instructores i
							 WHERE i.courseid={$cert['nuevoid']} AND userid={$row['userid']};");					
					if (empty($i)) {             // Create a new entry
						$i['userid']		= $row['userid'];
						$i['courseid']		= $cert['nuevoid'];
						$i['startdate']	= time();
						$i['modifierid']  	= $H_USER->get_property('id');
						$LMS->insert('mdl_proy_instructores', $i);
						print_r($cert);
					}
				}
			}
		}
	}
}
function sincronizarUsuarios(){
	global $LMS;

	echo "Empezando sincronización de usuarios...";

	error_reporting(E_ALL);
		ini_set('display_errors', 'On');
		set_time_limit(3600); // 1 hour should be enough
	ini_set('memory_limit', '128M');

		$result = $LMS->getAll("SELECT DISTINCT u.id,u.auth,u.confirmed,u.policyagreed,u.deleted,1 as mnethostid,u.username,
					u.idnumber,u.firstname,u.lastname, u.email, u.emailstop,u.skype, u.msn,u.phone1,
					u.phone2,u.institution, u.`department`, u.`address`, u.`city`, u.`country`, u.`lang`, u.`theme`,
					u.`timezone`, u.`firstaccess`, u.`lastaccess`, u.`lastlogin`, u.`currentlogin`, u.`lastip`,
					u.`secret`, u.`picture`, u.`url`, u.`description`, u.`mailformat`, u.`maildigest`, u.`maildisplay`,
					u.`autosubscribe`, u.`trackforums`, u.`timemodified`, u.`trustbitmask`,
					u.`imagealt`, u.`acid`, u.`fnacimiento`, u.`sexo`, u.`cp`, u.`saldo`, u.`obs`,
					u.`user_cisco`, u.`noquierospam`
					FROM `veteran-moodle`.mdl_user u
					INNER JOIN `veteran-moodle`.mdl_role_assignments ra ON u.id=ra.userid
					INNER JOIN `veteran-moodle`.mdl_context ctx ON ctx.id=ra.contextid
					INNER JOIN `veteran-moodle`.mdl_course c ON ctx.instanceid=c.id
					WHERE u.deleted=0 AND ctx.contextlevel=50
					AND c.academyid=50
					ORDER BY u.id ASC;");
		 foreach($result as $row){
 			if ($LMS->record_exists("mdl_user",$row['id'])){
				echo "<br/>UPDATE: ".$row['id'];
				$LMS->update('mdl_user',$row,"id = {$row['id']}");
			}else{
				$row['password']= md5($row['username']);
				$id = $LMS->insert('mdl_user',$row);
				echo "<br/>INSERT: {$row['id']}";
				$x++;
		 	}
			ob_flush(); flush();		 	
		}

	echo "Se migraron ".$x." usuarios No administradores.<br/>";
	ob_flush(); flush();
	return;
}


function migrarUsuariosAdmin(){
	global $LMS;
	$select_sql="SELECT DISTINCT u.id,u.auth,u.confirmed,u.policyagreed,u.deleted,1,u.username,
			u.`password`,u.idnumber,u.firstname,u.lastname, u.email, u.emailstop,u.skype, u.msn,u.phone1,
			u.phone2,u.institution, u.`department`, u.`address`, u.`city`, u.`country`, u.`lang`, u.`theme`,
			u.`timezone`, u.`firstaccess`, u.`lastaccess`, u.`lastlogin`, u.`currentlogin`, u.`lastip`,
			u.`secret`, u.`picture`, u.`url`, u.`description`, u.`mailformat`, u.`maildigest`, u.`maildisplay`,
			u.`autosubscribe`, u.`trackforums`, u.`timemodified`, u.`trustbitmask`,
			u.`imagealt`, u.`acid`, u.`fnacimiento`, u.`sexo`, u.`cp`, u.`saldo`, u.`obs`,
			u.`user_cisco`, u.`noquierospam`
			FROM `veteran-moodle`.mdl_user u
			INNER JOIN `veteran-moodle`.mdl_role_assignments ra ON u.id=ra.userid
			WHERE u.deleted=0 AND ra.roleid IN (1,2,3,4,8,9,10,11,12,13,14,15,16,17,18,19,20)
		 AND u.id NOT IN (SELECT id from `moodle`.mdl_user) AND u.username NOT IN (SELECT username from `moodle`.mdl_user);";
	 $result = $LMS->GetAll($select_sql);

	 $LMS->Execute("INSERT INTO moodle.mdl_user (id,auth,confirmed,policyagreed,deleted,mnethostid,username,
			`password`,idnumber,firstname,lastname, email, emailstop,skype, msn,phone1,
 		phone2,institution, `department`, `address`, `city`, `country`, `lang`, `theme`,
 		`timezone`, `firstaccess`, `lastaccess`, `lastlogin`, `currentlogin`, `lastip`,
 		`secret`, `picture`, `url`, `description`,`mailformat`, `maildigest`, `maildisplay`,
 		`autosubscribe`, `trackforums`, `timemodified`, `trustbitmask`,
 		`imagealt`, `acid`, `fnacimiento`, `sexo`, `cp`, `saldo`, `obs`,
 		`user_cisco`, `noquierospam`)
 		{$select_sql}");

	echo "Se migraron ".$LMS->affected_rows()." usuarios administradores.<br/>";
	if ($result) show_array($result);
	ob_flush(); flush();
	return;
}

function migrarUsuarios(){
	global $LMS;
	$select_sql="SELECT DISTINCT u.id,u.auth,u.confirmed,u.policyagreed,u.deleted,1,u.username,
				u.`password`,u.idnumber,u.firstname,u.lastname, u.email, u.emailstop,u.skype, u.msn,u.phone1,
				u.phone2,u.institution, u.`department`, u.`address`, u.`city`, u.`country`, u.`lang`, u.`theme`,
				u.`timezone`, u.`firstaccess`, u.`lastaccess`, u.`lastlogin`, u.`currentlogin`, u.`lastip`,
				u.`secret`, u.`picture`, u.`url`, u.`description`, u.`mailformat`, u.`maildigest`, u.`maildisplay`,
				u.`autosubscribe`, u.`trackforums`, u.`timemodified`, u.`trustbitmask`,
				u.`imagealt`, u.`acid`, u.`fnacimiento`, u.`sexo`, u.`cp`, u.`saldo`, u.`obs`,
				u.`user_cisco`, u.`noquierospam`
				FROM `veteran-moodle`.mdl_user u
				INNER JOIN `veteran-moodle`.mdl_role_assignments ra ON u.id=ra.userid
				INNER JOIN `veteran-moodle`.mdl_context ctx ON ctx.id=ra.contextid
				INNER JOIN `veteran-moodle`.mdl_course c ON ctx.instanceid=c.id
				WHERE u.deleted=0 AND ctx.contextlevel=50
				AND (u.lastaccess>1356998400 OR  c.periodo>130)
				AND c.academyid!=50
				AND u.id NOT IN (SELECT u3.id FROM moodle.mdl_user u3)
				AND u.username NOT IN (SELECT u3.username FROM moodle.mdl_user u3)
				ORDER BY id ASC;";
	 $result = $LMS->GetAll($select_sql);

	 $LMS->Execute("INSERT INTO moodle.mdl_user (id,auth,confirmed,policyagreed,deleted,mnethostid,username,
		`password`,idnumber,firstname,lastname, email, emailstop,skype, msn,phone1,
		phone2,institution, `department`, `address`, `city`, `country`, `lang`, `theme`,
		`timezone`, `firstaccess`, `lastaccess`, `lastlogin`, `currentlogin`, `lastip`,
		`secret`, `picture`, `url`, `description`,`mailformat`, `maildigest`, `maildisplay`,
		`autosubscribe`, `trackforums`, `timemodified`, `trustbitmask`,
		`imagealt`, `acid`, `fnacimiento`, `sexo`, `cp`, `saldo`, `obs`,
		`user_cisco`, `noquierospam`)
		{$select_sql}");

	echo "Se migraron ".$LMS->affected_rows()." usuarios No administradores.<br/>";
	if ($result) show_array($result);
	ob_flush(); flush();
	return;
}

function crearHistorico(){
		// Para crear la tabla historica de gradebooks
		global $LMS;
		$LMS->Execute('CREATE TABLE crm.h_gradebooks
						AS SELECT ra.id as id, u.id as userid, u.username, u.firstname, u.lastname, u.email,
						c.id as comisionid,c.shortname as comisionname, c.periodo, c.origen,c.academyid,a.name as academyname, 
						c.convenioid,con.name as convenioname,c.from_courseid as modeloid,
						c2.shortname as modeloname,c2.fullname as modelofullname, c.forma_de_pago,
						GROUP_CONCAT(DISTINCT CONCAT (gi.itemname,": ",gg.finalgrade) ORDER BY gg.itemid SEPARATOR " -- ") as notas,
						GROUP_CONCAT(DISTINCT CONCAT (gi3.itemname,": ",gg3.finalgrade) SEPARATOR " -- ") as final, gg2.finalgrade as graduacion,
						GROUP_CONCAT(DISTINCT CONCAT (r.name,": ",ins.firstname," ",ins.lastname) SEPARATOR " -- ") as docente,
						"lms1" as base_origen, res.reference as curricula
						FROM `veteran-moodle`.mdl_user u
						INNER JOIN `veteran-moodle`.mdl_role_assignments ra ON u.id=ra.userid
						INNER JOIN `veteran-moodle`.mdl_context ctx ON ctx.id=ra.contextid
						INNER JOIN `veteran-moodle`.mdl_course c ON ctx.instanceid=c.id
						INNER JOIN `veteran-moodle`.mdl_course c2 ON c.from_courseid=c2.id
						LEFT JOIN `veteran-moodle`.mdl_grade_items gi ON gi.courseid = c.id AND gi.itemmodule="quiz" AND gi.itemname NOT LIKE "%Final%"
						LEFT JOIN `veteran-moodle`.mdl_grade_grades gg ON gg.itemid = gi.id AND gg.userid=u.id
						LEFT JOIN `veteran-moodle`.mdl_grade_items gi3 ON gi3.courseid = c.id AND gi3.itemname LIKE "%Final%"
						LEFT JOIN `veteran-moodle`.mdl_grade_grades gg3 ON gg3.itemid = gi3.id AND gg3.userid=u.id
						LEFT JOIN `veteran-moodle`.mdl_grade_items gi2 ON gi2.courseid = c.id AND gi2.itemname LIKE "%Gradu%"
						LEFT JOIN `veteran-moodle`.mdl_grade_grades gg2 ON gg2.itemid = gi2.id AND gg2.userid=u.id
						LEFT JOIN `veteran-moodle`.mdl_academy a ON a.id=c.academyid
						LEFT JOIN `veteran-moodle`.mdl_convenios con ON con.id=c.convenioid
						LEFT JOIN `veteran-moodle`.mdl_role_assignments ra2 ON ctx.id=ra2.contextid AND ra2.roleid IN (3,4,11)
						LEFT JOIN `veteran-moodle`.mdl_user ins ON ra2.userid=ins.id
						LEFT JOIN `veteran-moodle`.mdl_role r ON r.id=ra2.roleid
                        LEFT JOIN `veteran-moodle`.mdl_resource res ON res.course=c2.id AND res.reference LIKE "%/curriculas/%" 						
						WHERE u.deleted=0  AND ra.roleid=5 AND ctx.contextlevel=50 AND c.id>0 GROUP BY u.id,c.id ORDER BY u.id;');
	}

function sincronizarStatus($periodo="173"){
//Para pasar los status del viejo crm al historico del nuevo crm
	global $LMS,$H_DB;

	$result = $LMS->getAll("SELECT ra.id as id,	gg2.finalgrade as vet_graduacion, gr.graduacion
				FROM `veteran-moodle`.mdl_user u
				INNER JOIN `veteran-moodle`.mdl_role_assignments ra ON u.id=ra.userid
				INNER JOIN `veteran-moodle`.mdl_context ctx ON ctx.id=ra.contextid
				INNER JOIN `veteran-moodle`.mdl_course c ON ctx.instanceid=c.id
				LEFT JOIN `veteran-moodle`.mdl_grade_items gi2 ON gi2.courseid = c.id AND gi2.itemname LIKE '%Gradu%'
				LEFT JOIN `veteran-moodle`.mdl_grade_grades gg2 ON gg2.itemid = gi2.id AND gg2.userid=u.id
				LEFT JOIN `crm`.h_gradebooks gr ON gr.id=ra.id 
                WHERE u.deleted=0  AND ra.roleid=5 AND ctx.contextlevel=50 AND c.id>0
				AND c.periodo={$periodo}
				GROUP BY u.id,c.id ORDER BY u.id;");
	foreach($result as $row){
		if ($row['vet_graduacion']!=$row['graduacion']){
			$H_DB->update("h_gradebooks",array("graduacion"=>$row['vet_graduacion']),"id = {$row['id']}");
			$cant++;
		}
	}
	echo "<p>Cantidad de estatus actualizados para {$periodo}: {$cant}</p>";
	return;
}
function sincronizarTimestart($periodo="173"){
//Para pasar la fecha en que fueron enrolados los usuarios al gradebook
	global $LMS,$H_DB;

	$result = $LMS->getAll("SELECT ra.id as id,ra.timestart
				FROM `veteran-moodle`.mdl_user u
				INNER JOIN `veteran-moodle`.mdl_role_assignments ra ON u.id=ra.userid
				INNER JOIN `veteran-moodle`.mdl_context ctx ON ctx.id=ra.contextid
				INNER JOIN `veteran-moodle`.mdl_course c ON ctx.instanceid=c.id
                WHERE u.deleted=0  AND ra.roleid=5 AND ctx.contextlevel=50 AND c.id>0
				AND c.periodo={$periodo}
				GROUP BY u.id,c.id ORDER BY u.id;");
	foreach($result as $row){
			$H_DB->update("h_gradebooks",array("date_enrollment"=>$row['timestart']),"id = {$row['id']}");
			$cant++;
	}
	echo "<p>Cantidad de fechas de inscripcion actualizados para {$periodo}: {$cant}</p>";
	return;
}

function sincronizarConvenios(){
	global $LMS,$H_DB;
	$migra = array(22=>1,51=>1,143=>1,24=>1,23=>9,78=>9,145=>2,31=>3,1=>3,21=>3,96=>3,84=>4,128=>4,141=>5);
	$convenios = $H_DB->getAll("SELECT * FROM `veteran-moodle`.mdl_academy_convenio WHERE (timeend>UNIX_TIMESTAMP() OR timeend=0) and academyid!=50 and convenioid IN (22,51,143,24,23,78,145,31,1,21,96,84,128,141);");
	foreach($convenios as $con){
		$con['convenioid'] = $migra[$con['convenioid']];
		$con['id']=$con['ac_id']; unset($con['ac_id']);
		$con['startdate']=$con['timestart']; unset($con['timestart']);
		$con['enddate']=$con['timeend']; unset($con['timeend']);		
		if($LMS->record_exists_sql("SELECT id FROM mdl_proy_academy_convenio WHERE academyid={$con['academyid']} AND convenioid={$con['convenioid']}")){
			print_r($con);echo "ACTUALIZAR!!!!".$con['convenioid']."</br>";
			$con['categoryid'] = $LMS->setConvenio($con['academyid'],$con['convenioid']);
			$LMS->update("mdl_proy_academy_convenio",$con,"id = {$con['id']}");
		}else{
			print_r($con);echo "Crear!!!!".$con['convenioid']."</br>";			
			$con['categoryid'] = $LMS->setConvenio($con['academyid'],$con['convenioid']);
			$LMS->insert("mdl_proy_academy_convenio",$con);
		}
	}
}
function sincronizarAcademias(){
	global $LMS;

	$academys= $LMS->getAll("SELECT DISTINCT a.* FROM `veteran-moodle`.mdl_academy a
						WHERE a.deleted = 0
						ORDER BY a.id DESC;");

	foreach ($academys as $row) {
		echo "<br/>".$row['id'].": ".$row['name']." | ";
		unset($row['parent']);
		if ($LMS->record_exists("mdl_proy_academy",$row['id'])){
			$LMS->setAcademy($row);
			echo "Actualizada ".$x++;
		}else{
			unset($row['parent']);			
			$LMS->newAcademy($row);			
			echo "Creada: ".$y++;
		}
	}		
}

function resetCourseBlocks($initcourseid){
	global $HULK, $CFG;
	require_once($HULK->libdir.'/lms_lib/setupmoodle.php');
	require_once($HULK->lms_dirroot.'/lib/blocklib.php');
	$CFG->defaultblocks_override = 'course_proydesa,activity_modules:attendance,news_items,calendar_upcoming,recent_activity';
	$CFG->defaultblocks_site = 'site_main_menu,site_proydesa:html,calendar_month';
	$courses = get_courses();//can be feed categoryid to just effect one category
	foreach($courses as $course) {
			if ($course->id<$initcourseid) continue;
	   $context = context_course::instance($course->id);
	   blocks_delete_all_for_context($context->id);
	   blocks_add_default_course_blocks($course);
		 ob_flush(); flush();
		 echo "Reseteando bloques por defecto de {$course->id} <br/>";
	}
	echo "FIN resetCourseBlocks()";
}
function resetSiteBlocks(){
	global $HULK, $CFG;
	require_once($HULK->libdir.'/lms_lib/setupmoodle.php');
	require_once($HULK->lms_dirroot.'/lib/blocklib.php');
	$CFG->defaultblocks_site = 'site_main_menu,site_proydesa:html,calendar_month';
	$course = get_course(1);//can be feed categoryid to just effect one category
		$context = context_course::instance($course->id);
	blocks_delete_all_for_context($context->id);
	blocks_add_default_course_blocks($course);
	ob_flush(); flush();
	echo "Reseteando bloques por defecto del sitio {$course->id} <br/>";
}


// Pasar Academias a inactivas cuando no tienen ningun curso en los ultimos n años.
function buscarInactivas(){
	global $HULK,$LMS,$H_DB,$H_USER,$view;
	require_once 'config.php';

	$academys= $LMS->getAcademys("AND a.status < 2 ");
	foreach ($academys as $row) {

		$ultimocomienzo = $LMS->GetField_sql("SELECT timecreated
									    FROM mdl_course
									    WHERE academyid={$row['id']}
										ORDER BY timecreated DESC LIMIT 0,1;");

		$dias = (time()-$ultimocomienzo) / (60 * 60 * 24);
		if ($dias > $HULK->acad_status_time){
			$H_DB->update("mdl_proy_academy", array("status"=> 1),"id = {$row['id']}");
		}else{
			$H_DB->update("mdl_proy_academy", array("status"=> 0),"id = {$row['id']}");
		}
	}
	return;
}


function borrarHistorialActividades($base){
	global $LMS;
	// Borramos todos los logs de actividades generados automaticamente
	// 60 * 60 *24 *360 = 360 días
	$borraranterioresa = time() - (60 * 60 * 24 * 120);
	$LMS->Execute("DELETE FROM `{$base}`.h_activity WHERE typeid=4 AND `subject` LIKE '%->%' AND startdate < {$borraranterioresa};");
	$registros = $LMS->affected_rows();
	echo "Se borraron {$registros} registros de las actividades de '{$base}' tipo 4 con subject que contienen '->' anteriores a ".date("d / m / Y",$borraranterioresa)."<br/>";
	return;
}

function sincronizarBases($table){
	global $LMS;
	if($table){
		$LMS->Execute("INSERT INTO `crm`.{$table} () SELECT DISTINCT * FROM `veteran-crm`.{$table} t WHERE t.id NOT IN (SELECT t2.id FROM crm.{$table} t2) ORDER BY t.id ASC;");
		echo "Se sincronizaron ".$LMS->affected_rows()." registros de {$table} <br/>";
	}else{
		echo "Debe especificar una base de datos.<br/>";
	}
	ob_flush(); flush();
	return;
}

function actualizarBase(){
	global $H_DB;

	$table="h_cuotas";
	$res = $H_DB->getAll("SELECT * FROM `veteran-crm`.{$table} WHERE `date`>1512145539;");
	foreach ($res as $row) {
		echo "*";
		if ($H_DB->record_exists($table,$row['id'])){
			$H_DB->update($table,$row,"id = {$row['id']}");
			$y++;
		}
	}
	echo "Actualizados {$table}: ".$y;
	ob_flush(); flush();
	return;
}


	/*
Acentos


UPDATE `crm`.h_grupos SET `name` = REPLACE(`name`, 'Ã“', 'Ó');
UPDATE `crm`.h_grupos SET `summary` = REPLACE(`summary`, 'Ã“', 'Ó');
UPDATE `crm`.h_grupos SET `name` = REPLACE(`name`, 'Ãš', 'Ú');
UPDATE `crm`.h_grupos SET `summary` = REPLACE(`summary`, 'Ãš', 'Ú');
UPDATE `crm`.h_grupos SET `name` = REPLACE(`name`, 'Ã‘', 'Ñ');
UPDATE `crm`.h_grupos SET `summary` = REPLACE(`summary`, 'Ã‘', 'Ñ');
UPDATE `crm`.h_grupos SET `name` = REPLACE(`name`, 'Ã±', 'ñ');
UPDATE `crm`.h_grupos SET `summary` = REPLACE(`summary`, 'Ã±', 'ñ');



UPDATE `crm`.h_activity SET `subject` = REPLACE(`subject`, 'Ã¡', 'á');
UPDATE `crm`.h_activity SET `summary` = REPLACE(`summary`, 'Ã¡', 'á');

UPDATE `crm`.h_activity SET `subject` = REPLACE(`subject`, 'Ã©', 'é');
UPDATE `crm`.h_activity SET `summary` = REPLACE(`summary`, 'Ã©', 'é');

UPDATE `crm`.h_activity SET `subject` = REPLACE(`subject`, 'Ãº', 'ú');
UPDATE `crm`.h_activity SET `summary` = REPLACE(`summary`, 'Ãº', 'ú');

UPDATE `crm`.h_activity SET `subject` = REPLACE(`subject`, 'Ã­', 'í');
UPDATE `crm`.h_activity SET `summary` = REPLACE(`summary`, 'Ã­', 'í');

UPDATE `moodle`.mdl_user SET `firstname` = REPLACE(`firstname`, 'Ã¡', 'á');
UPDATE `moodle`.mdl_user SET `lastname` = REPLACE(`lastname`, 'Ã¡', 'á');

UPDATE `moodle`.mdl_user SET `firstname` = REPLACE(`firstname`, 'Ã©', 'é');
UPDATE `moodle`.mdl_user SET `lastname` = REPLACE(`lastname`, 'Ã©', 'é');

UPDATE `moodle`.mdl_user SET `firstname` = REPLACE(`firstname`, 'Ãº', 'ú');
UPDATE `moodle`.mdl_user SET `lastname` = REPLACE(`lastname`, 'Ãº', 'ú');

UPDATE `moodle`.mdl_user SET `firstname` = REPLACE(`firstname`, 'Ã­', 'í');
UPDATE `moodle`.mdl_user SET `lastname` = REPLACE(`lastname`, 'Ã­', 'í');

UPDATE `moodle`.mdl_user SET `firstname` = REPLACE(`firstname`, 'Ã³', 'ó');
UPDATE `moodle`.mdl_user SET `lastname` = REPLACE(`lastname`, 'Ã³', 'ó');


	*/
/*
	function arregloLinks(){
		global $LMS;
		$select_sql="SELECT * FROM `veteran-moodle`.mdl_resource r WHERE r.reference NOT LIKE '%/%' AND r.type ='file' ORDER by course;";
		$result = $LMS->GetAll($select_sql);

		foreach($result as $row){
			$newreference = "https://www.proydesa.org/lms/fileproy.php?f=/".$row['course']."/".$row['reference'];
			echo $newreference."<br/>";

	    $sql = "UPDATE `veteran-moodle`.mdl_resource SET reference='{$newreference}' WHERE id={$row['id']}";
			echo $sql."<br/>";
			$LMS->Execute($sql);

		}
		return;
	}
	function cambioLinks(){
		global $LMS;

	//	$sql = "UPDATE `veteran-moodle`.mdl_course_sections SET `summary` = REPLACE(`summary`, 'https://www.proydesa.org/lms/fileproy.php', 'https://www.proydesa.org/lms/fileproy.php?f=')";
		$sql = "UPDATE `veteran-moodle`.mdl_question SET `questiontext` = REPLACE(`questiontext`, 'lms/file.php', 'lms/fileproy.php?f=')";
		echo $sql."<br/>";
		$LMS->Execute($sql);

	}
*/
?>