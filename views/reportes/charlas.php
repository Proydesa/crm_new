<?php if ($xls!="Exportar"): ?>
	<form method="post" action="reportes.php?v=charlas">
		<div align="center"><input type="submit" name="xls" value="Exportar" /></div>
	</form>
<?php endif; ?>


<table style='font-family:Arial;font-size:12px;' align='center' class="ui-widget" width="70%">
	<tr class="ui-widget-header">
		<th>#</th>
		<th>DNI</th>
		<th>Participante</th>
		<th>Mail</th>
		<th>C&oacute;mo se enter&oacute;?</th>
		<th>Charlas que le interesan</th>
	</tr>
	<?php $e = 1; ?>
	<?php foreach($result as $row):?>
		<?php 
			$userid = 0; 
			if(!$userid = $LMS->GetField('mdl_user','id',$row['dni'],'username')){ 
				$userid = $LMS->GetField('mdl_user','id',$row['mail'],'email');
			}
		?>
		<tr class="ui-widget-content">
			<td><b><?= $row['id']; ?></b></td>
			<td><?= $row['dni'];?></td>
			<?php if ($userid>0):?>
				<td align="left" class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $userid;?>';">
				<a href="contactos.php?v=view&id=<?= $userid;?>" target="_blank">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<b><?= $row['apellido'];?>, <?= $row['nombre'];?></b>
				</td>			
			<?php else:?>
				<td><?= $row['apellido'];?>, <?= $row['nombre'];?></td>
			<?php endif;?>
			<td><?= $row['mail'];?></td>
			<td><?= $row['enterado'];?></td>
			<?php if($row['id']<129): ?>
				<td>
					<ul>
					<?php for($i=1; $i<7; $i++){
						if($row['charla'.$i]==1){
							echo "<li>".$charlas[$i]."</li>";
						}
					} ?>
					</ul>
				</td>
			<?php elseif($row['id']<249): ?>
				<td>
					<ul>
						<li>Conferencia Cisco Data Center</li>
					</ul>
				</td>			
			<?php else: ?>
				<td>
					<ul>
						<li>Charla abierta PL-SQL</li>
					</ul>
				</td>
			<?php endif; ?>
		</tr>
		<?php $e ++; ?>
	<?php endforeach;?>
		
</table>
