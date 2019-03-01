<!-- Barra de ruta y botones de accion -->
<div class="ui-widget noprint" align="left" style="float:left;">
	<span align="left">
		<h2 class="ui-widget">Deudores de Cuota por comisi&oacute;n</h2>
	</span>
</div>

<div class="ui-widget noprint" align="right">
	<span align="right">
		<form action="reportes.php?v=xls" method="post" id="deudores-form">   
			<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;">Imprimir</span>
			<span id="deudores" class="button-xls" style="font-size: 9px;">XLS</span>
				<input type="hidden" id="deudores-table-xls" name="table-xls" />  
				<input type="hidden" id="name-xls" name="name-xls" value="reporte.deudores" />  	
		</form>	
	</span>
</div>

<form class="form-view validate" action="reportes.php?v=deudores-comi" method="GET">
	<div class="column noprint" style="width:20%">
		<div class="portlet">
			<div class="portlet-header">Academias</div>
			<div class="portlet-content" style="overflow:auto;height:122px;">
				<ul class="noBullet">
					<?php foreach($academias_user as $academia_user): ?>
						<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]" title="<?= $academia_user['name']?>"><?= $academia_user['shortname']?></label></li>
					<?php endforeach; ?>	
				</ul>
			</div>
		</div>
	</div>
	<div class="column" style="width:80%">
		<div class="portlet">
			<div class="portlet-header">Filtros</div>
			<div class="portlet-content" style="overflow:auto;height:122px;">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead>
					</thead>
					<tbody>
						<tr>
							<td><b>Per&iacute;odo</b></td>
							<td>
								<select name="periodos"  style="width:100px;">
									<option value="0">Todos...</option>
									<?php foreach($periodos_user as $periodo): ?>
										<option value="<?= $periodo['periodo']; ?>" <?php if($periodo['periodo']==$periodo_sel) echo "selected"; ?>><?= $periodo['periodo']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><b>Curso</b></td>
							<td>
								<select name="modelo" style="width:500px;">
									<option value="0">Todos...</option>
									<?php foreach($modelos as $modelo): ?>
										<option value="<?= $modelo['id']; ?>" <?php if($modelo['id']==$modelo_sel) echo "selected"; ?>><?= $modelo['name']; ?></option>
									<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td><b>Cuota</b></td>
							<td>
								<select name="cuota" style="width:100px;">
									<?php for($i=1;$i<6;$i++): ?>
										<option value="<?= $i; ?>" <?php if($i==$cuota_sel) echo "selected"; ?>><?= $i; ?></option>
									<?php endfor; ?>
								</select>
							</td>
						</tr>
						<tr>
							<td colspan="2" align="center"><button type="submit" class="button noprint">Enviar</button></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<input type="hidden" name="stage" value="1" />
	</div>
</form>

<div class="column" style="width:100%;">
	<div class="portlet">
		<div class="portlet-header">Resultados</div>
		<div class="portlet-content" style="overflow:auto;">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
				</thead>
				<tbody>
					<?php if($deudores): ?>
						<?php foreach($deudores as $comision=>$datos): ?>
							<tr class="ui-widget-content" style="font-size:12px">
								<?php $title = "Fecha de Inicio: ".date("d-m-Y", $datos['startdate'])."\nFecha de Cierre: ".date("d-m-Y", $datos['enddate']); ?>
								<td style="border-top:1px solid #A4A4A4;border-left:1px solid #A4A4A4;border-right:1px solid #A4A4A4;" title="<?= $title; ?>">
									<a href="courses.php?v=view&id=<?= $comision;?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
										&nbsp;<b><?= $LMS->GetField('mdl_course','fullname',$comision); ?></b>
										<span style="font-size:9px;">(Activos: <?= $datos['activos']; ?>)</span>
										<span style="float:right;font-size:9px;">Fecha de Inicio: <?= date("d-m-Y", $datos['startdate']); ?></span>
								</td>
								<?php for($i=1;$i<=$cuota_sel;$i++): ?>
									<td rowspan="2" style="border:1px solid #A4A4A4;" align="center"><b>Cuota <?= $i; ?></b></td>
								<?php endfor; ?>
							</tr>
							<tr class="ui-widget-content">
								<td style="border-bottom:1px solid #A4A4A4;border-left:1px solid #A4A4A4;border-right:1px solid #A4A4A4;">
									Instructor: <?= $datos['instructor']; ?>
									<span style="float:right;font-size:9px;">Fecha de Cierre: <?= date("d-m-Y", $datos['enddate']); ?></span>
								</td>
							</tr>
							<?php foreach($datos['alumnos'] as $id => $alum): ?>
								<tr class="ui-widget-content">
									<td style="border-bottom:1px solid #D8D8D8;">
										<a href="contactos.php?v=view&id=<?= $id;?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
											&nbsp;<?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname)", $id); ?>
									</td>							
									<?php for($i=1;$i<=$cuota_sel;$i++): ?>
										<?php $total[$i] += $alum[$i]; ?>
										<?php if($alum[$i]>0): ?>
											<td style="border-bottom:1px solid #D8D8D8;" align="right">$ <?= number_format($alum[$i], 2, ',', '.'); ?></td>
										<?php else: ?>
											<td style="border-bottom:1px solid #D8D8D8;">&nbsp;</td>
										<?php endif; ?>
									<?php endfor; ?>
								</tr>
							<?php endforeach; ?>
							<?php if($H_USER->has_capability('reporte/deudores/completo')): ?>
								<tr class="ui-widget-content">
									<th style="border-bottom:2px solid #A4A4A4;" align="left"><b>Total</b></th>
									<?php foreach($total as $tot): ?>
										<th style="border-bottom:2px solid #A4A4A4;" align="right">$ <?= number_format($tot, 2, ',', '.'); ?></th>
									<?php endforeach; ?>
									<?php unset($total); ?>
								</tr>
							<?php endif; ?>
							<tr style="height:20px;"><td colspan="<?= $cuota_sel+1; ?>">&nbsp;</td></tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<!-- Export -->
