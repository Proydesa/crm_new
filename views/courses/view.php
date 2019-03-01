﻿<div class="ui-widget" align="left">
<div class="column" style="width:50%">
	<div class="portlet">
		<div align="center"><h2><?= $row['shortname'];?></h2></div>
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Academia:</b></td>
							<td class="ui-widget-content" colspan="3"><?= $LMS->getField('mdl_proy_academy','name',$row['academyid']);?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Curso:</b></td>
							<td class="ui-widget-content" colspan="3"><?= $LMS->getField('mdl_course','fullname',$row['from_courseid']);?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Fecha Inicio:</b></td>
							<td class="ui-widget-content"><?= date("d/m/Y",$row['startdate']);?></td>
							<td class="ui-widget-content" align="right"><b>Fecha Fin:</b></td>
							<td class="ui-widget-content"><?= date("d/m/Y",$row['enddate']);?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Per&iacute;odo:</b></td>
							<td class="ui-widget-content"><?= $row['periodo'];?></td>
							<td class="ui-widget-content" align="right"><b>Horario:</b></td>
							<td class="ui-widget-content"><?= $horario['name'];?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Capacidad:</b></td>
							<td class="ui-widget-content"><?= $capacidad['capacity'] ;?></td>
							<td class="ui-widget-content" align="right"><b>Inscriptos:</b></td>
							<td class="ui-widget-content"><?= count($estudiantes) ;?></td>
						</tr>
						<tr>
							<td class="ui-widget-content" align="right"><b>Cursada:</b></td>
							<td class="ui-widget-content"><?= $HULK->course_cursada[$row['intensivo']];?></td>
							<td class="ui-widget-content" align="right"><b>LMS ID:</b></td>
							<td class="ui-widget-content"><a href="<?= $HULK->lms_wwwroot;?>/course/view.php?id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a><?= $row['id'];?></td>
						</tr>
						<tr>
							<td class="ui-widget-content" align="right"><b>Modalidad:</b></td>
							<td class="ui-widget-content"><?= $HULK->course_modalidad[$row['modalidad']];?></td>
							<td class="ui-widget-content" align="right"><b>CLASSID:</b></td>
							<td class="ui-widget-content"><?= $row['classid'];?></td>
						</tr>
						<tr>
							<td class="ui-widget-content" align="right"><b>Forma de pago:</b></td>
							<td class="ui-widget-content"><?= $row['forma_de_pago'];?></td>
							<td class="ui-widget-content" colspan="2" align="center">
							<?php if($row['lms_version']=="LMS2"): ?>								
								<a class="button" href="courses.php?v=edit&id=<?= $row['id'];?>">Editar</a>
								<?php if($H_USER->has_capability('course/cambio_instructor')): ?>
								<a class="button" href="courses.php?v=cambio_instructor&id=<?= $row['id'];?>">Cambiar instructor</a>
								<?php endif; ?>		
							<?php endif; ?>		
							</td>
						</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div class="column" style="width:50%">
	<?php if($row['lms_version']=="LMS2"): ?>								
	<?php if(count($estudiantes)): ?>
	<div class="portlet" align="center" style="padding:10px">
		<a class="button" target="_blank" href="courses.php?v=inscriptos-print&id=<?= $row['id'];?>">Imprimir Listado</a>
		<a class="button" href="courses.php?v=asistencia-completa&id=<?= $row['id'];?>">Asistencia</a>
		<?php
		$arrIDs = array();
		foreach($estudiantes as $ke=>$estudiante):
			$arrIDs[] = $estudiante['id'];
		endforeach;
		$linkAll = "javascript:window.open('hd.php?v=lista_notification&id=".implode(',',$arrIDs)."','Notificar','width=800px,height=600px')";
		?>
		<a class="button" href="#" onClick="<?= $linkAll ?>">Notificar a Todo el Curso</a>
	</div>
	<?php endif;?>
	<div class="portlet">
		<div class="portlet-header">Instructores</div>
		<div class="portlet-content"  style="overflow: auto; height: 107px;">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Username</th>
					<th>Nombre</th>
				</thead>
				<tbody>
					<?php foreach($instructores as $instructor):?>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="center"><?= $instructor['username'];?></td>
							<td class="ui-widget-content" class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $instructor['id'];?>';">
								<a href="contactos.php?v=view&id=<?= $instructor['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<?= $instructor['fullname']; if($instructor['roleid']==11) echo " <b>(Secundario)</b>";?>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
	<?php else:?>
	<div class="portlet" align="center" style="padding:10px">
		<p>Esta comisión pertenece a una versión de <b>LMS antigua</b>. Por eso no se muestran todas las opciones para el manejo de la misma.</p>
	</div>
	<?php endif;?>
