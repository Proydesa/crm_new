<?php

		require_once 'config.php';

		$H_USER->require_login();

		$type= $_REQUEST['type'];
		$data['v'] = $v = $_REQUEST['v'];


	  $old_limit = ini_set("memory_limit", "16M");

		$user = $_REQUEST['userid'];
		$file = $_REQUEST['file'];

		$filename = $HULK->dirroot.'/data/users/'.$user.'/'.$file;

    $extensiones = array("exe","php");
    if(strpos($file,"/")!==false){
        die("No puedes navegar por otros directorios");
    }
    $ftmp = explode(".",$file);
    $fExt = strtolower($ftmp[count($ftmp)-1]);

    if(in_array($fExt,$extensiones)){
        die("<b>ERROR!</b> no es posible descargar archivos con la extensión $fExt");
    }

    $fp=fopen("$filename", "r");
    header("Content-type: application/octet-stream");
    header("Content-Disposition: attachment; filename=\"$file\"\n");
    fpassthru($fp);
	exit;
?>
