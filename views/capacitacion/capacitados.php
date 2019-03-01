<div>	
	<form action="capacitacion.php?v=capacitados" method="POST">
		<div class="column" style="width:20%">
			<div class="portlet">
				<div class="portlet-header">Per&iacute;odos</div>
				<div class="portlet-content" >
					<ul class="noBullet">
						<?php foreach($periodos_user as $periodo_user): ?>
							<li><input type="checkbox" name="periodos[]" class="periodos" value="<?=$periodo_user['periodo'];?>" <?php if(in_array($periodo_user['periodo'],$periodos_sel)) echo "checked"; ?> /><label for="periodos[]"><?=$periodo_user['periodo'];?></label></li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>
			<input type="submit" class="button" value="Consultar" />	
		</div>
	</form>
	<div class="column" style="width:80%">	
		<div class="portlet">
			<div class="portlet-header">Instructores</div>
				<div class="portlet-content" style="overflow:auto;max-height:500px;">		
					<table id="listado" class="ui-widget" style="width:100%">
						<tr style="height: 20px;" class="ui-widget-header">	
							<th>Instructor</th>	
							<th>Curso</th>
							<th>Comisi&oacute;n</th>
						</tr>	
						<?php if($instructores): ?>						
							<?php foreach($instructores as $instructor):?>							
								<tr style="height: 20px;" class="ui-widget-content">
									<td>
										<a href="contactos.php?v=view&id=<?= $instructor['userid'];?>" target="_blank">
											<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
										</a>
										<?= $instructor['inst']; ?>
									</td>		
									<td><?= $instructor['curso']; ?></td>	
									<td>
										<a href="courses.php?v=view&id=<?= $instructor['comiid'];?>" target="_blank">
											<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
										</a>
										<?= $instructor['comi']; ?>
									</td>	
								</tr>					
							<?php endforeach;?>					
						<?php endif; ?>
					</table>
				</div>	
		</div>	
	</div>
</div>