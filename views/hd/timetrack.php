<div class="ui-widget" align="left">
	<h2 class="ui-widget">
		<a href="contactos.php?v=view&id=<?=$id;?>">
			<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$id);?>
		</a>
		-> Actividades
	</h2>

	<div class="column-c" style="width:90%;">
		<div class="portlet">
		<div class="portlet-header">Actividad</div>
		<div class="portlet-content">
	<table class="ui-widget" align="center" style="width:100%;">
			<thead class="ui-widget-header">
				<tr>
					<th style="width:40%;" colspan="2">Resumen - Tiempo Total Consumido en Febrero: xx:xx</th>
					<th style="width:60%;">Asunto</th>
				</tr>
			</thead>
				<tbody class="ui-widget-content">
					<?php foreach($activitys as $activity):?>
					<tr class="ui-widget-content" class="activity" >
							<td>
								<h3><?= date('F d, l',$activity['startdate']);?></h3>
							</td>
							<td align="center"><?= $activity['timespent'];?></td>
							<td></td>
					</tr>		
					<tr class="ui-widget-content" class="activity" >
							<td>
								<h4><?= $activity['catname'];?></h4>
								<br/><?= $activity['catsummary'];?>
							</td>
							<td  align="center"><?= $activity['timespent'];?></td>
							<td align="left">
								<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?>:</b><br/>		
								<p><?= $activity['summary'];?></p>
								<?php 
								if($activity['contactid']):
									echo $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['contactid']);
								else: 
									echo $LMS->GetField('mdl_proy_academy','shortname',$activity['academyid']);
								endif;
								?>
							</td>
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
