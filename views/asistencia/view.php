<script>
function enabledAllSelect(){
	var inputs = document.getElementsByClassName('selectProfReemplazo');
	for(var i = 0; i < inputs.length; i++) {
		inputs[i].disabled = false;
	}
}
</script>
<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 490px'";
	}else{
		$menufixed = "";
	}
?>
<div<?= $menufixed ?>>
	<form action="asistencia.php?v=view" method="POST" style="margin:0; padding:0;">
		<div class="column" style="width:100%">
			<div class="portlet">
				<div class="portlet-header">Filtros</div>
				<div class="portlet-content" >
				<table class="ui-widget" align="center">
						<tr>
							<td>Fecha: </td>
							<td><?php $view->jquery_datepicker2("#startdate");?>
								<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= isset($dia) ? $dia : date('d-m-Y') ; ?>" />
							</td>
						</tr>
						<tr>
								<td><b>Academias</b></td>
								<td class="ui-widget-content">
									<select name="idAcademia"  style="width:500px;">
										<option value="0">Todos...</option>
										<?php foreach($academies as $academia): ?>
											<option value="<?= $academia['id']; ?>" <?=$academia['id']== (isset($_POST['idAcademia']) ? $_POST['idAcademia']:1) ? 'selected': '' ?> ><?= $academia['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
						</tr>
						<tr>
							<td><b> </b></td>
							<td><input type="submit" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" class="button"  value="Buscar" /></td>			
						</tr>
					</table>
				</div>
			</div>
		</div>		
	</form>
	<form action="asistencia.php?v=save" method="POST" style="margin:0; padding:0;">
	<input type="hidden" name="dia" value="<?= $dia;?>"/>
	<input type="hidden" name="idAcademia" value="<?= $_POST['idAcademia'];?>"/>

	<div class="column" style="width:100%<?= $ejecuto==1 ? '': ';display:none;' ?>">
			<div class="portlet">
				<div class="portlet-header">Asistencias Instructores</div>
				<div class="portlet-content" >
					<table class="ui-widget" align="center" style="width:100%;" id="asistencia-export">
						<thead>
							<th>Comisión</th>
							<th>Instructor</th>	
							<th>Asistió</th>	
							<th>Reemplazo</th>	
							<th>Llegada/Inicio de clase</th>	
							<th>Finalización de clase</th>	
							<th>Observaciones</th>	
						</thead>
						<tbody>
						<?php foreach ($cursosDelDia as $curso){ 
								$registroGuardado=count($curso['asistencia'])>0;
								$hayDocenteReemplazo= false;
								if ($registroGuardado){
									$hayDocenteReemplazo=$curso['asistencia'][0]['idInstructorReemplazo']!='';
								}
								$asistio=$curso['asistencia'][0]['Asistencia']=='PRESENTE';
						?>
							<tr  data-userid="" class="ui-widget-content" data-username="" >
							<input type="hidden" name="idsCursos[]" value="<?= $curso['id'];?>"/>
							<input type="hidden" name="idsRegistro[]" value="<?= $registroGuardado ? $curso['asistencia'][0]['id']  : ''; ?>"/>
							<input type="hidden" name="idsDocente[]" value="<?= $curso['Instructores'][0]['id']; ?>"/>
								<td style="width:15%" class="ui-widget-content textCenter"><?=$curso['fullname'];?></td>
								<td style="width:15%" class="ui-widget-content textCenter"><?=$curso['Instructores'][0]['fullname'];?></td>
								<td style="width:5%" class="ui-widget-content textCenter"><input type="checkbox" name="asiste[]" value="<?= $curso['id'];?>" <?= $registroGuardado && !$hayDocenteReemplazo && $asistio  ? 'checked' : '' ?> onclick="if (this.checked){document.getElementById('SelectReemplazo<?= $curso['id'];?>').disabled = true;document.getElementById('SelectReemplazo<?= $curso['id'];?>').value = ''}else{document.getElementById('SelectReemplazo<?= $curso['id'];?>').disabled = false}" ></td>
								<td style="width:15%" class="ui-widget-content textCenter">
									<select id="SelectReemplazo<?= $curso['id'];?>" class="selectProfReemplazo" name="reemplazo[]" <?= $registroGuardado && !$hayDocenteReemplazo ? 'disabled' : '' ?> >
										<option value="" name="instructorReemplazo" <?= !$hayDocenteReemplazo ? 'selected': '' ?> ></option>
										<?php for ($i=1;$i < count($curso['Instructores']);$i++){ ?>
											<option value="<?= $curso['Instructores'][$i]['id'];?>" name="instructorReemplazo" <?= $hayDocenteReemplazo && $curso['asistencia'][0]['idInstructorReemplazo']==$curso['Instructores'][$i]['id'] ? 'selected': '' ?>  ><?= $curso['Instructores'][$i]['fullname'];?></option>
										<?php	} ?>
									</select>
								</td>
								<td style="width:5%" class="ui-widget-content textCenter"><input type="time" name="inicio[]" id="timeStart" value="<?= $registroGuardado && $asistio ? $curso['asistencia'][0]['Inicio'] : '' ?>"  /></td>
								<td style="width:5%" class="ui-widget-content textCenter"><input type="time" name="fin[]"  id="timeEnd" value="<?= $registroGuardado && $asistio ? $curso['asistencia'][0]['Fin'] : '' ?>" /></td>
								<td style="width:40%" class="ui-widget-content textCenter"><input type="text" style="width:100%" name="observacion[]"  id="observacion" value="<?= $registroGuardado && $asistio ? $curso['asistencia'][0]['Observacion'] : '' ?>" /></td>
							</tr>	
						<?php } ?>
						<tr>
							<td colspan="7" class="textCenter"><input type="submit" onclick="enabledAllSelect()" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" class="button"  value="Guardar" /></td>			
						</tr>	
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</form>
</div>