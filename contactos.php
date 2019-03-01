<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'nuevo_usuario':

			if ($_POST){
				if(preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $_POST['fnacimiento'])){
					$fnac = explode("-",$_POST['fnacimiento']);
					$form_data->fnacimiento	= mktime(0,0,0,$fnac[1],$fnac[0],$fnac[2]);
				}else{
				//	show_error("Error","Debe ingresar una fecha de nacimiento.");
				//	die();
				}

				$form_data->username	= $_POST['username'];
				$form_data->firstname	= utf8_decode($_POST['firstname']);
				$form_data->lastname	= utf8_decode($_POST['lastname']);
				$form_data->email		= $_POST['email'];
				$form_data->address		= $_POST['address'];
				$form_data->city		= $_POST['city'];
				$form_data->phone1		= $_POST['phone1'];
				$form_data->phone2		= $_POST['phone2'];
				$form_data->country		= $_POST['country'];
				$form_data->sexo 		= $_POST['sexo'];
				$form_data->obs 		= $_POST['obs'];
				$form_data->noquierospam= $_POST['noquierospam'];
				$form_data->cp	 		= $_POST['cp'];

				if($LMS->record_exists('mdl_user',$form_data->username,'username')){
					show_error("Error","Ya existe un usuario con el mismo DNI en la base de datos. <br/><a href='contactos.php?v=list&q={$form_data->username}'>contactos.php?v=list&q={$form_data->username}</a>");
					die();
				}
				if($LMS->record_exists('mdl_user',$form_data->email,'email')){
					show_error("Error","Ya existe un usuario con el mismo correo electronico en la base de datos. <br/><a href='contactos.php?v=list&q={$form_data->email}'>contactos.php?v=list&q={$form_data->email}</a>");
					die();
				}
				$newpassword	=	$H_USER->randomPass();
				$form_data->password	= md5($newpassword);
				if(!$id = $LMS->insertUser($form_data)){
					show_error("Error","Error al crear el usuario");
				}
				if($_REQUEST['origen_usuario']){
					$user_profile['userid'] = $id;
					$user_profile['origen'] = $_REQUEST['origen_usuario'];
					if(!$H_DB->insert('h_user_profile',$user_profile)){
					}
				}
				redireccionar("contactos.php?v=view&id={$id}");
			}
			$data['origen_usuario'] = explode(",",$HULK->origen_usuario);
		break;
	case 'actualizar_usuario':
		$id = $_REQUEST['id'];
		if ($_POST){
			$form_data = new stdClass();
			if(preg_match("/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/", $_POST['fnacimiento'])){
				$fnac = explode("-",$_POST['fnacimiento']);
				$form_data->fnacimiento	= mktime(0,0,0,$fnac[1],$fnac[0],$fnac[2]);
			}

			$form_data->username	= $_POST['username'];
			$form_data->firstname	= utf8_decode($_POST['firstname']);
			$form_data->lastname	= utf8_decode($_POST['lastname']);
			$form_data->email		= $_POST['email'];
			$form_data->address		= $_POST['address'];
			$form_data->city		= $_POST['city'];
			$form_data->phone1		= $_POST['phone1'];
			$form_data->phone2		= $_POST['phone2'];
			$form_data->country		= $_POST['country'];
			$form_data->sexo 		= $_POST['sexo'];
			$form_data->obs 		= $_POST['obs'];
			$form_data->noquierospam= $_POST['noquierospam'];
			$form_data->cp	 		= $_POST['cp'];
			if(!$LMS->update('mdl_user',$form_data,"id = {$id}")){
				show_error("Error","Error al actualizar el usuario");
			}
			if($H_USER->has_capability('role/edit')){
				if(isset($_POST['role'])){
					$role_assignments['roleid']=$_POST['role'];
					$role_assignments['userid']=$id;
					$role_assignments['academyid']=0;
					if ($_POST['role']>0){
						if($H_DB->record_exists_sql("SELECT id FROM h_role_assignments WHERE userid={$id} AND academyid={$role_assignments['academyid']} LIMIT 1;")){
							if(!$H_DB->update('h_role_assignments',$role_assignments,"userid = {$id} AND academyid={$role_assignments['academyid']} ")){
								show_error("Error","Error al actualizar el role del usuario");
							}
						}else{
							if(!$H_DB->insert('h_role_assignments',$role_assignments)){
								show_error("Error","Error al actualizar el role del usuario");
							}
						}
					}else{
						if($H_DB->record_exists_sql("SELECT id FROM h_role_assignments WHERE userid={$id} AND academyid={$role_assignments['academyid']} LIMIT 1;")){
							if(!$H_DB->delete('h_role_assignments',"userid = {$id} AND academyid={$role_assignments['academyid']} ")){
								show_error("Error","Error al borrar el role del usuario");
							}
						}
					}
				}
			}
			redireccionar("contactos.php?v=view&id={$id}");
		}
		break;
	case 'inscribir_usuario':

		$data['id'] = $id = $_REQUEST['id'];
		$data['row']	= $LMS->GetRow("SELECT * FROM mdl_user WHERE id={$id};");
		$menuroot['ruta'] = array("{$data['row']['lastname']}, {$data['row']['firstname']}"=>"contactos.php?v=view&id={$data['row']['id']}","Inscribir"=>"#");

		$data['academys']	= $LMS->getAcademys();

		$cuotas	= $H_DB->GetAll("SELECT DISTINCT c.*
								 FROM h_cuotas c
								 WHERE c.userid={$id}
								 ORDER BY c.courseid, c.periodo, c.cuota;");
		foreach($cuotas as $cuota):
			$pendiente = 0;
			$pendiente = $H_DB->GetOne("SELECT SUM(cc.importe)
										FROM h_comprobantes_cuotas cc
										INNER JOIN h_comprobantes c ON cc.comprobanteid=c.id
										WHERE cc.cuotaid={$cuota['id']} AND c.pendiente=1;");
			if($cuota['cuota'] > 0){
				$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota['insc_id']);
				if($beca>0){
					$cuota['valor_cuota'] = ceil($cuota['valor_cuota'] - ($cuota['valor_cuota']*$beca/100));
				}
			}
			$data['cuotas'][$cuota['insc_id']]['id'] = $cuota['courseid'];
			$data['cuotas'][$cuota['insc_id']]['cuota'.$cuota['cuota']] = $cuota['valor_cuota']-($cuota['valor_pagado']-$pendiente);
			$data['cuotas'][$cuota['insc_id']]['periodo'] = $periodo;
			// Verifico si está dado de baja
			$data['cuotas'][$cuota['insc_id']]['baja'] = $H_DB->GetOne("SELECT id FROM h_bajas
																		WHERE insc_id={$cuota['insc_id']}
																		AND cancel=0;");
		endforeach;
		$data['cursos'] =	$LMS->getUserCourse($id);

		$data['grupos']	= $H_DB->GetAll("SELECT g.id,g.name FROM h_grupos g
											INNER JOIN h_grupos_users gu ON g.id=gu.grupoid WHERE gu.userid={$id};");
		$data['grupos_otros']	= $H_DB->GetAll("SELECT g.id,g.name FROM h_grupos g	WHERE g.delete=0 ORDER BY g.name;");

		$data['grades'][0]='E';		$data['grades_class'][0]='';
		$data['grades'][1]='I';		$data['grades_class'][1]='ui-state-error';
		$data['grades'][2]='F';		$data['grades_class'][2]='ui-state-error';
		$data['grades'][3]='P';		$data['grades_class'][3]='ui-state-highlight';

		break;
	case 'inscribir_usuario_proceso':

				if($_POST['comi']>0){
					$data['id'] = $id = $_REQUEST['id'];
					$comision 	= $_POST['comi'];
					$modelo 		= $_POST['curso'];
					$cuotas 		= $_POST['cuotas'];
					$cuotas2		= $_POST['cuotas2'];
					$importe		=	$importe2 = $_POST['pago'];
					$concepto		=	$_POST['concepto'];
					$nrocheque 	= $_POST['nrocheque'];
					$libroid 		= $_POST['libroid'];
					$tipo				= $_POST['tipo'];

					if(!$_POST['pendiente']){	$pendiente = 0;	}else{	$pendiente = $_POST['pendiente'];	}
					if(!$_POST['becado']){ $becado = 0;	}else{ $becado = $_POST['porc_beca']; }
					if(!$_POST['descuento']){ $descuento = 0;	}else{ $descuento = $_POST['porc_desc'];	}

					$comprobante->numero = 0;
					$cuotas_default = $H_DB->getCuotasDefault($comision);
					$p_especial = $H_DB->compararCuotas($cuotas,$cuotas_default);

					//TODO: Si no pago nada lo registro y envio mail
					if($importe==0 && $becado<100){

						// Registro la actividad a nombre de administracion como alerta
						$activity['subject'] 		= "Inscripción sin cobrar";
						$activity['summary'] 		= "El usuario <a href='http://www.proydesa.org/crm/contactos.php?v=view&id={$H_USER->get_property('id')}'>{$LMS->GetField('mdl_user', 'CONCAT(firstname, " ", lastname)', $H_USER->get_property('id'))}</a>
														inscribió sin cobrar al contacto
														<a href='http://www.proydesa.org/crm/contactos.php?v=view&id={$id}'>{$LMS->GetField('mdl_user', 'CONCAT(firstname, " ", lastname, " (DNI: ", username, ")")', $id)}</a>
														en la comisión {$LMS->GetField('mdl_course','shortname',$comision)}.";
						$mail = new H_Mail();
						$mail->Send("CRM: ".$activity['subject'],$activity['summary'],"administracion@proydesa.org");

						$H_DB->setActivityLog(32730,$activity['subject'],$activity['summary']);
					}
					if(!$LMS->enrolUser($id, $comision, 5) ){
						show_error("Error","Error al enrolar el usuario");
					}

					$insc_id = $H_DB->registrarInscripcion($id,$comision, array('becado'=>$becado,'descuento'=>$descuento));
					$H_DB->asociarGrupo($id,$_POST['grupoid']);

					// Creo recibo de pago
					if($importe>0){
						$comprobante->userid		= $id;
						$comprobante->grupoid		= $_POST['grupoid'];
						$comprobante->date			= time();
						$comprobante->importe		= $importe;
						$comprobante->concepto	= $concepto;
						$comprobante->tipo			= $tipo;			
						$comprobante->detalle		= $_POST['detalle'];
						$comprobante->takenby 	= $H_USER->get_property('id');
						$comprobante->nrocheque = $nrocheque;
						$comprobante->pendiente = $pendiente;

						if ($tipo==1){ $comprobante->puntodeventa="0001";}	

						if(!$comprobante->id = $H_DB->insert("h_comprobantes",$comprobante)){
							show_error("Pagos","Error al insertar en la base de datos");
						}

						// Actualizo el campo saldo (si hubiere)
						if($_POST['saldo']>0){
							if($importe <= $_POST['saldo']){
								$LMS->update("mdl_user", array("saldo" => $_POST['saldo']-$importe), "id={$id}");
							}else{
								$LMS->update("mdl_user", array("saldo" => 0), "id={$id}");
							}
						}
					}

					if($importe==0 && $becado==100){
						//Es un alumno becado al 100%
						//Creo un comprobante por 0 pesos y las cuotas y las pongo como pagas

						$comprobante->userid		= $id;
						$comprobante->grupoid		= $_POST['grupoid'];
						$comprobante->date			= time();
						$comprobante->importe		= 0;
						$comprobante->concepto	= $concepto;
						$comprobante->tipo			= $tipo;
						$comprobante->detalle		= "";
						$comprobante->takenby 	= $H_USER->get_property('id');
						$comprobante->nrocheque = $nrocheque;
						$comprobante->pendiente = $pendiente;
						if ($tipo==1){ $comprobante->puntodeventa="0001";}	

						if(!$comprobante->id = $H_DB->insert("h_comprobantes",$comprobante)){
							show_error("Pagos","Error al insertar en la base de datos");
						}

						if($cuotas2){
							// Crear cobrar cuota
							foreach($cuotas2 as $indice=>$cuota){
								//if($cuota>0){
									unset($c_insert);
									$c_insert->userid				= $id;
									$c_insert->courseid			= $modelo;
									$c_insert->cuota				= $indice;
									$c_insert->valor_cuota	= $cuota;
									$c_insert->grupoid			= $_POST['grupoid'];
									$c_insert->insc_id			= $insc_id;
									// Si es plan especial
									if($p_especial){
										$c_insert->p_especial = 1;
									}else{
										$c_insert->p_especial = 0;
									}
									$c_insert->valor_pagado	= $cuota;
									$c_insert->date					= time();
									$c_insert->takenby			= $H_USER->get_property('id');
								//	$c_insert->periodo			= $HULK->periodo_insc;
									$c_insert->periodo			= $LMS->GetField("mdl_course","periodo",$comision);

									if(!$c_insert->id = $H_DB->insert("h_cuotas",$c_insert)){
										show_error("Cuotas","Error al insertar en la base de datos");
									}
										if(!$H_DB->insert("h_comprobantes_cuotas",array('comprobanteid'=>$comprobante->id,'cuotaid'=>$c_insert->id,'importe'=>$c_insert->valor_pagado))){
											show_error("Cuotas","Error al insertar h_comprobantes_cuotas en la base de datos");
										}

								//}
							}
						}
					}

					if($becado > 0 && $becado < 100){

						if($cuotas){
							// Crear cobrar cuota
							foreach($cuotas as $indice=>$cuota){
								if($cuota>0){
									unset($c_insert);
									$c_insert->userid				= $id;
									$c_insert->courseid			= $modelo;
									$c_insert->cuota				= $indice;
									$c_insert->valor_cuota	= $cuota;
									$c_insert->grupoid			= $_POST['grupoid'];
									$c_insert->insc_id			= $insc_id;
									// Si es plan especial
									if($p_especial){
										$c_insert->p_especial = 1;
									}else{
										$c_insert->p_especial = 0;
									}

									if($indice>0){

										$cuota = ceil($cuota - ($cuota*$becado/100));

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

									// Si no tiene valor entonces ese curso no tiene libro asociado.
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

							$view->Load('header',$data);
							if($importe2>0){
								$param = "&t=insc";
								if($newpassword){
									$param .= "&newpass={$newpassword}";
								}
								$view->js("window.open('contactos.php?v=pagos-print&id={$comprobante->id}{$param}','imprimir','width=600,height=500,scrollbars=NO');");
							}
							$view->js("window.location='contactos.php?v=pagos&id={$id}';");
							$view->Load('footer');
							die();
						}

					}else{

						if($cuotas2){
							// Crear cobrar cuota
							foreach($cuotas2 as $indice=>$cuota){
								if($cuota>0){
									unset($c_insert);
									$c_insert->userid				= $id;
									$c_insert->courseid			= $modelo;
									$c_insert->cuota				= $indice;
									$c_insert->valor_cuota	= $cuota;
									$c_insert->grupoid			= $_POST['grupoid'];
									$c_insert->insc_id			= $insc_id;
									// Si es plan especial
									if($p_especial){
										$c_insert->p_especial = 1;
									}else{
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

									// Si no tiene valor entonces ese curso no tiene libro asociado.
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

							$view->Load('header',$data);
							if($importe2>0){
								$param = "&t=insc";
								if($newpassword){
									$param .= "&newpass={$newpassword}";
								}
								$view->js("window.open('contactos.php?v=pagos-print&id={$comprobante->id}{$param}','imprimir','width=600,height=500,scrollbars=NO');");
							}
							$view->js("window.location='contactos.php?v=pagos&id={$id}';");
							$view->Load('footer');
							die();
						}

					}
				}
				header("Location: contactos.php?v=view&id={$id}");
				die();
		break;
	case 'comision_change':

		$H_USER->require_capability('course/change');

		@set_time_limit(3600); // 1 hour
		@ini_set('memory_limit', '128M');

		$id = $_REQUEST['id'];
		$data['comi_old'] = $c = $_REQUEST['c'];
		$data['stage'] = $stage = $_REQUEST['stage'];
		$data['insc_id'] = $insc_id = $_REQUEST['insc_id'];

		switch($stage){
			case 0:

				// Curso modelo del cual cambiamos el alumno
				$data['from_course'] = $LMS->GetField("mdl_course","from_courseid",$c);

				// Sólo puede cambiarse a un curso del mismo periodo del que está
				$periodo = $LMS->GetField("mdl_course","periodo",$c);

				// Cursos modelos que tienen las academias que tienen compartido el usuario del periodo actual, donde no este enrolado el alumno
				$data['modelos'] = $LMS->GetAll("SELECT DISTINCT c.id, c.fullname
																				 FROM mdl_course c, mdl_proy_academy_convenio ac
																				 WHERE c.academyid = 0
																				 AND ((ac.enddate =0) OR (ac.enddate >= '.time().' ))
																				 AND c.id IN (SELECT DISTINCT c1.from_courseid
																				 							FROM mdl_course c1 WHERE c1.periodo = {$periodo})
																				 ORDER BY fullname;");

				// Datos del alumno
				$data['row']	= $LMS->GetRow("SELECT * FROM mdl_user WHERE id={$id};");

				$menuroot['ruta'] = array("{$data['row']['lastname']}, {$data['row']['firstname']}"=>"contactos.php?v=view&id={$data['row']['id']}","Cambio de comisión"=>"#");

				//Historico de cuotas
				$cuotas	= $H_DB->GetAll("SELECT DISTINCT c.* FROM h_cuotas c	WHERE c.userid={$id} ORDER BY c.courseid, c.periodo, c.cuota;");

				foreach($cuotas as $cuota):
					$courseid = $cuota['courseid'];
					$periodo  = $cuota['periodo'];

					if($cuota['cuota'] > 0){
						$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota['insc_id']);
						if($beca>0){
							$cuota['valor_cuota'] = ceil($cuota['valor_cuota'] - ($cuota['valor_cuota']*$beca/100));
						}
					}

					$data['cuotas'][$courseid.$periodo]['id'] = $cuota['courseid'];
					$data['cuotas'][$courseid.$periodo]['cuota'.$cuota['cuota']] = $cuota['valor_cuota']-$cuota['valor_pagado'];
					$data['cuotas'][$courseid.$periodo]['periodo'] = $periodo;

//					if ($courseid == $data['from_course'] && $periodo == $HULK->periodo_insc){
					if ($courseid == $data['from_course'] && $periodo == $HULK->periodo){
						$data['cuotas_actual']['id'] = $cuota['courseid'];
						$data['cuotas_actual']['cuota'.$cuota['cuota']] = $cuota['valor_cuota'];
						$data['cuotas_actual']['cuotap'.$cuota['cuota']] = $cuota['valor_pagado'];
						$data['cuotas_actual']['periodo'] = $periodo;
					}

				endforeach;

				//Historico de cursos
				$data['cursos']		= $LMS->GetAll("SELECT c.id, c.periodo, a.name AS Academia,
																					(SELECT CONCAT(u1.lastname, ', ', u1.firstname) AS Nombre
																					 FROM mdl_role_assignments ra1
													 								 INNER JOIN mdl_user u1 ON ra1.userid = u1.id
																					 INNER JOIN mdl_context ctx1 ON ra1.contextid = ctx1.id
																					 WHERE ra1.roleid = 4 AND ctx1.instanceid = c.id
																					 LIMIT 1) AS Instructor,
																					c.shortname AS Comision, cm.shortname AS Course,
																					(SELECT gg.finalgrade
																					 FROM mdl_grade_items gi
							 														 INNER JOIN mdl_grade_grades gg ON gi.id=gg.itemid
							 														 WHERE gi.itemname LIKE '%Graduaci%' AND gg.userid={$id}
							 														 AND gi.courseid=c.id) AS Status,
																					(SELECT MAX(gg.finalgrade)
							 														 FROM mdl_grade_items gi
								 													 INNER JOIN mdl_grade_grades gg ON gi.id=gg.itemid
							 														 WHERE gi.itemname LIKE '%Final T%' AND gg.userid={$id}
							 														 AND gi.courseid=c.id) AS Final
																					FROM mdl_course c INNER JOIN mdl_proy_academy a ON c.academyid = a.id
																					INNER JOIN mdl_course cm ON c.from_courseid = cm.id
																					INNER JOIN mdl_context ctx ON c.id=ctx.instanceid
																					INNER JOIN mdl_role_assignments ra ON ctx.id=ra.contextid
																					WHERE ra.userid={$id} AND ra.roleid=5;");

				$data['grades'][0]='E';		$data['grades_class'][0]='';
				$data['grades'][1]='I';		$data['grades_class'][1]='ui-state-error';
				$data['grades'][2]='F';		$data['grades_class'][2]='ui-state-error';
				$data['grades'][3]='P';		$data['grades_class'][3]='ui-state-highlight';

				$data['comprobantes'] = $H_DB->GetAll("SELECT DISTINCT comp.*
																							 FROM h_comprobantes comp
																							 INNER JOIN h_comprobantes_cuotas cc ON comp.id=cc.comprobanteid
																							 INNER JOIN h_cuotas cuotas ON cc.cuotaid=cuotas.id
																							 WHERE comp.userid={$id} AND cuotas.periodo IN ({$HULK->periodo_insc})
																							 AND cuotas.courseid={$data['from_course']};");

				break;

			case 1:

				$comi_new 		= $_POST['comi'];
				$comi_old 		= $_POST['comi_old'];
				$modelo_old 	= $LMS->GetField('mdl_course','from_courseid',$comi_old);
				$modelo 		= $_POST['curso'];
				$cuotas2 		= $_POST['cuotas'];
				$importe		= $_POST['pago'];
				$concepto		= $_POST['concepto'];
				$nrocheque 		= $_POST['nrocheque'];
				$libroid 		= $_POST['libroid'];
				$tipo			= $_POST['tipo'];
				$total			= $_POST['total'];
				$comprobantes 	= $_POST['comprobantes'];
				$insc_id 		= $_POST['insc_id'];

				if ($comi_new>0){
					if ($_POST['opcion']=='I-P'){
						// Lo pongo en I en el gradebook en la comisión vieja y registro la baja
						$LMS->user_status($id,$comi_old,'I');
						$H_DB->registrarBaja($id,$comi_old,array('detalle'=>'Baja automática por cambio de comisión','insc_id'=>$insc_id));
					}elseif($_POST['opcion']=='D-P'){
						// Lo desenrolo de la comision vieja
						$LMS->unenrolUser($id, $comi_old, 5);
						$H_DB->registrarBaja($id,$comi_old,array('detalle'=>'Baja automática por cambio de comisión s/p','insc_id'=>$insc_id));
					}

					// Cancelo baja por si el usuario ya estuvo en esa comision nueva y esta como baja
					$H_DB->cancelarBaja($id,$comi_new);

					// Lo enrolo en la comisión nueva
					if(!$LMS->enrolUser($id, $comi_new, 5) ){
						show_error("Error","Error al enrolar el usuario");
					}

					$H_DB->setActivityLog($id,"Cambio de comisión","Cambio de comisión de {$LMS->GetField('mdl_course','shortname',$comi_old)} a {$LMS->GetField('mdl_course','shortname',$comi_new)} (Metodo: {$_POST['opcion']})");

					$new_insc_id = $H_DB->registrarInscripcion($id,$comi_new);

					// Si es sólo un cambio de comisión no genero ningún comprobante
					if($modelo_old == $modelo){

						// Chequeo si cambio de curso normal a intensivo o viceversa
						if($LMS->GetField("mdl_course", "intensivo", $comi_old) != $LMS->GetField("mdl_course", "intensivo", $comi_new)){

							// Borro las cuotas del curso viejo
							$cuotas_old = $H_DB->GetAll("SELECT id FROM h_cuotas WHERE insc_id={$insc_id};");

							foreach($cuotas_old as $cuota):
								$H_DB->delete("h_comprobantes_cuotas","cuotaid={$cuota['id']}");
								$H_DB->delete("h_cuotas","id={$cuota['id']}");
							endforeach;

							//TODO: crear cobrar cuota
							foreach ($cuotas2 as $indice=>$cuota){
								if($cuota>0){
									unset($c_insert);
									$c_insert->userid		= $id;
									$c_insert->courseid		= $modelo;
									$c_insert->cuota		= $indice;
									$c_insert->valor_cuota	= $cuota;

									if($indice>0){
										if($importe>=$cuota){
											$c_insert->valor_pagado	= $cuota;
											$importe = $importe-$cuota;
										}elseif($importe<$cuota){
											$c_insert->valor_pagado	= $importe;
											$importe = 0;
										}
									}else{
										$c_insert->libroid	= $libroid;
									}
									$c_insert->date			= time();
									$c_insert->takenby		= $H_USER->get_property('id');
									$c_insert->periodo		= $LMS->GetField("mdl_course", "periodo", $comi_new);
									$c_insert->insc_id		= $new_insc_id;

									if(!$c_insert->id = $H_DB->insert("h_cuotas",$c_insert)){
										show_error("Cuotas","Error al insertar en la base de datos");
									}
									if($c_insert->valor_pagado>0){
										if(!$H_DB->insert("h_comprobantes_cuotas",array('comprobanteid'=>$comp->id,'cuotaid'=>$c_insert->id,'importe'=>$c_insert->valor_pagado))){
											show_error("Cuotas","Error al insertar h_comprobantes_cuotas en la base de datos");
										}
									}
								} // if($cuota>0){
							} // foreach ($cuotas2 as $indice=>$cuota){
						} // Si el cambio se hace de un intensivo a regular o viceversa.
						// Actualizo la insccripción de las cuotas por la nueva
						$cuotas_old = $H_DB->GetAll("SELECT id
													FROM h_cuotas
													WHERE insc_id={$insc_id};");
						foreach($cuotas_old as $cuota):
							$H_DB->update("h_cuotas",array("insc_id" => $new_insc_id), "id={$cuota['id']}");
						endforeach;

						redireccionar("contactos.php?v=view&id={$id}");
					}

					// Todo: Ver que pasa si lo que pagó es más que lo que sale el nuevo curso
					if($importe > $total){
						// Si es mayor lo que ya pagó, se genera una NC para cancelar eso, se hace el nuevo recibo por el curso
						// y se genera otro recibo con el importe sobrante asociado a nada.
						// Cuando se le devuelve la plata al alumno, este recibo se anula.

						// Genero el recibo por la diferencia
						$importe_recibo = $importe-$total;
						$recibo_diferencia = $H_DB->generarRecibo($id,$importe_recibo,"Diferencia por cambio de curso.");

					}else{
						$total = $importe;
					}

					if ($importe>0){

						//Creo nota de credito para el recibo de pago anterior
						$notacreditoid = $H_DB->generarNotaDeCredito($id,$total,"Nota de crédito por cambio de comisión.");

						$comprobanteid = $H_DB->generarRecibo($id,$total,"Comprobante por cambio de comisión",$nrocheque);
						//TODO: Recorro las cuotas y cancelo los comprobantes asociados con la nota de credito

						// Agarro las cuotas de ese curso modelo del periodo actual
						$cuotas_old	= $H_DB->GetAll("SELECT DISTINCT c.* FROM h_cuotas c WHERE c.insc_id={$insc_id};");

						foreach($cuotas_old as $cuota){

							$comp_cuotas	= $H_DB->GetAll("SELECT DISTINCT c.*
																						 FROM h_comprobantes_cuotas c
																		         WHERE c.cuotaid={$cuota['id']};");
							foreach($comp_cuotas as $comp){
								$H_DB->delete("h_comprobantes_cuotas","comprobanteid={$comp['comprobanteid']} AND cuotaid={$comp['cuotaid']}");
								$H_DB->update("h_comprobantes",array('cancel' => $notacreditoid,),"id={$comp['comprobanteid']}");
							}
							$H_DB->delete("h_cuotas","id={$cuota['id']}");
						}
					}

					//TODO: crear cobrar cuota
					foreach ($cuotas2 as $indice=>$cuota){
						if ($cuota>0){
							unset($c_insert);
							$c_insert->userid				= $id;
							$c_insert->courseid			= $modelo;
							$c_insert->cuota				= $indice;
							$c_insert->valor_cuota	= $cuota;

							if($indice>0){
								if($importe>=$cuota){
									$c_insert->valor_pagado	= $cuota;
									$importe = $importe-$cuota;
								}elseif($importe<$cuota){
									$c_insert->valor_pagado	= $importe;
									$importe = 0;
								}
							}else{
								$c_insert->libroid = $libroid;
							}
							$c_insert->date					= time();
							$c_insert->takenby			= $H_USER->get_property('id');
							$c_insert->periodo			= $LMS->GetField("mdl_course", "periodo", $comi_new);
							$c_insert->insc_id			= $insc_id;

							if(!$c_insert->id = $H_DB->insert("h_cuotas",$c_insert)){
								show_error("Cuotas","Error al insertar en la base de datos");
							}
							if($c_insert->valor_pagado>0){
								if(!$H_DB->insert("h_comprobantes_cuotas",array('comprobanteid'=>$comprobanteid,'cuotaid'=>$c_insert->id,'importe'=>$c_insert->valor_pagado))){
									show_error("Cuotas","Error al insertar h_comprobantes_cuotas en la base de datos");
								}
							}
						}
					}
				}

				$view->Load('header',$data);

				/*popup*/
				//	$view->js("window.open('contactos.php?v=pagos-print&id={$comprobanteid}','imprimir','width=600,height=500,scrollbars=NO');");
				/*redirect*/
				//	$view->js("$(location).attr('href','contactos.php?v=pagos&id={$id}');");
				//	$view->js("window.locationf='contactos.php?v=pagos&id={$id}';");
				$view->js("window.location='contactos.php?v=pagos&id={$id}';");

				$view->Load('footer');
				die();
				break;

		}

		break;
/********************/
	case 'view':

		if ($_REQUEST['id']){
			$id = $_REQUEST['id'];
			$H_USER->require_capability('contact/view');
		}else{
			$id = $H_USER->get_property('id');
		}

		$data['row'] = $LMS->getUser($id);
		$menuroot['ruta'] = array("Contactos"=>"contactos.php?v=list","{$data['row']['firstname']} {$data['row']['lastname']}"=>"contactos.php?v=view&id={$data['row']['id']}");

		// 29/08/2018 creacion de variable con url definida para los diferentes ambientes
		
		$urlLMS = '/lms_new/user/profile.php?id='.$id;
		
		if (AMBIENTE =='DESARROLLO'){
			$urlLMS = $_SERVER['REQUEST_SCHEME'].'://'.$_SERVER['SERVER_NAME'].$urlLMS;
		}elseif (AMBIENTE =='PRODUCCION'){
				$urlLMS = $_SERVER['REQUEST_SCHEME'].'://www.proydesa.org'.$urlLMS;	
		}
		$data['urlLMS']=$urlLMS;
		// show_array($data['row']);

		// Módulo de actividad
		if (isset($_REQUEST['action']) && $_REQUEST['action']=='newactivity'){

			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	= $id;
			$activity['typeid'] 		= $_POST['typeid'];
			$activity['subject'] 		= $_POST['subject'];
			$activity['summary'] 		= stripcslashes(nl2br($_POST['summary']));
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$activity['statusid'] 	= $_POST['statusid'];

			if(!$activity_file['activityid'] = $H_DB->insert("h_activity",$activity)){
				show_error("Error al actualizar el campo.");
			}

		 	$valida	=	"1";
			$extensiones	=	array("exe","php");

			if (isset($_FILES["archivos"])) {

				foreach ($_FILES["archivos"]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {

						$files['userid']	= $H_USER->get_property('id');
						$files['name']		= $_FILES['archivos']['name'][$key];
						$files['size']		= $_FILES['archivos']['size'][$key];
						$files['type']		= $_FILES['archivos']['type'][$key];
						$temp_ext 				= explode(".",$files['name']);
						$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
						$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
						$files['date']		= time();

						foreach($extensiones as $ext){
							if($ext == $files['ext']) $valida = "0";
						}
					 	if($valida=="1"){
							if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key]))
							{
								if(!is_dir($files['locate']))
									@mkdir($files['locate'], 0777);
								copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
								if(!$activity_file['fileid'] = $H_DB->insert("h_files",$files)){
									show_error("Error al insertar en h_files.");
								}else{
									if(!$fileid = $H_DB->insert("h_activity_files",$activity_file)){
										show_error("Error al insertar en h_activity_files.");
									}
								}
							}
						}
					}
				}
			}

			header("Location: {$HULK->STANDARD_SELF}");
		  exit;
		}

		$data['activity_type']			= $H_DB->GetAll("SELECT * FROM h_activity_type;");
		$data['activity_status']		= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$data['activity_priority']	= $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		//alan 22/11/2012
		$data['activitys']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename
																				 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																				 INNER JOIN h_activity_type t ON a.typeid=t.id
																				 WHERE a.contactid={$id} AND a.parent=0 AND a.typeid IN (4)
																				 AND a.subject NOT LIKE '%INSERT ->%'
																			     AND a.subject NOT LIKE '%UPDATE ->%'
																			     AND a.subject NOT LIKE '%DELETE ->%'
																				 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,5;");
		//alan 22/11/2012

		$data['notes']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename
																				 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																				 INNER JOIN h_activity_type t ON a.typeid=t.id
																				 WHERE a.contactid={$id} AND a.parent=0 AND a.typeid IN (1,2,3)
																				 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,5;");

		$data['incidentes']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename
																				 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																				 INNER JOIN h_activity_type t ON a.typeid=t.id
																				 WHERE (a.contactid={$id} OR a.userid={$id}) AND a.parent=0 AND a.typeid IN (5)
																				 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,5;");

		$data['row']['roleid'] = $H_DB->GetField("h_role_assignments","roleid",$data['row']['id'],"userid");

		$data['roles'] = $H_DB->GetAll("SELECT * FROM h_role h;");


		$data['user_roles']= $LMS->getUserRoles($id);
		$cursos =	$LMS->getUserCourse($id);
		$data['cursos_ins'] =	$LMS->getUserCourseInstructor($id);

		foreach($cursos as $curso):
			// Obtengo el insc_id
			if($curso['periodo'] != ""):
				$curso['insc_id'] = $H_DB->GetOne("SELECT id FROM h_inscripcion
													 WHERE userid={$id} AND courseid={$curso['from_courseid']} AND periodo={$curso['periodo']}
													 AND comisionid={$curso['id']};");
			endif;
			$data['cursos'][] = $curso;
		endforeach;


		$cuotas	= $H_DB->GetAll("SELECT DISTINCT c.*
														 FROM h_cuotas c
														 WHERE c.userid={$id}
														 ORDER BY c.courseid, c.periodo, c.cuota;");

		foreach($cuotas as $cuota):
			$pendiente = 0;
			$pendiente = $H_DB->GetOne("SELECT SUM(cc.importe)
																	FROM h_comprobantes_cuotas cc
																	INNER JOIN h_comprobantes c ON cc.comprobanteid=c.id
																	WHERE cc.cuotaid={$cuota['id']} AND c.pendiente=1;");

			if($cuota['cuota'] > 0){
				$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota['insc_id']);
				if($beca>0){
					$cuota['valor_cuota'] = ceil($cuota['valor_cuota'] - ($cuota['valor_cuota']*$beca/100));
				}
			}

			$data['cuotas'][$cuota['insc_id']]['id'] = $cuota['courseid'];
			$data['cuotas'][$cuota['insc_id']]['cuota'.$cuota['cuota']] = $cuota['valor_cuota']-($cuota['valor_pagado']-$pendiente);
			$data['cuotas'][$cuota['insc_id']]['periodo'] = $cuota['periodo'];

			// Verifico si está dado de baja
			$data['cuotas'][$cuota['insc_id']]['baja'] = $H_DB->GetOne("SELECT id FROM h_bajas
																		WHERE insc_id={$cuota['insc_id']}
																		AND cancel=0;");

		endforeach;

		if($data['cursos']){
			foreach($data['cursos'] as $indice => $row):
				if($row['roleid']==5){
					// Traigo todos los exámenes del curso
					$exams_curso[$row['id']] = $LMS->GetAll("SELECT id, itemname FROM mdl_grade_items
																									 WHERE itemname IS NOT NULL AND courseid={$row['id']}
																									 AND itemname != 'Asistencia' AND itemname != 'Graduación'
																									 AND itemname NOT LIKE '%eKit%'
																									 ORDER BY sortorder;");

					// Traigo las notas de los exámenes
					foreach($exams_curso[$row['id']] as $i => $exam_curso):
						$exams_curso[$row['id']][$i]['nota'] = $LMS->GetField_sql("SELECT MAX(finalgrade)
																				 FROM mdl_grade_grades
																			 WHERE itemid={$exam_curso['id']} AND userid={$id};");
					endforeach;

					// Traigo la Graduación
					$grade_curso[$row['id']]['id'] = $LMS->GetField_sql("SELECT id, itemname FROM mdl_grade_items
																	 WHERE courseid={$row['id']} AND itemname LIKE '%Graduaci%';");

					@$grade_curso[$row['id']]['nota'] = $LMS->GetField_sql("SELECT MAX(finalgrade)
																			 FROM mdl_grade_grades
																		 WHERE itemid={$grade_curso[$row['id']]['id']}
																		 AND userid={$id};");

					/*$certificados[$row['id']] = $LMS->GetRow("SELECT CONCAT(u.firstname,' ',u.lastname) AS user, cert.userid, CONCAT(mdl_user_1.firstname,' ',mdl_user_1.lastname) AS usercreator, cert.createby, c.shortname, cert.courseid, cert.timecreate, cert.id as certificated
															FROM ((mdl_certificates cert INNER JOIN mdl_course c ON cert.courseid = c.id) INNER JOIN mdl_user u ON cert.userid = u.id) INNER JOIN mdl_user AS mdl_user_1 ON cert.createby = mdl_user_1.id
															WHERE (cert.courseid = {$row['id']}) AND cert.userid={$id}
															ORDER BY cert.timecreate DESC;");
*/
					if($row['insc_id'] > 0){
						// Verifico si está dado de baja
						$data['cursos'][$indice]['baja'] = $H_DB->GetRow("SELECT id,detalle,`date`,user FROM h_bajas
																		WHERE insc_id={$row['insc_id']} AND cancel=0;");
					}
				}
			endforeach;
		}

		if($data['cursos_ins']){
		foreach($data['cursos_ins'] as $row):
			if($row['roleid']==5){
				// Traigo todos los exámenes del curso
				$exams_curso[$row['id']] = $LMS->GetAll("SELECT id, itemname FROM mdl_grade_items
								 						 WHERE itemname IS NOT NULL AND courseid={$row['id']}
														 AND itemname != 'Asistencia' AND itemname != 'Graduación'
														 AND itemname NOT LIKE '%eKit%'
													 	 ORDER BY sortorder;");

				// Traigo las notas de los exámenes
				foreach($exams_curso[$row['id']] as $i => $exam_curso):
					$exams_curso[$row['id']][$i]['nota'] = $LMS->GetField_sql("SELECT MAX(finalgrade)
																			 FROM mdl_grade_grades
																			 WHERE itemid={$exam_curso['id']} AND userid={$id}");
				endforeach;

				$certificados[$row['id']] = $LMS->GetRow("SELECT CONCAT(u.firstname,' ',u.lastname) AS user, cert.userid, CONCAT(mdl_user_1.firstname,' ',mdl_user_1.lastname) AS usercreator, cert.createby, c.shortname, cert.courseid, cert.timecreate, cert.id as certificated
					FROM ((mdl_certificates cert INNER JOIN mdl_course c ON cert.courseid = c.id) INNER JOIN mdl_user u ON cert.userid = u.id) INNER JOIN mdl_user AS mdl_user_1 ON cert.createby = mdl_user_1.id
					WHERE (cert.courseid = {$row['id']}) AND cert.userid={$id}
					ORDER BY cert.timecreate DESC;");
			}
		endforeach;
		}
		$data['row']['lastname'] = addslashes($data['row']['lastname']);
		$data['gradebooks_lms'] =  $H_DB->GetAll("SELECT * FROM h_gradebooks g WHERE g.userid={$id} ORDER BY periodo DESC, modeloname DESC;");
		$data['gradebooks_bajador'] =  $H_DB->GetAll("SELECT * FROM `historicos`.h_gradebooks_bajadorNet3 g WHERE g.username LIKE '%{$data['row']['username']}%' OR (g.firstname LIKE '{$data['row']['firstname']}' AND g.lastname LIKE '{$data['row']['lastname']}') OR g.email LIKE '{$data['row']['email']}'  ORDER BY fecha_inicio DESC, modeloname DESC;");
		$data['gradebooks_gesteval'] =  $H_DB->GetAll("SELECT * FROM `historicos`.h_gradebooks_gesteval g WHERE g.username LIKE '%{$data['row']['username']}%' OR (g.firstname LIKE '{$data['row']['firstname']}' AND g.lastname LIKE '{$data['row']['lastname']}') OR g.email LIKE '{$data['row']['email']}'  ORDER BY fecha_inicio DESC, modeloname DESC;");
		$data['gradebooks_oracle'] =  $H_DB->GetAll("SELECT * FROM `historicos`.h_gradebooks_oracle g WHERE g.username LIKE '%{$data['row']['username']}%' OR (g.firstname LIKE '{$data['row']['firstname']}' AND g.lastname LIKE '{$data['row']['lastname']}') OR g.email LIKE '{$data['row']['email']}'  ORDER BY fecha_inicio DESC, modeloname DESC;");
		$data['gradebooks_sun'] =  $H_DB->GetAll("SELECT * FROM `historicos`.h_gradebooks_sun g WHERE g.username LIKE '%{$data['row']['username']}%' OR (g.firstname LIKE '{$data['row']['firstname']}' AND g.lastname LIKE '{$data['row']['lastname']}') OR g.email LIKE '{$data['row']['email']}'  ORDER BY fecha_inicio DESC, modeloname DESC;");

		$data['grades_class'] = array('E'=>'','I'=>'ui-state-error','F'=>'ui-state-error','P'=>'ui-state-highlight');

		$data['notas']	=	$exams_curso;
		if (isset($certificados)){	$data['certificados']	=	$certificados;}
		break;

/************************/
	case 'list':
		$data['q'] = $q = utf8_decode($_REQUEST['q']);
		$H_USER->require_capability('contact/view');

		$menuroot['ruta'] = array("Listado de contactos"=>"contactos.php?v=list");

		$WHERE .= "WHERE u.deleted=0 ";

		if ($q){
			$data['filtros'] =	$search_words = explode(' ',$q);

			foreach($search_words as $word){
				$not=''; $con=' OR ';
				if (substr($word, 0, 1)=='-'){$con=' AND ';  $not=' NOT '; $word=substr($word, 1); }

				$WHERE .=" AND (username {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} lastname {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} firstname {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} acid {$not} LIKE '%{$word}%'";
				$WHERE .=" {$con} email {$not} LIKE '%{$word}%' )";
			}
		}

		// Cantidad de resultados
		$max	= $LMS->Execute("SELECT COUNT(DISTINCT u.id) as total FROM mdl_user u {$WHERE} ORDER BY u.id DESC;");
		$t		= $max->fields['total'];

		// Si no encuentra resultados va directo a buscar comisiones
		if ($t == 0){ 	header("Location: courses.php?v=list&q={$q}");	die();	}

		// Pagina
		$p = $_REQUEST['p'];

		// Resultados
		$r = $_REQUEST['r'];

		if ($r<5) $r= 25;
		if ($p<1) $p= 1;
		$totalPag = ceil($t/$r);
		if ($p>$totalPag) $p = 1;
		$inicio		= ($p-1)*$r;

		//TODO: Armo la barra de paginación, esto bien podria estar en una libreria html pero se pierde el control desde el template.
		$x2=0;
		$data['links_pages'] = "Row: {$inicio} - ".($inicio+$r)." de {$t} | Página:  ";

		if ($p > 1){  $data['links_pages'] .= "	<a href='?v={$v}&p=1&q={$q}{$academiapost}' class='button-seek-start'>&nbsp;</a>
			<a href='?v={$v}&q={$q}&p=".($p-1)."{$academiapost}' class='button-seek-prev'> Prev</a>";
			for ($x=$p-3;$x<$p;$x++){
				if ($x>0){
					$data['links_pages'] .= " <a href='?v={$v}&q={$q}&p=".($x)."{$academiapost}' class='button'>{$x}</a> ";
					$x2++;
				}
			}
		}

		$data['links_pages'] .= " <span class='ui-button ui-state-disabled'><b>{$p}</b></span> ";

		for ($x=($p+1);$x<($p+11-$x2);$x++){
			if ($x<=$totalPag){
				$data['links_pages'] .= " <a href='?v={$v}&q={$q}&p=".($x)."{$academiapost}' class='button'>{$x}</a> ";
				$x2++;
			}
		}
		if ($t > (($p*$r)+$r))  $data['links_pages'] .= "<a href='?v={$v}&q={$q}&p=".($p+1)."{$academiapost}' class='button-seek-next'>Sig </a>
								<a href='?v={$v}&q={$q}&p={$totalPag}{$academiapost}' class='button-seek-end'>&nbsp;</a>";

		$data['p']=$p;

		$LIMIT = "LIMIT {$inicio},{$r}";
		$data['rows']		= $LMS->GetAll("SELECT DISTINCT u.id, u.username, u.lastname, u.firstname, u.email, u.acid  FROM mdl_user u {$WHERE} ORDER BY u.id DESC {$LIMIT};");

		// Si es un resultado redirecciono a ver el contacto
		if ($t == 1){		header("Location: contactos.php?v=view&id={$data['rows'][0]['id']}");	die();	}

		break;

/*****************/
	case 'pagos':

		$H_USER->require_capability('contact/paid/register');

		$data['userid'] = $id = $_REQUEST['id'];

		$userdata = $LMS->getUser($id);
		$menuroot['ruta'] = array("Contactos"=>"contactos.php?v=list","{$userdata['firstname']} {$userdata['lastname']}"=>"contactos.php?v=view&id={$userdata['id']}");
		//alan
		$data['grupos']=$H_DB->GetAll("SELECT name,id FROM h_grupos g
								INNER JOIN h_grupos_users GU ON GU.grupoid=g.id
								WHERE GU.userid={$id}
								and g.id > 0;");
		if($_REQUEST['grupos']!=""){
			$data['gruposel']=$_REQUEST['grupos'];
		}else{
			$data['gruposel']=" ";
		}
		//alan

		if ($_GET['action']=='regpago'){

			//TODO: Creo recibo de pago
			$comprobante = $_POST;
			if($comprobante['importe']>0){
				$comprobante['userid']		= $id;
				$comprobante['date']			= time();
				$comprobante['takenby']		= $H_USER->get_property('id');
				if ($comprobante['tipo']==1){$comprobante['puntodeventa']="0001";}				
				if(!$comprobante['pendiente']) $comprobante['pendiente'] = 0;
				if(!$comprobante['id'] = $H_DB->insert("h_comprobantes",$comprobante)){
					show_error("Pagos","Error al insertar en la base de datos");
				}
				$cuotasid=implode(",",$comprobante['cuotas']);
				$cuotas = $H_DB->GetAll("SELECT c.* FROM h_cuotas c
																 WHERE c.id IN ({$cuotasid});");
				//TODO: cobrar cuota
				$importe=$comprobante['importe'];
				foreach ($cuotas as $cuota){
					if ($importe > 0){
						unset($c_update);

						if($cuota['cuota']>0){
							$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota['insc_id']);
							if($beca>0){
								$cuota['valor_cuota'] = $cuota['valor_cuota'] - ceil($cuota['valor_cuota']*$beca/100);
							}
						}
						
						if($importe>=($cuota['valor_cuota']-$cuota['valor_pagado'])){
							$c_update['valor_pagado']	= $cuota['valor_cuota'];
							$importe = $importe-($cuota['valor_cuota']-$cuota['valor_pagado']);
							$pagado_parcial=($cuota['valor_cuota']-$cuota['valor_pagado']);
						}elseif($importe<($cuota['valor_cuota']-$cuota['valor_pagado'])){
							$c_update['valor_pagado']	= $cuota['valor_pagado']+$importe;
							$pagado_parcial=$importe;
							$importe = 0;
						}
						//solo modifica la fecha y quien lo hizo si hay algo por pagar
						if ($cuota['valor_cuota']-$cuota['valor_pagado']!=0){
							$c_update['date']					= time();
						    $c_update['takenby']			= $H_USER->get_property('id');
						}
						if(!$H_DB->update("h_cuotas",$c_update,"id = ".$cuota['id'])){
							$errors = show_error("Cuotas","Error al actualizar la base de datos");
						}
						if ($pagado_parcial> 0){
							if(!$H_DB->insert("h_comprobantes_cuotas",array('comprobanteid'=>$comprobante['id'],'cuotaid'=>$cuota['id'],'importe'=>$pagado_parcial))){
								$errors = show_error("Cuotas","Error al insertar h_comprobantes_cuotas en la base de datos");
							}
						}
						$comprobante['grupoid'] = $cuota['grupoid'];
					}
				}
				$comprobante['importe']=$comprobante['importe']-$importe;

				// Inserto comprobante
				if(!$H_DB->update("h_comprobantes",$comprobante,"id = {$comprobante['id']}")){
					$errors = show_error("Pagos","Error al insertar en la base de datos");
				}
			}
			/*
			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	= $id;
			$activity['typeid'] 		= 4;
			$activity['statusid'] 	= 7;
			$activity['subject'] 		= "Acreditacion de pago";
			$alumno=	$LMS->GetOne("SELECT CONCAT(lastname,' ',firstname) FROM mdl_user  WHERE id = {$id}");
			$editor= $LMS->GetOne("SELECT CONCAT(firstname,' ',lastname) FROM mdl_user  WHERE id = {$H_USER->get_property('id')}");
			$activity['summary'] 		= $editor." Le acreditó un pago de $".number_format($importesum,2,',','.')." a ".$alumno;
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$H_DB->insert("h_activity",$activity);
			*/
			if ($errors==0){
				header("Location: {$HULK->STANDARD_SELF}");
		  	exit;
		  }
		}
		if ($_GET['action']=='nrocomprobante'){

			$numero = $_POST['numero'];
			$puntodeventa = $H_DB->GetField("h_comprobantes","puntodeventa",$_POST['comprobanteid']);
			$tipo = $H_DB->GetField("h_comprobantes","tipo",$_POST['comprobanteid']);
			// Valido que el número de comprobante no exista.
			if($H_DB->record_exists_sql("SELECT id FROM h_comprobantes WHERE tipo={$tipo} AND numero={$numero} AND puntodeventa='{$puntodeventa}';")){
				show_error("record_exists","N&uacute;mero de comprobante existente.");die();
			}

			if(!$H_DB->update("h_comprobantes",array(numero=>$_POST['numero']),"id = ".$_POST['comprobanteid'])){
				show_error("update","Error al actualizar h_comprobantes.");die();
			}
			header("Location: {$HULK->STANDARD_SELF}");
		  exit;

		}
		//alan
		$data['pagos'] = $H_DB->GetAll("(SELECT c.*, GROUP_CONCAT(DISTINCT comp.id SEPARATOR '-') as numero,
																		b.id AS baja_id, b.cancel AS baja_cancel, b.detalle, '-' as pendiente
																		FROM h_cuotas c
																		LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																		LEFT JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
                                    LEFT JOIN (SELECT id, cancel, detalle, courseid, userid, periodo, insc_id
																							 FROM h_bajas
																							 WHERE cancel = 0
																							 ORDER BY id DESC) b ON b.insc_id = c.insc_id
																		AND c.periodo=b.periodo
																		WHERE c.userid={$id}
																		GROUP BY c.id
																		)
									UNION (SELECT 1 as id,c.userid as userid,'-' as courseid,'-' as cuota,'cobro' as valor_cuota,c.importe as valor_pagado,c.date as date,c.takenby as takenby,
									'-' as periodo,1 as libroid,1 as p_especial,'-' as grupoid,1 as insc_id,c.comprobanteid as numero,1 as baja_id,1 as baja_cancel, c.summary as detalle,
									cc.pendiente as pendiente
																FROM h_cobros c
																INNER JOIN h_comprobantes cc on cc.id=c.comprobanteid
																WHERE c.userid={$id}
																GROUP BY c.id
																	)
									order by id;");

		$data['cobros']= $H_DB->GetAll("SELECT summary,	comprobanteid, date, importe,takenby
																FROM h_cobros
																WHERE userid={$id}");
		//alan
		//alan	update

		if ($H_USER->has_capability('contactos/pagos')) {
			if($_REQUEST['boton']){
				foreach($data['pagos'] as $numero => $pago){
					$form_data['grupoid']=$_POST[$numero];
					$H_DB->update('h_cuotas',$form_data,"id={$pago['id']}");
					$form_data2['grupoid']=$_POST[$numero];
					if($pago['numero']!=""){
						$comp=explode("-",$pago['numero']);
					}else{
						$comp=0;
					}
					if($comp!=""){
						foreach($comp as $com){
							$H_DB->update('h_comprobantes',$form_data2,"id={$com}");
						}
					}
					$header=($pago['userid']);
				}
				header("Location: contactos.php?v=pagos&id={$header}");
			}
		}

		//alan
		/*
		$data['no_pagos'] = $H_DB->GetAll("SELECT DISTINCT c.courseid
																			 FROM h_cuotas c
																			 LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																			 LEFT JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
																			 WHERE c.userid={$id} AND c.valor_pagado < c.valor_cuota
																			 AND c.courseid NOT IN (SELECT DISTINCT b.courseid FROM h_bajas b
																															WHERE b.cancel=0 AND b.userid={$id}
																															AND b.courseid=c.courseid AND periodo={$HULK->periodo});");
		*/
		$data['no_pagos'] = $H_DB->GetAll("SELECT DISTINCT c.courseid
																			 FROM h_cuotas c
																			 LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																			 LEFT JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
																			 WHERE c.userid={$id} AND c.valor_pagado < c.valor_cuota
																			 AND c.insc_id NOT IN (SELECT DISTINCT b.insc_id FROM h_bajas b
																															WHERE b.cancel=0 AND b.userid={$id}
																															AND b.courseid=c.courseid AND periodo={$HULK->periodo});");

		$data['comprobantes'] = $H_DB->GetAll("SELECT * FROM h_comprobantes h WHERE userid={$id} ORDER BY date DESC,numero DESC;");


		break;

	case 'pagos-print':

		$H_USER->require_capability('contact/paid/register');

		$id = $_REQUEST['id'];
		$data['cobro'] = $H_DB->GetAll("SELECT * FROM h_cobros c WHERE c.comprobanteid={$id};");
		$data['comprobante'] = $H_DB->GetRow("SELECT * FROM h_comprobantes h WHERE id={$id};");

		$contacto = $LMS->GetRow("SELECT * FROM mdl_user u WHERE id={$data['comprobante']['userid']};");

		if($data['comprobante']['grupoid'] > 0)
			$grupo = $H_DB->GetRow("SELECT * FROM h_grupos g WHERE id={$data['comprobante']['grupoid']};");

		$summary=$H_DB->GetOne("SELECT summary FROM	h_cobros WHERE comprobanteid={$id}");

		$data['comprobante']['señores']		=	($data['comprobante']['grupoid']>0)? $grupo['name']."<br />Alumno: ".$contacto['lastname'].", ".$contacto['firstname']: $contacto['lastname'].", ".$contacto['firstname'];
		$data['comprobante']['domicilio']	=	($data['comprobante']['grupoid']>0)? $grupo['address']: $contacto['address'];
		$data['comprobante']['tel']			=	($data['comprobante']['grupoid']>0)? $grupo['phone']: $contacto['phone1']."<br/>".$contacto['phone2'];
		$data['comprobante']['localidad']	=	($data['comprobante']['grupoid']>0)? $grupo['city']: $contacto['city'];
		if($data['cobro']!=""){
			$data['nombre'] = ($data['comprobante']['grupoid']>0)? $grupo['name']."<br />Alumno: ".$contacto['lastname'].", ".$contacto['firstname']: $contacto['lastname'].", ".$contacto['firstname'];
			$data['comprobante']['summary'] =	$summary;
		}
		$data['comprobante']['cp']  			=	($data['comprobante']['grupoid']>0)? $grupo['cp']: $contacto['cp'];
		$data['comprobante']['cuit']			=	($data['comprobante']['grupoid']>0)? $grupo['cuit']: $contacto['cuit'];
		$data['comprobante']['username']	=	$contacto['username'];
		if ($data['comprobante']['grupoid'] > 0)
			$data['comprobante']['iva'.$grupo['iva']]="X";
		else
			$data['comprobante']['iva4']="X";

		if($data['comprobante']['tipo']!=3){
			$data['cuotas'] = $H_DB->GetAll("SELECT c.*,cc.*,i.comisionid FROM h_comprobantes_cuotas cc	 
											INNER JOIN h_cuotas c ON cc.cuotaid=c.id
											INNER JOIN h_inscripcion i ON i.id=c.insc_id											
											WHERE cc.comprobanteid={$id};");
			if($data['cuotas']){
				$t=1;
				$data['total_cuotas'] = $H_DB->GetOne("SELECT MAX(cuota) FROM h_cuotas c
																							 WHERE insc_id = {$data['cuotas'][0]['insc_id']};");
				$data['becado'] = $H_DB->GetOne("SELECT becado FROM h_inscripcion WHERE id ={$data['cuotas'][0]['insc_id']};");
			}else if($data['cobro']==""){
				show_error("Error", "El recibo no tiene cuotas asociadas");
			}
			/*
			//TODO: Revisar si no tiene cuotas pagas!!!
			$data['total_cuotas'] = $H_DB->GetOne("SELECT MAX(cuota) FROM h_cuotas c
												 WHERE c.userid={$data['comprobante']['userid']}
												 AND c.courseid={$data['cuotas'][0]['courseid']}
												 AND c.periodo={$data['cuotas'][0]['periodo']};");
			*/
		//alan
		}else{
			$data['comps'] = $H_DB->GetAll("SELECT * FROM h_comprobantes WHERE cancel={$data['comprobante']['id']};");
		}

		if(($_REQUEST['t']) OR ($t==1)){
			$data['comision'] = $LMS->GetOne("SELECT c.id
																				FROM mdl_course c
																				INNER JOIN mdl_context ctx ON c.id=ctx.instanceid
																				INNER JOIN mdl_role_assignments ra ON ctx.id=ra.contextid
																				WHERE ra.userid={$data['comprobante']['userid']}
																				AND c.id={$data['cuotas'][0]['comisionid']}
																				LIMIT 0,1;");

			if($_REQUEST['newpass']){
				$data['newpass'] = $_REQUEST['newpass'];
			}
		}
		//alan
		//show_array($data);
		if(!$data['cobro'])	{
			if($data['comprobante']['tipo']==1){
				$view->Load('print/recibo',$data);
			}elseif($data['comprobante']['tipo']==2){
				$view->Load('print/factura',$data);
			}elseif($data['comprobante']['tipo']==3){
				$view->Load('print/nota_credito',$data);
			}
			die();
		}
		if($data['cobro']){
			if($data['comprobante']['tipo']==1){
				$view->Load('print/recibocobro',$data);
			}elseif($data['comprobante']['tipo']==2){
				$view->Load('print/facturacobro',$data);
			}
			die();
		}
		//alan

		break;
	case 'pagos-print-pdf':

		$H_USER->require_capability('contact/paid/register');

		$id = $_REQUEST['id'];

		$data['comprobante'] = $H_DB->GetRow("SELECT * FROM h_comprobantes h WHERE id={$id};");

		$contacto = $LMS->GetRow("SELECT * FROM mdl_user u WHERE id={$data['comprobante']['userid']};");

		if($data['comprobante']['grupoid'] > 0)
			$grupo = $H_DB->GetRow("SELECT * FROM h_grupos g WHERE id={$data['comprobante']['grupoid']};");

		$data['comprobante']['señores']		=	($data['comprobante']['grupoid']>0)? $grupo['name']."<br />Alumno: ".$contacto['lastname'].", ".$contacto['firstname']: $contacto['lastname'].", ".$contacto['firstname'];
		$data['comprobante']['domicilio'] =	($data['comprobante']['grupoid']>0)? $grupo['address']: $contacto['address'];
		$data['comprobante']['tel']				=	($data['comprobante']['grupoid']>0)? $grupo['phone']: $contacto['phone1']."<br/>".$contacto['phone2'];
		$data['comprobante']['localidad'] =	($data['comprobante']['grupoid']>0)? $grupo['city']: $contacto['city'];
		$data['comprobante']['cp']  			=	($data['comprobante']['grupoid']>0)? $grupo['cp']: $contacto['cp'];
		$data['comprobante']['cuit']			=	($data['comprobante']['grupoid']>0)? $grupo['cuit']: $contacto['cuit'];
		$data['comprobante']['username']	=	$contacto['username'];
		if ($data['comprobante']['grupoid'] > 0)
			$data['comprobante']['iva'.$grupo['iva']]="X";
		else
			$data['comprobante']['iva4']="X";

		if($data['comprobante']['tipo']!=3){
			$data['cuotas'] = $H_DB->GetAll("SELECT * FROM h_comprobantes_cuotas cc
																			 INNER JOIN h_cuotas c ON cc.cuotaid=c.id
																			 WHERE cc.comprobanteid={$id};");
			if($data['cuotas']){
				$data['total_cuotas'] = $H_DB->GetOne("SELECT MAX(cuota) FROM h_cuotas c
																							 WHERE insc_id = {$data['cuotas'][0]['insc_id']};");
				$data['becado'] = $H_DB->GetOne("SELECT becado FROM h_inscripcion WHERE id ={$data['cuotas'][0]['insc_id']};");
			}else{
				show_error("Error", "El recibo no tiene cuotas asociadas");
			}
			/*
			//TODO: Revisar si no tiene cuotas pagas!!!
			$data['total_cuotas'] = $H_DB->GetOne("SELECT MAX(cuota) FROM h_cuotas c
																						 WHERE c.userid={$data['comprobante']['userid']}
																						 AND c.courseid={$data['cuotas'][0]['courseid']}
																						 AND c.periodo={$data['cuotas'][0]['periodo']};");
			*/
		}else{
			$data['comps'] = $H_DB->GetAll("SELECT * FROM h_comprobantes WHERE cancel={$data['comprobante']['id']};");
		}

		if($_REQUEST['t']){
			$data['comision'] = $LMS->GetOne("SELECT c.id
																				FROM mdl_course c
																				INNER JOIN mdl_context ctx ON c.id=ctx.instanceid
																				INNER JOIN mdl_role_assignments ra ON ctx.id=ra.contextid
																				WHERE ra.userid={$data['comprobante']['userid']}
																				AND c.from_courseid={$data['cuotas'][0]['courseid']}
																				AND c.periodo={$data['cuotas'][0]['periodo']}
																				LIMIT 0,1;");

			if($_REQUEST['newpass']){
				$data['newpass'] = $_REQUEST['newpass'];
			}
		}

		include("{$HULK->libdir}/pdfClass/class.ezpdf.php");
	//	include("{$HULK->libdir}/pdfClass/class.backgroundpdf.php");
	//	require_once("{$HULK->libdir}/dompdf/dompdf_config.inc.php");

		$pdf = new Cezpdf('A4');
	//	$pdf->selectFont('libraries/pdfClass/fonts/Helvetica.afm');

		$pdf->addText(400,717,12,'Hola mundo');
		/*if($data['comprobante']['tipo']==1){
			$view->Load('print/recibo-pdf',$data);
		}elseif($data['comprobante']['tipo']==2){
			$view->Load('print/factura-pdf',$data);
		}elseif($data['comprobante']['tipo']==3){
			$view->Load('print/nota_credito-pdf',$data);
		}*/

		$pdf->ezStream(array('Content-Disposition'=>'attachment','filename'=>'prueba.pdf'));

		die();

		break;

	case 'anular':

		$H_USER->require_capability('comprobante/anular');

		$id 	= $_REQUEST['id'];
		$user = $_REQUEST['user'];

		if($id>0){
			$H_DB->update("h_comprobantes",array('anulada' => 1),"id={$id}");

			// Actualizar cuotas
			$cuotas = $H_DB->GetAll("SELECT c.id, cc.importe, c.valor_pagado
															 FROM h_cuotas c INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
															 WHERE cc.comprobanteid={$id};");

			foreach($cuotas as $cuota){
				$H_DB->update("h_cuotas", array("valor_pagado" => ($cuota['valor_pagado']-$cuota['importe'])), "id={$cuota['id']}");
			}

			// Eliminar asociacion a cuotas
			$H_DB->delete("h_comprobantes_cuotas", "comprobanteid={$id}", null);
		}

		$comprobante = $H_DB->GetRow("SELECT numero, tipo FROM h_comprobantes WHERE id = {$id}");

		$activity['userid'] 		= $H_USER->get_property('id');
		$activity['contactid']	= $user;
		$activity['typeid'] 		= 4;
		$activity['statusid'] 	= 7;
		$activity['subject'] 		= "Se anuló el ".$HULK->tipos[$comprobante['tipo']]." número ".$comprobante['puntodeventa'].'-'.sprintf("%08d",$comprobante['numero']);
		$alumno = $LMS->GetOne("SELECT CONCAT(firstname,' ',lastname) FROM mdl_user WHERE id = {$user}");
		$editor = $LMS->GetOne("SELECT CONCAT(firstname,' ',lastname) FROM mdl_user WHERE id = {$H_USER->get_property('id')}");
		$activity['summary'] 		= $editor." anuló el comprobante de pago número ".$comprobante['puntodeventa'].'-'.$comprobante['numero']." al alumno ".$alumno.".";
		$activity['startdate'] 	= time();
		$activity['enddate']	 	= time();

		if(!$H_DB->insert("h_activity",$activity)){
			show_error("Error","Error al registrar la actividad.");
		}

		redireccionar("contactos.php?v=pagos&id=".$user);

		break;

	case 'cobrar':

		$H_USER->require_capability('comprobante/cobrar_pend');

		$id 		 = $_POST['comp_cobrar'];
		$concepto= $_POST['concepto'];
		$detalle = $_POST['detalle'];

		if($id>0){
			$comp = $H_DB->GetRow("SELECT * FROM h_comprobantes WHERE id={$id};");

			$comp['pendiente'] = 0;
			$comp['concepto']	 = $concepto;
			if($comp['detalle'] != ""){
				$comp['detalle'] .= "<br /><br />".$detalle;
			}else{
				$comp['detalle'] = $detalle;
			}
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
		redireccionar("contactos.php?v=pagos&id=".$comp['userid']);

		break;

	case 'cancelar':

		$id = $_REQUEST['id'];
		$user = $_REQUEST['user'];
		$H_USER->require_capability('comprobante/cancelar');

		$comp = $H_DB->GetRow("SELECT * FROM h_comprobantes WHERE id={$id};");

		// Genero la nota de crédito
		$nc->userid		= $comp['userid'];
		$nc->grupoid 	= $comp['grupoid'];
		$nc->date			= time();
		$nc->importe	= $comp['importe'];
		$nc->detalle	= "Para cancelar ".$HULK->tipos[$comp['tipo']]." Nro. ".sprintf("%08d", $comp['numero']).".";
		$nc->concepto	= $comp['concepto'];
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
		redireccionar("contactos.php?v=pagos&id=".$user);

		break;

/*****************/
	case 'roles':

		$data['id']       = $id = $_REQUEST['id'];
		$data['roleid']   = $roleid = $_REQUEST['roleid'];

		$menuroot['ruta'] = array("{$LMS->GetField('mdl_user','lastname',$id)}, {$LMS->GetField('mdl_user','firstname',$id)}"=>"contactos.php?v=view&id={$id}","Configuraci&oacute;n roles globales"=>"#");

	   $H_USER->require_capability('contact/lmsrole');

		if($_POST['update']){
			if($roleid==""){
				show_error("Error","Seleccione ROL.");
				die();
			}
		}
		if($id > 0){
			if ($_POST['update']){
				$data['recargar']=true;
				if($_POST['academias']){
					foreach($_POST['academias'] as $acad){
						$academyctx = $LMS->getAcademyCtx($acad);
						$academiasconesterole[]=$academyctx;
						if ($roleid > 0){
							 // Check for existing entry
							 $ra = $LMS->GetField_sql("SELECT id FROM mdl_role_assignments ra
							 WHERE roleid={$roleid} AND userid={$id} AND contextid={$academyctx};");
							if (empty($ra)) {             // Create a new entry
								$ra['roleid']		= $roleid;
								$ra['contextid']	= $academyctx;
								$ra['userid']      = $id;
								$ra['hidden']      = 1;
								$ra['timestart']	= round(time(), -2);
								$ra['timeend']     = 0;
								$ra['timemodified']= 0;
								$ra['enrol']       = 'manual';
								$ra['modifierid']  = $H_USER->get_property('id');
								$ra['academyid']   = $acad;
								if (!$ra = $LMS->insert('mdl_role_assignments', $ra)) {
									return false;
								}
							}
						}
					}
				}
				if ($academiasconesterole){
					$rolesnoborrar=implode(",",$academiasconesterole);
					if($roleid==2){
						$LMS->enrolUser($id, 100001, 5);
					}elseif($roleid==9 || $roleid==10){
						$LMS->enrolUser($id, 100000, 5);
					}			
				}else{
					$rolesnoborrar=0;
					if($roleid==2){
						$LMS->unenrolUser($id, 100001, 5);
					}elseif($roleid==9 || $roleid==10){
						$LMS->unenrolUser($id, 100000, 5);
					}			
				}
				$LMS->delete("mdl_role_assignments","contextid NOT IN ({$rolesnoborrar}) AND userid={$id} AND roleid = {$roleid}");
			}

			$data['roles']     = $LMS->GetAll("SELECT r.id, r.shortname AS name FROM mdl_role r WHERE r.id IN ({$HULK->roles_globales}) ORDER BY r.sortorder;");

			$data['academias'] = $LMS->getAcademys();

			if ($roleid > 0){
				if($existentes = $LMS->GetAll("SELECT ra.contextid FROM mdl_role_assignments ra WHERE ra.userid={$id} AND ra.roleid={$roleid};")){
					foreach ($existentes as $existente){
						$data['existentes'][]=$LMS->getContextAcad($existente['contextid']);
					}
				}else{
					$data['existentes'][]=0;
				}
			}
		}else{
				show_error("Error","Seleccione un usuario.");
		}
	break;
/*****************/
	case 'capacidades':

		$data['id']       = $id = $_REQUEST['id'];
		$menuroot['ruta'] = array("{$LMS->GetField('mdl_user','lastname',$id)}, {$LMS->GetField('mdl_user','firstname',$id)}"=>"contactos.php?v=view&id={$id}","Configuraci&oacute;n de certificaciones"=>"#");

		$H_USER->require_capability('contact/lmsrole');

			if ($_POST['update']){
				if ($_POST['cursos'] ){
					foreach($_POST['cursos'] as $curso){
							 // Check for existing entry
							 $i = $LMS->GetField_sql("SELECT id FROM mdl_proy_instructores i
							 WHERE i.courseid={$curso} AND userid={$id};");
							if (empty($i)) {             // Create a new entry
								$i['userid']		= $id;
								$i['courseid']		= $curso;
								$i['startdate']	= time();
								$i['modifierid']  	= $H_USER->get_property('id');
								$i = $LMS->insert('mdl_proy_instructores', $i);
							}
					}
					$cursosnoborrar=implode(",",$_POST['cursos']);
				}else{
					$cursosnoborrar=0;
				}
				$LMS->delete("mdl_proy_instructores","courseid NOT IN ({$cursosnoborrar}) AND userid={$id}");
			}

		$convenios = $LMS->getConvenios();

		foreach($convenios as $convenio){
			$data['convenios'][$convenio['id']]			= $convenio;
			$data['convenios'][$convenio['id']]['cursos']	= $LMS->getConvenioCourse($convenio['id']);
		}

		$data['existentes'] = $LMS->getCol("SELECT i.courseid FROM mdl_proy_instructores i WHERE i.userid={$id};");

	break;
	case 'cancel_insc':
		$data['id']	= $id = $_REQUEST['id'];
		$data['insc_id'] = $_POST['carreras'];
		$usuario=show_name($id);
		$menuroot['ruta'] = array("Contactos"=>"contactos.php?v=list","{$usuario}"=>"contactos.php?v=view&id={$id}","Cancelar Inscripción"=>"#");

		if ($_GET['action']=='cancel'){
			// Genero la nota de crédito
			$nc->userid		= $id;
			$nc->grupoid 	= $_POST['grupoid'];
			$nc->date			= time();
			$nc->importe	= $_POST['total'];
			if($_POST['detalle']!=""){
				$detalle = $_POST['detalle']."<br /><br />";
			}
			$detalle .= "Para cancelar los siguiente(s) comprobante(s):<br />";
			foreach($_POST['comprobantes'] as $comp){
				$detalle .= $HULK->tipos[$H_DB->GetField("h_comprobantes", "tipo", $comp)]." Nro. ".sprintf("%08d", $H_DB->GetField("h_comprobantes", "numero", $comp))."<br />";
			}
			$nc->detalle	= $detalle;
			$nc->concepto	= 1;
			$nc->tipo			= 3;
			$nc->takenby	= $H_USER->get_property('id');

			if(!$nc->id = $H_DB->insert("h_comprobantes",$nc)){
				show_error("Pagos", "Error al insertar en la base de datos la NC");
			}

			foreach($_POST['comprobantes'] as $comp){
				// Cancelo el comprobante con la nota de crédito
				$H_DB->update("h_comprobantes",array('cancel' => $nc->id),"id={$comp}");
				// Eliminar cuotas
				$cuotas = $H_DB->GetAll("SELECT c.id FROM h_cuotas c WHERE c.insc_id={$data['insc_id']};");
				foreach($cuotas as $cuota){
					$H_DB->delete("h_cuotas", "id={$cuota['id']}", null);
				}
				// Eliminar asociacion a cuotas
				$H_DB->delete("h_comprobantes_cuotas", "comprobanteid={$comp}", null);
			}
			if($_POST['saldo'] == 'devuelve'){
				// Módulo de actividad
				$activity['subject'] 	= "Cancelación de Inscripción";
				$activity['summary'] 	= "El alumno canceló la inscripción a {$LMS->GetField('mdl_course','shortname',$data['carrera'])}. Se le devuelve el dinero.";
				$H_DB->setActivityLog($id,$activity['subject'],$activity['summary']);

			}elseif($_POST['saldo'] == 'queda'){
				// Obtengo el saldo que tiene actualmente el alumno
				$saldo = $LMS->GetOne("SELECT saldo FROM mdl_user WHERE id={$id};");
				// Actualizo el saldo
				$LMS->update("mdl_user", array("saldo" => ($_POST['total']+$saldo)), "id={$id}");
				// Módulo de actividad
				$activity['subject'] 	= "Cancelación de Inscripción";
				$activity['summary'] 	= "El alumno canceló la inscripción a {$LMS->GetField('mdl_course','shortname',$data['carrera'])}. El dinero queda para una próxima inscripción. Se generó la Nota de Crédito por $ ".number_format($_POST['total'],2,',','.').".";
				$H_DB->setActivityLog($id,$activity['subject'],$activity['summary']);
			}
			// Obtengo el id de la comisión donde esta enrolado
			$id_comi = $H_DB->GetOne("SELECT comisionid FROM h_inscripcion WHERE id={$data['insc_id']};");
			// Desenrolo al alumno
			if(!$LMS->unenrolUser($id, $id_comi, 5) ){ show_error("Error","Error al desenrolar el usuario");}
			$carrera =$LMS->GetField("mdl_course","from_courseid",$id_comi);
			$baja->userid 		= $id;
			$baja->comisionid 	= $id_comi;
			$baja->detalle 		= "Baja por cancelación de inscripción a {$LMS->GetField('mdl_course','shortname',$carrera)}. Se le devuelve el dinero.";
			$baja->periodo 		= $LMS->GetField("mdl_course","periodo",$id_comi);
			$baja->courseid 	= $carrera;
			$baja->date			= time();
			$baja->user 		= $H_USER->get_property('id');
			$baja->insc_id 		= $data['insc_id'];
			if(!$H_DB->insert('h_bajas',$baja)){ show_error("Error","Error al insertar la baja");	}
			redireccionar("contactos.php?v=pagos&id=".$id);
		}
		$data['no_pagos'] = $H_DB->GetAll("SELECT DISTINCT c.courseid, c.insc_id
																			 FROM h_cuotas c
																			 LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																			 LEFT JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
																			 WHERE c.insc_id NOT IN(SELECT DISTINCT id FROM h_bajas
																															WHERE cancel=0 AND userid={$id})
																			 AND c.userid={$id};");
		$data['pagos'] = $H_DB->GetAll("SELECT c.*, GROUP_CONCAT(DISTINCT comp.id SEPARATOR '-') as numero,
																		b.id AS baja_id, b.cancel AS baja_cancel, b.detalle
																		FROM h_cuotas c
																		LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																		LEFT JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
																		LEFT JOIN (SELECT id, cancel, detalle, courseid, userid, periodo, insc_id
																							 FROM h_bajas
																							 ORDER BY id DESC) b ON b.insc_id = c.insc_id
																		AND c.periodo=b.periodo
																		WHERE c.userid={$id}
																		GROUP BY c.id;");

		$data['comprobantes'] = $H_DB->GetAll("SELECT * FROM h_comprobantes h WHERE userid={$id} ORDER BY date,numero;");

		break;

/*cuota edit, debajo de lo anterior*/
	case 'cuota_edit':
		if(!$H_USER->has_capability('cuota/editar')):
			show_error("Error","No tiene permisos para editar cuotas");
			die();
		endif;
		$id 		= $_REQUEST['id'];
		$value	= $_REQUEST['value'];
		$cuota1=$H_DB->GetOne("SELECT valor_cuota FROM h_cuotas  WHERE id = {$id}");
		if(!$H_DB->update('h_cuotas',array('valor_cuota' => $value),"id = {$id}")){
			show_error("Error","Error al actualizar la cuota");
		}
			$numerocuota=$H_DB->GetOne("SELECT cuota FROM h_cuotas  WHERE id = {$id}");
			$cuota2=$H_DB->GetOne("SELECT valor_cuota FROM h_cuotas  WHERE id = {$id}");
			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	=  $H_DB->GetOne("SELECT userid FROM h_cuotas WHERE id = {$id}");
			$activity['typeid'] 		= 4;
			$activity['statusid'] 	= 7;
			$activity['subject'] 		= "Actualizacion de cuota N° ".$numerocuota;
			$alumno= $LMS->GetOne("SELECT CONCAT(lastname,' ',firstname) FROM mdl_user  WHERE id = {$activity['contactid']}");
			$activity['summary'] 		=  $LMS->GetOne("SELECT CONCAT(firstname,' ',lastname) FROM mdl_user  WHERE id = {$activity['userid']}")." le actualizo la cuota  a ".$alumno." y la cuota a pagar paso de ".$cuota1." a ".$cuota2;
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$H_DB->insert("h_activity",$activity);
		echo number_format($value, 2, ',', '.');
		die();
		break;

	case 'baja':

		$H_USER->require_capability('contact/baja');

		$id 	= $_REQUEST['user'];
		$comi	= $_REQUEST['comi'];

		if($_POST['stage']==1){

			$H_DB->registrarBaja($id,$comi,array('detalle'=>$_POST['detalle'],'insc_id'=>$_POST['insc_id']));
			$LMS->user_status($id,$comi,'I');
			$H_DB->setActivityLog($id,"Baja {$LMS->GetField('mdl_course','shortname',$comi)}","El alumno ha sido dado de baja en la comisión {$LMS->GetField('mdl_course','shortname',$comi)}. Detalle: {$_POST['detalle']}");

			redireccionar("contactos.php?v=view&id={$id}");
			die();
		}

		$data['contact'] = $LMS->GetRow("SELECT * FROM mdl_user WHERE id={$id};");
		$data['comi'] = $LMS->GetRow("SELECT * FROM mdl_course WHERE id={$comi};");
		$insc = $H_DB->GetOne("SELECT id FROM h_inscripcion WHERE userid={$id} AND comisionid={$comi};");

		$menuroot['ruta'] = array("Contactos"=>"contactos.php?v=list","{$data['contact']['firstname']} {$data['contact']['lastname']}"=>"contactos.php?v=view&id={$id}");

		if ($insc>0){
			$data['comprobantes'] = $H_DB->GetAll("SELECT DISTINCT comp.* FROM h_comprobantes comp
																						 INNER JOIN h_comprobantes_cuotas cc ON cc.comprobanteid=comp.id
																						 INNER JOIN h_cuotas c ON cc.cuotaid=c.id
																						 WHERE c.insc_id={$insc} AND comp.anulada=0
																						 ORDER BY comp.date, comp.numero;");

			$cuotas = $H_DB->GetAll("SELECT * FROM h_cuotas
															 WHERE insc_id={$insc}
															 ORDER BY cuota;");

			foreach($cuotas as $cuota):

				$courseid = $cuota['courseid'];
				$periodo = $cuota['periodo'];

				$data['cuotas'][$cuota['courseid'].$periodo]['id'] = $cuota['courseid'];
				$data['cuotas'][$cuota['courseid'].$periodo]['cuota'.$cuota['cuota']] = $cuota['valor_cuota']-$cuota['valor_pagado'];
				$data['cuotas'][$courseid.$periodo]['periodo'] = $periodo;

				$data['insc_id'] = $cuota['insc_id'];
			endforeach;
		}

		break;

	case 'alta':

		$H_USER->require_capability('contact/alta');

		$id 	= $_REQUEST['bajaid'];

		$baja = $H_DB->GetRow("SELECT * FROM h_bajas WHERE id={$id};");
		$H_DB->cancelarBaja($baja['userid'],$baja['comisionid']);
		$LMS->user_status($baja['userid'],$baja['comisionid'],'E');
		$H_DB->setActivityLog($baja['userid'],"Alta {$LMS->GetField('mdl_course','shortname',$baja['comisionid'])}","El alumno ha sido dado de alta en la comisión {$LMS->GetField('mdl_course','shortname',$baja['comisionid'])}.");
		redireccionar("contactos.php?v=view&id={$baja['userid']}");
		die();
		break;

	case 'cobro':
			$id =$_REQUEST['id'];
			$data['grupos']	= $H_DB->GetAll("SELECT g.id,g.name FROM h_grupos g
																						INNER JOIN h_grupos_users gu ON g.id=gu.grupoid WHERE gu.userid={$id};");
			$usuario=show_name($id);
			$menuroot['ruta'] = array("Contactos"=>"contactos.php?v=list","{$usuario}"=>"contactos.php?v=view&id={$id}");

			if($_REQUEST['importe']){
				if($_REQUEST['pendiente']==1){
					$comprobante['pendiente'] 		= $_POST['pendiente'];
				}else{
					$comprobante['pendiente'] = 0;
				}
				$comprobante['importe'] 		= $_POST['importe'];
				$comprobante['takenby'] 		= $H_USER->get_property('id');
				$comprobante['userid'] 		= $_POST['usuario'];
				$comprobante['date']			= time();
				$comprobante['detalle']			=  $_POST['detalle'];
				$comprobante['tipo']			= $_POST['tipo'];
				$comprobante['concepto']			= $_POST['concepto'];
				$comprobante['nrocheque']	= $_POST['nrocheque'];
				$comprobante['grupoid']	= $_POST['grupoid'];
				if(!$comprobanteid = $H_DB->insert("h_comprobantes",$comprobante)){
								show_error("Error al registrar el comprobante.");
				}
				$form['importe'] 		= $_POST['importe'];
				$form['summary']	= $_POST['detalle'];
				$form['takenby'] 		= $H_USER->get_property('id');
				$form['userid'] 		= $_POST['usuario'];
				$form['date']			= time();
				$form['comprobanteid']			= $comprobanteid;
				$form['grupoid']	= $_POST['grupoid'];
				if(!$H_DB->insert("h_cobros",$form)){
								show_error("Error al registrar el cobro.");
				}

				$activity['userid']   =  $H_USER->get_property('id');
				$activity['contactid']   =  $_POST['usuario'];
				$activity['summary'] = "Se le registró un cobro al contacto";
				$activity['typeid'] = 4;
				$activity['statusid'] = 7;
				$activity['startdate'] = time();
				$activity['enddate'] = time();
				$activity['subject'] = "Cobro registrado";
				if(!$H_DB->insert("h_activity",$activity)){
								show_error("Error al registrar la actividad.");
				}
				header ("Location: contactos.php?v=pagos&id={$_POST['usuario']}");


			}
		break;

/*****************/
	case 'ver_administrativos':

		$data['q'] = $q = $_REQUEST['q'];
		$H_USER->require_capability('contact/view');

		$menuroot['ruta'] = array("Listado de contactos administrativos"=>"contactos.php?v=ver_administrativos");

		$WHERE .= "WHERE u.deleted=0 ";

		// Cantidad de resultados
		$max	= $LMS->Execute("SELECT COUNT(DISTINCT u.id) as total FROM mdl_user u {$WHERE} ORDER BY u.id DESC;");
		$t		= $max->fields['total'];

		// Pagina
		$p = $_REQUEST['p'];

		// Resultados
		$r = $_REQUEST['r'];

		if ($r<5) $r= 25;
		if ($p<1) $p= 1;
		$totalPag = ceil($t/$r);
		if ($p>$totalPag) $p = 1;
		$inicio		= ($p-1)*$r;

		$data['p']=$p;

		$LIMIT = "LIMIT {$inicio},{$r}";
		$data['rows']		= $LMS->GetAll("SELECT DISTINCT u.id, u.username, u.lastname, u.firstname, u.email, u.acid  FROM mdl_user u {$WHERE} ORDER BY u.id DESC {$LIMIT};");

		// Si es un resultado redirecciono a ver el contacto

		break;
		case 'editar_comprobante':

			if(!$H_USER->has_capability('comprobante/editar')):
				show_error("Error","No tiene permisos para editar comprobantes.");
				die();
			endif;
			$id = $_REQUEST['id'];
			$data['comp'] = $H_DB->GetRow("SELECT * FROM h_comprobantes WHERE id={$id};");

			if($data['comp']['rendicionid']>0){
				if(!$H_USER->has_capability('comprobante/editar_rendido')):
					show_error("Error","No tiene permisos para editar comprobantes que ya fueron rendidos.");
					die();
				endif;
			}
			// Traigo todos los datos del comprobante
			if($_POST['guardar']){
				unset($_POST['guardar']);
				$fnac = explode("-",$_POST['date']);
				$_POST['date']	= mktime(0,0,0,$fnac[1],$fnac[0],$fnac[2]);
				if(!$H_DB->update('h_comprobantes',$_POST,"id = {$id}")){
					show_error("Error","Error al actualizar el comprobante");
				}

				$activity['subject'] 	= "UPDATE -> comprobante id = ".$id;
				$activity['summary'] 	= "Punto de venta: {$data['comp']['puntodeventa']} => {$_POST['puntodeventa']} ";
				$activity['summary'] 	.= "Numero: {$data['comp']['numero']} => {$_POST['numero']} ";
				$activity['summary'] 	.= "Concepto: {$data['comp']['concepto']} => {$_POST['concepto']} ";
				$activity['summary'] 	.= "Tipo: {$data['comp']['tipo']} => {$_POST['tipo']} ";
				$activity['summary'] 	.= "Detalle: {$data['comp']['detalle']} => {$_POST['detalle']} ";
				$H_DB->setActivityLog($data['comp']['userid'],$activity['subject'],$activity['summary']);
				$view->js("window.opener.document.location.reload();self.close()");
			}

			$view->Load('header',$data);
			$view->Load('contactos/'.$v,$data);
			$view->Load('footer');
			die();
		break;

	default:
		$v	=	'list';
		break;
}
$view->Load('header',$data);
if(empty($print)) $view->Load('menu',$data);
if(empty($print)) $view->Load('menuroot',$menuroot);
$view->Load('contactos/'.$v,$data);
$view->Load('footer');
?>
