<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 510px'";
	}else{
		$menufixed = "";
	}
?>
<div<?= $menufixed ?>>
	<table align="right">
		<tr>
		<div class="ui-widget noprint" align="right">
			<td>
				<form action="reportes.php?v=xls" method="post" id="asistencia-form">
					<span class="button-print noprint" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
					<span id="asistencia" class="button-xls noprint" style="font-size: 9px;"><b>Descargar XLS</b></span>
					<input type="hidden" id="asistencia-table-xls" name="table-xls" />
					<input type="hidden" id="name-xls" name="name-xls" value="reporte.asistencia" />
				</form>
			</td>
		</div>
	</tr>
	</table>
	<form action="<?= $HULK->SELF?>" method="post" name="parametros" style="margin:0; padding:0;">
		<div class="column noprint" style="width:20%">
			<div class="portlet">
				<div class="portlet-header">Per&iacute;odos</div>
				<div class="portlet-content" style="overflow:auto;max-height:220px;">
					<ul class="noBullet">
						<?php foreach($periodos_user as $periodo_user): ?>
						<?php
								if ($periodo_user[2]==1){
									$tooltip="Enero Febrero 20".$periodo_user[0].$periodo_user[1];
								}elseif($periodo_user[2]==2){
									$tooltip="Marzo Julio 20".$periodo_user[0].$periodo_user[1];
								}else{
									$tooltip="Agosto Diciembre 20".$periodo_user[0].$periodo_user[1];
								}
						?>
							<li><input type="checkbox" name="periodos[]" value="<?=$periodo_user;?>" <?php if(in_array($periodo_user,$periodos_sel)) echo "checked"; ?>/><label for="periodo[]" title="<?=$tooltip;?>"><?=$periodo_user;?></label></li>
							<?php if(in_array($periodo_user,$periodos_sel)) $graf_periodo .= "&periodo[]=".$periodo_user;?>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'periodos[]\']').each( function() {	this.checked = true;});">Todos</span> |
			<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'periodos[]\']').each( function() {	this.checked = false;});">Ninguno</span>
		<br/>
		<br/>
			<div class="portlet">
				<div class="portlet-header">Academias</div>
				<div class="portlet-content" style="overflow:auto;max-height:220px;">
					<ul class="noBullet">
						<?php foreach($academias_user as $academia_user): ?>
							<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]" title="<?= $academia_user['name']?>"><?= $academia_user['shortname']?></label></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = true;});">Todos</span> |
			<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;"onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = false;});">Ninguno</span>
		<br/>
		<br/>

			<input type="submit" name="boton"  style="height: 35px; font-size:13px; width:95%; font-weight: bold;" class="button"  value="Ver" />
		</div>
	</form>
	<div class="column" style="width:80%">
		<div class="portlet">
			<div class="portlet-header">Asistencias tomadas</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;" id="asistencia-export">
					<thead>
						<tr class="ui-widget-header" style="height: 20px;">
							<th>Academia</th>
							<th>Comisión</th>
							<th>Tomó asistencia</th>
							<th>Clase</th>
							<th>Fecha en que se tomó</th>
							<th>Instructores</th>
							<th>Per&iacute;odo</th>
						</tr>
					</thead>
					<tbody>
					<?php foreach($rows as $row):?>
						<tr class="ui-widget-content" style="height: 20px;">
							<td class="ui-widget-content"><?= $LMS->GetField('mdl_proy_academy', 'shortname', $row['academyid']) ;?></td>
							<td class="ui-widget-content"><?= $row['fullname'];?></td>
							<td class="ui-widget-content"><?= $row['Tomada_por'];?></td>
							<td class="ui-widget-content" align="center"><?= date("d/m/Y",$row['Clase']);?></td>
							<td class="ui-widget-content" align="center"><?= date("d/m/Y",$row['timetaken']);?></td>
							<td class="ui-widget-content"><?= $row['Titulares'];?></td>
							<td class="ui-widget-content" align="center"><?= $row['periodo'];?></td>
						</tr>
					<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>