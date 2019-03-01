<script type="text/javascript">

	$(document).ready(function(){

		$("#subject").watermark("Asunto...");
		$("#summary").watermark("Registre su nueva actividad...");		
		
		$( "#notif" ).change(function () {
			var str = "";
			$( "#notif option:selected" ).each(function() {
				str += $( this ).val() + " ";
			});
			$( "#summary" ).val( str );
		});	
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
	<h2 class="ui-widget">
		<a href="contactos.php?v=view&id=<?=$id;?>">
			<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$id);?>
		</a>
		-> Actividades
	</h2>

	<div class="column-c" style="width:700px">
		<div class="portlet">
		<div class="portlet-header">Actividad</div>
		<div class="portlet-content">
				<form action="hd.php?v=listaplus&id=<?= $id;?>" method="post" name="postForm" align="center" enctype="multipart/form-data">
					<div>
						<p>
							Notificaciónes: <select name="notif" id="notif">
									<option value=""></option>							
									<option value="<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$id);?>;
 
Queremos recordarte que el día 5 de (MES) ha vencido el (NUMERO DE CUOTA) pago del curso que estás realizando, te informamos que es necesario que abones el mismo antes de ingresar a clases.

Si realizaste una transferencia bancaria, esperamos el envío del comprobante para poder registrar ese pago.

Gracias,

Fundación Proydesa


http://www.proydesa.org
https://www.facebook.com/proydesa
https://twitter.com/proydesa">Deuda</option>							
									<option value="<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$id);?>;

En el curso que estás realizando en Fundación Proydesa, registramos ausencias a clases, recordá que para poder rendir los finales es necesario tener los parciales aprobados.
 
Podes ponerte al día viniendo Lunes de 15 a 20hs, Miércoles de 15 a 18hs y/o Jueves de 15 a 18hs. De esta manera rendís parciales pendientes, resolvés dudas y no perdés la oportunidad de graduarte.
 
No dudes en contactarte con el Front Desk de la Academia, para poder ayudarte a resolver cualquier inquietud.
 
Esperamos poder contar con tu presencia en la próxima clase!!!
 
Gracias,
 
Fundación Proydesa
 
 
http://www.proydesa.org
https://www.facebook.com/proydesa
https://twitter.com/proydesa">Asistencia</option>							
									<option value="">Inscripción</option>							
							</select>
						</p>
						<span class="w">
							<textarea class="watermark" id="summary" name="summary" style="width: 685px; height: 235px;"></textarea>
						</span>	
						<div style="height:5px;"></div>
						<div id="activity-button">
							<input type="hidden" name="action" value="newactivity">
							<input type="text" id="subject" name="subject" required />
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
							<span class="button_file" onClick='nuevo_adjunto();' class="press" ><b>Adjuntar documento</b></span>
							<div id="files">
							</div>
							<?php foreach($activity_type as $type):?>
								<button value="<?= $type['id'];?>" type="checkbox" name="typeid" class="<?= $type['icon'];?>"><?= $type['name'];?></button>
							<?php endforeach;?>
						</div>
					</div>
				</form>		
	<table class="ui-widget" align="center" style="width:100%;">
			<thead class="ui-widget-header">
				<tr>
					<th style="width:60px;"></th>
					<th style="width:180px;">Asunto</th>
					<th style="width:120px;">Añadido por</th>
					<th style="width:70px;">Fecha</th>
					<th>Prioridad</th>
					<th>Estado</th>
				</tr>
			</thead>
				<tbody class="ui-widget-content">
					<?php foreach($activitys as $activity):?>
					<tr class="ui-widget-content" style="text-align:center;" class="activity" >
							<td align="center"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span>
								(<b><?= $activity['id'];?></b>)
							</td>
							<td align="left" class="press" ondblclick="window.location.href='hd.php?v=details&id=<?=$activity['id'];?>';">
								<a href="hd.php?v=details&id=<?=$activity['id'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<b><?php if ($activity['subject']){ echo $activity['subject']; }else{ echo $activity['typename']; }?></b>			
							</td>
							<td>
								<a href="contactos.php?v=view&id=<?=$activity['userid'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
								</a>
							</td>
							<td><?= date('d-m-Y',$activity['startdate']);?></td>
							<td><?= $activity['priority'];?></td>
							<td><?= $activity['status'];?></td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<p align="center">
			<!--
				<button type="button" class="agregar" onClick="window.location.href='hd.php?v=nuevo';" >Nueva Actividad</button>
			-->
			</p>
		</div>
	</div>

</div>
