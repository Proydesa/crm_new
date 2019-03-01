<h2 class="ui-widget">Errores de sincronizaci&oacute;n en nombres de comisi&oacute;n</h2>


<form action="<?= $HULK->SELF?>" method="post" name="reportes" style="margin:0; padding:0;">
<div class="column" style="width:20%">
	<div class="portlet">
		<div class="portlet-header">Per&iacute;odos</div>
		<div class="portlet-content" >
			<ul class="noBullet">
				<?php foreach($periodos_user as $periodo_user): ?>
				<li><input type="checkbox" name="periodos[]" value="<?=$periodo_user['periodo'];?>" <?php if(in_array($periodo_user['periodo'],$periodos_sel)) echo "checked"; ?>/><label for="periodo[]"><?=$periodo_user['periodo'];?></label></li>
				<?php endforeach; ?>	
			</ul>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Academias</div>
		<div class="portlet-content" style="overflow:auto;max-height:200px;">
			<ul class="noBullet">
				<?php foreach($academias_user as $academia_user): ?>
					<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]"><?= $academia_user['shortname']?></label></li>
				<?php endforeach; ?>	
			</ul>
		</div>
	</div>
	<button type="submit" class="button">Ver</button>
</div>
</form>

<div class="column" style="width:80%">
	<div class="portlet">
		<div class="portlet-header">Errores de sincronizaci&oacute;n en nombres de comisi&oacute;n</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>CLASSID</th>
						<th>Comisi&oacute;n</th>
						<th>Academia</th>
						<th>Curso</th>
						<th>Per&iacute;odo</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($rows as $error):?>
					<tr class="ui-widget-content" style="height: 20px;">
						<td><?=$error['classid']?></td>
						<td class="ui-state-error"><?=$error['comision']?></td>
						<td><?= $LMS->GetField('mdl_proy_academy','name',$error['academy'])?></td>
						<td><?=$error['course']?></td>
						<td align="center"><?=$error['periodo']?></td>
						<td align="center"><?=date("d/m/Y",$error['date'])?></td>
					</tr>
				<?php endforeach; ?>
				
				</tbody>
				<tfoot>
					<tr class="ui-widget-header" style="height: 16px;">
						<th colspan="7" align="right">Page: <?php echo $links_pages;?></th>
					</tr>
				</tfoot>		
			</table>
<p>Estos errores no impiden la sincronizacion pero advierten de que el formato del nombre de la comision
creada en Academy Conection no es el ideal y estandarizado que usamos en el LMS de Fundación Proydesa.</p>

		</div>
	</div>
</div>
