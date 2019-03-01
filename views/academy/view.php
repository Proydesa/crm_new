<?php
/*foreach ($activitys as $key => $value) {
	print_r( $value ).'<br /><br />';
}*/

?>


<script type="text/javascript">

	$(function () {

		$("#subject").watermark("Asunto...");
		$("#summary").watermark("Registre una nueva actividad...")
		.focus(function() {
				$("#activity-button").show();
				$(this).height("50px");
			})
		.focusout(function() {
			if ($.trim($(this).val())==""){
					$("#activity-button").hide();
					$(this).height("30px");
				}
			});

		$('textarea').autoResize({
			onResize : function() {
				$(this).css({opacity:0.8});
			},
			animateCallback : function() {
				$(this).css({opacity:1});
			},
			animateDuration : 300,
			extraSpace : 20
		});

		$('#cursos').html("<p align='center'><img src='themes/cargando.gif' border='0' valign='middle' /> Cargando...</p>").load('academy.php?v=widget-cursos&id=<?= $row['id'];?>');
		$('#instructores').html("<p align='center'><img src='themes/cargando.gif' valign='middle' border='0' /> Cargando...</p>").load('academy.php?v=widget-instructores&id=<?= $row['id'];?>');
		$('#convenios').html("<p align='center'><img src='themes/cargando.gif' valign='middle' border='0' /> Cargando...</p>").load('academy.php?v=widget-convenios&id=<?= $row['id'];?>');
	});

	var contador = 0;
  function nuevo_adjunto(){
		$("#files").append("<div id=\""+contador+"\" style=\"width:300px;margin:auto;\"><input type=\"file\" name=\"archivos[]\" class=\"press\"  style=\"height:30px;\" /><span class=\"ui-icon ui-icon-close\" onclick=\"elimina_adjunto("+contador+")\" class=\"press\" style=\"float:right;\"></span></div>");
		contador++;
  }

  function elimina_adjunto(elemento){
		$("#"+elemento).remove();
  }
