<div class="ui-widget" align="left">
	<div class="column-c" style="width:80%">
		<div class="portlet">
		<div class="portlet-content">
			<form method="get" action="hd.php?v=view&order=a.id&dir=<?= $dir;?>" >
			<input type="hidden" name="v" value="view" />
			<input type="hidden" name="order" value="<?=$order;?>" />
			<table class="ui-widget" align="center" style="width:100%;">
			<thead class="ui-widget-header">
				<tr>
					<th colspan="6" align="center">Listado de incidentes para <?= $H_USER->getName();?></th>
				</tr>
				<tr>
					<th style="width:60px;"><a href="hd.php?v=view&order=a.id&dir=<?= $ndir;?>">ID</a></th>
					<th style="width:180px;"><a href="hd.php?v=view&order=a.subject&dir=<?= $ndir;?>">Titulo</a></th>
					<?php if($H_USER->has_capability('activity/viewall')):?>
					<th style="width:120px;"><a href="hd.php?v=view&order=a.userid&dir=<?= $ndir;?>">Agregado por</a></th>
					<?php endif;?>
					<?php if($H_USER->has_capability('activity/representante')):?>
					<th style="width:120px;"><a href="hd.php?v=view&order=a.assignto&dir=<?= $ndir;?>">Asignado a</a></th>
					<?php endif;?>
					<th style="width:160px;"><a href="hd.php?v=view&order=a.startdate&dir=<?= $ndir;?>">Fecha de registro</a></th>
					<th><a href="hd.php?v=view&order=s.name&dir=<?= $ndir;?>">Estado</a></th>
					<th>Notas</th>					
				</tr>
			</thead>
				<tbody class="ui-widget-content">
					<tr>
						<td><input type="submit" class="button" value="filtrar"></td>
						<td align="center"><input type="text" name="subject" value="<?=$_GET['subject'];?>" /></td>
						<?php if($H_USER->has_capability('activity/viewall')):?>
						<td align="center">
							<select name="quserid">
								<option></option>
								<?php foreach($quserid as $userid):?>
								<option value="<?= $userid['id'];?>" <?= $useridselect[$userid['id']]?>><?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$userid['id']);?></option>
								<?php endforeach;?>
							</select>
						</td>
						<?php endif;?>						
					<?php if($H_USER->has_capability('activity/representante')):?>					
						<td align="center">
							<select name="assignto">
								<option></option>
								<?php foreach($qassignto as $assignto):?>
								<option value="<?= $assignto['id'];?>" <?= $assignselect[$assignto['id']]?>><?= $LMS->GetField('mdl_user','CONCAT(firstname," ",LEFT(lastname,1))',$assignto['id']);?></option>
								<?php endforeach;?>
							</select>
						</td>
						<?php endif;?>							
						<td align="center">
						<div id="fechas">
							<?php $view->jquery_datepicker("#startdate, #enddate");?>
							<input type="text" id="startdate" name="startdate" style="width:70px;" value="<?= date('d-m-Y',$qstartdate);?>" /> / 
							<input type="text" id="enddate" name="enddate" style="width:70px;" value="<?= date('d-m-Y',$qenddate);?>" />
						</div></td>
						<td align="center">
							<select name="statusid">
								<option></option>
								<?php foreach($qstatus as $status):?>
								<option value="<?= $status['id'];?>"  <?= $statusselect[$status['id']]?>><?= $status['name'];?></option>
								<?php endforeach;?>
							</select>
						</td>
					</tr>
					<?php foreach($activitys as $activity):?>
					<tr class="ui-widget-content" style="text-align:center; height:30px;" class="activity" >
							<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span>
								(<b><?= $activity['id'];?></b>)
							</td>
							<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
								<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>			
							</td>
							<?php if($H_USER->has_capability('activity/viewall')):?>
							<td>
								<a href="mailto:<?= $LMS->GetField('mdl_user','email',$activity['userid']);?>?Subject=HELPDESK: Problem <?= $activity['id'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
								</a>
							</td>
							<?php endif;?>							
							<?php if($H_USER->has_capability('activity/representante')):?>					
							<td>
								<a href="mailto:<?= $LMS->GetField('mdl_user','email',$activity['assignto']);?>?Subject=HELPDESK: Problem <?= $activity['id'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(firstname," ",LEFT(lastname,1))',$activity['assignto']);?>
								</a>
							</td>
							<?php endif;?>
							<td><?= date('d-m-Y',$activity['startdate']);?></td>
							<td><?= $activity['status'];?></td>
							<td><?= $activity['updates'];?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			<tr class="Head2">
              <td colspan="5">
                <div align="center">
				<?php if ($start>0):?>
					<button type="submit" name="start" value="<?=($start-20);?>" class="button">anterior</button> &nbsp;&nbsp;
				<?php endif;?>
				<?php if (count($activitys)==20):?>	
					<button type="submit" name="start" value="<?=($start+20);?>" class="button">siguiente</button> &nbsp;&nbsp;
				<?php endif;?>
                </div>
              </td>
            </tr>				
			</table>
			</form>
			<p align="center">
			<!--
				<button type="button" class="agregar" onClick="window.location.href='hd.php?v=nuevo';" >Nueva Actividad</button>
			-->
			</p>
		</div>
	</div>
</div>
           