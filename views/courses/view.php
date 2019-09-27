<div class="ui-widget">
	<div class="ui-widget" align="left">
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
									<td class="ui-widget-content"><?php if($row['intensivo']==2){
																			echo "Banda Negativa";
																		}else{
																			echo $HULK->course_cursada[$row['intensivo']];
																		}
																	?>
									</td>
									<td class="ui-widget-content" align="right"><b>LMS ID:</b></td>
									<td class="ui-widget-content"><a href="<?= $HULK->lms_wwwroot;?>/course/view.php?id=<?= $row['id'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><?= $row['id'];?></td>
								</tr>
								<tr>
									<td class="ui-widget-content" align="right"><b>Modalidad:</b></td>
									<td class="ui-widget-content"><?php if($row['modalidad']==2){
																			echo "Blended";
																		}else{
																			echo $HULK->course_modalidad[$row['modalidad']];
																		};
																	?></td>
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

										<?php if($H_USER->has_capability('convenios/view') && !$hasblocks): ?>
										<a class="button" href="courses.php?v=reset_course_blocks&id=<?=$row['id']?>">Generar Bloques</a>
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
					<?php 
					$tc = 0; 
					if(!empty($asistencias_canceladas)): 
						foreach($asistencias_canceladas as $asistencia):							
					?>
					<div class="bg-danger alert-item">
						<?= ConvertDays(date('D',$asistencia['sessdate'])).'. '.date('d',$asistencia['sessdate']).' '.ConvertMonth(date('M',$asistencia['sessdate'])).'. - '.$asistencia['description'] ?>
					</div>
					<?php $tc++; endforeach; endif; ?>
					<?php if(!$tc): ?>
					<p>No hubo clases canceladas</p>
					<?php endif; ?>
				</div>

				<div class="flex-item alert-container">
					<h4>Feriados</h4>
					<?php 
					if(!empty($asistencias_canceladas)): 
						foreach($asistencias_canceladas as $asistencia): 
							if(preg_match('/Feriado/',$asistencia['description'])):
					?>
					<div class="bg-primary alert-item">
						<?=ConvertDays(date('D',$asistencia['sessdate'])).'. '.date('d',$asistencia['sessdate']).' '.ConvertMonth(date('M',$asistencia['sessdate'])).'. - '.$asistencia['description']?>
					</div>
					<?php endif; endforeach; endif; ?>
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
							<th colspan="7"></th>
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
							<th>Img</th>
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
							<?php if(count($asistencias)>0): foreach($asistencias as $ka=>$asistencia): ?>
							
							<th class="textCenter sz-6">
								(<?= $ka+1 ?>)
								<br>
								<?= ConvertDays(date('D',$asistencia['sessdate'])).' '.date('d',$asistencia['sessdate']); ?>
								<br>
								<?=	ConvertMonth(date('M',$asistencia['sessdate'])); ?>								
							</th>

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
						if($eStatus === false){
							$eStatus = $H_DB->GetField_sql("SELECT finalgrade FROM vw_enrolados WHERE userid=".$estudiante['id']." AND id=".$row['id']);
							$eStatus=array (''=>'E','0.00000'=>'E','1.00000'=>'I','2.00000'=>'F','3.00000'=>'P')[$eStatus];
						}
					?>
						<tr data-userid="<?= $estudiante['id'] ?>" data-username="<?= $estudiante['namefull'] ?>" >
							<td class="textCenter"><?= $ke+1 ?></td>
							<td class="textCenter"><?= $estudiante['username']; ?></td>
							<td class="textCenter"><a title="<?= $estudiante['obs']; ?>" href="contactos.php?v=view&id=<?= $estudiante['id']; ?>" target="_blank" ><?= $estudiante['fullname']; ?></a></td>
							<td class="textCenter"><?= $estudiante['autorizousoimg']; ?></td>
							<td class="textCenter">I</td>
							<td class="<?= $arrStatus[$eStatus]; ?> textCenter"><?= $eStatus ?></td>
							<td class="textCenter"><?= date('d',$estudiante['timestart']).'/'.ConvertMonth(date('M',$estudiante['timestart'])).'/'.date('Y',$estudiante['timestart']); ?></td>


							<!-- Cuotas-->
							<?php

							if(count($cuotas)>0):
								foreach($cuotas as $kc=>$cuota):
									$difcuota = $LMS->getValorCuota($row['id'],$estudiante['id'],$cuota['cuota']);
							?>
							<td data-cuota="<?= $difcuota ?>" class="<?= $difcuota == '' ? '' : ($difcuota >= 0 ? 'bg-success' : 'bg-danger') ?> textCenter">
								<?= $difcuota == '' ? '-' : ($difcuota >= 0 ? 'ok' : '$'.number_format($difcuota,0,',','.')) ?>
							</td>
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

<!-- FIXED COLUMN -->
	<div class="table-header-fixed column-c dp-none" >
		<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
			<div class="portlet-content">
				<table id="table_fixed" class="ui-widget" align="center" style="width:100%;">
				</table>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript" src="views/courses/view.js"></script>
