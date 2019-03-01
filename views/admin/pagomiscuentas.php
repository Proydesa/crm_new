<br/><br/><br/>
<div class="ui-widget">

	<table class="ui-widget" align="center" style="width:40%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>Fecha</th>
			<th>Cantidad</th>
			<th></th>
			<th></th>
		</tr>
	<?php foreach ($fechas as $row):?>
		<tr class="ui-widget-content">
			<td align="center">Cuotas generadas entre el <?= $row['minimo'];?> y el <?= $row['maximo'];?></td>				
			<td align="center"><?= $row['cant'];?></td>
			<td align="center">
				<?php if (file_exists($HULK->dirroot.'\data\pagomiscuentas\FAC3634.'.$row['semana'])){?>
				<a href="admin.php?v=pagomiscuentas&semana=<?= $row['semana'];?>">Actualizar</a>
				<?php }else{ ?>
				<a href="admin.php?v=pagomiscuentas&semana=<?= $row['semana'];?>">Generar</a>
				<?php } ?>
			</td>
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