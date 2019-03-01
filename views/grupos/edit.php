 
<script type="text/javascript">
 
   //Evento que se dispara cuando el DOM esta listo para ser utilizado
   $(document).ready(function() {
             //Seteo los CSS Class en el validador para que actuen segun su validacion
            $.validator.addClassRules({
                'required': {
                    required: true
                },
                'date': {
                    date: true
                },
                'email': {
                    email: true
                },
                'url': {
                    url: true
                }
            });
 
            //Extiendo por jQuery los mensajes del plugin validador 
            //(sobre-escribo los ya existentes)
            $.extend($.validator.messages, {
                required: "Campo requerido",
                date: "Fecha no valida",
                email: "E-mail incorrecto",
                url: "URL incorrecta"
            });
 
            // Inicio el plugin
            $("#grupos").validate();
 
        });
</script>
<?php if ($H_USER->has_capability('grupos/edit')) : ?>
	<br/><br/>
	<div class="ui-widget">
		<div class="column-p" align="center" style="width:100%">
			<div class="portlet">
					<div class="portlet-header">Formulario para editar grupos</div>
					<div class="portlet-content">
						<form action="grupos.php?v=edit&grupoid=<?= $grupo['id'];?>" method="post" name="grupos" id="grupos" class="ui-widget" class="validate"> 
							<table class="ui-widget" align="center" style="width:60%;">
								<tbody class="ui-widget-content" style="text-align:right;">									
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="country">Nombre: </label>
										</b></td>
										<td  align="left" width="50%">
											<input maxlength="" size="70" name="name" class="required" type="text" value="<?= $grupo['name'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="summary">Descripci&oacute;n: </label>
										</b></td>
										<td align="left" width="50%">
											<textarea name="summary"  cols="80" rows="5" ><?= $grupo['summary'];?></textarea>
										</td>
									</tr>
									<tr style="height: 30px;">
										<td><b>Pa&iacute;s</b></td>
										<td align="left">
											<select style="width:250px;" name="country" class="required">
												<?php foreach($HULK->countrys as $count => $country):?>
													<?php if($grupo['country']==$count): ?>
														<option value="<?= $count;?>" selected ><?= $country;?></option>		
													<?php else: ?>
														<option value="<?= $count;?>"><?= $country;?></option>	
													<?php endif;?>
												<?php endforeach;?>
											</select>
										</td>	
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="city">Ciudad: </label>
										</b></td>
										<td align="left" width="50%">
											<input maxlength="20" size="30" name="city" type="text" value="<?= $grupo['city'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="address">Direcci&oacute;n: </label>
										</b></td>
										<td align="left" width="50%">
											<input maxlength="70" size="80" name="address" type="text" value="<?= $grupo['address'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="cp">C&oacute;digo Postal: </label>
										</b></td>
										<td align="left" width="50%">
											<input maxlength="10" size="30" name="cp" type="text" value="<?= $grupo['cp'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="phone">Telefono: </label>
										</b></td>
										<td align="left" width="50%">
											<input maxlength="50" size="80" name="phone" type="text" value="<?= $grupo['phone'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="email" >Correo Electr&oacute;nico: </label>
										</b></td>
										<td align="left" width="50%">
											<input maxlength="100" size="80" name="email" type="text" value="<?= $grupo['email'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="cuit">Cuit: </label>
										</b></td>
										<td align="left" width="50%">
											<input maxlength="13" size="30" name="cuit" type="text" value="<?= $grupo['cuit'];?>" />
										</td>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="cuit">IVA: </label>
										</b></td>
										<td align="left" width="50%">
											<select name="iva">
												<option value="0">Resp. Insc.</option>
												<option value="1">Monotributo</option>
												<option value="2">No resp.</option>
												<option value="3">Exento</option>
												<option value="4">Cons. Final</option>
											</select>
										</td>
										<input type="hidden" name="grupoid" value="<?= $grupo['id'];?>"/>
									</tr>
									<tr style="height: 25px;">
										<td width="50%"><b>
											<label for="startdate">Fecha de creación: </label>
										</b></td>
										<td align="left" width="50%">
											<?php $view->jquery("$('#startdate').datepicker({	changeMonth: true,	changeYear: true, yearRange: '1950:2020', dateFormat: 'dd-mm-yy'});");?>
											<input readonly name="startdate" id="startdate" type="text" value="<?= date('d-m-Y', $grupo['startdate']);?>"/>
										</td>
									</tr>
								</tbody>
							</table>
							</p>
							<div align="center">
								<button type="submit" name="guardar" class="add">Guardar</button>
							</div>
						</form> 
					</div>
			</div>
		</div>
	</div>
<?php endif; ?>