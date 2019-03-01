<div class="column-c" style="width:80%">
<table class="ui-widget" align="center" style="width:100%;">
	<thead>
		<tr class="ui-widget-header">
			<th>&nbsp;</th>
			<th>Alumno</th>
			<th>DNI</th>
			<th>Comisi&oacute;n</th>
		</tr>
	</thead>
	<tbody>
		<?php if($alumnos_lms){ ?>
			<?php $i=1; ?>
			<?php foreach($alumnos_lms as $alumno): ?>
				<tr class="ui-widget-content">
					<td align="right"><b><?= $i; ?></b></td>
					<td>
						<a href="contactos.php?v=view&id=<?= $alumno['userid'];?>" target="_blank">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
						&nbsp;<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$alumno['userid']); ?>
					</td>
					<td><?= $LMS->GetField('mdl_user','username',$alumno['userid']); ?></td>
					<td><?= $alumno['shortname']; ?></td>
				</tr>
				<?php $i++; ?>
			<?php endforeach; ?>
		<?php } ?>
		</tbody>
	</table>
</div>