<table id="deudores-export" class="ui-widget " style="display:none;">
	<thead>
	</thead>
	<tbody>
		<?php if($deudores): ?>
			<?php foreach($deudores as $comision=>$datos): ?>
				<tr class="ui-widget-content" style="font-size:12px">
					<td style="border-top:1px solid #A4A4A4;border-left:1px solid #A4A4A4;border-right:1px solid #A4A4A4;">
						&nbsp;<b><?= $LMS->GetField('mdl_course','fullname',$comision); ?></b>
						<span style="font-size:9px;">(Activos: <?= $datos['activos']; ?>)</span>
					</td>
					<?php for($i=1;$i<=$cuota_sel;$i++): ?>
						<td rowspan="4" style="border:1px solid #A4A4A4;" align="center"><b>Cuota <?= $i; ?></b></td>
					<?php endfor; ?>
				</tr>
				<tr class="ui-widget-content">
					<td style="border-left:1px solid #A4A4A4;border-right:1px solid #A4A4A4;">
						Instructor: <?= $datos['instructor']; ?>
					</td>
				</tr>
				<tr class="ui-widget-content">
					<td style="border-left:1px solid #A4A4A4;border-right:1px solid #A4A4A4;">
						Fecha de Inicio: <?= date("d-m-Y", $datos['startdate']); ?>
					</td>
				</tr>
				<tr class="ui-widget-content">
					<td style="border-bottom:1px solid #A4A4A4;border-left:1px solid #A4A4A4;border-right:1px solid #A4A4A4;">
						Fecha de Cierre: <?= date("d-m-Y", $datos['enddate']); ?>
					</td>
				</tr>				
				<?php foreach($datos['alumnos'] as $id => $alum): ?>
					<tr class="ui-widget-content">
						<td style="border-bottom:1px solid #D8D8D8;">
							&nbsp;<?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname)", $id); ?>
						</td>							
						<?php for($i=1;$i<=$cuota_sel;$i++): ?>
							<?php $total[$i] += $alum[$i]; ?>
							<?php if($alum[$i]>0): ?>
								<td style="border-bottom:1px solid #D8D8D8;" align="right">$ <?= number_format($alum[$i], 2, ',', '.'); ?></td>
							<?php else: ?>
								<td style="border-bottom:1px solid #D8D8D8;">&nbsp;</td>
							<?php endif; ?>
						<?php endfor; ?>
					</tr>
				<?php endforeach; ?>
				<?php if($H_USER->has_capability('reporte/deudores/completo')): ?>
					<tr class="ui-widget-content">
						<th style="border-bottom:2px solid #A4A4A4;" align="left"><b>Total</b></th>
						<?php foreach($total as $tot): ?>
							<th style="border-bottom:2px solid #A4A4A4;" align="right">$ <?= number_format($tot, 2, ',', '.'); ?></th>
						<?php endforeach; ?>
						<?php unset($total); ?>
					</tr>
				<?php endif; ?>
				<tr style="height:20px;"><td colspan="<?= $cuota_sel+1; ?>">&nbsp;</td></tr>
			<?php endforeach; ?>
		<?php endif; ?>
	</tbody>
	<tfoot>
	</tfoot>
</table>