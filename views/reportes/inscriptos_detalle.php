<?php
	if($rows):
		foreach($rows as $row):
			$cursos[$row['model']] += count($row['alumnos']);
		endforeach;
	endif;

?>

<script>
	$(document).ready(function(){

		$("#selecall").click(function(event){
			 if($("#selecall").is(':checked')) {
				$('.academy').each( function() {
					$(this).attr("checked","checked");
				});
			}else{
				$('.academy').each( function() {
					$(this).attr("checked",false);
				});
			}
		});
		$("#selecall2").click(function(event){
			 if($("#selecall2").is(':checked')) {
				$('.carrera').each( function() {
					$(this).attr("checked","checked");
				});
			}else{
				$('.carrera').each( function() {
					$(this).attr("checked",false);
				});
			}
		});
		<?php foreach($LMS->getPaises() as $pais): ?>
			$(".<?= $pais;?>").click(function(event){
				 if($(".<?= $pais;?>").is(':unchecked')) {
					$('#<?= $pais;?>').each( function() {
						$(this).attr("checked",false);
					});
				}
			});
			$("#<?= $pais;?>").click(function(event){
				 if($("#<?= $pais;?>").is(':checked')) {
					$('.<?= $pais;?>').each( function() {
						$(this).attr("checked","checked");
					});
				}else{
					$('.<?= $pais;?>').each( function() {
						$(this).attr("checked",false);
					});
				}
			});
		<?php endforeach;?>

	});
</script>

<!-- Barra de ruta y botones de accion -->
<div class="ui-widget noprint" align="right">
<table  align="right" class="noprint">
	<tr>
		<td>
			<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
		</td>
	</tr>
</table>
</div>
<div id="esconder" class="noprint">
		<form action="reportes.php?v=inscriptos&t=detalle" name="form" method="post">
			<div class="column" style="width:200px">
				<div class="portlet">
					<div class="portlet-header">Filtros</div>
					<div class="portlet-content" style="overflow:auto;max-height:300px;">
						<ul class="noBullet">
							<span>Periodo: </span>
							<select  name="periodos" id="periodos">
								<?php foreach($LMS->getPeriodos() as $periodo):?>
									<li><option value="<?= $periodo;?>" <?php if($periodo==$periodos_sel) echo 'selected="selected"'; ?>><?= $periodo;?></option></li>
								<?php endforeach;?>
							</select>
						</ul>
					<!--	<p>Este informe solo incluye los enrolados activos. Para incluir todos los inscriptos (bajas)
							<input type="checkbox" name="conbajas" value="1" <?php if($conbajassel) echo "checked"; ?>>
						</p>-->
					</div>
				</div>
			</div>
			<div class="column" style="width:600px">
				<div class="portlet">
					<div class="portlet-header">Academias</div>
					<div class="portlet-content">
						<center>
							<input type="checkbox" class="pais" id="selecall" value="selecall"/><label for="selecall">Todas</label>
								<?php foreach($LMS->getPaises() as $pais): ?>
										<?php if (!$H_USER->has_capability("reportes/academycourse/internacionales") AND $pais!="AR"){
											$estado="disabled=disabled";
											}else{
												$estado="";
											}
										?>
										| <input type="checkbox" <?= $estado;?> name="pais[]" class="pais" id="<?=$pais;?>" value="<?= $pais;?>" <?php if(in_array($HULK->countrys[$pais],$paisel)) echo "checked"; ?>/><label for="<?= $pais;?>"><?=$HULK->countrys[$pais];?></label>
								<?php endforeach; ?>
						</center>
						<ul class="noBullet"  style="overflow:auto;max-height:120px;">
							<?php foreach($LMS->getAcademias() as $acadlist): ?>
										<?php if (!$H_USER->has_capability("reportes/academycourse/internacionales") AND $acadlist['country']!="AR"){
												$estado='disabled="disabled"';
											}else{
												$estado='';
											}
										?>
								<li><input type="checkbox" <?= $estado;?> name="academias[]" class="academy <?=$acadlist['country'];?>" value="<?=$acadlist['id'];?>" <?php if(in_array($acadlist['id'],$acad_sel)) echo "checked"; ?>/><label for="academias[]"><?=$acadlist['name'];?></label></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="column" style="width:200px">
				<div class="portlet">
					<div class="portlet-header">Carreras</div>
					<div class="portlet-content">
						<center>
							<input type="checkbox" class="pais" id="selecall2" value="selecall2"/><label for="selecall2">Todas</label>
						</center>
						<ul class="noBullet" style="overflow:auto;max-height:120px;">
							<?php foreach($LMS->getCursosModelo() as $carrer): ?>
								<li><input type="checkbox" name="carrera[]" class="carrera" value="<?=$carrer['id'];?>" <?php if(in_array($carrer['id'],$carsel)) echo "checked"; ?>/><label for="carrera[]"><?=$carrer['shortname'];?></label></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
			<input type="submit" name="boton"  style="height: 30px; font-size:13px; width:100%; font-weight: bold;" class="button"  value="Consultar" />
		</form>
