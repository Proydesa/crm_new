<script>
	function calculateSum() {
		var add = 0;
		$('.sumar').each(function() {
			add += Number($(this).val());
		});
		$('#total').attr('value', add);
		$('#pago').rules('remove', "range");					
		//$('#pago').rules('add', { range: [$('#valor_minimo').val(), $('#total').val()] });
	};
	
	function checkVacante(vacantes){
		if(vacantes <= 0){
			alert("Atención! En esta comisión no hay vacantes disponibles. \n\nEsto no impide continuar con la inscripción.");
		}
	};
	
	function checkIntensivo(intensivo){
		if(intensivo == 1){
			intensivo_str = "&intensivo=1";
		}else{
			intensivo_str = "";
		}
		$('#cuotas').load('ajax.php?f=Cuotas'+intensivo_str+'&curso='+ $('#curso').val() + '&periodo=' + $('#periodo_insc').val());
		setTimeout("calculateSum()", 200);		
	};
	
	$(document).ready(function() {
		//alan
		$.validator.addClassRules({
							'required': {
									required: true
							},							
					});

					//Extiendo por jQuery los mensajes del plugin validador 
					//(sobre-escribo los ya existentes)
					$.extend($.validator.messages, {
							required: "Campo u opción requerido/a",
							digits: "S&oacute;lo se permiten n&uacute;meros",
					}); 
		
		// Inicio el plugin
		$("#frmSuscripcion").validate();
		//alan
		<?php if(!isset($row['id'])): ?>			
			$("input#username").autocomplete({
				source: function( request, response ) {
					$.getJSON( "autocomplete.php", {
						term: request.term 
					}, response );
				},
				minLength: 3,
				select: function( event, ui ) {
					$("<div id='busy' class='ui-state-highlight'> &nbsp;Cargando...</div>").appendTo("body");
					window.location="contactos.php?v=new&id=" + ui.item.id;
				}
			});
		<?php endif;?>
			
		$('#concepto').change(function(){
			if($('#concepto').val()=="2"){
				$('#recibo').attr('disabled', false).button("refresh");
				$('#recibo').attr('checked', 'checked').button("refresh");
				$('#titulo').html("<b>Nro. de Cheque:</b>");
				$('#contenido').empty().html('<input type="text" name="nrocheque" id="nrocheque" value="" class="required" style="width:50%;" />');
				$('#nrocheque').focus();
			}else{
				if($('#concepto').val()=="3"){
					$('#recibo').attr('disabled', true).button("refresh");
					$('#factura').attr('checked', 'checked').button("refresh");
					$('#nrocheque').attr('disabled', true);
					$('#titulo').html("<b>Banco:</b>");
					$('#contenido').empty().load('ajax.php?f=Bancos');
				}else{
					$('#recibo').attr('disabled', false).button("refresh");
					$('#recibo').attr('checked', 'checked').button("refresh");
					$('#nrocheque').attr('disabled', true);
					$('#titulo').empty();
					$('#contenido').empty();
				}
			}
		});		

		$('.add').click(function(){
		//alan
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
		//alan	
			
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
				$("#porc_beca").removeAttr("disabled");
				$('#factura').attr('checked', 'checked').button("refresh");
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
			$('#valor_minimo').val(<?= $HULK->minimo_cuota ?>); 
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

<div class="column-c" style="width:80%">
	<div class="portlet ">
		<div class="portlet-header">Datos</div>
		<div class="portlet-content">
		<form id="contact-new" class="form-view validate ui-widget" action="contactos.php?v=new&stage=1" method="post" name="inscripcion"> 
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
							<td><b>Fecha de Nacimiento*</b></td>	
							<td>
								<?php $view->jquery("$('#fnacimiento').datepicker({	changeMonth: true,	changeYear: true, yearRange: '1950:2000', dateFormat: 'dd-mm-yy', monthNamesShort: ['".implode($HULK->meses,"','")."']});");?>
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
							<td><input name="phone1" class="digits" type="text"  value="<?= $row['phone1'];?>"/></td>
							<td><b>Celular</b></td>
							<td><input name="phone2" class="digits" type="text"  value="<?= $row['phone2'];?>"/></td>
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
							<td colspan="2">&nbsp;</td>						
							<td><b>Trabaja en</b></td>
							<td><input type="text" name="trabajo" /></td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="2">&nbsp;</td>						
							<td><b>Mail trabajo</b></td>
							<td><input type="text" name="mail_trabajo" /></td>
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
										<?php if ($H_USER->has_capability('contact/new/oldperiodos')): ?>										
										<tr style="height: 30px;">
											<td><b>Periodo</b></td>
											<td>
												<select name="periodo_insc" id="periodo_insc" class="required" style="width:60px;" onChange="$('#curso').empty().load('ajax.php?f=ModelPeriodo&periodo=' + $('#periodo_insc').val());">
														<?php foreach($LMS->getPeriodos() as $per):?>
															<option  value="<?= $per;?>" <?php if ($per==$HULK->periodo_insc) echo "selected";?>><?= $per;?></option>
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
												<?php if ($H_USER->has_capability('contact/paid/register')) {
													$onchange = "$('#comision').empty().load('ajax.php?f=ComiList&userid={$id}&curso=' + $('#curso').val() + '&periodo=' + $('#periodo_insc').val());
																			 $('#cuotas').load('ajax.php?f=Cuotas&curso='+ $('#curso').val() + '&periodo=' + $('#periodo_insc').val(),function() { calculateSum();});
																			 $('#libros').load('ajax.php?f=Libros&curso='+ $('#curso').val(),function() { calculateSum();});
																			 $('#valor_minimo').val({$HULK->minimo_cuota});";
												} else {
													$onchange = "$('#comision').empty().load('ajax.php?f=ComiList&userid={$id}&curso=' + $('#curso').val());";
												} ?>
												<select name="curso" id="curso" OnChange="<?= $onchange ; ?>" class="required">
														<option value='0'></option>
														<?php foreach($modelos as $modelo):?>
															<option  value="<?= $modelo['id'];?>"><?= $modelo['fullname'];?></option>
														<?php endforeach;?>
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
								<?php if (!$row['id']): ?>
									<br/><br/><p align="right">Está registrando un nuevo usuario, especificar por que medio llego este usuario a saber sobre las carreras de Fundación Proydesa:
									<select name="origen_usuario" required>
										<option value=""></option>	
										<?php foreach($origen_usuario as $origen):?>
										<option value="<?= $origen;?>"><?= $origen;?></option>	
										<?php endforeach;?>
									</select></p>
								<?php endif; ?>
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
													<td class="ui-widget-content"><?= $curso['course']; ?></td>
													<td class="ui-widget-content"><?= $curso['comision']; ?></td>
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
									<!--<span class="press" id="agregar">Agregar</span> |-->
									<span class="press" id="editar">Editar</span> |
									<span class="press" id="reset">Reset</span>
								</td>
								<td align="right">
									<input id="becado" type="checkbox" name="becado" value="1" /><b>Becado</b>
								</td>
								<td align="left">
									<select name="porc_beca" id="porc_beca" style="width:70px;" disabled>
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
								<td id="titulo"></td>
								<td id="contenido" align="left"></td>						
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
								<td align="right"><input type="checkbox" id="pagado" name="pendiente" value="1" />&nbsp;<b>Pendiente</b></td>
								<td align="center" id="pendiente" style="color:red; font-weight:bold; font-size:13px;"></td>
							</tr>	
							<tr style="height: 30px;">
								<td><b>Descripci&oacute;n:</b></td>
								<td><input type="text" name="descripcion" id="detalle" value="" style="width:90%;"></td>
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