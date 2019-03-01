<div class="ui-widget" align="left">
	<h2 class="ui-widget">
		<a href="contactos.php?v=view&id=<?=$id;?>">
			<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$id);?>
		</a>
		-> Actividades
	</h2>

	<div class="column-c" style="width:700px">
		<div class="portlet">
		<div class="portlet-header">Actividad</div>
		<div class="portlet-content">
	<table class="ui-widget" align="center" style="width:100%;">
			<thead class="ui-widget-header">
				<tr>
					<th style="width:60px;"></th>
					<th style="width:180px;">Asunto</th>
					<th style="width:120px;">Añadido por</th>
					<th style="width:70px;">Fecha</th>
					<th>Prioridad</th>
					<th>Estado</th>
				</tr>
			</thead>
				<tbody class="ui-widget-content">
					<?php foreach($activitys as $activity):?>
					<tr class="ui-widget-content" style="text-align:center;" class="activity" >
							<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span>
								(<b><?= $activity['id'];?></b>)
							</td>
							<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
								<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>			
							</td>
							<td>
								<a href="contactos.php?v=view&id=<?=$activity['userid'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
								</a>
							</td>
							<td><?= date('d-m-Y',$activity['startdate']);?></td>
							<td><?= $activity['priority'];?></td>
							<td><?= $activity['status'];?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<p align="center">
			<!--
				<button type="button" class="agregar" onClick="window.location.href='hd.php?v=nuevo';" >Nueva Actividad</button>
			-->
			</p>
		</div>
	</div>

</div>
