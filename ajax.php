<?php

require_once 'config.php';

$H_USER->require_login();


$f 	= $_REQUEST['f'];

$f();

function carrerasList(){
	global $HULK,$LMS,$H_DB,$H_USER;
	$academy	= $_REQUEST['academy'];
	// Elije modelo segun academia
	$convenios	= $LMS->getAcademyConvenios($academy);
	echo "<option value='0'></option>";
	foreach($convenios as $conv):
		echo "<option value=".$conv['convenioid'].">".$conv['fullname']."</option>";
	endforeach;
}

function ModelList(){
	global $HULK,$LMS,$H_DB,$H_USER;
	$carrera	= $_REQUEST['carrera'];
	$academy	= $_REQUEST['academy'];
	$periodo	= $_REQUEST['periodo'];
	// Elije modelo segun academia
	if ($periodo > 0){
		$models	= $LMS->getConvenioCourseActivos($carrera,$periodo,$academy);
	}else{
		$models	= $LMS->getConvenioCourse($carrera);
	}
	if ($models){
		echo "<option value='0'></option>";
		foreach($models as $model):
			echo "<option value=".$model['id'].">".$model['shortname']." - ".$model['fullname']."</option>";
		endforeach;
	}else{
		echo "<option value='0'>No existen comisiones {$periodo} en ninguna de las carreras del convenio.</option>";
	}
}
function ModelPeriodo(){
	global $HULK,$LMS,$H_DB,$H_USER;
	$periodo		= $_REQUEST['periodo'];

	$models	= $LMS->GetAll("SELECT c.id, c.fullname
												FROM mdl_course c
												 WHERE c.academyid = 0
												 AND c.id IN (
													SELECT cc.courseid
													FROM mdl_convenio_course cc, mdl_proy_academy_convenio ac
													WHERE ac.convenioid = cc.convenioid
													AND (ac.timeend =0 OR ac.timeend >= UNIX_TIMESTAMP() )
												 )
												 AND c.id IN (SELECT DISTINCT c1.from_courseid
												 FROM mdl_course c1 WHERE c1.periodo IN ({$periodo}))
												 ORDER BY fullname;");
	echo "<option value='0'></option>";
	foreach($models as $model):
		echo "<option value=".$model['id'].">".$model['fullname']."</option>";
	endforeach;
}
function ComiList(){
	global $HULK,$LMS,$H_DB,$H_USER;
	$curso		= $_REQUEST['curso'];

	if ($_REQUEST['periodo']){
		$periodo=$_REQUEST['periodo'];
	}else{
		$periodo=$HULK->periodo_insc;
	}

	if ($_REQUEST['academy']){
		$academy=$_REQUEST['academy'];
	}else{
		$academy=1;
	}
	if($curso==0) return false;

	$comisiones = $LMS->getAcademyCourse($academy,$periodo,$curso);
	if($comisiones){
		echo "<table class='ui-widget' style='width:100%'>";

		echo "<thead class='ui-widget-header'>
					<th>&nbsp;</th><th>Comisión</th><th>Instructor</th><th>Cupo</th><th>Vacantes</th>
					</thead>
					<tbody class='ui-widget-content'>";
		foreach($comisiones as $comi):

			$aula = $H_DB->GetRow("SELECT capacity FROM h_academy_aulas aa
														 INNER JOIN h_course_config cc ON aa.id=cc.aulaid
														 WHERE cc.courseid={$comi['id']};");

			// Traigo todas las bajas
			$bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$comi['id']} AND cancel=0;");

			if($bajas){
				$sql = " AND u.id NOT IN({$bajas})";
			}else{
				$sql = "";
			}
			$enrolados = $LMS->GetRow("SELECT COUNT(ra.id) as cantidad
																 FROM mdl_role_assignments ra
																 INNER JOIN mdl_user u ON ra.userid = u.id
																 INNER JOIN mdl_context ctx ON ra.contextid = ctx.id
																 WHERE ra.roleid = 5 AND ctx.instanceid ={$comi['id']} {$sql};");

			if($aula['capacity']>0){
				$vacantes = $aula['capacity'] - $enrolados['cantidad'];
			}else{
				$vacantes = 1;
			}

			$instructores = $LMS->getCourseInstructors($comi['id']);

			$title = "Fecha de Inicio: ".date("d-m-Y", $comi['startdate'])."\nFecha de Cierre: ".date("d-m-Y", $comi['enddate']);
			echo "<tr title='{$title}'><td><input type='radio' name='comi' value='{$comi['id']}' style='width:12px; height:12px;' class='required' OnClick='checkIntensivo({$comi['intensivo']});checkVacante({$vacantes})'></td>";
			if(strtotime(date("d-m-Y",$comi['startdate']))>=strtotime(date("d-m-Y",time()))){
				$negrita="font-weight: bold";
			}else{
				$negrita="font-weight: normal";
			}
				echo	"<td style='{$negrita}'>{$comi['course']}</td><td>";
				foreach ($instructores as $ins){
					echo $ins['fullname'];
				}
				echo "</td>";
			echo	"<td align='center'>{$aula['capacity']}</td><td align='center'>{$vacantes}</td></tr>";
		endforeach;
		echo "</tbody></table>";
	}else{
		echo "No hay comisiones creadas para esa carrera.";
	}
}

function Cuotas(){
	global $HULK,$LMS,$H_DB,$H_USER;

	$academy		= $_REQUEST['academy'];
	$curso			= $_REQUEST['curso'];
	$userid			= $_REQUEST['userid'];
	$intensivo		= $_REQUEST['intensivo'];

	if ($_REQUEST['periodo']){
		$periodoreq=$_REQUEST['periodo'];
	}else{
		$periodoreq=$HULK->periodo_insc;
	}

	$periodo		= substr($periodoreq,-1);
	$periodos		= array(1=>"periodo_uno",2=>"periodo_dos",3=>"periodo_tres");

	if($intensivo==1){
		$cuotas = $H_DB->GetRow("SELECT cuotas_intensivo FROM h_cuotas_curso WHERE courseid={$curso}");
		$cuotas = $H_DB->GetRow("SELECT cuotas_intensivo,{$periodos[$periodo]}_int FROM h_cuotas_curso WHERE courseid={$curso}");
		if ($cuotas[$periodos[$periodo]."_int"] != ""){
			$cuotas = explode("#",$cuotas[$periodos[$periodo]."_int"]);
		}else{
			$cuotas = explode("#",$cuotas['cuotas_intensivo']);
		}
	}else{
		$cuotas = $H_DB->GetRow("SELECT cuotas,{$periodos[$periodo]} FROM h_cuotas_curso WHERE courseid={$curso}");
		if ($cuotas[$periodos[$periodo]] != ""){
			$cuotas = explode("#",$cuotas[$periodos[$periodo]]);
		}else{
			$cuotas = explode("#",$cuotas['cuotas']);
		}
	}

	$c = 1;
	foreach($cuotas as $cuota):
		//echo " $ <input type='text' name='cuotas[]' id='c{$c}' onKeyup='calculateSum();' class='sumar cuota digits readonly' value='{$cuota}' style='width:50px;' readonly> ";
		//echo "<input type='hidden' name='cuotas2[]' id='cc{$c}' value='{$cuota}' />";
		// TEBAN
		echo " $ <input type='text' name='cuotas2[]' id='c{$c}' onKeyup='calculateSum();' class='sumar cuota digits readonly' value='{$cuota}' style='width:50px;' readonly> ";
		echo "<input type='hidden' name='cuotas[]' id='cc{$c}' value='{$cuota}' class='cuota2' />";
		$total += $cuota;
		$c++;
	endforeach;
	echo "<input type='hidden' name='total2' id='total2' value='{$total}' />";
}

function Libros(){
	global $HULK,$LMS,$H_DB,$H_USER;
	$academy	= $_REQUEST['academy'];
	$curso		= $_REQUEST['curso'];
	$userid		= $_REQUEST['userid'];

	$libro = $H_DB->GetRow("SELECT id, name, valor FROM h_libros WHERE modelid={$curso};");

	echo "<input type='text' name='libro' style='width:100px;' value='".$libro['name']."' disabled />";
	echo "$ <input type='text' class='sumar readonly' name='cuotas2[]' onKeyup='calculateSum();' style='width:50px;' value='".$libro['valor']."' readonly />";
	echo "<input type='hidden' name='libroid' value='".$libro['id']."' />";
	echo "<input type='checkbox' id='libro_sel' name='libro_sel' value='1' />";
	echo "$ <input type='hidden' name='cuotas[]' value='".$libro['valor']."' />";

}

function noPagos(){
	global $HULK,$LMS,$H_DB,$H_USER;

	$userid		= $_REQUEST['userid'];
	$carrera	= $_REQUEST['carrera'];

	if(!$userid OR !$carrera) return false;

	$nopagos = $H_DB->GetAll("SELECT DISTINCT c.id, c.cuota, c.grupoid, c.valor_cuota, c.valor_pagado, c.insc_id
														FROM h_cuotas c
														LEFT JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
														LEFT JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
														WHERE c.userid={$userid} AND c.courseid={$carrera}
														AND c.insc_id NOT IN (SELECT DISTINCT b.insc_id FROM h_bajas b
																									WHERE b.cancel=0)
														ORDER BY c.cuota;");

	echo "<table class='ui-widget' width='75%'>	<thead class='ui-widget-header'>
					<th>Detalle</th><th>Pendiente</th></thead><tbody class='ui-widget-content'>";

	$disabled = "";
	foreach ($nopagos as $pago){
		$pago['valor'] = $pago['valor_cuota'] - $pago['valor_pagado'];
		$grupo = $pago['grupoid'];

		if ($pago['cuota']>0){
			$beca = $H_DB->GetField("h_inscripcion", "becado", $pago['insc_id']);
			if($beca>0){
				$pago['valor_cuota'] = ceil($pago['valor_cuota'] - ($pago['valor_cuota']*$beca/100));
			}
			$pago['valor'] = $pago['valor_cuota'] - $pago['valor_pagado'];
		}

		if($pago['valor'] > 0){
			if ($pago['cuota']==0){
				echo "<tr><td>".$H_DB->GetField('h_libros','name',$carrera, 'modelid')."</td>";
				echo "<td class='bg-danger' align='center'>
							<input id='chk_kit' type='checkbox' name='cuotas[]' onClick='calculateSum();' value='{$pago['id']}' />$ <span id='kit'>{$pago['valor']}</span>
							</td></tr>";
			}else{
				echo "<tr><td>Cuota ".$pago['cuota']."</td>";
				echo "<td class='bg-danger' align='center'>
							$ <span id='{$pago['id']}'>{$pago['valor']}</span>
							</td></tr>";

				$total += $pago['valor'];
				echo "<input type='hidden' name='cuotas[]' value='{$pago['id']}' />";

			}
		}
	}
	echo "<tr><td><b>Total: </b></td><td align='center'>$ <input type='text' name='total' id='total' value='{$total}' style='width:70px;' align='right' readonly /></td></tr>";
	echo "</tbody></table>";
	if($grupo > 0){
		echo "<span id='solo-fc' style='display:none;'>SI</span>";
	}
	echo "<input type='hidden' name='total_c' id='total_c' value='{$total}' />";

	return true;
}

function pagos(){
	global $HULK,$LMS,$H_DB,$H_USER;

	$userid		= $_REQUEST['userid'];
	$carrera	= $_REQUEST['carrera'];

	if(!$userid OR !$carrera) return false;

	$comprobantes = $H_DB->GetAll("SELECT DISTINCT comp.id, comp.numero, comp.importe, comp.tipo, c.grupoid
																 FROM h_cuotas c
																 INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																 INNER JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
																 WHERE comp.userid={$userid} AND c.courseid={$carrera}
																 AND c.insc_id NOT IN(SELECT DISTINCT insc_id FROM h_bajas
																											WHERE cancel=0 AND userid={$userid});");


	echo "<table class='ui-widget' width='75%'>	<thead class='ui-widget-header'>
					<th>Comprobante</th><th>Importe</th></thead><tbody class='ui-widget-content'>";

	foreach ($comprobantes as $comp){
		echo "<tr><td>".$comp['puntodeventa']."-".sprintf("%08d",$comp['numero'])."</td><td align='right'>$ ".number_format($comp['importe'],2,',','.')."</td></tr>";
		$total += $comp['importe'];
		echo "<input type='hidden' name='comprobantes[]' value='{$comp['id']}' />";
		$grupoid = $comp['grupoid'];
	}
	echo "<tr><td><b>Total:</b></td><td align='right'><b>$ ".number_format($total,2,',','.')."</b></td></tr>";

	echo "</tbody></table>";
	echo "<input type='hidden' name='total' id='total' value='{$total}' />";
	echo "<input type='hidden' name='grupoid' id='grupoid' value='{$grupoid}' />";

	return true;
}


// Nueva función, trae los comprobantes según el insc_id
function pagos2(){
	global $HULK,$LMS,$H_DB,$H_USER;

	$userid		= $_REQUEST['userid'];
	$insc_id	= $_REQUEST['insc_id'];

	if(!$userid OR !$insc_id) return false;

	$comprobantes = $H_DB->GetAll("SELECT DISTINCT comp.id, comp.numero, comp.importe, comp.tipo, c.grupoid
																 FROM h_cuotas c
																 INNER JOIN h_comprobantes_cuotas cc ON c.id=cc.cuotaid
																 INNER JOIN h_comprobantes comp ON cc.comprobanteid=comp.id
																 WHERE c.insc_id={$insc_id}
																 AND c.insc_id NOT IN(SELECT DISTINCT insc_id FROM h_bajas
																											WHERE cancel=0 AND userid={$userid});");


	echo "<table class='ui-widget' width='75%'>	<thead class='ui-widget-header'>
					<th>Comprobante</th><th>Importe</th></thead><tbody class='ui-widget-content'>";

	foreach ($comprobantes as $comp){
		echo "<tr><td>".$comp['puntodeventa']."-".sprintf("%08d",$comp['numero'])."</td><td align='right'>$ ".number_format($comp['importe'],2,',','.')."</td></tr>";
		$total += $comp['importe'];
		echo "<input type='hidden' name='comprobantes[]' value='{$comp['id']}' />";
		$grupoid = $comp['grupoid'];
	}
	echo "<tr><td><b>Total:</b></td><td align='right'><b>$ ".number_format($total,2,',','.')."</b></td></tr>";

	echo "</tbody></table>";
	echo "<input type='hidden' name='total' id='total' value='{$total}' />";
	echo "<input type='hidden' name='grupoid' id='grupoid' value='{$grupoid}' />";

	return true;
}

function category_form(){
	global $HULK,$LMS,$H_DB,$H_USER;

	$id	=	$_GET['id'];

	$category_form	= $H_DB->GetAll("SELECT acf.* FROM h_activity_category_form acf WHERE acf.categoryid={$id};");

	if ($category_form){

		foreach($category_form as $field){
			$required="";
			$class="";
			echo '<tr><td colspan="4" width="100%" align="left">';
			echo "<b>{$field['name']}:</b>";
			if ($field['required']==1){
				echo "<em>*</em>";
				//$required="required";
				$class.=" required";
			}
			$class.=" ".$field['validate'];

			echo "</td></tr>";
			echo "<tr><td colspan='4'>";
			switch($field['type']){
				case "text":
					echo "<input type='text' name='{$field['name']}' size='50' {$required} class='{$class}' />";
				break;
				case "textarea":
					echo "<div align='center' width='100%'><textarea rows='10' cols='150' name='{$field['name']}' {$required}  class='{$class}'></textarea></div>";
				break;
				case "checkbox":
					if($field['autovalue']){
						$valores = explode(",",$field['autovalue']);
						foreach($valores as $val){
							echo "{$val} <input type='checkbox' name='{$field['name']}[]' value='{$val}' {$required}  style='width:20px;' /> &nbsp;";
						}
					}else{
						echo "<input type='checkbox' name='{$field['name']}' value='1'  {$required}  style='width:20px;'/>";
					}
				break;
				case "radio":
					if($field['autovalue']){
						$valores = explode(",",$field['autovalue']);
						foreach($valores as $val){
							echo "{$val} <input type='radio' name='{$field['name']}' value='{$val}' {$required}  class='{$class}' style='width:20px;'/> &nbsp;";
						}
					}else{
						echo "<input type='radio' name='{$field['name']}' value='1'  {$required}  style='width:10px;'/>";
					}
				break;
				case "select":
					if($field['autovalue']){
						$valores = explode(",",$field['autovalue']);
						echo "<select name='{$field['name']}'  {$required}  class='{$class}'>";
						echo "<option value=''></option>";
						foreach($valores as $val){
							echo "<option value='{$val}'>{$val}</option>";
						}
						echo "</select>";
					}
				break;
				case "file":
					echo "<input type='file' name='archivos[]' size='50' {$required} />";
				break;
			}
			if ($field['summary'])
				echo "<br/>{$field['summary']}<br/><br/>";
			echo "</td></tr>";
		}
	}

	return true;
}

function category_desc(){
	global $HULK,$LMS,$H_DB,$H_USER;

	$id	=	$_GET['id'];

	if($id>0){
		$summary = $H_DB->GetField('h_activity_category','summary',$id);
		echo "<center>".$summary."</center>";
	}
	return true;

}
//alan
function RecordExists(){

  global $HULK,$LMS,$H_DB,$H_USER;

	$table	= $_REQUEST['table'];
	$return	= $_REQUEST['return'];
	$value	= $_REQUEST['value'];
	$field	= $_REQUEST['field'];
	if( $consulta = $LMS->GetRow("SELECT {$return} FROM {$table} WHERE {$field}='{$value}';")){
		return $consulta[$return];
	}
	return false;
}

function Bancos(){


  global $H_DB;

	$bancos = $H_DB->GetAll("SELECT * FROM h_bancos ORDER BY name;");

	echo "<select name='banco' style='width:200px;'>";
	echo "<option value='0'>Otro...</option>";
	foreach($bancos as $banco){
		echo "<option value='{$banco['id']}'>{$banco['name']}</option>";
	}
	echo "</select>";

	return true;
}
?>
