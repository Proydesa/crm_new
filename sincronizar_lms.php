<?php

	require_once 'config.php';

	$H_USER->require_login();
	$H_USER->require_capability('admin/view');
	
	set_time_limit(3600); // 1 hour should be enough

	$dir=$HULK->sincro_lms."/upload";
	$dir2=$HULK->sincro_lms."/historico";

	$row = 1;
	$userserrors = 0;
	$filename=$dir."/".$_REQUEST['name'];
	$arch=$arch2=0;
	if (stripos($_REQUEST['name'],"[O]")){
		$filename_h= str_replace("[O]", "[P]", $dir2."/".$_REQUEST['name']);
		$filename_error=str_replace("[O]", "[E]", "{$dir}/{$_REQUEST['name']}");
	}elseif(stripos($_REQUEST['name'],"[E]")){
		$filename_h= str_replace("[E]", "[P2]", $dir2."/".$_REQUEST['name']);	
		$filename_error=str_replace("[E]", "[E2]", "{$dir}/{$_REQUEST['name']}");
	}else{
		show_error("Error de archivo","No podemos procersar el nombre del archivo que estas queriendo sincronizar. Por favor descarga el archivo y volvelo a subir.");
		die();
	}
		
	$campos_obligatorios = array("firstname","lastname","username","email","course","academy","course_startdate","course_enddate");

	if ($_GET['init']>2){
		$init=$_GET['init']-1;
	}else{
		$init=1;
	}	
	
	$view->Load('header',$data);

?>

<p>Espere a que el proceso de sincronización termine para cerrar esta ventana.</p>
<div id="progressbar" style="margin:10px;"></div>
<p> Registros procesados <span id="procesadook"></span> de <span id="totalregistros"></span> (<span id="porcentaje"></span>%)</p>
<p>
<b>Detalle:</b><br/>
Cursos creados: <span id="coursecreated"></span><br/>
<!--Cursos actualizados: <span id="courseupdated"></span><br/>-->
Usuarios nuevos: <span id="usersnew"></span><br/>
<!--Usuarios actualizados: <span id="usersupdated"></span>-->
Errores: <span id="userserrors"></span><br/>
</p>
<p>Proceso: <span id="proceso"></span></p>
<script>

    $( "#progressbar" ).progressbar({
      value: 1
    });
	window.onbeforeunload=function(){
		//mostramos un mensaje al usuario avisandole
		if ($("#porcentaje").html()!="100"){
			window.opener.location.reload();
			return 'El proceso de sincronización parece no haber terminado todavia.';
		}
		window.opener.location.reload();
	};
	$(function() {
		if ($("#porcentaje").html()!="100"){
			$("#porcentaje").html("100");
			<!--location.reload();-->
			$(location).attr('href','sincronizar_lms.php?name=<?= $_REQUEST['name'];?>&init=' + $('#procesadook').html());
		}
	});
</script>


