<script>
	$(document).ready(function(){

		$('#carreras').change(function(){
			$("#avisos").empty();
			$('#nopagos').empty().load('ajax.php?f=Pagos2&userid=<?= $id; ?>&insc_id=' + $('#carreras').val());
			setTimeout("calculateSum()",500);
		});

		$('.add').click(function(){
			if($("#carreras").val()<1){
				$("#avisos").append('<div class="ui-state-error ui-corner-all" style="padding: 0 .1em; width:220px; height: 15px;"><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .1em;"></span><strong>Debe seleccionar la carrera.</strong></div>');
				return false;
			}
			$("#dialog-confirm").dialog('open');
			return false;
		});

		$("#dialog-confirm").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					$('#pagos').submit();
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});
	});

</script>

<div id="dialog-confirm" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por cancelar la inscripci&oacute;n del alumno. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div class="column-c" style="width:80%" align="center">
	<?php if($no_pagos):?>
		<div class="portlet">
			<div class="portlet-header">Cancelar Inscripci&oacute;n</div>
			<div class="portlet-content" >
				<form id="pagos" name="pagos" action="contactos.php?v=cancel_insc&id=<?= $id;?>&action=cancel" method="post" class="validate">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="right" width="50%"><b>Seleccione carrera:</b></td>
								<td align="left" width="50%">
									<select name="carreras" id="carreras">
										<option></option>
										<?php foreach($no_pagos as $carrera):?>
											<option value="<?= $carrera['insc_id'];?>"><?= $LMS->GetField('mdl_course','shortname',$carrera['courseid']); ?></option>
										<?php endforeach;?>
									</select>
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="right"><b>Comprobantes Asociados:</b></td>
								<td align="left" id="nopagos">
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="right"><b>Con el dinero:</b></td>
								<td align="left">
									<select name="saldo">
										<option value="devuelve">Se le devuelve al alumno</option>
										<option value="queda">Queda para una pr&oacute;xima inscripci&oacute;n</option>
									</select>
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="right"><b>Descripci&oacute;n:</b></td>
								<td align="left">
									<textarea name="detalle" cols="60"></textarea>
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="center" colspan="2" id="avisos"></td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="center" colspan="2">
									<span class="button add">Continuar</span>
								</td>
							</tr>
						</tbody>
					</table>
					<input type="hidden" name="id" value="<?= $id; ?>" />
				</form>
			</div>
		</div>
	<?php endif;?>
	<?php if($pagos):?>
	<div class="portlet">
		<div class="portlet-header">Libro de pagos</div>
		<div class="portlet-content" >
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Curso</th>
					<th>Detalle</th>
					<th>Nro. Comprobante</th>
					<th>Fecha</th>
					<th>Importe</th>
					<th>Pendiente</th>
					<th>Usuario</th>
					<th>Per&iacute;odo</th>
				</thead>
				<tbody class="ui-widget-content">
					<?php foreach($pagos as $pago):?>
						<?php
							if($pago['cuota']>0){
								$beca = $H_DB->GetField("h_inscripcion", "becado", $pago['insc_id']);
								if($beca>0){
									$pago['valor_cuota'] = ceil($pago['valor_cuota'] - $pago['valor_cuota']*$beca/100);
								}
							}
						?>
						<tr style="height: 20px;">
							<td><?= $LMS->GetField('mdl_course','shortname',$pago['courseid']); ?></td>
							<?php if($pago['cuota']>0){ ?>
								<td>Cuota <?= $pago['cuota'];?></td>
							<?php }else{ ?>
								<td><?= $H_DB->GetField('h_libros','name',$pago['libroid']); ?></td>
							<?php } ?>
							<?php if($pago['numero']!=""): ?>
								<?php
									$comps = explode("-", $pago['numero']);
									foreach($comps as $comp):
										$aux.=$H_DB->GetField("h_comprobantes", "numero", $comp)."-".sprintf("%08d",$H_DB->GetField("h_comprobantes", "numero", $comp));
										$resultado .= $aux.", ";
										$aux="";
									endforeach;
								?>
								<td align="right"><?= substr($resultado, 0, -2); ?></td>
								<?php $resultado = ""; ?>
							<?php else: ?>
								<td align="right">-</td>
							<?php endif; ?>
							<td align="center"><?= date("d-m-Y", $pago['date']) ;?></td>
							<?php if($pago['valor_pagado']==$pago['valor_cuota']):?>
								<td class="ui-widget-content ui-state-highlight style" align="right">$ <?= number_format($pago['valor_pagado'], 2, ',', '.') ;?></td>
							<?php else:?>
								<td class="ui-widget-content" align="right">$ <?= number_format($pago['valor_pagado'], 2, ',', '.') ;?></td>
							<?php endif;?>
							<?php if(($pago['valor_cuota'] - $pago['valor_pagado'])>0):?>
								<td class="ui-widget-content ui-state-error style" align="right">
									$ <?= number_format($pago['valor_cuota'] - $pago['valor_pagado'], 2, ',', '.') ;?>
								</td>
								<?php $pendiente = $pendiente-($pago['valor_cuota'] - $pago['valor_pagado']);?>
							<?php else:?>
								<td class="ui-widget-content" align="right">$ <?= number_format($pago['valor_cuota'] - $pago['valor_pagado'], 2, ',', '.') ;?></td>
							<?php endif;?>
							<td align="center"><?= $LMS->GetField('mdl_user','firstname',$pago['takenby'])." ".$LMS->GetField('mdl_user','lastname',$pago['takenby']) ;?></td>
							<td align="center"><?= $pago['periodo'] ;?></td>
						</tr>
					<?php endforeach;?>
						<tr style="height: 20px;">
							<td colspan="5" align="right">Total pendiente:</td>
							<td align="right" class="ui-widget-content"><b>$ <?= number_format(abs($pendiente), 2, ',', '.');?><span id="maxValue" style="display: none"><?= abs($pendiente);?></span></b></td>
							<td align="center"></td>
						</tr>
				</tbody>
			</table>
		</div>
	</div>
