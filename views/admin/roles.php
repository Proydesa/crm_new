<div class="column-c">
	<h2 class="ui-widget">Roles y permisos >>  <?= $role_name?> </h2>
	<div class="column" style="width:50%">
		<div class="portlet">
			<div class="portlet-header">Roles</div>
			<div class="portlet-content">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th width="180px">Nombre</th>
						<th>Descripcion</th>
						<th>Cant.</th>
						<th></th>
					</thead>
					<tbody>
						<?php foreach($roles as $role):?>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="left"> <?= $role['name'];?></td>
								<td align="left"><?= $role['summary'];?></td>
								<td align="center"><?= $role['users'];?></td>
								<td align="center"><a href="?v=roles&r=<?= $role['id'];?>" class="button" >Ver</a></td>
							</tr>
						<?php endforeach;?>

						<?php if ($H_USER->has_capability("role/create")):?>
							<tr style="height: 20px;">
								<td colspan="4"></td>
							</tr>
							<form id="form_0" action="admin.php?v=roles&action=modifiedrole" method="post" style="margin:0;padding:0;">
							<tr class="ui-widget-content">
								<td colspan="3" align="center">
									Role: <input type="text" name="name" value="" style="width:100px;">
									Descripcion: <input type="text" name="summary" value="" style="width:200px;">
									<input type="hidden" name="id" value="0">
									<input type="hidden" name="r" value="<?= $r;?>"> 								
								</td>
								<td align="center">
											<input type="submit" class="button" name="" value="Nuevo" />
								</td>
							</tr>
							</form>
						<?php endif;?>

					</tbody>
				</table>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-content">
				<script>$(function(){	$('#userlist').makeacolumnlists({cols: 3, colWidth: 0, equalHeight: 'ul', startN: 1});});</script>
				<ul id="userlist" class="noBullet">
						<?php foreach($role_assignments as $ra):?>
						<li style="margin:5px;">
							<a href="contactos.php?v=view&id=<?= $ra['userid'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<a href="contactos.php?v=view&id=<?=$ra['userid'];?>"><?= $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$ra['userid'])?></a> 
						</li>
						<?php endforeach;?>
				</ul>
			</div>
		</div>

	</div>
	<div class="column" style="width:50%">
		<div class="portlet">
			<div class="portlet-header">Capacidades ( Role: <?= $role_name?> )</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody>
						<?php foreach($role_capabilities as $rolec):?>
							<tr style="height: 30px;" class="ui-widget">
								<td align="left"><b><?= $rolec['capability'];?></b> (<?= $rolec['summary'];?>)</td>							
								<td align="left">
						<?php if ($H_USER->has_capability("role/edit")): // AND $r!=1?>
										<form id="form_<?= $rolec['capability'];?>" action="admin.php?v=roles&r=<?= $r;?>&action=modifiedcapacity" method="post" style="margin:0;padding:0;">
											<input type="hidden" name="capability" value="<?= $rolec['capability'];?>">
											<input type="hidden" name="summary" value="<?= $rolec['summary'];?>">
											<input type="hidden" name="roleid" value="<?= $r;?>">
											<input type="hidden" name="r" value="<?= $r;?>"> 
											<input type="submit" class="button" name="" value="Prohibir" />
										</form>
						<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-header">Discapacidades ( Role: <?= $role_name?> )</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody>
						<?php foreach($role_disabilities as $rolec):?>
							<tr style="height: 20px;" class="ui-widget">
								<td align="left"><b><?= $rolec['capability'];?></b> (<?= $rolec['summary'];?>)</td>							
								<td align="left">
										<form id="form_<?= $rolec['capability'];?>" action="admin.php?v=roles&r=<?= $r;?>&action=modifiedcapacity" method="post" style="margin:0;padding:0;">
											<input type="hidden" name="r" value="<?= $r;?>"> 
											<input type="hidden" name="capability" value="<?= $rolec['capability'];?>">
											<input type="hidden" name="summary" value="<?= $rolec['summary'];?>">
											<input type="hidden" name="roleid" value="<?= $r;?>"> 
											<input type="submit" class="button" name="" value="Permitir" />
										</form>
								</td>
							</tr>
						<?php endforeach;?>
							<tr style="height: 20px;">
								<td colspan="2"></td>							
							</tr>
							<?php if ($H_USER->has_capability("role/edit")): ?>
								<form id="form_1" action="admin.php?v=roles&action=modifiedcapacity" method="post" style="margin:0;padding:0;">
								<tr class="ui-widget-content">	
									<td align="center">
										<input type="hidden" name="r" value="<?= $r;?>"> 
										<input type="hidden" name="roleid" value="1"> 
										Capacidad:		<input type="text" name="capability" value="" style="width:100px;">
										Descripción:	<input type="text" name="summary" value="" style="width:200px;">
									</td>
									<td align="center">
												<input type="submit" class="button" name="" value="Nueva" />
									</td>
								</tr>
								</form>					
							<?php endif;?>
						</tbody>
				</table>
			</div>
		</div>
	</div>
</div>