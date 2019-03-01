<table class="ui-widget" align="center" style="width:100%;">
	<thead>
		<tr style="height: 20px;" class="ui-widget-header">
			<th>Curso</th>
			<?php foreach($periodos as $p):?>			
				<th><?= $p;?></th>
			<?php endforeach;?>
				<th>Total</th>
			</tr>
	</thead>
	<tbody class="ui-widget-content">
		<?php foreach($inscriptos as $carrera => $ins):?>
			<tr style="height: 20px;">
				<td class="press" ondblclick="">
					<a href="#" target="_blank">	
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
					</a><?= $carrera;?>
				</td>
				<?php foreach($periodos as  $p):?>			
					<td align="right"><?= $ins[$p];?></td>
				<?php endforeach;?>
					<td align="right"><b><?= $ins['total'];?></b></td>
				<?php $total += $ins['total']; ?>
			</tr>
		<?php endforeach;?>
		<tr style="height: 20px;">
			<td align="left" colspan="<?= (count($periodos)+1);?>"><b>Total</b></td>
			<th align="right"><b><?= $total;?></b></th>
		</tr>
	</tbody>
</table>