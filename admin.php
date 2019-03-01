<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];
# Role
$r = $_REQUEST['r'];


switch($v){
/***********************/
	case 'configuracion':

		if ($_GET['stage']==1){
			if ($_POST['id']>0){
				$H_DB->update('h_config',$_POST,"id = {$_POST['id']}");
			}else{
				$H_DB->insert('h_config',$_POST);
			}
		}

		$data['rows']		= $H_DB->GetAll("SELECT * FROM h_config;");

		break;

/***********************/
	case 'roles':

		// Genero o actualizo Roles!
		if ($H_USER->has_capability("role/create")){
			if ($_GET['action']=="modifiedrole"){
				if ($_POST['id']>0){
					$H_DB->update('h_role',$_POST,"id = {$_POST['id']}");
				}else{
					$H_DB->insert('h_role',$_POST);
				}
			}
		}
		// Modifico capacidades de los roles
		if ($H_USER->has_capability("role/edit")){
			if ($_GET['action']=="modifiedcapacity"){
				if($H_DB->record_exists_sql("SELECT id FROM h_role_capabilities
																		 WHERE roleid = {$_POST['roleid']} AND capability LIKE '{$_POST['capability']}'")){
					$H_DB->delete('h_role_capabilities',"roleid = {$_POST['roleid']} AND capability LIKE '{$_POST['capability']}'",false);
				}else{
					$H_DB->insert('h_role_capabilities',$_POST);
				}
			}
		}

		$data['roles']	= $H_DB->GetAll("SELECT r.*, (SELECT COUNT(ra.id) FROM h_role_assignments ra
																		 WHERE ra.roleid=r.id) AS users FROM h_role r ORDER By weight;");

		if (!$r) $r="1";
		$data['r']=$r;
		$data['role_name'] = $H_DB->GetField('h_role','name',$r);

		$data['role_capabilities']	= $H_DB->GetAll("SELECT * FROM h_role_capabilities rc
																								 WHERE rc.roleid={$r} ORDER BY rc.capability ASC;");

		$data['role_disabilities']	= $H_DB->GetAll("SELECT DISTINCT rc.capability, rc.summary
																								 FROM h_role_capabilities rc WHERE NOT EXISTS
																									(SELECT rc2.capability, rc2.summary FROM h_role_capabilities rc2
																									WHERE rc2.roleid={$r} AND rc.capability = rc2.capability )
																								 ORDER BY rc.capability;");


		$data['role_assignments']	= $H_DB->GetAll("SELECT * FROM h_role_assignments ra
																								WHERE ra.roleid={$r};");

		break;

/***********************/
	case 'cuotas':

		$courseid = $data['courseid'] = $_REQUEST['courseid'];


		if ($_GET['stage']==1){

			$_POST['cuotas'] = implode('#', array_filter($_POST['cuotas']));
			$_POST['cuotas_intensivo'] = implode('#', array_filter($_POST['cuotas_intensivo']));
			$_POST['periodo_uno'] = implode('#', array_filter($_POST['periodo_uno']));
			$_POST['periodo_dos'] = implode('#', array_filter($_POST['periodo_dos']));
			$_POST['periodo_tres'] = implode('#', array_filter($_POST['periodo_tres']));
			$_POST['periodo_uno_int'] = implode('#', array_filter($_POST['periodo_uno_int']));
			$_POST['periodo_dos_int'] = implode('#', array_filter($_POST['periodo_dos_int']));
			$_POST['periodo_tres_int'] = implode('#', array_filter($_POST['periodo_tres_int']));

			if($H_DB->record_exists('h_cuotas_curso',$_POST['courseid'],$field='courseid')){
				$H_DB->update('h_cuotas_curso',$_POST,"courseid = {$_POST['courseid']}");
			}else{
				$H_DB->insert('h_cuotas_curso',$_POST);
			}
		}

		if($courseid){
			$cuotas = $H_DB->GetRow("SELECT * FROM h_cuotas_curso WHERE courseid={$courseid};");
			$data['cuotas']	= explode('#',$cuotas['cuotas']);
			$data['cuotas_intensivo']	= explode('#',$cuotas['cuotas_intensivo']);
			$data['periodo_uno']	= explode('#',$cuotas['periodo_uno']);
			$data['periodo_dos']	= explode('#',$cuotas['periodo_dos']);
			$data['periodo_tres']	= explode('#',$cuotas['periodo_tres']);
			$data['periodo_uno_int']	= explode('#',$cuotas['periodo_uno_int']);
			$data['periodo_dos_int']	= explode('#',$cuotas['periodo_dos_int']);
			$data['periodo_tres_int']	= explode('#',$cuotas['periodo_tres_int']);
		}

		$data['models']	= $LMS->GetAll("SELECT DISTINCT c.id, c.fullname
																		FROM mdl_course c
																		WHERE c.academyid=0 AND c.id NOT IN (1,195)
																		ORDER BY c.fullname;");

		break;

/***********************/
	case 'libros':
		// Genero o actualizo Libros!
		if ($_GET['stage']==1){
			if(isset($_REQUEST['botondelete'])){
				$H_DB->update('h_libros',array(deleted=>1),"id = {$_POST['id']}");
			}
			if(isset($_REQUEST['botoneditar'])){
				$data['activar']=$_REQUEST['botoneditar'];
			}
			if(isset($_REQUEST['botonguardar'])){
				$form['id']=$_POST['id'];
				$form['name']=$_POST['name'];
				$form['detalle']=$_POST['detalle'];
				$form['valor']=$_POST['value'];
				$form['deleted']=0;
				$form['modelid']=$_POST['modelid'];
				$H_DB->update('h_libros',$form,"id = {$_POST['id']}");
			}
			if($_POST['id']<1){
				$H_DB->insert('h_libros',$_POST);
			}

		}
		$data['libros']	= $H_DB->GetAll("SELECT * FROM h_libros
												WHERE deleted != 1;");

		$data['models'] = $LMS->GetAll("SELECT id, fullname FROM mdl_course WHERE academyid=0 AND id>1 ORDER BY fullname;");

		break;

/***********************/
	case 'horarios':

		// Genero o actualizo Horarios!
		if ($_GET['stage']==1){
			if ($_POST['id']>0){
				$H_DB->update('h_horarios',$_POST,"id = {$_POST['id']}");
			}else{
				$H_DB->insert('h_horarios',$_POST);
			}
		}

		$data['horarios']	= $H_DB->GetAll("SELECT * FROM h_horarios;");

		break;

/***********************/
	case 'asuntos':

		// Genero o actualizo asuntos
		if ($_GET['stage']==1){
			if ($_POST['id']>0){
				$H_DB->update('h_activity_subjects',$_POST,"id = {$_POST['id']}");
			}else{
				$H_DB->insert('h_activity_subjects',$_POST);
			}
			header("Location: admin.php?v=asuntos");
		}

		$data['asuntos']	= $H_DB->GetAll("SELECT * FROM h_activity_subjects;");

		break;
/***********************/
	case 'bancos':

		if ($_GET['stage']==1){
			if ($_POST['id']>0){
				$H_DB->update('h_bancos',$_POST,"id = {$_POST['id']}");
				header("Location: admin.php?v=bancos");
			}else{
				$H_DB->insert('h_bancos',$_POST);
				header("Location: admin.php?v=bancos");
			}
		}

		$data['rows']		= $H_DB->GetAll("SELECT * FROM h_bancos;");

		break;
/***********************/
	case 'leerpagomiscuentas':
		if ($_FILES){
			$filepath = $_FILES['pagomiscuentasfile']['tmp_name'];
			if (!file_exists($filepath) || !is_file($filepath)) {
 				show_error("Error de archivo","{$filepath} no funciona o no es un archivo."); exit;
			}
			if($fp = @fopen($filepath, "r")){
				$x=0;
			    while (($linea = fgets($fp, 4096)) !== false) {

					if (substr($linea,0,1)==5){
						$pagos[$x]["userid"]	=	$H_DB->GetField('h_cuotas','userid',substr($linea,20,6));
						$pagos[$x]["dni"]		=	substr($linea,1,8);
						$pagos[$x]["cuota"]		=	$H_DB->GetField('h_cuotas','cuota',substr($linea,20,6));
						$pagos[$x]["curso"]		=	$LMS->GetField('mdl_course','shortname',$H_DB->GetField('h_cuotas','courseid',substr($linea,20,6)));
						$pagos[$x]["importe"]	=	substr($linea,57,9).".".substr($linea,65,2);
						$pagos[$x]["fecha_pago"]		=	substr($linea,55,2)."/".substr($linea,53,2)."/".substr($linea,49,4);
						$pagos[$x]["fecha_acredita"]	=	substr($linea,75,2)."/".substr($linea,73,2)."/".substr($linea,69,4);
						$x++;
					}
				}
				if ($pagos){
					$data['pagos']=$pagos;
				}else{
					show_error("No se encontraron registros compatibles","Ninguno de los registrois del archivo comenzaba con 5 que es el id de los registros de pagos.");
				}
				fclose($fp);
			}else{
				show_error("Error al abrir el archivo. Function fopen.");
			}
		}
		break;
/***********************/
	case 'pagomiscuentas':

		if ($_GET['semana']){
			$archivo=$HULK->dirroot.'/data/pagomiscuentas/FAC3634.'.$_GET['semana'];

  		$fp = fopen($archivo, 'w');

			$result	=	$H_DB->GetAll("SELECT c.userid, c.id as cuotaid, c.courseid, c.cuota, c.valor_cuota,c.valor_pagado,FROM_UNIXTIME(c.date,'%d-%m-%Y') as fecha, c.periodo, i.comisionid, comp.concepto,comp.pendiente
									FROM h_cuotas c
									INNER JOIN h_inscripcion i ON i.id = c.insc_id
									INNER JOIN vw_course cou ON cou.id = i.comisionid
									LEFT JOIN h_comprobantes_cuotas cocu ON cocu.cuotaid=c.id
									LEFT JOIN h_comprobantes comp ON comp.id=cocu.comprobanteid
									WHERE c.periodo>={$HULK->periodo} 
									AND ((c.valor_pagado<c.valor_cuota) OR (comp.pendiente=1 AND comp.concepto IN(1,6)))
										AND c.valor_cuota > 0 AND WEEK(FROM_UNIXTIME(c.date))='{$_GET['semana']}'
										AND cou.academyid=1
										ORDER BY c.userid,c.courseid,c.cuota;");
			$codigoregistro = "0";
			$codigobanelco 	= "400";
			$codigoempresa	= "3634";
			$fechaarchivo 	= date("Ymd",time());
			$cantidad=0;
			$filler1 		= str_pad("", 7, "0", STR_PAD_LEFT);
			$filler2		= str_pad("", 239, "0", STR_PAD_LEFT);
			$filler			= str_pad("", 264, "0", STR_PAD_LEFT);

			fwrite($fp, "{$codigoregistro}{$codigobanelco}{$codigoempresa}{$fechaarchivo}{$filler}".PHP_EOL);
			foreach($result as $row){
				$iniciocurso = $LMS->GetField("mdl_course","startdate",$row['comisionid']);
				if($row['cuota']==1){
					$vencimiento = date("Y",$iniciocurso).str_pad(date('m', $iniciocurso), 2, "0", STR_PAD_LEFT).str_pad(date('d', $iniciocurso), 2, "0", STR_PAD_LEFT);
				}else{
					$vencimiento = date("Y",$iniciocurso).str_pad((date('m', $iniciocurso)+$row['cuota']-1), 2, "0", STR_PAD_LEFT)."10";
				}
				$importe=$row['valor_cuota']-$row['valor_pagado'];
				if ($vencimiento<date("Ymd")) continue;
				if ($importe<2) continue;

				$codigoregistro = "5";
				$nroreferencia	= str_pad($LMS->GetField("mdl_user","username",$row['userid']), 19, " ", STR_PAD_RIGHT);
				$idfactura		= str_pad($row['cuotaid'], 20, " ", STR_PAD_RIGHT);
				$codigomoneda	= "0";
				$fecha1ervto	= $vencimiento;
				$importe1ervto	= str_pad(number_format($importe, 2, '', ''), 11, "0", STR_PAD_LEFT);
				$fecha2dovto	= $vencimiento;
				$importe2dovto	= str_pad(number_format($importe, 2, '', ''), 11, "0", STR_PAD_LEFT);
				$fecha3ervto	= $vencimiento;
				$importe3ervto	= str_pad(number_format($importe, 2, '', ''), 11, "0", STR_PAD_LEFT);
				$filler11		= str_pad("", 19, "0", STR_PAD_LEFT);
				$nroreferenciaant	= $nroreferencia;
				if($row['cuota']==0){
					$textcuota="EKIT";
				}else{
					$textcuota="CUOTA ".$row['cuota'];
				}
				$vowels = array("-", ".");
				$nombrecorto	= str_replace($vowels," ",strtoupper($LMS->GetField("mdl_course","shortname",$row['courseid'])));
				$mensajeticket	= "GRACIAS POR EL PAGO DE ".str_pad($nombrecorto.$textcuota, 17, " ", STR_PAD_RIGHT);
				$tempmenpant=substr($nombrecorto,0,7);
				$mensajepantalla= str_pad($tempmenpant, 7, " ", STR_PAD_RIGHT).str_pad($textcuota, 8, " ", STR_PAD_RIGHT);
				$codigobarras	= str_pad("", 60, " ", STR_PAD_RIGHT);
				$filler16		= str_pad("", 29, "0", STR_PAD_LEFT);
				$cantidad++;
				fwrite($fp, "{$codigoregistro}{$nroreferencia}{$idfactura}{$codigomoneda}{$fecha1ervto}{$importe1ervto}{$fecha2dovto}{$importe2dovto}{$fecha3ervto}{$importe3ervto}{$filler11}{$nroreferenciaant}{$mensajeticket}{$mensajepantalla}{$codigobarras}{$filler16}".PHP_EOL);
				$valor_total = $valor_total+$importe;
			}
			$codigoregistro = "9";
			$totalimporte	= str_pad(number_format($valor_total, 2, '', ''), 11, "0", STR_PAD_LEFT);
			$cantregistros	= str_pad($cantidad, 7, "0", STR_PAD_LEFT);

			fwrite($fp, "{$codigoregistro}{$codigobanelco}{$codigoempresa}{$fechaarchivo}{$cantregistros}{$filler1}{$totalimporte}{$filler2}");
			fclose($fp);
			$enlace = $archivo;

	    header("Content-Description: File Transfer");
			header("Content-Disposition: attachment; filename=FAC3634.".date('dmy') );
      header("Content-Type: application/octet-stream");
      header("Content-Transfer-Encoding: binary");
			header("Content-Length: ".filesize($archivo));

			ob_clean();
      flush();
      readfile($archivo);
      exit;
		}
		$data['fechas']	=	$H_DB->GetAll("SELECT FROM_UNIXTIME(h.date,'%m-%Y') as fecha, count(*) as cant,MIN(FROM_UNIXTIME(h.date,'%d-%m-%Y')) as minimo, MAX(FROM_UNIXTIME(h.date,'%d-%m-%Y')) as maximo, WEEK(FROM_UNIXTIME(h.date)) as semana, (WEEK(FROM_UNIXTIME(h.date)) - FROM_UNIXTIME(h.date,'01-%m-%Y')) as semdelmes
											FROM h_cuotas h
											INNER JOIN h_inscripcion i ON i.id = h.insc_id    
											INNER JOIN vw_course cou ON cou.id = i.comisionid
											LEFT JOIN h_comprobantes_cuotas cocu ON cocu.cuotaid=h.id
											LEFT JOIN h_comprobantes comp ON comp.id=cocu.comprobanteid
											WHERE ((h.valor_pagado<h.valor_cuota) OR (comp.pendiente=1 AND comp.concepto IN(1,6)))
											 AND h.valor_cuota >0 AND h.periodo>={$HULK->periodo} 
											 AND cou.academyid=1 GROUP BY (semana) ORDER BY h.date DESC;");
		break;
	default:
		break;
}

$view->Load('header',$data);
$view->Load('menu');
$view->Load('admin/'.$v,$data);
$view->Load('footer');

?>
