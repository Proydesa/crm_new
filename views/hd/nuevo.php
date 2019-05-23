<script>
	$(document).ready(function() {
		$("input#username").autocomplete({
			source: function( request, response ) {
				$.getJSON( "autocomplete.php", {
					term: request.term 
				}, response );
			},
			minLength: 3,
			select: function( event, ui ) {			
				$("#contactid").val(ui.item.id);
				$('#username').attr('readonly', true);
				$('#desvincular').show();
			}
		});
		$('#desvincular').click(function(){
				$("#contactid").val("");
				$("#username").val("");
				$('#username').attr('readonly', false);
				$('#desvincular').hide();			
		});
	});
</script>
<div class="column-c" style="width:80%; overflow: auto; height: 440px">
              <div align="right" class="ui-widget">
                <em>*</em> - Requerido&nbsp;&nbsp;&nbsp;
              </div>
	<div class="portlet">
		<div class="portlet-header"><center>Registrar un incidente nuevo</center></div>
		<div class="portlet-content">
	    <form action="hd.php?v=nuevo" method="POST" enctype="multipart/form-data" class="ui-widget form-view validate" name="activitynew">

			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
						<tr style="height: 30px;">
							<th colspan="2"><b>Información de contacto</b></th>
							<th colspan="2"><b>Clasificación de los incidentes</b></th>
						</tr>

				</thead>
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 30px;">
							<td width="15%"><b>Contacto</b></td>
							<td width="35%">
								<?=$H_USER->getName();?>
							</td>
							<td><b>Academia</b><em>*</em></td>
							<td>
								<select name="academy" id="academy" class="required">
									<option value="">
									<?php foreach(explode(",",$H_USER->getAcademys('all')) as $academia):?>
										<option value="<?= $academia;?>"><?= $LMS->getField('mdl_proy_academy','name',$academia);?></option>
									<?php endforeach;?>
								</select>
							</td>		
						</tr>
						<tr style="height: 30px;">
							<td><b><label for="email">Correo Electronico</label></b></td>
							<td>
								<?=$H_USER->get_property('email');?>
							</td>
							<td width="15%"><b>Categoria</b><em>*</em></td>
							<td width="35%">
								<select name="category" id="category" class="required" OnChange="$('#customfields').load('ajax.php?f=category_form&id='+ $('#category').val());">
									<option value="" SELECTED>Seleccione una categoría</option>
									<?php foreach($activity_category as $category):?>
									<optgroup label="<?= $category['name'];?>">
									<option value="<?= $category['id'];?>"><?= $category['summary'];?></option>
									<?php endforeach;?>
								</select>
							</td>					
						</tr>
						<tr style="height: 30px;">
							<td><b>Ubicaci&oacute;n</b></td>
							<td>
								<?=$H_USER->get_property('city');?>
							</td>
							<td width="15%"><b>Vinc. contacto</b></td>
							<td width="35%">
								<input name="username" id="username" type="text" value="" style="width:65%;"/> <b>Id:</b>
								<input name="contactid" id="contactid" type="text" value="" style="width:15%;" readonly />			
							</td>									
						</tr>
						<tr style="height: 30px;">
							<td><b>Tel&eacute;fono</b></td>
							<td>
								<?=$H_USER->get_property('phone1');?>	/ <?=$H_USER->get_property('phone2');?>
							</td>
							<td width="15%"></td>
							<td width="35%"><span id="desvincular" class="button" style="display:none;">Desvincular</span></td>									
						</tr>
					<tr><th colspan="4" class="ui-widget-header">Información del incidente</th></tr>
						<tr style="height: 30px;">
							<td colspan="4"  align="left"><b>Titulo:</b><em>*</em></td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="4"  align="left">
								<input name="subject" type="text" class="required" value=""/>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="4" align="left"><b>Descripci&oacute;n:</b><em>*</em></td>						
						</tr>
						<tr style="height: 30px;">
							<td colspan="4" align="center"><textarea cols="120" rows="10" name="summary"></textarea></td>
						</tr>
				</tbody>
				<tbody id="customfields">
				</tbody>				
			</table>
			<div align="center">
				<input type="hidden" value="crear" name="action">
				<button type="button" class="add" onclick="$('.validate').validate();$('.form-view').submit();" >Registrar un incidente</button>
				<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Borrar el formulario</button>
			</div>
		</form>
		</div>
	</div>
</div>