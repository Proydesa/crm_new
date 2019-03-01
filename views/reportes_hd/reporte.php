<div class="portlet" style="width:100%;">
	<div class="portlet-header">Reporte Help Desk</div>
	<div class="portlet-content">
		<form name="reporte_form" id="reporte_form" method="POST" action="reportes_hd.php?v=reporte">
			<table class="ui-widget" id="listado" align="center" width="100%">
				<tr class="ui-widget-header">
					<th>ID</th>
					<th width="180px">
						Academia<br />
						<select name="academias" style="width:170px;" OnChange="$('#reporte_form').submit();">
							<option value="">Todos</option>
							<?php foreach($academias as $academia): ?>
								<?php if($acad_sel==$academia['id']): $sel="selected"; else: $sel=""; endif; ?>
								<option value="<?= $academia['id']; ?>" <?= $sel; ?>><?= $academia['name']; ?></option>
							<?php endforeach; ?>
						</select>
					</th>
					<th style="width:120px;">T&iacute;tulo</th>
					<th style="width:130px;">
						Usuario<br />
						<select name="usuarios" style="width:120px;" OnChange="$('#reporte_form').submit();">
							<option value="">Todos</option>
							<?php foreach($usuarios as $usuario): ?>
								<?php if($user_sel==$usuario['id']): $sel="selected"; else: $sel=""; endif; ?>
								<option value="<?= $usuario['id']; ?>" <?= $sel; ?>><?= $usuario['user']; ?></option>
							<?php endforeach; ?>
						</select>
					</th>
					<th style="width:130px;">
						Representante<br />
						<select name="representantes" style="width:120px;" OnChange="$('#reporte_form').submit();">
							<option value="">Todos</option>
							<?php foreach($representantes as $representante): ?>
								<?php if($rep_sel==$representante['id']): $sel="selected"; else: $sel=""; endif; ?>
								<option value="<?= $representante['id']; ?>" <?= $sel; ?>><?= $representante['user']; ?></option>
							<?php endforeach; ?>
						</select>			
					</th>
					<th style="width:80px;">Fecha de <br />Ingreso</th>
					<th style="width:80px;">
						Prioridad<br />
						<select name="prioridades" style="width:70px;" OnChange="$('#reporte_form').submit();">
							<option value="">Todos</option>
							<?php foreach($prioridades as $prioridad): ?>
								<?php if($prio_sel==$prioridad['id']): $sel="selected"; else: $sel=""; endif; ?>
								<option value="<?= $prioridad['id']; ?>" <?= $sel; ?>><?= $prioridad['name']; ?></option>
							<?php endforeach; ?>
						</select>			
					</th>
					<th>
						Estado<br />
						<select name="estados" style="width:100px;" OnChange="$('#reporte_form').submit();">
							<option value="">Todos</option>
							<?php foreach($estados as $estado): ?>
								<?php if($est_sel==$estado['id']): $sel="selected"; else: $sel=""; endif; ?>
								<option value="<?= $estado['id']; ?>" <?= $sel; ?>><?= $estado['name']; ?></option>
							<?php endforeach; ?>
						</select>				
					</th>
					<th style="width:130px;">
						Categor&iacute;a<br />
						<select name="categorias" style="width:120px;" OnChange="$('#reporte_form').submit();">
							<option value="">Todos</option>
							<?php foreach($categorias as $categoria): ?>
								<?php if($cat_sel==$categoria['id']): $sel="selected"; else: $sel=""; endif; ?>
								<option value="<?= $categoria['id']; ?>" <?= $sel; ?>><?= $categoria['name']; ?></option>
							<?php endforeach; ?>
						</select>					
					</th>
					<th style="width:80px;">Antig&uuml;edad (Horas)</th>
				</tr>
				<?php foreach($rows as $row): ?>
					<tr>
						<td align="center" onclick="window.location.href='hd.php?v=representante-view&id=<?= $row['id'];?>';" nowrap>
							<a href="hd.php?v=representante-view&id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<b><?= $row['id']; ?></b>&nbsp;
						</td>
						<td><?= $LMS->GetField("mdl_proy_academy", "name", $row['acad']); ?>&nbsp;</td>
						<td><?= $row['subject']; ?>&nbsp;</td>
						<td><?= $LMS->GetField("mdl_user", "CONCAT(firstname, ' ', lastname)", $row['userid']); ?>&nbsp;</td>
						<td><?= $LMS->GetField("mdl_user", "CONCAT(firstname, ' ', lastname)", $row['assignto']); ?>&nbsp;</td>
						<td align="center"><?= date("d-m-Y H:i:s", $row['startdate']); ?>&nbsp;</td>
						<td align="center"><?= $row['priority']; ?>&nbsp;</td>
						<td align="center"><?= $row['status']; ?>&nbsp;</td>
						<td align="center"><?= $row['category']; ?>&nbsp;</td>
						<td align="center"><?= intval($row['horas']/3600); ?>&nbsp;(<?= $row['dias']; ?>)</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</form>
	</div>
</div>	