<script>
	$(document).ready(function() {
		$("#form_newacademy").validate();
	});
</script>
<div class="column2" style="width:100%">
	<div class="portlet ">
		<div class="portlet-header">Datos</div>
		<div class="portlet-content">
		<form id="form_newacademy" action="academy.php?v=new" method="post" name="inscripcion" class="ui-widget form-view validate"> 
			<table class="ui-widget" align="center" style="width:50%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 20px;">
							<td width="30%"><b>Nombre</b></td>
							<td width="70%">
								<input name="name" type="text" value="" class="required"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Nombre Corto</b></td>
							<td width="70%">
								<input name="shortname" type="text" value="" class="required"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Sede</b></td>
							<td width="70%">
								<input name="sede" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Nivel de Enseñanza</b></td>
							<td width="70%">
								<input name="nivel" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Teléfono</b></td>
							<td width="70%">
								<input name="phone" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Dirección</b></td>
							<td width="70%">
								<input name="address" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Código Postal</b></td>
							<td width="70%">
								<input name="code" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Seleccione País</b></td>
							<td width="70%">
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
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Provincia</b></td>
							<td width="70%">
								<input name="state" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Ciudad</b></td>
							<td width="70%">
								<input name="city" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Correo Electronico</b></td>
							<td width="70%">
								<input name="email" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Página Web</b></td>
							<td width="70%">
								<input name="url" type="text" value=""/>
							</td>
						</tr>
					</tbody>
			</table>
			<div align="center">
					<button type="button" class="add" onClick="$('.form-view').submit();" >Agregar academia</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
			</div>
		</form>
		</div>
	</div>
</div>