<?php
header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=reporte.xls");  //File name extension was wrong
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private",false);
?> 
<table  id="listado" border="1" name="listado" align="center" style="width:100%;">
	<thead class="">
		<th bgcolor="#004023" style="color: white;" >Nro.</th>
		<th bgcolor="#004023" style="color: white;" >Dia</th>			
		<th bgcolor="#004023" style="color: white;" >Instructor</th>
		<th bgcolor="#004023" style="color: white;" >Comision</th>
		<th bgcolor="#004023" style="color: white;">Llegada/Inicio de clase</th>
		<th bgcolor="#004023" style="color: white;" >Finalización de clase</th>
		<th bgcolor="#004023" style="color: white;" >Observacion</th>
	</thead>
	<tbody>
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
	
			if($instructor!=$row['nombre_instructor']){
				echo "<tr><td><hr></td></tr>";
				$instructor = $row['nombre_instructor'];
				$nro = 1;
			}
			?>
			<tr  data-userid="" class="" data-username="" >
				<td style="width:5%;<?= $colorFila?>"><?=  $nro;$nro++; ?></td>
				<td style="width:10%;<?= $colorFila?>"><?=  $row['fecha']; ?></td>
				<td style="width:20%;<?= $colorFila?>"><?=  $row['nombre_instructor']; ?></td>
				<td style="width:25%;<?= $colorFila?>"><?= $row['nombre_comision']; ?></td>
				<td style="width:5%;<?= $colorFila?> <?= $colorInicio?> "><?=  $row['inicio']; ?></td>
				<td style="width:5%;<?= $colorFila?> <?= $colorFin?> "><?=  $row['fin']; ?></td>
				<td style="width:30%;<?= $colorFila?> "><?=  $row['Observacion']; ?></td>
			</tr>
		<?php } ?>
	</tbody>
</table>

