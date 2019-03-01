<div class="ui-widget noprint" align="right">
	<span align="right">
		<form action="reportes.php?v=xls" method="post" id="capac-form">  			
				<span id="capac" class="button-xls" style="font-size: 9px;">XLS</span>
				<input type="hidden" id="capac-table-xls" name="table-xls" />  
				<input type="hidden" id="name-xls" name="name-xls" value="maincontacts" />  	
			</form>	
	</span>
</div>

<div>
	<form action="capacitacion.php?v=contactos" method="post">	
		<div class="column noprint" style="width:30%">		
			<div class="portlet">
				<div class="portlet-header">Convenios</div>
					<div class="portlet-content" style="overflow:auto;max-height:300px;">	
						<ul class="noBullet">	
							<?php foreach($convenios as $convenio):?>
								<li><input type="checkbox" name="convenios[]" value="<?= $convenio['id'];?>" <?php if(in_array($convenio['id'],$convsel)) echo "checked"; ?> /><label for="convenios[]"><?= $convenio['name']?></label></li>
							<?php endforeach;?>	
						</ul>	
					</div>	
			</div>
			<input type="submit" class="button" value="Consultar" />	
		</div>	
	</form>	
	<div class="column" style="width:70%">	
		<div class="portlet">
			<div class="portlet-header">Main Contacts y Contactos Administrativos</div>
				<div class="portlet-content" style="overflow:auto;max-height:500px;">		
					<table id="listado" class="ui-widget" style="width:100%">
						<tr style="height: 20px;" class="ui-widget-header">	
							<th>Usuario</th>
							<th>Rol</th>
							<th>E-Mail</th>	
							<th>capacia</th>
						</tr>	
						<?php if($mcs): ?>						
							<?php foreach($mcs as $mc):?>
								<tr style="height: 20px;" class="ui-widget-content">
									<td><?= $mc['mc']; ?></td>
									<td><?= $mc['role'];?></td>
									<td><?= $mc['email'];?></td>		
									<td><?= $mc['acad'];?></td>		
								</tr>		
							<?php endforeach;?>					
						<?php endif; ?>
					</table>
				</div>	
		</div>	
	</div>
</div>

<!-- Export -->
<table id="capac-export" class="ui-widget" border="1" style="width:100%;display:none;">
	<tr style="height: 20px;" class="ui-widget-header">	
		<th>Usuario</th>
		<th>Rol</th>
		<th>E-Mail</th>	
		<th>capacia</th>
	</tr>	
	<?php if($mcs): ?>						
		<?php foreach($mcs as $mc):?>
			<tr style="height: 20px;" class="ui-widget-content">
				<td><?= $mc['mc']; ?></td>
				<td><?= $mc['role'];?></td>
				<td><?= $mc['email'];?></td>		
				<td><?= $mc['acad'];?></td>		
			</tr>		
		<?php endforeach;?>					
	<?php endif; ?>
</table>




