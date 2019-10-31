
<div class="ui-widget">
	<div class="column-c" align="center" style="width:70%">
		<div class="portlet">
			<div class="portlet-header"><h2>Editar <?= $course['shortname'];?></h2></div>

			<div class="portlet-content">

				<?php if(empty($_POST)): ?>


				<form class="course-academic" method="post"  >

					<div class="form-wrapper">
						<div class="input-label"><span>Aula</span></div>
						<div class="input">
							<select name="aula" >
								<?php foreach($aulas as $aula):?>
									<option value="<?= $aula['id']?>" <?php if($aula['id']==$course_config[0]['aulaid']){echo 'selected';}?>><?= $aula['name']?> - (Cap.: <?= $aula['capacity']?>)</option>
								<?php endforeach;?>
							</select>
						</div>
					</div>


					<div class="form-wrapper">
						<div class="input-label"><span>Cambiar día de cursada</span></div>
						<div class="input">
							<select name="day" >
								<option value="">-- Días --</option>
								<?php if(!empty($asistencias)): foreach($asistencias as $asistencia): if(!$asistencia['cancelled']): ?>
									<option value="<?= $asistencia['id'] ?>"><?= $_daynames[date('w',$asistencia['sessdate'])-1].' '.date('d',$asistencia['sessdate']).' de '.$_monthnames[date('n',$asistencia['sessdate'])-1].' de '.date('Y').($asistencia['cancelled'] ? ' (cancelada)' : '') ?></option>
								<?php endif; endforeach; endif; ?>
							</select>
						</div>
					</div>
					<div class="form-wrapper">
						<div class="input-label"><span>Nuevo Día</span></div>
						<div class="input">
							<input id="new_date" name="newdate" type="text">
						</div>
					</div>
					<div class="form-wrapper">
						<div class="input-label"><span>Motivo</span></div>
						<div class="input">
							<textarea name="description" rows="6" ></textarea>
						</div>
					</div>

					<div class="form-wrapper">
						<div class="input-label"><span>Visible</span></div>
						<div class="input">
							<input name="visible" type="checkbox" <?= $course['visible'] ? 'checked' : '' ?> >
						</div>
					</div>

					<div class="form-wrapper">
						<div class="input-label"></div>
						<div class="input">
							<button class="btn btn-success"><i class="fa fa-save fa-fw"></i> Guardar</button>
						</div>
					</div>

				</form>

				<hr>
				<p><a href="courses.php?v=view&id=<?= $id ?>" class="btn btn-primary btn-sm"><i class="fa fa-angle-left"></i> Volver</a></p>

				<?php else: ?>

				<p>Los cambios se efectuaron correctamente. </p>
				<p><a href="courses.php?v=view&id=<?= $id ?>" class="btn btn-success btn-sm"><i class="fa fa-angle-left"></i> Volver</a></p>
				<?php endif; ?>


			</div>

		</div>
	</div>
</div>


<script>
$('#new_date').datepicker({
	dateFormat:'dd/mm/yy',
	firstDay:1,
	dayNamesMin:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
	monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
});
</script>