<script>
	$(document).ready(function() {
		$("#form_newconvenio").validate();
	});
</script>
<div class="column2" style="width:100%">
	<div class="portlet ">
		<div class="portlet-header">Datos del convenio</div>
		<div class="portlet-content">
		<form id="form_newconvenio" action="convenios.php?v=new" method="post" name="inscripcion" class="ui-widget form-view validate"> 
			<table class="ui-widget" align="center" style="width:50%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 20px;">
							<td width="30%"><b>Nombre:</b></td>
							<td width="70%">
								<input name="fullname" type="text" value="" class="required"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Nombre Corto:</b></td>
							<td width="70%">
								<input name="shortname" type="text" value="" class="required"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Descripción:</b></td>
							<td width="70%">
								<textarea name="summary" class="ui-widget" style="width:100%;"></textarea>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Link</b></td>
							<td width="70%">
								<input name="link" type="text" value=""/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Orden</b></td>
							<td width="70%">
								<input name="weight" type="text" value=""/>
							</td>
						</tr>
					</tbody>
			</table>
			<div align="center">
					<button type="button" class="add" onClick="$('.form-view').submit();" >Agregar convenio</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
			</div>
		</form>
		</div>
	</div>
</div>