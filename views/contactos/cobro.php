<script>
	$(document).ready(function(){

		$('#pagado').click(function(){
			if($(this).is(':checked')){
				$("#pendiente").html('PENDIENTE DE PAGO');
				// Esto es s�lo para FC
				$('#factura').attr('checked', 'checked').button("refresh");
				$('#recibo').attr('disabled', true).button("refresh");
			}else{
				$("#pendiente").html("");
				$('#recibo').attr('disabled', false).button("refresh");
			}
		});

		 $.validator.addClassRules({
                'required': {
                    required: true
                },
            });

            //Extiendo por jQuery los mensajes del plugin validador
            //(sobre-escribo los ya existentes)
            $.extend($.validator.messages, {
                required: "Campo requerido.",
            });

            // Inicio el plugin
            $("#frmSuscripcion").validate();

		$('.add').click(function(){

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
					$('#form-cobro').submit();
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});
		$('.fpago').click(function(){
			if($(this).attr('id')=="Cheque"){
				$('#nrocheque').attr('disabled', false).focus();
				$('#recibo').attr('disabled', false).button("refresh");
			}else{
				if($(this).attr('id')=="tarjeta"){
					$('#recibo').attr('disabled', true).button("refresh");
					$('#factura').attr('checked', 'checked').button("refresh");
					$('#nrocheque').attr('disabled', true);
				}else{
					$('#recibo').attr('disabled', false).button("refresh");
					$('#nrocheque').attr('disabled', true);
				}
			}
		});

	});
</script>
<div id="dialog-confirm" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por registrar el pago. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>
<input type="hidden" value="<?= $_REQUEST['id'];?>"/>
<div class="column-c" style="width:80%" align="center">

	<div class="portlet">
		<div class="portlet-header">Datos del cobro</div>
		<div class="portlet-content" >
			<form id="form-cobro" name="form-cobro" action="contactos.php?v=cobro&id=<?= $_REQUEST['id'];?>" method="POST" class="validate">
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody>
						<tr style="height: 20px;" class="ui-widget-content">
							<td align="right"><b>Importe en $:</b></td>
							<td><input type="text" id="importe" name="importe" value="" class="required digits" style="width:80px; margin-top:4px;" align="right"></td>

							<td COLSPAN=3 align="right">
								<div class="radio">
									<?php foreach($HULK->conceptos as $value=>$concepto):?>
										<input type="radio" id="<?= $concepto;?>" name="concepto" value="<?= $value;?>" class="required fpago" /><label for="<?= $concepto;?>"><?= $concepto;?></label>
									<?php endforeach;?>
								</div>
								<div>
									<span><b>Nro. de Cheque:</b></span>
									<input type="text" name="nrocheque" id="nrocheque" value="" class="required" style="width:50%;" disabled />
								</div>
							</td>
						</tr>
						<tr style="height: 20px;" class="ui-widget-content">
							<td align="center"><b>Detalle:</b></td>
							<td><textarea maxlength="155" name="detalle" cols="50" rows="2"></textarea></td>
							<td colspan=2>&nbsp;</td>
							<td colspan="2">
								<div id="extra-fc" style="float:left;"></div>
								<div class="radio" style="float:right;">
									<input type="radio" id="recibo" name="tipo" value="1" class="required"  /><label for="recibo">Recibo</label>
									<input type="radio" id="factura" name="tipo" value="2" class="required"  /><label for="factura">Factura</label>
								</div>
							</td>
						</tr>
						<tr>
							<td align="right"><input type="checkbox" id="pagado" name="pendiente" value="1" />&nbsp;<b>Pendiente</b></td>
							<td align="center" id="pendiente" style="color:red; font-weight:bold; font-size:13px;"></td>
						</tr>
						<tr>
							<center>
								<td></td>
								<td	COLSPAN=2>
									<label for="tipo" class="error">
										<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:60%">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Alert: </strong>Debe seleccionar si es 'Recibo' o 'Factura'.</p>
										</div>
									</label>
									<br/>
									<label for="concepto" class="error">
										<div class="ui-state-error ui-corner-all" style="padding: 0 .7em; width:60%">
											<p><span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
											<strong>Alert: </strong>Debe seleccionar un tipo de pago.</p>
										</div>
									</label>
								</td>
							</center>
						</tr>
						<tr style="height: 20px;" class="ui-widget-content">
							<td align="center" colspan="2">
								<span class="button add">Registrar pago</span>
							</td>
							<td><b>A nombre de empresa:</b></td>
							<td>
								<select name="grupoid" id="grupoid">
									<option value='0'>
									<?php if($grupos): ?>
										<?php foreach($grupos as $grupo):?>
											<option value="<?= $grupo['id'];?>"><?= $grupo['name'];?></option>
										<?php endforeach;?>
									<?php endif; ?>
								</select>
							</td>
						</tr>
						<input type="hidden" name="usuario" value="<?= $_REQUEST['id']; ?>" />
					</tbody>
				</table>
			</form>
		</div>
	</div>
</div>
<br/>
