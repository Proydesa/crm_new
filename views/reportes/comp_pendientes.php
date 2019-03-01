<table align="right"><tr>
	<div class="ui-widget noprint" align="right">
		<td>
			<form action="reportes.php?v=xls" method="post" id="comp_pendientes-form">
				<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
				<span id="comp_pendientes" class="button-xls" style="font-size: 9px;"><b>Descargar XLS</b></span>
				<input type="hidden" id="comp_pendientes-table-xls" name="table-xls" />
				<input type="hidden" id="name-xls" name="name-xls" value="reporte.comp_pendientes" />
			</form>
		</td>
	</div>
	</tr>
</table>

<form action="reportes.php?v=comp_pendientes" method="POST" class="ui-widget noprint" style="margin:0 0 0 100px; padding:0;">
		Desde: <?php $view->jquery_datepicker2("#startdate, #enddate");?>
				<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= $startdate; ?> " />
		Hasta:	<input id="enddate" style="width:90px;" name="enddate" type="text" align="center" value="<?= $enddate; ?> " />
				<input type="submit" name="boton"  style="font-size: 9px;" class="button"  value="Filtrar" />
</form>
<div class="column" style="width:100%">
	<div class="portlet">
		<div class="portlet-header">Comprobantes Pendientes de cobro</div>
		<div class="portlet-content" >
			<table class="ui-widget" id="comp_pendientes-export" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>N&uacute;mero</th>
					<th>Tipo</th>
					<th>Concepto</th>
					<th>Fecha</th>
					<th>Importe</th>
					<th>Detalle</th>
					<th>Contacto / Empresa</th>
					<th>Usuario</th>
				</thead>
				<tbody>
					<?php foreach($rows as $row): ?>
						<tr class="ui-widget-content" style="height: 20px;">
							<td class="ui-widget-content" align="center" nowrap><?= $row['puntodeventa'].'-'.sprintf("%08d",$row['numero']); ?></td>
							<td class="ui-widget-content"><?= $HULK->tipos[$row['tipo']]; ?></td>
							<td class="ui-widget-content"><?= $HULK->conceptos[$row['concepto']]; ?></td>
							<td class="ui-widget-content" align="center" nowrap><?= date("Y-m-d", $row['date']); ?></td>
							<td class="ui-widget-content" align="right" nowrap>$ <?= number_format($row['importe'],2,",","."); ?></td>
							<td class="ui-widget-content"><?= $row['detalle']; ?></td>
							<?php if($row['grupoid'] > 0) : ?>
								<td class="ui-widget-content" ondblclick="window.location.href='grupos.php?v=view&id=<?= $row['grupoid'];?>';">
									<a href="grupos.php?v=view&id=<?= $row['grupoid'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
									<b><?= $H_DB->GetField("h_grupos", "name", $row['grupoid']); ?></b>
								</td>
							<?php else: ?>
								<td class="ui-widget-content" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['userid'];?>';">
									<a href="contactos.php?v=view&id=<?= $row['userid'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
									<b><?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname, ' - DNI: ', username)", $row['userid']); ?></b>
								</td>
							<?php endif; ?>
							<td class="ui-widget-content" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['takenby'];?>';">
								<a href="contactos.php?v=view&id=<?= $row['takenby'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
								<b><?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname)", $row['takenby']); ?></b>
							</td>
						</tr>
					<?php endforeach; ?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
</div>
