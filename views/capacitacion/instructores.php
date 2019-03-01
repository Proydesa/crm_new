
<h2 class="ui-widget">Listado de Instructores</h2>

<div class="column" style="width:80%">
	<div class="portlet">
		<div class="portlet-header">Instructores</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Instructor</th>
						<th>Carrera</th>
						<th>Alta</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($rows as $row):?>
						<?php if($row['carreras']!=""): ?>
							<tr class="ui-widget-content" style="height: 20px;" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['userid'];?>';">
								<td>
									<a href="contactos.php?v=view&id=<?= $row['userid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<?= $row['username'];?>
								</td>							
								<td><?= $row['fullname'];?></td>
								<td><?= $row['startdate'];?></td>							
							</tr>
						<?php endif; ?>
					<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr style="height: 16px;">
						<th colspan="6" align="right">
							<?php echo $links_pages;?>
						</th>
					</tr>
				</tfoot>		
			</table>
		</div>
	</div>
</div>
<form action="<?= $HULK->SELF?>" method="POST" name="filtros" style="margin:0; padding:0;">
	<div class="column" style="width:20%">
		<div class="portlet">
			<div class="portlet-header">Filtros de b&uacute;squeda</div>
			<div class="portlet-content" >
				<br/>
				<center>
					<input class="search" id="localsearch" type="search" autocomplete="off" size="30" maxlength="2048" name="q" title="Buscar en Hulk" value="<?= $q;?>"  spellcheck="false" />
				</center>
				<br/>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-header">Academias</div>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<table class="ui-widget" width="100%">
					<?php foreach($academias as $academia): ?>
						<tr>
							<td title="<?= $carrera['name']; ?>">
								<input type="checkbox" name="academias[]" value="<?=$academia['id'];?>" <?php if(in_array($academia['id'],$academias_sel)) echo "checked"; ?>/><label for="academias[]">&nbsp;<?= $academia['name']; ?></label>
							</td>
						</tr>					
					<?php endforeach; ?>	
				</table>
			</div>
		</div>			
		<div class="portlet">
			<div class="portlet-header">Carreras</div>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<table class="ui-widget" width="100%">
					<?php foreach($carreras as $carrera): ?>
						<tr>
							<td title="<?= $carrera['fullname']; ?>">
								<input type="checkbox" name="carreras[]" value="<?=$carrera['id'];?>" <?php if(in_array($carrera['id'],$carreras_sel)) echo "checked"; ?>/><label for="carreras[]">&nbsp;<?=$carrera['shortname'];?></label>
							</td>
						</tr>					
					<?php endforeach; ?>	
				</table>
			</div>
		</div>		
		<button type="submit" class="button">Ver</button>
	</div>
</form>
