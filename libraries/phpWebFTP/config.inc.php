<?php
	$currentVersion = "4.0";

	$maxFileSize = 2000000;
	
	$resumeDownload = false;
	$defaultLanguage = "es";
	$defaultServer = "ftp.fproydesa.com.ar";

	$_SESSION["server"] = "ftp.fproydesa.com.ar";
	$_SESSION["user"] = "crm.fproydesa.com.ar";
	$_SESSION["password"] = "5TPmlQsd";
	$_SESSION["language"] = "es";
	$_SESSION["port"] = "21";
	$_SESSION["passive"] = false;

	$disableLoginScreen = false;
	$editDefaultServer = true;

	$clearTemp = true;
	
	$ftp_directory= "libraries/phpWebFTP/";
	$server_dir="http://www.fproydesa.com.ar/cont";
	$downloadDir = $ftp_directory."tmp";  # This is the tmp directory in the phpwebftp directory and should work by default

?>
