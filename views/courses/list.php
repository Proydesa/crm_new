<SCRIPT TYPE="text/javascript">

	$(document).ready(function(){

		$("#selecall").click(function(event){
			 if($("#selecall").is(':checked')) {
				$('.academia').each( function() {
					$(this).attr("checked","checked");
				});
			}else{
				$('.academia').each( function() {
					$(this).attr("checked",false);
				});
			}
		});
		$("#selecall2").click(function(event){
			 if($("#selecall2").is(':checked')) {
				$('.periodo').each( function() {
					$(this).attr("checked","checked");
				});
			}else{
				$('.periodo').each( function() {
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

		$(".pager").click(function(){
			$("#p").val($(this).attr("id"));
			$("#form-list").submit();
		});

	});

	function disabled_c(form){
		if (form.tipo[0[0]].checked){
			form.tipo[0][1].disabled = true;
			form.tipo[0][2].disabled = true;
			form.tipo[0][3].disabled = true;
			form.tipo[0][1].checked = false;
			form.tipo[0][2].checked = false;
			form.tipo[0][3].checked = false;
		}else{
			form.tipo[0][1].disabled = false;
			form.tipo[0][2].disabled = false;
			form.tipo[0][3].disabled = false;
		}
	}
</SCRIPT>
<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='width:90%; overflow: auto; height: 490px'";
	}else{
		$menufixed = "";
	}
?>
<div class="column-c"<?= $menufixed ?>>	
	<form action="<?= $HULK->SELF?>" method="post" name="parametros" id="form-list" class="noprint" style="margin:0; padding:0;">
	<input type="hidden" name="p" id="p" />
	<div class="column" style="width:300px">
	<div class="portlet">
		<div class="portlet-header">Filtros de b&uacute;squeda</div>
		<div class="portlet-content" style="height:140px;">
			<br/>
			<center>
				<input class="search" id="localsearch" type="search" autocomplete="off" size="30" maxlength="2048" name="q" title="Buscar en Hulk" value="<?= $q;?>"  spellcheck="false" />
			</center>
			<br/>
		</div>
	</div>
	</div>
	<div class="column" style="width:150px">
	<div class="portlet">
		<div class="portlet-header">Per&iacute;odos</div>
		<div class="portlet-content" style="height:140px;">
			<center>
				<input type="checkbox" class="pais" id="selecall2" value="selecall2"/><label for="selecall2">Todas</label>
			</center>
			<ul class="noBullet"  style="overflow:auto;max-height:110px;">
				<?php foreach($periodos_user as $periodo_user): ?>
				<li><input type="checkbox" name="periodos[]" class="periodo" value="<?=$periodo_user['periodo'];?>" <?php if(in_array($periodo_user['periodo'],$periodos_sel)) echo "checked"; ?>/><label for="periodo[]"><?=$periodo_user['periodo'];?></label></li>
				<?php endforeach; ?>
			</ul>
		</div>
	</div>
	</div>
	<div class="column" style="width:700px">
	<div class="portlet">
		<div class="portlet-header">Academias</div>
		<div class="portlet-content" style="height:140px;">
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
			<ul class="noBullet"  style="overflow:auto;max-height:110px;">
				<?php foreach($academias_user as $academia_user): ?>
					<li><input type="checkbox" name="academias[]" class="academia <?= $academia_user['country'];?>" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]"><?= $academia_user['name']?></label></li>
				<?php endforeach;?>
			</ul>
		</div>
	</div>
	<!--<div class="portlet">
		<div class="portlet-header">Tipo</div>
		<div class="portlet-content" style="overflow:auto;max-height:200px;">
			<ul class="noBullet">
				<li><input type="checkbox" name="tipo[]" value="privado" <?php if(in_array("privado",$tipo_sel)) echo "checked"; ?> onClick="javascript:disabled_c(this.form);"/><label for="tipo">Privado</label></li>
				<li><input type="checkbox" name="tipo[]" value="Control F" <?php if(in_array("Control F",$tipo_sel)) echo "checked"; ?>/><label for="tipo">Control-F</label></li>
				<li><input type="checkbox" name="tipo[]" value="Instructores" <?php if(in_array("Instructores",$tipo_sel)) echo "checked"; ?>/><label for="tipo">Instructores</label></li>
				<li><input type="checkbox" name="tipo[]" value="Bridge" <?php if(in_array("Bridge",$tipo_sel)) echo "checked"; ?>/><label for="tipo">Bridges</label></li>
			</ul>
		</div>
	</div>-->
	</div>
	<input type="submit" name="boton"  style="height: 30px; font-size:13px; width:98%; font-weight: bold;" class="button"  value="Consultar" />	<!-- alan > -->
	</form>
	<div class="portlet">
		<div class="portlet-header">Comisiones</div>
		<div class="portlet-content" >
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Academia</th>
						<th>Instructor</th>
						<th>Comisi&oacute;n</th>
						<th>Curso</th>
						<th>Estudiantes</th>
						<th>Per&iacute;odo</th>
				</tr>
				</thead>
				<tbody>
				<?php foreach($rows as $row):?>
					<tr class="ui-widget-content" style="height: 20px;" ondblclick="window.location.href='courses.php?v=view&id=<?= $row['id'];?>';">
						<td><?= $row['Academia'];?></td>
						<td><?= $row['Instructor'];?></td>
						<td>
							<a href="courses.php?v=view&id=<?= $row['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<?= $row['Comision'];?>
						</td>
						<td><?= $row['Course'];?></td>
						<td align="right"><?= $row['Alumnos'];?></td>
						<td align="center"><?= $row['periodo'];?></td>
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
