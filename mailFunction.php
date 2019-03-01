<?php

	require_once 'config.php';

	$H_USER->require_login();

	$f 	= $_REQUEST['f'];

	if($f){
		$f();
	}
	
	function hd_new_ticket(){
	
		global $HULK,$H_USER,$LMS;

		foreach(explode(',',$HULK->hd_user_notif) as $touser){
			echo $touser."<br/>".$LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$touser)." ".$LMS->GetField('mdl_user','email',$touser);
		}
		echo $H_USER->get_users_cap("activity/representante");
		/*
		$mail = new H_Mail();
		$mail->Subject("Notificación de nuevo incidente");
		$mail->Body("Notificación de nuevo incidente en el <b>Help Desk</b>");
		$mail->AltBody("Notificación de nuevo incidente en el Help Desk 345");
		$mail->AddAddress("santiago.ei@gmail.com", "Santiago Ei");
		$mail->Send();
		*/
		
	}

?>