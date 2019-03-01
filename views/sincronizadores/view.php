<script>
$(document).ready(function() {
    $('.sincronizar').submit(function() {
        window.open('', 'sincronizarpopup', 'width=710,height=420');
        this.target = 'sincronizarpopup';
    });
});
</script>
<div class="column" style="width:50%">
	<div class="portlet">
		<div class="portlet-header">Listado de procesos de sincronización</div>
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Nombre</th>
					<th>Descripcion</th>
					<th>Ultima ejecución</th>
					<th></th>
					<th></th>					
				</thead>
				<tbody>
					<tr style="height: 20px;">
						<td align="left">Help Desk</td>
						<td align="left">Actualiza los estados y prioridades de los incidentes.</td>
						<td align="left"></td>
						<td align="left"><a href="sincronizadores.php?v=help_desk" class="button" onclick="return confirm('<?= $H_USER->get_property('firstname');?> estás por comenzar una sincronización, ¿Estás seguro que queres continuar?')">Sincronizar</a></td>
						<td align="left"><a href="sincronizadores.php?v=view&log=help_desk" class="button">Logs</a></td>							
					</tr>
					<tr style="height: 20px;">
						<td align="left">LMS</td>
						<td align="left">Actualiza los datos del LMS con las bases bajadas desde los sistemas de CISCO</td>
						<td align="left"></td>
						<td align="left"><a href="sincronizadores.php?v=lms" class="button">Entrar</a></td>
						<td align="left"><a href="sincronizadores.php?v=view&log=lms" class="button">Logs</a></td>							
					</tr>				
					<tr style="height: 20px;">
						<td align="left">GradeBooks</td>
						<td align="left">Actualiza los datos del LMS con las plantillas de gradebooks bajadas de CISCO</td>
						<td align="left"></td>
						<td align="left">
							<form class="sincronizar" action="sincronizar_gradebooks.php" method="POST" style="float:left;"  target="_blank">	
								<input type="submit" class="button" name="action" value="Sincronizar"  onclick="return confirm('<?= $H_USER->get_property('firstname');?> estás por comenzar una sincronización, ¿Estás seguro que queres continuar?')"/>
							</form>
						</td>
						<td align="left"><a href="sincronizadores.php?v=gradebook_compare" target="_blank" class="button" title="Comparar los nombres de los archivos con el nombre que registran de comisión">Comparar</a></td>													
					</tr>			
				</tbody>
			</table>
		</div>
	</div>
</div>
<div class="column" style="width:50%">
	<div class="portlet">
		<div class="portlet-header">Logs ( Proceso: <?= $logname;?> )</div>
		<div class="portlet-content" >
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody>
							<?php if ($logs):?>
								<?php foreach($logs as $log):?>
						<tr style="height: 30px;" class="ui-widget">						
								<td align="left"><?= $log['name'];?></td>
								<td align="center">
									<form action="sincronizadores.php?v=log" method="POST">
										<input type="hidden" name="type" value="<?=$logname;?>" />
										<input type="hidden" name="name" value="<?=$log['name'];?>" />
										<input type="submit" class="button" name="Desacargar" value="Descargar"/>
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
