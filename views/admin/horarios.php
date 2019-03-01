<br/><br/><br/>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:50%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>Nombre</th>
			<th>Turno</th>
			<th></th>
		</tr>
			<?php foreach($horarios as $horario):?>
				<form id="form_<?= $horario['id'];?>" action="admin.php?v=horarios&stage=1" method="post" style="margin:0;padding:0;">
					<tr class="ui-widget-content">			
						<td>
							<input type="text" name="name" value="<?= $horario['name'];?>" style="width:50%;">
							<input type="hidden" name="id" value="<?= $horario['id'];?>">			
						</td>
						<td>
							<select name="turno">
								<option value="M" <?php if($horario['turno']=="M") echo "selected"; ?>>Mañana</option>
								<option value="T" <?php if($horario['turno']=="T") echo "selected"; ?>>Tarde</option>
								<option value="N" <?php if($horario['turno']=="N") echo "selected"; ?>>Noche</option>
							</select>
						</td>
						<td align="center">
							<a href="#" class="button" onClick="$('#form_<?= $horario['id'];?>').submit();" >Guardar</a>
						</td>
					</tr>
				</form>
			<?php endforeach;?>
				<tr class="ui-widget-content">
					<td colspan="5" height="20px">&nbsp;</td>
				</tr>				
				<form id="form_0" action="admin.php?v=horarios&stage=1" method="post" style="margin:0;padding:0;">
					<tr class="ui-widget-content">			
						<td>
							<input type="text" name="name" value="" style="width:50%;">
							<input type="hidden" name="id" value="0"> 
						</td>
						<td>
							<select name="turno">
								<option value="M">Mañana</option>
								<option value="T">Tarde</option>
								<option value="N">Noche</option>
							</select>
						</td>		
						<td align="center">
							<a href="#" class="button" onClick="$('#form_0').submit();">Nuevo</a>
						</td>
					</tr>
				</form>
	</table>
</div>
