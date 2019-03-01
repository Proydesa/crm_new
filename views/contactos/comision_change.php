<script>
	function calculateSum() {
		var add = 0;
		$('.sumar').each(function() {
			add += Number($(this).val());
		});
		$('#total').attr('value', add);
		//	$('#pago').rules('remove', "range");
		//	$('#pago').rules('add', { range: [$('#valor_minimo').val(), $('#total').val()] });
	};

	function checkVacante(vacantes){
		if(vacantes <= 0){
			alert("Atención! En esta comisión no hay vacantes disponibles. \n\nEsto no impide continuar con la inscripción.");
		}
	};

	function checkIntensivo(intensivo){
		if(intensivo == 1){
			intensivo_str = "&intensivo=1";
		}else{
			intensivo_str = "";
		}
		$('#cuotas').load('ajax.php?f=Cuotas'+intensivo_str+'&curso='+ $('#curso').val());
		setTimeout("calculateSum()", 200);
	};
	function changeComision(){
		$('#cuotas').empty();
		$('#libros').load('ajax.php?f=Libros&curso='+ $('#curso').val(),function() { calculateSum();});
		calculateSum();
		$('#valor_minimo').val(<?= $HULK->minimo_cuota ?>);
	}

	$(document).ready(function() {
		$('#carid').load('ajax.php?f=carrerasList&academy=<?= $LMS->GetField('mdl_course','academyid',$comi_old); ?>', function() {
			$('#carid').val(<?= $LMS->GetField('mdl_course','convenioid',$comi_old); ?>);
			$('#curso').load('ajax.php?f=ModelList&carrera='+$('#carid').val()+'&periodo=<?= $LMS->GetField('mdl_course','periodo',$comi_old); ?>&academy=<?= $LMS->GetField('mdl_course','academyid',$comi_old); ?>', function() {
				$('#curso').val(<?= $LMS->GetField('mdl_course','from_courseid',$comi_old); ?>);
				$('#comision').empty().load('ajax.php?f=ComiList&userid=<?= $row['id'] ?>&periodo=<?= $LMS->GetField('mdl_course','periodo',$comi_old); ?>&academy=<?= $LMS->GetField('mdl_course','academyid',$comi_old); ?>&curso=' + $('#curso').val());
			});
		});


		$('#curso').change(function(){
			$('#comision').empty().load('ajax.php?f=ComiList&userid=<?= $row['id'] ?>&periodo=<?= $LMS->GetField('mdl_course','periodo',$comi_old); ?>&academy=<?= $LMS->GetField('mdl_course','academyid',$comi_old); ?>&curso=' + $('#curso').val());
			changeComision();
		});

		$('#carid').change(function(){
			$('#curso').load('ajax.php?f=ModelList&carrera='+$('#carid').val()+'&periodo=<?= $LMS->GetField('mdl_course','periodo',$comi_old); ?>&academy=<?= $LMS->GetField('mdl_course','academyid',$comi_old); ?>');
			$('#comision').empty();
			changeComision();
		});

	});

</script>

