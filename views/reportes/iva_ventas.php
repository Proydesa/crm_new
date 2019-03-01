<form action="<?= $HULK->SELF?>" method="post" name="parametros" style="margin:0; padding:0;">
<div class="column noprint" style="width:20%">
	<div class="portlet">
		<div class="portlet-header">Per&iacute;odos</div>
		<div class="portlet-content" style="overflow:auto;max-height:220px;">
			<ul class="noBullet">
				<label for="tipo">Tipo:</label>
				<select name="tipo">
					<?php foreach($HULK->tipos as $clave=>$valor){?>
						<option <?= ($clave==$tipo)?"selected":"";?> value="<?= $clave;?>"><?= $valor;?></option>
					<?php } ?>
				</select><br/><br/>
		<label for="fecha">Mes:</label>
		<select name="mes">
			<?php for($x=1;$x<13;$x++){?>
				<option <?= ($x==$mes)?"selected":"";?> value="<?= $x;?>"><?= $HULK->meses[$x];?></option>
			<?php } ?>
		</select><br/><br/>
		<label for="fecha">Año:</label>
		<select name="ano">
			<?php for($x=2009;$x<=date("Y");$x++){?>
				<option <?= ($x==$ano)?"selected":"";?>><?= $x;?></option>
			<?php } ?>
		</select>
	</ul>
		</div>
	</div>
	<button type="submit" class="button" style=" width:90%; font-weight: bold; height:25px;">Ver</button>
</div>
</form>
<div class="column" style="width:80%">
		<form align="right" action="reportes.php?v=xls" method="post" id="basico-form">
			<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
			<span id="basico" class="button-xls" style="font-size: 9px;"><b>Descargar XLS</b></span>
			<input type="hidden" id="basico-table-xls" name="table-xls" />
			<input type="hidden" id="name-xls" name="name-xls" value="reporte.iva.ventas" />
		</form>
	<div class="portlet">
		<div class="portlet-header">Reporte IVA - Ventas</div>
		<div class="portlet-content" >
			<table id="basico-export" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Día</th>
						<th>Mes</th>
						<th>Año</th>
						<th>Tipo</th>
						<th>N° Recibo</th>
						<th>Empresa</th>
						<th>CUIT</th>
						<th>Cond. IVA</th>
						<th>Forma de pago</th>
						<th>Neto Grav.</th>
						<th>Importe Total</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($rows as $row):?>
					<tr class="ui-widget-content" style="height: 20px;">
						<td class="ui-widget-content" align="right"><?= show_fecha($row['date'],"d") ;?></td>
						<td class="ui-widget-content" align="right"><?= show_fecha($row['date'],"m") ;?></td>
						<td class="ui-widget-content" align="right"><?= show_fecha($row['date'],"Y") ;?></td>
						<td class="<?=$class;?>"><?= $HULK->tipos[$row['tipo']];?></td>
						<td class="ui-widget-content"><?= $row['puntodeventa'];?>-<?= str_pad($row['numero'], 8, "0", STR_PAD_LEFT);?></td>
						<?php if($row['empresa']!=""):?>
						<td class="ui-widget-content"><?= $row['empresa'];?></td>
						<?php else:?>
						<td class="ui-widget-content"><?= $LMS->getField("mdl_user","CONCAT(firstname,' ',lastname)",$row['userid']);?></td>
						<?php endif;?>
						<td class="ui-widget-content"><?= $row['cuit'];?></td>
						<td class="ui-widget-content"><?= $HULK->cond_iva[$row['iva']];?></td>
						<td class="ui-widget-content"><?= $HULK->conceptos[$row['concepto']];?></td>
						<td class="ui-widget-content" align="right"><?= numero($row['importe']);?></td>
						<td class="ui-widget-content" align="right"><?= numero($row['importe']);?></td>
					</tr>
					<?php $total+=$row['importe'];	?>
				<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr class="ui-widget-content" style="height: 16px;">
						<th colspan="8"></th>
						<th align="right"><b>TOTAL:</b></th>
						<th align="right"><b><?=numero($total);?></b></th>
						<th align="right"><b><?=numero($total);?></b></th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
