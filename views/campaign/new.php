<br/>

<div class="column" style="width:20%">
<form action="<?= $HULK->SELF?>" method="post" name="parametros">
	<div class="portlet">
		<div class="portlet-header">Chat Online</div>
		<div class="portlet-content" >
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
			<br/>
		</div>
	</div>
</form>
</div>
<div class="column" style="width:80%">
	<div class="portlet">
		<div class="portlet-header">Crear campaña</div>
		<div class="portlet-content">
		<form class="form-view validate" action="contactos.php?v=new&stage=1" method="post" name="inscripcion" class="ui-widget"> 
			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
						<tr style="height: 30px;">
							<th colspan="4"><b>Información sobre la campaña</b></th>
						</tr>
				</thead>
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 30px;">
							<td width="15%"><b>Encargado de la campaña</b></td>
							<td width="35%">
								<input name="username" type="text" class="required" value="<?= $row['username'];?>"/>
							</td>
							<td width="15%"><b>Tipo</b></td>
							<td width="35%">
								<select name="academia" id="academia">
									<option value="0">-Ninguno-</option>
									<option value="1">Conferencia</option>
									<option value="2">eMail</option>
									<option value="3">Formulario web</option>
									<option value="4">Anuncio</option>
								</select>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b><label for="email">Nombre de la campaña</label></b></td>
							<td>
								<input id="email" name="email" type="text" class="required email" value="<?= $row['email'];?>"/>
							</td>
							<td width="15%"><b>Estado</b></td>
							<td width="35%">
								<select name="academia" id="academia">
									<option value="0">-Ninguno-</option>
									<option value="1">Planeamiento</option>
									<option value="2">Activa</option>
									<option value="3">Inactiva</option>
									<option value="4">Completa</option>
								</select>		
							</td>
						</tr>

						<tr style="height: 30px;">
							<td>
								<b><label for="startdate">Fecha de inicio: </label></b>
							</td>
							<td>
									<input type="text" id="datepicker" name="startdate"/>
							</td>
							<td>
									<b><label for="enddate">Fecha de cierre: </label></b>
							</td>
							<td>
									<input type="text" id="datepicker1" name="enddate"/>
							</td>
						</tr>		
						<script>
							$(function() {
								$( "#datepicker" ).datepicker();
							});
							$(function() {
								$( "#datepicker1" ).datepicker();
							});
						</script>				
						<tr style="height: 30px;">
							<td><b>Ingresos previstos: </b></td>
							<td>
								<input id="email" name="email" type="text" class="required email" value="<?= $row['email'];?>"/>
							</td>
							<td width="15%"><b>Ingresos presupuestados: </b></td>
							<td width="35%">
								<input id="email" name="email" type="text" class="required email" value="<?= $row['email'];?>"/>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Costos reales: </b></td>
							<td>
								<input id="email" name="email" type="text" class="required email" value="<?= $row['email'];?>"/>
							</td>
							<td><b>Respuesta prevista: </b></td>
							<td>
								<input id="email" name="email" type="text" class="required email" value="<?= $row['email'];?>"/>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Numero enviado: </b></td>
							<td>
								<input name="phone1" type="text" value="<?= $row['phone1'];?>"/>
							</td>
							<td></td>
							<td></td>
						</tr>
					<tr><th colspan="4" class="ui-widget-header">Descripcion/Información</th></tr>
						<tr style="height: 30px;">
							<td colspan="4" align="left"><b>Descripci&oacute;n:</b></td>						
						</tr>
						<tr style="height: 30px;">
							<td colspan="4" align="center"><textarea cols="150" rows="10"></textarea></td>
						</tr>
				</tbody>
			</table>
			<div align="center">
					<input type="hidden" name="id" value="<?= $row['id'];?>">
					<button type="button" class="add" onClick="$('.form-view').submit();" >Guardar datos</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
			</div>
		</form>
		</div>
	</div>
</div>


