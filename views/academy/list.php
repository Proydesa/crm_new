<div class="column-c" style="width:95%">
	<div class="portlet">
		<div class="portlet-header">Academias</div>
		<div class="portlet-content" >
<table class="ui-widget" width="25%">
<thead>
<tr>
<th class="ui-widget-header" width="75%">Estado</th>
<th class="ui-widget-header">Cantidad</th>
</tr>
</thead>
<tbody>
<tr>
<td class="ui-widget-content"><strong>Activas (A):</strong></td>
<td class="ui-widget-content"><?= $contar[0]; ?></td>
</tr>
<tr>
<td class="ui-widget-content"><strong>Inctivas (I):</strong></td>
<td class="ui-widget-content"><?= $contar[1]; ?></td>
</tr>
<tr>
<td class="ui-widget-content"><strong>Bajas (B):</strong></td>
<td class="ui-widget-content"><?= $contar[3]; ?></td>
</tr>
<tr>
<td class="ui-widget-content" align="right"><strong>TOTAL:</strong></td>
<td class="ui-widget-content"><strong><?= $contar[0]+$contar[1]+$contar[3] ; ?></strong></td>
</tr>
</tbody>
</table>        
			<table class="tablesorterfilter" align="center" style="width:100%;">
			<thead>
				<tr>
					<th width="30px">ID</th>
					<th width="60px">Estado</th>
					<th width="40px">Sigla</th>					
					<th>Nombre</th>
					<th width="40px">Pais</th>
					<th>Ciudad</th>
					<th>Convenios</th>
				</tr>
			</thead>
			<tbody>
			<?php foreach($rows as $row):?>
				<tr style="height: 20px;" ondblclick="window.location.href='academy.php?v=view&id=<?= $row['id'];?>';">
					<td><?= $row['id'];?></td>
					<td><?= substr($HULK->acad_status[$row['status']],0,1);?></td>
					<td><?= $row['shortname'];?></td>
					<td>
						<a href="academy.php?v=view&id=<?= $row['id'];?>" target="_blank">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<?= $row['name'];?></td>
					<td><?= $row['country'];?></td>
					<td><?= $row['city'];?></td>
					<td><?= $LMS->getAcademyConveniosActivosField($row['id']);?></td>
				</tr>
			<?php endforeach;?>
			</tbody>
			</table>
		</div>
	</div>
</div>