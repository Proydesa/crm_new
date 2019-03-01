<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?> 
<div class="column" style="width:80%<?= $ejecuto==1 ? '': ';display:none;' ?>">
	<div class="portlet">
		<div class="portlet-header" bgcolor="#004023">Asistencias</div>
		<div class="portlet-content" >
		<table class="ui-widget"  style="width:100%;" id="asistencia-export" border=1>
				<thead>
					<th>  </th>
					<th>  </th>	
				</thead>
				<tbody style="color: white;">
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
			<table class="ui-widget"  style="width:100%;" id="asistencia-export" border=1>
				<thead class="ui-widget-header" bgcolor="#004023"  border=1>
					<th></th>
					<th>Presentes</th>
					<th>Ausentes</th>
					<th>Excusados</th>
				</thead>
				<tbody style="color: white;"  border=1>
					<tr>
						<td> Alumnos </td>
						<td class="ui-widget-content textCenter " style="background-color: #5cb85c;color: white;"><?= $presentes;?></td>
						<td class="ui-widget-content textCenter " style="background-color: #d9534f;color: white;"><?= $ausentes;?></td>
						<td class="ui-widget-content textCenter" style="background-color: #337ab7;color: white;"><?= $enrolados;?></td>
					</tr>
					
				</tbody>
			</table>
		<br>
		<br>
		<?php foreach($alumnosPorCurso as $row){  ?>
			<table class="ui-widget" align="center" style="width:100%;" border=1>
				<tbody>
				<tr>
				<td colspan="3" class="ui-widget-header textCenter" style="color: white;" bgcolor="#004023"><?= $row['curso']?> - <?= $row['nombre']?> - Total Ausentes (<?= count($row['1'])+count($row['2'])+count($row['3'])?>)</td>
				</tr>
				</tbody>
			</table>
			<table class="ui-widget" align="center" style="width:100%;" border=1>
				<thead class="ui-widget-header" bgcolor="#004023">
					<th >1 Inasistencia</th>
					<th >2 Inasistencia</th>
					<th >3 o mas Inasistencia</th>
				</thead>
				<tbody style="color: white;">
				
				<?php for($i=0;$i<$row['mayorColumna'];$i++){?>
					<tr  data-userid="" class="ui-widget-content" data-username="" >
					<td border=1 style="width:33%" class="textCenter"><?= isset($row[1][$i]) ? $row[1][$i]['apellido']." ".$row[1][$i]['nombre']."<br><mark>".$row[1][$i]['debe']."</mark>" : '<br>&nbsp' ?></td>
					<td border=1 style="width:33%" class="textCenter"><?= isset($row[2][$i]) ? $row[2][$i]['apellido']." ".$row[2][$i]['nombre']."<br><mark>".$row[2][$i]['debe']."</mark>".( $row[2][$i]['ausentesConsecutivos']!=0 ? '<br>('.$row[2][$i]['ausentesConsecutivos'].' Consecutivas)':'' ) : '<br>&nbsp' ?></td>
					<td border=1 style="width:33%" class="textCenter"><?= isset($row[3][$i]) ? $row[3][$i]['apellido']." ".$row[3][$i]['nombre']."<br><mark>".$row[3][$i]['debe']."</mark>".( $row[3][$i]['ausentesConsecutivos']!=0 ? '<br>('.$row[3][$i]['ausentesConsecutivos'].' Consecutivas)':'' )."<br>(".$row[3][$i]['cantidadTotalAusentes']." Inasistencias)" : '<br>&nbsp' ?></td>
				</tr>
				<?php } ?>
				</tbody>
			</table>
			<br>
			<br>
				<?php } ?>
			
				<table class="ui-widget" align="center" style="width:100%;" border=1>
				<thead class="ui-widget-header" >
					<th bgcolor="#004023" style="color: white;">Nro.</th>					
					<th bgcolor="#004023" style="color: white;">Instructor</th>
					<th bgcolor="#004023" style="color: white;">Comision</th>
					<th bgcolor="#004023" style="color: white;">Llegada/Inicio de clase</th>
					<th bgcolor="#004023" style="color: white;">Finalización de clase</th>
				</thead>
				<tbody style="color: white;">
					<?php $nro=1; ?>
					<?php foreach($asistenciaInstructores as $row){
						$colorFin ="";
						$colorFila="";
						$colorInicio="";
						if ($row['Asistencia']!="AUSENTE"){
							if (strtotime($row['fin'])< strtotime($row['deberia_finalizar'].' - 30 minutes')){
								$colorFin = 'background-color:#d9534f;color:white;';
							}
							if (strtotime($row['fin'])>= strtotime($row['deberia_finalizar'].' - 30 minutes') && strtotime($row['fin'])< strtotime($row['deberia_finalizar'].' - 15 minutes')){
								$colorFin = 'background-color:#f0ad4e;color:white;';
							}
							if (strtotime($row['fin'])>= strtotime($row['deberia_finalizar'].' - 15 minutes') && strtotime($row['fin'])<= strtotime($row['deberia_finalizar']) ){
								$colorFin = 'background-color:#5cb85c;color:white;';
							}
							if (strtotime($row['fin'])> strtotime($row['deberia_finalizar'])  ){
								$colorFin = 'background-color:#d9534f;color:white;';
							}
							$colorInicio = strtotime($row['inicio']) <= strtotime($row['deberia_iniciar']) ? 'background-color:#5cb85c;color:white;' : (strtotime($row['inicio']) <= strtotime($row['deberia_iniciar'].' + 15 minutes') ? 'background-color:#f0ad4e;color:white;' : 'background-color:#d9534f;color:white;') ;
						}else{
							$colorFila= 'background-color:#d9534f;color:white;';
							$row['inicio']="";
							$row['fin']="";	
						}
						?>
						<tr  data-userid="" class="ui-widget-content" data-username="" >
							<td style="<?= $colorFila?>"><?=  $nro;$nro++; ?></td>
							<td style="<?= $colorFila?>"><?=  $row['nombre_instructor']; ?></td>
							<td style="<?= $colorFila?>"><?= $row['nombre_comision']; ?></td>
							<td style="<?= $colorFila?> <?= $colorInicio?> "><?=  $row['inicio']; ?></td>
							<td style="<?= $colorFila?><?= $colorFin?>"><?=  $row['fin']; ?></td>
						</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
</div>