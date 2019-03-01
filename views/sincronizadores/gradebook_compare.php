<div class="column-c" style="width:90%">
	<div class="portlet">
		<div class="portlet-header">Archivos que no coincide el nombre con el campo del nombre de comisíon</div>
		<div class="portlet-content" align="center" style="overflow: auto; max-height: 500px;">
			<table class='ui-widget' id='listado' width="100%">
			<tr class='ui-widget-header' ><th>Academia</th><th>Curso</th><th>Archivo</th><th>Nombre de comisión (dentro de archivo)</th></tr>
			<?php foreach($cambiados AS $cambiado):?>
				<tr class='ui-widget-content'>
					<td><?= $cambiado['academia']?></td>
					<td><?= $cambiado['curso']?></td>
					<td>Calificaciones-<?= $cambiado['file']?>.csv</td>
					<td><?= $cambiado['shortname']?></td>
				</tr>
			<?php endforeach;?>
		</div>
	</div>
</div>