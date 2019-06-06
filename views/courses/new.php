<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 490px; padding-top: 10px'";
	}else{
		$menufixed = "";
	}
?>
<div<?= $menufixed ?>>	
	<div class="ui-widget">
		<div class="column-p" align="center" style="width:100%">
			<div class="portlet">

				<!-- STAGE 0 -->
				<?php if($stage==0):?>
					<form action="courses.php?v=new&stage=1" method="post"  name="academy" class="form-view">

						<div class="portlet-header"> Paso 1: Seleccionar curso modelo</div>
						<div class="portlet-content">

							<table class="ui-widget" align="center" style="width:60%;">
								<tbody class="ui-widget-content" style="text-align:right;">
									<tr style="height: 25px;">
										<td width="50%"><b><label for="instructor">Academia: </label></b></td>
										<td width="50%">
											<select name="academyid" id="academyid" OnChange="$('#carid').load('ajax.php?f=carrerasList&academy='+ $('#academyid').val());">
												<option value="0"></option>
											<?php foreach($academys as $academy):?>
													<option value="<?= $academy['id']?>"><?= $academy['name']?></option>
												<?php endforeach;?>
											</select>
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b><label for="instructor">Carrera: </label></b></td>
										<td width="50%">
											<div class="portlet-content" id="carreraid">
												<select name="carid" id="carid" OnChange="$('#modid').load('ajax.php?f=ModelList&carrera='+ $('#carid').val());">
												</select>
											</div>
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b><label for="instructor">Curso: </label></b></td>
										<td width="50%">
											<div class="portlet-content" id="modelid">
												<select name="modid" id="modid" OnChange="$('#next').removeAttr('disabled');$('#next').removeClass('ui-button-disabled ui-state-disabled');">
												</select>
											</div>
										</td>
									</tr>
								</tbody>
							</table>
						<button type="submit" id="next" class="btn btn-primary" disabled="disabled" >Siguiente <i class="fa fa-check-circle"></i></button>
					</form>

				<?php endif;?>

				<!-- STAGE 1 -->
				<?php if($stage==1):?>

					<div class="portlet-header">Paso 2: Ajustes generales del curso</div>
					<div class="portlet-content">

						<form action="courses.php?v=new&stage=2" id="course-new" method="post" name="course" class="ui-widget">

							<div class="course-academic">

								<label for="id_shortname">Nombre: </label><input maxlength="100" size="20" disabled="disabled" name="shortname" type="text" value="<?= $from_course['shortname'];?>" id="id_shortname" />
								<label for="id_aula">Aula: </label>
									<select id="aulita" name="aula" >
										<option value="0" data-code=""></option>
										<?php foreach($aulas as $aula):?>
											<option value="<?= $aula['id']?>" data-code="<?= $aula['name'] ?>"  ><?= $aula['name']?> - (Cap.: <?= $aula['capacity']?>)</option>
										<?php endforeach;?>
									</select>
								<label for="id_detalle">Detalle: </label><input maxlength="5" size="6" name="detalle" type="text" id="id_detalle" />
								<br/>
								<br/>

								<!-- INSTRUCTORES -->
								<?php if ($instructores):?>
								<div id="instructor">
									<label for="instructor">Instructor: </label>
								<select name="instructor" >
										<option value="0">
										<?php foreach($instructores as $ins):?>
											<option value="<?= $ins['id']?>"><?= $ins['fullname'];?></option>
										<?php endforeach;?>
									</select>
								</div>
								<br/>
								<div id="secundario">
									<label for="secundario">Secundario: </label>
								<select name="secundario" >
										<option value="0">
									<?php foreach($instructores as $ins):?>
											<option value="<?= $ins['id']?>"><?= $ins['fullname'];?></option>
										<?php endforeach;?>
									</select>
								</div>
								<br/>
								<?php endif;?>

							</div>


							<!-- DIAS & HORARIOS -->
							<div class="course-days">
								<div class="wrapper">
									<div class="box left">
										<label for="list_days">Día de la semana</label>
										<select id="list_days">
											<option value="1" data-daycode="L" >Lunes</option>
											<option value="2" data-daycode="M" >Martes</option>
											<option value="3" data-daycode="W" >Miércoles</option>
											<option value="4" data-daycode="J" >Jueves</option>
											<option value="5" data-daycode="V" >Viernes</option>
											<option value="6" data-daycode="S" >Sábado</option>
										</select>

										<label for="list_schedules">Horarios</label>
										<select id="list_schedules">
											<?php foreach($schedules as $schedule):?>
												<option value="<?= $schedule['id']?>" data-turno="<?= $schedule['turno'] ?>" ><?= $schedule['name']?></option>
											<?php endforeach;?>
										</select>

										<button id="btn_add_day" class="btn btn-primary" type="button">Agregar <i class="fa fa-caret-right"></i></button>
									</div>
									<div class="box right">
										<span>Días & Horarios Seleccionados:</span>
										<ul id="selected_days" class="well"></ul>
									</div>
								</div>

							</div>

							<!-- OPTIONS -->
							<div class="course-options">
								<div id="fechas">
									<label for="startdate">Fecha de inicio: </label>
									<input type="text" id="startdate" name="startdate" required />
								</div>
								<div id="clases">
									<label for="clases">Cant. de Clases: </label>
									<input type="number" id="clases" name="clases" required value="16" />
								</div>
								<div class="radio-group">
									<div class="item">
										<label for="assitance">Generar asistencia: </label>
										<input type="checkbox" id="assistance" name="assistance" value="1" checked />
									</div>
									<br />
									<div class="item">
										<label for="assitance">Tipo de Curso: </label>
										<select id="tipo_curso" name="intensivo">
										  <option value="0" selected="selected">Regular</option>
										  <option value="1">Intensivo</option>
										  <option value="2">Banda negativa</option>
										</select>
									</div>
									<br/>
									<div class="item">
										<label for="assitance">Ignorar Feriados Técnicos: </label>
										<input type="checkbox" id="tech" name="tech" value="1" />
										<div id="holidays" class="dp-none">										
											<br>
											<?php if($holidays): ?>
											<select name="holidays[]" multiple style="width:100%;height:120px">
												<?php 
												foreach($holidays as $holiday):
													$feriado = new DateTime($holiday['date']);
												?>
												<option value="<?=$holiday['date']?>"><?=$feriado->format('d/m/Y').' - '.$_daynames[$feriado->format('N')-1]?></option>
												<?php endforeach; ?>
											</select>

											<small>(Mantener la tecla CTRL o Shift para seleccionar más de uno)</small>
											<?php endif; ?>
										</div>
									</div>
									<br/>

								</div>

								<div id="modalidad">
									<label for="modalidad">Modalidad: </label>
										<select name="modalidad">
										<option value="0">Presencial</option>
										<option value="1">A distancia</option>
										<option value="2">Blended</option>
									</select>
								</div>
							</div>

							<div style="display: none;">
								<input name="MAX_FILE_SIZE" type="hidden" value="83886080" />
								<input name="admin" type="hidden" value="yes" />
								<input name="from_courseid" type="hidden" value="<?= $from_course['id'];?>" />
								<input name="category" type="hidden" value="<?= $categoryid;?>" />
								<input name="convenioid" type="hidden" value="<?= $convenioid;?>" />
								<input name="forma_de_pago" type="hidden" value="<?= $forma_de_pago;?>" />
								<input name="fullname" type="hidden" value="<?= $from_course['fullname'];?>" />
								<input name="fullname_complete" type="hidden" >
								<input name="schedules" type="hidden" >
								<input name="daycodes" type="hidden" >
								<input name="academyid" type="hidden" value="<?= $academyid;?>" />
								<input name="numsections" type="hidden" value="5" />
								<input name="visible" type="hidden" value="1" />
								<input name="idnumber" type="hidden" value="" />
								<input name="summary" type="hidden" value="<?= $from_course['summary'];?>" />
								<input name="format" type="hidden" value="<?= $from_course['format'];?>" />
								<input name="hiddensections" type="hidden" value="<?= $from_course['hiddensections'];?>" />
								<input name="newsitems" type="hidden" value="<?= $from_course['newsitems'];?>" />
								<input name="showgrades" type="hidden" value="<?= $from_course['showgrades'];?>" />
								<input name="showreports" type="hidden" value="<?= $from_course['showreports'];?>" />
								<input name="maxbytes" type="hidden" value="<?= $from_course['maxbytes'];?>" />
								<input name="metacourse" type="hidden" value="<?= $from_course['metacourse'];?>" />
								<input name="enrol" type="hidden" value="<?= $from_course['enrol'];?>" />
								<input name="defaultrole" type="hidden" value="<?= $from_course['defaultrole'];?>" />
								<input name="enrollable" type="hidden" value="<?= $from_course['enrollable'];?>" />
								<input name="enrolenddate" type="hidden" value="<?= $from_course['enrolenddate'];?>" />
								<input name="enrolenddisabled" type="hidden" value="<?= $from_course['enrolenddisabled'];?>" />
								<input name="enrolperiod" type="hidden" value="<?= $from_course['enrolperiod'];?>" />
								<input name="expirynotify" type="hidden" value="<?= $from_course['expirynotify'];?>" />
								<input name="notifystudents" type="hidden" value="<?= $from_course['notifystudents'];?>" />
								<input name="expirythreshold" type="hidden" value="<?= $from_course['expirythreshold'];?>" />
								<input name="groupmode" type="hidden" value="<?= $from_course['groupmode'];?>" />
								<input name="groupmodeforce" type="hidden" value="<?= $from_course['groupmodeforce'];?>" />
								<input name="lang" type="hidden" value="<?= $from_course['lang'];?>" />
								<input name="password" type="hidden" value="" />
								<input name="guest" type="hidden" value="0" />
								<input name="restrictmodules" type="hidden" value="" />
								<input name="role_1" type="hidden" value="Administador" />
								<input name="role_2" type="hidden" value="Creador de curso" />
								<input name="role_3" type="hidden" value="Profesor" />
								<input name="role_4" type="hidden" value="Instructor" />
								<input name="role_5" type="hidden" value="Estudiante" />
								<input name="role_6" type="hidden" value="Invitado" />
								<input name="role_7" type="hidden" value="Usuario autenticado" />
								<input name="role_8" type="hidden" value="Main Contact" />
								<input name="role_9" type="hidden" value="Contacto Administrativo" />
								<input name="role_10" type="hidden" value="QAP Manager" />
								<input name="role_11" type="hidden" value="Profesor Secundario" />
								<input name="role_12" type="hidden" value="Encargado Asistencia" />
								<input name="role_13" type="hidden" value="Administrador de Comisión" />
								<input name="role_14" type="hidden" value="Visor de Gradebooks" />
								<input name="role_15" type="hidden" value="Tomar Asistencia" />
								<input name="id" type="hidden" value="" />
								<input name="teacher" type="hidden" value="" />
								<input name="teachers" type="hidden" value="" />
								<input name="student" type="hidden" value="" />
								<input name="students" type="hidden" value="" />
								<input name="_qf__course_edit_form" type="hidden" value="1" />
							</div>

							<div class="course-action">
								<div align="center">

									<!--alan-->
									<input type="hidden" name="id_existe" id="id_existe" />
									<div id="hiddendiv" class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:30%;display:none">
										<p id="pepe"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
										<strong>Alert:</strong>Ya existe la comisi&oacute;n <p id="nombrecompleto"></p>
										<br/>
									</div>
									<!--alan-->

									<button id="btn_generate" type="button" class="btn btn-primary">Generar comisi&oacute;n <i class="fa fa-check-circle"></i></button>
								</div>
							</div>


						</form>


					</div>
				<?php endif;?>

				<!-- STAGE 2 -->
				<?php if($stage==2):?>
				<div class="portlet-header">Generando</div>
				<div class="portlet-content">
					<p>Comisi&oacute;n creada con &eacute;xito! <a href="courses.php?v=view&id=<?= $course['id'];?>"><b><?= $course['shortname'];?></b></a></p>
				</div>
				<?php endif;?>


			</div>
		</div>
	</div>


	<!-- MESSAGES -->
	<div id="messages" >
		<div class="content">
			<div class="header">
				<button type="button" class="close" data-button="close"><i class="fa fa-times"></i></button>
			</div>
			<div class="body" ></div>
			<div class="footer">
				<button type="button" data-button="close" class="btn btn-primary">Cerrar</button>
				<button type="button" data-button="ok" class="btn btn-success">Confirmar</button>
			</div>
		</div>
	</div>
