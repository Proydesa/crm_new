<script>
$(document).ready(function() {
    $('.sincronizar').submit(function() {
        window.open('', 'sincronizarpopup', 'width=700,height=320');
        this.target = 'sincronizarpopup';
    });
});
</script>
	<div class="portlet">
		<div class="portlet-content">
			<form action="sincronizadores.php?v=lms" method="POST" enctype="multipart/form-data">
			<p style="margin:10px"><b>Subir nuevo archivo para sincronizar:</b>
			<input type="hidden" name ="MAX_FILE_SIZE" value="5000000">
			<input type="file" name="archivo" class="press"  style="height:30px;" />
			<input type="submit" name="subir" value="subir" class="button">
			</p>
			</form>
		</div>
	</div>
<div class="column" style="width:50%">
	<div class="portlet">
		<div class="portlet-header">Archivos de sincronización</div>
		<div class="portlet-content" style="overflow: auto; height: 500px;">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class='ui-widget-header'>
				<tr>
					<th class='ui-widget-header'>Archivo</th>
					<th class='ui-widget-header'>Operaciones</th>
				</tr>
				</thead>
				<tbody>
					<?php if ($archivos):?>
						<?php foreach($archivos as $row):?>
						<tr style="height: 30px;" class="ui-widget-content">						
							<td align="left" class='ui-widget-content' width="250px"><?= $row['name'];?></td>
							<td align="center" class='ui-widget-content'>
								<form action="sincronizadores.php?v=lms" method="POST" style="float:left; margin-left:10px;">
									<input type="hidden" name="name" value="<?=$row['name'];?>" />
									<input type="hidden" name="tipo" value="upload" />
									<input type="submit" class="button" name="action" value="Descargar"/>
									<input type="submit" class="button" name="action" value="Borrar"/>
								</form>
								<form action="sincronizadores.php?v=lms_view" method="POST" style="float:left;"  target="_blank">
									<input type="hidden" name="name" value="<?=$row['name'];?>" />
									<input type="hidden" name="tipo" value="upload" />
									<input type="submit" class="button" name="action" value="Analizar"/>
								</form>									
								<form class="sincronizar" action="sincronizar_lms.php" method="GET" style="float:left;"  target="_blank">	
									<input type="hidden" name="name" value="<?=$row['name'];?>" />
									<input type="submit" class="button" name="action" value="Sincronizar" onclick="return confirm('<?= $H_USER->get_property('firstname');?> estás por comenzar una sincronización, ¿Estás seguro que queres continuar?')"/>
								</form>
							</td>
						</tr>
						<?php endforeach;?>
					<?php endif;?>	
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="column" style="width:50%">
	<div class="portlet">
		<div class="portlet-header">Historico</div>
		<div class="portlet-content" style="overflow: auto; height: 500px;">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class='ui-widget-header'>
				<tr>
					<th>Archivo</th>
					<th>Operaciones</th>
				</tr>
				</thead>
				<tbody>
					<?php if ($archivos_h):?>
						<?php foreach($archivos_h as $row):?>
						<tr style="height: 30px;" class="ui-widget-content">						
							<td align="left" class='ui-widget-content' width="400px"><?= $row['name'];?></td>
							<td align="center" class='ui-widget-content'>
								<form action="sincronizadores.php?v=lms" method="POST" style="float:left;"   target="_blank">
									<input type="hidden" name="name" value="<?=$row['name'];?>" />
									<input type="hidden" name="tipo" value="historico" />
									<input type="submit" class="button" name="action" value="Descargar"/>
								</form>	
							</td>
						</tr>
						<?php endforeach;?>
					<?php endif;?>	
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="column" style="width:100%">
	<div class="portlet">
		<div class="portlet-header">Log de actividad</div>
		<div class="portlet-content" align="center" style="overflow: auto; max-height: 500px;">
		</div>
	</div>
</div>