<div class="column-c" style="width:80%">
	<div class="portlet ">
		<div class="portlet-header">Datos</div>
		<div class="portlet-content">
		<form class="form-view validate" action="contactos.php?v=comision_change&stage=1" method="post" name="inscripcion" class="ui-widget">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody class="ui-widget-content">
						<tr style="height: 30px;" class="radio" style="float:right;">
							<td class="ui-widget-content">
								<b>Tipo de cambio:</b>
							</td>
							<td class="ui-widget-content">
								<select name="opcion">
									<option value="I-P"> Poner en [I]ncompleto y pasar pagos</option>
									<option value="D-P" selected> Desenrolar y pasar pagos</option>
									<!-- <option value="D-N"> Desenrolar y pasar notas</option>
									<option value="I"> Poner en [I]ncompleto</option> -->
								</select>
							</td>
							<td colspan="2" class="ui-widget-content">
								<b>Comisión actual:</b> <?= $LMS->GetField('mdl_course','shortname',$comi_old); ?> (<?= $LMS->GetField('mdl_course','shortname',$cuotas_actual['id']); ?>)
							</td>
						</tr>
						<tr>
							<td colspan="2" style="vertical-align:top;">
								<table class="ui-widget" align="center" style="width:100%;">
									<thead class="ui-widget-header">
										<tr>
											<th colspan="2" class="ui-widget-header">Curso</th>
										</tr>
									</thead>
									<tbody class="ui-widget-content">
										<tr style="height: 25px;">
											<td><b><label for="carrera">Carrera: </label></b></td>
											<td>
												<div class="portlet-content" id="carreraid">
													<select name="carid" id="carid">
													</select>
												</div>
											</td>
										</tr>
										<tr style="height: 30px;">
											<td><b>Curso:</b></td>
											<td>
												<select name="curso" id="curso" class="required">
												</select>
											</td>
										</tr>
										<tr style="height: 30px;">
											<td colspan="2" >
												<b>Comisión a cursar:</b>
											<div name="comision" id="comision" style="overflow: auto; max-height: 250px;">
											</div>
											</td>
										</tr>
										<tr style="height: 30px;">
											<td><b>Libro</b></td>
											<td><div name="libros" id="libros"></div></td>
										</tr>
									</tbody>
								</table>
							</td>
							<td colspan="2" style="vertical-align:top;">
								<?php if ($cuotas): ?>
									<table class="ui-widget" align="center" style="width:100%;">
										<thead class="ui-widget-header">
											<tr><th colspan="8">Historial Cuotas</th></tr>
											<tr>
												<th>Curso</th>
												<th>Cuota 1</th>
												<th>Cuota 2</th>
												<th>Cuota 3</th>
												<th>Cuota 4</th>
												<th>Cuota 5</th>
												<th>Libro</th>
												<th>Per&iacute;odo</th>
											</tr>
										</thead>
										<tbody class="ui-widget-content">
											<?php foreach($cuotas as $cuota):?>
												<tr style="height: 20px;">
													<td class="ui-widget-content"><b><?= $LMS->GetField('mdl_course','shortname',$cuota['id']); ?><b></td>
													<?php for($x=1;$x<6;$x++):?>
														<?php if($cuota['cuota'.$x]=='0'):?>
															<td class="ui-widget-content ui-state-highlight style" align="center"><b>OK</b></td>
														<?php elseif($cuota['cuota'.$x]==0):?>
															<td class="ui-widget-content"></td>
														<?php else:?>
															<td class="ui-widget-content ui-state-error style" align="center"><?= $cuota['cuota'.$x]; ?></td>
														<?php endif;?>
													<?php endfor;?>
													<?php if($cuota['cuota0']=='0'):?>
														<td class="ui-widget-content ui-state-highlight style" align="center"><b>OK</b></td>
													<?php else:?>
														<td class="ui-widget-content" align="center"><?= $cuota['cuota0']; ?></td>
													<?php endif;?>
													<td class="ui-widget-content" align="center"><?= $cuota['periodo']; ?></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								<?php endif; ?>
								<?php if($cursos):?>
									<table class="ui-widget" align="center" style="width:100%;">
										<thead class="ui-widget-header">
											<tr><th colspan="10">Historial Cursos</th></tr>
											<tr>
												<th>Curso</th>
												<th>Comisión</th>
												<th>Instructor</th>
												<th>Final</th>
												<th>Status</th>
											</tr>
										</thead>
										<tbody class="ui-widget-content">
											<?php foreach($cursos as $curso):?>
												<tr style="height: 20px;">
													<td class="ui-widget-content"><?= $curso['Course']; ?></td>
													<td class="ui-widget-content"><?= $curso['Comision']; ?></td>
													<td class="ui-widget-content"><?= $curso['Instructor']; ?></td>
													<td class="ui-widget-content"><?= number_format($curso['Final'],2); ?></td>
													<td class="ui-widget-content"><?= $grades[(int)$curso['Status']];?></td>
												</tr>
											<?php endforeach;?>
										</tbody>
									</table>
								<?php endif;?>
							<td>
						</tr>

						<tr><th colspan="4" class="ui-widget-header">Cuotas y Forma de Pago</th></tr>
						<tr>
							<td colspan="2" align="center"><b>Curso Nuevo</b></td>
							<td colspan="2" align="center"><b>Curso Actual</b></td>
						</tr>
						<tr style="height: 30px;">
							<td align="right"><b>Plan de cuotas:</b></td>
							<td align="center">
								<table class="ui-widget" align="center" style="width:100%;">
									<tbody class="ui-widget-content">
										<tr style="height: 20px;">
											<?php for($x=1;$x<6;$x++):?>
												<td class="ui-widget-content ui-state-error style" align="center"><?= $cuotas_actual['cuota'.$x]; ?></td>
											<?php endfor;?>
										</tr>
									</tbody>
								</table>
							</td>
							<td align="right"><b>Cuotas:</b></td>
							<td align="left">
								<table class="ui-widget" align="center" style="width:100%;">
									<thead class="ui-widget-header">
										<th>Cuota 1</th>
										<th>Cuota 2</th>
										<th>Cuota 3</th>
										<th>Cuota 4</th>
										<th>Cuota 5</th>
										<th>Libro</th>
									</thead>
									<tbody class="ui-widget-content">
										<tr style="height: 20px;">
											<?php for($x=1;$x<6;$x++):?>
												<td class="ui-widget-content ui-state-highlight style" align="center"><?= $cuotas_actual['cuotap'.$x]; ?></td>
												<?php $pago += $cuotas_actual['cuotap'.$x]; ?>
											<?php endfor;?>
											<td class="ui-widget-content ui-state-highlight style" align="center"><?= $cuotas_actual['cuotap0']; ?></td>
											<?php $pago += $cuotas_actual['cuotap0']; ?>
										</tr>
									</tbody>
								</table>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td align="right"><b>Cuotas</b></td>

							<td name="cuotas" id="cuotas" align="center"></td>
							<td align="right"><b>Pago total realizado:</b></td>
							<td align="left">
								$ <input type="text" name="pago" id="pago" value="<?= $pago;?>" style="width:100px; margin-top:4px;" readonly>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td></td>
							<td align="left">
								<a href="#" onClick="$('#cuotas').append('	$ <input type=text name=cuotas[] onKeyup=calculateSum(); class=sumar style=width:50px;>  ');" >Agregar</a>
								| <a href="#" onClick="$('.readonly').attr('readonly', false); $('#valor_minimo').val(0);" >Editar</a>
								| <a href="#" onClick="
													$('#cuotas').load('ajax.php?f=Cuotas&curso='+ $('#curso').val(),function() { calculateSum();});
													$('#libros').load('ajax.php?f=Libros&curso='+ $('#curso').val(),function() { calculateSum();});
													$('#valor_minimo').val(<?= $HULK->minimo_cuota ?>);" >Reset</a>
							</td>
							<td colspan="2" rowspan="2" align="center">
								<?php if($comprobantes): ?>
									<table width="50%" class="ui-widget">
										<tr class="ui-widget-header">
											<th>Comprobante</th>
											<th>Importe</th>
										<tr/>
										<?php foreach($comprobantes as $comp): ?>
											<tr class="ui-widget-content">
												<td><?= $comp['puntodeventa']."-".sprintf("%08d",$comp['numero']); ?></td>
												<td align="right">$ <?= number_format($comp['importe'],2,',','.'); ?></td>
											</tr>
											<input type="hidden" name="comprobantes[]" value="<?= $comp['id']; ?>" />
										<?php endforeach; ?>
									</table>
								<?php endif; ?>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td align="right"><b>Total del curso:</b></td>
							<td align="left">
								$ <input type='text' id='total' name='total' class='digits' value='' style='width:50px;' readonly>
							</td>
							<td colspan="3"></td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="4">&nbsp;</td>
						</tr>
				</tbody>
			</table>
			<div align="center">
				<input type="hidden" name="id" value="<?= $row['id'];?>" />
				<input type="hidden" name="from_course" id="from_course" value="<?= $from_course ?>" />
				<input type="hidden" name="comi_old" id="comi_old" value="<?= $comi_old ?>" />
				<input type="hidden" name="insc_id" value="<?= $insc_id; ?>" />
				<button type="button" class="add" onClick="$('.form-view').submit();" >Guardar datos</button>
			</div>
		</form>
		</div>
	</div>
</div>
