<script type="text/javascript">
	$(function(){
		$( ".button_file2" ).button({ icons: { secondary: "ui-icon-document" }, text: true });
	});

	var contador = 0;
	
	function nuevo_adjunto(){
		if(contador != 3){
			$("#files").append("<br/><input type=\"file\" name=\"archivos[]\" class=\"press\"  style=\"height:30px;\" /><br/>");
			contador++;
		}else{
			alert("No puede adjuntar mas de tres archivos.");
		}
	}
</script>
<div class="column-c" style="width:950px; overflow: auto; height: 440px">
              <div align="right" class="ui-widget">
                <em>*</em> - Requerido&nbsp;&nbsp;&nbsp; 
                <?php
				if ($H_USER->has_capability('activity/representante')) {
					echo "[<a href='hd.php?v=representante_view&id={$activity['id']}'>Vista Avanzada</a>]";
				}
				?>&nbsp;&nbsp;
              </div>
	<div class="portlet">
    	<div class="portlet-header"> Detalles del incidente&nbsp;<?=$activity['id']?></div>
		<div class="portlet-content">
			<table class="ui-widget form-view" align="center" style="width:100%;">
				<thead>
						<tr style="height: 30px;">
							<th colspan="2"><b>Información de contacto</b></th>
							<th colspan="2"><b>Clasificación de los incidentes</b></th>
						</tr>
				</thead>
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 30px;">
							<td width="15%"><b>Contacto</b></td>
							<td width="35%">
								<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
							</td>
							<td><b>Academia</b></td>
							<td>
								<?= $activity['academyid'];?>
							</td>		
						</tr>
						<tr style="height: 30px;">
							<td><b><label for="email">Correo Electronico</label></b></td>
							<td>
								<?= $LMS->GetField('mdl_user','email',$activity['userid']);?>
							</td>
							<td width="15%"><b>Categoria</b></td>
							<td width="35%">
								<?= $H_DB->GetField('h_activity_category','name',$activity['categoryid']);?>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Ubicaci&oacute;n</b></td>
							<td>
								<?= $LMS->GetField('mdl_user','city',$activity['userid']);?>
							</td>
							<td><b>Fecha de inicio</b></td>
							<td>
								<?= date('d-m-Y h:i:s A',$activity['startdate']);?>							
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Tel&eacute;fono</b></td>
							<td>
								<?= $LMS->GetField('mdl_user','CONCAT(phone1," / ",phone2)',$activity['userid']);?>
							</td>
					        <td><b>Asignado a</b></td>
							<td>
								<a href="mailto: <?= $LMS->GetField('mdl_user','email',$activity['assignto']);?>?Subject=Help Desk:&nbsp;Incidente&nbsp;<?=$activity['id'];?>"> <?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['assignto']);?></a>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td></td>
							<td>
							</td>
					        <td><b>Estado</b></td>
							<td>	
								<?= $activity['status'];?>
							</td>
						</tr>
						<tr><th colspan="4" class="ui-widget-header">Información del incidente</th></tr>
						<tr style="height: 30px;">
							<td colspan="4"  align="left"><b>Titulo:</b><em>*</em></td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="4"  align="left">
								<input name="subject" type="text" readonly value="<?= $activity['subject'];?>"/>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td colspan="4" align="left"><b>Descripci&oacute;n:</b><em>*</em></td>						
						</tr>
						<tr style="height: 30px;">
							<td colspan="4" align="left"><blockquote style="margin:10px;"><?= $activity['summary'];?></blockquote></td>
						</tr>
						<tr><th colspan="4" class="ui-widget-header">Información complementaria</th></tr>
							<?php if ($activity['serialize_data']):?>	
							<?php foreach( $activity['serialize_data'] as $key=>$value):?>					
								<tr style="height: 30px;"><td><b>	<?= $key;?></b></td>
								<?php if(is_array($value)) $value=implode(",",$value);?>
								<td colspan="3" align="left">&nbsp;<?= $value;?></td></tr>
							<?php endforeach;?>
							<?php endif;?>									
							<?php if ($activity['files']):?>	
								<tr style="height: 30px;"><td><b>Archivos adjuntos:</b></td><td colspan="3">			
								<?php foreach($activity['files'] as $file):?>
									&nbsp;
									<a href="file.php?userid=<?= $activity['userid'];?>&file=<?= $file['file'];?>" target="_blank"><?= $file['file'];?></a>
									&nbsp;
								<?php endforeach;?>
								</td></tr>
							<?php endif;?>		
						<tr><th colspan="4" class="ui-widget-header">Notas</th></tr>
						<?php foreach($activity_parent as $parent):?>				
						<tr>
							<td colspan="4"  align="left">
								<b>[<?= date('d-m-Y H:i A',$parent['startdate']);?> - <?= $LMS->GetField('mdl_user','CONCAT(firstname," ",LEFT(lastname,1))',$parent['userid']);?>]
								<?php if($parent['private']>0) echo " - PRIVATE";?>
								</b>
								<blockquote><?= $parent['summary'];?></blockquote>
							</td>
						</tr>
							<?php if ($parent['files']):?>	
								<tr style="height: 30px;"><td><b>Archivos adjuntos:</b></td><td colspan="3" align="left">			
								<?php foreach($parent['files'] as $file):?>
									&nbsp;
									<a href="file.php?userid=<?= $parent['userid'];?>&file=<?= $file['file'];?>" target="_blank"><?= $file['file'];?></a>
									&nbsp;
								<?php endforeach;?>
								</td></tr>
							<?php endif;?>
						<?php endforeach;?>
				</tbody>
			</table>						
			<form method="post" action="hd.php?v=update" id="notas" class="form-view" enctype="multipart/form-data" >
			<input type="hidden" name="id" value="<?= $activity['id'];?>">
			<table class="ui-widget form-view" align="center" style="width:100%;">
				<tbody>
					<tr><th colspan="4" class="ui-widget-header">Proporcione notas adicionales:</th></tr>
					<tr style="height: 30px;">
						<td colspan="3" align="center">
						<textarea cols="100" rows="10" name="summary"></textarea></td>
						<td  align="left" valign="top">
							<span class="button_file2" onClick='nuevo_adjunto();' class="press" >Adjuntar documento</span>
							<div id="files"></div>
						</td>
					</tr>
				</tbody>
			</table>
			<div align="center">
				<input type="hidden" value="crear" name="action">
				<button type="button" class="add" onclick="$('.validate').validate();$('#notas').submit();">Actualizar incidente</button>
				<button type="button" class="button-borrar" onClick="$('#notas')[0].reset();">Borrar las notas</button>
			</div>
		</form>
		</div>
	</div>
</div>