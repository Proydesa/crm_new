<script>

	$(document).ready(function() {
		$.validator.addClassRules({'required': {required: true}});
		$.extend($.validator.messages, {
			required: "Campo u opción requerido/a",
			digits: "S&oacute;lo se permiten n&uacute;meros",
		});
		$("#frmSuscripcion").validate();
		$("input#username").autocomplete({
			source: function( request, response ) {
				$.getJSON( "autocomplete.php", {
					term: request.term
				}, response );
			},
			minLength: 3,
			select: function( event, ui ) {
				$("<div id='busy' class='ui-state-highlight'> &nbsp;Cargando...</div>").appendTo("body");
				window.location="contactos.php?v=view&id=" + ui.item.id;
			}
		});

		$('.add').click(function(){

			var nombre = $('#firstname').val();
			var apellido = $('#lastname').val();
			var email = $('#email').val();
			var fnacimiento = $('#fnacimiento').val();
			var dni= $('#username').val();
			var pago = $('#pago').val();

			if((nombre=="")||(apellido=="")||(email=="")||(fnacimiento=="")||(dni=="")||(pago=="")){
				$('#error').show();
				return false;
			}
			if((nombre!="")&&(apellido!="")&&(email!="")&&(fnacimiento!="")&&(dni!="")||(pago=="")){
				$('#error').hide();
			}

			$("#contact-new").validate();
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
		$('#fnacimiento').datepicker({	changeMonth: true,	changeYear: true, yearRange: '1950:2000', dateFormat: 'dd-mm-yy'});
	});
</script>

<div id="dialog-confirm" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por registrar el usuario. &iquest;Desea continuar?<div id='extra2' align="center" style="font-size:12px;font-weight:bold;"></div><br /><div id='extra3' align="center" style="font-size:12px;font-weight:bold;"></div><br /><br /><div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div class="column-c" style="width:80%">
	<div class="portlet ">
		<div class="portlet-header">Datos</div>
		<div class="portlet-content">
		<form id="contact-new" class="form-view validate ui-widget" action="contactos.php?v=nuevo_usuario&stage=1" method="post" name="inscripcion">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 30px;">
							<td width="15%"><b>DNI*</b></td>
							<td width="35%">
								<input name="username" id="username" type="text" value="<?= $row['username'];?>"/>
								<p id="estado"></p>
							</td>
							<td width="15%"></td>
							<td width="35%"></td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Apellido*</b></td>
							<td><input name="lastname" id="lastname" type="text" value="<?= $row['lastname'];?>"/></td>
							<td><b>Nombre*</b></td>
							<td><input name="firstname" id="firstname" type="text" value="<?= $row['firstname'];?>"/></td>
						</tr>
						<tr style="height: 30px;">
							<td><b><label for="email">E-Mail*</label></b></td>
							<td><input id="email" name="email" type="text"  value="<?= $row['email'];?>"/></td>
							<td><b>Fecha de Nacimiento (dd-mm-yyyy)*</b></td>
							<td>
								<input name="fnacimiento" id="fnacimiento" type="text" value="<?= date('d-m-Y',$row['fnacimiento']);?>" />
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Direcci&oacute;n</b></td>
							<td><input name="address" type="text" value="<?= $row['address'];?>"/></td>
							<td><b>C&oacute;digo Postal</b></td>
							<td><input name="cp" type="text" value="<?= $row['cp'];?>"/></td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Pa&iacute;s</b></td>
							<td>
								<select name="country" class="required">
									<?php foreach($HULK->countrys as $count => $country):?>
										<?php if($row['country']): ?>
											<?php ($count==$row['country'])? $SELECTED="selected":$SELECTED="";?>
										<?php else: ?>
											<?php ($count=="AR")? $SELECTED="selected":$SELECTED="";?>
										<?php endif; ?>
										<option value="<?= $count;?>" <?= $SELECTED;?>><?= $country;?></option>
									<?php endforeach;?>
								</select>
							</td>
							<td><b>Localidad</b></td>
							<td><input name="city" type="text" value="<?= $row['city'];?>"/></td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Tel&eacute;fono</b></td>
							<td><input name="phone1" class="digits" type="text"  value="54911<?= $row['phone1'];?>"/></td>
							<td><b>Celular</b></td>
							<td><input name="phone2" class="digits" type="text"  value="54911<?= $row['phone2'];?>"/></td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Sexo</b></td>
							<td>
								<select name="sexo">
									<option value="M" <?php if($row['sexo']=='M') echo "selected" ?>>Masculino</option>
									<option value="F" <?php if($row['sexo']=='F') echo "selected" ?>>Femenino</option>
									<option value="N" <?php if($row['sexo']=='N') echo "selected" ?>>Prefiero no decirlo</option>
								</select>
							</td>
							<td><b>Observaciones</b></td>
							<td><input name="obs" type="text" value="<?= $row['obs'];?>"/></td>
						</tr>
						<tr style="height: 30px;">
							<td>Por que medio llego este usuario a saber sobre las carreras de Fundación Proydesa:</td>
							<td> <select name="origen_usuario" required>
										<option value=""></option>
										<?php foreach($origen_usuario as $origen):?>
										<option value="<?= $origen;?>"><?= $origen;?></option>
										<?php endforeach;?>
									</select></p></td>
							<td><b>Trabaja en</b></td>
							<td><input type="text" name="trabajo" /></td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="2">&nbsp;</td>
							<td><b>Mail trabajo</b></td>
							<td><input type="text" name="mail_trabajo" /></td>
						</tr>
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
