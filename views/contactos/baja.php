<script>
	$(document).ready(function(){

		$('.add').click(function(){
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
					$('#baja').submit();
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
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por dar de baja al alumno. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>
<div class="column-c" style="width:80%" align="center">
	<div class="portlet">
		<div class="portlet-header">Datos de la baja</div>
		<div class="portlet-content" >
			<form id="baja" name="baja" action="contactos.php?v=baja" method="POST" class="validate">
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody>
						<tr style="height: 20px;" class="ui-widget-content">
							<td align="right" width="30%"><b>Comisi&oacute;n:</b></td>
							<td align="left" width="70%"><?= $comi['shortname']; ?></td>
						</tr>
						<tr style="height: 20px;" class="ui-widget-content">
							<?php if(isset($comprobantes)):?>
							<td align="right"><b>Pagos:</b></td>
							<td align="left">
								<table class="ui-widget">
									<tr class="ui-widget-header">
										<th>Comprobante</th>
										<th>Importe</th>
									</tr>
									<?php	foreach($comprobantes as $comprobante): ?>
										<tr class="ui-widget-content">
											<td><?= $comprobante['puntodeventa']."-".sprintf("%08d",$comprobante['numero']); ?></td>
											<td align="right">$ <?= number_format($comprobante['importe'],2,',','.'); ?></td>
											<?php $total += $comprobante['importe']; ?>
										</tr>
									<?php endforeach; ?>
									<tr>
										<td><b>Total:</b></td>
										<td align="right"><b>$ <?= number_format($total,2,',','.'); ?></b></td>
									</tr>
								</table>
							</td>
						<?php endif; ?>
						</tr>
						<?php if($cuotas): ?>
							<tr>
								<td align="right"><b>Cuotas:</b></td>
								<td>
									<table class="ui-widget" align="left" style="width:60%;">
										<thead class="ui-widget-header">
											<tr>
												<th>Curso</th>
												<th>Cuota 1</th>
												<th>Cuota 2</th>
												<th>Cuota 3</th>
												<th>Cuota 4</th>
												<th>Cuota 5</th>
												<th>Libro</th>
												<th>Per&iacute;odo</th>
											</tr>
										</thead>
										<tbody class="ui-widget-content">
											<?php foreach($cuotas as $cuota):?>
												<tr style="height: 20px;">
													<td class="ui-widget-content"><b><?= $LMS->GetField('mdl_course','shortname',$cuota['id']); ?><b></td>
													<?php for($x=1;$x<6;$x++):?>
														<?php if($cuota['cuota'.$x]=='0'):?>
															<td class="ui-widget-content ui-state-highlight style" align="center"><b>OK</b></td>
														<?php elseif($cuota['cuota'.$x]==0):?>
															<td class="ui-widget-content"></td>
														<?php else:?>
															<td class="ui-widget-content ui-state-error style" align="center"><?= $cuota['cuota'.$x]; ?></td>
														<?php endif;?>
													<?php endfor;?>
													<?php if($cuota['cuota0']=='0'):?>
														<td class="ui-widget-content ui-state-highlight style" align="center"><b>OK</b></td>
													<?php else:?>
														<td class="ui-widget-content" align="center"><?= $cuota['cuota0']; ?></td>
													<?php endif;?>
													<td class="ui-widget-content" align="center"><?= $cuota['periodo']; ?></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								</td>
							</tr>
						<?php else: ?>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="right"><b></b></td>
								<td>No se encontraron cuotas asociadas a este usuario en la comisión. Lo mas probable es que el usuario no haya sido enrolado mediante el CRM y no tenga datos de facturación. La baja solo camabiara su estatus en la comisión a [I]ncompleto.</td>
							</tr>
						<?php endif; ?>
						<tr style="height: 20px;" class="ui-widget-content">
							<td align="right"><b>Observaciones:</b></td>
							<td><textarea name="detalle" cols="70" rows="3"></textarea></td>
						</tr>
						<tr style="height: 20px;" class="ui-widget-content">
							<td align="center" colspan="2">
								<span class="button add">Continuar</span>
								<span class="button" onClick="window.location.href='contactos.php?v=view&id=<?= $contact['id'];?>';">Cancelar</span>
							</td>
						</tr>
					</tbody>
					<input type="hidden" name="stage" value="1" />
					<input type="hidden" name="user" value="<?= $contact['id']; ?>" />
					<input type="hidden" name="comi" value="<?= $comi['id']; ?>" />
					<input type="hidden" name="insc_id" value="<?= $insc_id; ?>" />
				</table>
			</form>
		</div>
	</div>
</div>
<br/>
