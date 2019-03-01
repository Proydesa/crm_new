<script>
	$(document).ready(function(){

		$('#pagado').click(function(){
			if($(this).is(':checked')){
				$("#pendiente").html('PENDIENTE DE PAGO');
				// Esto es solo para FC
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

		$('.btn-success').click(function(){
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
		<form id="form-cobro" name="form-cobro" action="grupos.php?v=cobros&id=<?= $_REQUEST['id'];?>" method="POST" class="validate">
			<div class="form-wrapper">						
				<div class="input-label">Importe</div>
				<div class="input">
					<input type="text" id="importe" name="importe" value="" class="required digits" style="width:80px; margin-top:4px;" align="right">
				</div>
			</div>
			<div class="form-wrapper">						
				<div class="input-label">Forma de pago</div>
				<div class="input">
					<select name="concepto" id="concepto">
						<option value='0'></option>
					<?php foreach($HULK->conceptos as $value=>$concepto):?>
						<option id="<?= $concepto;?>" name="concepto" value="<?= $value;?>" class="required fpago" ><?= $concepto;?></option>
					<?php endforeach;?>
				</select>
				</div>
			</div>
			<div class="form-wrapper">						
				<div class="input-label">Nro. de Cheque</div>
				<div class="input">	
					<input type="text" name="nrocheque" id="nrocheque" value="" class="required" style="width:50%;" disabled />
				</div>
			</div>
			<div class="form-wrapper">						
				<div class="input-label">Detalle</div>
				<div class="input">
					<textarea maxlength="155" name="detalle" cols="50" rows="2"></textarea>
				</div>
			</div>
			<div class="form-wrapper">						
				<div class="input-label">Tipo comprobante</div>
				<div class="input">
					<select name="tipo" id="tipo">
						<option id="recibo" value="1">Recibo</option>
						<option id="factura" value="2">Factura</option>
					</select>
				</div>
			</div>
			<div class="form-wrapper">						
				<div class="input-label">Pendiente</div>
				<div class="input">
					<input type="checkbox" id="pagado" name="pendiente" value="1" />	
				</div>
			</div>
			<div id="extra-fc"></div>
			<div align="center" id="pendiente" style="color:red; font-weight:bold; font-size:13px;"></div>
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

				<div class="form-wrapper">						
					<div class="input-label"></div>
					<div class="input">
						<button class="btn btn-success"><i class="fa fa-save fa-fw"></i> Guardar</button>
					</div>
				</div>
				<input type="hidden" name="grupoid" value="<?= $_REQUEST['id']; ?>" />
			</form>
			<hr/>
			<p><a href="#" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Volver</a></p>			
		</div>
	</div>
</div>