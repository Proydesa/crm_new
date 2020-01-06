<script>
	function calculateSum() {
		var add = 0;
		$('.sumar').each(function() { add += Number($(this).val()); });
		$('#total').attr('value', add);
		$('#pago').rules('remove', "range");
		$('#pago').rules('add', { range: [$('#valor_minimo').val(), $('#total').val()] });
	};

	function checkVacante(vacantes){
		if(vacantes <= 0){
			alert("Atención! En esta comisión no hay vacantes disponibles. \n\nEsto no impide continuar con la inscripción.");
		}
	};

	function checkIntensivo(intensivo){
		if(intensivo == 1){ intensivo_str = "&intensivo=1";	}else{ intensivo_str = "";	}
		$('#cuotas').load('ajax.php?f=Cuotas'+intensivo_str+'&curso='+ $('#curso').val() + '&periodo=' + $('#periodo_insc').val());
		setTimeout("calculateSum()", 200);
	};

	function resetCuotas(){
		$('.cuota').each(function(index) {
			aux = $("#c" + $(this).attr("id")).val();
			$(this).val(aux);
		});
	}

	function evaluarBanco(id){
		if(id==49){
			document.getElementById('detalle').value = "Pago con tarjeta de: Banco ";
		}else{
			document.getElementById('detalle').value = "";
		}
	}
	
	$(document).ready(function() {
		//alan
		$.validator.addClassRules({	'required': { required: true },		});

		//Extiendo por jQuery los mensajes del plugin validador
		//(sobre-escribo los ya existentes)
		$.extend($.validator.messages, {
				required: "Campo u opción requerido/a",
				digits: "S&oacute;lo se permiten n&uacute;meros",
		});

		// Inicio el plugin
		$("#frmSuscripcion").validate();

		$('#concepto').change(function(){
			switch($('#concepto').val()){
				case '2':
					$('#recibo').attr('disabled', false).button("refresh");
					$('#recibo').attr('checked', 'checked').button("refresh");
					$('#titulo').html("<b>Nro. de Cheque:</b>");
					$('#contenido').empty().html('<input type="text" name="nrocheque" id="nrocheque" value="" class="required" style="width:94%;" />');
					$('#nrocheque').focus();
					$('#titulo_tarjeta').empty();
					$('#tarjetas').empty();
					break;
				case '5':
					$('#recibo').attr('disabled', true).button("refresh");
					$('#factura').attr('checked', 'checked').button("refresh");
					$('#nrocheque').attr('disabled', true);
					$('#titulo').html("<b>Banco:</b>");
					$('#contenido').empty().load('ajax.php?f=Bancos');
					$('#titulo_tarjeta').html("<b>Tarjeta:</b>");
					$('#tarjetas').empty().load('ajax.php?f=CreditCards');
				case '3':
					$('#recibo').attr('disabled', true).button("refresh");
					$('#factura').attr('checked', 'checked').button("refresh");
					$('#nrocheque').attr('disabled', true);
					$('#titulo').html("<b>Banco:</b>");
					$('#contenido').empty().load('ajax.php?f=Bancos');
					$('#titulo_tarjeta').empty();
					$('#tarjetas').empty();
					break;
				default:
					$('#recibo').attr('disabled', false).button("refresh");
					$('#recibo').attr('checked', 'checked').button("refresh");
					$('#nrocheque').attr('disabled', true);
					$('#titulo').empty();
					$('#contenido').empty();
					$('#titulo_tarjeta').empty();
					$('#tarjetas').empty();
					break;
			}
		});

		$('.add').click(function(){
			var pago = $('#pago').val();

			if($("#pagado").is(':checked')){
				if($("#detalle").val()==""){
					alert("Si selecciona pendiente debe ingresar el detalle");
					$("#detalle").focus();
					return false;
				}
			}

			if($("#becado").is(':checked')){
				if($("#detalle").val()==""){
					alert("Si selecciona becado debe ingresar el detalle");
					$("#detalle").focus();
					return false;
				}
			}

			$("#contact-new").validate();

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
				$('#extra2').empty().append("El comprobante quedar&aacute; pendiente de pago.");
			}else{
				$("#pendiente").html("");
				$('#recibo').attr('disabled', false).button("refresh");
				$('#extra2').empty();
			}
		});

		$('#becado').click(function(){
			if($(this).is(':checked')){
				$('#descuento').attr('checked', false);
				$("#porc_beca").removeAttr("disabled");
				$("#porc_desc").attr("disabled", true);
				$('#porc_desc').val(0)
				$('#factura').attr('checked', 'checked').button("refresh");
				$('#extra3').empty().append("El alumno est&aacute; seleccionado como becado.");
				resetCuotas();
				if($('#porc_beca').val() > 0){
					calculateSum();
				}
			}else{
				$("#porc_beca").attr("disabled", true);
				$('#extra3').empty();
				$('#porc_beca').val(0)
				resetCuotas();
				calculateSum();
			}
		});
		$('#descuento').click(function(){
			if($(this).is(':checked')){
        $('#becado').attr('checked', false);
				$("#porc_desc").removeAttr("disabled");
				$("#porc_beca").attr("disabled", true);
				$('#porc_beca').val(0)
				$('#factura').attr('checked', 'checked').button("refresh");
				$('#extra3').empty().append("El alumno est&aacute; seleccionado con descuento.");
				resetCuotas();
				if($('#porc_desc').val() > 0){
					calculateSum();
				}
			}else{
				$("#porc_desc").attr("disabled", true);
				$('#extra3').empty();
				$('#porc_desc').val(0)
				resetCuotas();
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
		$('#porc_desc').change(function(){
			$('.cuota').last().val($('.cuota2').last().val());
				aux = $('.cuota').last().val() - ($("#total2").val() * $('#porc_desc').val() / 100);
				// recorrer do---- while aux < cuota.
				$('.cuota').last().val(Math.ceil(aux));
				calculateSum();
		});

		$("#agregar").click(function(){
			$('#cuotas').append(" $ <input type='text' name='cuotas[]' onKeyup='calculateSum();' class='sumar' style='width:50px;'> ");
		});

		$("#editar").click(function(){
			$('.readonly').attr('readonly', false);
			$('#valor_minimo').val(0);
			$('.readonly:eq(1)').focus();
		});

		$("#reset").click(function(){
			$('#cuotas').load('ajax.php?f=Cuotas&curso='+ $('#curso').val() + '&periodo=' + $('#periodo_insc').val(), function(){ calculateSum(); });
			$('#libros').load('ajax.php?f=Libros&curso='+ $('#curso').val(), function(){ calculateSum(); });
			$('#valor_minimo').val(<?= $HULK->minimo_cuota; ?>);
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
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por registrar el pago. &iquest;Desea continuar?<div id='extra2' align="center" style="font-size:12px;font-weight:bold;"></div><br /><div id='extra3' align="center" style="font-size:12px;font-weight:bold;"></div><br /><br /><div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<?php if($row['saldo']>0): ?>
	<div id="dialog-saldo-favor" title="Confirmar acci&oacute;n">
		<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>El alumno tiene un saldo a favor de $ <?= number_format($row['saldo'], 2, ',', '.'); ?><div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
	</div>
<?php endif; ?>
<div class="column-c" style="width:90%">
	<div class="portlet ">
		<div class="portlet-header">Datos</div>
		<div class="portlet-content">
		<form id="contact-new" class="form-view validate ui-widget" action="contactos.php?v=inscribir_usuario_proceso&stage=1" method="post" name="inscripcion">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr>
							<td colspan="2" style="vertical-align:top;">
								<table class="ui-widget" align="center" style="width:100%;">
									<thead class="ui-widget-header">
										<tr>
											<th colspan="2" class="ui-widget-header">Selección de comisión</th>
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
										<tr><td colspan="2"><input type="hidden" name="periodo_insc" id="periodo_insc" value="<?= $HULK->periodo_insc;?>" /></td></tr>
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
							<td colspan="2" style="vertical-align:top;">
								<?php if ($cuotas): ?>
									<table class="ui-widget" align="center" style="width:100%;">
										<thead class="ui-widget-header">
											<tr><th colspan="8">Historial Cuotas</th></tr>
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
												<tr style="height: 20px;<?php if($cuota['baja']>0) echo "text-decoration:line-through;"; ?>" title="<?php if($cuota['baja']>0) echo "Baja: ".$H_DB->GetField("h_bajas", "detalle", $cuota['baja']); ?>">
													<td class="ui-widget-content"><b><?= $LMS->GetField('mdl_course','shortname',$cuota['id']); ?></b></td>
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
								<?php endif; ?>
								<?php if($cursos):?>
									<table class="ui-widget" align="center" style="width:100%;">
										<thead class="ui-widget-header">
											<tr><th colspan="10">Historial Cursos</th></tr>
											<tr>
												<th>Curso</th>
												<th>Comisión</th>
												<th>Instructor</th>
												<th>Final</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody class="ui-widget-content">
											<?php foreach($cursos as $curso):?>
												<tr style="height: 20px;">
													<td class="ui-widget-content"><?= $curso['model']; ?></td>
													<td class="ui-widget-content"><?= $curso['course']; ?></td>
													<td class="ui-widget-content"><?= $curso['instructor']; ?></td>
													<td class="ui-widget-content"><?= number_format($curso['final'],2); ?></td>
													<td class="ui-widget-content"><?= $grades[(int)$curso['status']];?></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								<?php endif;?>
							<td>
						</tr>
						<?php if ($H_USER->has_capability('contact/paid/register')):	?>
							<tr><th colspan="4" class="ui-widget-header" align="center">Cuotas y Forma de Pago</th></tr>
							<tr style="height: 30px;">
								<td width="150px"><b>Cuotas</b></td>

								<td name="cuotas" id="cuotas" align="center"></td>
								<td width="150px"><span><b>Total del curso:</b></span></td>
								<td align="left">
									$<input type='text' id='total' name='total' class='digits' value='' style='width:50px;' readonly>
								</td>
							</tr>
							<tr style="height: 30px;">
								<td></td>
								<td align="left">
									<!--<span class="press" id="agregar">Agregar</span> |-->
									<span class="press" id="editar">Editar</span> |
									<span class="press" id="reset">Reset</span>
								</td>
								<td align="center">
									<input id="becado" type="checkbox" name="becado" value="1" /><b>Beca:</b>
									<select name="porc_beca" id="porc_beca" style="width:70px;" disabled>
										<option value="0"></option>
										<?php for($i=1; $i<101; $i++): ?>
											<option value="<?= $i; ?>"><?= $i; ?> %</option>
										<?php endfor; ?>
									</select>
								</td>
								<td align="center">
									<input id="descuento" type="checkbox" name="descuento" value="1" /><b>Descuento:</b>
									<span class='ui-icon ui-icon-help' style='display:inline-block'></span>
									<select name="porc_desc" id="porc_desc" style="width:70px;" disabled>
										<option value="0"></option>
										<?php for($i=1; $i<101; $i++): ?>
											<option value="<?= $i; ?>"><?= $i; ?> %</option>
										<?php endfor; ?>
									</select>
								</td>
							</tr>
							<tr style="height: 30px;">
								<td><b>Registro de Pago*</b></td>
								<td align="left">
									$ <input type="text" name="pago" id="pago" value="" style="width:100px; margin-top:4px;" />
									<input type="hidden" name="valor_minimo" id="valor_minimo" value="<?= $HULK->minimo_cuota ?>" disabled />
									<select name="concepto" id="concepto" style="width:150px;">
											<?php foreach($HULK->conceptos as $value=>$concepto):?>
												<option value="<?= $value;?>" class="required fpago" id="<?= $concepto;?>"><?= $concepto;?></option>
											<?php endforeach;?>
									</select>
								</td>
								<td colspan="2">
									<table class="ui-widget"><tr><td id="titulo"></td><td id="contenido" align="left"></td><td></td><td id="titulo_tarjeta"></td><td id="tarjetas"></td></tr></table>
								</td>
							</tr>
							<?php if($row['saldo']>0): ?>
								<tr style="height: 30px;">
									<td colspan="2" style="font-size:13px; color:red">El alumno tiene un saldo a favor de <b>$ <?= number_format($row['saldo'], 2, ',', '.'); ?></b></td>
									<td colspan="2"></td>
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
								<td align="right"><input type="checkbox" id="pagado" name="pendiente" value="1" />&nbsp;<b>Pendiente</b></td>
								<td align="center" id="pendiente" style="color:red; font-weight:bold; font-size:13px;"></td>
							</tr>
							<tr style="height: 30px;">
								<td><b>Detalle:</b></td>
								<td><input type="text" name="detalle" id="detalle" value="" style="width:90%;"></td>
								<td><b>A nombre de empresa:</b></td>
								<td>
									<select name="grupoid" id="grupoid">
										<option value='0'></option>
										<optgroup label="Empresas asociadas">
										<?php if($grupos): ?>
											<?php foreach($grupos as $grupo):?>
												<option value="<?= $grupo['id'];?>"><?= $grupo['name'];?></option>
											<?php endforeach;?>
										<?php endif; ?>
										</optgroup>
											<optgroup label="Otras empresas">
											<?php foreach($grupos_otros as $grupo):?>
												<option value="<?= $grupo['id'];?>"><?= $grupo['name'];?></option>
											<?php endforeach;?>
											</optgroup>
									</select>
								</td>
							</tr>
						<?php endif;?>
				</tbody>
			</table>
			<div align="center">
					<div id="error" class="ui-state-error ui-corner-all" style="padding: 0.7em; width:30%;display:none;">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Ingrese todos los datos obligatorios (*).</strong></p>
											<br/>
					</div>
					<input type="hidden" name="id" value="<?= $row['id'];?>">
					<button type="button" class="add">Guardar datos</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
			</div>
		</form>
		</div>
	</div>
</div>
