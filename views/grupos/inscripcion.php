<script>
	function calculateSum() {
		var add = 0;
		$('.sumar').each(function() {
			add += Number($(this).val());
			$("#tot_" + $(this).attr("id")).val($(this).val()*$("#cantidad").val());
		});
		$('#total').attr('value', add);
		$('#total_empresa').attr('value', (add*$('#cantidad').val()));
		$('#pago').rules('remove', "range");
		//$('#pago').rules('add', { range: [$('#valor_minimo').val(), $('#total').val()] });
		//$('#cuotas_total'+este).val($('#cuotas'+este).val()*$("#cantidad").val());
	};

	function checkVacante(vacantes){
		if(vacantes < $('#cantidad').val()){
			alert("Atención! En esta comisión no hay tantas vacantes disponibles. \n\nEsto no impide continuar con la inscripción.");
		}
	};

	function checkIntensivo(intensivo){
		if(intensivo == 1){
			intensivo_str = "&intensivo=1";
		}else{
			intensivo_str = "";
		}
		$('#cuotas').load('grupos.php?v=cuotas'+intensivo_str+'&cant='+$('#cantidad').val()+'&curso='+ $('#curso').val());
		setTimeout("calculateSum()", 200);
	};

	$(document).ready(function() {

		$('.fpago').click(function(){
			if($(this).attr('id')=="Cheque"){
				$('#nrocheque').attr('disabled', false).focus();
			}else{
				if($(this).attr('id')=="Tarjeta de débito"){
					$('#recibo').attr('checked', false);
					$('#factura').attr('checked', true);
					$('#nrocheque').attr('disabled', true);
				}else if($(this).attr('id')=="Tarjeta de crédito"){
					$('#recibo').attr('checked', false);
					$('#factura').attr('checked', true);
					$('#nrocheque').attr('disabled', true);
				}else{
					$('#nrocheque').attr('disabled', true);
				}
			}
		});

		$('.add').click(function(){

			if($("#pagado").is(':checked')){
				if($("#detalle").val()==""){
					alert("Si selecciona pendiente debe ingresar el detalle");
					$("#detalle").focus();
					return false;
				}
			}

			$('#extra').empty().append('Total Recibido: $ '+$('#pago').val());
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
					$('#contact-new').submit();
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

		<?php if($row['saldo']>0): ?>
			$("#dialog-saldo-favor").dialog({
				resizable: false,
				height:250,
				width:450,
				modal: true,
				autoOpen: false,
				buttons: {
					"Confirmar": function() {
						$(this).dialog("close");
					}
				}
			});
			$("#dialog-saldo-favor").dialog('open');
			$("#pago").val(<?= $row['saldo']; ?>);
			//$('#pago').attr('readonly', true);
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

		$('#becado').click(function(){
			if($(this).is(':checked')){
				$("#porc_beca").removeAttr("disabled");
				$('#extra3').empty().append("El alumno est&aacute; seleccionado como becado.");
				if($('#porc_beca').val() > 0){
					calculateSum();
				}
			}else{
				$("#porc_beca").attr("disabled", true);
				$('#extra3').empty();
				$('#porc_beca').val(0)
				$('.cuota').each(function() {
					aux = $("#c" + $(this).attr("id")).val();
					$(this).val(Math.ceil(aux));
				});
				calculateSum();
			}

		});

		$('#porc_beca').change(function(){
			// Cambio el valor de las cuotas
			$('.cuota').each(function() {
				aux = $("#c" + $(this).attr("id")).val() - ($("#c" + $(this).attr("id")).val() * $('#porc_beca').val() / 100);
				$(this).val(Math.ceil(aux));
				calculateSum();
			});
		});

		$('#editar').click(function(){
			$('.readonly').attr('readonly', false);
			$('#valor_minimo').val(0);
			$('.readonly:eq(1)').focus();
		});
		$('#academyid').val(1);
		$('#carid').load('ajax.php?f=carrerasList&academy='+ $('#academyid').val());

		$('#academyid').change(function(){
			$('#carid').load('ajax.php?f=carrerasList&academy='+ $('#academyid').val());
		});
		$('#carid').change(function(){
			$('#curso').load('ajax.php?f=ModelList&carrera='+ $('#carid').val() +'&periodo='+$('#periodo_insc').val() + '&academy='+ $('#academyid').val());
		});
		<?php if ($H_USER->has_capability('contact/new/oldperiodos')): ?>
			$('#periodo_insc').change(function(){
				$('#curso').load('ajax.php?f=ModelList&periodo=' + $('#periodo_insc').val()+'&carrera='+ $('#carid').val() +'&academy='+ $('#academyid').val());
			});
		<?php	endif; ?>

		$('#curso').change(function(){
		<?php if ($H_USER->has_capability('contact/paid/register')) { ?>
			$('#comision').empty().load('ajax.php?f=ComiList&userid={$id}&curso=' + $('#curso').val() + '&periodo=' + $('#periodo_insc').val() + '&academy='+ $('#academyid').val());
			$('#cuotas').load('ajax.php?f=Cuotas&curso='+ $('#curso').val() + '&periodo=' + $('#periodo_insc').val(),function() { calculateSum();});
			$('#libros').load('ajax.php?f=Libros&curso='+ $('#curso').val(),function() { calculateSum();});
			$('#valor_minimo').val(<?=$HULK->minimo_cuota;?>);
		<?php		} else { ?>
			$('#comision').empty().load('ajax.php?f=ComiList&userid={$id}&curso=' + $('#curso').val());
		<?php		} ?>		
		});

	});
</script>

<div id="dialog-confirm" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por registrar el pago. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<?php if($row['saldo']>0): ?>
	<div id="dialog-saldo-favor" title="Confirmar acci&oacute;n">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>El alumno tiene un saldo a favor de $ <?= number_format($row['saldo'], 2, ',', '.'); ?><div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
	</div>
<?php endif; ?>
<div class="column-c" style="width:80%">
	<div class="portlet ">
		<div class="portlet-header">Datos</div>
		<div class="portlet-content">
		<form id="contact-new" class="form-view validate" action="grupos.php?v=inscripcion&stage=1" method="POST" name="inscripcion" class="ui-widget">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 30px;">
							<td width="20%"><b>Usuarios:</b></td>
							<td width="80%">
								<?php $cant=0;?>
								<?php foreach($users as $user):?>
									[<input type="checkbox" name="users[]" value="<?= $user;?>" checked /> <?= $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$user);?>]
									<?php $cant++;?>
								<?php endforeach;?>
								<input type="hidden" name="grupoid" value="<?= $group;?>" />
								<input type="hidden" id="cantidad" name="cantidad" value="<?=$cant;?>" />
							</td>
						</tr>
						<tr>
							<td colspan="2" style="vertical-align:top;">
								<table class="ui-widget" align="center" style="width:100%;">
									<thead class="ui-widget-header">
										<tr>
											<th colspan="2" class="ui-widget-header">Curso</th>
										</tr>
									</thead>
									<tbody class="ui-widget-content">
										<tr style="height: 25px;">
											<td width="30%"><b><label for="academia">Academia: </label></b></td>
											<td width="70%">
												<select name="academyid" id="academyid">
													<option value="0"></option>
						  							<?php foreach($academys as $academy):?>
														<option value="<?= $academy['id']?>"><?= $academy['name']?></option>
													<?php endforeach;?>
												</select>
											</td>
										</tr>
										<tr style="height: 25px;">
											<td width="30%"><b><label for="carrera">Carrera: </label></b></td>
											<td width="70%">
												<div class="portlet-content" id="carreraid">
													<select name="carid" id="carid">
													</select>
												</div>
											</td>
										</tr>
										<?php if ($H_USER->has_capability('contact/new/oldperiodos')): ?>
										<tr style="height: 30px;">
											<td><b>Periodo</b></td>
											<td>
												<select name="periodo_insc" id="periodo_insc" class="required" style="width:200px;">
													<?php foreach($LMS->getPeriodos() as $per):?>
														<option  value="<?= $per;?>" <?php if ($per==$HULK->periodo_insc) echo "selected";?>><?= $per;?>
															<?php
																	if ($per[2]==1){
																		echo "(Enero Febrero 20".$per[0].$per[1].")";
																	}elseif($per[2]==2){
																		echo "(Marzo Julio 20".$per[0].$per[1].")";
																	}else{
																		echo "(Agosto Diciembre 20".$per[0].$per[1].")";
																	}
															?>
														</option>
													<?php endforeach;?>
												</select>
											</td>
										</tr>
										<?php else:?>
										<tr><td><input type="hidden" name="periodo_insc" id="periodo_insc" value="<?= $HULK->periodo_insc;?>" /></td></tr>
										<?php endif;?>
										<tr style="height: 30px;">
											<td><b>Curso</b></td>
											<td>
												<select name="curso" id="curso" class="required">
												</select>
											</td>
										</tr>
										<tr style="height: 30px;">
											<td colspan="2" >
											<div name="comision" id="comision" style="overflow: auto; max-height: 250px;">
											</div>
											</td>
										</tr>
										<?php if ($H_USER->has_capability('contact/paid/register')): ?>
											<tr style="height: 30px;">
												<td><b>Libro</b></td>
												<td><div name="libros" id="libros"></div></td>
											</tr>
										<?php endif; ?>
									</tbody>
								</table>
							</td>
						</tr>
					</tbody>
					</table>
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content" style="text-align:right;">
							<?php if ($H_USER->has_capability('contact/paid/register')):	?>
							<tr><th colspan="4" class="ui-widget-header" align="center">Cuotas y Forma de Pago</th></tr>
							<tr style="height: 30px;">
								<td><b>Cuotas</b></td>
								<td name="cuotas" id="cuotas" align="center"></td>
								<td><span><b>Total del curso:</b></span></td>
								<td align="left">
									$ <input type='text' id='total' name='total' class='digits' value='' style='width:50px;' readonly>
								</td>
							</tr>
							<tr style="height: 30px;">
								<td></td>
								<td align="left">
									<!-- <span class="press" onClick="$('#cuotas').append('	$ <input type=text name=cuotas[] onKeyup=calculateSum(); class=sumar style=width:50px;>  ');" >Agregar</span>
									| --> <span class="press" id="editar">Editar</span>
									| <span class="press" onClick="
														$('#cuotas').load('grupos.php?v=cuotas&cant='+$('#cantidad').val()+'&curso='+ $('#curso').val(),function() { calculateSum();});
														$('#libros').load('grupos.php?v=libros&cant='+$('#cantidad').val()+'&curso='+ $('#curso').val(),function() { calculateSum();});
														$('#valor_minimo').val(<?= $HULK->minimo_cuota ?>);" >Reset</span></td>
								<td><span><b>Total x <?= $cant;?>:</b></span></td>
								<td align="left">
								$ <input type='text' id='total_empresa' name='total_empresa' class='digits' value='' style='width:50px;' readonly>
								</td>
							</tr>
							<tr style="height: 30px;">
								<td><b>Registro de Pago x c/u</b></td>
								<td align="left">
									$ <input type="text" name="pago" id="pago" value="" style="width:100px; margin-top:4px;">
									<input type="hidden" name="valor_minimo" id="valor_minimo" value="<?= $HULK->minimo_cuota ?>" disabled>
									<div class="radio" style="float:right;">
									<?php foreach($HULK->conceptos as $value=>$concepto):?>
										<input type="radio" id="<?= $concepto;?>" name="concepto" value="<?= $value;?>" class="required fpago" /><label for="<?= $concepto;?>"><?= $concepto;?></label>
									<?php endforeach;?>
									</div>
								</td>
								<td><span><b>Nro. de Cheque:</b></span></td>
								<td  align="left">
									<input type="text" name="nrocheque" id="nrocheque" value="" class="required" style="width:50%;" />
								</td>
							</tr>
							<?php if($row['saldo']>0): ?>
								<tr style="height: 30px;">
									<td colspan="2" style="font-size:13px; color:red">El alumno tiene un saldo a favor de <b>$ <?= number_format($row['saldo'], 2, ',', '.'); ?></b></td>
									<td colspan="2">&nbsp;</td>
								</tr>
								<input type="hidden" name="saldo" value="<?= $row['saldo']; ?>" />
							<?php endif; ?>
							<tr style="height: 30px;">
								<td colspan="2" align="left">
									<div class="radio" style="float:right;">
										<input type="radio" id="recibo" name="tipo" value="1" class="required" checked /><label for="recibo">Recibo</label>
										<input type="radio" id="factura" name="tipo" value="2" class="required" /><label for="factura">Factura</label>
									</div>
								</td>
								<td colspan="2">
								</td>
							</tr>
							<tr style="height: 30px;">
								<td align="right"><input type="checkbox" id="pagado" name="pendiente" value="1" />&nbsp;<b>Pendiente</b></td>
								<td colspan="2" align="center" id="pendiente" style="color:red; font-weight:bold; font-size:13px;"></td>
								<td>&nbsp;</td>
							</tr>
							<tr style="height: 30px;">
								<td align="right"><input type="checkbox" id="becado" name="becado" value="1" />&nbsp;<b>Becado</b></td>
								<td colspan="2" align="left">
									<select name="porc_beca" id="porc_beca" style="width:70px;" disabled>
										<option value="0"></option>
										<?php for($i=1; $i<101; $i++): ?>
											<option value="<?= $i; ?>"><?= $i; ?> %</option>
										<?php endfor; ?>
									</select>
								</td>
								<td>&nbsp;</td>
							</tr>
							<tr style="height: 30px;">
								<td><b>Descripci&oacute;n:</b></td>
								<td><input type="text" name="descripcion" id="detalle" value="" style="width:90%;"></td>
								<td></td>
								<td></td>
							</tr>
						<?php endif;?>
				</tbody>
			</table>
			<div align="center">
					<input type="hidden" name="id" value="<?= $row['id'];?>">
					<button type="button" class="add">Guardar datos</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
			</div>
		</form>
		</div>
	</div>
</div>