<?php endif;?>
<?php if($comprobantes):?>
	<div class="portlet">
		<div class="portlet-header">Comprobantes</div>
		<div class="portlet-content" >
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Nro. Comprobante</th>
					<th>Fecha</th>
					<th>Importe</th>
					<th>Concepto</th>
					<th>Tipo</th>
					<th>Detalle</th>
				</thead>
				<tbody class="ui-widget-content">
					<?php foreach($comprobantes as $comprobante):?>
						<tr style="height: 20px;<?php if($comprobante['anulada']==1){ echo "text-decoration: line-through;";} ?>">
							<td id="nrocomprobante-<?=$comprobante['id'];?>">
								<?php if ($comprobante['numero']>0):?>
									&nbsp; <?= $comprobante['puntodeventa'];?>-<?= sprintf("%08d",$comprobante['numero']);?>
								<?php else: ?>
									<form id="nrocomprobante-<?=$comprobante['id'];?>" name="nrocomprobante-<?=$comprobante['id'];?>" action="<?= $HULK->SELF;?>&action=nrocomprobante" method="post">
										&nbsp;<?= $comprobante['puntodeventa']; ?>-<input type='text' name='numero' />
										<input type='hidden' name='comprobanteid' value="<?=$comprobante['id'];?>" />
										<input class="button" type='submit' value='Guardar' style="padding:.4em;">
									</form>
								<?php endif; ?>
							</td>
							<td align="center"><?= date("d-m-Y h:i:s", $comprobante['date']) ;?></td>
							<td class="ui-widget-content" align="center">$ <?= numero($comprobante['importe']);?></td>
							<td align="right"><?= $HULK->conceptos[$comprobante['concepto']];?></td>
							<td align="right"><?= $HULK->tipos[$comprobante['tipo']];?></td>
							<td align="left"><?= $comprobante['detalle'];?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>
<?php endif;?>
