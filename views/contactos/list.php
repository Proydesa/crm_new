<div class="column-c">
	<div class="portlet">
		<div class="portlet-header">Contactos</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>ID</th>
						<th>Username</th>
						<th>Nombre</th>
						<th>Correo Electr&oacute;nico</th>
						<th>ACID</th>
						
					</tr>
				</thead>
				<tbody>
				<?php foreach($rows as $row):?>
					<tr style="height: 20px;" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['id'];?>';">
						<td><?= $row['id'];?></td>
						<td><?= $row['username'];?></td>
						<td>
							<a href="contactos.php?v=view&id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<?= $row['lastname'];?>, <?= $row['firstname'];?>
						</td>
						<td><?= $row['email'];?></td>
						<td><?= $row['acid'];?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr style="height: 16px;">
						<th colspan="8" align="right"></th>
					</tr>
					<tr style="height: 16px;">
						<th colspan="8" align="right">
							<?php echo $links_pages;?>
						</th>
					</tr>
				</tfoot>		
			</table>
		</div>
	</div>
</div>
