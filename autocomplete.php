<?php

	require_once 'config.php';

	$H_USER->require_login();
	$q = $_GET['term'];

	$rows	= $LMS->GetAll("SELECT CONCAT(firstname,' ', lastname) as name, id, username, email FROM mdl_user 
												WHERE (firstname LIKE '%{$q}%' OR lastname LIKE '%{$q}%' OR username LIKE '%{$q}%')
													AND deleted=0 LIMIT 0,10;");

	foreach($rows as $row){

		$json[] = array(
			'label' => $row['username']." - ".$row['name'],
			'value' => $row['username']." - ".$row['name'],
			'id' => $row['id'],
		);
		
		
	}

	echo json_encode($json);
?>
