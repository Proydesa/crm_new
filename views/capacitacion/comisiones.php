
<h2 class="ui-widget">Listado de comisiones de Capacitaci&oacute;n de Instructores</h2>

<form action="<?= $HULK->SELF?>" method="POST" name="filtros" style="margin:0; padding:0;">
	<div class="column" style="width:20%">
		<div class="portlet">
			<div class="portlet-header">Filtros de b&uacute;squeda</div>
			<div class="portlet-content" >
				<br/>
				<center>
					<input class="search" id="localsearch" type="search" autocomplete="off" size="30" maxlength="2048" name="q" title="Buscar en Hulk" value="<?= $q;?>"  spellcheck="false" />
				</center>
				<br/>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-header">Carreras</div>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<table class="ui-widget" width="100%">
					<?php foreach($carreras as $carrera): ?>
						<tr>
							<td title="<?= $carrera['fullname']; ?>">
								<input type="checkbox" name="carreras[]" value="<?=$carrera['id'];?>" <?php if(in_array($carrera['id'],$carreras_sel)) echo "checked"; ?>/><label for="carreras[]">&nbsp;<?=$carrera['shortname'];?></label>
							</td>
						</tr>					
					<?php endforeach; ?>	
				</table>
			</div>
		</div>		
		<div class="portlet">
			<div class="portlet-header">Per&iacute;odos</div>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<ul class="noBullet">
					<?php foreach($periodos as $periodo): ?>
						<li><input type="checkbox" name="periodos[]" value="<?=$periodo['periodo'];?>" <?php if(in_array($periodo['periodo'],$periodos_sel)) echo "checked"; ?>/><label for="periodo[]"><?=$periodo['periodo'];?></label></li>
					<?php endforeach; ?>	
				</ul>
			</div>
		</div>
		<div class="portlet">
			<div class="portlet-header">Tipo</div>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<ul class="noBullet">
					<li><input type="checkbox" name="ctrlf" value="Control F" <?php if($ctrlf == "ctrlf") echo "checked"; ?>/><label for="ctrlf">Control-F</label></li>
					<li><input type="checkbox" name="bridge" value="Bridge" <?php if($bridge == "bridge") echo "checked"; ?>/><label for="bridge">Bridges</label></li>
				</ul>
			</div>
		</div>
		<button type="submit" class="button">Ver</button>
	</div>
</form>

<div class="column" style="width:80%">
	<div class="portlet">
		<div class="portlet-header">Comisiones</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Comisi&oacute;n</th>
						<th>Instructor(es)</th>
						<th>Fecha Inicio</th>
						<th>Fecha Fin</th>
						<th>Curso</th>
						<th>Per&iacute;odo</th>
						<th>Cursantes</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach($rows as $row):?>
						<tr class="ui-widget-content" style="height: 20px;" ondblclick="window.location.href='courses.php?v=view&id=<?= $row['id'];?>';">
							<td>
								<a href="courses.php?v=view&id=<?= $row['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<?= $row['comi'];?>
							</td>							
							<td><?= $row['instructor'];?></td>
							<td align="center"><?= date("d-m-Y", $row['startdate']); ?></td>
							<td align="center"><?= date("d-m-Y", $row['enddate']); ?></td>
							<td><?= $row['course'];?></td>
							<td align="center"><?= $row['periodo'];?></td>
							<td align="right"><?= $row['cursantes']; ?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
				<tfoot>
					<tr style="height: 16px;">
						<th colspan="6" align="right">
							<?php echo $links_pages;?>
						</th>
					</tr>
				</tfoot>		
			</table>
		</div>
	</div>
</div>
