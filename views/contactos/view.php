<script type="text/javascript">
	$(document).ready(function(){

		$(".button-editar").click(function(){
			$('.form-view *').removeAttr('readonly');
			$('.form-view select').removeAttr('disabled');
			$('.edit').toggle();
		});

		$(".cancel").click(function(){
			$('.form-view input').attr('readonly','readonly');
			$('.form-view select').attr('disabled','disabled');
			$('.form-view textarea').attr('readonly','readonly');
			$('.edit').toggle();
			$('.form-view')[0].reset();
		});
		$('#activity').html("<p align='center'><img src='themes/cargando.gif' border='0' valign='middle' /> Cargando...</p>").load('hd.php?v=widget_activity&id=<?= $row["id"];?>');
		$('#fnacimiento').datepicker({	changeMonth: true,	changeYear: true, yearRange: '1950:2000', dateFormat: 'dd-mm-yy'});
	});
</script>

<div class="ui-widget" align="left">
	<div class="column" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Datos</div>
			<div class="portlet-content">
				<form class="form-view validate" action="contactos.php?v=actualizar_usuario&id=<?= $row['id'];?>" method="post" name="inscripcion" class="ui-widget">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content">
							<tr style="height: 20px;">
								<td class="ui-widget-content" align="right"><b>DNI:</b></td>
								<td class="ui-widget-content">
									<input readonly name="username" type="text" value="<?= $row['username'];?>" class="required"/>
								</td>
								<td align="center" class="ui-widget-content"><b>LMSID:</b> <a href="<?= $urlLMS;?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:right;"></span></a><?= $row['id'];?></td>
								<td align="center" class="ui-widget-content">
									 <b>ACID / Username:</b> <?= $row['acid'];?> / <?= $row['user_cisco'];?>
								</td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Apellido:</b></td>
								<td class="ui-widget-content">
									<input readonly name="lastname" type="text" value="<?= $row['lastname'];?>"/>
								</td>
								<td align="center" colspan="2"><b>Información de contacto</b></td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Nombre:</b></td>
								<td class="ui-widget-content">
									<input readonly name="firstname" type="text" value="<?= $row['firstname'];?>"/>
								</td>
								<td align="right" class="ui-widget-content"><b>E-Mail:</b></td>
								<td class="ui-widget-content">
									<input readonly name="email" type="text" value="<?= $row['email'];?>" class="required email"/>
								</td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Fecha de Nacimiento:</b></td>
								<td class="ui-widget-content">
									<input readonly name="fnacimiento" id="fnacimiento" type="text" value="<?= date('d-m-Y',$row['fnacimiento']);?>"/>
								</td>
								<td align="right" class="ui-widget-content"><b>Tel&eacute;fono:</b></td>
								<td class="ui-widget-content">
									<input readonly name="phone1" type="text" class="digits" value="<?= $row['phone1'];?>"/>
								</td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Pa&iacute;s:</b></td>
								<td class="ui-widget-content">
									<select name="country" class="required" disabled>
										<option value="">Seleccione pa&iacute;s</option>
										<?php foreach($HULK->countrys as $count => $country):?>
											<?php ($count==$row['country'])? $SELECTED="selected":$SELECTED="";?>
											<option value="<?= $count;?>" <?= $SELECTED;?>><?= $country;?></option>
										<?php endforeach;?>
									</select>
								</td>
								<td align="right" class="ui-widget-content"><b>Celular:</b></td>
								<td class="ui-widget-content">
									<input readonly name="phone2" type="text" class="digits" value="<?= $row['phone2'];?>"/>
								</td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Localidad:</b></td>
								<td class="ui-widget-content">
									<input readonly name="city" type="text" value="<?= $row['city'];?>"/>
								</td>
									<td align="right" class="ui-widget-content"><b>No mandar spam:</b></td>
									<td class="ui-widget-content"><input readonly type="checkbox" name="noquierospam" value="1" <?php echo ($row['noquierospam']>0)? "checked" : "";?> /></td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Direcci&oacute;n:</b></td>
									<td class="ui-widget-content">
										<input readonly name="address" type="text" value="<?= $row['address'];?>"/>
									</td>
									<td align="right" class="ui-widget-content"><b>Primer acceso:</b></td>
									<td class="ui-widget-content"><?php echo ($row['firstaccess']>0)? show_fecha($row['firstaccess'],"") : "Nunca";?></td>
									</tr>
								</td>
							</tr>

							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>C&oacute;digo Postal:</b></td>
										<td class="ui-widget-content"><input readonly name="cp" type="text" value="<?= $row['cp'];?>"/></td>
								<td align="right" class="ui-widget-content"><b>&Uacute;ltimo acceso:</b></td>
								<td class="ui-widget-content"><?php echo ($row['lastaccess']>0)? show_fecha($row['lastaccess'],"") : "Nunca";?></td>
							</tr>
							<tr style="height: 20px;">
								<td align="right" class="ui-widget-content"><b>Sexo:</b></td>
								<td class="ui-widget-content">
									<select name="sexo" disabled>
										<option value="M" <?php if($row['sexo']=='M') echo "selected" ?>>Masculino</option>
										<option value="F" <?php if($row['sexo']=='F') echo "selected" ?>>Femenino</option>
										<option value="N" <?php if($row['sexo']=='N') echo "selected" ?>>Prefiero no decirlo</option>
									</select>
								</td>
								<?php if($H_USER->has_capability('role/edit')):?>
								<td align="right" class="ui-widget-content"><b>Role (dentro del CRM):</b></td>
								<td class="ui-widget-content">
									<select name="role" disabled>
										<option value="">Sin role</option>
										<?php foreach($roles as $role):?>
											<?php if($role['id']==$row['roleid']):?>
												<option value="<?= $role['id'];?>" <?= "selected";?>><?= $role['name'];?></option>
											<?php else:?>
												<option value="<?= $role['id'];?>"><?= $role['name'];?></option>
											<?php endif;?>
										<?php endforeach;?>
									</select>
								</td>
							<?php else:?>
								<td colspan="2"></td>
						<?php endif;?>
							</tr>
							<tr>
								<td align="right" class="ui-widget-content"><b>Observaciones:</b></td>
								<td class="ui-widget-content" colspan="3" style="padding:5px;" >
								<textarea readonly="readonly" id="obs" name="obs" style="width:100%; overflow: auto;"><?= $row['obs'];?></textarea></td>
							</tr>
						</tbody>
					</table>
					<?php if($H_USER->has_capability('contact/edit')): ?>
						<div align="center">
							<span class="edit">
								<a class="agregar" href="contactos.php?v=pagos&id=<?= $row['id'];?>"><b>Cobrar Cuota</b></a>
								<button type="button" class="agregar" onClick="window.location.href='contactos.php?v=inscribir_usuario&id=<?= $row['id'];?>';" ><b>Inscribir</b></button>
								<!--<button type="button" class="button-editar" onClick="$('.form-view *').removeAttr('disabled');$('.edit').toggle();">Editar contacto</button>-->
								<button type="button" class="button-editar"><b>Editar contacto</b></button>
							</span>
							<span class="edit" style="display:none;">
								<button type="button" class="button" onClick="$('.form-view').submit();" ><b>Guardar contacto</b></button>
								<!--<button type="button" class="button" onClick="$('.form-view input,.form-view select').attr('disabled','disabled');$('.edit').toggle();$('.form-view')[0].reset();">Cancelar</button>-->
								<button type="button" class="button cancel"><b>Cancelar</b></button>
							</span>
							<?php if ($H_USER->has_capability('site/loginas')):?>
								<span class="button" onClick="window.location.href='contactos.php?v=view&id=<?= $row['id'];?>&loginas=<?= $row['id'];?>';"><b>Entrar como</b></span>
							<?php endif;?>
						</div>
					<?php endif;?>
				</form>
			</div>
		</div>
	</div>

	<div class="column" style="width:35%">
		<div class="portlet">
			<div class="portlet-header">Cuotas</div>
			<div class="portlet-content">
				<?php if($cuotas): ?>
					<table class="ui-widget" align="center" style="width:100%;">
						<thead class="ui-widget-header">
							<tr>
								<th>Curso</th>
								<th>Cuota 1</th>
								<th>Cuota 2</th>
								<th>Cuota 3</th>
								<th>Cuota 4</th>
								<th>Cuota 5</th>
								<th>Libro</th>
								<th>Per&iacute;odo</th>
							</tr>
						</thead>
						<tbody class="ui-widget-content">
							<?php foreach($cuotas as $cuota):?>
								<tr style="height: 20px;<?php if($cuota['baja']>0) echo "text-decoration:line-through;"; ?>" title="<?php if($cuota['baja']>0) echo "Baja: ".$H_DB->GetField("h_bajas", "detalle", $cuota['baja']); ?>">
									<?php if ($coursename = $LMS->GetField('mdl_course','shortname',$cuota['id'])): ?>
										<td class="ui-widget-content"><b><?= $coursename; ?><b></td>
										<?php else:?>
											<td class="ui-widget-content"><b><?= $H_DB->h_gradebooks('modeloname','modeloid',$cuota['id']); ?><b></td>
									<?php endif;?>
									<?php for($x=1;$x<6;$x++):?>
										<?php if(isset($cuota['cuota'.$x]) && $cuota['cuota'.$x]<='0'):?>
											<td class="ui-widget-content bg-success" align="center"><b>OK</b></td>
										<?php elseif($cuota['cuota'.$x]==0):?>
											<td class="ui-widget-content"></td>
										<?php else:?>
											<td class="ui-widget-content bg-danger" align="center"><?= $cuota['cuota'.$x]; ?></td>
										<?php endif;?>
									<?php endfor;?>
									<?php if($cuota['cuota0']=='0'):?>
										<td class="ui-widget-content bg-success" align="center"><b>OK</b></td>
									<?php else:?>
										<td class="ui-widget-content" align="center"><?= $cuota['cuota0']; ?></td>
									<?php endif;?>
									<td class="ui-widget-content" align="center"><?= $cuota['periodo']; ?></td>
								</tr>
							<?php endforeach;?>
						</tbody>
					</table>
				<?php endif; ?>
				<p><b>Saldo:</b> $ <?= $row['saldo'];?></p>
				<div align="center">
					<?php if ($H_USER->has_capability('contact/edit')):	?>
						<button type="button" align="center" class="cancel" onClick="window.location.href='contactos.php?v=cancel_insc&id=<?= $row['id'];?>';" ><b>Cancelar Inscripci&oacute;n</b></button>
						<button type="button" align="center"class="button" onClick="window.location.href='contactos.php?v=cobro&id=<?= $row['id'];?>';" ><b>Cobrar Especial</b></button>

					<?php endif; ?>
					<br/>
				</div>
			</div>
		</div>

			<div class="portlet">
				<div class="portlet-header">Academias</div>
				<div class="portlet-content"  style="overflow: auto; max-height: 250px;">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content">
							<?php if($user_roles): ?>
							<?php foreach($user_roles as $urole): ?>
								<tr style="height: 20px;" class="ui-widget-content">
									<td class="ui-widget-content">
										<b><?= $urole['category'];?></b></br><?= $urole['rol']; ?>
									</td>
								</tr>
							<?php endforeach; ?>
							<?php endif; ?>
						</tbody>
					</table>
				</div>
				<p align="right">
					<?php if ($H_USER->has_capability('contact/lmsrole')):?>
						<a class="button" href="contactos.php?v=capacidades&id=<?= $row['id'];?>"><b>Configurar certificaciones</b></a>&nbsp;
						<a class="button" href="contactos.php?v=roles&id=<?= $row['id'];?>"><b>Configurar Roles Globales</b></a>&nbsp;
					<?php endif; ?>
				</p>
			</div>
		</div>


	<div class="column" style="width:65%">
		<div class="portlet">
			<div class="portlet-header">Actividad</div>
			<div class="portlet-content"  id="activity">
			</div>
		</div>

		<?php if ($cursos): ?>
			<div class="portlet">
				<div class="portlet-header">Cursos: Estudiante</div>
				<div class="portlet-content"  style="overflow: auto; max-height: 300px;">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content">
							<?php foreach($cursos as $curso):?>
								<?php if ($curso['roleid']==5): ?>
									<tr style="height: 20px;">
										<?php if($curso['baja']['id'] > 0){ $class="ui-state-error"; }else{ $class="ui-widget-content"; } ?>
										<td class="ui-widget-content press <?= $class; ?>">
											<a href="courses.php?v=view&id=<?= $curso['id'];?>" target="_blank">
												<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
											</a><b><?= $curso['course'];?></b> (<?= $curso['model'];?>)
											<?php if (($curso['periodo'] == $HULK->periodo || in_array($curso['periodo'],explode(",",$HULK->periodo_insc))) && ($H_USER->has_capability('course/change'))):?>
												<?php if(($curso['periodo'] == $HULK->periodo || in_array($curso['periodo'],explode(",",$HULK->periodo_insc))) && $curso['baja']['id'] > 0): ?>
													<b>BAJA</b>
													<?php if($curso['baja']['detalle'] != "Baja automática por cambio de comisión"): ?>
															<a class="alta" href="contactos.php?v=alta&bajaid=<?= $curso['baja']['id']; ?>">Dar de Alta</a>
													<?php endif; ?>
												<?php else: ?>
													<?php if($LMS->user_status($row['id'],$curso['id']) != "I"): ?>
														<a href="contactos.php?v=comision_change&id=<?= $row['id']?>&c=<?= $curso['id'];?>&insc_id=<?= $curso['insc_id'];?>" class="cambio_comi">Cambio de comisi&oacute;n</a>
														<a class="baja" href="contactos.php?v=baja&user=<?= $row['id']; ?>&comi=<?= $curso['id'];?>">Dar de Baja</a>
													<?php endif; ?>
												<?php endif; ?>
											<?php endif;?>
										</td>
										<td class="<?= $class; ?>" align="center" >
											<b><?= $curso['periodo'];?></b>
										</td>
									</tr>
									<tr>
										<td  class="ui-widget-content">
											<table class="ui-widget" style="width:100%">
												<tr style="font-size: 10px;">
													<?php foreach($notas[$curso['id']] as $nota):?>
														<td><?= $nota['itemname'];?></td>
													<?php endforeach;?>
												</tr>
												<tr>
													<?php foreach($notas[$curso['id']] as $nota):?>
														<td><?= number_format($nota['nota'],2);?></td>
													<?php endforeach;?>
												</tr>
											</table>
										</td>
										<td class="ui-widget-content">
											<table class="ui-widget <?= $grades_class[$LMS->user_status($row['id'],$curso['id'])];?>" style="width:100%">
												<tr>
													<td align="center">Graduaci&oacute;n</td>
												</tr>
												<tr>
													<td align="center"><?= $LMS->user_status($row['id'],$curso['id']);?></td>
												</tr>
											</table>
										</td>
									</tr>
									<?php if($certificados[$curso['id']]): ?>
										<tr style="height: 20px;">
											<td colspan="2" class="ui-widget-content" align="right">
												Certicado emitido el <?= date("d/m/Y",$certificados[$curso['id']]['timecreate']);?> por  <?= $certificados[$curso['id']]['usercreator'];?>
											</td>
										</tr>
									<?php endif; ?>
									<?php if($curso['baja']['detalle']): ?>
										<tr style="height: 20px;">
											<td colspan="2" class="ui-widget-content" align="right">
												<b>Baja realizada por</b> <?= show_name($curso['baja']['user']);?> <b> el </b> <?= show_fecha($curso['baja']['date']);?>:</b> <?= $curso['baja']['detalle'];?>
											</td>
										</tr>
									<?php endif; ?>
								<?php endif;?>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($cursos_ins): ?>
			<div class="portlet">
				<div class="portlet-header">Cursos: Instructor</div>
				<div class="portlet-content"  style="overflow: auto; max-height: 300px;">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content">
							<?php foreach($cursos_ins as $curso):?>
								<?php if ($curso['roleid']!=5): ?>
									<tr style="height: 20px;">
										<td class="press" ondblclick="window.location.href='courses.php?v=view&id=<?= $curso['id']; ?>';">
											<a href="courses.php?v=view&id=<?= $curso['id']; ?>" target="_blank">
												<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
											</a><b><?= $curso['course'];?></b>
										</td>
										<td class="ui-widget-content"><?= $curso['model']; ?></td>
										<td class="ui-widget-content"><?= $curso['rol']; ?></td>
									</tr>
								<?php endif; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>
	</div>
	<?php
		$arrStatus = array('E'=>'bg-primary','P'=>'bg-success','I'=>'bg-danger','F'=>'bg-warning');
		$status	= array (''=>'E','0.00000'=>'E','1.00000'=>'I','2.00000'=>'F','3.00000'=>'P');		
	?>
	<div class="column" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Gradebooks Historicos</div>
			<div class="portlet-content">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<tr style="height: 20px;">
							<td class="ui-widget-content">Comisión</td>
							<td class="ui-widget-content">Curso</td>
							<td class="ui-widget-content">Notas</td>
							<td class="ui-widget-content">Final</td>
							<td class="ui-widget-content">Instructor/es</td>
							<td class="ui-widget-content">Origen</td>
							<td class="ui-widget-content">Periodo/Inicio</td>							
							<td class="ui-widget-content">Graduación</td>							
						</tr>
					</thead>
					<tbody class="ui-widget-content">

						<?php foreach($gradebooks_lms as $row):?>
							<tr style="height: 20px;">
								<td class="ui-widget-content">
									<a href="courses.php?v=view&id=<?= $row['comisionid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><b><?= $row['comisionname'];?></b>
								</td>
								<td class="ui-widget-content"><?= $row['modeloname'];?></td>
								<td class="ui-widget-content"><?= $row['notas'];?></td>
								<td class="ui-widget-content"><?= $row['final'];?></td>
								<td class="ui-widget-content"><?= $row['docente'];?></td>
								<td class="ui-widget-content"><?= $row['base_origen'];?></td>
								<td class="ui-widget-content" align="center"><b><?= $row['periodo'];?></b></td>
								<td class="<?= $arrStatus[$status[$row['graduacion']]]; ?> textCenter"><?= $status[$row['graduacion']]; ?></td>								
							</tr>
						<?php endforeach; ?>
						<?php foreach($gradebooks_bajador as $row):?>
								<tr style="height: 20px;">
									<td class="ui-widget-content"><b><?= $row['comisionname'];?></b></td>
									<td class="ui-widget-content"><?= $row['modeloname'];?></td>
									<td class="ui-widget-content"><?= $row['notas'];?></td>
									<td class="ui-widget-content"><?= $row['final'];?></td>
									<td class="ui-widget-content"><?= $row['docente'];?></td>
									<td class="ui-widget-content"><?= $row['base_origen'];?></td>
									<td class="ui-widget-content textCenter"><b><?= $row['fecha_inicio'];?></b></td>
									<td class="<?= $arrStatus[$row['graduacion']]; ?> textCenter"><?= $row['graduacion']; ?></td>							
								</tr>
						<?php endforeach; ?>
						<?php foreach($gradebooks_gesteval as $row):?>
								<tr style="height: 20px;">
									<td class="ui-widget-content"><b><?= $row['comisionname'];?></b></td>
									<td class="ui-widget-content"><?= $row['modeloname'];?></td>
									<td class="ui-widget-content"><?= $row['notas'];?></td>
									<td class="ui-widget-content"><?= $row['final'];?></td>
									<td class="ui-widget-content"><?= $row['docente'];?></td>
									<td class="ui-widget-content"><?= $row['base_origen'];?></td>
									<td class="ui-widget-content textCenter"><b><?= $row['fecha_inicio'];?></b></td>
									<td class="<?= $arrStatus[$row['graduacion']]; ?> textCenter"><?= $row['graduacion']; ?></td>								
								</tr>
						<?php endforeach; ?>
						<?php foreach($gradebooks_oracle as $row):?>
								<tr style="height: 20px;">
									<td class="ui-widget-content"><b><?= $row['comisionname'];?></b></td>
									<td class="ui-widget-content"><?= $row['modeloname'];?></td>
									<td class="ui-widget-content"><?= $row['notas'];?></td>
									<td class="ui-widget-content"><?= $row['final'];?></td>
									<td class="ui-widget-content"><?= $row['docente'];?></td>
									<td class="ui-widget-content"><?= $row['base_origen'];?></td>
									<td class="ui-widget-content textCenter"><b><?= $row['fecha_inicio'];?></b></td>
									<td class="<?= $arrStatus[$row['graduacion']]; ?> textCenter"><?= $row['graduacion']; ?></td>						
								</tr>
						<?php endforeach; ?>
						<?php foreach($gradebooks_sun as $row):?>
								<tr style="height: 20px;">
									<td class="ui-widget-content"><b><?= $row['comisionname'];?></b></td>
									<td class="ui-widget-content"><?= $row['modeloname'];?></td>
									<td class="ui-widget-content"><?= $row['notas'];?></td>
									<td class="ui-widget-content"><?= $row['final'];?></td>
									<td class="ui-widget-content"><?= $row['docente'];?></td>
									<td class="ui-widget-content"><?= $row['base_origen'];?></td>
									<td class="ui-widget-content textCenter"><b><?= $row['fecha_inicio'];?></b></td>
									<td class="<?= $arrStatus[$row['graduacion']]; ?> textCenter"><?= $row['graduacion']; ?></td>								
								</tr>
						<?php endforeach; ?>

					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