</script>
<div class="ui-widget" align="left">
<div class="column" style="width:70%">
	<div class="portlet">
		<div align="center"><h2><?= $row['name'];?></h2></div>
		<div class="portlet-content">
		<form class="form-view" action="academy.php?v=new&id=<?= $row['id'];?>&stage=1" method="post" name="inscripcion" class="ui-widget">
		<table class="ui-widget" align="center" style="width:100%;">
				<tbody class="ui-widget-content">
					<tr style="height: 20px;">
						<td class="ui-widget-content" width="135" align="right"><b>Nombre:</b></td>
						<td class="ui-widget-content" width="230">
						<input  disabled name="name" type="text" value="<?= $row['name'];?>"/>
						<input  disabled name="categoryid" type="hidden" value="<?= $row['categoryid'];?>"/>
						</td>
						<td class="ui-widget-content" width="135" align="right"><b>Nombre Corto:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="shortname" type="text" value="<?= $row['shortname'];?>"/>
						</td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>Sede:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="sede" type="text" value="<?= $row['sede'];?>"/>
						</td>
						<td class="ui-widget-content" align="right"><b>Nivel:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="nivel" type="text" value="<?= $row['nivel'];?>"/>
						</td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>E-Mail:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="email" type="text" value="<?= $row['email'];?>"/>
						</td>
						<td class="ui-widget-content" align="right"><b>Tel&eacute;fono:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="phone" type="text" value="<?= $row['phone'];?>"/>
						</td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>Direcci&oacute;n:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="address" type="text" value="<?= $row['address'];?>"/>
						</td>
						<td class="ui-widget-content" align="right"><b>Localidad:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="city" type="text" value="<?= $row['city'];?>"/>
						</td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>Ciudad:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="state" type="text" value="<?= $row['state'];?>"/>
						</td>
						<td class="ui-widget-content" align="right"><b>C&oacute;digo Postal:</b></td>
						<td class="ui-widget-content">
							<input  disabled name="code" type="text" value="<?= $row['code'];?>"/>
						</td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>Pa&iacute;s:</b></td>
						<td class="ui-widget-content">
							<select name="country" class="required" disabled>
								<option value="">Seleccione pa&iacute;s</option>
								<?php foreach($HULK->countrys as $count => $country):?>
									<?php ($count==$row['country'])? $SELECTED="selected":$SELECTED="";?>
									<option value="<?= $count;?>" <?= $SELECTED;?>><?= $country;?></option>
								<?php endforeach;?>
							</select>
						</td>
						<td class="ui-widget-content" align="right"><b>Logo:</b></td>
						<td class="ui-widget-content"> <img style="cursor: pointer;" src="http://www.proydesa.org/lms_new/mod/certificate/img/1/<?= $row['shortname'];?>.jpg" onClick="javascript: window.open ('http://www.proydesa.org/lms_new/mod/certificate/img/1/<?= $row['shortname'];?>.jpg','titulodelpopup','width=430,height=190,scrollbars=NO')" height="40" width="70"></td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>Usuario del certificado:</b></td>
						<td class="ui-widget-content"><input  disabled name="certificates_user" type="text" value="<?= $row['certificates_user'];?>"/></td>
						<td class="ui-widget-content" align="right"><b>Role del certificado:</b></td>
						<td class="ui-widget-content"><input  disabled name="certificates_role" type="text" value="<?= $row['certificates_role'];?>"/></td>
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" align="right"><b>Modificado:</b></td>
						<td class="ui-widget-content"><?= show_fecha($row['timemodified'],"");?></td>
						<td class="ui-widget-content" align="right"><b>Firma del certificado:</b></td>
						<td> <img style="cursor: pointer;" src="http://www.proydesa.org/lms/mod/certificate/img/4/<?= $row['shortname'];?>.jpg" onClick="javascript: window.open ('http://www.proydesa.org/lms/mod/certificate/img/4/<?= $row['shortname'];?>.jpg','titulodelpopup','width=430,height=190,scrollbars=NO')" height="40" width="70"></td>
						
						
					</tr>
					<tr style="height: 20px;">
						<td class="ui-widget-content" colspan="2">
						<span onClick="$('#links').slideToggle()" class="button" align="center"><b>Links y redes sociales</b></span>
								<ul id="links" style="display:none;">
									<li><a href="<?= $row['url'];?>" target="_blank">Web</a> (incluir http://): <input  disabled name="url" type="text" value="<?= $row['url'];?>"/></li>
									<li><a href="skype:<?= $row['skype'];?>?call" target="_blank">Skype</a>: <input  disabled name="skype" type="text" value="<?= $row['skype'];?>"/></li>
									<li><a href="<?= $row['msn'];?>" target="_blank">MSN</a>: <input  disabled name="msn" type="text" value="<?= $row['msn'];?>"/></li>
									<li><a href="http://twitter.com/#!/<?= $row['twitter'];?>" target="_blank">Twitter</a> (usuario): <input  disabled name="twitter" type="text" value="<?= $row['twitter'];?>"/></li>
									<li><a href="https://www.facebook.com/<?= $row['facebook'];?>" target="_blank">Facebook</a> (usuario): <input  disabled name="facebook" type="text" value="<?= $row['facebook'];?>"/></li>
									<li><a href="<?= $row['googleplus'];?>" target="_blank">Google Plus</a>: <input  disabled name="googleplus" type="text" value="<?= $row['googleplus'];?>"/></li>
									<li><a href="<?= $row['googlemaps'];?>" target="_blank">Google Maps</a> (coordenadas "x,y"): <input  disabled name="googlemaps" type="text" value="<?= $row['googlemaps'];?>"/></li>
									<li><a href="<?= $row['linkedin'];?>" target="_blank">LinkedIn</a>: <input  disabled name="linkedin" type="text" value="<?= $row['linkedin'];?>"/></li>
								</ul>
						</td>
						<td class="ui-widget-content" align="right"><b>Estado de la academia:</b></td>
						<td class="ui-widget-content">
							<select name="status" disabled>
								<?php foreach($HULK->acad_status as $key=>$value):?>
									<option value="<?= $key;?>"  <?php if( $row['status']==$key) echo "SELECTED";?> ><?=$value;?></option>
								<?php endforeach;?>
							</select>
						</td>
						</tr>
						<tr style="height: 20px;">
						<td colspan="4" align="center">
							<span class="edit">
								<button type="button" class="button-editar" onClick="$('.form-view *').removeAttr('disabled');$('.edit').toggle();">Editar</button>
							</span>
							<span class="edit" style="display:none;">
								<button type="button" class="button-editar" onClick="$('.form-view').submit();" >Guardar cambios</button>
								<button type="button" class="button-borrar" onClick="$('.form-view input,.form-view select').attr('disabled','disabled');$('.edit').toggle();$('.form-view')[0].reset();">Cancelar</button>
							</span>
						</td>
					</tr>
				</tbody>
			</table>
		</form>
		</div>
	</div>
</div>
<div class="column" style="width:30%">
	<div class="portlet">
		<div class="portlet-header">Procedimientos</div>
		<div class="portlet-content" style="margin:5px;">
				<h3>Informes</h3>
				<span>
					<a href="reportes.php?v=inscriptos&t=detalle&academias[]=<?=$row['id'];?>" class="button">Listado Enrolados</a>
					<a href="reportes.php?v=inscriptos_periodo_calendario&academias[]=<?=$row['id'];?>" class="button">Inscriptos por periodo de calendario</a>
					<a href="reportes.php?v=inscriptos_periodo&academias[]=<?=$row['id'];?>" class="button">Comparativa de inscriptos</a>
					<a href="reportes.php?v=inscriptos_dia&academias[]=<?=$row['id'];?>" class="button">Inscriptos por día</a>
					<a href="reportes.php?v=inscriptos_mensual&academias[]=<?=$row['id'];?>" class="button">Inscriptos por mes</a>
				</span>
				<h3>Generales</h3>
				<span>
					<a href="?v=grilla_aulas&id=<?=$row['id'];?>" class="button" >Grilla de aulas</a>
					<a href="?v=aulas&academyid=<?=$row['id'];?>" class="button" >Administrar aulas</a>
				</span>
				<h3>Administrativos</h3>
				<span>
					<p>
						<?php if($row['status']!=2):?>
						<a href="?v=bloquear_academia&academyid=<?=$row['id'];?>" class="button confirmLink" >Bloquear Academia</a>
						<?php else:?>
						<a href="?v=desbloquear_academia&academyid=<?=$row['id'];?>" class="button confirmLink" >Desbloquear Academia</a>
						<?php endif;?>
						El bloqueo de la academia no permite crear ningún curso a la misma. Manteniendo el ingreso de los usuarios a las comisiones existentes normalmente.
					</p>
					<a href="?v=view&id=<?=$row['id'];?>" class="button confirmLink" >Baja Academia</a>
				</span>
		</div>
	</div>
</div>
<span style="clear"></span>
<div class="column" style="width:55%">
	<div class="portlet">
		<div class="portlet-header">Usuarios Administrativos</div>
		<div class="portlet-content"  style="overflow: auto; max-height:180px">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody class="ui-widget-content">
					<?php foreach($u_admins as $u_admin):?>
						<tr style="height: 20px;">
							<td class="ui-widget-content" class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $u_admin['id'];?>';">
								<a href="contactos.php?v=view&id=<?= $u_admin['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<?= $u_admin['nombre'];?>
							</td>
							<td class="ui-widget-content"><?= $u_admin['rol'];?></td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Instructores</div>
		<div class="portlet-content" id="instructores"  style="overflow: auto; max-height: 180px;"></div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Convenios</div>
		<div class="portlet-content" id="convenios"  style="overflow: auto; max-height: 250px;"></div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Cursos del per&iacute;odo actual</div>
		<div class="portlet-content" id="cursos" style="overflow: auto; max-height: 250px;"></div>
	</div>
</div>
<div class="column" style="width:45%">
	<div class="portlet">
		<div class="portlet-header">Actividad</div>
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<tr>
						<th></th>
						<th>Fecha</th>
						<th>Asunto</th>
						<th>A&ntilde;adido por</th>
						<th>Con</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
					<?php foreach($activitys as $activity):?>
					<tr title="ID (<?=$activity['id'];?>): <?= str_replace("'",'"',strip_tags($activity['summary']));?>" class="ui-widget-content" style="height: 30px; text-align:center;" class="activity" >
							<td class="ui-widget-content"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span></td>
							<td class="ui-widget-content"><?= date('d-m-Y',$activity['startdate']);?></td>
							<td class="ui-widget-content" align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
								<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>
							</td>
							<td class="ui-widget-content"><?= $LMS->GetField('mdl_user','CONCAT(firstname, " ",lastname)',$activity['userid']);?></td>
							<td class="ui-widget-content"><?= $LMS->GetField('mdl_user','CONCAT(firstname, " ",lastname)',$activity['contactid']);?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<p align="right">
				<a class="button" href="academy.php?v=reportes&academias[]=<?= $row['id'];?>"><b>Ver todo</b></a>&nbsp;
			</p>
			<form action="academy.php?v=view&id=<?= $row['id'];?>" method="post" name="postForm" align="center" enctype="multipart/form-data">
				<div>
					<span class="w">
						<textarea class="watermark" id="summary" name="summary" style="width:80%;"></textarea>
					</span>
					<div style="height:5px;"></div>
					<div id="activity-button" style="display:none;">
						<input type="hidden" name="action" value="newactivity">
						<input type="hidden" name="academyid" value="<?= $row['id'];?>">
						<input type="text" id="subject" name="subject"/>
						<!--<select name="subject">
							<option value=""></option>
							<?php foreach($asuntos as $asunto):?>
								<option value="<?= $asunto['id']; ?>"><?= $asunto['name']; ?></option>
							<?php endforeach;?>
						</select>-->
						<select name="statusid">
							<?php foreach($activity_status as $status):?>
							<?php ($status['id']=='7')? $SELECTED="selected":$SELECTED="";?>
							<option value="<?= $status['id'];?>" <?=$SELECTED;?>><?= $status['name'];?></option>
							<?php endforeach;?>
						</select>
						<select name="priorityid">
							<?php foreach($activity_priority as $priority):?>
							<option value="<?= $priority['id'];?>"><?= $priority['name'];?></option>
							<?php endforeach;?>
						</select>
						<span class="button_file" onClick='nuevo_adjunto();' class="press" >Adjuntar documento</span>
						<div id="files">
						</div>
						<?php foreach($activity_type as $type):?>
							<button value="<?= $type['id'];?>" type="checkbox" name="typeid" class="<?= $type['icon'];?>" ><?= $type['name'];?></button>
						<?php endforeach;?>
					</div>
				</div>
			</form>
		</div>
	</div>
	</div>
</div>
</div>
