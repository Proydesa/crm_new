<div class="portlet" style="width:100%;">
	<div class="portlet-header">Formulario de contactos del portal</div>
	<div class="portlet-content">
		<form name="reporte_form" id="reporte_form" method="POST" action="reportes_hd.php?v=reporte">
			<table class="ui-widget" id="listado" align="center" width="100%">
				<tr class="ui-widget-header">
					<th>ID</th>
					<th width="180px">email					</th>
					<th style="width:120px;">Nombre</th>
					<th style="width:130px;">Tema</th>
					<th>Mensaje</th>
				</tr>
				<?php foreach($rows as $row): ?>
					<tr>
						<td><?= $row['id'];?></td>
						<td><?= $row['fromemail'];?></td>
						<td><?= $row['fromname'];?></td>
						<td><?= $row['subject'];?></td>
						<td><?= $row['summary'];?></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</form>
	</div>
</div>	