</div>
</div>

<div class="clearfix"></div>
<hr>

<?php //if(count($estudiantes)): ?>
<!-- NUEVO LAYOUT -->
<div class="column-c">
	<!-- ALERTAS -->
	<div class="ui-widget">

		<div class="flex-container">
			<div class="flex-item alert-container">
				<h4>Alertas Asistencias</h4>

				<div id="faltas_warn" class="bg-warning alert-item" ></div>
				<div id="faltas_danger" class="bg-danger alert-item" ></div>

			</div>

			<div class="flex-item alert-container">
				<h4>Alertas Cuotas</h4>

				<div id="cuotas_warn" class="bg-warning alert-item" ></div>
				<div id="cuotas_danger" class="bg-danger alert-item" ></div>

			</div>

			<div class="flex-item alert-container">
				<h4>Clases Canceladas</h4>
				<?php $tc = 0; if(!empty($asistencias_canceladas)): foreach($asistencias_canceladas as $asistencia): ?>
				<div class="bg-danger alert-item">
					<?= ConvertDays(date('D',$asistencia['sessdate'])).'. '.date('d',$asistencia['sessdate']).' '.ConvertMonth(date('M',$asistencia['sessdate'])).'. - '.$asistencia['description'] ?>
				</div>
				<?php $tc++; endforeach; endif; ?>
				<?php if(!$tc): ?>
				<p>No hubo clases canceladas</p>
				<?php endif; ?>
			</div>
		</div>

	</div>

	<!-- TABLA -->
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="portlet-header ui-widget-header ui-corner-all">Comisiones</div>
		<div class="portlet-content">
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height:20px;font-size:7pt">
						<th colspan="6"></th>
						<th colspan="<?= count($cuotas) == 0 ? 1 : count($cuotas) ?>">Cuotas</th>
						<th colspan="<?= count($asistencias) == 0 ? 1 : count($asistencias) ?>">Asistencia</th>
						<th colspan="<?= count($examenes) == 0 ? 1 : count($examenes) ?>">Exámenes</th>
				</tr>
				</thead>

				<thead>
					<tr class="ui-widget-header" style="height:20px;font-size:7pt">
						<th>N°</th>
						<th>DNI</th>
						<th>Alumno</th>
						<th>Cat.</th>
						<th>Estado</th>
						<th>Enrolado</th>

						<!-- Cuotas-->
						<?php if(count($cuotas)>0): foreach($cuotas as $kc=>$cuota): ?>
						<th><?= $cuota['cuota'] == 0 ? 'eKit' : $cuota['cuota'] ?></th>
						<?php endforeach; else: ?>
						<th>-</th>
						<?php endif; ?>

						<!-- Asistencia-->
						<?php if(count($asistencias)>0): foreach($asistencias as $asistencia): ?>
						<th class="textCenter sz-6"><?= ConvertDays(date('D',$asistencia['sessdate'])).'<br />'.date('d',$asistencia['sessdate']).'<br />'.ConvertMonth(date('M',$asistencia['sessdate'])); ?></th>
						<?php endforeach; else: ?>
						<th>Info no cargada</th>
						<?php endif; ?>

						<!-- Exámenes -->
						<?php if(count($examenes)>0):  foreach($examenes as $ke=>$examen): ?>
						<th style="width:16px;" class="textCenter sz-6"><?= $examen['itemname'] ?></th>
						<?php endforeach;  else: ?>
						<th>-</th>
						<?php endif; ?>


				</tr>
				</thead>

				<!-- ROWS ALUMNOS -->
				<tbody>
				<?php
				$arrStatus = array('E'=>'bg-primary','P'=>'bg-success','I'=>'bg-danger','F'=>'bg-warning');
				$arrAttendance = array('E'=>'bg-primary','P'=>'bg-success','T'=>'bg-warning','A'=>'bg-danger');
				foreach($estudiantes as $ke=>$estudiante):
					$eStatus = $LMS->user_status($estudiante['id'],$row['id']);
				?>
					<tr data-userid="<?= $estudiante['id'] ?>" data-username="<?= $estudiante['namefull'] ?>" >
						<td class="textCenter"><?= $ke+1 ?></td>
						<td class="textCenter"><?= $estudiante['username']; ?></td>
						<td class="textCenter"><a title="<?= $estudiante['obs']; ?>" href="contactos.php?v=view&id=<?= $estudiante['id']; ?>" target="_blank" ><?= $estudiante['fullname']; ?></a></td>
						<td class="textCenter">I</td>
						<td class="<?= $arrStatus[$eStatus]; ?> textCenter"><?= $eStatus ?></td>
						<td class="textCenter"><?= date('d',$estudiante['timestart']).'/'.ConvertMonth(date('M',$estudiante['timestart'])).'/'.date('Y',$estudiante['timestart']); ?></td>


						<!-- Cuotas-->
						<?php

						if(count($cuotas)>0):
							foreach($cuotas as $kc=>$cuota):
								$difcuota = $LMS->getValorCuota($row['id'],$estudiante['id'],$cuota['cuota']);
						?>
						<td data-cuota="<?= $difcuota ?>" class="<?= $difcuota == '' ? '' : ($difcuota >= 0 ? 'bg-success' : 'bg-danger') ?> textCenter"><?= $difcuota == '' ? '-' : ($difcuota >= 0 ? 'ok' : '$'.number_format($difcuota,0,',','.')) ?></td>
						<?php
							endforeach;
						else:
						?>
						<td data-cuota="" class="textCenter">-</td>
						<?php endif; ?>


						<!-- Asistencia-->
						<?php
						//$arrStudentAttendance = array();
						//$countAttendance = 0;
						if(count($asistencias)>0):
							foreach($asistencias as $asistencia):
								$attendancePrefix = $LMS->getCourseAttendanceStudent($asistencia['id'],$estudiante['id']);
								/*if($attendancePrefix == 'A'):
									$countAttendance++;
								endif;*/
						?>
							<td data-attendance="<?= $attendancePrefix ?>" class="<?= $arrAttendance[$attendancePrefix]; ?> textCenter"><?= $attendancePrefix == '' ? '-' : $attendancePrefix; ?></td>
						<?php
							endforeach;
							///$arrStudentAttendance[] = array('id'=>$estudiante['id'],'name'=>$estudiante['fullname'],'faltas'=>$countAttendance);
						else:
						?>
							<td data-attendance="" class="textCenter">-</td>
						<?php endif; ?>

						<!-- Exámenes -->
						<?php
						if(count($examenes)>0):						
						foreach($examenes as $ke=>$examen):
							$notaExamn = $LMS->getStudentNotes($examen['id'],$estudiante['id']);
							if($notaExamn>=70):
								$labelnota = 'bg-success';
							elseif($notaExamn>=40 && $notaExamn<70):
								$labelnota = 'bg-warning';
							elseif($notaExamn > 0 && $notaExamn<40):
								$labelnota = 'bg-danger';
							else:
								$labelnota = '';
							endif;
						?>
							<td class="textCenter <?= $labelnota ?>"><?= $notaExamn == 0 ? '-' : ((round($notaExamn,2))*10).'%'; ?></td>
						<?php endforeach; 
							else:
						?>
							<td class="textCenter">-</td>
						<?php endif; ?>
					</tr>
				<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="32" align="left" style="padding:16px 0">
							Total Alumnos: <?= count($estudiantes) ?>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
	<hr>
	<div class="ui-widget">
		<h3>Referencias:</h3>		<br />
		<div class="flex-container">
			<div class="flex-item">
				<span><strong>Categoría:</strong></span>
				<ul class="list-ref">
					<li>C: Corporativo</li>
					<li>I: Individual</li>
				</ul>
			</div>
			<div class="flex-item">
				<span><strong>Estatus:</strong></span>
				<ul class="list-ref">
					<li class="bg-success" >P: Pass (Pasó)</li>
					<li class="bg-primary" >E: Enroll (Enrolado)</li>
					<li class="bg-danger" >I: Incomplete (Incompleto)</li>
					<li class="bg-warning" >F: Failed (Falló)</li>
				</ul>
			</div>
			<div class="flex-item">
				<span><strong>Asistencias:</strong></span>
				<ul class="list-ref">
					<li class="bg-success">P: Presente</li>
					<li class="bg-primary" >E: Excusado</li>
					<li class="bg-danger">A: Ausente</li>
					<li class="bg-warning" >T: Tarde</li>
				</ul>
			</div>
		</div>
	</div>