<?php
	flush_buffers();
	// Paso los datos del archivo a un vector organizado por nombre de campo
	if (($handle = fopen($filename, "r")) !== FALSE) {
		while (($file = fgetcsv($handle, 1000, ",")) !== FALSE) {
			$ValorTotal = count($file);
			for ($c=0; $c < $ValorTotal; $c++) {
				if ($row==1){
					$col[$c]=trim(strtolower(utf8_encode($file[$c])));
				}elseif($row>$init){
					$result[$row][$col[$c]]=trim(utf8_encode($file[$c]));
				}
			}
			$row++;
		}
		fclose($handle);
	}

	$file_errors = fopen($filename_error, 'w');
	fputcsv($file_errors,$col,",");
	$errores=0;	

	$ValorTotal=$row-$init;
	echo "<script>$('#totalregistros').html('{$ValorTotal}')</script>";

	foreach($result as $num=>$row){	

		unset($modelo);
		$error = false;
		unset($course);
		unset($user);
		$row_csv = $row;
		$row_csv['registro']=$num;
		
		$porcentaje = ($num) * 100 / $ValorTotal;
		echo "<script>$('#progressbar').progressbar( 'value', ".round($porcentaje).");</script>"; 
		echo "<script>$('#procesadook').html('{$num}')</script>";
		echo "<script>$('#porcentaje').html('".round($porcentaje)."')</script>";
		flush_buffers();
		foreach($campos_obligatorios as $field){
			if (!isset($row[$field]) or $row[$field] === '') {
				// TODO: error
				$row_csv['errores'] .="Falta campo {$field}. ";
				$error = true;
			}
		}

		if ($error==true) {
			fputcsv($file_errors,$row_csv,",");
			$userserrors++;
			echo "<script>$('#userserrors').html('{$userserrors}')</script>";
			//show_array($row_csv);
			continue;
		}

		// Academia
		if (!$row['academy_id']){
			if(!$row['academy_id'] = $LMS->getField('mdl_proy_academy','id',$row['academy'],'shortname')){
				// No se encontro academia
				$row_csv['errores'] .="No se encontro academia. ";				
				fputcsv($file_errors,$row_csv,",");	
				$userserrors++;	
				echo "<script>$('#userserrors').html('{$userserrors}')</script>";
				//show_array($row_csv);
				continue;
			}
		}     		
		// End Academia	
		
		if (!isset($row['classid']) or $row['classid'] == '' or $row['classid']==0) {
		//Si no tiene classid tendria que preguntar por nombre de comisión si está comisión existe en dicha academia.
			$row['classid'] = $LMS->GetField_sql("SELECT classid FROM mdl_course WHERE shortname='{$row['shortname']}' AND academyid={$row['academy_id']};");
			if (!isset($row['classid']) or $row['classid'] == '' or $row['classid']==0) {
				$row['classid'] = $LMS->GetField_sql('SELECT MAX(classid) FROM mdl_course;');	
				$row['classid']++;
			}
		}

		// Curso modelo
		if(!$modelo = $LMS->GetRow("SELECT c.id,c.format,c.numsections,c.newsitems,c.showgrades,c.showreports,c.maxbytes,c.metacourse,c.defaultrole,
									c.enrollable,c.enrolenddate,c.enrolperiod,c.expirynotify,c.notifystudents,c.expirythreshold,c.visible,c.origen
									FROM mdl_course c WHERE c.shortname = '{$row['course']}'")) {
			// No se encontro curso modelo
			$row_csv['errores'] .="No se encontro curso modelo. ";				
			fputcsv($file_errors,$row_csv,",");	
			$userserrors++;			
			echo "<script>$('#userserrors').html('{$userserrors}')</script>";
			//show_array($row_csv);
			continue;  
		}		
		// End Curso Modelo
		// Start User

		if($existinguser = $LMS->GetRow("SELECT id FROM mdl_user WHERE email='{$row['email']}';")){
			$row['id'] = $existinguser['id'];
			$row['acid'] = $LMS->GetField('mdl_user', 'acid',$row['email'],'email');					
			$row['username'] = $LMS->GetField('mdl_user', 'username',$row['email'],'email');					
			$row['user_cisco'] = $LMS->GetField('mdl_user', 'user_cisco',$row['email'],'email');				
		}

		if (!isset($row['user_cisco']) or $row['user_cisco'] === '') {
			$row['user_cisco'] = $row['email'];
		}
		if (!isset($row['username']) or $row['username'] == '') {
				$row['username'] = $row['email'];
		}			
		$row['auth']='manual';
		
		if($row['id']){
			if ($LMS->update('mdl_user', $user,"id={$row['id']}")) {
				$usersupdated++;
				echo "<script>$('#usersupdated').html('{$usersupdated}')</script>";				
			} else {
				$row_csv['errores'] .="Al actualizar user. ";				
				fputcsv($file_errors,$row_csv,",");
				$userserrors++;
				echo "<script>$('#userserrors').html('{$userserrors}')</script>";
				//show_array($row_csv);
				continue;
			}
		}else{
			$row['confirmed'] = 1;
			$row['timemodified'] = time();			        
			$row['lang']='es_utf8';
			$row['mnethostid']=3;
			$newpassword	=	$H_USER->randomPass();			
			$row['password']	= md5($newpassword);			
			// TODO: Revisar por que si el usuario no existe y existe el email devuelve un email duplicado
			if($LMS->record_exists('mdl_user',$row['email'],'email')){
				// Errores sincro.
				$row_csv['errores'] .="Email duplicado. ";				
				fputcsv($file_errors,$row_csv,",");
				$userserrors++;
				echo "<script>$('#userserrors').html('{$userserrors}')</script>";
				//show_array($row_csv);
				continue;
			}
			if ($row['id'] = $LMS->insertUser($row)) {
				$usersnew++;
				echo "<script>$('#usersnew').html('{$usersnew}')</script>";
			}else{
				$row_csv['errores'] .="Al crear user. ";				
				fputcsv($file_errors,$row_csv,",");			
				$userserrors++;
				echo "<script>$('#userserrors').html('{$userserrors}')</script>";
				//show_array($row_csv);
				continue;
			}
			if($_REQUEST['origen_usuario']){
				$user_profile['userid'] = $row['id'];
				$user_profile['origen'] = "Cisco";
				@$H_DB->insert('h_user_profile',$user_profile);						
			}
		}
		// End User
		// Start Course
		if (!in_array($row['academy_id'], array(1,2,3))){
			// Si no es de proydesa, las comisiones primero se crean en cisco y siempre se sincronizan, entonces preguntamos por classid
			if (!$course=$LMS->GetRow("SELECT id FROM mdl_course WHERE classid={$row['classid']};")){
				$course=$modelo;
				unset($course['id']);
				unset($course['category']);	
				$course['fullname']      = $row['shortname'];
				$course['shortname']     = $row['shortname'];
				$course['classid']       = $row['classid'];
				$course['startdate']      = $row['course_startdate'];
				$course['enddate']        = $row['course_enddate'];
				$course['enrolstartdate'] = 0;   
				$course['enrolenddate']   = 0;
				$course['timemodified']   = time();
				$course['timecreated']    = time();
				$course['from_courseid']  = $modelo['id'];
				$course['need_courseid']  = 0;
				$course['need_create_courseid']  = 0;
				$course['academyid']  = $row['academy_id'];
				$course['periodo']	= $row['periodo'];
				if(stristr($course['shortname'], 'INTENSIVO')){
					$course['intensivo']	= 1;
				}else{
					$course['intensivo']	= 0;
				}
				if(!$course['id'] = $LMS->create_comision($course)){
					$row_csv['errores'] .="No se creo la comisión. ";				
					fputcsv($file_errors,$row_csv,",");		
					$userserrors++;
					echo "<script>$('#userserrors').html('{$userserrors}')</script>";
					//show_array($row_csv);
					continue;
				}else{
					$coursecreated++;
					echo "<script>$('#coursecreated').html('{$coursecreated}')</script>";
				}
			}
		}else{
			// Si es de proydesa, las comisiones primero se crean en el CRM por eso se pregunta por nombre
			$course=$LMS->GetRow("SELECT id FROM mdl_course WHERE shortname='{$row['shortname']}';");
		}

		if($course['id']){
				$course['fullname']      = $row['shortname'];
				$course['shortname']     = $row['shortname'];
				$course['classid']       = $row['classid'];
				$course['startdate']     = $row['course_startdate'];
				$course['enddate']       = $row['course_enddate'];
			if ($LMS->update('mdl_course', $course,"id = {$course['id']}")) {
				$courseupdated++;
				echo "<script>$('#courseupdated').html('{$courseupdated}')</script>";
			}
		}else{
			$row_csv['errores'] .="No existe la comisión. ";				
			fputcsv($file_errors,$row_csv,",");		
			$userserrors++;
			echo "<script>$('#userserrors').html('{$userserrors}')</script>";
		}
		// End Course		
		// Start Role
		if($course['id']){
			if (!empty($row['role'])){
				$addrole = $row['role'];
			}else{
				$addrole = 5;
			}
			if(!$LMS->enrolUser($row['id'], $course['id'], $addrole) ){
				$row_csv['errores'] .="No se pudo enrolar al usuario.";				
				fputcsv($file_errors,$row_csv,",");
				$userserrors++;
				echo "<script>$('#userserrors').html('{$userserrors}')</script>";
			}
		}
		// End Role
		// Start Gradebook
		if ($addrole==5) {
			if ($row['status'] !=""){
				$LMS->user_status($row['id'],$course['id'],$row['status']);	
			}
			$LMS->user_final($row['id'],$course['id'],$row['final']);	
		}
		//End gradebook
		//show_array($row_csv);
	}
	fclose($file_errors);
	
	if ($userserrors==0){
		unlink($filename_error);
	}
	rename($filename, $filename_h);
	//else
		//unlink($filename_error);

echo "<script>$('#proceso').html('Finalizado!');window.opener.location.reload();</script>";	
echo'</body>
</html>';

function flush_buffers(){ 
    ob_end_flush(); 
    ob_flush(); 
    flush(); 
    ob_start(); 
} 
?>