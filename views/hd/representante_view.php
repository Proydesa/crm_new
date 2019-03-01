<script>
  $(document).ready(function() {

	$("input, select").change(function(){
		$("#summary").removeClass("required");
	});
	
  	function split( val ) {
			return val.split( /,\s*/ );
		}
		function extractLast( term ) {
			return split( term ).pop();
		}
		
    $("input#autocomplete").bind( "keydown", function( event ) {
				if ( event.keyCode === $.ui.keyCode.TAB &&
						$( this ).data( "autocomplete" ).menu.active ) {
					event.preventDefault();
				}
			}).autocomplete({
   			source: function( request, response ) {
					$.getJSON( "autocomplete.php", {
						term: extractLast( request.term )
					}, response );
				},
				search: function() {
					// custom minLength
					var term = extractLast( this.value );
					if ( term.length < 2 ) {
						return false;
					}
				},
				focus: function() {
					// prevent value inserted on focus
					return false;
				},
				select: function( event, ui ) {
					var terms = split( this.value );
					// remove the current input
					terms.pop();
					// add the selected item
					terms.push( ui.item.value );
					// add placeholder to get the comma-and-space at the end
					terms.push( "" );
					this.value = terms.join( ", " );
          $('#estado').append(ui.item.label + " (" + ui.item.value + ") " + "tiene un username de: " + ui.item.estado + "<br/ >");
//          $('#email').val($('#email').val() + "," + ui.item.email);
					return false;
				}

		});
  });
