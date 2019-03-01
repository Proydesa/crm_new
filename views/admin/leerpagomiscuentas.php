<br/><br/><br/>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:40%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>DNI</th>
			<th>Curso</th>
			<th>Cuota</th>
			<th>Importe</th>
			<th>Fecha de pago</th>
			<th>Fecha en que se acredita</th>
		</tr>
	<?php foreach ($pagos as $row):?>
		<tr class="ui-widget-content">
			<td align="center"><a href="contactos.php?v=view&id=<?= $row['userid'];?>" target="_blank"><?= $row['dni'];?></a></td>
			<td align="center"><?= $row['curso'];?></td>
			<td align="center"><?= $row['cuota'];?></td>
			<td align="center"><?= numero($row['importe']);?></td>
			<td align="center"><?= $row['fecha_pago'];?></td>
			<td align="center"><?= $row['fecha_acredita'];?></td>
		</tr>
	<?php endforeach;?>
	</table>
	<br/>
	<br/>	
	<div align="center" class="ui-widget" >
			<form enctype="multipart/form-data" action="admin.php?v=leerpagomiscuentas" method="POST">
		    <input type="hidden" name="MAX_FILE_SIZE" value="30000" />
			 Subir y ver archivo: <input name="pagomiscuentasfile" type="file" />
		    <input type="submit" value="Subir archivo" />
		</form>	
	</div>
</div>