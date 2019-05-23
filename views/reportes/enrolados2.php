<?php
	function obtenerNombre($nombre){
		if (strpos($nombre,"(")===false){
			return $nombre;}else{
		return substr($nombre,strpos($nombre,"(")+1,strlen($nombre)-strpos($nombre,"(")-2);
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
	.notfirst:hover {
		background-color: #ffff99 !important;

}
</style>
<script>
	$(document).ready(function(){

		$("#selecall").click(function(event){
			deseleccionarPaises();
			 if($("#selecall").is(':checked')) {
				$('.academy').each( function() {
					$(this).attr("checked","checked");
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
<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 510px'";
	}else{
		$menufixed = "";
	}
?>
<div<?= $menufixed ?>>
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
		<form action="reportes.php?v=enrolados2" name="form" method="post">
			<div class="column" style="width:200px">
				<div class="portlet">
					<div class="portlet-header">Filtros</div>
					<div class="portlet-content" style="overflow:auto;max-height:300px;">
						<ul class="noBullet">
							<li><span>Periodo 1: </span>
							<select  name="periodo1" id="periodo1">
								<?php foreach($LMS->getPeriodos() as $periodo):?>
									<li><option value="<?= $periodo;?>" <?php if($periodo==$persel1) echo 'selected="selected"'; ?>><?= $periodo;?></option></li>
								<?php endforeach;?>
							</select><br/></li>
							<li><span>Periodo 2: </span>
							<select  name="periodo2" id="periodo2">
								<li><option value="" <?php if($persel2=="") echo 'selected="selected"'; ?>></option></li>
								<?php foreach($LMS->getPeriodos() as $periodo):?>
									<li><option value="<?= $periodo;?>" <?php if($periodo==$persel2) echo 'selected="selected"'; ?>><?= $periodo;?></option></li>
								<?php endforeach;?>
							</select></li>
							<li><span>Periodo 3: </span>
							<select  name="periodo3" id="periodo3">
								<li><option value="" <?php if($persel3=="") echo 'selected="selected"'; ?>></option></li>
								<?php foreach($LMS->getPeriodos() as $periodo):?>
									<li><option value="<?= $periodo;?>" <?php if($periodo==$persel3) echo 'selected="selected"'; ?>><?= $periodo;?></option></li>
								<?php endforeach;?>
							</select></li>

						</ul>
						<ul class="noBullet">
						Filtrar por forma de pago del convenio:<br/>
						<?php foreach($LMS->get_formas_de_pago() as $formadepago):?>
							<li><input type="checkbox" name="forma_de_pago[]" value="<?= $formadepago;?>" <?php if(in_array($formadepago,$forma_de_pagosel)) echo "checked"; ?>><label for="<?= $formadepago;?>"><?= $HULK->formas_de_pago[$formadepago];?></label></li>
						<?php endforeach;?>
						</ul>
					</div>
				</div>
			</div>
			<div class="column" style="width:800px">
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
	<div class="column" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Enrolados</div>
			<table class="listado ui-widget" id="detalle-export" align="center" width="100%">
				<thead>
				<tr class="ui-widget-header">
					<th rowspan="2">Academia</th>
					<th rowspan="2">Carrera</th>
					<?php if($persel3>0): ?><th colspan="2">Insc. <?= $persel3;?></th><?php endif;?>
					<?php if($persel2>0): ?><th colspan="2">Insc. <?= $persel2;?></th><?php endif;?>
					<th colspan="2">Insc. <?= $persel1;?></th>
				</tr>
				<tr class="ui-widget-header">
	<?php if($persel3>0): ?>
					<th>Enrol.</th>
					<th>Bajas</th>
	<?php endif;?>
	<?php if($persel2>0): ?>
					<th>Enrol.</th>
					<th>Bajas</th>
	<?php endif;?>
					<th>Enrol.</th>
					<th>Bajas</th>
				</tr>
				</thead>
				<tbody class="">
			<?php $acad = $result[0]['Academia'];?>
			<?= $result[0]['Academia'];?>
			<?php foreach($result as $row):?>
				<?php if($acad != $row['Academia']):?>
					<tr  class="ui-widget-content" style="height: 20px;" bgcolor='#F0F0F0'>
						<td colspan='2' class="ui-widget-content "  align='right'><b>Total <?= obtenerNombre($acad);?>:</b></td>
						<?php if($persel3>0): ?>
						<td class="ui-widget-content ui-state-highlight" align='right'><b><?= $total_acad_e3;?></b></td>
						<td class="ui-widget-content ui-state-error" align='right'><b><?= $total_acad_b3;?></b></td>
					<?php endif;?>
						<?php if($persel2>0): ?>
						<td class="ui-widget-content ui-state-highlight" align='right'><b><?= $total_acad_e2;?></b></td>
						<td class="ui-widget-content ui-state-error" align='right'><b><?= $total_acad_b2;?></b></td>
					<?php endif;?>
						<td class="ui-widget-content ui-state-highlight" align='right'><b><?= $total_acad_e1;?></b></td>
						<td class="ui-widget-content ui-state-error" align='right'><b><?= $total_acad_b1;?></b></td>
					</tr>
					<?php $total_acad_e1=0; $total_acad_b1=0;$total_acad_e2=0;$total_acad_b2=0;$total_acad_e3=0;$total_acad_b3=0;?>
					<tr><td>&nbsp;</td></tr>
				<?php endif;?>

					<tr class="notfirst" style="height: 20px;">
						<td style="background:; border-style:solid; border-width:1px;" >
							<a href="academy.php?v=view&id=<?= $row['academyid'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<?= obtenerNombre($row['Academia']);?></td>
						<td style="background:; border-style:solid; border-width:1px;" ><?= $row['Course'];?></td>
						<?php if($persel3>0): ?>
						<td  class="ui-widget-content ui-state-highlight" align='right'><?= $row['enrol3'];?></td>
						<td  class="ui-widget-content ui-state-error" align='right'><?= $row['bajas3'];?></td>
					<?php endif;?>
						<?php if($persel2>0): ?>
						<td  class="ui-widget-content ui-state-highlight" align='right'><?= $row['enrol2'];?></td>
						<td  class="ui-widget-content ui-state-error" align='right'><?= $row['bajas2'];?></td>
					<?php endif;?>
						<td  class="ui-widget-content ui-state-highlight" align='right'><?= $row['enrol1'];?></td>
						<td  class="ui-widget-content ui-state-error" align='right'><?= $row['bajas1'];?></td>
					</tr>
					<?php if($persel3>0): ?>
					<?php $total_acad_e3 += $row['enrol3'];?>
					<?php $total_acad_b3 += $row['bajas3'];?>
				<?php endif;?>
					<?php if($persel2>0): ?>
					<?php $total_acad_e2 += $row['enrol2'];?>
					<?php $total_acad_b2 += $row['bajas2'];?>
				<?php endif;?>
					<?php $total_acad_e1 += $row['enrol1'];?>
					<?php $total_acad_b1 += $row['bajas1'];?>

					<?php $acad = $row['Academia'];?>
				<?php endforeach;?>
					<tr  class="ui-widget-content" style="height: 20px;" bgcolor='#F0F0F0'>
						<td colspan='2' class="ui-widget-content "  align='right'><b>Total <?= obtenerNombre($acad);?>:</b></td>
						<?php if($persel3>0): ?>
						<td class="ui-widget-content ui-state-highlight" align='right'><b><?= $total_acad_e3;?></b></td>
						<td class="ui-widget-content ui-state-error" align='right'><b><?= $total_acad_b3;?></b></td>
					<?php endif;?>
						<?php if($persel2>0): ?>
						<td class="ui-widget-content ui-state-highlight" align='right'><b><?= $total_acad_e2;?></b></td>
						<td class="ui-widget-content ui-state-error" align='right'><b><?= $total_acad_b2;?></b></td>
					<?php endif;?>
						<td class="ui-widget-content ui-state-highlight" align='right'><b><?= $total_acad_e1;?></b></td>
						<td class="ui-widget-content ui-state-error" align='right'><b><?= $total_acad_b1;?></b></td>
					</tr>
					<tr><td>&nbsp;</td></tr>
				</tbody>
				</table>
				<table class="listado ui-widget " id="detalle-export2" align="center" width="100%">
				<tr class="ui-widget-header"><td colspan='8' align='center'><b>Alumnos por carrera</b></td></tr>
				<tr class="ui-widget-header">
					<th rowspan="2" colspan="2">Carrera</th>
					<?php if($persel3>0): ?><th colspan="2">Insc. <?= $persel3;?></th><?php endif;?>
					<?php if($persel2>0): ?><th colspan="2">Insc. <?= $persel2;?></th><?php endif;?>
					<th colspan="2">Insc. <?= $persel1;?></th>
				</tr>
				<tr class="ui-widget-header">
					<?php if($persel3>0): ?>
									<th>Enrol.</th>
									<th>Bajas</th>
					<?php endif;?>
					<?php if($persel2>0): ?>
									<th>Enrol.</th>
									<th>Bajas</th>
					<?php endif;?>
					<th>Enrol.</th>
					<th>Bajas</th>
				</tr>
				<?php foreach($result2 as $row):?>
					<tr class="notfirst " style="height: 20px;">
						<td colspan='2' style="background:; border-style:solid; border-width:1px;" ><?= $row['Course'];?></td>
						<?php if($persel3>0): ?>
						<td  class="ui-widget-content ui-state-highlight" align='right'><?= $row['enrol3'];?></td>
						<td  class="ui-widget-content ui-state-error" align='right'><?= $row['bajas3'];?></td>
					<?php endif;?>
						<?php if($persel2>0): ?>
						<td  class="ui-widget-content ui-state-highlight" align='right'><?= $row['enrol2'];?></td>
						<td  class="ui-widget-content ui-state-error" align='right'><?= $row['bajas2'];?></td>
					<?php endif;?>
						<td  class="ui-widget-content ui-state-highlight" align='right'><?= $row['enrol1'];?></td>
						<td  class="ui-widget-content ui-state-error" align='right'><?= $row['bajas1'];?></td>
					</tr>
					<?php
						if($persel3>0):
							$total_alum_e3 += $row['enrol3'];
							$total_alum_b3 += $row['bajas3'];
						endif;
						if($persel2>0):
							$total_alum_e2 += $row['enrol2'];
							$total_alum_b2 += $row['bajas2'];
						endif;
						$total_alum_e1 += $row['enrol1'];
						$total_alum_b1 += $row['bajas1'];
					?>
				<?php endforeach;?>

				<tr><td colspan='2' class="ui-widget-content" align='right'><b>Total de alumnos</b></td>
					<?php if($persel3>0): ?>
					<td class="ui-widget-content ui-state-highlight"  align='right'><b><?= $total_alum_e3;?></b></td>
					<td class="ui-widget-content ui-state-error"  align='right'><b><?= $total_alum_b3;?></b></td>
					<?php endif;?>
					<?php if($persel2>0): ?>
					<td class="ui-widget-content ui-state-highlight"  align='right'><b><?= $total_alum_e2;?></b></td>
					<td class="ui-widget-content ui-state-error"  align='right'><b><?= $total_alum_b2;?></b></td>
					<?php endif;?>
					<td class="ui-widget-content ui-state-highlight"  align='right'><b><?= $total_alum_e1;?></b></td>
					<td class="ui-widget-content ui-state-error"  align='right'><b><?= $total_alum_b1;?></b></td>
				</tr>
	</tbody>
			</table>
		</div>
	</div>
	<div class="table-header-fixed column-c dp-none" style="width: 99%;" >
		<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
			<div class="portlet-content">
				<table id="table_fixed" class="ui-widget" align="center" style="width:100%;">
				</table>
			</div>
		</div>
	</div>
</div>
<script>

$('#detalle-export thead tr th').each(function() {
	$(this).attr("width",$(this).width())
});
$('#detalle-export2 thead tr th').each(function() {
	$(this).attr("width",$(this).width())
});
$(function(){
	var ResizeHeader = function(){
	$.each($('#listado thead:eq(1) > tr > th'),function(k,v){
		$('#table_fixed thead:eq(1) th:eq('+k+')').width($(this).width());
	});
}
var BuildHeader = function(){
	var head1 = $('#detalle-export thead:eq(0)').clone();
	var head2 = $('#detalle-export thead:eq(1)').clone();
	$('#table_fixed').append(head1);
	$('#table_fixed').append(head2);
	ResizeHeader();
}
	$(window).scroll(function(){
		if($(this).scrollTop() > $('#detalle-export').offset().top+10){
			$('.table-header-fixed').removeClass('dp-none');
		}else{
			$('.table-header-fixed').addClass('dp-none');
		}
	});
	$(window).resize(function(){
		ResizeHeader();
	});
	BuildHeader();
});

</script>
