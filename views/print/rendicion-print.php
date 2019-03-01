
<table class="ui-widget" align="center" style="width:100%;">
	<thead >
		<tr>
			<td colspan="5">
				<h1>&nbsp;&nbsp;&nbsp;<?= $LMS->GetField('mdl_user','username',$userid);?></h1>
			</td>
			<td colspan="2" align="right">
				<h1><?= date("d \d\e F \d\e  Y ") ;?>&nbsp;&nbsp;&nbsp;</h1>
			</td>
		</tr>
	<tr class="ui-widget-header">
		<th>Nro. Comprobante</th>
		<th>DNI</th>
		<th>Apellido</th>
		<th>Empresa</th>
		<th>Forma de pago</th>
		<th>Observaciones</th>
		<th>Importe</th>
	</tr>
	</thead>
	<tbody class="ui-widget-content">
		<?php foreach($comprobantes as $comprobante):?>
			<tr style="height: 20px;">
				<td>&nbsp;00001-<?= $comprobante['numero'] ;?></td>
				<td align="left"><?= $LMS->getField('mdl_user','username',$comprobante['userid']);?></td>
				<td align="left"><?= $LMS->getField('mdl_user','lastname',$comprobante['userid']);?></td>
				<td align="center"></td>
				<td align="right"><?= $comprobante['concepto'] ;?></td>
				<td align="left"><?= $comprobante['detalle'] ;?></td>
				<td class="ui-widget-content" align="center">$ <?= $comprobante['importe'] ;?></td>
			</tr>
			<?php $total = $total+($comprobante['importe']);?>
		<?php endforeach;?>
			<tr class="ui-widget-header">
				<td colspan="6" align="left"><h3 style=" margin:4px;">Total:</h3></td>
				<td align="center"><h3 style="margin:4px;">$ <?= $total;?></h3></td>
			</tr>					
	</tbody>
</table>
