<div class="column-c" style="width:80%">
	<div class="portlet">
		<div class="portlet-header">Contactos Administrativos</div>
		<div class="portlet-content" >
			<table class="tablesorterfilter" align="center" style="width:100%;">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Nombre</th>					
					<th>Correo Electrónico</th>
					<th></th>
					<th></th>
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
					<?= $row['name'];?></td>
					<td><?= $row['email'];?></td>
					<td></td>
					<td></td>
				</tr>
			<?php endforeach;?>
			</tbody>
				<tfoot>
					<tr style="height: 16px;">
						<th colspan="6" align="right">
						</th>
					</tr>
				</tfoot>			
			</table>
		</div>
	</div>
</div>