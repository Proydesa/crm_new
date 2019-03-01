<div class="ui-widget" align="left">
	<h2><?= $LMS->GetField('mdl_course', 'fullname', $courseid);?></h2>

	<div class="column-p" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Detalle de asistencia</div>
			<div class="portlet-content">
				<?php if($sesiones): ?>
					<table class="ui-widget" align="center" style="width:100%;">
						<thead class="ui-widget-header">
							<th>&nbsp;</th>
							<?php foreach($sesiones as $sesion): ?>
								<th style="font-size:10px;" title="<?= $sesion['description']; ?>"><?= date("d-m", $sesion['sessdate']); ?></th>
							<?php endforeach; ?>
							<th>%</th>
						</thead>
						<?php if($rows): ?>						
						<tbody class="ui-widget-content">
							<?php foreach($rows as $row): ?>
								<?php
									if($row['baja'] > 0):
										$class = "ui-state-error";
										$title = $H_DB->GetField("h_bajas", "detalle", $row['baja']);
									else:
										$class = "";
										$title = "";
									endif;
								?>
								<tr class="<?= $class; ?>" title="<?= $title; ?>">
									<td class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['id']; ?>';">
										<a href="contactos.php?v=view&id=<?= $row['id']; ?>" target="_blank">
											<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
										</a>
										<?= $row['alum']; ?>
									</td>
									<?php foreach($row['asistencias'] as $id => $status): ?>
										<td align="center"><?= $status;?></td>
									<?php endforeach; ?>
									<td align="right"><?= number_format($row['porc'], 2); ?> %</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					<?php else: ?>
						<div align="center"><b>No hay alumnos enrolados.</b></div>
					<?php endif; ?>
					</table>
				<?php else: ?>
					<div align="center"><b>No hay sesiones de asistencia para la comisi&oacute;n</b></div>
				<?php endif; ?>
			</div>
		</div>
		<div align="center"><a align="center" class="button" href="courses.php?v=view&id=<?= $courseid; ?>">Volver</a></div>
	</div>
</div>
