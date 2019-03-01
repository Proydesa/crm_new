<?php
	require_once 'config.php';
	
	if ($H_USER->is_loaded())
	{
		if ($_GET['logout']==1)
		{
			$H_USER->logout();
			header('Location: index.php');
			exit;
		}
		header('Location: index.php');
		exit;
	}else{
		if ((isset($_POST['username'])) AND (isset($_POST['password'])))
		{
			if ($H_USER->login($_POST['username'],$_POST['password'],$_POST['remember'], true ))
			{
				if($_SESSION['login_redirect']){
					header('Location: '.$_SESSION['login_redirect']);					
				}else{
					header('Location: index.php');
				}
				exit;

			}
		}
		$view->Load('header');
		$view->Load('login', $data);
		$view->Load('footer');
	}
?>
