<div class="column-c" style="width:800px">

	<div class="portlet">
		<div class="portlet-header">Inscriptos por carrera</div>
		<!--<div class="portlet-content"  id="table_inscriptos">

		</div>-->
		<div class="portlet-content">
<form action="" method="POST">
			<p>Está es la tabla comparativa entre los inscriptos del periodo <?=$periodo_anterior;?> al día <?= date("d-m-Y",$hoy_anterior);?> y los inscriptos
			del periodo <?=$periodo_actual;?> al día <?= date("d-m-Y",$hoy);?>.</p>
<br/><span class="button" style=" width:30%; font-weight: bold; height:25px;" onClick="$('#acaselec').slideToggle();">Selecciónar academias</span>
<div id="acaselec" style="overflow:auto;height:300px; display:none;">
<br/>
		<span class="button" style="height: 25px; font-size:11px; width:20%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = true;});">Todas</span>
		<span class="button" style="height: 25px; font-size:11px; width:20%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = false;});">Ninguna</span>  | 
		<input type="submit" class="button" name="Mostrar" value="Mostrar"></input>

		<script>$(function(){	$('#acalist').makeacolumnlists({cols: 7, colWidth: "100px", equalHeight: 'ul', startN: 1});});</script>
	<ul id="acalist" class="noBullet">
		<?php foreach($academias_user as $academia_user): ?>
			<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]"><?= $academia_user['shortname']?></label></li>
			<?php if(in_array($academia_user['id'],$acad_sel)) $graf_acad .= "&academy[]=".$academia_user['id'];?>
		<?php endforeach; ?>	
	</ul>
	</div>
</form>	
			<table class="ui-widget" align="center" style="width:70%;">
				<thead>
					<tr style="height: 20px;" class="ui-widget-header">
						<th rowspan="2">Carrera</th>
						<th align="center" style="width: 20px;" colspan="3"><?=$periodo_anterior;?></th>
						<th align="center" style="width: 20px;" colspan="3"><?=$periodo_actual;?></th>
						<th align="center" style="width: 20px;" colspan="3">Diferencia</th>
					</tr>
					<tr style="height: 20px;" class="ui-widget-header">
						<th align="center" style="width: 20px;">Insc.</th>
						<th align="center" style="width: 20px;">Bajas</th>
						<th align="center" style="width: 20px;">Cambios</th>
						<th align="center" style="width: 20px;">Insc.</th>
						<th align="center" style="width: 20px;">Bajas</th>
						<th align="center" style="width: 20px;">Cambios</th>
						<th align="center" style="width: 20px;">Insc.</th>
						<th align="center" style="width: 20px;">Bajas</th>
						<th align="center" style="width: 20px;">Cambios</th>
					</tr>					
				</thead>
				<tbody class="ui-widget-content">
						<?php foreach($comparativa as $carrera => $datos):?>
							<tr style="height: 20px;">
								<td class="press" ondblclick=""  class="ui-widget-content">
									<a href="#" target="_blank">	
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><?= $carrera;?>
								</td>
								<td align="center" class="ui-widget-content"><?= $datos[$periodo_anterior]['insc'];?></td>
								<td align="center" class="ui-widget-content"><?= $datos[$periodo_anterior]['bajas'];?></td>
								<td align="center" class="ui-widget-content"><?= $datos[$periodo_anterior]['cambios'];?></td>
								<td align="center" class="ui-widget-content"><?= $datos[$periodo_actual]['insc'];?></td>
								<td align="center" class="ui-widget-content"><?= $datos[$periodo_actual]['bajas'];?></td>
								<td align="center" class="ui-widget-content"><?= $datos[$periodo_actual]['cambios'];?></td>
								<td align="center" class="ui-widget-content"><b><?= ($datos[$periodo_actual]['insc']-$datos[$periodo_anterior]['insc']);?></b></td>
								<td align="center" class="ui-widget-content"><b><?= ($datos[$periodo_actual]['bajas']-$datos[$periodo_anterior]['bajas']);?></b></td>
								<td align="center" class="ui-widget-content"><b><?= ($datos[$periodo_actual]['cambios']-$datos[$periodo_anterior]['cambios']);?></b></td>
							</tr>
							<?php $total[$periodo_anterior]['insc'] +=$datos[$periodo_anterior]['insc'];?>
							<?php $total[$periodo_actual]['insc'] +=$datos[$periodo_actual]['insc'];?>
							<?php $total[$periodo_anterior]['bajas'] +=$datos[$periodo_anterior]['bajas'];?>
							<?php $total[$periodo_actual]['bajas'] +=$datos[$periodo_actual]['bajas'];?>
							<?php $total[$periodo_anterior]['cambios'] +=$datos[$periodo_anterior]['cambios'];?>
							<?php $total[$periodo_actual]['cambios'] +=$datos[$periodo_actual]['cambios'];?>
						<?php endforeach;?>
					<tr style="height: 20px;">
						<td align="left" class="ui-widget-content"><b>Total</b></td>
						<th align="center" class="ui-widget-content"><b><?= $total[$periodo_anterior]['insc'];?></b></th>
						<th align="center" class="ui-widget-content"><b><?= $total[$periodo_anterior]['bajas'];?></b></th>
						<th align="center" class="ui-widget-content"><b><?= $total[$periodo_anterior]['cambios'];?></b></th>
						<th align="center" class="ui-widget-content"><b><?= $total[$periodo_actual]['insc'];?></b></th>
						<th align="center" class="ui-widget-content"><b><?= $total[$periodo_actual]['bajas'];?></b></th>
						<th align="center" class="ui-widget-content"><b><?= $total[$periodo_actual]['cambios'];?></b></th>
						<th align="center" class="ui-widget-content"><b><?= ($total[$periodo_actual]['insc']-$total[$periodo_anterior]['insc']);?></b></th>
						<th align="center" class="ui-widget-content"><b><?= ($total[$periodo_actual]['bajas']-$total[$periodo_anterior]['bajas']);?></b></th>
						<th align="center" class="ui-widget-content"><b><?= ($total[$periodo_actual]['cambios']-$total[$periodo_anterior]['cambios']);?></b></th>
					</tr>
				</tbody>
			</table>		
		</div>
	</div>