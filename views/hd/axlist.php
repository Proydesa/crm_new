	<?php if ($activitys): ?>
	<?php foreach($activitys as $activity):?>
	<tr class="ui-widget-content" style="height: 30px; text-align:center;" class="activity" >
			<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;" title="(<?= $activity['id'];?>)"></span>
			<td><?= date('d-m-Y',$activity['startdate']);?></td>
			<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
			<a href="hd.php?v=view&id=<?=$activity['id'];?>" target="_blank">
				<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
			</a>
			<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>
			</td>
			<?php if($t=="incidentes"):?>
			<td>
				<a href="contactos.php?v=view&id=<?=$activity['userid'];?>">
					<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
				</a>
			</td>
			<?php else: ?>
			<td>
				<a href="contactos.php?v=view&id=<?=$activity['contactid'];?>">
					<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['contactid']);?>
				</a>
			</td>
			<?php endif; ?>
			<td><?= $activity['status'];?></td>
	</tr>
	<?php endforeach;?>
	<?php else: ?>
	<tr>
		<?php if($t=="incidentes"):?>
		<td colspan="5" align="center">No tiene ningún incidente asignado pendiente actualmente. Puede ingresar a su <a href="hd.php">Panel de Help Desk</a> a ver la lista de nuevos incidentes no asignados a ningún usuario que están pendientes de resolución.</td>
		<?php else: ?>
		<td colspan="5" align="center">Todavia no guardaste ninguna nota <?= $H_USER->get_property('firstname');?>. Las notas sirven para guardar información adicional referida a los usuarios, que queda disponible para un futuro uso. A diferencia de los logs, las notas no son registros temporales.</td>						
		<?php endif;?>
	</tr>				
	<?php endif; ?>
	<tr>
		<td colspan="4"></td>
		<td align="right">
		P&aacute;gina: <?= $p;?>
		<?php if ($ant):?>
			<button onClick="$('#<?=$t;?>').load('<?=$ant;?>');" style="margin-bottom:-3;" id="rewind" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only" title="rewind"><span class="ui-button-icon-primary ui-icon ui-icon-seek-prev"></span><span class="ui-button-text">rewind</span></button>
		<?php endif;?>
		<?php if ($activitys): ?>
			<button onClick="$('#<?=$t;?>').load('<?=$sig;?>');" id="forward" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only"  title="fast forward"><span class="ui-button-icon-primary ui-icon ui-icon-seek-next"></span><span class="ui-button-text">fast forward</span></button>
		<?php endif; ?>
	</tr>