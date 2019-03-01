<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];


switch($v){
/***********************/
	case 'view':
		
		$H_USER->require_capability('admin/view');

		if($_REQUEST['log']){
			$data['logname']=$_REQUEST['log'];
		}else{
			$data['logname']="help_desk";
		}
		$menuroot['ruta'] = array("Sincronizadores"=>"sincronizadores.php","{$data['logname']}"=>"#");
		
		switch($data['logname']){
			case 'help_desk':
				$dir = "D:/AppServ/sincronizadores/help_desk/Logs";
			break;
		}
		

		if (is_dir($dir)) {
			if ($gd = opendir($dir)) {
				while (($archivo = readdir($gd)) !== false) {
					if ($archivo != "." && $archivo != ".."){
					  $data['logs'][]= array('name'=>$archivo,'ruta'=>$dir.'/'.$archivo);
					}					  
				}
				closedir($gd);
			}
		}		
		
		break;

/***********************/
	case 'help_desk':

		$H_USER->require_capability('admin/view');
		exec('D:\AppServ\sincronizadores\help_desk\Synchronizer.exe');
		header("Location: sincronizadores.php?v=view&log=help_desk");				
		break;

/***********************/
	case 'bajador':
		break;
	case 'gradebook_compare':
	
		$dir=$HULK->sincro_gradebook."/upload";
		
		if (is_dir($dir)) {
			if ($academys = opendir($dir)) {
				while (($academy = readdir($academys)) !== false) {
					if ($academy != "." && $academy != ".."){
						if ($courses = opendir($dir.'/'.$academy)) {
							while (($course = readdir($courses)) !== false) {
								if ($course != "." && $course != ".."){
									if ($gradebooks = opendir($dir.'/'.$academy.'/'.$course)) {
										while (($gradebook = readdir($gradebooks)) !== false) {
											if ($gradebook != "." && $gradebook != ".."){
												if (($handle = fopen($dir.'/'.$academy.'/'.$course.'/'.$gradebook, "r")) !== FALSE) {
													$row=1;
													unset($result);
													while (($file = fgetcsv($handle, 2000, ",")) !== FALSE) {										
														$ValorTotal = count($file);
														for ($c=0; $c < $ValorTotal; $c++) {
															if ($row==1){
																$col[$c]=trim(strtolower(utf8_encode($file[$c])));
															}elseif($row==3){
																$result[$col[$c]]=trim(utf8_encode($file[$c]));
																$gradebook = str_replace(array("Calificaciones-",".csv"),"",$gradebook);
																if ($gradebook!=$result['section'] AND $col[$c]=="section"){
																	$data['cambiados'][]= array("academia"=>$academy,"curso"=>$course,"file"=>$gradebook,"shortname"=>$result["section"]);
																	continue(3);
																}														
															}
														}
														$row++;
													}
													fclose($handle);
												}
											}
										}
									}	
								}
							}
							closedir($courses);
						}				  
					}					  
				}
				closedir($academys);
			}
		}		
		break;

/***********************/
	case 'recolector':
		break;

/***********************/
	case 'lms':

		$H_USER->require_capability('admin/view');
	
		$dir=$HULK->sincro_lms."/upload";
		$dir2=$HULK->sincro_lms."/historico";
	
		// Si hay nuevo archivo lo subimos
		if (isset($_FILES["archivo"])) {
			
			$files['userid']	= $H_USER->get_property('id');
			$files['name']		= $_FILES['archivo']['name'];
			$files['size']		= $_FILES['archivo']['size'];
			$files['type']		= $_FILES['archivo']['type'];
			$temp_ext 			= explode(".",$files['name']);
			$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
			$files['date']		= time();
			$fecha 				= date('Y-m-d_hi');
			if(in_array($files['ext'],array("csv"))){
				if (is_uploaded_file($_FILES['archivo']['tmp_name']))
				{
					if(!is_dir($dir)) 	
						@mkdir($dir, 0777);
					copy($_FILES['archivo']['tmp_name'], "{$dir}/{$fecha}_[O].csv");
					redireccionar("sincronizadores.php?v=lms");
				}
			}else{
				show_error("Fail ext","La extensión .{$files['ext']} del archivo que está intentando subir no está permitida.");
			}	
		}	
		switch($_POST['action']){
			case 'Descargar':
				$filename=$HULK->sincro_lms."/".$_POST['tipo']."/".$_POST['name'];
				header ("Content-Disposition: attachment; filename=".$_POST['name']."\n\n"); 
				header ("Content-Type: application/csv;  charset=utf-8");
				header ("Content-Length: ".filesize($filename));
			
				readfile($filename);
				exit;
				
			break;
			case 'Borrar':
				$filename=$HULK->sincro_lms."/".$_POST['tipo']."/".$_POST['name'];
				unlink($filename);
				redireccionar("sincronizadores.php?v=lms");
			break;
		}
	
	// Levantamos los archivo que hay
		if (is_dir($dir)) {
			if ($gd = opendir($dir)) {
				while (($archivo = readdir($gd)) !== false) {
					if ($archivo != "." && $archivo != ".." && strpos($archivo,".csv")){
					  $data['archivos'][]= array('name'=>$archivo,'ruta'=>$dir.'/'.$archivo);
					}					  
				}
				closedir($gd);
			}
		}
	// Levantamos los archivo que hay en historicos
		if (is_dir($dir2)) {
			if ($gd2 = opendir($dir2)) {
				while (($archivo2 = readdir($gd2)) !== false) {
					if ($archivo2 != "." && $archivo2 != ".."){
					  $data['archivos_h'][]= array('name'=>$archivo2,'ruta'=>$dir2.'/'.$archivo2);
					}					  
				}
				closedir($gd2);
			}
		}		
	
		break;
/***********************/
	case 'lms_view':

		$H_USER->require_capability('admin/view');
		$dir=$HULK->sincro_lms;
		set_time_limit(3600); // 1 hour should be enough
	
		$row = 1;
		$filename=$dir."/".$_POST['tipo']."/".$_POST['name'];
		$campos_obligatorios = array("firstname","lastname","username","email","course","academy","course_startdate","course_enddate");

		// Paso los datos del archivo a un vector organizado por nombre de campo
		if (($handle = fopen($filename, "r")) !== FALSE) {
			while (($file = fgetcsv($handle, 2000, ",")) !== FALSE) {
				$num = count($file);
				for ($c=0; $c < $num; $c++) {
					if ($row==1){
						$col[$c]=strtolower(utf8_encode($file[$c]));
					}else{
						$result[$row][$col[$c]]=utf8_encode($file[$c]);
					}
				}
				$row++;			
			}
			fclose($handle);
		}
		
			$data['preview']= "<table class='ui-widget' id='listado'>";
			foreach($result as $num=>$row){	
				$data['preview'].= "<tr style='height:25px;'><td class='ui-widget-content'>{$num}</td>";										
				foreach($row as $fieldname => $fieldvalue){
					
					if ($fieldname=="academy"){
						if (!$LMS->record_exists("mdl_proy_academy",$fieldvalue,$field='shortname')){
								$error_state="ui-state-error";
								$row_error.="La academia {$fieldvalue} no se encuentra en la base de datos. \r";
						}
					}
					if ($fieldname=="course"){
						if (!$LMS->record_exists("mdl_course",$fieldvalue,$field='shortname')){
							$error_state="ui-state-error";
							$row_error.="El curso modelo {$fieldvalue} no se encuentra en la base de datos. \r";
						}
					}
					$data['preview'].= "<td class='ui-widget-content {$error_state}' title='{$row_error}'>".$fieldvalue."</td>";										
					unset($error_state);
					unset($row_error);
				}
				$data['preview'].= "<td class='ui-widget-content {$error_state}'>".$row_error."</td></tr>";
			}
			$data['preview'].="</table>";
		break;

/***********************/
	case 'log':
		$H_USER->require_capability('admin/view');
		if($_POST['name']){
			$filename="D:/AppServ/sincronizadores/".$_POST['type']."/Logs/".$_POST['name'];
		//	$fp=fopen($filename, "r");
		//	fpassthru($fp);


			header ("Content-Disposition: attachment; filename=".$_POST['name']."\n\n"); 
			header ("Content-Type: application/octet-stream");
			header ("Content-Length: ".filesize($filename));
		
			readfile($filename);
		}

	exit;
	break;
	default:
		break;
}	

$view->Load('header',$data);
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('sincronizadores/'.$v,$data);
$view->Load('footer');

?>
