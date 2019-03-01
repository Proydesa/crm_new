<!-- alan  -->
<center>	
	<div style="width:30%" class="portlet">
		<div class="portlet-header">Seleccione Encuesta</div>
			<div class="portlet-content">
				<form action="reportes.php?v=encuestas" method="post">
					<select  name="encuestas" id="encuestas">
						<?php foreach($encuestas as $encuesta):?>
						<option value="<?= $encuesta['id'];?>" <?php if($encuesta['id']==$encsel) echo 'selected="selected"'; ?>><?= $encuesta['title'];?></option>
						<?php endforeach;?>
					</select>
					<input type="submit" value="Enviar" />
				</form>
			</div>			
		</div>
	</div>
</center>
<div class="column" style="width:100%">	
	<div class="portlet">
		<div style="height: 20px;" class="portlet-header">Preguntas y respuestas</div>
			<div class="portlet-content" style="overflow:auto;max-height:500px;">
				<table id="listado" class="ui-widget">
					<tr style="height: 20px;" class="ui-widget-header">	
						<th>Usuario</th>
						<th>Nombre completo</th>	
						<?php if($preguntas!=""){ ?>
							<?php foreach($preguntas as $preguntitas):?>
								<td><?= $LMS->GetField('mdl_questionnaire_question','content',$preguntitas['idpregunta']);?></td>		
							<?php endforeach;?>	
						<?php } ?>		
					</tr>
					<tr class="ui-widget-content">	
						<?php if($respuestas!=""){ ?>
							<?php foreach($respuestas as $user => $respuesta):?>		
							<tr style="height: 20px;" class="ui-widget-content">
								<td><?= $LMS->GetField('mdl_user','username',$user);?></td>
								<td>
								<a href="contactos.php?v=view&id=<?= $LMS->GetField('mdl_user','id',$user); ?>" target="_blank">
												<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
											</a>
								<b><?= $LMS->GetField('mdl_user',"CONCAT(firstname,' ',lastname)",$user);?></b></td>
								<?php foreach($respuesta as $respuestita => $valor):?>	
									<td><?= $valor;?></td>
								<?php endforeach;?>		
							</tr>	
							<?php endforeach;?>
						<?php } ?>
					</tr>					
				</table>
			</div>
		</div>	
	</div>
<!-- alan  -->	