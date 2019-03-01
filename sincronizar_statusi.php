<?php

	require_once 'config.php';

	$H_USER->require_login();
	$H_USER->require_capability('admin/view');
	
	set_time_limit(3600); // 1 hour should be enough

	$dir=$HULK->sincro_gradebook."/upload";
	$dir2=$HULK->sincro_gradebook."/historico";
	

	$rowtotal = 1;
	$userserrors = 0;
		
	$campos_obligatorios = array("email","course","academy");

	$view->Load('header');
	
	if (isset($_GET['init'])){
		if ($_GET['init']>2){
			$init=$_GET['init']-1;
		}
	}else{
		$init=0;
	}	
	
	
?>

<p>Espere a que el proceso de sincronización termine para cerrar esta ventana.</p>
<div id="progressbar" style="margin:10px;"></div>
<p> Registros procesados <span id="procesadook"></span> de <span id="totalregistros"></span> (<span id="porcentaje"></span>%)</p>
<p>
<b>Detalle:</b><br/>
Usuarios dados de baja: <span id="statusi">0</span><br/>
</p>
<p>Proceso: <span id="proceso"></span></p>

<script>

    $( "#progressbar" ).progressbar({
      value: 1
    });
	window.onbeforeunload=function(){
		//mostramos un mensaje al usuario avisandole
		if ($("#porcentaje").html()!="100"){
			return 'El proceso de sincronización parece no haber terminado todavia.';
		}
	};
	$(function() {
		if ($("#porcentaje").html()!="100"){
			$("#porcentaje").html("100");
			var iniciar=(parseInt($('#procesadook').html())+parseInt(<?=$init;?>));
		<!--	window.location='sincronizar_gradebooks.php?init=' + iniciar;-->			
			<!--location.reload();-->
			alert('Algo paso.');
		}
	});
</script>


<?php
	flush_buffers();
	// Paso los datos del archivo a un vector organizado por nombre de campo

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
													while (($file = fgetcsv($handle, 0, ",")) !== FALSE) {										
														$ValorTotal = count($file);
														for ($c=0; $c < $ValorTotal; $c++) {
															if ($row==1){
																$col[$c]=trim(strtolower(utf8_encode($file[$c])));
																if (strpos($col[$c], "instructor use only")!==false){
																	$col[$c]="status";
																}elseif(strpos($col[$c], "final exam")!==false){
																	$col[$c]="final";															
																}
															}elseif(($row!=2) AND ($rowtotal>$init)){
																$result[$rowtotal-$init][$col[$c]]=trim(utf8_encode($file[$c]));
																$result[$rowtotal-$init]['academy']=trim(utf8_encode($academy));
																$result[$rowtotal-$init]['course']=trim(utf8_encode($course));
																$result[$rowtotal-$init]['archivo']=trim(utf8_encode($dir.'/'.$academy.'/'.$course.'/'.$gradebook));
															}
														}
														$row++;
														$rowtotal++;	
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
	flush_buffers();

	$ValorTotal=($rowtotal-$init)-2;
	echo "<script>$('#totalregistros').html('{$ValorTotal}')</script>";
	foreach($result as $num=>$row2){	
		
		unset($row);
		unset($modelo);
		$error = false;
		unset($course);
		unset($user);

		echo "<script>$('#archivo').html('{$row2['archivo']}')</script>";
		echo "<script>$('#proceso').html('')</script>";
		$row['academy']=$row2['academy'];
		$row['course']=$row2['course'];
		$row['shortname']=$row2['section'];
		if($row2['student']!=""){
			$nombre = explode(" ",$row2['student'], 2);
		}else{
			$nombre = explode(" ",$row2['ï»¿student'], 2);							
		}
		$row['firstname']=trim(utf8_decode($nombre[0]));
		$row['lastname']=trim(utf8_decode($nombre[1]));
		$row['username']=$row2['sis login id'];
		$row['email']=$row2['sis login id'];
		$row['course_startdate']=time()-604800;
		$row['course_enddate']=time()+7776000;
		$row['type']="1";
		
		if (($row2["status"]=="0") && ($row2["final"]>59)){
			$row['status']="P";
		}else{
			$row['status']="E";		
		}
		$row['acid']=$row2['id'];
		$row['periodo']=$HULK->periodo;
		$row['final']=$row2["final"];	
		$row['user_cisco']=$row2['sis login id'];
		$row_csv = $row;
		$row_csv['registro']=$num;
		
		$porcentaje = ($num-1) * 100 / $ValorTotal;
		$numtemp=$num-1;
		echo "<script>$('#progressbar').progressbar( 'value', ".round($porcentaje).");</script>"; 
		echo "<script>$('#procesadook').html('{$numtemp}')</script>";
		echo "<script>$('#porcentaje').html('".round($porcentaje)."')</script>";
		flush_buffers();
		echo "<script>$('#proceso').html('Revisando campos obligatorios')</script>";

		foreach($campos_obligatorios as $field){
			if (!isset($row[$field]) or $row[$field] === '') {
				// TODO: error
				$error = true;
			}
		}
		if (strpos($row['email'], '@') == false){
				$error = true;
		}		

		if ($error==true) {
			$userserrors++;
			echo "<script>$('#userserrors').html('{$userserrors}')</script>";
			continue;
		}

		// Academia
		if (!$row['academy_id']){
			if(!$row['academy_id'] = $LMS->getField('mdl_proy_academy','id',$row['academy'],'shortname')){
				// No se encontro academia
				echo "No se encontro academia";
				show_array($row);
				die();
			}
		}     		
		// End Academia	
		
		if (!isset($row['courseid']) or $row['courseid'] == '' or $row['courseid']==0) {
			echo "<script>$('#proceso').html('Obteniendo courseid')</script>";		
			$row['courseid'] = $LMS->GetField_sql("SELECT id FROM mdl_course WHERE shortname='{$row['shortname']}' AND academyid={$row['academy_id']} AND periodo='{$row['periodo']}';");
		}
		$course['id']=$row['courseid'];

		// Start User
		
		if($existinguser = $LMS->GetRow("SELECT id, username, user_cisco FROM mdl_user WHERE email='{$row['email']}';")){
			$row['id'] = $existinguser['id'];	
		}else{
			echo "El usuario no se encuentra en la base";
			show_array($row);
			die();
		}
		

	

		if($course['id']){
			$status[$course['id']][]=$row['id'];
		}else{
			echo "El curso no se encuentra en la base";
			show_array($row);
			die();
		}

	}
	foreach($status as $curso=>$estudiantes){
		// reocorrio todo un curso, comparo los que agrego con los que están pongo en I si ya no están y borro array
		$usuariostodos=$LMS->GetAll("SELECT u.id FROM mdl_course c 
									INNER JOIN mdl_context ct ON c.id = ct.instanceid 
									INNER JOIN mdl_role_assignments ra ON ct.id = ra.contextid 
									INNER JOIN mdl_user u ON ra.userid = u.id 
									WHERE ra.roleid=5 AND u.deleted=0 AND c.id='{$curso}';");                 
		
		foreach($usuariostodos as $esteusuario){

				if(!in_array($esteusuario['id'],$estudiantes)){
					$statusi++;
					echo "<script>$('#statusi').html('{$statusi}')</script>";
					$LMS->user_status($esteusuario['id'],$curso,"I");
				}
		}
	}
	

	
echo "<script>$('#archivo').html('')</script>";		
echo "<script>$('#proceso').html('Finalizado!')</script>";		
echo'</body>
</html>';

function flush_buffers(){ 
    ob_end_flush(); 
    @ob_flush(); 
    flush(); 
    ob_start(); 
} 
?>