</div>

<?php if(count($estudiantes)): ?>
	<div class="portlet" align="center" style="padding:10px">
		No hay alumnos enrolados en esta comisión.
	</div>
<?php endif; ?>

<div class="column" style="width:100%">
<div class="portlet">
		<div class="portlet-header">Bajas</div>
		<div class="portlet-content"  style="overflow: auto; height: 320px;">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Usuario</th>
					<th>Detalle</th>
					<th>Fecha</th>
				</thead>
				<tbody>
					<?php foreach($bajas as $baja):?>
						<tr class="ui-widget-content">
							<td class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $baja['userid'];?>';">
								<a href="contactos.php?v=view&id=<?= $baja['userid'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<?php echo $LMS->GetField("mdl_user","CONCAT(lastname,' ',firstname)",$baja['userid']);?>
							</td>	
							<td><?= $baja['detalle'];?></td>							
							<td align="center"><?= show_fecha($baja['date'],"d-m-Y");?></td>							
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
</div>

<!-- FIXED COLUMN -->
<div class="table-header-fixed column-c dp-none" >
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="portlet-content">
			<table id="table_fixed" class="ui-widget" align="center" style="width:100%;">
			</table>
		</div>
	</div>
</div>

<script>
var ResizeHeader = function(){
	$.each($('#listado thead:eq(1) > tr > th'),function(k,v){
		$('#table_fixed thead:eq(1) th:eq('+k+')').width($(this).width());
	});
}
var BuildHeader = function(){
	var head1 = $('#listado thead:eq(0)').clone();
	var head2 = $('#listado thead:eq(1)').clone();
	$('#table_fixed').append(head1);
	$('#table_fixed').append(head2);
	ResizeHeader();
}
var MakeLink = function(str,ids,btn){
	return '<a href="#" onClick="javascript:window.open(\'hd.php?v=lista_notification&id='+ids+'\',\'Notificar\',\'width=800px,height=600px\')" '+(btn===true?'class="btn btn-xs btn-primary"':'')+' >'+str+'</a>';

}
var NotifyAttendance = function(){

	var arrFaltas = [];
	var arrCuotas = [];
	$.each($('tr[data-userid]'),function(ka,va){
		arrFaltas.push({userid:$(va).attr('data-userid'),username:$(va).attr('data-username')});
		arrCuotas.push({userid:$(va).attr('data-userid'),username:$(va).attr('data-username')});
		arrFaltas[ka].asistencias = [];
		/////////////////////
		var sumfalta = 0;
		if($(this).find('td[data-attendance]').attr('data-attendance') != ''){
			$.each($(this).find('td[data-attendance]'),function(kf,vf){
				if($(vf).attr('data-attendance')!=''){
					arrFaltas[ka].asistencias.push($(this).attr('data-attendance'));
				}
				if($(vf).attr('data-attendance')=='A'){
					sumfalta++;
				}
			});
			arrFaltas[ka].faltas = sumfalta;
		}
		/////////////////////
		var sumcuota = 0;
		if($(this).find('td[data-cuota]').attr('data-cuota') != ''){
			$.each($(this).find('td[data-cuota]'),function(kf,vf){
				if( parseFloat($(vf).attr('data-cuota')) < 0 ){
					sumcuota++;
				}
			});
			arrCuotas[ka].cuotas = sumcuota;
		}
	});
	///////// FALTAS //////////////////
	var alumnWarn = '';
	var alumnWarnID = [];
	var alumnDanger = '';
	var alumnDangerID = [];
	var nmAlumnW = 0;
	var nmAlumnD = 0;
	//console.log(arrFaltas[1].asistencias);
	$.each(arrFaltas,function(kf,vf){
		var user = '<a href="#" onClick="javascript:window.open(\'hd.php?v=listaplus&id='+vf.userid+'\',\'Notificar\',\'width=800,height=600\')">'+vf.username+'</a>';
		///var user = MakeLink(vf.username,vf.userid,false);
		if(vf.asistencias.length==1){
			if(vf.asistencias[vf.asistencias.length-1]=='A' ){
				alumnWarn += user+', ';
				alumnWarnID.push(vf.userid);
				nmAlumnW++;
			}
		}
		if(vf.asistencias.length>1){
			if(vf.asistencias[vf.asistencias.length-1]=='A' && vf.asistencias[vf.asistencias.length-2]=='A'){
				alumnDanger += user+', ';
				alumnDangerID.push(vf.userid);
				nmAlumnD++;
			}else if(vf.asistencias[vf.asistencias.length-1]=='A' && vf.asistencias[vf.asistencias.length-2]!='A'){
				alumnWarn += user+', ';
				alumnWarnID.push(vf.userid);
				nmAlumnW++;
			}
		}
	});

	if(alumnWarn != ''){
		$('#faltas_warn').html(alumnWarn+' ha'+(nmAlumnW>1?'n':'')+' faltado a la última clase<br /><br />').append(MakeLink('Notificar a Todos',alumnWarnID.join(','),true));
	}else{
		$('#faltas_warn').hide();
	}
	if(alumnDanger != ''){
		$('#faltas_danger').html(alumnDanger+' ha'+(nmAlumnD>1?'n':'')+' faltado a las 2 últimas clases<br /><br />').append(MakeLink('Notificar a Todos',alumnDangerID.join(','),true));
	}else{
		$('#faltas_danger').hide();
	}
	//////// CUOTAS /////////////////
	var cuotaWarn = '';
	var cuotaWarnID = [];
	var cuotaDanger = '';
	var cuotaDangerID = [];
	var nmCuotaW = 0;
	var nmCuotaD = 0;
	$.each(arrCuotas,function(kf,vf){
		var user = '<a href="#" onClick="javascript:window.open(\'hd.php?v=listaplus&id='+vf.userid+'\',\'Notificar\',\'width=800,height=600\')">'+vf.username+'</a>';
		if(vf.cuotas == 1){
			cuotaWarn += user+', ';
			nmCuotaW++;
			cuotaWarnID.push(vf.userid);
		}
		if(vf.cuotas > 1){
			cuotaDanger += user+', ';
			cuotaDangerID.push(vf.userid);
			nmCuotaD++;
		}
	});
	//////////////////////////////////////////////
	if(cuotaWarn != ''){
		$('#cuotas_warn').html(cuotaWarn+' debe'+(nmCuotaW>1?'n':'')+' 1 cuota<br /><br />').append(MakeLink('Notificar a Todos',cuotaWarnID.join(','),true));
	}else{
		$('#cuotas_warn').hide();
	}
	if(cuotaDanger != ''){
		$('#cuotas_danger').html(cuotaDanger+' debe'+(nmCuotaD>1?'n':'')+' 2 cuotas o más<br /><br />').append(MakeLink('Notificar a Todos',cuotaDangerID.join(','),true));
	}else{
		$('#cuotas_danger').hide();
	}
}
$(function(){
	$(window).scroll(function(){
		if($(this).scrollTop() > $('#listado').offset().top+10){
			$('.table-header-fixed').removeClass('dp-none');
		}else{
			$('.table-header-fixed').addClass('dp-none');
		}
	});
	$(window).resize(function(){
		ResizeHeader();
	});
	BuildHeader();
	NotifyAttendance();
});
</script>