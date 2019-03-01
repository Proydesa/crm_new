<div class="column2" style="width:100%">
	<div class="portlet ">
		<div class="portlet-header">Edición de comprobante</div>
		<div class="portlet-content">
		<form id="form_editcomp" action="contactos.php?v=editar_comprobante" method="post" name="editar_comprobante" class="ui-widget form-view">
			<table class="ui-widget" align="center" style="width:70%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 20px;">
							<td width="30%"><b>Punto de venta:</b></td>
							<td width="70%">
								<input name="puntodeventa" type="text" value="<?= $comp['puntodeventa'];?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Número:</b></td>
							<td width="70%">
								<input name="numero" type="text" value="<?= $comp['numero'];?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Fecha (dd-mm-aaaa): </b></td>
							<td width="70%">
								<input name="date" type="text" value="<?= date("d-m-Y", $comp['date']);?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Concepto: </b></td>
							<td width="70%">
								<select name="concepto" class="required">
								<?php foreach($HULK->conceptos as $value=>$concepto): ?>
									<option value="<?=$value;?>" <?php if ($value==$comp['concepto']){ echo"selected";}?>><?=$concepto;?></option>
								<?php endforeach;?>
								</select>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Tipo: </b></td>
							<td width="70%">
								<select name="tipo" class="required">
								<?php foreach($HULK->tipos as $value=>$tipo): ?>
									<option value="<?=$value;?>" <?php if ($value==$comp['tipo']){ echo"selected";}?>><?=$tipo;?></option>
								<?php endforeach;?>
								</select>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Detalle:</b></td>
							<td width="70%">
								<input name="detalle" type="text" value="<?= $comp['detalle'];?>"/>
							</td>
						</tr>
					</tbody>
			</table>
			<div align="center">
					<input type="hidden" name="id" value="<?= $comp['id']?>" />
					<input type="hidden" name="guardar" value="1" />
					<button type="button" class="add" onClick="$('.form-view').submit();" name="guardar" value="Guardar" />Guardar</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Resetear</button>
			</div>
		</form>
		</div>
	</div>
		<div align="center"><button type="button" class="button" onClick="window.opener.document.location.reload();self.close()">Cerrar</button></div>
</div>
