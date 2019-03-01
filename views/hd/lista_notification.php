<div class="ui-widget" align="left">
	<h2 class="ui-widget">
		<a href="contactos.php?v=view&id=<?=$id;?>">
			<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$id);?>
		</a>
		-> Notificaciones
	</h2>

	<div class="column-c" style="width:700px">
		<h4>Usuarios:</h4>
		<?php
		if(count($rowusers)):
			foreach ($rowusers as $ku=>$vu):
				echo $vu['username'].(count($rowusers)==$ku+1 ? '' : ', ');
			endforeach;
		endif;
		?>

	</div>

	<div class="column-c" style="width:700px">
		<div class="portlet">
		<div class="portlet-header">Actividad</div>
		<div class="portlet-content">
				<form action="hd.php?v=lista_notification&id=<?= $idUsers; ?>" method="post" name="postForm" align="center" enctype="multipart/form-data" >
					
					<p>
						Notificaciones: 
						<select name="notif" id="notif">
								<option value=""></option>							
								<option value="deuda">Deuda</option>							
								<option value="asistencia">Asistencia</option>							
								<option value="">Inscripción</option>							
						</select>
					</p>

					<span class="w">
						<textarea class="watermark" id="summary" name="summary" style="width: 685px; height: 235px;"></textarea>
					</span>	

					<div style="height:5px;"></div>
					<div id="activity-button">
						<input type="hidden" name="action" value="newactivity">
						<input type="text" id="subject" name="subject" required style="width: 685px;" /><br /><br />
						
						<?php foreach($activity_type as $type):?>
							<button value="<?= $type['id'];?>" type="checkbox" name="typeid" class="<?= $type['icon'];?>"><?= $type['name'];?></button>
						<?php endforeach;?>
					</div>
					
				</form>		
		</div>
	</div>

</div>


<p data-toggle="deuda" class="hidden">Queremos recordarte que el día 5 de (MES) ha vencido el (NUMERO DE CUOTA) pago del curso que estás realizando, te informamos que es necesario que abones el mismo antes de ingresar a clases.

Si realizaste una transferencia bancaria, esperamos el envío del comprobante para poder registrar ese pago.

Gracias,

Fundación Proydesa


http://www.proydesa.org
https://www.facebook.com/proydesa
https://twitter.com/proydesa
</p>

<p data-toggle="asistencia" class="hidden">En el curso que estás realizando en Fundación Proydesa, registramos ausencias a clases, recordá que para poder rendir los finales es necesario tener los parciales aprobados.

Podes ponerte al día viniendo Lunes de 15 a 20hs, Miércoles de 15 a 18hs y/o Jueves de 15 a 18hs. De esta manera rendís parciales pendientes, resolvés dudas y no perdés la oportunidad de graduarte.
 
No dudes en contactarte con el Front Desk de la Academia, para poder ayudarte a resolver cualquier inquietud.
 
Esperamos poder contar con tu presencia en la próxima clase!!!
 
Gracias,
 
Fundación Proydesa
 
 
http://www.proydesa.org
https://www.facebook.com/proydesa
https://twitter.com/proydesa
</p>


<script>

$(document).ready(function(){

	$("#subject").watermark("Asunto...");
	$("#summary").watermark("Registre su nueva actividad...");		
	
	$( "#notif" ).change(function () {
		var type = $(this).val();
		$( "#summary" ).val( $('[data-toggle="'+type+'"]').text() );
	});	

	

});
</script>