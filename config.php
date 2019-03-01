<?php  // Biblioteca Configuration File

unset($HULK);

$HULK = new stdClass;

$HULK->dbtype    = 'mysqli';
$HULK->dbhost    = 'localhost';
$HULK->dbname    = 'crm';
$HULK->dbuser    = 'moodleadmin';
$HULK->dbpass    = 'myadmin';
$HULK->prefix    = 'h_';

$HULK->wwwroot   = '//'.$_SERVER['SERVER_NAME'].'/crm_new';

$HULK->dataroot  = '/var/www/html/crm_new/data';

$HULK->lms_dbtype   = 'mysqli';
$HULK->lms_dbhost   = 'localhost';
$HULK->lms_dbname   = 'moodle';
$HULK->lms_dbuser   = 'moodleadmin';
$HULK->lms_dbpass   = 'myadmin';
$HULK->lms_prefix   = 'mdl_';
$HULK->lms_wwwroot	= 'https://'.$_SERVER['HTTP_HOST'].'/lms_new';

$HULK->lms_dirroot	= '/var/www/html/lms_new';

$HULK->passwordsaltmain = 'laprofundaproydesasalada';

//se define constante de ambiente 29/08/2018
define("AMBIENTE", "DESARROLLO");


require_once("setup.php");