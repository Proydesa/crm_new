<?php if(isset($cumpleshoyactual) || isset($cumpleshoyotros)): ?>
<div class="column-c">
	<div class="portlet">
		<div class="portlet-header">Listado de alerta de cumpleaños</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Nombre</th>
						<th>Correo Electr&oacute;nico</th>
						<th>Cumpleaños</th>
						<th>Comisión</th>
					</tr>
				</thead>
				<tbody>
					<?php if($cumpleshoyactual):?>
					<tr>
						<td colspan="4" align="center">
							<b>Cumpleaños de usuarios activos en el periodo actual.</b>
						</td>
					</tr>
					<?php foreach($cumpleshoyactual as $row):?>
					<tr style="height: 20px;" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['id'];?>';">
						<td>
							<a href="contactos.php?v=view&id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<?= $row['lastname'];?>, <?= $row['firstname'];?>
						</td>
						<td><?= $row['email'];?></td>
						<td><?= show_fecha($row['fnacimiento']);?></td>
						<td><?= $row['shortname'];?></td>
					</tr>
				<?php endforeach;?>
				<?php else:?>
					<tr>
						<td colspan="4" align="center">
							<b>No hay usuarios que cumplan hoy activos en el periodo actual.</b>
						</td>
					</tr>
				<?php endif;?>
				<?php if($cumpleshoyotros):?>
					<tr>
						<td colspan="4" align="center">
							<b>Cumpleaños de usuarios de otros periodos.</b>
						</td>
					</tr>
					<?php foreach($cumpleshoyotros as $row):?>
					<tr style="height: 20px;" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['id'];?>';">
						<td>
							<a href="contactos.php?v=view&id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<?= $row['lastname'];?>, <?= $row['firstname'];?>
						</td>
						<td><?= $row['email'];?></td>
						<td><?= show_fecha($row['fnacimiento']);?></td>
						<td><?= $row['shortname'];?></td>
					</tr>
				<?php endforeach;?>
			<?php endif;?>
				</tbody>
				</tfoot>
			</table>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Tareas Pendientes</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Fecha</th>
						<th>Academia</th>
						<th>Pendiente</th>
						<th>&nbsp</th>
					</tr>
				</thead>
				<tbody>
				<?php
					$tareasPendientes= $LMS->GetAll("SELECT c.*,(SELECT name FROM mdl_proy_academy where id= c.id_academia) academia,date_format(FROM_UNIXTIME(c.fec_alta),'%d/%m/%Y %H:%i') fecha_alta FROM {$HULK->dbname}.h_contactos_de_la_red c, {$HULK->dbname}.h_contactos_de_la_red_usuarios cu WHERE c.pendientes='S' AND c.cod_estado='A' AND c.id_contactos_de_la_red= cu.id_contactos_de_la_red AND cu.cod_estado='A' AND cu.id_usuario = {$H_USER->get_property('id')}");
				?>
				<?php foreach($tareasPendientes as $tarea):?>
				<tr>
				<td width="15%"><?= $tarea['fecha_alta'] ?></td>
				<td width="25%"><a href='./contactos_de_la_red.php?v=search&idAcademia=<?= $tarea['id_academia'] ?>'><?= $tarea['academia'] ?></a></td>
				<td width="55%"><?= $tarea['descripcion_pendientes'] ?></td>
				<td width="5%"><button type="button" style="width:45%;height:20px" class="button-editar ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="check(<?= $tarea['id_contactos_de_la_red'] ?>)" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-check"></span><span class="ui-button-text"><span class="ui-button-icon-primary ui-icon ui-icon-check"></span></span></button></td>
				</tr>
				<?php endforeach;?>

				</tbody>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<?php endif;?>


<div id="result" style="display:none"></div>


<script>
function check(id){
$("#result").load("./contactos_de_la_red.php?v=check&idContacto="+id);
alert("El Tarea fue actualizada");
window.location.reload();
}
</script>