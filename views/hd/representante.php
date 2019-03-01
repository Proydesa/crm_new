	<div class="column" style="width:50%">
		<div class="portlet">
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:95%;">
			<thead class="ui-widget-header">
				<tr>
					<th colspan="5" align="center">Listado de incidentes asignados a <?= $H_USER->getName();?></th>
				</tr>
				<tr>
					<th style="width:60px;">ID</th>
					<th style="width:180px;">Titulo</th>
					<th style="width:120px;">Agregado por</th>
					<th style="width:160px;">Fecha de registro</th>
					<th>Estado</th>
				</tr>			
			</thead>
				<tbody class="ui-widget-content">
					<?php foreach($activitys_assign as $activity):?>
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
								<a href="mailto:<?= $LMS->GetField('mdl_user','email',$activity['userid']);?>?Subject=HELPDESK: Problem <?= $activity['id'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
								</a>
							</td>
							<td><?= date('d-m-Y',$activity['startdate']);?></td>
							<?php  $status=explode("-",$activity['status']);?>
							<td title="<?= $status[1];?>"><?= $status[0];?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>

	<div class="column" style="width:50%">
		<div class="portlet">
		<div class="portlet-content">
			<form method="get" action="hd.php?v=representante&order=a.id&dir=<?= $dir;?>" >
			<input type="hidden" name="v" value="representante" />
			<input type="hidden" name="order" value="<?=$order;?>" />
			<table class="ui-widget" align="center" style="width:100%;">
			<thead class="ui-widget-header">
				<tr>
					<th colspan="5" align="center">Listado de incidentes sin asignar</th>
				</tr>
				<tr>
					<th style="width:60px;"><a href="hd.php?v=representante&order=a.id&dir=<?= $ndir;?>">ID</a></th>
					<th style="width:180px;"><a href="hd.php?v=representante&order=a.subject&dir=<?= $ndir;?>">Titulo</a></th>
					<th style="width:120px;"><a href="hd.php?v=representante&order=a.userid&dir=<?= $ndir;?>">Agregado por</a></th>
					<th style="width:160px;"><a href="hd.php?v=representante&order=a.startdate&dir=<?= $ndir;?>">Fecha de registro</a></th>
					<th><a href="hd.php?v=representante&order=s.name&dir=<?= $ndir;?>">Estado</a></th>
				</tr>			
			</thead>
				<tbody class="ui-widget-content">
					<tr>
						<td><input type="submit" class="button" value="filtrar"></td>
						<td align="center"><input type="text" name="subject" value="<?=$_GET['subject'];?>"   style="width:100px"/></td>
						<td align="center">
							<select name="quserid"  style="width:100px">
								<option></option>
								<?php foreach($quserid as $userid):?>
								<option value="<?= $userid['id'];?>" <?= $useridselect[$userid['id']]?>><?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$userid['id']);?></option>
								<?php endforeach;?>
							</select>
						</td>
						<td align="center">
						<div id="fechas">
							<?php $view->jquery_datepicker("#startdate, #enddate");?>
							<input type="text" id="startdate" name="startdate" style="width:70px;" value="<?= date('d-m-Y',$qstartdate);?>" /> / 
							<input type="text" id="enddate" name="enddate" style="width:70px;" value="<?= date('d-m-Y',$qenddate);?>" />
						</div></td>
						<td align="center">
							<select name="statusid"  style="width:100px">
								<option></option>
								<?php foreach($qstatus as $status):?>
								<option value="<?= $status['id'];?>"  <?= $statusselect[$status['id']]?>><?= $status['name'];?></option>
								<?php endforeach;?>
							</select>
						</td>
					</tr>	
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
								<a href="mailto:<?= $LMS->GetField('mdl_user','email',$activity['userid']);?>?Subject=HELPDESK: Problem <?= $activity['id'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
								</a>
							</td>
							<td><?= date('d-m-Y',$activity['startdate']);?></td>
							<?php  $status=explode("-",$activity['status']);?>
							<td title="<?= $status[1];?>"><?= $status[0];?></td>
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