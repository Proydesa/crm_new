<script>
	$(document).ready(function(){
		$("#selecall").click(function(event){
			 if($("#selecall").is(':checked')) {
				$('.academy').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.academy').each( function() { $(this).attr("checked",false); });
			}
		});
		$("#selecall2").click(function(event){
			 if($("#selecall2").is(':checked')) {
				$('.carrera').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.carrera').each( function() {	$(this).attr("checked",false); });
			}
		});
		$("#selecall3").click(function(event){
			 if($("#selecall3").is(':checked')) {
				$('.periodo').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.periodo').each( function() { $(this).attr("checked",false); });
			}
		});
		$("#selecall4").click(function(event){
			 if($("#selecall4").is(':checked')) {
				$('.noperiodo').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.noperiodo').each( function() { $(this).attr("checked",false); });
			}
		});
		$("#selecall5").click(function(event){
			 if($("#selecall5").is(':checked')) {
				$('.noacademy').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.noacademy').each( function() {	$(this).attr("checked",false); });
			}
		});
		$("#selecall6").click(function(event){
			 if($("#selecall6").is(':checked')) {
				$('.nocarrera').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.nocarrera').each( function() { $(this).attr("checked",false); });
			}
		});
		$("#selecall7").click(function(event){
			 if($("#selecall7").is(':checked')) {
				$('.roles').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.roles').each( function() { $(this).attr("checked",false); });
			}
		});
		$("#selecall8").click(function(event){
			 if($("#selecall8").is(':checked')) {
				$('.noroles').each( function() { $(this).attr("checked","checked"); });
			}else{
				$('.noroles').each( function() { $(this).attr("checked",false); });
			}
		});
	});

</script>

