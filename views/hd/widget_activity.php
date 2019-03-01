<script type="text/javascript">

	$(function () {

	$("#subject").watermark("Asunto...");
		$("#summary").watermark("Registre su nueva actividad...")
		.focus(function() {
				$("#activity-button").show();
				$(this).height("50px");
			})
		.focusout(function() {
			if ($.trim($(this).val())==""){
					$("#activity-button").hide();
					$(this).height("30px");
				}
			});

		$('textarea').autoResize({
			onResize : function() {
				$(this).css({opacity:0.8});
			},
			animateCallback : function() {
				$(this).css({opacity:1});
			},
			animateDuration : 300,
			extraSpace : 20
		});
	});
	
  var contador = 0;
	
  function nuevo_adjunto(){
		$("#files").append("<div id=\""+contador+"\" style=\"width:300px;margin:auto;\"><input type=\"file\" name=\"archivos[]\" class=\"press\"  style=\"height:30px;\" /><span class=\"ui-icon ui-icon-close\" onclick=\"elimina_adjunto("+contador+")\" class=\"press\" style=\"float:right;\"></span></div>");
		contador++;
  }
    
  function elimina_adjunto(elemento){
		$("#"+elemento).remove();
  }
</script>	
	<table class="ui-widget" align="center" style="width:100%;">
		<thead class="ui-widget-header">
			<tr>
				<th></th>
				<th>Fecha</th>
				<th>Asunto</th>
				<th>Añadido por</th>
				<th>Asignado a</th>
				<th>Estado</th>
			</tr>
		</thead>
		<tbody class="ui-widget-content">
			<tr>
				<th colspan="6">Incidentes</th>
			</tr>				
			<?php foreach($incidentes as $activity):?>
			<tr title='ID (<?=$activity['id'];?>): <?= str_replace("'",'"',strip_tags($activity['summary']));?>' class="ui-widget-content" style="height: 30px; text-align:center;" class="activity" >
					<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span></td>
					<td><?= date('d-m-Y',$activity['startdate']);?></td>
					<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
						<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
						<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>
					</td>
					<td><?= $LMS->GetField('mdl_user','username',$activity['userid']);?></td>
					<td><?= $activity['assignedto'];?></td>
					<td><?= $activity['status'];?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tbody class="ui-widget-content">
			<tr>
				<th colspan="6">Notas</th>
			</tr>				
			<?php foreach($notes as $activity):?>
			<tr title='ID (<?=$activity['id'];?>): <?= str_replace("'",'"',strip_tags($activity['summary']));?>' class="ui-widget-content" style="height: 30px; text-align:center;" class="activity" >
					<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span></td>
					<td><?= date('d-m-Y',$activity['startdate']);?></td>
					<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
						<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
						<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>
					</td>
					<td><?= $LMS->GetField('mdl_user','username',$activity['userid']);?></td>
					<td><!--<?= $activity['assignedto'];?>--></td>
					<td><!--<?= $activity['status'];?>--></td>
			</tr>
			<?php endforeach;?>
		</tbody>
		<tbody class="ui-widget-content">
			<tr>
				<th colspan="6">Log</th>
			</tr>				
			<?php foreach($activitys as $activity):?>
			<tr title='ID (<?=$activity['id'];?>): <?= str_replace("'",'"',strip_tags($activity['summary']));?>' class="ui-widget-content" style="height: 30px; text-align:center;" class="activity" >
					<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span></td>
					<td><?= date('d-m-Y',$activity['startdate']);?></td>
					<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
						<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
						<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>
					</td>
					<td><?= $LMS->GetField('mdl_user','username',$activity['userid']);?></td>
					<td><?= $activity['assignedto'];?></td>
					<td><?= $activity['status'];?></td>
			</tr>
			<?php endforeach;?>
		</tbody>
	</table>
	<p align="right">
		<a class="button" href="hd.php?v=lista&id=<?= $id;?>"><b>Ver todo</b></a>&nbsp;
	</p>
	<form action="contactos.php?v=view&id=<?= $id;?>" method="post" name="postForm" align="center" enctype="multipart/form-data">
		<div align="center">
			<span class="w">
				<textarea class="watermark" id="summary" name="summary" cols="100"></textarea>
			</span>	
			<div style="height:5px;"></div>
			<div id="activity-button" style="display:none;">
				<input type="hidden" name="action" value="newactivity">
				<input type="text" id="subject" name="subject" required />
				<select name="statusid">
					<?php foreach($activity_status as $status):?>
					<?php ($status['id']=='7')? $SELECTED="selected":$SELECTED="";?>
						<option value="<?= $status['id'];?>" <?=$SELECTED;?>><?= $status['name'];?></option>
					<?php endforeach;?>
				</select>
				<select name="priorityid">
					<?php foreach($activity_priority as $priority):?>
						<option value="<?= $priority['id'];?>"><?= $priority['name'];?></option>
					<?php endforeach;?>
				</select>
				<span class="button_file" onClick='nuevo_adjunto();' class="press" ><b>Adjuntar documento</b></span>
				<div id="files">
				</div>
				<?php foreach($activity_type as $type):?>
					<button value="<?= $type['id'];?>" type="checkbox" name="typeid" class="<?= $type['icon'];?>"><?= $type['name'];?></button>
				<?php endforeach;?>
			</div>
		</div>
	</form>