</div>

<script>
var Templates = {
	li:$('<li>'),
	span:$('<span>'),
	i:$('<i>'),
	daylist:function(){
		var mod = this.li.clone().append(
			this.span.clone(),'',this.i.clone().addClass('fa fa-times').attr({title:'quitar'})
		);
		return mod;
	}
}
var Messages = {
	dialog:function(obj){
		if(obj.show){
			$('#messages').addClass('active').find('.body').html(obj.message);
		}else{
			$('#messages').removeClass('active').find('.body').html('');
		}
		$('#messages [data-button="close"]').unbind('click').click(function(){
			Messages.dialog({show:false});
		});
		if(obj.action){
			$('#messages [data-button="ok"]').show().unbind('click').click(function(){
				Messages.dialog({show:false});
				obj.action();
			});			
		}else{
			$('#messages [data-button="ok"]').hide();
		}
	}
}
var GenerateName = function(){
	var nombre = $('#id_shortname').val();
	var tipo_curso='';
	if($('#tipo_curso').val()!=0){
		$('#tipo_curso').val()==1 ? tipo_curso='INTENSIVO' : tipo_curso='BANDANEG';
	}

	var fecha = $('#startdate').val();
	var mes = parseInt(fecha.substring(3,5));
	var year = fecha.substring(8);
	if(mes < 3){
		periodo = year+'1';
	}else if(mes >=3 && mes < 8){
		periodo = year+'2';
	}else{
		periodo = year+'3';
	}
	var schedules = '';
	var idschedules = [];
	var daycodes = [];
	$.each($('#selected_days li'),function(){
		schedules += $(this).attr('data-daycode')+$(this).attr('data-turno');
		idschedules.push($(this).attr('data-idschedule'));
		daycodes.push($(this).attr('data-daycode'));
	});
	var aula = $('#aulita option:selected').attr('data-code');
	var detalle = $('#id_detalle').val();
	var fullname = nombre+tipo_curso+'-'+periodo+'-'+schedules+aula+detalle;

	$('input[name="fullname_complete"]').prop('value',fullname);
	$('input[name="schedules"]').prop('value',idschedules.join(','));
	$('input[name="daycodes"]').prop('value',daycodes.join(','));

	return fullname;
}
$(document).ready(function(){

	$('#btn_generate').click(function(){
		if($('#aulita').val() == 0){
			Messages.dialog({
				show:true,
				message:'Debes elegir un aula'
			});
			return false;
		}
		if($('select[name="instructor"]').val() == 0){
			Messages.dialog({
				show:true,
				message:'Debes elegir un instructor'
			});
			return false;
		}
		if($('#selected_days li').length == 0){
			Messages.dialog({
				show:true,
				message:'Debes elegir un día y un horario para esta comisión'
			});
			return false;
		}
		if($('#startdate').val() == ''){
			Messages.dialog({
				show:true,
				message:'Debes elegir una fecha de inicio'
			});
			return false;
		}
		//TIPO CURSO
		//regular es una vez por semana
		//intensivo es dos veces por semana
		//banda negativa es dos veces por semana (una presencial y otra a distancia)
		//Blended
		
		if($('#selected_days li').length>1 && $('#tipo_curso').val()==0){
			Messages.dialog({
				show:true,
				message:'Has elegido más de dos días de cursada en la semana para curso Regular'
			});
			return false;
		}

		if($('#selected_days li').length==1 && $('#tipo_curso').val()!=0){
			Messages.dialog({
				show:true,
				message:'Has elegido un día de cursada en la semana para curso '+$('select[name="tipo_curso"] option:selected').text()
			});
			return false;
		}
		
		$(this).prop('disabled',true);
		var fullname = GenerateName();
		var html = $.ajax({
			type: "POST",
			url: "ajax.php",
			data: "f=RecordExists&table=mdl_course&return=fullname&field=fullname&value="+fullname,
			cache: false,
			dataType:'html',
			error:function(data){
				console.log(data);
			},
			success:function(data){
				///console.log(data,fullname);
				$('#btn_generate').prop('disabled',false);
				if($.trim(data) === fullname){
					Messages.dialog({
						show:true,
						message:'La comisión que intentas crear ya existe.'
					});
					return false;
				}
				//$("#dialog-confirm").dialog('open');
				Messages.dialog({
					show:true,
					message:'Estás por registrar la comisión. ¿Deseas continuar?',
					action:function(){
						$('#course-new').submit();
						$('#btn_generate').prop('disabled',true);
					}
				});
			}
		});


	});

	/*--- EDIT ---*/
	$('#btn_add_day').click(function(){
		var mod = Templates.daylist();
		var day = $('#list_days option:selected').text();
		var daycode = $('#list_days option:selected').attr('data-daycode');
		var schedule = $('#list_schedules option:selected').text();
		var turno = $('#list_schedules option:selected').attr('data-turno');
		var idschedule = $('#list_schedules').val();
		var numday = $('#list_days').val();
		mod.attr({'data-day':numday,'data-idschedule':idschedule,'data-daycode':daycode,'data-turno':turno}).find('span').text(day+' - '+schedule);
		var pass = true;
		$.each($('#selected_days li'),function(k,v){
			if($(this).attr('data-day') == numday && $(this).attr('data-idschedule') == idschedule){
				pass = false;
			}
		});
		if(pass){
			$('#selected_days').append(mod);
		}
	});
	$('#selected_days').on('click','i',function(){
		$(this).parent().remove();
	});
	$('#startdate').datepicker({
		dateFormat: "dd-mm-yy",
		dayNamesMin: [ "Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa" ],
		firstDay:1
	});
	if($('#academyid').val() != 0){
		$('#academyid').trigger('change');
	}

	$('#tech').change(function(){
		if($(this).is(':checked')){
			$('#holidays').removeClass('dp-none');
		}else{
			$('#holidays').addClass('dp-none');			
		}
	});
});
</script>