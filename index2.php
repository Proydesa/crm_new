<?php

	require_once 'config.php';

	$H_USER->require_login();

	$data['cursos']		= $LMS->GetAll("SELECT c.id, c.fullname AS course, cm.fullname AS model, r.name AS rol, ra.roleid, c.periodo
										FROM mdl_role_assignments ra
										INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
										INNER JOIN mdl_course c ON c.id=ctx.instanceid
										INNER JOIN mdl_course cm ON c.from_courseid=cm.id
										INNER JOIN mdl_role r ON ra.roleid=r.id
										WHERE ra.userid={$H_USER->get_property('id')} AND ra.roleid=5 AND cm.fullname LIKE '%Instructores%'
										ORDER BY model, course, rol;");
	$data['cursos_ins']		= $LMS->GetAll("SELECT c.id, c.fullname AS course, cm.fullname AS model, r.name AS rol, ra.roleid, c.periodo
											FROM mdl_role_assignments ra
											INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
											INNER JOIN mdl_course c ON c.id=ctx.instanceid
											INNER JOIN mdl_course cm ON c.from_courseid=cm.id
											INNER JOIN mdl_role r ON ra.roleid=r.id
											WHERE ra.userid={$H_USER->get_property('id')} AND ra.roleid != 5
											ORDER BY model, course, rol;");
		
	$view->Load('public/header');
	$view->Load('public/menu');
	$view->Load('public/index', $data);	
	$view->Load('public/footer');

//	prueba_mail_smtp();
?>
