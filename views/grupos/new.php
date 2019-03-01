 
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
                'url': {
                    url: true
                }
            });
 
            //Extiendo por jQuery los mensajes del plugin validador 
            //(sobre-escribo los ya existentes)
            $.extend($.validator.messages, {
                required: "Campo requerido",
                date: "Fecha no valida",
                url: "URL incorrecta"
            });
 
            // Inicio el plugin
            $("#grupos").validate();
 
        });
</script>

<br/><br/>
<div class="ui-widget">
	<div class="column-p" align="center" style="width:100%">
		<div class="portlet">
				<div class="portlet-header">Formulario para generar nuevos grupos</div>
				<div class="portlet-content">
					<form action="grupos.php?v=new" method="post" name="grupos" id="grupos" class="ui-widget" class="validate"> 
						<table class="ui-widget" align="center" style="width:60%;">
							<tbody class="ui-widget-content" style="text-align:right;">
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="name">Nombre: </label>
									</b></td>
									<td align="left" width="50%">
										<input align="left" maxlength="45" size="70" name="name" type="text" class="required" value="<?= $grupo['name'];?>" />
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
								<tr style="height: 25px;">
									<td><b>Pa&iacute;s</b></td>
										<td align="left" >
										<?php $gruposelpais=$H_DB->GetField('h_grupos','country',$gruposel);?>
											<select name="country" style="width:250px;" class="required">
												<?php foreach($HULK->countrys as $count => $country):?>
													<?php if($gruposelpais==$count): ?>
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
									<td align="left"  width="50%">
										<input maxlength="20" size="30" name="city" type="text" value="<?= $grupo['city'];?>" />
									</td>
								</tr>
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="address">Direcci&oacute;n: </label>
									</b></td>
									<td align="left"  width="50%">
										<input maxlength="70" size="80" name="address" type="text" value="<?= $grupo['address'];?>" />
									</td>
								</tr>
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="cp">C&oacute;digo Postal: </label>
									</b></td>
									<td align="left"  width="50%">
										<input maxlength="10" size="30" name="cp" type="text" value="<?= $grupo['cp'];?>" />
									</td>
								</tr>
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="phone">Telefono: </label>
									</b></td>
									<td align="left"  width="50%">
										<input maxlength="50" size="80" name="phone" type="text" value="<?= $grupo['phone'];?>" />
									</td>
								</tr>
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="email">Correo Electr&oacute;nico: </label>
									</b></td>
									<td align="left" width="50%">
										<!-- alan -->
										<input maxlength="100" size="80" name="email" type="text" value="<?= $grupo['email'];?>" />
										<!-- alan -->
									</td>
								</tr>
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="cuit">Cuit: </label>
									</b></td>
									<td align="left"  width="50%">
										<input maxlength="13" size="30" name="cuit" type="text" value="<?= $grupo['cuit'];?>" />
									</td>
								</tr>
								<tr style="height: 25px;">
									<td width="50%"><b>
										<label for="cuit">IVA: </label>
									</b></td>
									<td align="left"  width="50%">
										<select name="iva">
											<option value="0">Resp. Insc.</option>
											<option value="1">Monotributo</option>
											<option value="2">No resp.</option>
											<option value="3">Exento</option>
											<option value="4">Cons. Final</option>
										</select>
									</td>
								</tr>
							</tbody>
						</table>
						</p>
						<div align="center">
							<button type="submit" name="boton" class="add">Generar grupo</button>
							<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
						</div>
					</form> 
				</div>
		</div>
	</div>
</div>
