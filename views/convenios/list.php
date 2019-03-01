<div class="column-c" style="width:40%">
	<div class="portlet">
		<div class="portlet-header">Convenios</div>
		<div class="portlet-content" >
			<table class=" tablesorter" align="center" style="width:100%;">
			<thead>
				<tr>
					<th>Id</th>
					<th>Nombre</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $row):?>
				<tr style="height: 20px;" ondblclick="window.location.href='convenios.php?v=view&id=<?= $row['id'];?>';">
					<td><b><?= $row['id'];?></b></td>
					<td>
						<a href="convenios.php?v=view&id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
						<?= $row['fullname'];?>
					</td>
				</tr>
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
