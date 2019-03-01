<script>
	$(document).ready(function(){


		$('#carreras').change(function(){
			$('#nopagos').empty().load('ajax.php?f=noPagos&userid=<?= $userid; ?>&carrera=' + $('#carreras').val());
			setTimeout("calculateSum()",500);
			setTimeout(function(){
															if($("#solo-fc").html()=="SI"){
																$("#extra-fc").empty().append("<span style='font-size:12px;color:red;font-weight:bold;'>Alummno asociado a empresa. Cobrar con Factura.</span>");
																$('#factura').attr('checked', 'checked').button("refresh");
																$('#recibo').attr('disabled', true).button("refresh");
															}else{
																$("#extra-fc").empty();
															}
														}, 500);
		});

		$('.add').click(function(){
			if($("#pagado").is(':checked')){
				if($("#detalle").val()==""){
					alert("Si selecciona pendiente debe ingresar el detalle");
					$("#detalle").focus();
					return false;
				}
			}

			/*alan*/
			var value = $('#carreras option:selected').val();
			if(value==0){
				$('#hiddendiv').show();
				return false;
			}
			if(value>0){
				$('#hiddendiv').hide();
			}
			/*alan*/

			$('#extra').empty().append('Total Recibido: $ '+$('#importe').val());
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

		$('.anular').click(function(){
			$("#comp_actual").val($(this).attr("id"));
			$("#dialog-confirm2").dialog('open');
			return false;
		});

		$("#dialog-confirm2").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					location.href=("contactos.php?v=anular&id="+$("#comp_actual").val()+"&user="+<?= $userid; ?>);
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

		$('.cancelar').click(function(){
			$("#comp_actual").val($(this).attr("id"));
			$("#dialog-confirm3").dialog('open');
			return false;
		});

		$("#dialog-confirm3").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					location.href=("contactos.php?v=cancelar&id="+$("#comp_actual").val()+"&user="+<?= $userid; ?>);
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

		$('.cobrar').click(function(){
			$("#comp_actual").val($(this).attr("id"));
			$("#comp_cobrar").val($(this).attr("id"));
			$("#dialog-confirm4").dialog('open');
			return false;
		});

		$("#dialog-confirm4").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					//location.href=("contactos.php?v=cobrar&id="+$("#comp_actual").val()+"&user="+<?= $userid; ?>);
					$("#form-cobrar").submit();
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

		$('#concepto').change(function(){
			if($('#concepto').val()=="2"){
				//$('#nrocheque').attr('disabled', false).focus();
				$('#recibo').attr('disabled', false).button("refresh");
			}else{
				if($('#concepto').val()=="3"){
					$('#recibo').attr('disabled', true).button("refresh");
					$('#factura').attr('checked', 'checked').button("refresh");
					//$('#nrocheque').attr('disabled', true);
				}else{
					$('#recibo').attr('disabled', false).button("refresh");
					$('#recibo').attr('checked', 'checked').button("refresh");
					//$('#nrocheque').attr('disabled', true);
				}
			}
		});

		<?php if($H_USER->has_capability('cuota/editar')):	?>
			$(".edit_cuota").editable("contactos.php?v=cuota_edit", {
					indicator : "<img src='img/indicator.gif'>",
					tooltip   : "Doble click para editar...",
					event     : "dblclick",
					style  		: "inherit",
					submit 		: "OK",
					submitdata: function() {
						return {id : $(this).attr("id")};
					},
					cssclass	: "editable",
					callback	: function(){
						calculatePend();
					}

			});
		<?php endif; ?>

		$('#pagado').click(function(){
			if($(this).is(':checked')){
				$("#pendiente").html('PENDIENTE DE PAGO');
				// Esto es sólo para FC
				$('#factura').attr('checked', 'checked').button("refresh");
				$('#recibo').attr('disabled', true).button("refresh");
			}else{
				$("#pendiente").html("");
				$('#recibo').attr('disabled', false).button("refresh");
			}
		});

		$("#conc_cobrar").change(function(){
			if($(this).val()==2){
				$("#nro_cheque").show();
			}else{
				$("#nro_cheque").hide();
			}
		});

	});

	function calculateSum(){

		if($('#chk_kit').is(':checked')){
			$('#total').val(Number($('#kit').html()) + Number($('#total_c').val()));
		}else{
			$('#total').val(Number($('#total_c').val()));
		}

		$('#importe').rules('remove', "range");
		$('#importe').rules('add', { range: [ '1', $('#total').val() ] });

	}

	function calculatePend(){

		var add = 0;
		$('.pend').each(function() {
			aux = $(this).html().replace(".","A");
			aux = aux.replace(",",".");
			aux = aux.replace("A","");
			add += Number(aux);
		});
		$('#tot_pend').html(add);
		$('#maxValue').html(add);

	}
</script>
<div class="column-c" style="width:93%" align="center">
	<?php if($no_pagos):?>
		<div class="portlet">
			<div class="portlet-header">Registro de pagos</div>
			<div class="portlet-content" >
				<form id="pagos" name="pagos" action="<?= $HULK->SELF?>v=pagos&id=<?= $userid;?>&action=regpago" method="post" class="validate">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="center"><b>Seleccione carrera:</b></td>
								<td align="center" width="250px">
									<select name="carreras" id="carreras">
										<option value="0"></option>
										<?php foreach($no_pagos as $carrera):?>
											<option value="<?= $carrera['courseid'];?>"><?= $LMS->GetField('mdl_course','shortname',$carrera['courseid']); ?></option>
										<?php endforeach;?>
									</select>
								</td>
								<td align="center">
									<b>Registro de pagos:</b>
								</td>
								<td align="right">
									<div class="radio">
										$
										<input type="text" id="importe" name="importe" value="<?= abs($pendiente);?>" class="required digits" style="width:80px; margin-top:4px;" align="right">
									<select name="concepto" id="concepto" style="width:150px;">
											<?php foreach($HULK->conceptos as $value=>$concepto):?>
												<option value="<?= $value;?>" class="required fpago" id="<?= $concepto;?>"><?= $concepto;?></option>
											<?php endforeach;?>
									</select>
									</div>
									<div>
										<span><b>Detalle de pago:</b> (Ej: N° de cheque)</span>
										<input type="text" name="nrocheque" id="nrocheque" value="" style="width:50%;" />
									</div>
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td align="center"><b>Seleccione cuota(s):</b></td>
								<td align="center" rowspan="5"  id="nopagos">	</td>
								<td colspan="2">
									<div id="extra-fc" style="float:left;"></div>
									<div class="radio" style="float:right;">
										<input type="radio" id="recibo" name="tipo" value="1" class="required"  /><label for="recibo">Recibo</label>
										<input type="radio" id="factura" name="tipo" value="2" class="required"  /><label for="factura">Factura</label>
									</div>
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td>&nbsp;</td>
								<td align="right"><input type="checkbox" id="pagado" name="pendiente" value="1" />&nbsp;<b>Pendiente</b></td>
								<td align="center" id="pendiente" style="color:red; font-weight:bold; font-size:13px;"></td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td>&nbsp;</td>
								<td align="center"><b>Detalle:</b></td>
								<td align="center"><input name="detalle" id="detalle" style="width:90%" /></td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td>&nbsp;</td>
								<td>&nbsp;</td>								
								<td colspan="3" align="center">
									<label for="cuota[]" class="error">
										<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:30%">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Alert:</strong>Debe selecciona una cuota como m&iacute;nimo.</p>
										</div>
									</label> <br/>
									<label for="tipo" class="error">
										<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:30%">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Alert: </strong>Debe seleccionar si es 'Recibo' o 'Factura'.</p>
										</div>
									</label> <br/>
									<label for="concepto" class="error">
										<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:30%">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Alert: </strong>Debe seleccionar un tipo de pago.</p>
										</div>
									</label>
									<!--alan-->
									<div id="hiddendiv" class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:30%; DISPLAY:none">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Alert: </strong>Debe seleccionar una carrera.</p>
											<br/>
									</div>
									<!--alan-->
								</td>
							</tr>
							<tr style="height: 20px;" class="ui-widget-content">
								<td colspan="8" align="center">
									<span class="button add">Registrar pago</span>
								</td>
							</tr>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	<?php endif;?>
	<?php if($pagos):?>
		<form action="" method="post"  name="pagos" class="form-view">
			<div class="portlet">
				<div class="portlet-header">Libro de pagos</div>
				<div class="portlet-content" style="overflow:auto;max-height:200px;">
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
							<!--<th>Empresa</th>-->
						</thead>
						<tbody class="ui-widget-content">
							<?php $i=0;?>
							<?php foreach($pagos as $pago):?>
							<!--alan -->
							<?php if($pago['valor_cuota']=="cobro"):?>
								<?php $anulado=$H_DB->GetField('h_comprobantes','anulada',$pago['numero']);?>
								<?php if($anulado==1){ $pago['baja_id']=1;
								 $pago['baja_cancel']=0;
								 }?>
							<?php endif;?>
							<!--alan -->
								<?php if($pago['baja_id']>0 && $pago['baja_cancel']==0){ $title="Baja: ".$pago['detalle']; }else{ $title=""; } ?>
								<?php if($pago['baja_id']>0 && $pago['baja_cancel']==0){ $style="text-decoration:line-through;"; }else{ $style=""; } ?>
								<tr style="height: 20px;<?= $style; ?>" title="<?= $title; ?>">
									<?php if(($pago['courseid'])!="-"):?>
										<td><?= $LMS->GetField('mdl_course','shortname',$pago['courseid']); ?></td>
									<?php else:?>
										<td>-</td>
									<?php endif;?>
									<?php if($pago['cuota']>0): ?>
										<td <?php if($pago['grupoid']>0) echo 'title="Asociado a empresa '.$H_DB->GetField("h_grupos", "name", $pago['grupoid']).'"'; ?>>
											Cuota <?= $pago['cuota'];?> <?php if($pago['grupoid']>0) echo "(*)"; ?>
										</td>
									<?php else: ?>
										<td <?php if($pago['grupoid']>0) echo 'title="Asociado a empresa '.$H_DB->GetField("h_grupos", "name", $pago['grupoid']).'"'; ?>>
											<?= $H_DB->GetField('h_libros','name',$pago['libroid']); ?> <?php if($pago['grupoid']>0) echo "(*)"; ?>
											<?php if($pago['grupoid']=="-") echo "Cobro"; ?>
										</td>
									<?php endif; ?>
									<?php $importe = 0; ?>
									<?php if($pago['numero']!=""): ?>
										<?php
											$comps = explode("-", $pago['numero']);
											foreach($comps as $comp):

												if($H_DB->GetField("h_comprobantes", "pendiente", $comp)==1){

													$aux.="<b>";
													$importe = $H_DB->GetOne("SELECT importe FROM h_comprobantes_cuotas
																										WHERE comprobanteid={$comp} AND cuotaid={$pago['id']};");
												}
												$aux .= $H_DB->GetField("h_comprobantes", "puntodeventa", $comp)."-".sprintf("%08d",$H_DB->GetField("h_comprobantes", "numero", $comp));
												if($H_DB->GetField("h_comprobantes", "pendiente", $comp)==1){
													$aux.="</b>";
												}
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
									<?php
										if($pago['cuota']>0){
											$beca = $H_DB->GetField("h_inscripcion", "becado", $pago['insc_id']);
											if($beca>0){
												$pago['valor_cuota'] = ceil($pago['valor_cuota'] - $pago['valor_cuota']*$beca/100);
											}
										}
									?>
									<?php if($pago['pendiente']=="-"):?>
										<?php if($pago['valor_pagado']-$importe>=$pago['valor_cuota']):?>
											<td class="ui-widget-content bg-success" align="right">$ <?= number_format($pago['valor_pagado']-$importe, 2, ',', '.') ;?></td>
										<?php else:?>
											<td class="ui-widget-content" align="right">$ <?= number_format($pago['valor_pagado']-$importe, 2, ',', '.') ;?></td>
										<?php endif;?>
										<?php if($pago['valor_pagado']-$importe>0): ?>
											<?php if(($pago['valor_cuota'] - $pago['valor_pagado'])>0):?>
												<td class="ui-widget-content bg-danger" align="right">
													$ <span class="pend"><?= number_format($pago['valor_cuota'] - ($pago['valor_pagado']-$importe), 2, ',', '.') ;?></span>
												</td>
												<?php $pendiente = $pendiente-($pago['valor_cuota'] - ($pago['valor_pagado']-$importe));?>
											<?php else:?>
												<td class="ui-widget-content" align="right">
													<?php if($pago['valor_cuota'] - ($pago['valor_pagado']-$importe) < 0): ?>
														$ 0,00
													<?php else: ?>
														$ <span class="pend"><?= number_format($pago['valor_cuota'] - ($pago['valor_pagado']-$importe), 2, ',', '.') ;?></span>
													<?php endif; ?>
												</td>
											<?php endif;?>
										<?php else: ?>
											<?php if($pago['baja_id']>0 && $pago['baja_cancel']==0){ $class=""; }else{ $class="edit_cuota"; } ?>
											<td class="ui-widget-content bg-danger" align="right">$ <span class="<?= $class; ?> pend" id="<?= $pago['id']; ?>"><?= number_format($pago['valor_cuota'], 2, ',', '.') ;?></span></td>
											<?php $pendiente = $pendiente-($pago['valor_cuota'] - $pago['valor_pagado']);?>
										<?php endif; ?>
									<?php else: ?>
										<?php if($pago['pendiente']==0):?>
											<td class="ui-widget-content bg-success" align="right">$ <?= number_format($pago['valor_pagado']-$importe, 2, ',', '.') ;?></td>
											<td class="ui-widget-content" align="right">$ 0,00</td>
										<?php else:?>
											<td class="ui-widget-content" align="right">$ 0,00</td>
											<td class="ui-widget-content bg-danger" align="right">
													$ <span class="pend"><?= number_format($pago['valor_pagado'], 2, ',', '.') ;?></span>
													<?php $pendiente=$pendiente-$pago['valor_pagado'];?>
												</td>
										<?php endif;?>
									<?php endif;?>
									<td align="center"><?= $LMS->GetField('mdl_user','firstname',$pago['takenby'])." ".$LMS->GetField('mdl_user','lastname',$pago['takenby']) ;?></td>
									<td align="center"><?= $pago['periodo'] ;?></td>
									<!--alan listado de grupos que a los que esta asociado el user -->
									<?php $numcomp[$contador]=$pago['numero'];?>
										<?php if($numcomp[$contador]!=$numcomp[$contador-1]): ?>
											<?php $contador++;?>
										<?php endif;?>
										<?php if($pago['numero']==0):?>
											<?php if($contador!=0):?>
												<?php $contador++;?>
											<?php endif;?>
										<?php endif;?>
									<!--<td><ul class="noBullet">
										<?php if ($H_USER->has_capability('contactos/pagos')) : ?>
											<select  name="<?= $i;?>" class=<?=$pago['numero'];?> id="<?= $i;?>">
												<option></option>
												<?php foreach($grupos as $grupo):?>
													<li><option value="<?= $grupo['id'];?>" <?php if($grupo['id']==$pago['grupoid']) echo 'selected="selected"'; ?>><?= $grupo['name'];?></option></li>
												<?php endforeach;?>
											</select>
										<?php else:?>
											<select disabled="disabled" name="<?= $i;?>" class=<?=$pago['numero'];?> id="<?= $i;?>">
												<option></option>
												<?php foreach($grupos as $grupo):?>
													<li><option value="<?= $grupo['id'];?>" <?php if($grupo['id']==$pago['grupoid']) echo 'selected="selected"'; ?>><?= $grupo['name'];?></option></li>
												<?php endforeach;?>
											</select>
										<?php endif;?>
									</td></ul>-->
									<!--alan -->
								</tr>
								<?php $i++;?>
							<?php endforeach;?>
							<!--alan -->

							<!--alan -->
								<tr style="height: 20px;">
									<td colspan="5" align="right">Total pendiente:</td>
									<td align="right" class="ui-widget-content"><b>$ <span id="tot_pend"><?= number_format(abs($pendiente), 2, ',', '.');?></span><span id="maxValue" style="display: none"><?= abs($pendiente);?></span></b></td>
									<td align="center"></td>
								</tr>
						</tbody>
						<!--alan -->
						<?php if ($H_USER->has_capability('contactos/pagos')) : ?>
							<!--<input class="button" name="boton" type='submit' value='Asociar a empresas' style="width: 150px; font-size:11px"></ul></td>-->
						<?php endif;?>
						<!--alan -->
					</table>
				</div>
			</div>
		</form>
	<?php endif;?>

	<?php if($comprobantes): ?>
		<div class="portlet">
			<div class="portlet-header">Comprobantes</div>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th>Nro. Comprobante</th>
						<th>Fecha</th>
						<th>Importe</th>
						<th>Concepto</th>
						<th>Tipo</th>
						<th>Detalle</th>
						<th></th>
					</thead>
					<tbody class="ui-widget-content">
						<?php foreach($comprobantes as $comprobante):?>
							<?php if($comprobante['anulada']==1){ $style .= "text-decoration: line-through;"; }else{ $style=""; } ?>
							<?php if($comprobante['pendiente']==1) $style .= "font-weight: bold;"; ?>
							<tr style="height: 20px;<?= $style; ?>" class="comprobante">
								<td id="nrocomprobante-<?=$comprobante['id']?>" width="200px">
									<?php if ($comprobante['numero']>0):?>
										&nbsp;<span id="pv-<?= $comprobante['id']; ?>"><?= $comprobante['puntodeventa']; ?></span>-<span class="numero" id="<?=$comprobante['id']?>"><?= sprintf("%08d", $comprobante['numero']);?></span>
									<?php else: ?>
										<form id="nrocomprobante-<?=$comprobante['id']?>" name="nrocomprobante-<?=$comprobante['id']?>" action="<?= $HULK->SELF?>&action=nrocomprobante" method="post">
											&nbsp;<?= $comprobante['puntodeventa']; ?>-<input type='text' name='numero' maxlength='8' style="width:80px;" />
											<input type='hidden' name='comprobanteid' value="<?=$comprobante['id']?>" />
											<input class="button-save2" type='submit' value='Guardar' style="padding:.4em;">
										</form>
									<?php endif; ?>
								</td>
								<td align="center"><span class="fecha" id="<?=$comprobante['id']?>"><?= date("d-m-Y", $comprobante['date']) ;?></span></td>
								<td class="ui-widget-content" align="center">$ <?= number_format($comprobante['importe'], 2, ',', '.') ;?></td>
								<td align="right"><span class="concepto" id="<?=$comprobante['id']?>"><?= $HULK->conceptos[$comprobante['concepto']] ;?></span></td>
								<td align="right"><span class="tipo" id="<?=$comprobante['id']?>"><?= $HULK->tipos[$comprobante['tipo']] ;?></span></td>
								<td align="left"><?= $comprobante['detalle'] ;?></td>
								<td align="center">
									<?php if($comprobante['anulada']==0): ?>
										<span  class="button-print" id="imprimir<?=$comprobante['id']?>" onClick="window.open('contactos.php?v=pagos-print&id=<?=$comprobante['id'];?>','imprimir','width=600,height=500,resizable=yes,scrollbars=yes');">Imprimir</span>
										<?php if($H_USER->has_capability('comprobante/anular') AND $comprobante['rendicionid']==0):	?>
											<span class="button-anular anular" id="<?=$comprobante['id']?>">Anular</span>
										<?php endif; ?>
										<?php if($H_USER->has_capability('comprobante/cancelar')):	?>
											<span class="button-cancelar cancelar" id="<?=$comprobante['id']?>">Cancelar</span>
										<?php endif; ?>
										<?php if($comprobante['pendiente']==1): ?>
											<?php if($H_USER->has_capability('comprobante/cobrar_pend')):	?>
												<span class="button-add cobrar" id="<?=$comprobante['id']?>">Cobrado</span>
											<?php endif; ?>
										<?php endif; ?>
										<input type="hidden" name="comp_actual" id="comp_actual" value="" />
									<?php endif; ?>
									<?php if($H_USER->has_capability('comprobante/editar')):	?>
										<span class="button-editar" id="<?=$comprobante['id']?>" onClick="window.open('contactos.php?v=editar_comprobante&id=<?=$comprobante['id'];?>','editar','width=600,height=500,scrollbars=NO');">Editar</span>
									<?php endif; ?>
								</td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	<?php endif; ?>
</div>
<br/>

<div id="dialog-confirm" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por registrar el pago. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div id="dialog-confirm2" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por anular el comprobante. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div id="dialog-confirm3" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por cancelar el comprobante. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div id="dialog-confirm4" title="Confirmar acci&oacute;n">
	<p>
		<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
		Est&aacute; por cobrar el comprobante. &iquest;Desea continuar?
		<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div>
	</p>
	<br />
	<p align="left">
		<form id="form-cobrar" action="contactos.php?v=cobrar" method="POST">
			&nbsp;&nbsp;&nbsp;&nbsp;<b>Concepto:</b>
			<select name="concepto" id="conc_cobrar">
				<?php foreach($HULK->conceptos as $id => $concepto): ?>
					<option value="<?= $id; ?>"><?= $concepto; ?></option>
				<?php endforeach; ?>
			</select>
			<input type="text" id="nro_cheque" name="nro_cheque" style="display:none;"/>
			<br />
			&nbsp;&nbsp;&nbsp;&nbsp;<b>Detalle:</b>&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="text" name="detalle" style="width:300px;"/>
			<input type="hidden" id="comp_cobrar" name="comp_cobrar" />
		</form>
	<p>
</div>
