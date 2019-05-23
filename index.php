<?php

	require_once 'config.php';

	$H_USER->require_login();

	$data['cumpleshoyactual'] = $LMS->GetAll("SELECT u.id, u.firstname,u.lastname,u.email, u.fnacimiento,c.shortname,c.id as courseid
															FROM mdl_user u
															INNER JOIN mdl_role_assignments ra ON ra.userid=u.id
															INNER JOIN mdl_context cont ON cont.id =ra.contextid AND cont.contextlevel=50
															INNER JOIN mdl_course c ON c.id=cont.instanceid AND c.periodo={$HULK->periodo}
															WHERE
															 DATE_FORMAT(FROM_UNIXTIME(u.fnacimiento),'%m-%d') = DATE_FORMAT(NOW(),'%m-%d');");

	 $data['cumpleshoyotros'] = $LMS->GetAll("SELECT DISTINCT u.id, u.firstname,u.lastname,u.email, u.fnacimiento, MAX(c.periodo), c.shortname, c.id as courseid
 															FROM mdl_user u
 															INNER JOIN mdl_role_assignments ra ON ra.userid=u.id
 															INNER JOIN mdl_context cont ON cont.id =ra.contextid AND cont.contextlevel=50
 															INNER JOIN mdl_course c ON c.id=cont.instanceid AND c.periodo!={$HULK->periodo}
 															WHERE
 															 DATE_FORMAT(FROM_UNIXTIME(u.fnacimiento),'%m-%d') = DATE_FORMAT(NOW(),'%m-%d')
															 GROUP BY(u.id) ORDER BY c.periodo DESC;");


	$view->Load('header');
	$view->Load('menu');
	$view->Load('menuroot');
	$view->Load('index', $data);
	//$view->Load('footer');
?>
