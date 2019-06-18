
<div>
	<div class="ui-widget noprint" align="right">
		<span align="right">
			<form action="reportes.php?v=xls" method="post" id="deudores-form">
				<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
				<span id="deudores" class="button-xls" style="font-size: 9px;"><b>Descargar XLS</b></span>
					<input type="hidden" id="deudores-table-xls" name="table-xls" />
					<input type="hidden" id="name-xls" name="name-xls" value="reporte.deudores" />
				</form>
		</span>
	</div>
	<form action="reportes.php?v=deudores" method="GET">
		<div class="column-c" style="width:99%">
			<div class="portlet">
				<table class="ui-widget noprint" align="center" style="width:100%;">
					<thead>
						<th class="ui-widget-header" colspan="2">Filtros</th>
					</thead>
					<tbody>
						<tr>
							<td><b>Per&iacute;odo</b></td>
							<td class="ui-widget-content">
								<select name="periodos"  style="width:100px;">
									<option value="0">Todos...</option>
									<?php foreach($periodos_user as $periodo): ?>
										<option value="<?= $periodo; ?>" <?php if($periodo==$periodo_sel) echo "selected"; ?>><?= $periodo; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><b>Curso</b></td>
							<td class="ui-widget-content">
								<select name="modelo" style="width:500px;">
									<option value="0">Todos...</option>
									<?php foreach($modelos as $modelo): ?>
										<option value="<?= $modelo['id']; ?>" <?php if($modelo['id']==$modelo_sel) echo "selected"; ?>><?= $modelo['fullname']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
					<!-- alan 22/11/2012 -->
						<tr>
							<td><b>Caracter&iacute;stica</b></td>
							<td class="ui-widget-content">
								<select name="caracteristica" style="width:500px;">
									<option value="1">Todos...</option>
									<option value="2" <?php if($caracsel==2) echo "selected"; ?>>Intensivo</option>
									<option value="3" <?php if($caracsel==3) echo "selected"; ?>>Regular</option>
								</select>
							</td>
						</tr>
						<!-- alan 22/11/2012 -->
						<tr>
							<td><b>Cuota</b></td>
							<td class="ui-widget-content">
								<select name="cuota" style="width:100px;">
									<?php for($i=1;$i<6;$i++): ?>
										<option value="<?= $i; ?>" <?php if($i==$cuota_sel) echo "selected"; ?>><?= $i; ?></option>
									<?php endfor; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><b>Días</b></td>
							<td class="ui-widget-content">
								<input type="checkbox" name="dia[]" value="L" <?php if(in_array("L",$dia_sel)) echo "checked"; ?>/><label for="dia[]">Lunes</label>
								<input type="checkbox" name="dia[]" value="M" <?php if(in_array("M",$dia_sel)) echo "checked"; ?>/><label for="dia[]">Martes</label>
								<input type="checkbox" name="dia[]" value="W" <?php if(in_array("W",$dia_sel)) echo "checked"; ?>/><label for="dia[]">Miercoles</label>
								<input type="checkbox" name="dia[]" value="J" <?php if(in_array("J",$dia_sel)) echo "checked"; ?>/><label for="dia[]">Jueves</label>
								<input type="checkbox" name="dia[]" value="V" <?php if(in_array("V",$dia_sel)) echo "checked"; ?>/><label for="dia[]">Viernes</label>
								<input type="checkbox" name="dia[]" value="S" <?php if(in_array("S",$dia_sel)) echo "checked"; ?>/><label for="dia[]">Sabado</label>
							</td>
						</tr>
						<tr>
							<td><b>Otros filtros</b><span>&nbsp;&nbsp;( Marcar para incluir en los resultados) </span></td>
							<td class="ui-widget-content">
								<input type="checkbox" name="ctrlf" <?php if($ctrlf==1) echo "checked"; ?> /><label for="check1">CTRL-F</label>
								<input type="checkbox" name="instructor" <?php if($instructor==1) echo "checked"; ?> /><label for="check2">Instructores</label>
								<input type="checkbox" name="bridge" <?php if($bridge==1) echo "checked"; ?> /><label for="check3">Bridge Course</label>
							</td>
						</tr>
						<tr>
							<td><b>Seleccionar academias</b> <span class="button" onClick="$('#acaselec').slideToggle();">Mostrar</span></td>
							<td class="ui-widget-content">
								<div id="acaselec" style="display:none;align:center;">
								<div align="right" style="margin:5px;">
									<span class="press" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = true;});"><b>Todas</b></span> |
									<span class="press" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = false;});"><b>Ninguna</b></span>
								</div>
									<script>$(function(){	$('#acalist').makeacolumnlists({cols: 6, colWidth: "80px", equalHeight:false, startN: 1});});</script>
									<ul id="acalist" class="noBullet">
										<?php foreach($academias_user as $academia_user): ?>
											<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]"><?= $academia_user['shortname']?></label></li>
										<?php endforeach; ?>
									</ul>
								</div>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><button type="submit" class="button noprint">Consultar</button></td>
						</tr>
					</tbody>
				</table>
			<input type="hidden" name="v" value="deudores" />
			<input type="hidden" name="stage" value="1" />
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<tr class="ui-widget-header">
						<th rowspan="2" width="80px"></th>
						<th rowspan="2" width="320px">Comisi&oacute;n</th>
						<th rowspan="2" width="150px">Instructor/es</th>
						<th rowspan="2" width="45px">Inicio</th>
						<th rowspan="2" width="35px">Insc. Enrol.</th>
						<th rowspan="2" width="35px">Bajas</th>
						<th rowspan="2" width="35px">Cam. com.</th>
						<th rowspan="2" width="35px">Cap.</th>
						<th rowspan="2" width="35px">Vac.</th>
						<th colspan="5">Deuda</th>
					</tr>
					<tr class="ui-widget-header">
						<th>Cuota 1</th>
						<th>Cuota 2</th>
						<th>Cuota 3</th>
						<th>Cuota 4</th>
						<th>Cuota 5</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
					<?php if($deudores): ?>
						<?php foreach($deudores as $comision=>$cuotas): ?>
							<tr class="ui-widget-content">
						<?php if ($carrera!=$cuotas['from_courseid']):?>
						<?php $carrera=$cuotas['from_courseid'];?>
							<td width="80px" rowspan="<?=$carreras[$carrera];?>" align="center" class="ui-widget-content"><b><?= $LMS->GetField('mdl_course','shortname',$carrera); ?></b></td>
						<?php endif;?>
								<?php $title = "Fecha de Inicio: ".date("d-m-Y", $cuotas['startdate'])."\nFecha de Cierre: ".date("d-m-Y", $cuotas['enddate']); ?>
								<td width="300px" title="<?= $title; ?>" class="ui-widget-content">
									<a href="courses.php?v=view&id=<?= $comision;?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
									<?php if(strtotime(date("d-m-Y",$cuotas['startdate']))>=strtotime(date("d-m-Y",time()))): ?>
										&nbsp;<b><?= $LMS->GetField('crm.vw_course','fullname',$comision); ?></b>
									<?php else: ?>
										&nbsp;<?= $LMS->GetField('crm.vw_course','fullname',$comision); ?>
									<?php endif; ?>
								</td>
								<td width="150px" class="ui-widget-content"><?= $cuotas['instructor']; ?></td>
								<td width="50px" align="center" class="ui-widget-content"><?= date("d-m", $cuotas['startdate']); ?></td>
								<td align="right" class="ui-widget-content"><?= $cuotas['activos']; ?></td>
								<td align="right" class="ui-widget-content"><?= $cuotas['bajas']; ?></td>
								<td align="right" class="ui-widget-content"><?= $cuotas['cambio_comi']; ?></td>
								<td align="right" class="ui-widget-content"><?= $cuotas['capacidad'];?> <!--(<?= $cuotas['aula'];?>)--></td>
								<td align="right" class="ui-widget-content"><?= $cuotas['capacidad'] - $cuotas['activos']; ?></td>
								<?php $totales['activos'] += $cuotas['activos']; ?>
								<?php $totales['bajas'] += $cuotas['bajas']; ?>
								<?php $totales['cambio_comi'] += $cuotas['cambio_comi']; ?>
								<?php $totales['capacidad'] += $cuotas['capacidad']; ?>
								<?php $carre[$cuotas['from_courseid']]['activos'] += $cuotas['activos'];?>
								<?php $carre[$cuotas['from_courseid']]['bajas'] += $cuotas['bajas'];?>
								<?php $carre[$cuotas['from_courseid']]['cambio_comi'] += $cuotas['cambio_comi'];?>
								<?php $carre[$cuotas['from_courseid']]['capacidad'] += $cuotas['capacidad'];?>
								<?php for($i=1;$i<6;$i++){ ?>
								<?php
									$cuot=$i;
									if ($LMS->GetField('mdl_course','intensivo',$comision)==1){
										if ((substr($LMS->GetField('mdl_course','periodo',$comision),-1)=="2") AND (date("n",$LMS->GetField('mdl_course','startdate',$comision))==5 OR date("n",$LMS->GetField('mdl_course','startdate',$comision))==6)){
											$cuot= $i-2;
										}elseif ((substr($LMS->GetField('mdl_course','periodo',$comision),-1)=="3") AND (date("n",$LMS->GetField('mdl_course','startdate',$comision))==10 OR date("n",$LMS->GetField('mdl_course','startdate',$comision))==11)){
											$cuot= $i-2;
										}
									}
									if(in_array($cuotas['from_courseid'],array(100334,100071,100072,100073,100074))){
										# Cursos de una sola cuota
										$cuot=1;
									}elseif(in_array($cuotas['from_courseid'],array(100075,100144))){
										# Cursos de dos cuotas vmware y cicsco voice premier
										$cuot= $i-1;
									}
									// Solo para 1 comisión que empieza en abril y el plan se pasa de 5 a 4 cuotas.
									if(in_array($comision,array(100390,100459))){
										$cuot= $i-1;
									}								
								?>
									<td align="right" class="ui-widget-content">
										<a href="reportes.php?v=d_cuota&comision=<?= $comision;?>&cuota=<?= $cuot; ?>" target="_blank"><?= $cuotas[$i]['alumnos']; ?></a>
										<?php if ($cuotas[$i]['importe']>0){ ?>
											($ <?= number_format($cuotas[$i]['importe'], 0, ',', '.'); ?>)
											<?php $adeudadas[$i] += $cuotas[$i]['importe']; ?>
										<?php } ?>
											<?php $avalores_totales[$i] += $cuotas[$i]['valor_cuota']; ?>
											<?php $pagados_totales[$i] += $cuotas[$i]['valor_pagado']; ?>
									</td>
									<?php $totales[$i] += $cuotas[$i]['alumnos']; ?>
									<?php $carre[$cuotas['from_courseid']][$i]['importe'] += $cuotas[$i]['importe'];?>
									<?php $carre[$cuotas['from_courseid']][$i]['alumnos'] += $cuotas[$i]['alumnos'];?>
									<?php $carre[$cuotas['from_courseid']][$i]['valor_cuota'] += $cuotas[$i]['valor_cuota'];?>
									<?php $carre[$cuotas['from_courseid']][$i]['valor_pagado'] += $cuotas[$i]['valor_pagado'];?>
								<?php } ?>
							</tr>
						<?php endforeach; ?>
						<tr class="ui-widget-content">
							<td colspan="4" class="ui-widget-content"><b>Totales: </b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['activos']; ?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['bajas']; ?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['cambio_comi']; ?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['capacidad'];?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['capacidad'] - $totales['activos']; ?></b></td>
							<?php for($i=1;$i<6;$i++){ ?>
								<?php if ($totales[$i]>0){ ?>
									<td align="right" class="ui-widget-content"><b><?= $totales[$i]; ?></b></td>
								<?php }else{ ?>
									<td align="right" class="ui-widget-content">&nbsp;</td>
								<?php } ?>
							<?php } ?>
						</tr>
						<tr class="ui-widget-content">
							<td colspan="4" align="right">Inscriptos:</td>
							<td colspan="2" align="center"><b><?= ($totales['activos']+$totales['bajas']);?></b></td>
							<td colspan="4">&nbsp;</td>
						</tr><!--
						<tr class="ui-widget-content">
							<td colspan="10" class="ui-widget-content">Alumnos enrolados desde el LMS:
								<a href="reportes.php?v=enrol_lms&periodo=<?= $periodo_sel; ?>">
									<span style="font-size: 15px; font-weight: bold;color:red;"><?= $enrol_LMS; ?></span>
								</a>
							</td>
						</tr>-->
					<tr><td colspan="10">&nbsp;</td></tr>
				</tbody>
			</table>
			<?php if ($H_USER->has_capability('reporte/deudores/completo')):	?>
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<tr class="ui-widget-header">
						<th rowspan="2">Carrera</th>
						<th rowspan="2">Insc.</th>
						<th rowspan="2">Bajas</th>
						<th rowspan="2">Cam. com.</th>
						<th rowspan="2">Cap.</th>
						<th rowspan="2">Vac.</th>
						<th colspan="3">Cuota 1</th>
						<th colspan="3">Cuota 2</th>
						<th colspan="3">Cuota 3</th>
						<th colspan="3">Cuota 4</th>
						<th colspan="3">Cuota 5</th>
						<th rowspan="2">Deuda Total</th>
					</tr>
					<tr class="ui-widget-header">
						<th>A cobrar</th>
						<th>Cobrado</th>
						<th>Deuda</th>
						<th>A cobrar</th>
						<th>Cobrado</th>
						<th>Deuda</th>
						<th>A cobrar</th>
						<th>Cobrado</th>
						<th>Deuda</th>
						<th>A cobrar</th>
						<th>Cobrado</th>
						<th>Deuda</th>
						<th>A cobrar</th>
						<th>Cobrado</th>
						<th>Deuda</th>
					</tr>
				</thead>
				<tbody>
						<?php foreach($carre as $modelo=>$modelo_cuotas): ?>
							<?php unset($deuda_carre);?>
							<tr class="ui-widget-content">
								<td class="ui-widget-content">
										&nbsp;<?= $LMS->GetField('mdl_course','shortname',$modelo); ?>
								</td>
								<td align="right" class="ui-widget-content"><?= $modelo_cuotas['activos']; ?></td>
								<td align="right" class="ui-widget-content"><?= $modelo_cuotas['bajas']; ?></td>
								<td align="right" class="ui-widget-content"><?= $modelo_cuotas['cambio_comi']; ?></td>
								<td align="right" class="ui-widget-content"><?= $modelo_cuotas['capacidad'];?></td>
								<td align="right" class="ui-widget-content"><?= $modelo_cuotas['capacidad'] - $modelo_cuotas['activos']; ?></td>
								<?php for($i=1;$i<6;$i++){ ?>
									<td align="right" class="ui-widget-content" style="background: #F9EFEF !important;"><b>$<?= number_format($modelo_cuotas[$i]['valor_cuota'], 0, ',', '.'); ?></b></td>
									<td align="right" class="ui-widget-content" style="background: #F0F8F4 !important;"><b>$<?= number_format($modelo_cuotas[$i]['valor_pagado'], 0, ',', '.'); ?></b></td>
									<td align="right" class="ui-widget-content" style="background: #C3F7FC !important;"><b>$<?= number_format($modelo_cuotas[$i]['importe'], 0, ',', '.'); ?></b></td>
									<?php $deuda_carre += $modelo_cuotas[$i]['importe'];?>
									<?php $deuda_total += $modelo_cuotas[$i]['importe'];?>
								<?php } ?>
								<td align="right" class="ui-widget-content" style="background: #88E3FE !important;"><b>$<?= number_format($deuda_carre, 0, ',', '.'); ?></b></td>
							</tr>
						<?php endforeach; ?>
						<tr class="ui-widget-content">
							<td  class="ui-widget-content"><b>Totales: </b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['activos']; ?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['bajas']; ?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['cambio_comi']; ?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['capacidad'];?></b></td>
							<td align="right" class="ui-widget-content"><b><?= $totales['capacidad'] - $totales['activos']; ?></b></td>
							<?php for($i=1;$i<6;$i++){ ?>
									<td align="right" class="ui-widget-content" colspan="3"><b><?php /* $totales[$i];*/ ?></b></td>
							<?php } ?>
						</tr>
							<tr class="ui-widget-header">
								<th colspan="6"><b>Totales</b></th>
								<?php for($i=1;$i<6;$i++): ?>
									<th align="right" >$<?= number_format($avalores_totales[$i], 0, ',', '.'); ?></th>
									<th align="right" >$<?= number_format($pagados_totales[$i], 0, ',', '.'); ?></th>
									<th align="right" ><b>$<?= number_format($adeudadas[$i], 0, ',', '.'); ?> </th>
									<?php $total_facturado += $avalores_totales[$i];?>
									<?php $total_cobrado += $pagados_totales[$i];?>
								<?php endfor; ?>
									<th align="right" ><b><b>$<?= number_format($deuda_total, 0, ',', '.'); ?></b></th>
							</tr>
						<?php endif; ?>
				</tbody>
				<?php endif; ?>
				<tfoot>
					<tr><td colspan="20" align="center"><b>Total a cobrar:</b> $<?= number_format($total_facturado, 0, ',', '.'); ?>
					<br/> <b>Total cobrado:</b> $<?= number_format($total_cobrado, 0, ',', '.'); ?>
					<br/> <b>Total deuda:</b> $<?= number_format($deuda_total, 0, ',', '.'); ?> </td></tr>
				</tfoot>
			</table>
			</div>
		</div>
	</form>
	<!-- Export -->
	<table class="ui-widget" id="deudores-export" border="1" style="display: none;">
		<thead>
			<tr class="ui-widget-header">
				<th>Comisi&oacute;n</th>
				<th>Instructor/es</th>
				<th>Inicio</th>
				<th>Insc. Enrol.</th>
				<th>Bajas</th>
				<th>Cam. com.</th>
				<th>Cap.</th>
				<th>Vac.</th>			
				<th>Cuota 1</th>
				<th>Cuota 2</th>
				<th>Cuota 3</th>
				<th>Cuota 4</th>
				<th>Cuota 5</th>
			</tr>
		</thead>
		<tbody>
			<?php if($deudores): ?>
				<?php foreach($deudores as $comision=>$cuotas): ?>
					<tr class="ui-widget-content">
						<td><?= $LMS->GetField('mdl_course','fullname',$comision); ?></td>
						<td><?= $cuotas['instructor']; ?></td>
						<td><?= date("d-m", $cuotas['startdate']); ?></td>
						<td><?= $cuotas['activos']; ?></td>
						<td><?= $cuotas['bajas']; ?></td>
						<td><?= $cuotas['cambio_comi']; ?></td>
						<td><?= $cuotas['capacidad'];?> <!--(<?= $cuotas['aula'];?>)--></td>
						<td><?= $cuotas['capacidad'] - $cuotas['activos']; ?></td>					
						<?php for($i=1;$i<6;$i++): ?>
							<td align="right"><?= $cuotas[$i]['alumnos']; ?>
								<?php if ($cuotas[$i]['importe']>0): ?>
									($ <?= number_format($cuotas[$i]['importe'], 2, ',', '.'); ?>)
								<?php endif; ?>
							</td>
						<?php endfor; ?>
					</tr>
				<?php endforeach; ?>
				<tr class="ui-widget-content">
					<td colspan="3"><b>Totales: </b></td>
						<td align="right" class="ui-widget-content"><b><?= $totales['activos']; ?></b></td>
						<td align="right" class="ui-widget-content"><b><?= $totales['bajas']; ?></b></td>
						<td align="right" class="ui-widget-content"><b><?= $totales['cambio_comi']; ?></b></td>
						<td align="right" class="ui-widget-content"><b><?= $totales['capacidad'];?></b></td>
						<td align="right" class="ui-widget-content"><b><?= $totales['capacidad'] - $totales['activos']; ?></b></td>
						<?php for($i=1;$i<6;$i++): ?>
						<?php if ($totales[$i]>0): ?>
							<td align="right"><b><?= $totales[$i]; ?></b></td>
						<?php else: ?>
							<td align="right">&nbsp;</td>
						<?php endif; ?>
					<?php endfor; ?>
				</tr>
				<tr class="ui-widget-content">
					<td colspan="8">&nbsp;</td>
				</tr>

				<?php if ($H_USER->has_capability('reporte/deudores/completo')):	?>
					<tr class="ui-widget-content">
						<td colspan="8">Importe total adeudado</td>
						<?php for($i=1;$i<6;$i++): ?>
							<?php if ($adeudadas[$i]>0): ?>
								<td align="right">$ <?= number_format($adeudadas[$i], 2, ',', '.'); ?></td>
							<?php else: ?>
								<td align="right">&nbsp;</td>
							<?php endif; ?>
						<?php endfor; ?>
					</tr>
				<?php endif; ?>
			<?php endif; ?>
		</tbody>
		<tfoot>
		</tfoot>
	</table>
</div>