<h2 class="ui-widget">Errores de sincronizaci&oacute;n de usuarios</h2>
<form action="<?= $HULK->SELF?>" method="post" name="parametros" style="margin:0; padding:0;">
	<div class="column" style="width:20%">
		<?php if ($filtros):?>
			<div class="portlet">
				<div class="portlet-header">Filtros de b&uacute;squeda</div>
				<div class="portlet-content" >
					<ul class="noBullet">
						<?php foreach($filtros as $filtro): ?>
						<li><?= $filtro;?></li>
						<?php endforeach; ?>	
					</ul>
				</div>
			</div>
		<?php endif;?>
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
		<div class="portlet-header">Errores de sincronizaci&oacute;n de usuarios</div>
		<div class="portlet-content">
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Academia</th>
						<th>ACID</th>
						<th>Username (DNI)</th>
						<th>Nombre</th>
						<th>Apellido</th>
						<th>E-Mail</th>
						<th>Rol</th>
						<th>Fecha</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($rows as $error):
						unset($class);	
						if ($error['username']==''){$class['username']="ui-state-error";}
						if ($error['firstname']==''){$class['firstname']="ui-state-error";}
						if ($error['lastname']==''){$class['lastname']="ui-state-error";}
						if ($error['email']==''){$class['email']="ui-state-error";}?>		
						<tr class="ui-widget-content" style="height: 20px;">
							<td><?= $LMS->GetField('mdl_proy_academy','shortname',$error['academy']);?></td>
							<td><?=$error['acid'];?></td>
							<td class="<?=$class['username']?>"><?=$error['username']?></td>
							<td class="<?=$class['firstname']?>"><?=$error['firstname']?></td>
							<td class="<?=$class['lastname']?>"><?=$error['lastname']?></td>
							<td class="<?=$class['email']?>"><?=$error['email']?></td>
							<td><?= $LMS->GetField('mdl_role','name',$error['role']); ?></td>
							<td align="center"><?=date("d/m/Y",$error['date'])?></td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
					<tr class="ui-widget-header" style="height: 16px;">
						<th colspan="9" align="right">Page: <?php echo $links_pages;?></th>
					</tr>
				</tfoot>		
			</table>
		</div>
	</div>
</div>

