<?php

	require_once 'config.php';
	require_once $HULK->libdir.'/lib_sincronizador.php';

	$H_USER->require_login();
	$H_USER->require_capability('admin/view');
	
	set_time_limit(3600); // 1 hour should be enough

	$dir=$HULK->sincro_gradebook."/upload";
	$dir2=$HULK->sincro_gradebook."/historico";
	$filename_error=$HULK->sincro_gradebook."/errores ".date('Y-m-d_his').".csv";
	
	$userserrors = 0;
		
	$campos_obligatorios = array('firstname','lastname','username','email','course','academy');

	$view->Load('header');
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
<p>Archivo: <span id="archivo"></span></p>
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
		}
	});
</script>

<?php
	flush_buffers();
	// Paso los datos del archivo a un vector organizado por nombre de campo
	$result = files_to_vector($dir);

	$file_errors = fopen($filename_error, 'w');
	fputcsv($file_errors,array("academy","course","shortname","firstname","lastname","username","email","course_startdate","course_enddate","type","classid","status","acid","periodo","final","user_cisco"),",");	
	$errores=0;	

	$ValorTotal=count($result);
	flush_buffers("<script>$('#totalregistros').html('{$ValorTotal}')</script>");
	foreach($result as $num=>$row2){	
		
		unset($row);
		unset($modelo);
		$error = false;
		unset($course);
		unset($user);

		flush_buffers("<script>$('#archivo').html('{$row2['archivo']}')</script>");
		flush_buffers("<script>$('#proceso').html('')</script>");
		$row['academy']=$row2['academy'];
		$row['course']=trim(utf8_decode($row2['course']));
		$row['shortname']=$row2['section'];
		if($row2['student']!=""){
			$nombre = explode(" ",$row2['student'], 2);
		}else{
			$nombre = explode(" ",$row2['ï»¿student'], 2);							
		}
		$row['firstname']=trim($nombre[0]);
		$row['lastname']=trim($nombre[1]);			
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
		flush_buffers("<script>$('#progressbar').progressbar( 'value', ".round($porcentaje).");</script>"); 
		flush_buffers("<script>$('#procesadook').html('{$numtemp}')</script>");
		flush_buffers("<script>$('#porcentaje').html('".round($porcentaje)."')</script>");
		flush_buffers();
		flush_buffers("<script>$('#proceso').html('Revisando campos obligatorios')</script>");

		foreach($campos_obligatorios as $field){
			if (!isset($row[$field]) or $row[$field] === '') {
				// TODO: error
				$row_csv['errores'] .="Falta campo {$field}. ";
				$error = true;
			}
		}

		if (($row['firstname'] == 'Alumno') AND ($row['lastname'] == 'de prueba')){
			continue;
		}
		if (($row['firstname'] == 'Points') AND ($row['lastname'] == 'Possible')){
			continue;
		}
				
		if (strpos($row['email'], '@') == false){
				$row_csv['errores'] .="El email ({$row['email']}) no tiene arroba.";
				$error = true;
		}
		
		if ($error==true) {
			fputcsv($file_errors,$row_csv,",");
			$userserrors++;
			flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
			continue;
		}

		// Academia
		if (!$row['academy_id']){
			flush_buffers("<script>$('#proceso').html('Obteniendo id academia')</script>");
			if(!$row['academy_id'] = $LMS->getField('mdl_proy_academy','id',$row['academy'],'shortname')){
				// No se encontro academia
				$row_csv['errores'] .="No se encontro academia. ";				
				fputcsv($file_errors,$row_csv,",");	
				$userserrors++;	
				flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
				continue;
			}
		}     		
		// End Academia	
		
		if (!isset($row['courseid']) or $row['courseid'] == '' or $row['courseid']==0) {
			flush_buffers("<script>$('#proceso').html('Obteniendo courseid')</script>");		
			$row['courseid'] = $LMS->GetField_sql("SELECT id FROM mdl_course WHERE shortname='{$row['shortname']}' AND academyid={$row['academy_id']} AND periodo={$row['periodo']};");
		}

		flush_buffers("<script>$('#proceso').html('Verificando que existe curso modelo')</script>");
		// Curso modelo
		if(!$modelo = $LMS->GetRow("SELECT c.id,c.format,c.newsitems,c.category
									FROM mdl_course c WHERE c.shortname = '{$row['course']}'")) {
			// No se encontro curso modelo
			$row_csv['errores'] .="No se encontro curso modelo. ";				
			fputcsv($file_errors,$row_csv,",");	
			$userserrors++;			
			flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
			continue;  
		}		
		// End Curso Modelo
		// Start User
		
		if($existinguser = $LMS->GetRow("SELECT id, username, user_cisco FROM mdl_user WHERE email='{$row['email']}';")){
			$row['id'] = $existinguser['id'];	
			$row['username'] = $existinguser['username'];				
			$row['user_cisco'] = $existinguser['user_cisco'];		
		}else{
			$row['user_cisco'] = $row['email'];
			$row['username'] = $row['email'];
		}
		$row['auth']='manual';
		
		if($row['id']){
			flush_buffers("<script>$('#proceso').html('Actualizando usuario')</script>");
			if ($LMS->update('mdl_user', $user,"id={$row['id']}")) {
				$usersupdated++;
				flush_buffers("<script>$('#usersupdated').html('{$usersupdated}')</script>");				
			} else {
				$row_csv['errores'] .="Al actualizar user. ";				
				fputcsv($file_errors,$row_csv,",");
				$userserrors++;
				flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
				continue;
			}
		}else{
		
			// TODO: Revisar por que si el usuario no existe y existe el email devuelve un email duplicado
			if($LMS->record_exists('mdl_user',$row['email'],'email')){
				// Errores sincro.
				$row_csv['errores'] .="Email duplicado. ";				
				fputcsv($file_errors,$row_csv,",");
				$userserrors++;
				flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
				continue;
			}
			$newpassword	=	$H_USER->randomPass();			
			$row['password']	= md5($newpassword);				
			
			flush_buffers("<script>$('#proceso').html('Creando usuario')</script>");
			if ($row['id'] = $LMS->insertUser($row)) {
				$usersnew++;
				flush_buffers("<script>$('#usersnew').html('{$usersnew}')</script>");
			}else{
				$row_csv['errores'] .="Al crear user. ";				
				fputcsv($file_errors,$row_csv,",");			
				$userserrors++;
				flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
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
			// Si no es de proydesa, las comisiones primero se crean en cisco y siempre se sincronizan
			if (!$row['courseid']){
				$course=$modelo;
				unset($course['id']);
				$course['convenioid'] = $LMS->getConvenioId_fromCategory($course['category']);
				unset($course['category']);	
				$course['category']		= $LMS->getAcademyCategory($row['academy_id']);			
				$course['fullname']     = $row['shortname'];
				$course['shortname']    = $row['shortname'];
				$course['startdate']    = $row['course_startdate'];
				$course['enddate']      = $row['course_enddate'];
				$course['enrolstartdate'] = 0;   
				$course['enrolenddate']   = 0;
				$course['timemodified']   = time();
				$course['timecreated']    = time();
				$course['from_courseid']  = $modelo['id'];
				$course['need_courseid']  = 0;
				$course['need_create_courseid']  = 0;
				$course['academyid']  = $row['academy_id'];
				$course['periodo']	= $row['periodo'];
				$course['forma_de_pago']    = 'por_alumno';				
				if(stristr($course['shortname'], 'INTENSIVO')){
					$course['intensivo']	= 1;
				}else{
					$course['intensivo']	= 0;
				}
				flush_buffers("<script>$('#proceso').html('Creando curso')</script>");		
				if(!$course['id'] = $LMS->create_comision($course)){
					$row_csv['errores'] .="No se creo la comisión. ";				
					fputcsv($file_errors,$row_csv,",");		
					$userserrors++;
					flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
					//show_array($row_csv);
					continue;
				}else{
					$coursecreated++;
					flush_buffers("<script>$('#coursecreated').html('{$coursecreated}')</script>");
				}
			}else{
				$course['id']=$row['courseid'];
			}
		}else{
			// Si es de proydesa, las comisiones primero se crean en el CRM por eso se pregunta por nombre
			$course=$LMS->GetRow("SELECT id FROM mdl_course WHERE shortname='{$row['shortname']}';");
		}

		if($course['id']){
				$course['fullname']      = $row['shortname'];
				$course['shortname']     = $row['shortname'];
				//$course['startdate']     = $row['course_startdate'];
				//$course['enddate']       = $row['course_enddate'];
			if ($LMS->update('mdl_course', $course,"id = {$course['id']}")) {
				$courseupdated++;
				flush_buffers("<script>$('#courseupdated').html('{$courseupdated}')</script>");
			}
		}else{
			$row_csv['errores'] .="No existe la comisión. ";				
			fputcsv($file_errors,$row_csv,",");		
			$userserrors++;
			flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
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
				flush_buffers("<script>$('#userserrors').html('{$userserrors}')</script>");
			}
		}
		// End Role
		// Start Gradebook
		if ($addrole==5) {
			if ($row['status'] !=""){
				$LMS->user_status($row['id'],$course['id'],$row['status']);	
			}
			if ($row['final'] >0){
				$LMS->user_final($row['id'],$course['id'],$row['final']);	
			}
		}
		//End gradebook
		//show_array($row_csv);
	}
	fclose($file_errors);
	
	if ($userserrors==0){
		unlink($filename_error);
		flush_buffers("<script>$('#proceso').html('Finalizado!<br/><br/> <a href=\"sincronizar_statusi.php\" class=\"confirmlink\" >Puede sincronizar los status I</a>')</script>");		
	}else{
		flush_buffers("<script>$('#proceso').html('Finalizado!')</script>");		
	}
echo "<script>$('#archivo').html('')</script>";		
echo'</body>
</html>';