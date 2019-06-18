
<div>
	<div class="ui-widget noprint" align="right">
		<span align="right">
			<form action="reportes.php?v=xls" method="post" id="listado-form">
				<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir<b/></span>
				<span id="listado" class="button-xls" style="font-size: 9px;"><b>Descargar XLS<b/></span>
				<input type="hidden" id="listado-table-xls" name="table-xls" />
				<input type="hidden" id="name-xls" name="name-xls" value="reporte.listado" />
			</form>
		</span>
	</div>
	<div class="column" style="width:20%">
		<form name="form_fact" action="reportes.php?v=facturacion"  method="POST">
			<div class="portlet">
				<div class="portlet-header">Filtro</div>
				<div class="portlet-content" style="overflow: auto;">
					<table class="ui-widget" align="center">
						<tr>
							<td>Desde</td>
							<td>
								<?php $view->jquery_datepicker2("#startdate, #enddate");?>
								<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= $startdate; ?> " />
							</td>
						</tr>
						<tr>
							<td>Hasta</td>
							<td>
								<input id="enddate" style="width:90px;" name="enddate" type="text" align="center" value="<?= $enddate; ?> "  />
							</td>
						</tr>
					</table>
					<br />
				</div>
			</div>
			<button type="submit" class="button" style="width:191px;"><b>Consultar</b></button>
		</form>
	</div>

	<?php if($rows): ?>
		<div class="column ui-widget" style="width:80%" align="left">
			<div class="portlet">
				<div class="portlet-content" style="overflow:auto;max-height:380px;">
					<table id="listado" class="ui-widget" align="center" style="width:100%;">
						<thead>
						</thead>
						<tbody>
							<?php foreach($rows as $user => $dias): ?>
								<tr>
									<td colspan="5" style="font-size:14px;" class="ui-widget-header"><b><?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname)", $user); ?></b></td>
								</tr>
								<?php foreach($dias as $dia => $tipos): ?>
									<tr>
										<td colspan="5" align="right" class="ui-widget-content"><b><?= $dia; ?></b></td>
									</tr>
									<?php foreach($tipos as $tipo => $comps): ?>
										<tr>
											<td colspan="5" class="ui-widget-content"><b><?= $HULK->tipos[$tipo]; ?></b></td>
										</tr>
										<?php foreach($comps as $comp): ?>
											<?php
												if($comp['anulada']!=1){
													if($comp['tipo']==3){
														$tot[$user] -= $comp['importe'];
														$tot[$dia] -= $comp['importe'];
														$tot[$tipo] -= $comp['importe'];
														$totales[$tipo] -= $comp['importe'];
														$tot_per[$comp['periodo']] -= $comp['importe'];
													}else{
														$tot[$user] += $comp['importe'];
														$tot[$dia] += $comp['importe'];
														$tot[$tipo] += $comp['importe'];
														$totales[$tipo] += $comp['importe'];
														$tot_per[$comp['periodo']] += $comp['importe'];
													}
													$num_comp[$comp['tipo']]++;
												}
												if($comp['anulada']==1){
													$style = "text-decoration:line-through;";
												}else{
													$style="";
												}
												if($comp['pendiente']==1){
													$style2 = "font-style:italic;font-weight:bold;";
													$title = "Comprobante Pendiente de cobro";
												}else{
													$style2 ="";
													$title = "";
												}
												if($comp['tipo'] == 3){
													$signo = "-";
												}else{
													$signo = "";
												}
											?>
											<tr style="<?= $style.$style2; ?>" title="<?= $title; ?>" class="ui-widget-content">
												<td class="ui-widget-content"><?= $comp['puntodeventa']."-".sprintf("%08d",$comp['numero']);?></td>
												<td class="ui-widget-content"><?= $HULK->tipos[$comp['tipo']]; ?></td>
												<td class="ui-widget-content">
													<?= $HULK->conceptos[$comp['concepto']]; ?>
													<?php if($comp['concepto']==2) echo "(Nro.: ".$comp['nrocheque'].")"; ?>
												</td>
												<!--<td align="center"><?= date('d/m/Y', $comp['date']); ?></td>-->
												<td class="ui-widget-content"><?= $comp['detalle']; ?></td>
												<td align="right" class="ui-widget-content">$ <?= $signo; ?> <?= number_format($comp['importe'], 2, ',', '.'); ?></td>
											</tr>
										<?php endforeach; ?>
										<tr class="ui-widget-content">
											<td class="ui-widget-content" colspan="4" align="right"><b>Total <?= $HULK->tipos[$tipo]; ?></b></td>
											<td class="ui-widget-content" align="right"><b>$ <?= number_format($tot[$tipo], 2, ',', '.'); ?></b></td>
										</tr>
										<?php unset($tot[$tipo]); ?>
									<?php endforeach; ?>
									<tr class="ui-widget-content">
										<td class="ui-widget-content" colspan="4"  align="right"><b>Total <?= $dia; ?></b></td>
										<td class="ui-widget-content" align="right"><b>$ <?= number_format($tot[$dia], 2, ',', '.'); ?></b></td>
									</tr>
									<tr style="height:5px;" class="ui-widget-content"><td class="ui-widget-content" colspan="5">&nbsp;</td></tr>
									<?php unset($tot[$dia]); ?>
								<?php endforeach; ?>
								<tr style="font-size:13px;" class="ui-widget-content">
									<td class="ui-widget-content" colspan="4" align="right"><b>Total <?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname)", $user); ?></b></td>
									<td class="ui-widget-content" align="right"><b>$ <?= number_format($tot[$user], 2, ',', '.'); ?></b></td>
								</tr>
								<tr class="ui-widget-content" style="height:25px;"><td colspan="5">&nbsp;</td></tr>
							<?php endforeach; ?>
						</tbody>
						<tfoot>
							<?php foreach($totales as $tipo => $total): ?>
								<?php $total_f += $total; ?>
								<tr>
									<td align="right" colspan="4">Total <b><?= $HULK->tipos[$tipo]; ?></b> entre <b><?= $startdate; ?></b> y <b><?= $enddate; ?>:</b></td>
									<td align="right"><b>$ <?= $signo; ?> <?= number_format($total, 2, ',', '.'); ?></b> (<?= $num_comp[$tipo]; ?>)</td>
								</tr>
							<?php endforeach; ?>
							<tr><td colspan="5">&nbsp;</td></tr>
							<?php foreach($tot_per as $periodo => $total): ?>
								<tr>
									<td align="right" colspan="4">Total per&iacute;odo <b><?= $periodo; ?></b></td>
									<td align="right"><b>$ <?= number_format($total, 2, ',', '.'); ?></b></td>
								</tr>
							<?php endforeach; ?>
							<tr><td colspan="5">&nbsp;</td></tr>
							<tr>
								<td align="right" colspan="4"><b>Total facturado entre <?= $startdate; ?> y <?= $enddate; ?>:</b></td>
								<td align="right"><b>$ <?= number_format($total_f, 2, ',', '.'); ?></b></td>
							</tr>
						</tfoot>
					</table>
				</div>
			</div>
		</div>
	<?php endif; ?>
</div>