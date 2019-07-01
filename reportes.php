<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

$v();

function enrolados2(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
	set_time_limit(3600); // 1 hour should be enough


		$data['periodo'] = $_REQUEST['periodo'];
		$menuroot['ruta'] = array("Reportes"=>"reportes.php?v=enrolados2","Alumnos por Academia de la Red"=>"reportes.php?v=enrolados2","Listado"=>"reportes.php?v=enrolados2");

			if($_POST['pais']!=""){
				$data['paisel']=$paisel=$_POST['pais'];
			}else{
				$data['paisel']=array();
			}

			if($_POST['forma_de_pago']!=""){
				$data['forma_de_pagosel']=$_POST['forma_de_pago'];
				$formadepago="'".implode("','",$_POST['forma_de_pago'])."'";
			}else{
				$data['forma_de_pagosel']=array("por_alumno");
				$formadepago="'por_alumno'";
			}

			if($_POST['periodo1']!=""){
				$data['persel1'] = $periodos[] = $_POST['periodo1'];
			}else{
				$data['persel1'] = $periodos[] = $HULK->periodo;
			}
			$SUMIF = "SUM(IF(e.finalgrade='1.00000' AND e.periodo={$data['persel1']},1,0)) bajas1,
								SUM(IF((e.finalgrade<>'1.00000' OR e.finalgrade is NULL) AND e.periodo={$data['persel1']},1,0)) enrol1";
			if($_POST['periodo2']!=""){
				$data['persel2'] = $periodos[] = $_POST['periodo2'];
				$SUMIF .=",	SUM(IF(e.finalgrade='1.00000' AND e.periodo={$data['persel2']},1,0)) bajas2,
									SUM(IF((e.finalgrade<>'1.00000' OR e.finalgrade is NULL) AND e.periodo={$data['persel2']},1,0)) enrol2";
			}else{
				$data['persel2'] = "";
			}

			if($_POST['periodo3']!=""){
				$data['persel3'] = $periodos[] = $_POST['periodo3'];
				$SUMIF .=",	SUM(IF(e.finalgrade='1.00000' AND e.periodo={$data['persel3']},1,0)) bajas3,
									SUM(IF((e.finalgrade<>'1.00000' OR e.finalgrade is NULL) AND e.periodo={$data['persel3']},1,0)) enrol3";
			}else{
				$data['persel3'] = "";
			}


			if($_POST['carrera']){
				$data['carsel']=$_POST['carrera'];
				$carsel=implode(",",$_POST['carrera']);
			}else{
				$carsel="114,118,119,120";
				$data['carsel']=explode(",",$carsel);
			}
			if($_POST['academy']){
				$data['acadsel']=$_POST['academy'];
				$acadselec=implode(",",$_POST['academy']);
			}else{
				$acadselec="1,2,3";
				$data['acadsel']=array(1,2,3);
			}
			$periodos_seleccionados = implode(",",$periodos);

		$data['result'] = $LMS->GetAll("SELECT a.id AS academyid, a.name AS Academia, c.fullname AS Course, COUNT(*) AS Alumnos,
											{$SUMIF}
										FROM {$HULK->dbname}.vw_enrolados e
										INNER JOIN mdl_proy_academy a ON e.academy = a.id
										INNER JOIN {$HULK->dbname}.vw_course c ON c.id=e.modelid
										WHERE e.periodo IN ({$periodos_seleccionados})
										AND c.id IN ({$carsel})
										AND e.academy IN ({$acadselec})
										AND e.forma_de_pago IN ({$formadepago})
										AND e.roleid = 5
										GROUP BY e.academy, e.modelid
										ORDER BY e.academy, e.modelid;");

		$data['result2'] = $LMS->GetAll("SELECT c.fullname AS Course, COUNT(*) AS Alumnos,
										{$SUMIF}
										FROM {$HULK->dbname}.vw_enrolados e
										INNER JOIN mdl_proy_academy a ON e.academy = a.id
										INNER JOIN {$HULK->dbname}.vw_course c ON c.id=e.modelid
										WHERE e.periodo IN ({$periodos_seleccionados})
										AND c.id IN ({$carsel})
										AND e.academy IN ({$acadselec})
										AND e.forma_de_pago IN ({$formadepago})
										AND e.roleid = 5
										GROUP BY e.modelid
										ORDER BY e.modelid;");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);
}
function academycourse(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
	set_time_limit(3600); // 1 hour should be enough

			if($_REQUEST['encero']){
				$data['encero'];
			}
			$menuroot['ruta'] = array("Reportes"=>"reportes.php?v=academycourse","Alumnos por Academia de la Red"=>"reportes.php?v=academycourse","Tabla"=>"reportes.php?v=academycourse");

			if($_POST['pais']!=""){
				$data['paisel']=$paisel=$_POST['pais'];
			}else{
				$data['paisel']=array();
			}

			if($_POST['forma_de_pago']!=""){
				$data['forma_de_pagosel']=$_POST['forma_de_pago'];
				$formadepago="'".implode("','",$_POST['forma_de_pago'])."'";
			}else{
				$data['forma_de_pagosel']=array("por_alumno");
				$formadepago="'por_alumno'";
			}
			$data['colores_pago']= array("por_alumno"=>"#C3F7FC","por_comision"=>"#DFD9C3","plan_social"=>"#DFD9C3","fee_anual"=>"#DFD9C3");

			if($_POST['periodos']!=""){
				$data['persel'] = $_POST['periodos'];
			}else{
				$data['persel'] = $HULK->periodo;
			}


			if($_POST['carrera']){
				$data['carsel']=$_POST['carrera'];
				$carsel=implode(",",$_POST['carrera']);
			}else{
				$carsel="114,118,119,120";
				$data['carsel']=explode(",",$carsel);
			}
			if($_POST['academy']){
				$data['acadsel']=$_POST['academy'];
				$acadselec=implode(",",$_POST['academy']);
			}else{
				$acadselec="";
				$data['acadsel']=array();
			}

		if($_POST['selvertipo']==1){
			$data['conbajassel']=1;
			$sqlbajas=" AND e.userid NOT IN (select distinct b.userid from `veteran-crm`.h_bajas b WHERE b.userid=e.userid AND b.comisionid=c.id AND b.cancel=0 AND b.detalle LIKE '%tica por cambio de comisi%') ";
		}else{
			$data['conbajassel']=0;
			$sqlbajas=" AND (e.finalgrade = 3.00000 OR e.finalgrade IS NULL)
					AND e.userid NOT IN (select distinct b.userid from `veteran-crm`.h_bajas b WHERE b.userid=e.userid AND b.comisionid=c.id AND b.cancel=0) ";
		}

		if($acadselec!=""){
			$sql = "FROM {$HULK->dbname}.vw_enrolados e
					INNER JOIN mdl_proy_academy a ON e.academy = a.id
					INNER JOIN {$HULK->dbname}.vw_course c ON c.id = e.id
					INNER JOIN mdl_course cm ON cm.id = c.from_courseid
					WHERE e.periodo = {$data['persel']}
					AND cm.id IN ({$carsel})
					AND e.academy IN ({$acadselec})
					AND e.forma_de_pago IN ({$formadepago})
					AND e.roleid = 5
					{$sqlbajas}
					GROUP BY e.academy, cm.id";

			$data['carreras']=$LMS->GetAll("SELECT DISTINCT cm.id as modelid, cm.shortname ".$sql." ORDER BY cm.fullname");

			$result=$LMS->GetAll("SELECT DISTINCT e.academy as academyid, a.name, a.country, a.shortname, cm.id as modelid, COUNT(*) AS Alumnos, e.forma_de_pago ".$sql." ORDER BY a.country, a.name, cm.fullname");

			foreach($result as $row){
				$data['result'][$row['academyid']]['name']=$row['name'];
				$data['result'][$row['academyid']]['shortname']=$row['shortname'];
				$data['result'][$row['academyid']]['country']=$row['country'];
				$data['result'][$row['academyid']]['cursos'][$row['modelid']]['cant']=$row['Alumnos'];
				$data['result'][$row['academyid']]['cursos'][$row['modelid']]['forma']=$row['forma_de_pago'];
			}
		}

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}

function deudores(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

	$data['periodo_sel'] = $_REQUEST['periodos'];
	$data['modelo_sel']  = $_REQUEST['modelo'];
	$data['cuota_sel'] 	 = $_REQUEST['cuota'];
	$data['acad_sel'] 	 = $_REQUEST['academias'];
	$data['dia_sel'] 	 = $_REQUEST['dia'];

	$menuroot['ruta'] = array("Informe de deudores"=>"reportes.php?v=deudores");

	$data['academias_user'] = $LMS->getAcademys("AND status=0 ");

	$data['periodos_user'] = $LMS->getPeriodos();

	//Por curso
	$data['modelos']	= $LMS->getCursosModelo();

	if(!$data['acad_sel']){
		$data['acad_sel'] = array(1,2,3);
	}

	$WHERE = " c.academyid IN(".implode(",",$data['acad_sel']).")";

	if(!$data['cuota_sel']){
		$data['cuota_sel'] = 5;
	}

	if(!$data['periodo_sel']){
		$data['periodo_sel'] = $HULK->periodo;
	}
	$WHERE .= " AND c.periodo={$data['periodo_sel']} ";

	if($_REQUEST['modelo']){
		$WHERE .= " AND c.from_courseid={$data['modelo_sel']} ";
	}
	//alan 27/11/2012
	if(($_REQUEST['caracteristica'])==2){
		$WHERE .= " AND c.fullname LIKE '%Intensivo%' ";
		$data['caracsel']=2;
	}elseif(($_REQUEST['caracteristica'])==3){
		$WHERE .= " AND c.fullname NOT LIKE '%Intensivo%' ";
		$data['caracsel']=3;
	}

	if(!$data['dia_sel']){
		$data['dia_sel'] = $dia=array("L","M","W","J","V","S");
		//$data['dia_sel'] = array($dia[date("N")-1]);
	}

	if (!$_REQUEST['ctrlf']) 			$WHERE .= " AND	c.fullname NOT LIKE '%CTRLF%'";
	if (!$_REQUEST['instructor'])	$WHERE .= " AND	cm.fullname NOT LIKE '%instructor%'";
	if (!$_REQUEST['bridge'])			$WHERE .= " AND	cm.fullname NOT LIKE '%bridge%'";

	//Traigo todas las comisiones del período
	$comisiones	= $LMS->GetAll("SELECT c.id, c.from_courseid, c.periodo, c.startdate, c.enddate, c.shortname, c.intensivo
															FROM {$HULK->dbname}.vw_course c INNER JOIN mdl_course cm ON c.from_courseid = cm.id
															WHERE {$WHERE} ORDER BY cm.shortname,c.shortname;");
	// Si es del viejo lms traigo el modeloidviejo
	if ($data['periodo_sel']<181){
		$comision['from_courseid'] = $H_DB->GetField("h_course_modelos","viejoid",$comision['from_courseid'],"nuevoid");
	}

	//Por cada alumno obtengo las cuotas
	foreach($comisiones as $comision){
		$sipasa = "no";
		$temp1 = explode("-",$comision['shortname']);
		$temp2 = explode(".",$temp1[4]);
		unset($temp3);
		for($x=0;$x<(strlen($temp2[0])-1);$x++){
			if(in_array($temp2[0][$x],$data['dia_sel'])){
				$sipasa="si";
			}
		}

		if($sipasa=="no"){
			continue;
		}
		// Traigo todas las bajas
		$bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$comision['id']} AND cancel=0;");
		$cant_bajas = $H_DB->GetOne("SELECT COUNT(userid) FROM h_bajas WHERE comisionid={$comision['id']} AND cancel=0 AND detalle NOT LIKE '%tica por cambio de comisi%';");
		$cant_cambio_comi = $H_DB->GetOne("SELECT COUNT(userid) FROM h_bajas WHERE comisionid={$comision['id']} AND cancel=0 AND detalle LIKE '%tica por cambio de comisi%';");

		$sql = "SELECT e.userid AS id
					  FROM {$HULK->dbname}.vw_enrolados e
					  WHERE e.roleid = 5 AND e.id = {$comision['id']}";

		$enrolados_sinbajas = $LMS->GetAll($sql);

		if($bajas){
			$sql .= " AND e.userid NOT IN({$bajas});";
		}

		//Traigo todos los enrolados en esa comisión
		$enrolados = $LMS->GetAll($sql);
		$data['deudores'][$comision['id']]['from_courseid']=$comision['from_courseid'];
		$data['carreras'][$comision['from_courseid']]++;
		$data['deudores'][$comision['id']]['activos'] = count($enrolados);
		$data['deudores'][$comision['id']]['bajas'] = $cant_bajas;
		$data['deudores'][$comision['id']]['cambio_comi'] = $cant_cambio_comi;
		$data['deudores'][$comision['id']]['capacidad'] = $H_DB->GetOne("SELECT aa.capacity
																	 FROM h_academy_aulas aa INNER JOIN h_course_config cc ON aa.id = cc.aulaid
																	 WHERE cc.courseid={$comision['id']};");
		$data['deudores'][$comision['id']]['aula'] = $H_DB->GetOne("SELECT aa.name
																	 FROM h_academy_aulas aa INNER JOIN h_course_config cc ON aa.id = cc.aulaid
																	 WHERE cc.courseid={$comision['id']};");

		$data['deudores'][$comision['id']]['startdate'] = $comision['startdate'];
		$data['deudores'][$comision['id']]['enddate'] = $comision['enddate'];

		//Traigo el instructor de la comisión
		$instructor = $LMS->GetOne("SELECT GROUP_CONCAT(lastname SEPARATOR ' / ') AS inst
																FROM mdl_user u INNER JOIN {$HULK->dbname}.vw_enrolados e ON u.id=e.userid
																WHERE e.roleid=4 AND e.id={$comision['id']}");

		$data['deudores'][$comision['id']]['instructor'] = $instructor;
		foreach($enrolados_sinbajas as $enrolado){
			if(!$enrolado['id']>0) continue;
			//Verifico que cada alumno de esa comisión tenga todas las cuotas pagas
			$cuotas_alum = $H_DB->GetAll("SELECT * FROM h_cuotas
											WHERE userid={$enrolado['id']}
											AND courseid={$comision['from_courseid']} AND periodo={$comision['periodo']}
											AND cuota>0 AND cuota<={$data['cuota_sel']};");

			if($cuotas_alum){
				foreach($cuotas_alum as $cuota_alum){

					if($cuota_alum['cuota'] > 0){
						$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota_alum['insc_id']);
						if($beca>0){
							$cuota_alum['valor_cuota'] = ceil($cuota_alum['valor_cuota'] - ($cuota_alum['valor_cuota']*$beca/100));
						}
					}

					/*
					Si el curso es intensivo, el periodo del curso es 2 entonces si el mes de inicio del curso es mayo-junio entonces
					la cuota 1 del intensivo es la 3 de los normales. Si el periodo es 3 y el mes de inicio del curso es octubre o noviembre
					lo mismo, la cuota 1 es la 3 del normal y ahi en adelante.
					*/
					
					if ($comision['intensivo']==1){
						if ((substr($comision['periodo'],-1)=="2") AND (date("n",$comision['startdate'])==5 OR date("n",$comision['startdate'])==6)){
							$cuota_alum['cuota']+=2;
						}elseif ((substr($comision['periodo'],-1)=="3") AND (date("n",$comision['startdate'])==10 OR date("n",$comision['startdate'])==11)){
							$cuota_alum['cuota']+=2;
						}
					}

					if(in_array($comision['from_courseid'],array(100334,100075,100144,100071,100072,100073,100074))){
						if (substr($comision['periodo'],-1)=="2"){
							$cuota_alum['cuota']=date("n",$comision['startdate'])-2;
						}elseif(substr($comision['periodo'],-1)=="3"){
							$cuota_alum['cuota']=date("n",$comision['startdate'])-7;
						}
					}
					// Solo para 1 comisión que empieza en abril y el plan se pasa de 5 a 4 cuotas.
					if(in_array($comision['id'],array(100390,100459,100736))){
						$cuota_alum['cuota']=$cuota_alum['cuota']+1;
					}
					// Chequear que el comprobante al que está asociada la cuota no esté pendiente
					$comprobantes = $H_DB->GetAll("SELECT c.*, cc.importe AS pagado
												 FROM h_comprobantes c
												 INNER JOIN h_comprobantes_cuotas cc ON cc.comprobanteid=c.id
												 WHERE cc.cuotaid={$cuota_alum['id']};");
					if($comprobantes){
						foreach($comprobantes as $comp){
							if($comp['pendiente']==1){
								$cuota_alum['valor_pagado'] -= $comp['pagado'];
							}
						}
					}
					if($cuota_alum['valor_pagado']<$cuota_alum['valor_cuota']){
									// No tiene que ser baja para que sume a deuda
						if (!$H_DB->GetOne("SELECT DISTINCT insc_id FROM h_bajas WHERE userid={$cuota_alum['userid']} AND courseid={$cuota_alum['courseid']}
																 AND comisionid={$comision['id']}
																 AND cancel=0;")){
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['alumnos']++;
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['importe'] += $cuota_alum['valor_cuota'] - $cuota_alum['valor_pagado'];
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['valor_cuota'] += $cuota_alum['valor_cuota'];
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['valor_pagado']+= $cuota_alum['valor_pagado'];
					if ($comision['id']==100461){
						show_array($cuota_alum);
					}						}else{
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['valor_cuota'] += $cuota_alum['valor_pagado'];
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['valor_pagado']+= $cuota_alum['valor_pagado'];							
						}
					}else{
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['valor_cuota'] += $cuota_alum['valor_cuota'];
							$data['deudores'][$comision['id']][$cuota_alum['cuota']]['valor_pagado']+= $cuota_alum['valor_pagado'];
					}

				}
			}else{
				//El alumno es deudor en todas las cuotas que estoy buscando
				$data['enrol_LMS']++;
			}
		}
	}
	
	//show_array($data['deudores']);
	
	
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function enrol_lms(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

	$curso		= $_REQUEST['curso'];
	$periodo	= $_REQUEST['periodo'];

	$menuroot['ruta'] = array("Informe de deudores"=>"reportes.php?v=deudores","Alumnos enrolados desde el LMS del periodo $periodo:"=>"#");

	$alumnos = $H_DB->GetAll("SELECT * FROM vw_enrolados vw_e WHERE vw_e.periodo={$periodo} AND vw_e.roleid=5 AND vw_e.academy=1 ORDER BY vw_e.userid;");

	foreach($alumnos as $alumno){
		unset($tiene_cuota);
		$tiene_cuota = $H_DB->record_exists_sql("SELECT * FROM h_cuotas
	 									WHERE userid={$alumno['userid']}
										AND courseid={$alumno['modelid']} AND periodo={$alumno['periodo']}
										AND insc_id NOT IN(SELECT DISTINCT b.insc_id
																 FROM h_bajas b
																 WHERE b.userid={$alumno['userid']}
																 AND b.comisionid={$alumno['id']}
																 AND b.cancel=0);");
		if(!$tiene_cuota){
			$data['alumnos_lms'][] = $alumno;
		}

	}
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);


}
function d_cuota(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$data['comision'] = $comision = $_REQUEST['comision'];
		$data['cuota'] = $cuota	= $_REQUEST['cuota'];

		//Traigo el instructor de la comisión
		$instructor = $LMS->GetOne("SELECT GROUP_CONCAT(CONCAT(lastname, ', ',firstname) SEPARATOR ' / ') AS inst
																FROM mdl_user u INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																WHERE ra.roleid=4 AND ctx.instanceid={$comision}");

		$data['instructor'] = $instructor;

		// Traigo todas las bajas
		$bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$comision} AND cancel=0;");

		$sql = "SELECT u.id, u.lastname,u.firstname, u.username,u.phone1,u.phone2,u.email,
						e.modelid, e.periodo, e.id AS courseid
				  FROM {$HULK->dbname}.vw_enrolados e
					INNER JOIN mdl_user u ON e.userid = u.id
				WHERE e.roleid = 5 AND e.id = {$comision}";

		if($bajas){
			$sql .= " AND e.userid NOT IN({$bajas});";
		}

		// Traigo todos los enrolados en esa comisión
		$enrolados = $LMS->GetAll($sql);

		foreach($enrolados as $enrolado){

			// Verifico que cada alumno de esa comisión tenga todas las cuotas pagas
			$cuota_alum = $H_DB->GetRow("SELECT * FROM h_cuotas
										 WHERE userid={$enrolado['id']}
										 AND courseid={$enrolado['modelid']} AND periodo={$enrolado['periodo']}
										 AND cuota={$cuota}
										 AND insc_id NOT IN(SELECT DISTINCT insc_id
																				FROM h_bajas
																				WHERE userid={$enrolado['id']}
																				AND courseid={$enrolado['modelid']}
																				AND periodo={$enrolado['periodo']}
																				AND cancel=0);");
																				
		
			if($cuota_alum){

				if($cuota_alum['cuota'] > 0){
					$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota_alum['insc_id']);
					if($beca>0){
						$cuota_alum['valor_cuota'] = ceil($cuota_alum['valor_cuota'] - ($cuota_alum['valor_cuota']*$beca/100));
					}
				}

				// Chequear que el comprobante al que está asociada la cuota no esté pendiente
				$comprobantes = $H_DB->GetAll("SELECT c.*, cc.importe AS pagado
																			 FROM h_comprobantes c
																			 INNER JOIN h_comprobantes_cuotas cc ON cc.comprobanteid=c.id
																			 WHERE cc.cuotaid={$cuota_alum['id']};");
			}
			if($comprobantes){
				foreach($comprobantes as $comp){
					if($comp['pendiente']==1){
						$cuota_alum['valor_pagado'] -= $comp['pagado'];
					}
				}
			}

			if($cuota_alum['valor_pagado']<$cuota_alum['valor_cuota']){
				$enrolado['deuda'] = $cuota_alum['valor_cuota'] - $cuota_alum['valor_pagado'];
				$data['deudores'][] = $enrolado;
			}
		}
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);


}
function convenios_a_vencer(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$data['convenios']				= $LMS->GetAll("SELECT a.name academy, c.name, c.id, c.shortname, ac.timestart, ac.timeend,
																							(SELECT COUNT(comi.id)
														 					        	FROM mdl_convenio_course cc
														 			  	         	INNER JOIN mdl_course cm ON cc.courseid=cm.id
														 			             	INNER JOIN mdl_course comi ON cm.id=comi.from_courseid
																	             	WHERE comi.periodo!={$HULK->periodo}
                                               	AND cc.convenioid=c.id) AS closes,
                                              (SELECT COUNT(comi.id) AS opens
														 					         	FROM mdl_convenio_course cc
														 			  	          INNER JOIN mdl_course cm ON cc.courseid=cm.id
														 			              INNER JOIN mdl_course comi ON cm.id=comi.from_courseid
																	              WHERE comi.periodo={$HULK->periodo}
                                                AND cc.convenioid=c.id) AS opens
																							FROM mdl_convenios c
																				 			INNER JOIN mdl_proy_academy_convenio ac ON ac.convenioid=c.id
																							INNER JOIN mdl_proy_academy a ON a.id=ac.academyid
																							WHERE ac.timeend > ".time()." AND ac.timeend < ".(time()+(86400*$HULK->venc_convenio))."
																							AND a.id!=50 AND a.deleted=0
																							ORDER BY academy, c.name;");


	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function charlas(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		@set_time_limit(1800); // 30 minutes
		@ini_set('memory_limit', '64M');

		$data['xls'] = $_REQUEST['xls'];

		$data['result'] = $H_DB->GetAll("SELECT * FROM tmp_participantes_charlas ORDER BY id DESC;");


		$data['charlas'] = array(1 => 'Seminario “Fraudes en E-commerce”. Gratuito',
														 2 => 'Charlas Informativas de CCNA 1. Gratuito',
														 3 => 'Charlas Informativas de DBA 1. Gratuito',
														 4 => 'Charlas informativas de Java Inicial. Gratuito',
														 5 => 'Seminario “Telefonía IP”. Gratuito',
														 6 => 'Charlas Informativas de CCNA 1. Gratuito');


		if ($data['xls']=="Exportar"){
			$data['xlsname']="inscriptos-charlas";
			$view->Load('xls',$data);
			$view->Load("reportes\charlas", $data);
			die();
		}
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function inscriptos(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		@set_time_limit(1800); // 30 minutes
		@ini_set('memory_limit', '64M');

		$tipo 		= $_REQUEST['t'];
		$academias	= $_REQUEST['academias'];
		$periodos	= $_REQUEST['periodos'];

		if(!$tipo) $tipo="basico";

		$menuroot['ruta'] = array("Reporte de inscriptos ({$tipo})"=>"reportes.php?v=inscriptos&t={$tipo}");

		$v .= "_".$tipo;

		if($academias){
			$data['acad_sel'] = $academias;
			$WHERE .= " AND c.academyid IN(";
			foreach($academias as $academy){
				$WHERE .= "{$academy},";
			}
			$WHERE = substr($WHERE,0,strlen($WHERE)-1).")";
		}else{
			$data['acad_sel'] = $LMS->GetRow("SELECT MIN(a.id) as ids
																FROM mdl_proy_academy a
																WHERE a.deleted=0
																GROUP BY a.deleted ORDER BY shortname");

			$academys	= $LMS->GetRow("SELECT MIN(a.id) as ids
																FROM mdl_proy_academy a
																WHERE a.deleted=0
																GROUP BY a.deleted ORDER BY shortname");

			$WHERE = " AND c.academyid IN ({$academys['ids']}) ";
		}
		if($_POST['pais']!=""){
			$data['paisel']=$paisel=$_POST['pais'];
		}else{
			$data['paisel']=array();
		}

		if($periodos){
			$data['periodos_sel'] = $periodos;
			$WHERE .= " AND c.periodo ={$periodos} ";
		}else{
			// Si no selccionó periodo selecciono el ultimo
			$data['periodos_sel'] = $HULK->periodo;
			$WHERE .= " AND c.periodo =".$HULK->periodo;
		}
		$data['academias_user'] = $LMS->getAcademys();
		$data['periodos_user'] = $LMS->getPeriodos();
		$data['carrerasb']=$LMS->GetAll("SELECT 		cr2.id as idcr,cr2.shortname
															FROM mdl_course cr
															INNER JOIN mdl_course cr2 ON cr2.id=cr.from_courseid
															group by cr2.id
															order by cr2.shortname ASC");
		if($_POST['carrera']){
				$data['carsel']=$_POST['carrera'];
				$carsel=implode(",",$_POST['carrera']);
				$WHERECA="AND cm.id IN ({$carsel})";
		}
		else{
				$data['carsel']=array(0);
		}

		// Traigo todas las comisiones
		$rows = $LMS->GetAll("SELECT c.id, c.from_courseid, c.shortname, c.startdate, c.enddate, cm.fullname model
													FROM {$HULK->dbname}.vw_course c INNER JOIN mdl_course cm ON c.from_courseid=cm.id
													WHERE
													c.periodo > 0
													{$WHERE}
													{$WHERECA}
													ORDER BY c.fullname;");

		foreach($rows as $row):

			$row['instructor'] = $LMS->GetOne("SELECT GROUP_CONCAT(CONCAT(lastname, ', ',firstname) SEPARATOR ' / ') AS inst
																				 FROM mdl_user u
																				 INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																				 INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																				 WHERE ra.roleid=4 AND ctx.instanceid={$row['id']};");

			// Traigo las bajas
			$bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$row['id']} AND cancel=0;");

			if($bajas){
				$WHERE2 = " AND u.id NOT IN ({$bajas}) ";
			}else{
				$WHERE2 = "";
			}

			$row['alumnos'] = $LMS->GetAll("SELECT 
												u.id AS uid, CONCAT(u.lastname, ', ', u.firstname) AS alumno,
												u.username, u.email, u.acid, u.phone1, u.phone2, 
													(	select distinct co.detalle 
														from {$HULK->dbname}.h_comprobantes co 
															INNER JOIN {$HULK->dbname}.h_comprobantes_cuotas cc ON co.id = cc.comprobanteid 
															INNER JOIN {$HULK->dbname}.h_cuotas cu ON cc.cuotaid = cu.id
														where
															cu.courseid={$row['from_courseid']} and 
															co.detalle like '%Promo%' and 
															cu.cuota=1 and 
															co.concepto=3 and
															cu.userid=u.id) detalle, timestart
											FROM {$HULK->dbname}.vw_enrolados enr
												INNER JOIN moodle.mdl_user u  ON u.id=enr.userid
											WHERE enr.id={$row['id']}
												AND enr.roleid = 5
												{$WHERE2}
											ORDER BY u.lastname, u.firstname;");
	
			$row['capacidad'] = $H_DB->GetOne("SELECT aa.capacity
																				 FROM h_academy_aulas aa INNER JOIN h_course_config cc ON aa.id = cc.aulaid
																				 WHERE cc.courseid={$row['id']};");
			$data['rows'][] = $row;
		endforeach;

		$data['carreras'] = $LMS->GetAll("SELECT cm.fullname, cm.shortname, COUNT(*) AS alumnos
																			FROM {$HULK->dbname}.vw_enrolados e
																			INNER JOIN mdl_course cm ON e.modelid=cm.id
																			INNER JOIN {$HULK->dbname}.vw_course c ON c.id=e.id
																			WHERE (EXISTS(SELECT 1 AS Not_used FROM {$HULK->dbname}.vw_gradebook
																										WHERE c.id = vw_gradebook.courseid
																										AND e.userid = vw_gradebook.userid) = 0)
																										AND e.roleid = 5
																			{$WHERE}
																			GROUP BY cm.fullname, cm.shortname
																			ORDER BY cm.fullname;");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function xls(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		//show_array($_REQUEST);die();

		$name = $_REQUEST['name-xls'];
	    $table = str_replace("\\", "", $_REQUEST['table-xls']);

		$view->Load('xls',array("xlsname"=> $name,"table" => $table));
		die();
}

function ekits(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


	$menuroot['ruta'] = array("Reporte de eKits"=>"reportes.php?v=ekits");

	if($_POST['pais']!=""){
		$data['paisel']=$paisel=$_POST['pais'];
	}else{
		$data['paisel']=array();
	}


	if($_POST['academy']){
		$data['acadsel']=$_POST['academy'];
		$acadselec=implode(",",$_POST['academy']);
	}else{
		$acadselec="1,2,3";
		$data['acadsel']=array(1,2,3);
	}


	if($_REQUEST['periodos']){
		$data['periodos_sel'] = $_REQUEST['periodos'];
		$periodoselec=implode(",",$_POST['periodos']);
	}else{
		$data['periodos_sel'] = array($HULK->periodo);
		$periodoselec=$HULK->periodo;
	}

	$rows = $LMS->GetAll("SELECT u.id AS userid, u.username, CONCAT(u.firstname, ' ', u.lastname) AS alumno,
							c.id AS comiid, c.fullname AS comision, a.name AS academy, cm.fullname AS modelo,
							cm.id AS modelid
							FROM mdl_user u
							INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
							INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
							INNER JOIN mdl_course c ON ctx.instanceid=c.id
							INNER JOIN mdl_proy_academy a ON c.academyid=a.id
							INNER JOIN mdl_course cm ON c.from_courseid=cm.id
							LEFT JOIN mdl_grade_items gi ON gi.courseid=c.id
							WHERE (gi.itemname LIKE '%e-Kit')
							AND ra.roleid=5 AND cm.fullname NOT LIKE '%instructores%'
							AND c.periodo IN ({$periodoselec})
							AND a.id IN ($acadselec)
								ORDER BY c.id, u.lastname;");

	foreach($rows as $row){
		$pago = $H_DB->GetOne("SELECT COUNT(c.id) as pago
														 FROM h_cuotas c
														 WHERE  c.cuota=0 AND c.courseid={$row['modelid']}
														 AND c.userid={$row['userid']} AND c.libroid > 0
														 AND c.valor_cuota=c.valor_pagado;");
		$row['pago'] = $pago;
		$recursa = $LMS->GetRow("SELECT ason.onlinetext, c.id AS courseid, c.fullname
								 FROM mdl_course c
								 INNER JOIN mdl_grade_items gi ON gi.courseid=c.id
								 INNER JOIN mdl_assign_submission asub ON asub.assignment = gi.iteminstance
							     INNER JOIN mdl_assignsubmission_onlinetext ason ON ason.submission = asub.id
								 WHERE gi.itemname LIKE '%e-Kit' AND ason.onlinetext!=''
									 AND c.from_courseid={$row['modelid']} AND asub.userid={$row['userid']}
									 LIMIT 0,1;");

		if($recursa['onlinetext'] != ""){
			$row['recursa'] = $recursa['courseid'];
			$row['onlinetext'] = $recursa['onlinetext'];
		}else{
			$row['recursa'] = 0;
		}
		$data['rows'][$row['comision']]['users'][] = $row;
		$data['rows'][$row['comision']]['modelo'] = $row['modelo'];
		$data['rows'][$row['comision']]['comiid'] = $row['comiid'];
	}
	$data['academias'] = $LMS->GetAll("SELECT DISTINCT a.name AS academy, cm.fullname AS modelo, COUNT(*) AS alumnos
																		 FROM mdl_user u
																		 INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																		 INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																		 INNER JOIN mdl_course c ON ctx.instanceid=c.id
																		 INNER JOIN mdl_proy_academy a ON c.academyid=a.id
																		 INNER JOIN mdl_course cm ON c.from_courseid=cm.id
																		 LEFT JOIN mdl_grade_items gi ON gi.courseid=c.id
																		 WHERE (gi.itemname LIKE '%e-Kit')
																		 AND ra.roleid=5 AND cm.fullname NOT LIKE '%instructores%'
																		AND c.periodo IN ({$periodoselec})
																		AND a.id IN ($acadselec)
																		 GROUP BY academy, modelo
																		 ORDER BY academy, modelo;");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function ekit_edit(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
			if(!$H_USER->has_capability('ekit/editar')):
			show_error("Error","No tiene permisos para editar ekits");
			die();
		endif;

		if($_POST['comision']){

			$gg['assignment'] = $LMS->GetOne("SELECT ass.id
											FROM mdl_assign ass
											WHERE ass.name LIKE '%e-Kit'
											AND ass.course={$_POST['comision']}	LIMIT 0,1;");
		//	$gg['usermodified'] = 38453;
		//	$gg['timecreated'] 	= time();
		//	$gg['timemodified'] = time();
			$gg['onlineformat'] = 1;
			foreach($_POST['onlinetext'] as $gg['userid']=>$gg['onlinetext']){
				$gg['submission'] = $LMS->GetOne("SELECT sub.id FROM mdl_assign_submission sub WHERE sub.assignment ={$gg['assignment']} AND sub.userid={$gg['userid']}	LIMIT 0,1;");
				if (!$gg['submission']>0){
					$gg['submission'] = $LMS->insert('mdl_assign_submission',array("assignment"=>$gg['assignment'],"userid"=>$gg['userid'],"timecreated"=>time(),"timemodified"=>time(),"status"=>"submitted","latest"=>1));
				}	
				//if($gg['onlinetext']!=""){
					$gg['onlinetext']= str_replace('\"','"',$gg['onlinetext']);
					$gg['onlinetext']= utf8_decode($gg['onlinetext']);
					if($LMS->record_exists_sql("SELECT * FROM `moodle`.mdl_assignsubmission_onlinetext WHERE assignment = {$gg['assignment']} AND submission = {$gg['submission']}")){
						$LMS->update('mdl_assignsubmission_onlinetext',$gg,"assignment = {$gg['assignment']} AND submission = {$gg['submission']}");
					}else{
						if($gg['onlinetext']!=""){
						$LMS->insert('mdl_assignsubmission_onlinetext',$gg);
						}
					}
					$LMS->update('mdl_assign_submission',array("status" => "submitted"),"id = {$gg['submission']}");					
				//}
			}
		}
		redireccionar("reportes.php?v=ekits");
		die();
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function bajas(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		$H_USER->require_capability('reportes/bajas');


		$startdate = $data['startdate'] = $_REQUEST['startdate'];
		$enddate = $data['enddate'] = $_REQUEST['enddate'];

		$startdate = explode("-", $startdate);
		$enddate = explode("-", $enddate);

		$desde = mktime(0,0,0, $startdate[1],$startdate[0]-1,$startdate[2]);
		$hasta = mktime(23,59,59, $enddate[1],$enddate[0]+1,$enddate[2]);


		if($_REQUEST['periodos']){
		    $data['periodos_sel'] = $_REQUEST['periodos'];
            $WHERE .= " AND periodo IN(".implode(",", $_REQUEST['periodos']).")";
        }else{
            $data['periodos_sel'] = array(0);
            //$WHERE .= "AND c.periodo=$HULK->periodo";
        }

        if($_REQUEST['carrera']){
            $data['carsel']=$_REQUEST['carrera'];
            $WHERE2 .= " AND courseid IN(".implode(",", $_REQUEST['carrera']).")";
        } else{
            $data['carsel']=array(0);
        }

		$data['bajas'] = $H_DB->GetAll("SELECT * FROM h_bajas WHERE cancel=0 {$WHERE}
														AND date BETWEEN {$desde} AND {$hasta} ;");

		$data['bajas_cancel'] = $H_DB->GetAll("SELECT * FROM h_bajas WHERE cancel=1 {$WHERE}
														AND date BETWEEN {$desde} AND {$hasta};");

		$data['academias_user'] = $LMS->getAcademys();

		$data['periodos_user'] = $LMS->GetAll("SELECT DISTINCT periodo
											 FROM mdl_course c
											 WHERE c.periodo != '' AND c.periodo > 0
											 ORDER BY periodo DESC;");

		$data['carrerlist']=$LMS->GetAll("SELECT 		cr2.id as idcr,cr2.shortname
															FROM mdl_course cr
															INNER JOIN mdl_course cr2 ON cr2.id=cr.from_courseid
															WHERE cr2.fullname NOT LIKE '%Instructores%'
															AND cr2.fullname NOT LIKE '%Bridge Course%'
															AND cr2.fullname NOT LIKE '%Control F%'
															AND cr.from_courseid != 195
															GROUP BY cr2.id
															ORDER BY cr2.shortname ASC");
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function comp_pendientes(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

	$H_USER->require_capability('reportes/comp_pend');
	$menuroot['ruta'] = array("Comprobantes pendientes de cobro"=>"#");

	if (!$_REQUEST['startdate']) $_REQUEST['startdate'] = date("d-m-Y",time()-2678400);
	if (!$_REQUEST['enddate']) $_REQUEST['enddate'] = date("d-m-Y",time());

	$startdate = $data['startdate'] = $_REQUEST['startdate'];
	$enddate = $data['enddate'] = $_REQUEST['enddate'];

	$startdate = explode("-", $startdate);
	$enddate = explode("-", $enddate);

	$desde = mktime(0,0,0, $startdate[1],$startdate[0]-1,$startdate[2]);
	$hasta = mktime(23,59,59, $enddate[1],$enddate[0]+1,$enddate[2]);

	$data['rows'] = $H_DB->GetAll("SELECT * FROM h_comprobantes WHERE pendiente=1 AND anulada=0 AND `date` BETWEEN {$desde} AND {$hasta} ORDER BY `date` DESC;");


	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function certificados(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
			$data['academias_user'] = $LMS->getAcademys();
		$desde = $data['fecha1f'] = $_REQUEST['fecha1'];
		$hasta = $data['fecha2f'] = $_REQUEST['fecha2'];
		$desde = explode('-',$desde);
		$desde = mktime(0,0,0, $desde[1],$desde[0]-1,$desde[2]);
		$hasta = explode('-',$hasta);
		$hasta = mktime(23,59,59, $hasta[1],$hasta[0]+1,$hasta[2]);

		if(isset($_REQUEST[academias])){
			$data['acad_sel'] = $_REQUEST['academias'];
			$as=implode(",",$_REQUEST[academias]);
			$ac="AND a.id IN({$as})";
		}
		else{
			$data['acad_sel'] = array();
			$ac=" ";
		}
		$data['traer']=$LMS->GetAll("SELECT u.firstname,u.id, u.lastname,c.timecreate, u2.firstname AS nombre,
		u2.lastname AS apellido,cr.shortname as comision, u3.username AS DNI,cm.fullname  AS completo
									 FROM mdl_user u
									 INNER JOIN mdl_certificates c ON u.id=c.userid
									 INNER JOIN mdl_course cr ON cr.id=c.courseid
									 INNER JOIN mdl_user u2 ON c.createby=u2.id
									 INNER JOIN mdl_user u3 ON u3.id=c.userid
									 INNER JOIN mdl_course cm ON cm.id=cr.from_courseid
									 INNER JOIN mdl_proy_academy a ON a.id=cr.academyid
									 WHERE timecreate BETWEEN '{$desde}' AND  '{$hasta}'
									 {$ac}");
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function encuestas(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		 //alan
		 $encuestaid=$_POST['encuestas'];
			$data['encuestas'] = $LMS->GetAll("SELECT QS.id,QS.title
			FROM mdl_questionnaire_survey as QS
			INNER JOIN mdl_course as cr ON QS.owner=cr.id
				group by QS.id ");
		if($encuestaid){
			$data['encsel']=$_POST['encuestas'];
			$usuarios=$LMS->GetAll("SELECT QS.id, QR.username,user.firstname, user.lastname, user.id as iduser, QR.id as idresp
								FROM mdl_questionnaire_response as QR
								INNER JOIN mdl_questionnaire_survey as QS ON QR.survey_id=QS.id
								INNER JOIN mdl_course as cr ON cr.id=QS.owner
								INNER JOIN mdl_user as user ON QR.username=user.id
 								where QS.id={$encuestaid}
			");

			$data['preguntas']=$LMS->GetAll("SELECT QQ.content, QQT.response_table, QQ.id as idpregunta, QQT.type
									FROM mdl_questionnaire_question as QQ
									INNER JOIN mdl_questionnaire_survey as QS ON QS.id=QQ.survey_id
									INNER JOIN mdl_questionnaire_question_type as QQT ON QQT.typeid=QQ.type_id

									where QS.id={$encuestaid} AND QQT.typeid NOT IN (99,100)
			");
			foreach($usuarios as $user):
				foreach($data['preguntas'] as $pregunta):
					if(($pregunta['response_table'] == "response_text")||($pregunta['response_table'] == "response_other")){
							$data['respuestas'][$user['username']][$pregunta['idpregunta']]=$LMS->GetOne("SELECT QRT.response, QR.username
									FROM mdl_questionnaire_response QR
									INNER JOIN mdl_questionnaire_question QQ ON QR.survey_id=QQ.survey_id
									INNER JOIN mdl_questionnaire_{$pregunta['response_table']} QRT ON QRT.question_id=QQ.id
									where QR.username={$user['username']} AND QQ.id={$pregunta['idpregunta']} AND QRT.response_id={$user['idresp']}
						");


					}elseif($pregunta['response_table'] == "response_date"){
						$aux2 .= "-".$LMS->GetOne("SELECT QRT.response, QR.username
									FROM mdl_questionnaire_response QR
									INNER JOIN mdl_questionnaire_question QQ ON QR.survey_id=QQ.survey_id
									INNER JOIN mdl_questionnaire_{$pregunta['response_table']} QRT ON QRT.question_id=QQ.id
									where QR.username={$user['username']} AND QQ.id={$pregunta['idpregunta']} AND QRT.response_id={$user['idresp']}
									");
						$aux3=explode("-",$aux2);
						$data['respuestas'][$user['username']][$pregunta['idpregunta']]=$aux3[3]."-".$aux3[2]."-".$aux3[1];
						$aux2="";

					}elseif(($pregunta['response_table'] == "resp_single")||($pregunta['response_table'] == "resp_multiple")){
						$data['respuestas'][$user['username']][$pregunta['idpregunta']]=$LMS->GetOne("SELECT QQC.content as Respuesta
							FROM mdl_questionnaire_response QR
							INNER JOIN mdl_questionnaire_{$pregunta['response_table']} QRS ON QRS.response_id=QR.id
							INNER JOIN mdl_questionnaire_quest_choice QQC ON QQC.id=QRS.choice_id
							INNER JOIN mdl_questionnaire_question QQ on QQ.id=QRS.question_id
							WHERE QR.username={$user['username']} AND QQ.id={$pregunta['idpregunta']} AND QRS.response_id={$user['idresp']}
							");
					}elseif($pregunta['response_table']== "response_rank"){
						$content=$LMS->GetAll("SELECT QQC.content, QQC.id
						FROM mdl_questionnaire_quest_choice as QQC
						WHERE QQC.question_id={$pregunta['idpregunta']}");
							foreach($content as $contenido):

								$aux .= " - ".$LMS->GetOne("SELECT CONCAT('{$contenido['content']}',' ',QRK.rank+1) as Respuesta
									FROM mdl_questionnaire_response QR
									INNER JOIN mdl_questionnaire_response_rank QRK ON QRK.response_id=QR.id
									where QR.username={$user['username']} AND QRK.choice_id={$contenido['id']}	")."<br />";
							endforeach;
							$data['respuestas'][$user['username']][$pregunta['idpregunta']]= $aux;
							$aux="";

					}elseif($pregunta['response_table'] == "response_bool"){
							$responsebool=$LMS->GetOne("SELECT  QRB.choice_id as Respuesta
							FROM mdl_questionnaire_response_bool as QRB
							inner join mdl_questionnaire_response as QR on QRB.response_id=QR.id
							inner join mdl_questionnaire_survey as QS on QR.survey_id=QS.id
							inner join mdl_user as U on QR.username=U.id
							inner join mdl_questionnaire_question as QQ on QQ.survey_id=QS.id
							where QR.username={$user['username']} AND QQ.id={$pregunta['idpregunta']}
							");
								if($responsebool=="y"){
							$data['respuestas'][$user['username']][$pregunta['idpregunta']]="SI";
								}elseif($responsebool=="n"){
								$data['respuestas'][$user['username']][$pregunta['idpregunta']]="NO";
								}
					}

				endforeach;
			endforeach;
		}
		//alan
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function deudores_comi(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		@set_time_limit(1800); // 30 minutes
		@ini_set('memory_limit', '64M');

		$data['academias_user'] = $LMS->getAcademys();

		$data['periodos_user'] = $LMS->getPeriodos();

		$data['periodos_sel'] = array(0);
		$data['acad_sel'] = array(0);

		//Por curso
		$data['modelos']	= $LMS->GetAll("SELECT DISTINCT c.id, c.fullname as name, ac.convenioid
																			FROM mdl_course c, mdl_proy_academy_convenio ac
																			WHERE ac.convenioid
																			IN (
																					SELECT mdl_convenio_course.convenioid
																					FROM mdl_convenio_course
																					WHERE mdl_convenio_course.courseid = c.id
																			)
																			AND c.academyid = 0
																			AND ((ac.timeend =0) OR (ac.timeend >= '.time().' ))
																			AND c.fullname NOT LIKE '%Instructor%'
																			AND c.fullname NOT LIKE '%Bridge%'
																			AND c.fullname NOT LIKE '%Control F%'
																			ORDER BY fullname;");

		$stage = $_REQUEST['stage'];

		switch($stage){

			case 1:

				$data['periodo_sel'] = $_REQUEST['periodos'];
				$data['modelo_sel']  = $_REQUEST['modelo'];
				$data['cuota_sel'] 	 = $_REQUEST['cuota'];
				$data['acad_sel'] 	 = $_REQUEST['academias'];


				if($data['acad_sel']){
					$WHERE = " AND c.academyid IN(";
					foreach($data['acad_sel'] as $academy){
						$WHERE .= "{$academy},";
					}
					$WHERE = substr($WHERE,0,strlen($WHERE)-1).")";
				}else{
					$data['acad_sel'] = array(0);
				}

				if($data['periodo_sel']){
					$WHERE .= "AND c.periodo={$data['periodo_sel']} ";
				}else{
					$data['periodos_sel'] = array(0);
				}

				if($_REQUEST['modelo']){
					$WHERE .= "AND c.from_courseid={$data['modelo_sel']} ";
				}

				//Traigo todas las comisiones del período
				$comisiones	= $LMS->GetAll("SELECT c.id, c.from_courseid, c.periodo, c.startdate, c.enddate, c.shortname
																		FROM mdl_course c INNER JOIN mdl_course cm ON c.from_courseid = cm.id
																		WHERE
																		cm.fullname NOT LIKE '%Instructor%'
																		{$WHERE}
																		AND cm.fullname NOT LIKE '%Instructor%'
																		AND cm.fullname NOT LIKE '%Bridge%'
																		AND cm.fullname NOT LIKE '%Control F%';");

				//Por cada alumno obtengo las cuotas
				foreach($comisiones as $comision){

					// Traigo todas las bajas
					$bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$comision['id']} AND cancel=0;");

					$sql = "SELECT u.id
									FROM mdl_role_assignments ra
									INNER JOIN mdl_user u ON ra.userid = u.id
									INNER JOIN mdl_context ctx ON ra.contextid = ctx.id
									WHERE ra.roleid = 5 AND ctx.instanceid = {$comision['id']}
									ORDER BY u.lastname, u.firstname";

					if($bajas){
						$sql .= " AND u.id NOT IN({$bajas});";
					}

					//Traigo todos los enrolados en esa comisión
					$enrolados = $LMS->GetAll($sql);

					foreach($enrolados as $enrolado){
						//Verifico que cada alumno de esa comisión tenga todas las cuotas pagas
						$cuotas_alum = $H_DB->GetAll("SELECT * FROM h_cuotas
																					WHERE userid={$enrolado['id']}
																					AND courseid={$comision['from_courseid']} AND periodo={$comision['periodo']}
																					AND cuota>0 AND cuota<={$data['cuota_sel']}
																					AND insc_id NOT IN(SELECT DISTINCT insc_id
																														 FROM h_bajas
																														 WHERE userid={$enrolado['id']}
																														 AND courseid={$comision['from_courseid']}
																														 AND periodo={$comision['periodo']}
																														 AND cancel=0);");

						if($cuotas_alum){
							foreach($cuotas_alum as $cuota_alum){
								// Chequear que el comprobante al que está asociada la cuota no esté pendiente
								$comprobantes = $H_DB->GetAll("SELECT c.*, cc.importe AS pagado
																							 FROM h_comprobantes c
																							 INNER JOIN h_comprobantes_cuotas cc ON cc.comprobanteid=c.id
																							 WHERE cc.cuotaid={$cuota_alum['id']};");
								if($comprobantes){
									foreach($comprobantes as $comp){
										if($comp['pendiente']==1){
											$cuota_alum['valor_pagado'] -= $comp['pagado'];
										}
									}
								}
								if($cuota_alum['valor_pagado']<$cuota_alum['valor_cuota']){

									// Hay deudores, traigo todos los datos de la comision
									$data['deudores'][$comision['id']]['activos'] = count($enrolados);

									$data['deudores'][$comision['id']]['startdate'] = $comision['startdate'];
									$data['deudores'][$comision['id']]['enddate'] = $comision['enddate'];

									//Traigo el instructor de la comisión
									$instructor = $LMS->GetOne("SELECT GROUP_CONCAT(CONCAT(lastname, ', ',firstname) SEPARATOR ' / ') AS inst
																							FROM mdl_user u INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																							INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																							WHERE ra.roleid=4 AND ctx.instanceid={$comision['id']}");

									$data['deudores'][$comision['id']]['instructor'] = $instructor;

									$data['deudores'][$comision['id']]['alumnos'][$enrolado['id']][$cuota_alum['cuota']] = $cuota_alum['valor_cuota'] - $cuota_alum['valor_pagado'];
								}
							}
						}else{
							//El alumno es deudor en todas las cuotas que estoy buscando
							$data['enrol_LMS']++;
						}
					}

				}

		}

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function facturacion(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		$H_USER->require_capability('reportes/facturacion');

		$startdate = $data['startdate'] = $_REQUEST['startdate'];
		$enddate = $data['enddate'] = $_REQUEST['enddate'];

		$startdate = explode("-", $startdate);
		$enddate = explode("-", $enddate);

		if(count($startdate) == 3){
			$startdate = mktime(0, 0, 0, $startdate[1], $startdate[0], $startdate[2]);
			$enddate = mktime(23, 59, 59, $enddate[1], $enddate[0], $enddate[2]);

			$rows = $H_DB->GetAll("SELECT * FROM h_comprobantes
														 WHERE date >= {$startdate} AND date <= {$enddate}
														 ORDER BY date;");

			foreach($rows as $row){
				$dia = date("d",$row['date'])." de ".$HULK->meses[date("n",$row['date'])]." de ".date("Y",$row['date']);

				// Averiguo el periodo al que pertenece el comprobante
				$row['periodo'] = $H_DB->GetOne("SELECT periodo FROM h_cuotas c
												 INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
												 WHERE cc.comprobanteid={$row['id']}
												 LIMIT 0,1;");
				if (!$row['periodo']>0){
					$row['periodo'] = unixToPeriodo($row['date']);
				}
				// Si es NC verifico si está asociada a un RC o FC
				if($row['tipo'] == 3){
					$asoc = $H_DB->GetField("h_comprobantes", "tipo", $row['id'], "cancel");
					if($asoc != ""){
						$data['rows'][$row['takenby']][$dia][$asoc][] = $row;
					}
				}else{
					$data['rows'][$row['takenby']][$dia][$row['tipo']][] = $row;
				}
			}
		}

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function becados(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

			if ($H_USER->has_capability('reportes/becados')) {
				if($_POST['periodos']){
					$WHERE = "AND periodo = {$_POST['periodos']}";
					$WHERE2 = "WHERE periodo = {$_POST['periodos']}";
					$data['persel']=$_POST['periodos'];
					$data['meses']=substr($_POST['periodos'], -1);
				}
				if($_POST['boton']){
					$data['boton']=true;
				}

				$data['periodos'] = $LMS->GetAll("SELECT DISTINCT c.periodo
																 FROM mdl_course c
																 WHERE c.periodo != ''
																 AND c.periodo !='0'
																 GROUP BY periodo
																 ORDER BY periodo DESC;");


				$data['becados']=$H_DB->GetAll("SELECT i.courseid,i.becado, i.comisionid, i.userid FROM h_inscripcion i
											WHERE i.becado!=0
											ORDER BY courseid
				");


				$data['porcentajes']=$H_DB->GetAll("SELECT becado FROM h_inscripcion
													WHERE becado!=0
													{$WHERE}
													GROUP BY becado
													ORDER BY becado
													");
				$cursos=$H_DB->GetAll("SELECT i.courseid,i.becado, i.comisionid, i.userid FROM h_inscripcion i
				ORDER BY courseid
				");
			}
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function inscriptos_dia(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		@set_time_limit(1800); // 30 minutes
		@ini_set('memory_limit', '64M');
		$menuroot['ruta'] = array("Informe de Marketing"=>"reportes.php?v=inscriptos_dia");

		$H_USER->require_capability('reportes/inscriptos');

		if ($_REQUEST['ano']){
			$data['ano'] = $_REQUEST['ano'];
		}else{
			$data['ano'] = date("Y");
		}
		if($_REQUEST['mes']){
			$data['mes'] = $_REQUEST['mes'];
		}else{
			$data['mes'] = date("n");
		}
		if($_REQUEST['periodo']){
			$data['periodo'] = $_REQUEST['periodo'];
		}else{
			$data['periodo'] =	$HULK->periodo;
		}

		if($_REQUEST['academias']){
			$data['acad_sel'] = $_REQUEST['academias'];
			$academias	= implode(",",$_REQUEST['academias']);
		}else{
			$data['acad_sel'] = array(1,2,3);
			$academias = "1,2,3";
		}
		$data['academias_user'] = $LMS->getAcademys();


		 $sql = "SELECT  COUNT(*) as cant, c2.shortname as carrera,c.intensivo,DAY(FROM_UNIXTIME(u.timestart)) as dia, MONTHNAME(FROM_UNIXTIME(u.timestart)) as mes,
			YEAR(FROM_UNIXTIME(u.timestart)) as ano, GROUP_CONCAT(DISTINCT u.userid SEPARATOR ',') as usuarios
			FROM {$HULK->dbname}.vw_enrolados u
			INNER JOIN {$HULK->dbname}.vw_course c ON c.id=u.id
			LEFT JOIN mdl_course c2 ON c2.id =c.from_courseid
		    LEFT JOIN  {$HULK->dbname}.h_bajas bajas ON bajas.comisionid=c.id AND bajas.cancel=0 AND bajas.userid=u.userid AND bajas.detalle LIKE '%tica por cambio de comisi%'
			WHERE c.academyid IN ({$academias}) AND
			YEAR(FROM_UNIXTIME(u.timestart)) IN ({$data['ano']}) AND
			MONTH(FROM_UNIXTIME(u.timestart)) IN ({$data['mes']}) AND
			c.periodo IN ({$data['periodo']})
	    	AND bajas.userid IS NULL
			AND u.roleid = 5
			GROUP BY c2.shortname, c.intensivo, DAY(FROM_UNIXTIME(u.timestart)), MONTHNAME(FROM_UNIXTIME(u.timestart)), YEAR(FROM_UNIXTIME(u.timestart))
			ORDER BY c.intensivo,c2.shortname,u.timestart ASC;";
		//echo $sql;
		//die();
		$comparativa=$LMS->GetAll($sql);

		$sqlcambiocomision="SELECT 
								periodo, 
								DAY(FROM_UNIXTIME(date)) as dia,
								MONTHNAME(FROM_UNIXTIME(date)) as mes, 
								YEAR(FROM_UNIXTIME(date)) as ano,
								COUNT(*) as cant,
								GROUP_CONCAT(DISTINCT userid SEPARATOR ',') as usuarios
							FROM crm.h_bajas h 
							WHERE 
								YEAR(FROM_UNIXTIME(date)) IN ({$data['ano']}) AND 
								MONTH(FROM_UNIXTIME(date)) IN ({$data['mes']}) AND 
								cancel=0 AND 
								periodo IN (192)  AND 
								detalle LIKE '%tica por cambio de comisi%' 
							GROUP BY periodo, DAY(FROM_UNIXTIME(date));";
		$data['cambios'] = $LMS->GetAll($sqlcambiocomision);
		//show_array($cambiocomision);
		$intensivo= array(0=>"",1=>"_intensivo");
		foreach($comparativa as $compa){
			$data['carreras'][]= $compa['carrera'].$intensivo[$compa['intensivo']];
			$data['comparativa'][$compa['carrera'].$intensivo[$compa['intensivo']]][$compa['dia']] = $compa['cant'];

			$compa['usuarios'] = explode(',',$compa['usuarios']);
			foreach($compa['usuarios'] as $usuario){
				$origen = $H_DB->GetField("h_user_profile", "origen", $usuario, "userid");
				if($origen=="") $origen="Otros";
				$data['origenes'][$origen][$compa['dia']]++;
			}
		}
		$data['cantdias'] = date("t",mktime(0,0,0,$data['mes'],1,$data['ano']))+1;
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function inscriptos_periodo_calendario(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		@set_time_limit(1800); // 30 minutes
		@ini_set('memory_limit', '64M');
		$menuroot['ruta'] = array("Informe de inscriptos por rango de fechas"=>"reportes.php?v=inscriptos-periodo-calendario");

		$H_USER->require_capability('reportes/inscriptos');


		if($_REQUEST['startdate']){
			$data['qstartdate'] = strtotime(date($_REQUEST['startdate']));
		}else{
			$data['qstartdate']	= time()-604800;
		}
		if($_REQUEST['enddate']){
			$data['qenddate'] = strtotime(date($_REQUEST['enddate']));
			$data['qenddate'] = $data['qenddate']+43200;
		}else{
			$data['qenddate']	= time();
		}
		$WHERE .= " u.timestart >= {$data['qstartdate']}";
		$WHERE .= " AND u.timestart <= {$data['qenddate']}";

		if($_REQUEST['academias']){
			$data['acad_sel'] = $_REQUEST['academias'];
			$academias	= implode(",",$_REQUEST['academias']);
		}else{
			$data['acad_sel'] = array(1,2,3);
			$academias = "1,2,3";
		}
		$data['academias_user'] = $LMS->getAcademys();


		 $sql = "SELECT  COUNT(*) as cant, c2.shortname as carrera, GROUP_CONCAT(DISTINCT u.userid SEPARATOR ',') as usuarios
									FROM {$HULK->dbname}.vw_enrolados u
								INNER JOIN {$HULK->dbname}.vw_course c ON c.id=u.id
								INNER JOIN mdl_course c2 ON c2.id =c.from_courseid
								WHERE c.academyid IN ({$academias}) AND u.roleid = 5 AND
								{$WHERE}
								GROUP BY c2.shortname
							ORDER BY c2.shortname ASC;";
		$comparativa=$LMS->GetAll($sql);
		foreach($comparativa as $compa){
			$data['carreras'][]= $compa['carrera'];
			$data['comparativa'][$compa['carrera']] = $compa['cant'];

			$compa['usuarios'] = explode(',',$compa['usuarios']);
			foreach($compa['usuarios'] as $usuario){
				$origen = $H_DB->GetField("h_user_profile", "origen", $usuario, "userid");
				if($origen=="") $origen="Otros";
				$data['origenes'][$origen]++;

			}
		}
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function inscriptos_periodo(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
	@set_time_limit(1800); // 30 minutes
	@ini_set('memory_limit', '64M');

	$H_USER->require_capability('reportes/inscriptos');
	$menuroot['ruta'] = array("Comparativa de inscriptos"=>"reportes.php?v=inscriptos-periodo");

	if($_REQUEST['periodo']){
		$data['periodo'] = $_REQUEST['periodo'];
	}else{
		$data['periodo'] =	$LMS->GetField_sql("SELECT MAX(c.periodo) FROM mdl_course c WHERE c.periodo NOT IN('',0);");
	}

	if($_REQUEST['academias']){
		$data['acad_sel'] = $_REQUEST['academias'];
		$academias	= implode(",",$_REQUEST['academias']);
	}else{
		$data['acad_sel'] = array(1,2,3);
		$academias = "1,2,3";
	}
	$data['periodo_actual']=$periodo_actual = $HULK->periodo;
	$data['periodo_anterior']=$periodo_anterior = $HULK->periodo-10;

	$data['academias_user'] = $LMS->getAcademys();
	$data['hoy'] = time();

	$sql = "SELECT  COUNT(*) as cant, c2.shortname as carrera,c.periodo
			FROM {$HULK->dbname}.vw_enrolados u
			INNER JOIN {$HULK->dbname}.vw_course c ON u.id=c.id
			INNER JOIN mdl_course c2 ON c2.id =c.from_courseid
		    LEFT JOIN  {$HULK->dbname}.h_bajas bajas ON bajas.comisionid=c.id AND bajas.`date` <= '{$data['hoy']}'  AND bajas.cancel=0 AND bajas.userid=u.userid
			WHERE c.academyid IN ({$academias}) AND
			u.timestart <= '{$data['hoy']}' AND
			c.periodo IN ({$periodo_actual})
    	AND bajas.userid IS NULL
			AND u.roleid = 5
			GROUP BY c2.shortname
			ORDER BY c2.shortname ASC;";
	$compa_actual=$LMS->GetAll($sql);
	//echo $sql;
	//die();
	$data['hoy_anterior'] = date('U',strtotime('-1 year'));

	$sql = "SELECT  COUNT(*) as cant, c2.shortname as carrera, c.periodo
			FROM {$HULK->dbname}.vw_enrolados u
			INNER JOIN {$HULK->dbname}.vw_course c ON u.id=c.id
			INNER JOIN mdl_course c2 ON c2.id =c.from_courseid
	    LEFT JOIN  {$HULK->dbname}.h_bajas bajas ON bajas.comisionid=c.id AND bajas.`date` <= '{$data['hoy_anterior']}' AND bajas.cancel=0 AND bajas.userid=u.userid
			WHERE  c.academyid IN ({$academias}) AND
			u.timestart <= '{$data['hoy_anterior']}' AND
			c.periodo IN ({$periodo_anterior})
	    	AND bajas.userid IS NULL
				AND u.roleid = 5
			GROUP BY c2.shortname
			ORDER BY c2.shortname ASC;";
	$compa_anterior=$LMS->GetAll($sql);

	foreach($compa_actual as $compa){
		$data['comparativa'][$compa['carrera']][$compa['periodo']]['insc'] = $compa['cant'];
	}
	foreach($compa_anterior as $compa){
		$data['comparativa'][$compa['carrera']][$compa['periodo']]['insc'] = $compa['cant'];
	}

	$bajas_actual=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy']}' AND cancel=0 AND periodo IN ({$periodo_actual}) AND detalle NOT LIKE '%tica por cambio de comisi%' GROUP BY courseid;");
	$cambios_actual=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy']}' AND cancel=0 AND periodo IN ({$periodo_actual})  AND detalle LIKE '%tica por cambio de comisi%' GROUP BY courseid;");

	$bajas_anterior=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy_anterior']}' AND cancel=0 AND periodo IN ({$periodo_anterior}) AND detalle NOT LIKE '%tica por cambio de comisi%' GROUP BY courseid;");
	$cambios_anterior=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy_anterior']}' AND cancel=0 AND periodo IN ({$periodo_anterior}) AND detalle LIKE '%tica por cambio de comisi%' GROUP BY courseid;");
	
	foreach($bajas_actual as $compa){
		if($data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!=0){
			$data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['bajas'] = $compa['cant'];
		}
	}
	foreach($bajas_anterior as $compa){
		if($data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!=0){
			$data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['bajas'] = $compa['cant'];
		}
	}
	foreach($cambios_actual as $compa){
		if($data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!=0){
			$data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['cambios'] = $compa['cant'];
		}
	}
	foreach($cambios_anterior as $compa){
		if($data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!=0){
			$data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['cambios'] = $compa['cant'];
		}
	}
//	show_array($data);
//	die();
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function detalle_actualizacion(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function iva_ventas(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
			$menuroot['ruta'] = array("Reporte de IVA - Ventas"=>"#");
			$H_USER->require_capability('reportes/iva-ventas');
			if ($_POST['tipo']){
				$data['tipo']=$_POST['tipo'];
			}else{
				$data['tipo']=2;
			}
			if ($_POST['mes']){
				$data['mes']=$_POST['mes'];
			}else{
				$data['mes']=date("n");
			}
			if ($_POST['ano']){
				$data['ano']=$_POST['ano'];
			}else{
				$data['ano']=date("Y");;
			}
			if($data['tipo']==3){
				// Si es nota de credito le limito la numeración por que es la unica forma de identificar si son de recibo o no. No hay que mostrar las de recibo.
				$noncrecibo="AND (c.numero > 100000)";
			}
				$data['rows']=$H_DB->GetAll("SELECT c.*,g.name AS empresa,g.cuit,g.iva FROM h_comprobantes c
											LEFT JOIN h_grupos g ON c.grupoid=g.id
											WHERE c.tipo = {$data['tipo']}
											AND MONTH(FROM_UNIXTIME(c.date))={$data['mes']}
											AND YEAR(FROM_UNIXTIME(c.date))={$data['ano']}
										ORDER BY c.date;");
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}
function donaciones(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
			$menuroot['ruta'] = array("Donaciones"=>"#");
			$H_USER->require_capability('reportes/donaciones');

			if ($_POST['mes']){
				$data['mes']=$_POST['mes'];
			}else{
				$data['mes']=date("n");
			}
			if ($_POST['ano']){
				$data['ano']=$_POST['ano'];
			}else{
				$data['ano']=date("Y");;
			}
				$data['rows']=$H_DB->GetAll("SELECT c.*,g.name AS empresa,g.cuit,g.iva,i.becado,cu.libroid FROM h_comprobantes c
										LEFT JOIN h_grupos g ON c.grupoid=g.id
										LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.comprobanteid
										LEFT JOIN h_cuotas cu ON cu.id=cc.cuotaid
										LEFT JOIN h_inscripcion i ON i.id=cu.insc_id
										WHERE c.tipo = 2
										AND MONTH(FROM_UNIXTIME(c.date))={$data['mes']}
										AND YEAR(FROM_UNIXTIME(c.date))={$data['ano']}
										GROUP BY c.id
										ORDER BY c.date;");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}

function facturacion_ekits(){
	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
	$menuroot['ruta'] = array("Facturación eKits"=>"#");

	$data['ekits'] = $H_DB->GetAll("SELECT MONTH(FROM_UNIXTIME(comp.date)) as mes, YEAR(FROM_UNIXTIME(comp.date)) as ano,
	SUM(c.valor_cuota) as facturado, SUM(c.valor_pagado) as cobrado
	FROM h_cuotas c
	INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
	INNER JOIN h_comprobantes comp ON comp.id=cc.comprobanteid
	WHERE c.cuota=0 AND comp.pendiente=0 GROUP BY mes, ano ORDER BY ano DESC, mes DESC;");

	$view->Load('header');

	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);
}
function inscriptos_mensual(){
	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

	if($_REQUEST['periodo']){
		$data['periodo'] = $_REQUEST['periodo'];
	}else{
		$data['periodo'] =	$HULK->periodo;
	}

	$data['meses'][1] = array (11,12,1,2,3);
	$data['meses'][2] = array (1,2,3,4,5,6,7);
	$data['meses'][3] = array (6,7,8,9,10,11,12);

	if($_REQUEST['academias']){
		$data['acad_sel'] = $_REQUEST['academias'];
		$academias	= implode(",",$_REQUEST['academias']);
	}else{
		$data['acad_sel'] = array(1);
		$academias = "1";
	}

	foreach($data['acad_sel'] as $academyid){
		$menuroot['ruta'][$LMS->getField("mdl_proy_academy","name",$academyid)] = "academy.php?v=view&id=".$academyid;
	}


	$sql = "SELECT  COUNT(u.userid) as cant, c2.shortname as carrera,c.shortname as comision,c.id as courseid,c.intensivo, MONTH(FROM_UNIXTIME(u.timestart)) as mes,
			YEAR(FROM_UNIXTIME(u.timestart)) as ano, GROUP_CONCAT(DISTINCT u.id SEPARATOR ',') as usuarios, GROUP_CONCAT(DISTINCT bajas.userid SEPARATOR ',')
			FROM {$HULK->dbname}.vw_enrolados u
			INNER JOIN {$HULK->dbname}.vw_course c ON u.id=c.id
			INNER JOIN mdl_course c2 ON c2.id =c.from_courseid
		    LEFT JOIN  {$HULK->dbname}.h_bajas bajas ON bajas.comisionid=c.id AND bajas.cancel=0 AND bajas.userid=u.userid AND bajas.detalle LIKE '%tica por cambio de comisi%'
			WHERE c.academyid IN ({$academias})  AND
			c.periodo IN ({$data['periodo']})
	    	AND bajas.userid IS NULL
				AND u.roleid = 5
			GROUP BY c2.shortname, c.shortname,  MONTHNAME(FROM_UNIXTIME(u.timestart)), YEAR(FROM_UNIXTIME(u.timestart))
			ORDER BY c2.shortname,c.intensivo,u.timestart ASC;";
	$result=$LMS->GetAll($sql);
	if(count($result)){
		foreach($result as $row){
			$carreras[$row['comision']]['carrera']=$row['carrera'];
			$carreras[$row['comision']]['courseid']=$row['courseid'];
			$carreras[$row['comision']][$row['mes']]['enrolados']=$row['cant'];
		}
		foreach($carreras as $carrera){
			$cantidad[$carrera['carrera']]++;
		}
		$data['carreras']=$carreras;
		$data['cantidad']=$cantidad;
	}
	
//	show_array($data);
	
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);
}
function empresas(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		if($_REQUEST['periodos']){
			$data['periodos_sel'] = $_REQUEST['periodos'];
			$WHERE .= " AND cta.periodo IN(";
			foreach($_REQUEST['periodos'] as $periodo){
				$WHERE .= "{$periodo},";
			}
			$WHERE = substr($WHERE,0,strlen($WHERE)-1).")
			";
		}else{
			$data['periodos_sel'] = array(0);
			$WHERE .= "AND cta.periodo=$HULK->periodo";
		}

		if($_REQUEST['cursos']){
			$data['cursos_sel'] = $_REQUEST['cursos'];
			$WHERE .= " AND c.id IN(";
			foreach($_REQUEST['cursos'] as $curso){
				$WHERE .= "{$curso},";
			}
			$WHERE = substr($WHERE,0,strlen($WHERE)-1).")
			";
		}else{
			$data['cursos_sel'] = array(0);
		}

/*		$data['sql'] = "SELECT g.id, g.name as empresa,
						g.address as direccion,
						g.city as ciudad,
						g.phone as telefono,
						g.email as email,
						g.summary as datos_de_contacto,
						cta.periodo,
						GROUP_CONCAT(distinct c.shortname
						ORDER BY c.id separator ' - ') as carreras_dictadas,
						GROUP_CONCAT(distinct cta.userid ORDER BY cta.userid separator ' - ') as usuarios,
						COUNT(distinct cta.userid) as cant_usuarios,
						COUNT(cta.userid) as inscripciones
						FROM h_cuotas as cta
						INNER JOIN `veteran-moodle`.mdl_course as c on cta.courseid=c.id
						INNER JOIN h_grupos as g on cta.grupoid=g.id
						WHERE cta.cuota=1
						{$WHERE}
						GROUP BY g.name, cta.periodo";*/
		$data['sql'] = "SELECT g.id, g.name as empresa,
						g.address as direccion,
						g.city as ciudad,
						g.phone as telefono,
						g.email as email_empresa,
						g.summary as datos_de_contacto,
						cta.periodo, c.shortname, u.username, u.firstname, u.lastname, u.email, u.phone1, u.phone2
                		FROM h_cuotas as cta
						INNER JOIN vw_course as c on cta.courseid=c.id
						INNER JOIN h_grupos as g on cta.grupoid=g.id
        			    INNER JOIN moodle.mdl_user as u on cta.userid=u.id
						WHERE cta.cuota=1
						{$WHERE}
						ORDER BY empresa, u.username, cta.periodo,c.shortname";

		$sql		= $H_DB->Execute($data['sql']);
		$data['rows']		= $sql->GetRows();


	$HULK->SELF = $_SERVER['PHP_SELF']."?p={$p}&v={$v}";

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('reportes/'.$v, $data);

}


?>
