
<div class="column-c" style="width:90%;margin:26px auto">

	<!--======== FILTROS ========-->
	<div class="column" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Filtros</div>
			<div class="portlet-content" >

				<div class="flex-container">

						<!--====== LEFT ======-->						
						<form action="<?= $HULK->SELF?>" method="post" class="flex-item" style="padding:16px;width:50%">
							<h3>Reporte A</h3>

							<input type="hidden" name="report" value="a" >
							<p style="font-size:8pt">Este reporte mostrará el listado de las empresas con la información de los alumnos registrados en las comisiones correspondientes al período seleccionado.</p>
							<hr>

							<!-- EMPRESAS -->
							<div style="background-color:rgba(255,255,255,0.50);padding:8px;margin:4px 0">
								<h4>Empresas</h4>
								<input id="fd_checkall" type="checkbox"> <label for="fd_checkall">Seleccionar Todas</label>
								<ul id="list_groups" class="noBullet" style="overflow:auto;max-height:80px;padding:0;border:1px solid #dfdfdf;padding:8px 6px">
									<?php foreach($groups as $group): ?>
									<li>
										<input id="fd_group_<?= $group['id'] ?>" type="checkbox" name="groups[]" value="<?= $group['id'] ?>" <?php if(in_array($group['id'],$group_sel)) echo "checked"; ?>>
										<label for="fd_group_<?= $group['id'] ?>"><?= $group['name'] ?></label>
									</li>
									<?php endforeach; ?>
								</ul>
							</div>

							<!-- PERIODOS -->
							<div style="background-color:rgba(255,255,255,0.50);padding:8px;margin:4px 0">
								<h4>Períodos</h4>
								<select name="period_a" id="period_a" style="padding:2px 4px;width:120px">
									<?php foreach($periods as $period): ?>
									<option value="<?= $period['periodo'] ?>" <?= isset($_POST['period_a']) ? $_POST['period_a'] == $period['periodo'] ? 'selected' : '' : '' ?> ><?= $period['periodo'] ?></option>
									<?php endforeach; ?>									
								</select>
							</div>

							<hr>
							<button type="submit" class="fg-button ui-state-default ui-corner-all">Buscar</button>
						</form>


						
						<div class="flex-item" style="background-color:#6e6e6e;width:1px"></div>

						<!--====== RIGHT ======-->
						<form action="<?= $HULK->SELF?>" method="post" class="flex-item" style="padding:16px;width:50%">

							<input type="hidden" name="report" value="b" >

							<h3>Reporte B</h3>
							<p style="font-size:8pt">En este reporte se mostrarán comparativamente las diferencias de los alumnos correspondiente a una empresa inscriptos en dos períodos diferentes.</p>
							<hr>
							<div class="flex-container">
								<div class="flex-item">
									<h4>Período 1</h4>
									<select name="period_from" id="period_from" style="padding:2px 4px;width:120px">
										<?php foreach($periods as $period): ?>
										<option value="<?= $period['periodo'] ?>" <?= isset($_POST['period_from']) ? $_POST['period_from'] == $period['periodo'] ? 'selected' : '' : '' ?> ><?= $period['periodo'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
								<div class="flex-item">
									<h4>Período 2</h4>
									<select name="period_to" id="period_to" style="padding:2px 4px;width:120px">
										<?php foreach($periods as $period): ?>
										<option value="<?= $period['periodo'] ?>" <?= isset($_POST['period_to']) ? $_POST['period_to'] == $period['periodo'] ? 'selected' : '' : '' ?> ><?= $period['periodo'] ?></option>
										<?php endforeach; ?>
									</select>
								</div>
							</div>

							<hr>

							<button type="submit" class="fg-button ui-state-default ui-corner-all">Buscar</button>
							
						</form>

					</div>
					<hr>

			</div>
		</div>
	</div>

	<div class="clearfix"></div>


	<!--================ Reporte A =================-->
	<?php if($_POST['report'] == 'a'): ?>
	<h4>Reporte A</h4>
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="panel">
			<div class="panel-header">
				<h2><a href="#">Empresa</a></h2>
				<p>Dirección | Tel</p>
			</div>
			<div class="panel-body">
				<div class="panel-item">
					<table>
						<thead class="ui-widget-header">
							<tr>
								<th class="td-std" colspan="5" ></th>
								<th class="td-std" colspan="2" >Clases</th>
								<th class="td-std" colspan="2" >Parciales</th>
							</tr>
							<tr>
								<th class="td-std"></th>
								<th class="td-std">Alumno</th>
								<th class="td-std">DNI</th>
								<th class="td-std">Carrera</th>
								<th class="td-std">Comisión</th>
								<th class="td-std">N°</th>
								<th class="td-std">Faltas</th>
								<th class="td-std">Rendidos</th>
								<th class="td-std">Por Rendir</th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="td-std" >2</td>
								<td class="td-std" ><a href="#">Nombre Alumno  <i class="fa fa-external-link"></i></a></td>
								<td class="td-std" >11111111</td>
								<td class="td-std" ><a href="#">CCNA 4 Connecting Networks</a></td>
								<td class="td-std" ><a href="#">AR-FPROY-CCNA4-163-JN.BIZ</a></td>
								<td class="td-std" >6</td>
								<td class="td-std" >1</td>
								<td class="td-std" >5</td>
								<td class="td-std" >1</td>
							</tr>
							<tr>
								<td class="td-std" >3</td>
								<td class="td-std" ><a href="#">Nombre Alumno  <i class="fa fa-external-link"></i></a></td>
								<td class="td-std" >11111111</td>
								<td class="td-std" ><a href="#">CCNA 4 Connecting Networks</a></td>
								<td class="td-std" ><a href="#">AR-FPROY-CCNA4-163-JN.BIZ</a></td>
								<td class="td-std" >6</td>
								<td class="td-std" >1</td>
								<td class="td-std" >5</td>
								<td class="td-std" >1</td>
							</tr>
							<tr>
								<td class="td-std" >1</td>
								<td class="td-std" ><a href="#">Nombre Alumno  <i class="fa fa-external-link"></i></a></td>
								<td class="td-std" >11111111</td>
								<td class="td-std" ><a href="#">CCNA 4 Connecting Networks</a></td>
								<td class="td-std" ><a href="#">AR-FPROY-CCNA4-163-JN.BIZ</a></td>
								<td class="td-std" >6</td>
								<td class="td-std" >1</td>
								<td class="td-std" >5</td>
								<td class="td-std" >1</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<?php else: ?>

	<!--================ Reporte B =================-->
	<h4>Reporte B</h4>
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="panel">
			<div class="panel-header">				
				<h2>Períodos 173 <i class="fa fa-exchange"></i> 172</h2>
			</div>
			<div class="panel-body">
				<div class="panel-item">
					<table>
						<thead class="ui-widget-header">
							<tr>
								<th colspan="3" ></th>
								<th colspan="3" >Cantidad de Alumnos</th>
							</tr>
							<tr>
								<th class="td-std" >Empresa</th>
								<th class="td-std" >Contacto RRHH</th>
								<th class="td-std" >Carrera / Comisión</th>
								<th class="td-std" >P. 173</th>								
								<th class="td-std" >P. 172</th>								
								<th class="td-std" >Dif.</th>								
							</tr>
						</thead>
						<tbody>
							<tr>
								<td class="td-std"><a href="#">Nombre de Empresa <i class="fa fa-external-link"></i></a></td>
								<td class="td-std">nombre apellido</td>
								<td class="td-std"><a href="#">CCNA 4 Connecting Networks</a><br /><a href="#">AR-FPROY-CCNA4-173-JN.BIZ</a></td>
								<td class="td-std">10</td>
								<td class="td-std">3</td>
								<td class="td-std bg-success">+7</td>
							</tr>
							<tr>
								<td class="td-std"><a href="#">Nombre de Empresa <i class="fa fa-external-link"></i></a></td>
								<td class="td-std">nombre apellido</td>
								<td class="td-std"><a href="#">CCNA 4 Connecting Networks </a><br /><a href="#">AR-FPROY-CCNA4-173-JN.BIZ</a></td>
								<td class="td-std">6</td>
								<td class="td-std">8</td>
								<td class="td-std bg-danger">-2</td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>

	<?php endif; ?>

</div>

<script>
$('#fd_checkall').change(function(){
	if($(this).attr('checked')){
		$('#list_groups input').attr('checked',true);
	}else{
		$('#list_groups input').attr('checked',false);
	}
});
</script>