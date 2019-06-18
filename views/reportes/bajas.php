<script>
	$(document).ready(function(){
		$("#periodosall").click(function(event){
			 if($("#periodosall").is(':checked')) {
				$('.periodo').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.periodo').each( function() { $(this).attr("checked",false); });
			}
		});
		$("#carrerasall").click(function(event){
			 if($("#carrerasall").is(':checked')) {
				$('.carrera').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.carrera').each( function() { $(this).attr("checked",false); });
			}
		});
	});
</script>

<div class="column-c">
	<table align="right"><tr>
		<div class="ui-widget noprint" align="right">
			<td>
				<form action="reportes.php?v=xls" method="post" id="bajas-form">
					<span class="button-print noprint" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
					<span id="bajas" class="button-xls noprint" style="font-size: 9px;"><b>Descargar XLS</b></span>
					<input type="hidden" id="bajas-table-xls" name="table-xls" />
					<input type="hidden" id="name-xls" name="name-xls" value="reporte.bajas" />
				</form>
			</td>
		</div>
		</tr>
	</table>
	<h2 class="ui-widget">Reporte de Bajas</h2>
	<div class="column-c">
		<form action="reportes.php?v=bajas" method="POST" style="margin:0; padding:0;">
			<div class="portlet">
				<div class="portlet-header">Filtros del listado de bajas</div>
				<input type="hidden" name="v" value="bajas" />
				<div class="ui-widget-content">
					<div class="column" style="width:30%; margin:2px;">
					<b>Per&iacute;odos</b><input type="checkbox" id="periodosall" value="periodosall"/><label for="periodosall">Todas/Ninguna</label>
						<div class="portlet-content" style="overflow:auto;max-height:120px;">
							<ul class="noBullet">
								<?php foreach($periodos_user as $periodo): ?>
								<?php
									if ($periodo_user['periodo'][2]==1){ $tooltip="Enero Febrero 20".$periodo_user['periodo'][0].$periodo_user['periodo'][1];
									}elseif($periodo_user['periodo'][2]==2){ $tooltip="Marzo Julio 20".$periodo_user['periodo'][0].$periodo_user['periodo'][1];
									}else{ $tooltip="Agosto Diciembre 20".$periodo_user['periodo'][0].$periodo_user['periodo'][1];	}
								?>
									<li><input class="periodo" type="checkbox" name="periodos[]" value="<?= $periodo['periodo']; ?>" <?php if(in_array($periodo['periodo'],$periodos_sel)) echo "checked"; ?> /><label for="periodo[]" title="<?=$tooltip;?>"><?= $periodo['periodo']?></label></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="ui-widget-content">
					<div class="column" style="width:30%; margin:2px;">
					<b>Carreras</b><input type="checkbox" id="carrerasall" value="carrerasall"/><label for="carrerasall">Todas/Ninguna</label>
						<div class="portlet-content" style="overflow:auto;max-height:120px;">
							<ul class="noBullet">
								<?php foreach($carrerlist as $carrer): ?>
									<li><input type="checkbox" name="carrera[]" class="carrera" id="carrera" value="<?=$carrer['idcr'];?>" <?php if(in_array($carrer['idcr'],$carsel)) echo "checked"; ?>/><label for="carrera[]"><?=$carrer['shortname'];?></label></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
				</div>
				<div class="ui-widget-content">
					<div class="column" style="width:30%; margin:2px;">
						<b>Fecha</b>
						<div class="portlet-content">
							<table class="ui-widget" align="center">
								<tr>
									<td>Desde</td>
									<td><?php $view->jquery_datepicker2("#startdate, #enddate");?>
										<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= $startdate; ?> " />
									</td>
								</tr>
								<tr>
									<td>Hasta</td>
									<td>
										<input id="enddate" style="width:90px;" name="enddate" type="text" align="center" value="<?= $enddate; ?> " />
									</td>
								</tr>
							</table>
						</div>
					</div>
					<input type="submit" name="boton"  style="height: 30px; font-size:13px; width:100%; font-weight: bold;" class="button"  value="Ver reporte" />
				</div>
			</div>
		</form>
	</div>
	<div class="column-c">
		<div class="portlet">
			<div class="portlet-header">Alumnos dados de baja</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;" id="bajas-export">
					<thead>
						<tr class="ui-widget-header" style="height: 20px;">
							<th>Alumno</th>
							<th>Comisión</th>
							<th>Usuario</th>
							<th>Fecha</th>
							<th>Detalle</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($bajas as $baja): ?>
							<tr class="ui-widget-content" style="height: 20px;">
								<td class="ui-widget-content">
									<a href="contactos.php?v=view&id=<?= $baja['userid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname, ' - DNI: ', username)", $baja['userid']); ?>
								</td>
								<td class="ui-widget-content"><?= $LMS->GetField("mdl_course", "fullname", $baja['comisionid']); ?></td>
								<td class="ui-widget-content"><?= $LMS->GetField("mdl_user", "username", $baja['user']); ?></td>
								<td class="ui-widget-content"><?= date("d-m-Y", $baja['date']); ?></td>
								<td class="ui-widget-content"><?= $baja['detalle']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-header">Alumnos con bajas canceladas</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;">
					<thead>
						<tr class="ui-widget-header" style="height: 20px;">
						<th>Alumno</th>
						<th>Comisión</th>
						<th>Usuario</th>
						<th>Fecha</th>
						<th>Detalle</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($bajas_cancel as $baja): ?>
							<tr class="ui-widget-content" style="height: 20px;">
								<td class="ui-widget-content"><?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname, ' - DNI: ', username)", $baja['userid']); ?></td>
								<td class="ui-widget-content"><?= $LMS->GetField("mdl_course", "fullname", $baja['comisionid']); ?></td>
								<td class="ui-widget-content"><?= $LMS->GetField("mdl_user", "username", $baja['user_cancel']); ?></td>
								<td class="ui-widget-content"><?= date("d-m-Y", $baja['date_cancel']); ?></td>
								<td class="ui-widget-content"><?= $baja['detalle']; ?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>