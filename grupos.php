<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'new':

		$menuroot['ruta'] = array("Empresas"=>"grupos.php","Nueva"=>"#");

		if(isset($_POST['boton'])){
			if($H_DB->record_exists('h_grupos',"('{$_POST['name']}')",'name')){
				show_error("Error","Ya existe una empresa con ese nombre.");
				die();
			}
			if($H_DB->record_exists('h_grupos',"('{$_POST['cuit']}')",'cuit')){
				show_error("Error","Ya existe una empresa con ese Cuit.");
				die();
			}
			//alan
			if($_POST['email']!=""){
				if($H_DB->record_exists('h_grupos',"('{$_POST['email']}')",'email')){
					show_error("Error","Ya existe una empresa con esa direcci&oacute;n de email.");
					die();
				}
			}
			//alan
			$_POST['startdate']= time();
			$_POST['name']= utf8_decode($_POST['name']);
			$_POST['summary']= utf8_decode($_POST['summary']);

			if ($grupo['id'] = $H_DB->insert("h_grupos",$_POST)){
				redireccionar("grupos.php?v=view&id={$grupo['id']}");
			}
		}


		break;

/***********************/
	case 'pagos-print':

		$id = $_REQUEST['id'];

		$data['comprobante'] = $H_DB->GetRow("SELECT * FROM h_comprobantes h WHERE id={$id};");


		$grupoid = $data['comprobante']['grupoid'];

		$contactos = $H_DB->GetAll("SELECT DISTINCT c.userid
															FROM h_comprobantes_cuotas cc
															INNER JOIN h_cuotas c ON cc.cuotaid=c.id
															WHERE cc.comprobanteid={$id};");
		/*		$cont2 = "<br />Alumno/s: ";
		foreach ($contactos as $cont){
			$cont2 .= $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$cont['userid']).", ";
		}
		*/
		$data['comprobante']['leyenda'] =	" de ".count($contactos)." participantes";

		$grupo = $H_DB->GetRow("SELECT * FROM h_grupos g WHERE id={$grupoid};");

		$data['comprobante']['señores']		=	$grupo['name'].$cont2;
		$data['comprobante']['domicilio'] =	$grupo['address'];
		$data['comprobante']['tel']				=	$grupo['phone'];
		$data['comprobante']['localidad'] =	$grupo['city'];
		$data['comprobante']['cp']  			=	$grupo['cp'];
		$data['comprobante']['cuit']			=	$grupo['cuit'];
		$data['comprobante']['username']	=	$grupo['name'].$cont2;
		$data['comprobante']['iva'.$grupo['iva']]="X";

		$data['nombre']		=	$grupo['name'];

		if($data['comprobante']['tipo']!=3){
			$data['cuotas'] = $H_DB->GetAll("SELECT DISTINCT cuota,importe,valor_cuota,cuotaid,comprobanteid,periodo,courseid,libroid, insc_id
																				FROM h_comprobantes_cuotas cc
																			 INNER JOIN h_cuotas c ON cc.cuotaid=c.id
																			 WHERE cc.comprobanteid={$id};");
			/*
			//TODO: Revisar si no tiene cuotas pagas!!!
			$data['total_cuotas'] = $H_DB->GetOne("SELECT MAX(cuota) FROM h_cuotas c
																						 WHERE c.grupoid={$data['comprobante']['grupoid']}
																						 AND c.courseid={$data['cuotas'][0]['courseid']}
																						 AND c.periodo={$data['cuotas'][0]['periodo']};");
			*/
			// AgregadoTeban INICIO
			if($data['cuotas']){
				$data['total_cuotas'] = $H_DB->GetOne("SELECT MAX(cuota) FROM h_cuotas c
																							 WHERE insc_id = {$data['cuotas'][0]['insc_id']};");
				$data['becado'] = $H_DB->GetOne("SELECT becado FROM h_inscripcion WHERE id ={$data['cuotas'][0]['insc_id']};");
			}else{
				show_error("Error", "El recibo no tiene cuotas asociadas");
			}
			// AgregadoTeban FIN

		}else{
			$data['comps'] = $H_DB->GetAll("SELECT * FROM h_comprobantes WHERE cancel={$data['comprobante']['id']};");
		}
		
		if($data['comprobante']['tipo']==1){
			$view->Load('print/recibo',$data);
		}elseif($data['comprobante']['tipo']==2){
			$view->Load('print/factura',$data);
		}elseif($data['comprobante']['tipo']==3){
			$view->Load('print/nota_credito',$data);
		}
		die();

		break;

/***********************/
	case 'view':
		$id = $_GET['id'];
		$menuroot['ruta'] = array("Empresas"=>"grupos.php","Ver"=>"#");

		if ($_POST['userid']){
			if ($grupo['id'] = @$H_DB->insert("h_grupos_users",$_POST)){
				redireccionar("grupos.php?v=view&id={$id}");
			}
		}
		$_comp	= new Comprobantes;

		if ($_GET['action']=='nrocomprobante'){

			$numero = $_POST['numero'];

			// Valido que el número de comprobante no exista.
			if($H_DB->record_exists('h_comprobantes',$numero,'numero')){
				show_error("record_exists","N&uacute;mero de comprobante existente.");die();
			}

			if(!$H_DB->update("h_comprobantes",array(numero=>$_POST['numero']),"id = ".$_POST['comprobanteid'])){
				show_error("update","Error al actualizar h_comprobantes.");die();
			}
			header("Location: {$HULK->STANDARD_SELF}");
		  exit;

		}


		if ($_REQUEST['q']){
			$data['filtros'] =	$search_words = explode(' ',$_REQUEST['q']);
			$WHERE .= "WHERE u.deleted=0 ";

			foreach($search_words as $word){
				$not=''; $con=' OR ';
				if (substr($word, 0, 1)=='-'){$con=' AND ';  $not=' NOT '; $word=substr($word, 1); }

				$WHERE .=" AND (username {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} lastname {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} firstname {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} acid {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} email {$not} LIKE '%{$word}%' )";
			}
					// Cantidad de resultados
			$data['nomiembros']		= $LMS->GetAll("SELECT DISTINCT u.id, u.username, u.lastname, u.firstname, u.email, u.acid  FROM mdl_user u {$WHERE} ORDER BY u.id DESC LIMIT 0,15;");
		}

		if ($id){
			$data['grupo']		= $H_DB->GetRow("SELECT * FROM h_grupos WHERE id={$id};");
			$data['usuarios']	= $H_DB->GetAll("SELECT * FROM h_grupos_users WHERE grupoid={$id};");
		}

		$cuotas	= $H_DB->GetAll("SELECT DISTINCT c.*
														 FROM h_cuotas c
														 WHERE c.grupoid={$id}
														 ORDER BY c.courseid, c.periodo, c.cuota;");
		foreach($cuotas as $cuota):
			$courseid = $cuota['courseid'];
			$periodo = $cuota['periodo'];

			$data['cuotas'][$courseid.$periodo]['id'] = $cuota['courseid'];
			$data['cuotas'][$courseid.$periodo]['cuota'.$cuota['cuota']] = $cuota['valor_cuota']-$cuota['valor_pagado'];
			$data['cuotas'][$courseid.$periodo]['periodo'] = $periodo;
			if (!is_array($data['cuotas'][$courseid.$periodo]['userid']))
				$data['cuotas'][$courseid.$periodo]['userid']=array();

			if (!in_array($cuota['userid'],$data['cuotas'][$courseid.$periodo]['userid'])){
				$data['cuotas'][$courseid.$periodo]['userid'][] = $cuota['userid'];
			}
		endforeach;

		$data['comprobantes'] = $H_DB->GetAll("SELECT * FROM h_comprobantes h WHERE grupoid={$id} ORDER BY date,numero;");

	break;

/************************/
case 'inscripcion':

		$id = $_REQUEST['id'];
		$data['stage'] = $stage = $_REQUEST['stage'];
		$data['users'] = $_POST['users'];
		$data['group'] = $_GET['group'];

		$menuroot['ruta'] = array("Empresas"=>"grupos.php?v=view&id={$data['group']}","Formulario de inscripción"=>"#");
		switch($stage){
			case 0:

				$data['academys']	= $LMS->getAcademys();
				$data['modelos'] = $LMS->getModelCourseActivos($HULK->periodo,1);

				$data['grades'][0]='E';		$data['grades_class'][0]='';
				$data['grades'][1]='I';		$data['grades_class'][1]='ui-state-error';
				$data['grades'][2]='F';		$data['grades_class'][2]='ui-state-error';
				$data['grades'][3]='P';		$data['grades_class'][3]='ui-state-highlight';
				break;

			case 1:

				if ($_POST['comi']>0){
					//show_array($_REQUEST);//die();

					$comision 			= $_POST['comi'];
					$modelo 				= $_POST['curso'];
					$cuotas 				= $_POST['cuotas'];
					$concepto				=	$_POST['concepto'];
					$nrocheque 			= $_POST['nrocheque'];
					$libroid 				= $_POST['libroid'];
					$tipo						= $_POST['tipo'];
					if(!$_POST['pendiente']){
						$pendiente = 0;
					}else{
						$pendiente = $_POST['pendiente'];
					}
					//AgregadoTeban INICIO
					if(!$_POST['becado']){
						$becado = 0;
					}else{
						$becado = $_POST['porc_beca'];
					}
					//AgregadoTeban FIN
					$comprobante->numero = 0;

					//Calculo valor total del comprobante y valor por pibe en base a las cuotas seleccionadas
					if ($_POST['libro_sel']){
						$importe_total = $_POST['cuotas_total'][0];
						$importe_alumno = $_POST['cuotas'][0];
					}
					foreach($_POST['cuota_cobrar'] as $cuot){
						$importe_total += $_POST['cuotas_total'][$cuot];
						$importe_alumno		+= $_POST['cuotas'][$cuot];
					}

					//TODO: Creo recibo de pago
					$comprobante->userid		= 0;
					$comprobante->grupoid		= $_POST['grupoid'];
					$comprobante->date			= time();
					$comprobante->importe		= $importe_total;
					$comprobante->concepto	= $concepto;
					$comprobante->tipo			= $tipo;
					$comprobante->detalle		= "";
					$comprobante->takenby 	= $H_USER->get_property('id');
					$comprobante->nrocheque = $nrocheque;
					$comprobante->pendiente = $pendiente;

					//show_array($comprobante);die();

					if(!$comprobante->id = $H_DB->insert("h_comprobantes",$comprobante)){
						show_error("Pagos","Error al insertar en la base de datos");
					}

					foreach($data['users'] as $id){
						$importe		=	$importe2 = $importe_alumno;

						if(!$LMS->enrolUser($id, $comision, 5) ){
							show_error("Error","Error al enrolar el usuario.");
						}

						// Modulo de actividad
						$activity['userid'] 		= $H_USER->get_property('id');
						$activity['contactid']	= $id;
						$activity['typeid'] 		= 4;
						$activity['statusid'] 	= 7;
						$activity['subject'] 		= "Inscripción en {$LMS->GetField('mdl_course','shortname',$modelo)}";
						$activity['summary'] 		= "Se enroló al contacto en la comisión {$LMS->GetField('mdl_course','shortname',$comision)}.";
						$activity['startdate'] 	= time();
						$activity['enddate']	 	= time();

						if(!$H_DB->insert("h_activity",$activity)){
							show_error("Error al registrar la actividad.");
						}

						// Registro la inscripción
						$insc->userid 		= $id;
						$insc->courseid 	= $modelo;
						$insc->date				= time();
						$insc->takenby		= $H_USER->get_property('id');
					//	$insc->periodo		= $HULK->periodo_insc;
						$insc->periodo		= $LMS->GetField("mdl_course","periodo",$comision);
						$insc->comisionid = $comision;
						//AgregadoTeban INICIO
						$insc->becado			= $becado;
						//AgregadoTeban FIN

						if(!$insc->id = $H_DB->insert("h_inscripcion", $insc)){
							show_error("Error al registrar la inscripción.");
						}

						if ($cuotas){
							//TODO: crear cobrar cuota
							foreach ($cuotas as $indice=>$cuota){
								if ($cuota>0){
									unset($c_insert);
									$c_insert->userid		= $id;
									$c_insert->courseid		= $modelo;
									$c_insert->cuota		= $indice;
									$c_insert->valor_cuota	= $cuota;
									$c_insert->grupoid		= $_POST['grupoid'];
									$c_insert->insc_id		= $insc->id;
									// Si es plan especial
									if($p_especial){
										$c_insert->p_especial = 1;
									} else {
										$c_insert->p_especial = 0;
									}

									if($indice>0){
										if($importe>=$cuota){
											$c_insert->valor_pagado	= $cuota;
											$importe = $importe-$cuota;
										}elseif($importe<$cuota){
											$c_insert->valor_pagado	= $importe;
											$importe = 0;
										}
									}else{
										$c_insert->libroid 			= $libroid;
										$c_insert->valor_cuota	= $cuota;
										if($_POST['libro_sel']){
											if($importe>=$cuota){
												$c_insert->valor_pagado	= $cuota;
												$importe = $importe-$cuota;
											}elseif($importe<$cuota){
												$c_insert->valor_pagado	= $importe;
												$importe = 0;
											}
										}
									}
									$c_insert->date					= time();
									$c_insert->takenby			= $H_USER->get_property('id');
								//	$c_insert->periodo			= $HULK->periodo_insc;
									$c_insert->periodo			= $LMS->GetField("mdl_course","periodo",$comision);

									//Si no tiene valor entonces ese curso no tiene libro asociado.
									if($indice == 0 && $c_insert->valor_cuota == 0){
									}else{
										if(!$c_insert->id = $H_DB->insert("h_cuotas",$c_insert)){
											show_error("Cuotas","Error al insertar en la base de datos");
										}
										if($c_insert->valor_pagado>0){
											if(!$H_DB->insert("h_comprobantes_cuotas",array('comprobanteid'=>$comprobante->id,'cuotaid'=>$c_insert->id,'importe'=>$c_insert->valor_pagado))){
												show_error("Cuotas","Error al insertar h_comprobantes_cuotas en la base de datos");
											}
										}
									}
								}
							}
						}
						unset($insc);
					}
					$view->Load('header',$data);

					if($importe2>0){
						$view->js("window.open('grupos.php?v=pagos-print&id={$comprobante->id}','imprimir','width=600,height=500,scrollbars=NO');");
						// $view->js("$(location).attr('href','contactos.php?v=pagos&id={$id}');");
						// $view->js("window.locationf='contactos.php?v=pagos&id={$id}';");
					}
					$view->js("window.location='grupos.php?v=view&id={$comprobante->grupoid}';");
					$view->Load('footer');
					die();
				}
			header("Location: grupos.php?v=view&id={$comprobante->grupoid}");
			die();
			break;
		}

		break;
/************************/

	case 'cuotas':

		$academy		= $_REQUEST['academy'];
		$curso			= $_REQUEST['curso'];
		$intensivo	= $_REQUEST['intensivo'];
		$cant	= $_REQUEST['cant'];

		if($intensivo==1){
			$cuotas = $H_DB->GetAll("SELECT cuotas_intensivo FROM h_cuotas_curso WHERE courseid={$curso}");
			$cuotas = explode("#",$cuotas['0']['cuotas_intensivo']);
		}else{
			$cuotas = $H_DB->GetAll("SELECT cuotas FROM h_cuotas_curso WHERE courseid={$curso}");
			$cuotas = explode("#",$cuotas['0']['cuotas']);
		}
		$x=0;
		foreach($cuotas as $cuota):
			$x++;

			echo "<input type='checkbox' name='cuota_cobrar[{$x}]' value='{$x}' />
			$ <input type='text' id='cuotas{$x}' name='cuotas[{$x}]' onKeyup='calculateSum();' class='sumar digits cuota readonly' value='{$cuota}' style='width:50px;' readonly>
			$ <input type='text' id='tot_cuotas{$x}' name='cuotas_total[{$x}]' value='".($cuota*$cant)."' style='width:50px;' readonly />
			<input type='hidden' id='ccuotas{$x}' name='cuotas2[]' value='{$cuota}' class='sumar2' />
			<br/>";

		endforeach;
		die();
	break;
/************************/

	case 'libros':

		$academy	= $_REQUEST['academy'];
		$curso		= $_REQUEST['curso'];
		$cant			= $_REQUEST['cant'];

		$libro = $H_DB->GetRow("SELECT id, name, valor FROM h_libros WHERE modelid={$curso};");

		echo "<input type='text' name='libro' style='width:100px;' value='".$libro['name']."' disabled />";
		echo "$ <input type='text' id='cuotas0' class='sumar readonly' name='cuotas[0]' onKeyup='calculateSum();' style='width:50px;' value='".$libro['valor']."' readonly />";
		echo "<input type='hidden' name='libroid' value='".$libro['id']."' />";
		echo "$ <input type='text' id='tot_cuotas0' name='cuotas_total[0]' value='".($libro['valor']*$cant)."' style='width:50px;' readonly />";
		echo "<input type='checkbox' id='libro_sel' name='libro_sel' value='1' />";
		die();
	break;
/************************/

	case 'cobrar':

		$id = $_REQUEST['id'];
		$grupo = $_REQUEST['grupo'];


		if($id>0){
			$comp = $H_DB->GetRow("SELECT * FROM h_comprobantes WHERE id={$id};");

			$comp['pendiente'] = 0;
			$comp['takenby'] = $H_USER->get_property('id');

			$H_DB->update("h_comprobantes",$comp,"id={$id}");

			// Registrar actividad
			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	=  $H_DB->GetOne("SELECT userid FROM h_comprobantes WHERE id = {$id}");
			$activity['typeid'] 		= 4;
			$activity['statusid'] 	= 7;
			$activity['subject'] 		= "Comprobante cobrado: ".$comp['puntodeventa'].'-'.sprintf("%08d",$comp['numero']);
			$activity['summary'] 		= "El comprobante ha sido cobrado.";
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$H_DB->insert("h_activity",$activity);
		}

		redireccionar("grupos.php?v=view&id=".$grupo);

		break;


	case 'anular':

		$id = $_REQUEST['id'];
		$grupo = $_REQUEST['grupo'];

		if($id>0){
			$H_DB->update("h_comprobantes",array('anulada' => 1),"id={$id}");

			// Actualizar cuotas
			$cuotas = $H_DB->GetAll("SELECT c.id, cc.importe, c.valor_pagado
															 FROM h_cuotas c INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
															 WHERE cc.comprobanteid={$id};");

			foreach($cuotas as $cuota){
				$H_DB->update("h_cuotas", array("valor_pagado" => ($cuota['valor_pagado']-$cuota['importe'])), "id={$cuota['id']}");
				$cuotitas=$H_DB->GetOne("SELECT CONCAT(cuota,' ',valor_cuota) FROM h_cuotas WHERE id={$cuota['id']}");
				$cuotaacti.=" cuota N°".$cuotitas." pesos";
			}

			// Eliminar asociacion a cuotas
			$H_DB->delete("h_comprobantes_cuotas", "comprobanteid={$id}", null);
		}
		$comprobante=$H_DB->GetOne("SELECT numero FROM h_comprobantes WHERE id = {$id}");
		/*
		$activity['userid'] 		= $H_USER->get_property('id');
		$activity['contactid']	=  $H_DB->GetOne("SELECT userid FROM h_comprobantes WHERE id = {$id}");
		$activity['typeid'] 		= 4;
		$activity['statusid'] 	= 7;
		$activity['subject'] 		= "Se anuló El comprobante° ".$comprobante;
		$alumno= $LMS->GetOne("SELECT CONCAT(lastname,' ',firstname) FROM mdl_user  WHERE id = {$activity['contactid']}");
		$editor= $LMS->GetOne("SELECT CONCAT(lastname,' ',firstname) FROM mdl_user  WHERE id = {$H_USER->get_property('id')}");
		$activity['summary'] 		= $editor." le anuló el comprobante de pago a ".$alumno."  y ahora debe por cuota: ".$cuotaacti;
		$activity['startdate'] 	= time();
		$activity['enddate']	 	= time();
		$H_DB->insert("h_activity",$activity);
		*/
		redireccionar("grupos.php?v=view&id=".$grupo);

		break;


	case 'edit':

		$H_USER->require_capability('grupos/edit');
		$grupoid=$_REQUEST['grupoid'];
		if($grupoid){
			$data['grupo'] = $H_DB->select("h_grupos",$grupoid);
		}else{
			show_error("Debe seleccionar una empresa.");die();
		}

		if(isset($_POST['guardar'])){
			if($H_DB->record_exists_sql("SELECT name FROM h_grupos WHERE name = '{$_POST['name']}' AND id != {$grupoid}")){
				show_error("Error","Ya existe una empresa con ese nombre."); die();
			}
			
			if($H_DB->record_exists_sql("SELECT email FROM h_grupos WHERE email = '{$_POST['email']}' AND id != {$grupoid}")){
				show_error("Error","Ya existe una empresa con esa direcci&oacute;n de email.");	die();
			}
			if($H_DB->record_exists_sql("SELECT cuit FROM h_grupos	WHERE cuit = '{$_POST['cuit']}'	AND id != {$grupoid}")){
				show_error("Error","Ya existe una empresa con ese Cuit."); die();
			}

			$from_data['name']		=$_POST['name'];
			$from_data['summary']	=$_POST['summary'];
			$from_data['country']	=$_POST['country'];
			$from_data['city']		=$_POST['city'];
			$from_data['address']	=$_POST['address'];
			$from_data['cp']		=$_POST['cp'];
			$from_data['phone']		=$_POST['phone'];
			$from_data['email']		=$_POST['email'];
			$from_data['cuit']		=$_POST['cuit'];
			$from_data['iva']		=$_POST['iva'];

			if(preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $_POST['startdate'])){
				$fnac = explode("-",$_POST['startdate']);
				$from_data['startdate']	= mktime(0,0,0,$fnac[1],$fnac[0],$fnac[2]);
			}

			$H_DB->update("h_grupos",$from_data,"id = ".$grupoid);
			header("Location: grupos.php?v=view&id={$grupoid}");
		}

		break;
	case 'cobros':
		$grupoid = $_REQUEST['id'];

		$grupo = $H_DB->getRow("SELECT * FROM h_grupos WHERE id={$grupoid}");

		$menuroot['ruta'] = array("Empresas"=>"grupos.php?v=list",$grupo['name']=>"grupos.php?v=view&id={$grupoid}","Generar comprobante"=>"#");

		if($_POST['grupoid']>0){
			$_POST['date']=time();			
			$_POST['takenby']=$H_USER->get_property('id');
			
			if ($_POST['tipo']==1){ $_POST['puntodeventa']="0001";}	
			
			@$comprobanteid = $H_DB->insert("h_comprobantes",$_POST);
			redireccionar("grupos.php?v=view&id=".$grupoid);
		}

	break;

	case 'cancelar':

		$id = $_REQUEST['id'];
		$grupo = $_REQUEST['grupo'];

		$comp = $H_DB->GetRow("SELECT * FROM h_comprobantes WHERE id={$id};");

		// Genero la nota de crédito
		$nc->userid		= $comp['userid'];
		$nc->grupoid 	= $comp['grupoid'];
		$nc->date			= time();
		$nc->importe	= $comp['importe'];
		$nc->detalle	= "Para cancelar ".$HULK->tipos[$comp['tipo']]." Nro. ".sprintf("%08d", $comp['numero']).".";
		$nc->concepto	= 1;
		$nc->tipo			= 3;
		$nc->takenby	= $H_USER->get_property('id');


		if(!$nc->id = $H_DB->insert("h_comprobantes",$nc)){
			show_error("Pagos", "Error al insertar en la base de datos");
		}

		// Cancelo el comprobante con la nota de crédito
		$H_DB->update("h_comprobantes",array('cancel' => $nc->id),"id={$id}");

		// Actualizar cuotas
		$cuotas = $H_DB->GetAll("SELECT c.id, cc.importe, c.valor_pagado
														 FROM h_cuotas c INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
														 WHERE cc.comprobanteid={$id};");

		foreach($cuotas as $cuota){
			$H_DB->update("h_cuotas", array("valor_pagado" => ($cuota['valor_pagado']-$cuota['importe'])), "id={$cuota['id']}");
			$cuotitas=$H_DB->GetOne("SELECT CONCAT(cuota,' ',valor_cuota) FROM h_cuotas WHERE id={$cuota['id']}");
			$cuotaacti.=" cuota ".$cuotitas." pesos";
		}


		// Eliminar asociacion a cuotas
		$H_DB->delete("h_comprobantes_cuotas", "comprobanteid={$id}", null);

		$comprobante=$H_DB->GetOne("SELECT numero FROM h_comprobantes WHERE id = {$id}");
		/*
		$activity['userid'] 		= $H_USER->get_property('id');
		$activity['contactid']	=  $H_DB->GetOne("SELECT userid FROM h_comprobantes WHERE id = {$id}");
		$activity['typeid'] 		= 4;
		$activity['statusid'] 	= 7;
		$activity['subject'] 		= "Se cancelo El comprobante° ".$comprobante;
		$alumno= $LMS->GetOne("SELECT CONCAT(lastname,' ',firstname) FROM mdl_user  WHERE id = {$activity['contactid']}");
		$editor= $LMS->GetOne("SELECT CONCAT(lastname,' ',firstname) FROM mdl_user  WHERE id = {$H_USER->get_property('id')}");
		$activity['summary'] 		= $editor." le canceló el comprobante de pago a ".$alumno."  y ahora debe por cuota: ".$cuotaacti;
		$activity['startdate'] 	= time();
		$activity['enddate']	 	= time();
		$H_DB->insert("h_activity",$activity);
		*/
		redireccionar("grupos.php?v=view&id=".$grupo);

		break;


	default:

		$v = 'list';

		$WHERE = "WHERE g.delete=0 ";

		if($_GET['startdate']){
			$data['qstartdate'] = strtotime(date($_GET['startdate']));
			$WHERE .= " AND g.startdate >= {$data['qstartdate']}";
		}else{
			$data['qstartdate']	= $H_DB->GetOne("SELECT MIN(g.startdate) FROM h_grupos g;");
		}
		if($_GET['enddate']){
			$data['qenddate'] = strtotime(date($_GET['enddate']));
			$enddate = $data['qenddate']+86400;
			$WHERE .=" AND g.startdate <= {$enddate}";
		}else{
			$data['qenddate']	= $H_DB->GetOne("SELECT MAX(g.startdate) FROM h_grupos g;");
		}


		$menuroot['ruta'] = array("Empresas"=>"grupos.php","Listado"=>"#");
		$data['grupos']		= $H_DB->GetAll("SELECT * FROM h_grupos g {$WHERE} ORDER BY startdate DESC;");


		break;
}

$view->Load('header',$data);
$view->Load('menu',$data);
$view->Load('menuroot',$menuroot);
$view->Load('grupos/'.$v,$data);
$view->Load('footer');
?>
