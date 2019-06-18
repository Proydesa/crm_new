<script>
	$(document).ready(function(){    

		$("#selecall").click(function(event){  
			 if($("#selecall").is(':checked')) {  
				$('.academy').each( function() {		
					$(this).attr("checked","checked");
				});
			}else{	
				$('.academy').each( function() {	
					$(this).attr("checked",false);
				});	
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
			});
		<?php endforeach;?>		
	}); 
</script>

<div>
	<div class="ui-widget noprint" align="right">
		<table  align="right" class="noprint">
			<tr>
				<td>
					<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
				</td>
			</tr>
		</table>
	</div>
	<div id="esconder" class="noprint column-c" style="width:90%">	
		<form action="reportes.php?v=ekits" name="form" method="post">	
			<div class="column" style="width:200px">		
				<div class="portlet">
					<div class="portlet-header">Filtros</div>
					<div class="portlet-content" style="overflow:auto;max-height:300px;">	
						<ul class="noBullet">	
							<span>Periodo/s: </span>
								<?php foreach($LMS->getPeriodos() as $periodo):?>									
							<?php 
									if ($periodo[2]==1){
										$tooltip="Enero Febrero 20".$periodo[0].$periodo[1];
									}elseif($periodo[2]==2){
										$tooltip="Marzo Julio 20".$periodo[0].$periodo[1];
									}else{
										$tooltip="Agosto Diciembre 20".$periodo[0].$periodo[1];
									}
								?>
								<li><input type="checkbox" name="periodos[]" class="periodos" value="<?= $periodo; ?>" id="periodo<?= $periodo; ?>" <?php if(in_array($periodo,$periodos_sel)) echo "checked"; ?> /><label for="periodo<?= $periodo; ?>" title="<?=$tooltip;?>"><?= $periodo;?></label></li>
								<?php endforeach;?>	
						</ul>
					</div>	
				</div>	
			</div>	
			<div class="column" style="width:600px">		
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
						<ul class="noBullet"  style="overflow:auto;max-height:120px;">
							<?php foreach($LMS->getAcademias() as $acadlist): ?>
										<?php if (!$H_USER->has_capability("reportes/academycourse/internacionales") AND $acadlist['country']!="AR"){
												$estado='disabled="disabled"';
											}else{
												$estado='';
											}
										?>							
								<li><input type="checkbox" <?= $estado;?> name="academy[]" class="academy <?=$acadlist['country'];?>" value="<?=$acadlist['id'];?>" id="acad<?=$acadlist['id'];?>" <?php if(in_array($acadlist['id'],$acadsel)) echo "checked"; ?>/><label for="acad<?=$acadlist['id'];?>"><?=$acadlist['name'];?></label></li>
							<?php endforeach; ?>
						</ul>		
					</div>	
				</div>				
			</div>
			<input type="submit" name="boton"  style="height: 30px; font-size:13px; width:100%; font-weight: bold;" class="button"  value="Consultar" />
		</form>	
	</div>

	<div class="column-c" style="width:90%">
		<div class="portlet">
			<div class="portlet-header">Alumnos Sin Ekits</div>
			<div class="portlet-content" >
				<table id="listado" class="ui-widget" align="center" style="width:100%;">
					<thead>
						<tr class="ui-widget-header" style="height: 20px;">
							<th>Academia</th>
							<th>Curso</th>
							<th>Total</th>
						</tr>
					</thead>
					<tbody>
					<?php if ($academias): ?>
						<?php foreach($academias as $academia): ?>
							<tr>
								<td><?= $academia['academy']; ?></td>
								<td><?= $academia['modelo']; ?></td>
								<td align="right"><?= $academia['alumnos']; ?></td>
							</tr>
						<?php endforeach; ?>
					<?php endif;?>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<?php if ($rows): ?>
				<?php foreach($rows as $comi=>$rows2):?>
				<form method="post" action="reportes.php?v=ekit_edit">
				<input type="hidden" name="comision" value="<?=$rows2['comiid']?>" />
				<table id="listado" class="ui-widget" align="center" style="width:100%;">
						<thead>
							<tr class="ui-widget-header" style="height: 20px;" title="<?= $title;?>">
								<th></th>
								<th colspan="2">
									<a href="courses.php?v=list&q=<?= $comi;?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<?=$comi;?>
								</th>
								<th colspan="2"><?=$rows2['modelo'];?></th>
							</tr>
							<tr class="ui-widget-header" style="height: 20px;">
								<th width="80px">DNI</th>
								<th width="200px">Alumno</th>
								<th width="200px">Academia</th>
								<th width="50px">Estado</th>
								<th>eKits</th>
							</tr>
						</thead>
						<?php foreach($rows2['users'] as $comi=>$row):?>
						<tbody>
							<?php 
							if($row['recursa']>0){ 
								$class="ui-state-highlight"; 
								$title="Recursante. Comsi&oacute;n con eKit: ".$LMS->GetField("mdl_course", "fullname", $row['recursa']);
								$texto="Recursante";
							}else{
								$class="";$title="";$texto="";
							}	
							if($row['pago']==0){ 
								$class="ui-state-error";
								$texto="No está pago";
							}else{
								$class="";$texto="Pagó";
							}
							?>
							<tr class="ui-widget-header" style="height: 20px;" title="<?= $title;?>">
								<td class="<?= $class; ?>"><?= $row['username']; ?></td>
								<td class="<?= $class; ?>">
									<a href="contactos.php?v=view&id=<?= $row['userid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<?= $row['alumno']; ?>
								</td>
								<td class="<?= $class; ?>"><?= $row['academy'];?></td>
								<td class="<?= $class; ?>"><?= $texto;?></td>
								<td class="<?= $class; ?>" align="center"><textarea name="onlinetext[<?=$row['userid']?>]" cols="60"><?= $row['onlinetext'] ;?></textarea></td>
							</tr>
						<?php endforeach;?>
						<tr class="ui-widget-header" style="height: 20px;" title="<?= $title;?>">
							<td colspan="4"></td>
							<td align="center"><input type="submit" name="submit" value="Cargar" class="button" /></td>
						</tr>	
					</tbody>
				</table>
				</form>
				<?php endforeach;?>
				<?php endif;?>
			</div>
		</div>
	</div>
</div>