<?php
if (!isset($_POST['idAcademia'] )){
	$_POST['idAcademia'] = 1;
	$dia= date('d-m-Y');
}
?>
<form action="reportes_lms.php?v=segAsistencia" method="POST" style="margin:0; padding:0;">
	<div class="column" style="width:80%">
		<div class="portlet">
			<div class="portlet-header">Filtros</div>
			<div class="portlet-content" >
			<table class="ui-widget" align="center">
					<tr>
						<td>Fecha: </td>
						<td><?php $view->jquery_datepicker2("#startdate, #enddate");?>
							<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= $dia; ?> " />
						</td>
					</tr>
					<tr>
							<td><b>Academias</b></td>
							<td class="ui-widget-content">
								<select name="idAcademia"  style="width:500px;">
									<option value="0">Todos...</option>
									<?php foreach($academies as $academia): ?>
										<option value="<?= $academia['id']; ?>" <?=$academia['id']== $_POST['idAcademia'] ? 'selected': '' ?> ><?= $academia['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
					</tr>
					<tr>
						<td><b> </b></td>
						<td><input type="submit" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" class="button"  value="Ver reporte" /></td>			
					</tr>
				</table>
			</div>
		</div>
	</div>		
</form>
	<div class="column" style="width:80%<?= $ejecuto==1 ? '': ';display:none;' ?>">
		<div class="portlet">
			<div class="portlet-header">Asistencias tomadas</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;" id="asistencia-export">
					<thead>
						<th>  </th>
						<th>  </th>	
					</thead>
					<tbody>
						<tr>
							<td> Dia </td>
							<td class="ui-widget-content textCenter"><?= $dia;?></td>
						</tr>
						<tr>
							<td> Total de Alumnos </td>
							<td class="ui-widget-content textCenter"><?= $totalDeAlumnos;?></td>
						</tr>
					</tbody>
				</table>
				<table class="ui-widget" align="center" style="width:100%;" id="asistencia-export">
					<thead class="ui-widget-header">
						<th></th>
						<th>Presentes</th>
						<th>Ausentes</th>
						<th>Excusados</th>
					</thead>
					<tbody>
						<tr>
							<td> Alumnos </td>
							<td class="ui-widget-content textCenter bg-success"><?= $presentes;?></td>
							<td class="ui-widget-content textCenter bg-danger"><?= $ausentes;?></td>
							<td class="ui-widget-content textCenter"><?= $enrolados;?></td>
						</tr>
						
					</tbody>
				</table>
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th >1 Inasistencia</th>
						<th >2 Inasistencia</th>
						<th >3 Inasistencia</th>
					</thead>
					<tbody>
					
					
					
					<?php for($i=0;$i<$mayorColumna;$i++){?>
						<tr  data-userid="" class="ui-widget-content" data-username="" >
						<td class="ui-widget-content textCenter"><?= isset($alumnos1Inasistencia[$i]) ? "<a href='contactos.php?v=view&id=".$alumnos1Inasistencia[$i]['idAlumno']."'>" .$alumnos1Inasistencia[$i]['apellido']." ".$alumnos1Inasistencia[$i]['nombre']."<br><mark>".$alumnos1Inasistencia[$i]['debe']."</mark></a>" : '<br>&nbsp' ?></td>
						<td class="ui-widget-content textCenter"><?= isset($alumnos2Inasistencia[$i]) ? "<a href='contactos.php?v=view&id=".$alumnos2Inasistencia[$i]['idAlumno']."'>" .$alumnos2Inasistencia[$i]['apellido']." ".$alumnos2Inasistencia[$i]['nombre']."<br><mark>".$alumnos2Inasistencia[$i]['debe']."</mark></a>" : '<br>&nbsp' ?></td>
						<td class="ui-widget-content textCenter"><?= isset($alumnos3Inasistencia[$i]) ? "<a href='contactos.php?v=view&id=".$alumnos3Inasistencia[$i]['idAlumno']."'>" .$alumnos3Inasistencia[$i]['apellido']." ".$alumnos3Inasistencia[$i]['nombre']."<br><mark>".$alumnos3Inasistencia[$i]['debe']."</mark></a>" : '<br>&nbsp' ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				<br>
				<br>
				<br>
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th >Nro.</th>					
						<th >Instructor</th>
						<th >Comision</th>
						<th >Llegada del Instructor</th>
						<th >Salida del Instructor</th>
					</thead>
					<tbody>
						<?php $nro=1; ?>
						<?php foreach($asistenciaInstructores as $row){  ?>
							<tr  data-userid="" class="ui-widget-content" data-username="" >
								<td class="ui-widget-content textCenter"><?=  $nro;$nro++; ?></td>
								<td class="ui-widget-content textCenter"><?=  $row['nombre_instructor']; ?></td>
								<td class="ui-widget-content textCenter"><a href='./courses.php?v=view&id=<?= $row['id_comision']; ?>'><?= $row['nombre_comision']; ?></a></td>
								<td class="ui-widget-content textCenter  <?= strtotime($row['inicio']) <= strtotime($row['deberia_iniciar']) ? ' bg-success ' : (strtotime($row['inicio']) <= strtotime($row['deberia_iniciar'].' + 15 minutes') ? ' bg-warning ' : ' bg-danger ') ?>"><?=  $row['inicio']; ?></td>
								<td class="ui-widget-content textCenter  <?= strtotime($row['fin']) > strtotime($row['deberia_finalizar']) ? ' bg-danger ' : (strtotime($row['fin']) <= strtotime($row['deberia_finalizar'].' - 30 minutes') ? ' bg-danger ' : (strtotime($row['fin']) > strtotime($row['deberia_finalizar'].' - 15 minutes')) ? ' bg-success ': ' bg-warning ' )?>"><?=  $row['fin']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
