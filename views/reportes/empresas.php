<div class="ui-widget noprint" align="right">
	<span  class="ui-widget noprint" align="right">
		<form action="reportes.php?v=xls" method="post" id="detalle-form">
			<span class="button-print" onClick="document.body.offsetHeight;window.print();"><b>Imprimir</b></span>
			<span id="detalle" class="button-xls"><b>Descargar XLS</b></span>
			<input type="hidden" id="detalle-table-xls" name="table-xls" />
			<input type="hidden" id="name-xls" name="name-xls" value="reporte.listado" />
		</form>
	</span>
</div>
<form action="<?= $HULK->SELF?>" method="post" name="parametros" style="margin:0; padding:0;">
<div class="column" style="width:20%">
	<div class="portlet">
		<div class="portlet-header">Per&iacute;odos</div>
		<div class="portlet-content" style="overflow:auto;max-height:160px;">
			<ul class="noBullet">
				<?php foreach($LMS->getPeriodos() as $periodo_user): ?>
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
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'periodos[]\']').each( function() {	this.checked = true;});">Todos</span> |
	<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;"onClick="$('input[type=checkbox][name=\'periodos[]\']').each( function() {	this.checked = false;});">Ninguno</span>
<br/>
<br/>
	<div class="portlet">
		<div class="portlet-header">Cursos</div>
		<div class="portlet-content" >
			<p class="noBullet"  style="overflow:auto;max-height:150px;">
				<table  class="ui-widget" width="100%">
				<?php foreach($LMS->getCursosModelo() as $curso): ?>
				<tr>
					<td class="ui-widget-content">
						<label for="cursos[]" ><?=$curso['shortname'];?></label>
					</td>
					<td class="ui-widget-content">
						<input type="checkbox" name="cursos[]" value="<?=$curso['id'];?>" <?php if(in_array($curso['id'],$cursos_sel)) echo "checked"; ?>/>
					</td>
				</tr>
				<?php endforeach; ?>
				</table>
			</p>
		</div>
	</div>
	<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'cursos[]\']').each( function() {	this.checked = true;});">Todos</span> |
	<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;"onClick="$('input[type=checkbox][name=\'cursos[]\']').each( function() {	this.checked = false;});">Ninguno</span>
<br/>
<br/>

	<input type="submit" name="boton"  style="height: 35px; font-size:13px; width:95%; font-weight: bold;" class="button"  value="Ver" />
</div>
</form>


<div class="column" style="width:80%">
	<div class="portlet">
		<div class="portlet-header">Empresas por actividad</div>
		<div class="portlet-content" >
			<table id="detalle-export" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th></th>
						<th>Empresa</th>
						<th>Email</th>
						<th>Contacto</th>
						<th>Teléfono</th>
						<th>Carrera</th>
						<th>Periodo</th>
						<th>Alumno</th>
						<th>DNI</th>
						<th>Email</th>
						<th>Telefono</th>
						<th>Celular</th>
					</tr>
				</thead>
				<tbody>
				<?php $i=0;?>
				<?php foreach($rows as $row):?>
					<?php $i++;?>
					<tr class="ui-widget-content" style="height: 20px;">
						<td class="ui-widget-content"><?= $i;?></td>
						<td class="ui-widget-content" align="left" class="press" ondblclick="window.location.href='grupos.php?v=view&id=<?=$row['id'];?>';">
						<a href="grupos.php?v=view&id=<?=$row['id'];?>" target="_blank">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
						<b><?php echo utf8_encode($row['empresa']);?></b>
						</td>
						<td class="ui-widget-content"><?= $row['email_empresa'];?></td>
						<td class="ui-widget-content"><?php echo utf8_encode($row['datos_de_contacto']);?></td>
						<td class="ui-widget-content"><?= $row['telefono'];?></td>
						<td class="ui-widget-content"><?= $row['shortname'];?></td>
						<td class="ui-widget-content"><?= $row['periodo'];?></td>
						<td class="ui-widget-content"><?php echo utf8_encode($row['firstname']." ".$row['lastname']);?></td>
						<td class="ui-widget-content"><?= $row['username'];?></td>
						<td class="ui-widget-content"><?= $row['email'];?></td>
						<td class="ui-widget-content"><?= $row['phone1'];?></td>
						<td class="ui-widget-content"><?= $row['phone2'];?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
