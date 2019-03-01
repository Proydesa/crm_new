<script>
	$(document).ready(function() {
		$("#form_newacademy").validate();
	});
</script>
<div class="column2" style="width:100%">
	<div class="portlet ">
		<div class="portlet-header">Edición de convenios</div>
		<div class="portlet-content">
		<form id="form_newacademy" action="academy.php?v=agregar_convenio" method="post" name="inscripcion" class="ui-widget form-view validate"> 
			<table class="ui-widget" align="center" style="width:70%;">
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 20px;">
							<td width="30%"><b>Academia:</b></td>
							<td width="70%">
								<select name="academyid" class="required" id="academyselect" >
									<?php foreach($academias as $academia):?>
										<option value="<?= $academia['id'];?>" <?= $acad['select'][$academia['id']];?>  ><?= $academia['name'];?></option>	
									<?php endforeach;?>
								</select>				
								<?= $acad['readonly'];?>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Convenio:</b></td>
							<td width="70%">
								<select name="convenioid" class="required" id="convenioselect">
									<?php foreach($convenios as $row):?>
										<option value="<?= $row['id'];?>"  <?= $conv['select'][$row['id']];?>><?= $row['fullname'];?></option>	
									<?php endforeach;?>
								</select>
								<?= $conv['readonly'];?>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Detalle: </b></td>
							<td width="70%">
								<input name="summary" type="text" value="<?= $conv['summary'];?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b>Forma de pago: </b></td>
							<td width="70%">
								<select name="forma_de_pago" class="required">
									<option value="por_alumno">Por alumno</option>
									<option value="por_comision">Por comisión</option>
									<option value="fee_anual">Fee anual</option>
									<option value="plan_social">Plan social</option>
									<option value="instructor">Instructor</option>
								</select>
							</td>
						</tr>
						<tr style="height: 20px;">
							<?php $view->jquery_datepicker("#startdate, #enddate");?>
							<td width="30%"><b><label for="startdate">Fecha de inicio (dd-mm-aaaa): </label></b></td>
							<td width="70%">
								<input type="text" id="startdate" name="startdate" value="<?= $conv['startdate'];?>" readonly="readonly" />
							</td>
						</tr>
						<tr style="height: 20px;">
							<td width="30%"><b><label for="enddate">Fecha de cierre (dd-mm-aaaa): </label></b></td>
							<td width="70%">
								<input type="text" id="enddate" name="enddate" value="<?= $conv['enddate'];?>" readonly="readonly" />
							</td>
						</tr>
					</tbody>
			</table>
			<div align="center">
					<input type="hidden" name="guardar" value="1" />
					<input type="hidden" name="id" value="<?= $conv['id']?>" />
					<button type="button" class="add" onClick="$('.form-view').submit();" name="guardar" value="Guardar" />Guardar</button>
					<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
			</div>
		</form>
		</div>
	</div>
</div>