</script>
<div class="column-c" style="width:950px;">
	<div class="portlet">
		<div class="portlet-header">Incidente N° <?= $activity['id'];?></div>
		<div class="portlet-content">
		<form class="form-view validate" action="hd.php?v=representante-update&id=<?=$activity['id'];?>" method="post" enctype="multipart/form-data" id="activitynew" name="activitynew" class="ui-widget"> 
			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
						<tr style="height: 30px;">
							<th colspan="2"><b>Información de contacto</b></th>
							<th colspan="2"><b>Clasificación</b></th>
						</tr>
				</thead>
				<tbody class="ui-widget-content" style="text-align:right;">
						<tr style="height: 30px;">
							<td width="15%"><b>Creado por:</b></td>
							<td width="35%" align="center">
								<a href="contactos.php?v=view&id=<?=$activity['userid'];?>">
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['userid']);?>
								</a>
							</td>
							<td width="15%"><b>Categoria:</b></td>
							<td width="35%">
								<select name="categoryid" >
									<?php foreach($activity_category as $category):?>
										<?php ($category['id']==$activity['category'])? $SELECTED="selected":$SELECTED="";?>
										<option value="<?= $category['id'];?>" <?= $SELECTED;?>><?= $category['name'];?></option>
									<?php endforeach;?>
								</select>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td width="15%"><b><label for="email">Contacto/s:</label></b></td>
							<td width="35%" align="center">
								<a href="contactos.php?v=view&id=<?=$activity['contactid'];?>">							
									<?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$activity['contactid']);?>
								</a>
								<input name="contactid" type="hidden" value="<?= $activity['contactid'];?>"/>
							</td>
							<td width="15%"><b>Estado:</b></td>
							<td width="35%">
								<select name="statusid"  >
									<?php foreach($activity_status as $status):?>
										<?php ($status['id']==$activity['statusid'])? $SELECTED="selected":$SELECTED="";?>
										<option value="<?= $status['id'];?>" <?= $SELECTED;?>><?= $status['name'];?></option>
									<?php endforeach;?>
								</select>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Asignado a:</b></td>
							<td width="35%">
								<select name="assignto" >
									<?php echo "<option value='0'>"; ?>
									<?php foreach($assigns_users as $auser):?>
										<?php ($auser['userid']==$activity['assignto'])? $SELECTED="selected":$SELECTED="";?>
										<option value="<?= $auser['userid'];?>" <?= $SELECTED;?>>
											<span  style="font-weight:bold"><?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$auser['userid']);?></span>
											(<?= $auser['roles'];?>)
										</option>
									<?php endforeach;?>
								</select>								
							</td>
							<td width="15%"><b>Prioridad:</b></td>
							<td width="35%">
								<select name="priorityid" >
									<?php foreach($activity_priority as $priority):?>
										<?php ($priority['id']==$activity['priorityid'])? $SELECTED="selected":$SELECTED="";?>
										<option value="<?= $priority['id'];?>" <?= $SELECTED;?>><?= $priority['name'];?></option>
									<?php endforeach;?>
								</select>
							</td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Academia:</b></td>
							<td width="35%">
								<select name="academyid" >
									<?php echo "<option value='0'>"; ?>
									<?php foreach($academias as $academia):?>
										<?php ($academia['id']==$activity['academyid'])? $SELECTED="selected":$SELECTED="";?>
										<option value="<?= $academia['id'];?>" <?= $SELECTED;?>><?= $academia['name'];?></option>
									<?php endforeach;?>
								</select>
							</td>
							<td></td>
							<td align="left">
							</td>
						</tr>
						<tr class="press" onClick="$('.detalle').toggle();"><th colspan="4" class="ui-widget-header">Tiempos</th></tr>
						<tr class="detalle" style="height: 30px;">
							<td><b>Fecha de creación:</b></td>
							<td>
								<?= date('d-m-Y',$activity['startdate']);?>
							</td>
							<td><b>Fecha de vencimiento:</b></td>
							<td>
								<?= ($activity['duedate'])?date('d-m-Y',$activity['duedate']):"-";?>
							</td>
						</tr>
						<tr class="detalle" style="height: 30px;">
							<td><b>Tiempo consumido:</b></td>
							<td>
								<?=$total_timespent;?> (minutos)
							</td>
							<td><b>Fecha de cierre:</b></td>
							<td>
								<?= ($activity['enddate'])?date('d-m-Y',$activity['enddate']):"-";?>
							</td>						
						</tr>
						<tr><th colspan="4" class="ui-widget-header">Informaci&oacute;n del incidente</th></tr>
						<tr style="height: 20px;">
							<td><b>Titulo:</b></td>
							<td colspan="3"  align="left"><blockquote style="padding-top:5px;"><?= $activity['subject'];?></blockquote></td>
						</tr>
						<tr style="height: 30px;">
							<td  style="padding-top:8px; vertical-align:top;"><b>Descripci&oacute;n:</b></td>						
							<td colspan="3" align="left"><blockquote style="padding-top:8px;width:600px;"><?= $activity['summary'];?></blockquote></td>
						</tr>
						<tr style="height: 30px;">
							<td><b>Tipo:</b></td>	
							<td align="left"><blockquote style="padding-top:8px;"><span class="ui-icon ui-icon-<?= $activity['icon'];?>" style="float:left;"></span>
								&nbsp;&nbsp;<?= $activity['type'];?>
								</blockquote>
							</td>
							<td><b>Adjuntos:</b></td>	
							<td align="left">
								<?php if ($activity['files']):?>								
									<?php foreach($activity['files'] as $file):?>
										&nbsp;
										<a href="file.php?userid=<?= $activity['userid'];?>&file=<?= $file['file'];?>" target="_blank"><?= $file['file'];?></a>
										&nbsp;
									<?php endforeach;?>
								<?php endif;?>							
							</td>
						</tr>
						<?php if ($H_USER->has_capability('activity/update')):?>

							<tr><th colspan="4" class="ui-widget-header">Información adicional</th></tr>
							<?php foreach($activity_parent as $parent):?>					
							<tr style="height: 20px;">
								<td colspan="4" align="left">
									<span class="ui-icon ui-icon-<?= $parent['icon'];?>" style="float:left;"></span>
									<b> [<?= date('d-m-Y G:i A',$parent['startdate']);?> - <?= $LMS->GetField('mdl_user','CONCAT(firstname," ",LEFT(lastname,1))',$parent['userid']);?>]
									<?php if($parent['private']>0) echo " - PRIVATE";?>
									</b> <?= $parent['subject'];?>
								</td>
							</tr>
							<?php if ($parent['summary']):?>
							<tr>
								<td colspan="4">
									<blockquote align="left"><?= $parent['summary'];?></blockquote>
								</td>
							</tr>
							<?php endif; ?>
							<tr style="height: 30px;">
										<td>
											<?php if ($parent['files']):?>
												<b>Adjuntos:</b>
											<?php endif;?>
										</td>	
										<td align="left">
											<?php if ($parent['files']):?>								
												<?php foreach($parent['files'] as $file):?>
													&nbsp;
													<a href="file.php?userid=<?= $parent['userid'];?>&file=<?= $file['file'];?>" target="_blank"><?= $file['file'];?></a>
													&nbsp;
												<?php endforeach;?>
											<?php endif;?>
										</td>
										<td colspan="2">
											<span align="right">TC: <?= $parent['timespent'];?> (m) | Estado => <?= $parent['status'];?> | Prioridad => <?= $parent['priority'];?> </span>
										</td>
									</tr>
							<?php endforeach;?>
							<tr style="height: 10px;">
							</tr>					
							<tr style="height: 30px;">
								<td><b>Titulo:</b></td>
								<td  align="left">
									<input name="subject" type="text" class="required" value="Re: <?= $activity['subject'];?>"/>
								</td>
								<td><b>Privado:</b></td>
								<td align="left">
									<input type="checkbox" name="private" value="1" />
								</td>
							</tr>
							<tr style="height: 30px;">
								<td width="15%" style="padding-top:8px; vertical-align:top;"><b>Descripci&oacute;n:</b></td>						
								<td  colspan="2"  align="center"><textarea class="wysiwyg required" name="summary" id="summary" cols="80" rows="10"></textarea></td>
								<td align="left"  style="padding:8px; vertical-align:top;">
									<p>Tiempo	<input name="timespent" type="text" class="required" value="00" style="width:80px;"/> minutos</p>
									<b><span onClick="$('#files').append('<input type=file name=archivos[] class=press  style=height:30px;><br/><br/>');" class="press" >Agregar adjuntos</span>: </b>
									<br/>
									<br/>
									<div id="files">
									</div>
								</td>
							</tr>
							<tr style="height: 30px;">
								<td colspan="4" align="left"></td>						
							</tr>
						<?php endif;?>			
						</tbody>
				</table>
				<?php if ($H_USER->has_capability('activity/update')):?>
				<div align="center">
						<input type="hidden" name="id" value="">
						<input type="hidden" name="parent" value="<?= $activity['id'];?>">
						<input type="hidden" name="typeid" value="5">						
						<button type="button" class="add" onclick="$('.validate').validate();$('#activitynew').submit();">Actualizar incidente</button>
						<button type="button" class="button-borrar" onClick="$('.form-view')[0].reset();">Cancelar</button>
				</div>
				<?php endif;?>
			</form>
		</div>
	</div>
</div>