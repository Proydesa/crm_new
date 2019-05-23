<script>
	$(document).ready(function() {

		$('#models').change(function(){
			location.href=("admin.php?v=cuotas&courseid="+$(this).val());
		});
		
		$('#button-cuotas').click(function(){
			$('#cuotas').append('$ <input type="text" name="cuotas[]" style="width:40px;">');
		});
		
		$('#button-cuotasint').click(function(){
			$('#cuotas_int').append('$ <input type="text" name="cuotas_intensivo[]" style="width:40px;">');
		});
		$('#button-cuotasuno').click(function(){
			$('#cuotas_uno').append('$ <input type="text" name="periodo_uno[]" style="width:40px;">');
		});
		$('#button-cuotasdos').click(function(){
			$('#cuotas_dos').append('$ <input type="text" name="periodo_dos[]" style="width:40px;">');
		});
		$('#button-cuotastres').click(function(){
			$('#cuotas_tres').append('$ <input type="text" name="periodo_tres[]" style="width:40px;">');
		});
		$('#button-cuotasunoint').click(function(){
			$('#cuotas_uno_int').append('$ <input type="text" name="periodo_uno_int[]" style="width:40px;">');
		});
		$('#button-cuotasdosint').click(function(){
			$('#cuotas_dos_int').append('$ <input type="text" name="periodo_dos_int[]" style="width:40px;">');
		});
		$('#button-cuotastresint').click(function(){
			$('#cuotas_tres_int').append('$ <input type="text" name="periodo_tres_int[]" style="width:40px;">');
		});		
	});
</script>

<br/>
<div class="ui-widget" style="margin-top: 60px">
	<div align="center">
		<b>Curso:</b> <select name="courseid" id="models">
			<option value=""></option>
			<?php foreach($models as $row):?>
				<option value="<?= $row['id']; ?>" <?php if($row['id']==$courseid) echo 'selected="selected"'; ?>><?= $row['fullname'];?></option>
			<?php endforeach;?>
		</select>
	</div>
	<form id="form" method="POST" action="admin.php?v=cuotas&stage=1">
		<table class="ui-widget" align="center">
			<tr class="ui-widget-header" style="height: 20px;">
				<th>Curso</th>
				<th>Cuotas</th>
			</tr>
			<?php if($courseid): ?>
				<tr>
					<td rowspan="14"><?= $LMS->GetField("mdl_course", "fullname", $courseid); ?></td>
				</tr>
				<tr>			
					<th class="ui-widget-content">Por defecto</th>
				</tr>
				<tr>					
					<td>
						<div id="cuotas" style="float:left;">
						<b>Regulares: </b>
							<?php foreach($cuotas as $cuota):?>						
								$ <input type="text" name="cuotas[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>
						</div>
						<span class="button-add2" id="button-cuotas" style="font-size:9px;float:left;">Agregar</span>					
					</td>
				</tr>
				<tr>			
					<td>
						<div id="cuotas_int" style="float:left;">
						<b>Intensivos: </b>
							<?php foreach($cuotas_intensivo as $cuota):?>						
								$ <input type="text" name="cuotas_intensivo[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotasint" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>
				<tr>			
					<th class="ui-widget-content">Primer periodo</th>
				</tr>
				<tr>	
					<td>
						<div id="cuotas_uno" style="float:left;">
						<b>Regulares: </b>
							<?php foreach($periodo_uno as $cuota):?>						
								$ <input type="text" name="periodo_uno[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotasuno" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>
				<tr>	
					<td>
						<div id="cuotas_uno_int" style="float:left;">
						<b>Intensivos: </b>
							<?php foreach($periodo_uno_int as $cuota):?>						
								$ <input type="text" name="periodo_uno_int[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotasunoint" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>				
				<tr>			
					<th class="ui-widget-content">Segundo periodo</th>
				</tr>
				<tr>						
					<td>
						<div id="cuotas_dos" style="float:left;">
						<b>Regulares: </b>
							<?php foreach($periodo_dos as $cuota):?>						
								$ <input type="text" name="periodo_dos[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotasdos" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>
				<tr>						
					<td>
						<div id="cuotas_dos_int" style="float:left;">
						<b>Intensivos: </b>
							<?php foreach($periodo_dos_int as $cuota):?>						
								$ <input type="text" name="periodo_dos_int[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotasdosint" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>
				<tr>			
					<th class="ui-widget-content">Tercer periodo</th>
				</tr>
				<tr>						
					<td>
						<div id="cuotas_tres" style="float:left;">
						<b>Regulares: </b>
							<?php foreach($periodo_tres as $cuota):?>						
								$ <input type="text" name="periodo_tres[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotastres" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>
				<tr>						
					<td>
						<div id="cuotas_tres_int" style="float:left;">
						<b>Intensivos: </b>
							<?php foreach($periodo_tres_int as $cuota):?>						
								$ <input type="text" name="periodo_tres_int[]" value="<?= $cuota; ?>" style="width:40px;">
							<?php endforeach; ?>			
						</div>
						<span class="button-add2" id="button-cuotastresint" style="font-size:9px;float:left;">Agregar</span>									
					</td>
				</tr>
				<tr>											
					<td colspan="2" align="center">
						<span class="button" onClick="$('#form').submit();">Guardar</span>
					</td>			
				</tr>
				<input type="hidden" name="courseid" value="<?= $courseid; ?>" />
			<?php endif; ?>
		</table>
	</form>		
</div>
