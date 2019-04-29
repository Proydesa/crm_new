<script>
function enabledAllSelect(){
	var inputs = document.getElementsByClassName('selectProfReemplazo');
	for(var i = 0; i < inputs.length; i++) {
		inputs[i].disabled = false;
	}
}
</script>
<form action="asistencia.php?v=reporte" method="POST" style="margin:0; padding:0;">
	<div class="column" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Filtros</div>
			<div class="portlet-content" >
			<table class="ui-widget" align="center">
					<tr>
						<td>Fechas: </td>
						<td><?php $view->jquery_datepicker2("#startdate");?><?php $view->jquery_datepicker2("#enddate");?>
						<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= isset($diaInicio) ? $diaInicio : date('d-m-Y') ; ?>" />
						<input id="enddate" style="width:90px;" name="enddate" type="text" align="center" value="<?= isset($diaFin) ? $diaFin : date('d-m-Y') ; ?>" />
						</td>
						<td>&nbsp;</td>
					</tr>
					<tr>
							<td><b>Instructores:</b></td>
							<td class="ui-widget-content">
								<select multiple name="idInstructor[]"  style="width:500px;">
									<option value="0">Todos...</option>
									<?php foreach($instructores as $instructor): ?>
										<option value="<?= $instructor['id']; ?>" <?=$instructor['id']== (isset($_POST['idInstructor']) ? $_POST['idInstructor']:1) ? 'selected': '' ?> ><?= $instructor['fullname']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
							<td class="ui-widget-content">Para seleccionar varios instructores<br>tiene que mantener presionada la tecla Ctrl,<br>y hacer clic en el nombre de cada instructor.</td>
					</tr>
					<tr>
						<td><b> </b></td>
						<td><input type="submit" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" onClick='this.form.action="asistencia.php?v=reporte";' class="button"  value="Buscar" /><input type="submit" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" class="button"  value="Exportar" onClick='this.form.action="asistencia.php?v=reporteXLS";' /></td>
						<td>&nbsp;</td>			
					</tr>
				</table>
			</div>
		</div>
	</div>		
</form>
<?php
if ($ejecuto==1):
?>
<table class="ui-widget listado" id="listado" name="listado" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th >Nro.</th>
						<th >Dia</th>			
						<th >Instructor</th>
						<th >Comision</th>
						<th >Llegada/Inicio de clase</th>
						<th >Finalización de clase</th>
						<th >Observacion</th>
					</thead>
					<tbody>
						<?php $nro=1; ?>
						<?php 
						$instructor = $asistenciaInstructores['nombre_instructor'];
						
						foreach($asistenciaInstructores as $row){
							$colorFin ="";
							$colorFila="";
							$colorInicio="";
							if ($row['Asistencia']!="AUSENTE"){
								if (strtotime($row['fin'])< strtotime($row['deberia_finalizar'].' - 30 minutes')){
									$colorFin = ' bg-danger ';
								}
								if (strtotime($row['fin'])>= strtotime($row['deberia_finalizar'].' - 30 minutes') && strtotime($row['fin'])< strtotime($row['deberia_finalizar'].' - 15 minutes')){
									$colorFin = ' bg-warning ';
								}
								if (strtotime($row['fin'])>= strtotime($row['deberia_finalizar'].' - 15 minutes') && strtotime($row['fin'])<= strtotime($row['deberia_finalizar']) ){
									$colorFin = ' bg-success ';
								}
								if (strtotime($row['fin'])> strtotime($row['deberia_finalizar'])  ){
									$colorFin = ' bg-danger ';
								}
								$colorInicio = strtotime($row['inicio']) <= strtotime($row['deberia_iniciar']) ? ' bg-success ' : (strtotime($row['inicio']) <= strtotime($row['deberia_iniciar'].' + 15 minutes') ? ' bg-warning ' : ' bg-danger ') ;
								if ($row['inicio']==""){
									$colorInicio='';
								}
								if ($row['fin']==""){
									$colorFin='';
								}
								
							}else{
								$colorFila= ' bg-danger ';
								$row['inicio']="";
								$row['fin']="";	
							}
							
							if($instructor!=$row['nombre_instructor']){
								echo "<tr><td colspan='7'><hr></td></tr>";
								$instructor = $row['nombre_instructor'];
								$nro = 1;
							}
							
							?>
							
							<tr  data-userid="" class="ui-widget-content" data-username="" >
								<td style="width:5%" class="ui-widget-content textCenter <?= $colorFila?>"><?=  $nro;$nro++; ?></td>
								<td style="width:10%" class="ui-widget-content textCenter <?= $colorFila?>"><?=  $row['fecha']; ?></td>
								<td style="width:20%" class="ui-widget-content textCenter <?= $colorFila?>"><?=  $row['nombre_instructor']; ?></td>
								<td style="width:25%" class="ui-widget-content textCenter <?= $colorFila?>"><a href='./courses.php?v=view&id=<?= $row['id_comision']; ?>'><?= $row['nombre_comision']; ?></a></td>
								<td style="width:5%" class="ui-widget-content textCenter <?= $colorFila?> <?= $colorInicio?> "><?=  $row['inicio']; ?></td>
								<td style="width:5%" class="ui-widget-content textCenter <?= $colorFila?> <?= $colorFin?> "><?=  $row['fin']; ?></td>
								<td style="width:30%" class="ui-widget-content textCenter <?= $colorFila?> "><?=  $row['Observacion']; ?></td>
							</tr>
							
						<?php } ?>
					</tbody>
				</table>
<?php
endif;
?>

