<?php
	if($rows):
		foreach($rows as $row): 
			$comisiones[$row['id']]['shortname'] = $row['shortname'];	
			$comisiones[$row['id']]['instructor'] = $row['instructor'];
			$comisiones[$row['id']]['alumnos'] = count($row['alumnos']);	
			$comisiones[$row['id']]['capacidad'] = $row['capacidad'];		
			$comisiones[$row['id']]['startdate'] = $row['startdate'];
			$comisiones[$row['id']]['enddate'] = $row['enddate'];
			if (strpos($row['shortname'],"-EMP") !== FALSE){
				$cursos[$row['model']]['plansocial'] += count($row['alumnos']);
			}else{
				$cursos[$row['model']]['privado'] += count($row['alumnos']);
			}
		endforeach;
	endif;	
	
	if($periodos_sel){
		foreach($periodos_sel as $periodo_sel):
			$periodos_str .= $periodo_sel.", ";
		endforeach;
		$periodos_str = substr($periodos_str, 0, strlen($periodos_str)-2);
	}
	if($acad_sel){
		foreach($acad_sel as $acad):
			$acad_str .= $LMS->GetField("mdl_proy_academy", "shortname", $acad).", ";
		endforeach;
		$acad_str = substr($acad_str, 0, strlen($acad_str)-2);
	}
?>

<!-- Barra de ruta y botones de accion -->
<div class="ui-widget noprint" align="right">
	<span align="right">
		<form action="reportes.php?v=xls" method="post" id="basico-form">   
			<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
			<span id="basico" class="button-xls" style="font-size: 9px;"><b>Descargar XLS</b></span>
			<input type="hidden" id="basico-table-xls" name="table-xls" />  
			<input type="hidden" id="name-xls" name="name-xls" value="reporte.inscriptos.basico" />  	
		</form>	
	</span>
</div>

<form class="form-view validate noprint" action="reportes.php?v=inscriptos&t=basico" method="POST" name="inscripcion">
	<div class="column" style="width:20%">
	
		<div class="portlet">
			<div class="portlet-header">Academias</div>
			<div class="portlet-content" style="overflow:auto;max-height:220px;">
				<ul class="noBullet">					
					<?php foreach($academias_user as $academia_user): ?>
						<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]" title="<?= $academia_user['name']?>"><?= $academia_user['shortname']?></label></li>
						<?php if(in_array($academia_user['id'],$acad_sel)) $graf_acad .= "&academy[]=".$academia_user['id'];?>
					<?php endforeach; ?>	
				</ul>
			</div>
		</div>
		<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = true;});">Todos</span> | 
		<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = false;});">Ninguno</span>
		<br/>
		<br/>
		<div class="portlet">
			<div class="portlet-header">Per&iacute;odos</div>
			<div class="portlet-content" style="overflow:auto;max-height:220px;">
				<ul class="noBullet">
					<?php foreach($periodos_user as $periodo_user): ?>
					<?php 
						if ($periodo_user['periodo'][2]==1){
							$tooltip="Enero Febrero 20".$periodo_user['periodo'][0].$periodo_user['periodo'][1];
						}elseif($periodo_user['periodo'][2]==2){
							$tooltip="Marzo Julio 20".$periodo_user['periodo'][0].$periodo_user['periodo'][1];
						}else{
							$tooltip="Agosto Diciembre 20".$periodo_user['periodo'][0].$periodo_user['periodo'][1];
						}
					?>					
						<li><input type="checkbox" name="periodos[]" value="<?=$periodo_user['periodo'];?>" <?php if(in_array($periodo_user['periodo'],$periodos_sel)) echo "checked"; ?>/><label for="periodo[]"  title="<?=$tooltip;?>"><?=$periodo_user['periodo'];?></label></li>
						<?php if(in_array($periodo_user['periodo'],$periodos_sel)) $graf_periodo .= "&periodo[]=".$periodo_user['periodo'];?>
					<?php endforeach; ?>	
				</ul>
			</div>
		</div>
		<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'periodos[]\']').each( function() {	this.checked = true;});">Todos</span> | 
		<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'periodos[]\']').each( function() {	this.checked = false;});">Ninguno</span>
		<br/>
		<br/>				
		<div class="portlet">
				<div class="portlet-header">Carreras</div>
				<div class="portlet-content" style="overflow:auto;max-height:220px;">	
					<ul class="noBullet">						
					</ul>	
					<ul class="noBullet">
						<?php foreach($carrerasb as $carrer): ?>								
							<li><input type="checkbox" name="carrera[]" class="carrera" value="<?=$carrer['idcr'];?>" <?php if(in_array($carrer['idcr'],$carsel)) echo "checked"; ?>/><label for="carrera[]"><?=$carrer['shortname'];?></label></li>
						<?php endforeach; ?>				
					</ul>		
				</div>
				<div class="portlet-content" style="overflow:auto;max-height:190px;">	
				</div>	
		</div>		
		<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'carrera[]\']').each( function() {	this.checked = true;});">Todos</span> | 
		<span class="button" style="height: 25px; font-size:11px; width:43%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'carrera[]\']').each( function() {	this.checked = false;});">Ninguno</span>
		<br/>
		<br/>
		<input type="submit" class="button" style=" width:100%; font-weight: bold; height:25px;" value="Ver" />

	</div>