</div>

<div class="column-c" style="width:100%">
	<div class="portlet">
		<div class="portlet-header">Inscriptos por carrera</div>
		<table class="ui-widget" align="center" style="width:100%;">
			<thead>
				<tr style="height: 20px;" class="ui-widget-header">
					<th>Curso</th>
					<th>Total</th>
				</tr>
			</thead>
			<tbody class="ui-widget-content">
				<?php if($cursos):?>
					<?php foreach($cursos as $curso => $tot_cur):?>
						<tr style="height: 20px;" class="ui-widget-content">
							<td class="press" ondblclick="">
								<a href="#" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a><?= $curso;?>
							</td>
							<td align="right"><?= $tot_cur; ?></td>
							<?php $tot_curs += $tot_cur; ?>
						</tr>
					<?php endforeach;?>
				<?php endif;?>
				<tr style="height: 20px;" class="ui-widget-footer">
					<td align="left"><b>Total</b></td>
					<th align="right"><b><?= $tot_curs;?></b></th>
				</tr>
			</tbody>
		</table>
	</div>

	<?php if($rows): ?>
		<div class="portlet">
			<div class="portlet-header">Inscriptos por comisi&oacute;n</div>
			<table style='font-family:Arial;font-size:12px;' align='center' class="ui-widget-content" width="100%">
				<tr>
					<td colspan="2">
						<table style='font-family:Arial;font-size:12px;' align='center' class="ui-widget" width="100%">
							<?php foreach($rows as $comision):?>
								<tr class="portlet-header">
									<td align="left" colspan="7"> <b>- <?= $comision['shortname']."</b> (Total: ".count($comision['alumnos']).")"; ?> - Instructor: <?= $comision['instructor']; ?></td>
								</tr>
								<?php //alan// ?>
								<?php $e=1; ?>
									<tr class="portlet-header">
										<td width="20px">Nº</td>
										<td>Alumno</td>
										<td>DNI</td>
										<td>Telefonos</td>
										<td>Email</td>
										<td>Inscripcion</td>
										<td>Detalle</td>
									</tr>
								<?php foreach($comision['alumnos'] as $alumno): ?>
									<tr class="ui-widget-content">
										<td width="20px"><?= $e; ?></td>
										<td><?= $alumno['alumno']; ?></td>
										<td><?= $alumno['username']; ?></td>
										<td><?= $alumno['phone1']." / ".$alumno['phone2']; ?> </td>
										<td><?= $alumno['email']; ?> </td>
										<td><?= date('d',$alumno['timestart']).'/'.ConvertMonth(date('M',$alumno['timestart'])).'/'.date('Y',$alumno['timestart']); ?> </td>
										<td><?= $alumno['detalle']; ?> </td>
									</tr>
									<?php $e++; ?>
								<?php endforeach;?>
								<?php //alan// ?>
								<tr><td colspan="7">&nbsp;</td></tr>
							<?php endforeach;?>
						</table>
					</td>
				</tr>
			</table>
		</div>
	<?php endif; ?>
</div>