<div>
	<div id="esconder" class="noprint">
		<form action="<?= $HULK->SELF?>" method="post" name="parametros" style="margin:0; padding:0;">
			<div class="portlet" style="float:left;width:49%">
				<div class="portlet-header">Que estén enrolados en...</div>
				<div class="column" style="width:36%; margin:2px;">
					<div class="ui-widget-content">
						<b>&nbsp;Carreras:</b><input type="checkbox" id="selecall2" value="selecall2"/><label for="selecall2">Todas/Ninguna</label>
						<div class="portlet-content" >
							<p class="noBullet"  style="overflow:auto;max-height:230px;">
								<table  class="ui-widget" width="100%">
								<?php foreach($cursos as $curso): ?>
								<tr>
									<td class="ui-widget-content">
										<label for="cursos[]" ><?=$curso['shortname'];?></label>
									</td>
									<td class="ui-widget-content">
										<input type="checkbox" class="carrera" name="cursos[]" value="<?=$curso['id'];?>" <?php if(in_array($curso['id'],$cursos_sel)) echo "checked"; ?>/>
									</td>
								</tr>
								<?php endforeach; ?>
								</table>
							</p>
						</div>
					</div>
				</div>
					<div class="column" style="width:30%; margin:2px;">
						<div class="ui-widget-content">
							<b>&nbsp;Roles: </b><input type="checkbox" id="selecall7" value="selecall7"/><label for="selecall7">Todos/Ninguno</label>
							<div style="overflow:auto;max-height:100px;">
								<ul class="noBullet">
									<?php foreach($roles_user as $role_name => $role_id): ?>
									<li><input type="checkbox" name="roles[]" class="roles" value="<?=$role_id;?>" <?php if(in_array($role_id,$roles_sel)) echo "checked"; ?>/><label for="roles[]"><?=$role_name;?></label></li>
									<?php endforeach; ?>
								</ul>
							</div>
						</div>
						<div class="ui-widget-content" style="margin-top:3px;">
						<b>&nbsp;Academias:</b><input type="checkbox" id="selecall" value="selecall"/><label for="selecall">Todas/Ninguna</label>
						<div class="portlet-content" style="overflow:auto;max-height:110px;">
							<ul class="noBullet">
								<?php foreach($academias_user as $academia_user): ?>
									<li><input type="checkbox" name="academias[]" class="academy" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academias[]" title="<?= $academia_user['name']?>"><?= $academia_user['shortname']?></label></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					</div>
					<div class="column" style="width:30%; margin:2px;">
					<div class="ui-widget-content">
						<b>&nbsp;Periodos: </b><input type="checkbox" id="selecall3" value="selecall3"/><label for="selecall3">Todos/Ninguno</label>
						<div style="overflow:auto;max-height:100px;">
							<ul class="noBullet">
								<?php foreach($periodos_user as $periodo_user): ?>
								<?php
										if ($periodo_user[2]==1){
											$tooltip="Enero Febrero 20".$periodo_user[0].$periodo_user[1];
										}elseif($periodo_user[2]==2){
											$tooltip="Marzo Julio 20".$periodo_user[0].$periodo_user[1];
										}else{
											$tooltip="Agosto Diciembre 20".$periodo_user[0].$periodo_user[1];
										}
								?>
								<li><input type="checkbox" name="periodos[]" class="periodo" value="<?=$periodo_user;?>" <?php if(in_array($periodo_user,$periodos_sel)) echo "checked"; ?>/><label for="periodo[]" title="<?=$tooltip;?>"><?=$periodo_user;?></label></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<div class="ui-widget-content" style="margin-top:3px;">
						<b>&nbsp;Estado: </b><input type="checkbox" id="selecall7" value="selecall7"/><label for="selecall7">Todos/Ninguno</label>
						<div style="overflow:auto;max-height:160px;">
							<ul class="noBullet">
								<li><input type="checkbox" name="status[]" class="status" value="0" <?php if(in_array(0,$status_sel)) echo "checked"; ?>/>
									<label for="status[]">Enrolado</label></li>
								<li><input type="checkbox" name="status[]" class="status" value="1" <?php if(in_array(1,$status_sel)) echo "checked"; ?>/>
									<label for="status[]">Incompleto</label></li>
								<li><input type="checkbox" name="status[]" class="status" value="2" <?php if(in_array(2,$status_sel)) echo "checked"; ?>/>
									<label for="status[]">Fallido</label></li>
								<li><input type="checkbox" name="status[]" class="status" value="3" <?php if(in_array(3,$status_sel)) echo "checked"; ?>/>
									<label for="status[]">Promocionado</label></li>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div class="portlet" style="float:right;width:49%">
				<div class="portlet-header">Y que no estén enrolados en...</div>
		<div class="column" style="width:36%; margin:2px;">
			<div class="ui-widget-content">
			<b>&nbsp;Carreras:</b><input type="checkbox" id="selecall6" value="selecall6"/><label for="selecall6">Todas/Ninguna</label>
			<div class="portlet-content" >
				<p class="noBullet"  style="overflow:auto;max-height:160px;">
					<table  class="ui-widget" width="100%">
					<?php foreach($cursos as $curso): ?>
					<tr>
						<td class="ui-widget-content">
							<label for="nocursos[]" ><?=$curso['shortname'];?></label>
						</td>
						<td class="ui-widget-content">
							<input type="checkbox" class="nocarrera" name="nocursos[]" value="<?=$curso['id'];?>" <?php if(in_array($curso['id'],$nocursos_sel)) echo "checked"; ?>/>
						</td>
					</tr>
					<?php endforeach; ?>
					</table>
				</p>
			</div>
		</div>
		</div>
				<div class="column" style="width:30%; margin:2px;">
					<div class="ui-widget-content">
					<b>&nbsp;Academias:</b><input type="checkbox" id="selecall5" value="selecall5"/><label for="selecall5">Todas/Ninguna</label>
					<div class="portlet-content" style="overflow:auto;max-height:182px;">
						<ul class="noBullet">
							<?php foreach($academias_user as $academia_user): ?>
								<li><input type="checkbox" name="noacademias[]" class="noacademy" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$noacad_sel)) echo "checked"; ?>/><label for="noacademias[]" title="<?= $academia_user['name']?>"><?= $academia_user['shortname']?></label></li>
							<?php endforeach; ?>
						</ul>
					</div>
				</div>
				</div>
				<div class="column" style="width:30%; margin:2px;">
					<div class="ui-widget-content">
						<b>&nbsp;Periodos:</b><input type="checkbox" id="selecall4" value="selecall4"/><label for="selecall4">Todos/Ninguno</label>
						<div class="portlet-content" style="overflow:auto;max-height:182px;">
							<ul class="noBullet">
								<?php foreach($periodos_user as $periodo_user): ?>
								<?php
										if ($periodo_user[2]==1){
											$tooltip="Enero Febrero 20".$periodo_user[0].$periodo_user[1];
										}elseif($periodo_user[2]==2){
											$tooltip="Marzo Julio 20".$periodo_user[0].$periodo_user[1];
										}else{
											$tooltip="Agosto Diciembre 20".$periodo_user[0].$periodo_user[1];
										}
								?>
								<li><input type="checkbox" name="noperiodos[]" class="noperiodo" value="<?=$periodo_user;?>" <?php if(in_array($periodo_user,$noperiodos_sel)) echo "checked"; ?>/><label for="noperiodo[]" title="<?=$tooltip;?>"><?=$periodo_user;?></label></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>
					<div class="ui-widget-content" style="margin-top:3px;">
						<b>&nbsp;Estado: </b><input type="checkbox" id="selecall7" value="selecall7"/><label for="selecall7">Todos/Ninguno</label>
						<div style="overflow:auto;max-height:182px;">
							<ul class="noBullet">
								<li><input type="checkbox" name="nostatus[]" class="nostatus" value="0" <?php if(in_array(0,$nostatus_sel)) echo "checked"; ?>/>
									<label for="nostatus[]">Enrolado</label></li>
								<li><input type="checkbox" name="nostatus[]" class="nostatus" value="1" <?php if(in_array(1,$nostatus_sel)) echo "checked"; ?>/>
									<label for="nostatus[]">Incompleto</label></li>
								<li><input type="checkbox" name="nostatus[]" class="nostatus" value="2" <?php if(in_array(2,$nostatus_sel)) echo "checked"; ?>/>
									<label for="nostatus[]">Fallido</label></li>
								<li><input type="checkbox" name="nostatus[]" class="nostatus" value="3" <?php if(in_array(3,$nostatus_sel)) echo "checked"; ?>/>
									<label for="nostatus[]">Promocionado</label></li>
							</ul>
						</div>
					</div>
					<div class="ui-widget-content" style="margin-top:3px;">
						<b>&nbsp;Roles: </b><input type="checkbox" id="selecall8" value="selecall8"/><label for="selecall8">Todos/Ninguno</label>
						<div style="overflow:auto;max-height:182px;">
							<ul class="noBullet">
								<?php foreach($roles_user as $role_name => $role_id): ?>
								<li><input type="checkbox" name="noroles[]" class="noroles" value="<?=$role_id;?>" <?php if(in_array($role_id,$noroles_sel)) echo "checked"; ?>/><label for="noroles[]"><?=$role_name;?></label></li>
								<?php endforeach; ?>
							</ul>
						</div>
					</div>

				</div>
			</div>
		<input type="submit" name="boton"  style="height: 35px; font-size:13px; width:100%; font-weight: bold; margin:10px;" class="button"  value="Ver" />
		</form>
	</div>
	<div class="ui-widget noprint" align="right">
		<span align="right">
			<form action="reportes.php?v=xls" method="post" id="detalle-form">
				<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;"><b>Imprimir</b></span>
				<span id="detalle" class="button-xls" style="font-size: 9px;"><b>Descargar XLS</b></span>
				<input type="hidden" id="detalle-table-xls" name="table-xls" />
				<input type="hidden" id="name-xls" name="name-xls" value="reporte-listado" />
			</form>
		</span>
	</div>
	<!--<div class="column" style="width:100%">-->
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
			<div class="portlet-header ui-widget-header ui-corner-all">Comisiones</div>
			<div class="portlet-content">
				<table id="detalle-export" class="ui-widget" align="center" style="width:100%;">
					<thead>
					<tr class="ui-widget-header" style="height:20px;font-size:7pt">
							<th style="width: 1%;">     </th>
							<th style="width: 5%;">Role</th>
							<th style="width: 10%;">Apellido</th>
							<th style="width: 10%;">Nombre</th>
							<th style="width: 15%;">Empresa</th>
							<th style="width: 20%;">Comisión</th>
							<th style="width: 19%;">Correo Electrónico</th>
							<th style="width: 10%;">Celular</th>
							<th style="width: 5%;">Modo</th>
							<th style="width: 5%;">Estado</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$i=0;
					$arrStatus	= array ('0.00000'=>'E',''=>'E','1.00000'=>'I','2.00000'=>'F','3.00000'=>'P');
					$arrStatusClass = array('E'=>'bg-primary','P'=>'bg-success','I'=>'bg-danger','F'=>'bg-warning');
					?>
					<?php foreach($rows as $row):?>
						<?php
							$i++;
						?>
						<tr class="ui-widget-content" style="height: 20px;">
							<td class="ui-widget-content"><?= $i;?></td>
							<td class="ui-widget-content"><?= $row['role'];?></td>
							<td class="ui-widget-content"><?= $row['lastname'];?></td>
							<td class="ui-widget-content"><?= $row['firstname'];?></td>
							<td class="ui-widget-content"><?= $row['empresa'];?></td>
							<td class="ui-widget-content"><?= $row['Comision'];?></td>
							<td class="ui-widget-content"><?= $row['email'];?></td>
							<td class="ui-widget-content"><?= $row['phone2'];?></td>
							<td class="ui-widget-content"><?php if($row['intensivo']==1){ echo "Intensivo"; }else{ echo "Regular";}?></td>
							<td class="<?= $arrStatusClass[$arrStatus[$row['status']]]; ?> textCenter"><?= $arrStatus[$row['status']]; ?></td>
						</tr>
					<?php endforeach;?>
					</tbody>

				</table>
			</div>
		</div>
	<!--</div>-->
		<?php if ($H_USER->has_capability('site/config')):	?>
			<h4>Consulta armada</h4>
			<pre><?= $sql;?></pre>
		<?php endif;?>
	<!-- FIXED COLUMN -->
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