</form>

<div class="column" style="width:80%">
	<div class="ui-widget" style="font-size:13px; font-weight:bold;">Academias: <?= $acad_str; ?></div>
	<div class="ui-widget" style="font-size:13px; font-weight:bold;">Per&iacute;odos: <?= $periodos_str; ?></div>
	<div class="portlet">
		<div class="portlet-header">Gr&aacute;fico de inscriptos</div>
		<div class="portlet-content"  id="graf_inscriptos">

		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Inscriptos por carrera</div>
		<!--<div class="portlet-content"  id="table_inscriptos">

		</div>-->
		<div class="portlet-content">
			<table class="ui-widget listado" align="center" style="width:100%;">
				<thead>
					<tr style="height: 20px;" class="ui-widget-header">
						<th>Curso</th>
						<th>Planes Sociales</th>						
						<th>Privados</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
					<?php if($cursos):?>
						<?php foreach($cursos as $curso => $tot_cur):?>
							<tr style="height: 20px;">
								<td class="press" ondblclick="">
									<a href="#" target="_blank">	
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><?= $curso;?>
								</td>								
								<td align="right"><?= $tot_cur['plansocial']; ?></td>
								<td align="right"><?= $tot_cur['privado']; ?></td>
								<?php $tot_plansocial += $tot_cur['plansocial']; ?>
								<?php $tot_privado += $tot_cur['privado']; ?>
								<?php $tot_curs += $tot_cur['plansocial']+$tot_cur['privado']; ?>
							</tr>
						<?php endforeach;?>
					<?php endif;?>	
					<tr class="ui-widget-content" style="height: 20px;">
						<td align="left"><b>Total</b></td>
						<th align="right"><b><?= $tot_plansocial;?></b></th>
						<th align="right"><b><?= $tot_privado;?></b></th>
					</tr>
					<tr class="ui-widget-content" style="height: 20px;">
						<td align="left"><b>Total General</b></td>
						<th align="right" colspan="2"><b><?= $tot_curs;?></b></th>
					</tr>
				</tbody>
			</table>		
		</div>
	</div>

<script type="text/javascript">
	$('#graf_inscriptos').load('academy.php?v=widget-graf_inscriptos<?= $graf_acad;?><?= $graf_periodo;?>');
	//$('#table_inscriptos').load('academy.php?v=widget-table_inscriptos<?= $graf_acad;?><?= $graf_periodo;?>');
