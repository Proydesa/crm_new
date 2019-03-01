<div class="ui-widget" align="left">
<h2><?= $LMS->GetField('mdl_course', 'fullname', $courseid);?></h2>
<h3>Clase: <?= date("d-m-Y",$sessdate);?></h3>
<h3>Tomada por: <?= $LMS->GetField('mdl_user', 'CONCAT(lastname, ", ", firstname)', $takenby);?></h3>

<div class="column-p" style="width:60%">
	<div class="portlet">
		<div class="portlet-header">Detalle de asistencia</div>
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Usuario</th>
					<th>Estudiante</th>
					<th>Status</th>
					<th>Observaciones</th>
				</thead>
				<tbody class="ui-widget-content">
					<?php foreach($rows as $row): ?>
						<tr>
							<td><?= $row['username']; ?></td>
							<td><?= $row['student']; ?></td>
							<td align="center"><?= $row['acronym']; ?></td>
							<td><?= $row['remarks']; ?></td>
						</tr>			
					<?php endforeach; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
