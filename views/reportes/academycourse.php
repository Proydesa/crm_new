<?php
function getMeses($periodo){
	if ($periodo[2]==1){
		return "(Enero Febrero 20".$periodo[0].$periodo[1].")";
	}elseif($periodo[2]==2){
		return "(Marzo Julio 20".$periodo[0].$periodo[1].")";
	}else{
		return "(Agosto Diciembre 20".$periodo[0].$periodo[1].")";
	}	
}
?>


<style style="text/css">
  	.hoverTable{
		
	}
	.hoverTable td{ 
	}
	/* Define the default color for all the table rows */
	
	/* Define the hover highlight color for the table row */
    .hoverTable tr:hover {
          background-color: #ffff99;
    }
</style>
<script>
	$(document).ready(function(){

		$("#selecall").click(function(event){
			deseleccionarPaises();
			 if($("#selecall").is(':checked')) {
				$('.academy').each( function() {
					if($(this).attr('value')!=200){ 
					$(this).attr("checked","checked");
				}
				});
				mostrarTodosLi();

			}else{
				$('.academy').each( function() {
					$(this).attr("checked",false);
				});
				mostrarTodosLi();

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
					mostrarSoloSeleccionados();
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
				mostrarSoloSeleccionados();
			});
		<?php endforeach;?>

	});
function busquedaText(valor){
	deseleccionarPaises();
	mostrarTodosLi();
	$("#selecall").attr("checked","checked");
	$('.li_academy').each( function() {
		if ($(this).html().toLowerCase().indexOf(valor.toLowerCase())>= 0){
			$(this).attr("style","display:block");
		}else{
			$(this).attr("style","display:none");

		}
	});

}
function deseleccionarPaises(){
	$('.pais').each( function() {
					if ($(this).attr('id')!='selecall'){
					$(this).attr("checked",false);}
				});
}
function mostrarSoloSeleccionados(){
	$('.academy').each( function() {
						if ($(this).is(':checked')){
							$(".li_"+$(this).val()).attr("style","display:block");
						}else{
							$(".li_"+$(this).val()).attr("style","display:none");
						}
					});
}
function ocultarTodosLi(){
	$('.li_academy').each( function() {
						$(this).attr("style","display:none");
					});
}
function mostrarTodosLi(){
	$('.li_academy').each( function() {
						$(this).attr("style","display:block");
					});
}

function mostrarLi(pais){
	$('.li_'+pais).each( function() {
						$(this).attr("style","display:block");
					});
}

</script>
<div class="ui-widget noprint" align="right">
<table  align="right" class="noprint">
	<tr>
	<?php if ($H_USER->has_capability("reportes/academycourse/internacionales")):?>
		<td>
			<form action="reportes.php?v=xls" method="post" id="academ-form">
				<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
				<span id="academ" class="button-xls" style="font-size: 9px;"><b>XLS por Academia</b></span>
				<input type="hidden" id="academ-table-xls" name="table-xls" />
				<input type="hidden" id="name-xls" name="name-xls" value="reporte.alum_acad" />
			</form>
		</td>
	<?php endif;?>
	</tr>
</table>
</div>
<div id="esconder" class="noprint">
		<form action="reportes.php?v=academycourse" name="form" method="post">
			<div class="column" style="width:270px">
				<div class="portlet">
					<div class="portlet-header">Filtros</div>
					<div class="portlet-content" style="overflow:auto;max-height:270px;">
						<ul class="noBullet">
							<span>Periodo: </span>
							<select  name="periodos" id="periodos">
								<?php foreach($LMS->getPeriodos() as $periodo):?>
									<li><option value="<?= $periodo;?>" <?php if($periodo==$persel) echo 'selected="selected"'; ?>><?= $periodo.getMeses($periodo);?></option></li>
								<?php endforeach;?>
							</select>
						</ul>
						<ul class="noBullet">
						Filtrar por forma de pago del convenio:<br/>
						<?php foreach($LMS->get_formas_de_pago() as $formadepago):?>
							<li><input type="checkbox" name="forma_de_pago[]" value="<?= $formadepago;?>" <?php if(in_array($formadepago,$forma_de_pagosel)) echo "checked"; ?>><label for="<?= $formadepago;?>"><?= $HULK->formas_de_pago[$formadepago];?></label></li>
						<?php endforeach;?>
						</ul>
						<p>Ver:
						<select >
						<option <?php if($conbajassel) echo "selected"; ?> onclick="document.getElementById('conbajas').checked=true" title="Son todas las personas que se inscribieron a los diferentes cursos sin importar si despues solicitaron la baja y no lo terminaron">Inscriptos</option>
						<option <?php if(!$conbajassel) echo "selected"; ?> onclick="document.getElementById('conbajas').checked=false" title="Son todas las personas que se inscribieron a los diferentes cursos y abonaron todas las cuotas">Graduados</option>
						</select>
						</p>
						<p style="display:none">Este informe solo incluye los enrolados activos. Para incluir todos los inscriptos (bajas)
							<input type="checkbox"  name="conbajas" id="conbajas" value="1" <?php if($conbajassel) echo "checked"; ?>>
						</p>
					</div>
				</div>
			</div>
			<div class="column" style="width:685px">
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
						<div align="right"><input type="text" onkeyup="busquedaText(this.value);"></div>

						<ul class="noBullet"  style="overflow:auto;max-height:120px;">
							<?php foreach($LMS->getAcademias() as $acadlist): ?>
										<?php if (!$H_USER->has_capability("reportes/academycourse/internacionales") AND $acadlist['country']!="AR"){
												$estado='disabled="disabled"';
											}else{
												$estado='';
											}
										?>
								<?php	if ($acadlist['id']!=201){ ?>
								<li class="li_academy li_<?=$acadlist['country'];?> li_<?=$acadlist['id'];?>"><input type="checkbox" <?= $estado;?> name="academy[]" class="academy <?=$acadlist['country'];?>" value="<?=$acadlist['id'];?>" <?php if(in_array($acadlist['id'],$acadsel)) echo "checked"; ?>/><label for="academy[]"><?=$acadlist['name'];?></label></li>
							<?php }endforeach; ?>
						</ul>
					</div>
				</div>
			</div>
			<div class="column" style="width:180px">
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
<?php if(count($acadsel) >0):?>
<div class="column" style="width:100%">
	<div class="portlet">
		<div class="portlet-header">Academias y carreras</div>
		<div class="portlet-content">
			<table id="listado" class="ui-widget listado header-academycourse" style="table-layout:fixed; width:100%;" >
				<tr style="height: 120px;" class="ui-widget-header">
					<th align="left" colspan="2"></th>
					<th colspan="2" class="print"></th>					
						<?php foreach($carreras as $carrerita): ?>
							<th  style="height:120px;writing-mode: vertical-rl;text-orientation: mixed;" class="portlet-content "><?=$carrerita['shortname'];?></th>
						<?php endforeach; ?>
						<?php foreach($forma_de_pagosel as $formadepago):?>
							<th class="portlet-content" style="height:120px;writing-mode: vertical-rl;text-orientation: mixed;"><?= $HULK->formas_de_pago[$formadepago];?></th>
						<?php endforeach; ?>
				</tr>
			</table>
		</div>
		<div class="portlet-content academycourse">
			<table id="academ-export" class="ui-widget hoverTable" style="table-layout:fixed; width:99%;" >
				<tr class="noprint noprint nodisplay">
					<th colspan="2" class="noprint nodisplay" ></th>
						<?php foreach($carreras as $carrerita): ?>
							<th class="noprint nodisplay"  ><?= $carrerita['shortname']; ?></th>
						<?php endforeach; ?>
						<?php foreach($forma_de_pagosel as $formadepago):?>
							<th class="noprint nodisplay" ><?= $HULK->formas_de_pago[$formadepago];?></th>
						<?php endforeach; ?>
						<th width="13px" class="noprint nodisplay"></th>
				</tr>
				<?php foreach($result as $academyid=>$row): ?>
					<?php if(($resto%2)==0): $colores_pago['por_alumno']=""; else: $colores_pago['por_alumno']=""; endif;?>
					<?php if($country!=$row['country']): ?>
						<?php if( $pasoprimera==true): ?>
						<tr>
							<td colspan="2"><b>Total del pa&iacute;s:</b></td>
							<?php foreach($carreras as $carrera):?>
								<td align="right" style="border-color:lightlue; border-style:solid; border-width:1px;">
								<?php foreach($forma_de_pagosel as $formadepago):?>
									<b> <?= $sumcarreraacad[$country][$carrera['modelid']][$formadepago];?></b>
								<?php endforeach; ?>									</td>
							<?php endforeach; ?>
							<?php foreach($forma_de_pagosel as $formadepago):?>
								<td style="border-color:lightlue; border-style:solid; border-width:1px;" align="right"><b><?=$sumapais[$country][$formadepago];?></b></td>
							<?php endforeach;?>
						</tr>
						<?php endif;?>
						<tr>
							<td colspan="<?= (count($carreras)+count($forma_de_pagosel)+2);?>" align="center"><b><?= strtoupper($HULK->countrys[$row['country']]);?></b></td>
						</tr>
					<?php endif;?>
					<?php $country=$row['country']; $pasoprimera=true;?>
					<tr>
						<td class="noprint" colspan="2">
							<a href="academy.php?v=view&id=<?= $academyid;?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<a title="<?= $row['name'];?>">
								<?= $row['shortname']; ?>
							</a>
						</td>
						<!--<td>
							<span class="print"><a title="<?= $row['name'];?>">
								<?= $row['name']; ?>
							</a></span>
						</td>-->			
						<?php foreach($carreras as $carrera):?>
							<?php if($row['cursos'][$carrera['modelid']]>0): ?>
								<td style="background:<?= $colores_pago[$row['cursos'][$carrera['modelid']]['forma']];?>; border-style:solid; border-width:1px;" align="right"><?= $row['cursos'][$carrera['modelid']]['cant'];?></td>
								<?php $sumacad[$row['cursos'][$carrera['modelid']]['forma']]+=$row['cursos'][$carrera['modelid']]['cant']; ?>
								<?php $sumcarrera[$carrera['modelid']][$row['cursos'][$carrera['modelid']]['forma']]+=$row['cursos'][$carrera['modelid']]['cant']; ?>
								<?php if ($country!="AR"):?>
									<?php $sumcarreraLATAM[$carrera['modelid']][$row['cursos'][$carrera['modelid']]['forma']]+=$row['cursos'][$carrera['modelid']]['cant']; ?>
								<?php endif;?>
								<?php $sumcarreraacad[$country][$carrera['modelid']][$row['cursos'][$carrera['modelid']]['forma']]+=$row['cursos'][$carrera['modelid']]['cant']; ?>
							<?php else: ?>
									<td style="background:<?= $colores_pago['por_alumno'];?>; border-style:solid; border-width:1px;" align="right">&nbsp;</td>
							<?php endif; ?>
						<?php endforeach; ?>
						<?php foreach($forma_de_pagosel as $formadepago):?>
							<td style="background:<?= $colores_pago[$formadepago];?>; border-style:solid; border-width:1px;" align="right"><b><?= $sumacad[$formadepago]; ?></b></td>
							<?php $sumapais[$row['country']][$formadepago]+=$sumacad[$formadepago]; ?>
						<?php endforeach; ?>
						<?php unset($sumacad); ?>
					</tr>
					<?php $resto++;?>
				<?php endforeach; ?>
				<tr>
					<td colspan="2"><b>Total del pa&iacute;s:</b></td>
					<?php foreach($carreras as $carrera):?>
							<td align="right" style="border-color:lightlue; border-style:solid; border-width:1px;">
								<?php foreach($forma_de_pagosel as $formadepago):?>
									<b> <?= $sumcarreraacad[$country][$carrera['modelid']][$formadepago];?></b>
								<?php endforeach; ?>
							</td>
					<?php endforeach; ?>
					<?php foreach($forma_de_pagosel as $formadepago):?>
						<td style="border-color:lightlue; border-style:solid; border-width:1px;" align="right"><b><?=$sumapais[$country][$formadepago];?></b></td>
					<?php endforeach;?>
				</tr>
				<tr>
					<td colspan="<?= (count($carreras)+count($forma_de_pagosel)+2);?>" align="center"><b>Totales para LATAM</b></td>
				</tr>
				<?php foreach($forma_de_pagosel as $formadepago):?>
				<tr><td colspan="2"><b><?= $HULK->formas_de_pago[$formadepago];?></b></td>
					<?php foreach($carreras as $carrera): ?>
							<td align="right" style="background:<?= $colores_pago[$formadepago];?>; border-style:solid; border-width:1px;">
								<b> <?= $sumcarreraLATAM[$carrera['modelid']][$formadepago];?></b>
								<?php $sumatotalLATAM[$formadepago]+=$sumcarreraLATAM[$carrera['modelid']][$formadepago];?>
							</td>
					<?php endforeach; ?>
						<td style="background:<?= $colores_pago[$formadepago];?>; border-style:solid; border-width:1px;" align="right" colspan="<?=count($forma_de_pagosel);?>"><b><?= $sumatotalLATAM[$formadepago]; ?></b></td>
				</tr>
				<?php endforeach;?>
				<tr>
					<td colspan="<?= (count($carreras)+count($forma_de_pagosel)+2);?>" align="center"><b>Totales para Argentina + LATAM</b></td>
				</tr>
				<?php foreach($forma_de_pagosel as $formadepago):?>
				<tr><td colspan="2"><b><?= $HULK->formas_de_pago[$formadepago];?></b></td>
					<?php foreach($carreras as $carrera): ?>
							<td align="right" style="background:<?= $colores_pago[$formadepago];?>; border-style:solid; border-width:1px;">
								<b><?= $sumcarrera[$carrera['modelid']][$formadepago];?></b>
								<?php $sumatotal[$formadepago]+=$sumcarrera[$carrera['modelid']][$formadepago];?>
							</td>
					<?php endforeach; ?>
						<td style="background:<?= $colores_pago[$formadepago];?>; border-style:solid; border-width:1px;" align="right" colspan="<?=count($forma_de_pagosel);?>"><b><?= $sumatotal[$formadepago]; ?></b></td>
				</tr>
				<?php endforeach;?>
			</table>
		</div>
	</div>
</div>
<?php endif;?>
<!-- Export -->

	<table  id="academ-export" border=1 class="noprint" style="display:none;">

	</table>