</script>

	<div class="portlet">
		<div class="portlet-header">Inscriptos por comisi&oacute;n</div>
		<table style='font-family:Arial;font-size:12px;' align='center' class="ui-widget" width="100%">
			<tr class="ui-widget-header">
				<th>Comisi&oacute;n</th>
				<th>Instructor</th>
				<th>Inscriptos</th>
				<th>Vacantes</th>
			</tr>
			<?php if($comisiones): ?>
				<?php $total = 0; ?>
				<?php foreach($comisiones as $id => $datos):?>
					<?php $total += $datos['alumnos']; ?>
					<?php $title = "Fecha de Inicio: ".date("d-m-Y", $datos['startdate'])."\nFecha de Cierre: ".date("d-m-Y", $datos['enddate']); ?>
					<tr class="ui-widget-content" style="border-style:dashed; border-width:2px;" title="<?= $title; ?>">
						<td align="left">
							<a href="courses.php?v=view&id=<?= $id; ?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
								<?php if(strtotime(date("d-m-Y",$datos['startdate']))>=strtotime(date("d-m-Y",time()))): ?>
									<b><?= $datos['shortname']; ?></b>
								<?php else: ?>
									<?= $datos['shortname']; ?>								
								<?php endif; ?>
						</td>
						<td><?= $datos['instructor']; ?></td>
						<td align="right"><?= $datos['alumnos']; ?></td>
						<td align="right"><?= $datos['capacidad'] - $datos['alumnos']; ?></td>
					</tr>
				<?php endforeach;?>
				<tr class="ui-widget-content">
					<th align="left">&nbsp;</th>
					<th align="left">Total</th>
					<th align="right"><?= $total; ?></th>
					<th align="left">&nbsp;</th>
				</tr>
			<?php endif; ?>
		</table>
	</div>
</div>

<?php unset($tot_curs); ?>

<!-- Export -->
<table border="1" style="display:none;" id="basico-export" class="ui-widget">
	<tr>
		<td colspan="4"><div class="ui-widget" style="font-size:13px; font-weight:bold;">Academias: <?= $acad_str; ?></div></td>
	</tr>
	<tr>
		<td colspan="4"><div class="ui-widget" style="font-size:13px; font-weight:bold;">Per&iacute;odos: <?= $periodos_str; ?></div></td>
	</tr>
	<tr>	
		<td colspan="4"><div class="ui-widget" style="font-size:13px; font-weight:bold;">Por Carrera</div></td>
	</tr>
	<tr class="ui-widget-header">
		<th colspan="2">Curso</th>
		<th colspan="2">Alumnos</th>
	</tr>
	<?php if($cursos):?>
		<?php foreach($cursos as $curso => $tot_cur):?>
			<tr class="ui-widget-content">
				<td colspan="2"><?= $curso; ?>
				<td align="right" colspan="2"><?= $tot_cur; ?></td>
				<?php $tot_curs += $tot_cur; ?>
			</tr>
		<?php endforeach;?>
	<?php endif;?>	
	<tr class="ui-widget-content">
		<th align="left" colspan="2">Total</th>
		<th align="right" colspan="2"><?= $tot_curs; ?></th>
	</tr>
	<tr style="height:30px;">
		<td colspan="4">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="4"><div class="ui-widget" style="font-size:13px; font-weight:bold;">Por Comisi&oacute;n</div></td>
	</tr>
	<tr class="ui-widget-header">
		<th>Comisi&oacute;n</th>
		<th>Instructor</th>
		<th>Inscriptos</th>
		<th>Vacantes</th>
	</tr>
	<?php if($comisiones): ?>
		<?php $total = 0; ?>
		<?php foreach($comisiones as $id => $datos):?>
			<?php $total += $datos['alumnos']; ?>
			<tr class="ui-widget-content">
				<td align="left"><?= $datos['shortname']; ?></td>
				<td><?= $datos['instructor']; ?></td>
				<td align="right"><?= $datos['alumnos']; ?></td>
				<td align="right"><?= $datos['capacidad'] - $datos['alumnos']; ?></td>
			</tr>
		<?php endforeach;?>
		<tr class="ui-widget-content">
			<th align="left">&nbsp;</th>
			<th align="left">Total</th>
			<th align="right"><?= $total; ?></th>
			<th align="left">&nbsp;</th>
		</tr>
	<?php endif; ?>
</table>