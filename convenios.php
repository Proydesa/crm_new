<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'new':
		$menuroot['ruta'] = array("Convenios"=>"convenios.php?v=new");

		$id = $_REQUEST['id'];

		// Si id entonces edit
		if ($_POST){
			if ($id){
				$form_data = $_POST;
				$form_data['id']=$id;
				$LMS->setConvenio($form_data);
				header("Location: convenios.php?v=view&id={$id}");
			}else{
				$form_data = $_POST;
				$form_data['fullname'] = utf8_decode($_POST['fullname']);
				$form_data['summary'] = utf8_decode($_POST['summary']);
				$id = $LMS->newConvenio($form_data);
				header("Location: convenios.php?v=view&id={$id}");
			}
		}
		break;
/***********************/
	case 'view':
		$id = $_REQUEST['id'];

		$data['row']		= $LMS->getConvenio($id);
		$data['carreras']	= $LMS->getConvenioCourse($id);
		$data['academys']	= $LMS->getConvenioAcademys($id);
		$menuroot['ruta'] = array("Convenios"=>"convenios.php?v=list",$data['row']['fullname']=>"#");
		break;
/************************/
	case 'list':

		$menuroot['ruta'] = array("Convenios"=>"#");

		$data['rows']		= $LMS->getConvenios();

		break;

	default:
		$v	=	'index';
		break;
}

$view->Load('header',$data);
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('convenios/'.$v,$data);
$view->Load('footer');

?>
