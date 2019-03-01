<script>
	$(document).ready(function(){
		$('.cobrar').click(function(){
			$("#comp_actual").val($(this).attr("id"));
			$("#dialog-confirm4").dialog('open');
			return false;
		});

		$("#dialog-confirm4").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					location.href=("grupos.php?v=cobrar&id="+$("#comp_actual").val()+"&grupo="+<?= $grupo['id']; ?>);
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

		$('.anular').click(function(){
			$("#comp_actual").val($(this).attr("id"));
			$("#dialog-confirm2").dialog('open');
			return false;
		});

		$("#dialog-confirm2").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					location.href=("grupos.php?v=anular&id="+$("#comp_actual").val()+"&grupo="+<?= $grupo['id']; ?>);
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

		$('.cancelar').click(function(){
			$("#comp_actual").val($(this).attr("id"));
			$("#dialog-confirm3").dialog('open');
			return false;
		});

		$("#dialog-confirm3").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					location.href=("grupos.php?v=cancelar&id="+$("#comp_actual").val()+"&grupo="+<?= $grupo['id']; ?>);
					$(this).dialog("close");
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});

	});
</script>

<div id="dialog-confirm2" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por anular el comprobante. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div id="dialog-confirm3" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por cancelar el comprobante. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div id="dialog-confirm4" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por cobrar el comprobante. &iquest;Desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>

<div class="ui-widget" align="left">
<div class="column" style="width:40%">
	<div class="portlet">
		<div align="center"><h2><?= $grupo['name'];?></h2></div>
		<div class="portlet-content" style="overflow: auto; max-height: 350px;">
			<table class="ui-widget" align="center" style="width:100%;">
				<tbody>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right" width="80px"><b>Direcci&oacute;n:</b></td>
							<td class="ui-widget-content" colspan="3"><?= $grupo['address'];?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"  width="70px"><b>C&oacute;digo P:</b></td>
							<td class="ui-widget-content" ><?= $grupo['cp'];?></td>
							<td class="ui-widget-content" align="right"><b>Telefono:</b></td>
							<td class="ui-widget-content" ><?= $grupo['phone'];?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right" width="80px"><b>Ciudad:</b></td>
							<td class="ui-widget-content" ><?= $grupo['city'];?></td>
							<td class="ui-widget-content" align="right"  width="70px"><b>Pa&iacute;s:</b></td>
							<td class="ui-widget-content" ><?= $grupo['country'];?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>E-Mail:</b></td>
							<td class="ui-widget-content" colspan="3"><?= $grupo['email'];?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Cuit:</b></td>
							<td class="ui-widget-content" ><?= $grupo['cuit'] ;?></td>
							<td class="ui-widget-content" align="right"><b>IVA:</b></td>
							<td class="ui-widget-content">
								<select name="iva" disabled>
									<option value="0" <?php if($grupo['iva']==0) echo "selected"; ?>>Resp. Insc.</option>
									<option value="1" <?php if($grupo['iva']==1) echo "selected"; ?>>Monotributo</option>
									<option value="2" <?php if($grupo['iva']==2) echo "selected"; ?>>No Resp.</option>
									<option value="3" <?php if($grupo['iva']==3) echo "selected"; ?>>Exento</option>
									<option value="4" <?php if($grupo['iva']==4) echo "selected"; ?>>Cons. Final</option>
								</select>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>ID:</b></td>
							<td class="ui-widget-content"><?= $grupo['id'];?></td>
							<td class="ui-widget-content" align="right"><b>Creación:</b></td>
							<td class="ui-widget-content" ><?= show_fecha($grupo['startdate']);?></td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Saldo:</b></td>
							<td class="ui-widget-content" colspan="3">$ <?= number_format($_comp->get_saldo($grupo['id']), 2, ',', '.') ;?>	</td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Descripci&oacute;n:</b></td>
							<td class="ui-widget-content" colspan="3"><?= $grupo['summary'] ;?></td>
						</tr>
						<tr>
							<td colspan="4" align="center">
								<a class="button"  href="grupos.php?v=edit&grupoid=<?= $grupo['id'];?>">Editar grupo</a>
							</td>
						</tr>
				</tbody>
			</table>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Miembros</div>
		<div class="portlet-content"  style="overflow: auto;max-height:350px;">
			<form id="inscribir" action="grupos.php?v=inscripcion&group=<?= $grupo['id'];?>" method="post">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th>Usuario</th>
						<th>Nombre</th>
						<th>Correo Electronico</th>
						<th></th>
					</thead>
					<tbody>
					<?php foreach($usuarios as $usuario):?>
					<tr>
						<td class="ui-widget-content"><?= $LMS->GetField('mdl_user','username',$usuario['userid']);?></td>
						<td class="ui-widget-content" class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $usuario['userid'];?>';">
							<a href="contactos.php?v=view&id=<?= $usuario['userid'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>	<b><?= $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$usuario['userid']);?></b></td>
						<td class="ui-widget-content"><?= $LMS->GetField('mdl_user','email',$usuario['userid']);?></td>
						<td class="ui-widget-content" align="center"><input type="checkbox" name="users[]" value="<?= $usuario['userid'];?>" /></td>
					</tr>
					<?php endforeach;?>
					</tbody>
				</table>
				<button type="button" class="add" onClick="$('#inscribir').submit();">Inscribir usuarios seleccionados</button>
			</form>
		</div>
		<form action="" method="post"><b>Agregar usuarios: </b><input type="text" size="30" name="q" value="" /><input type="submit" id="submit" value="Buscar" /></form>
		<div class="portlet-content"  style="overflow: auto; max-height:350px;">
		<?php if ($nomiembros):?>	
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Usuario</th>
					<th>Nombre</th>
					<th>Email</th>
					<th></th>
				</thead>
				<tbody>
					<?php foreach($nomiembros as $nomi):?>
					<tr>
					<td><?= $nomi['username'];?></td>
					<td><?= $nomi['firstname'];?> <?= $nomi['lastname'];?></td>
					<td><?= $nomi['email'];?></td>
					<td>
						<form action="" method="post">
							<input type="hidden" name="userid" value="<?= $nomi['id'];?>" />
							<input type="hidden" name="grupoid" value="<?= $grupo['id'];?>" />
							<input type="submit" id="submit" value="Agregar" />
						</form>
					</td>
					</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		<?php endif;?>
		</div>
	</div>
</div>

<div class="column" style="width:60%">
<?php if ($cuotas): ?>
	<div class="portlet">
		<div class="portlet-header">Cuotas</div>
		<div class="portlet-content">
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
					<th>Usuarios</th>
				</tr>
			</thead>
				<tbody class="ui-widget-content">
					<?php foreach($cuotas as $cuota):?>
						<tr style="height: 20px;">
							<td class="ui-widget-content"><b><?= $LMS->GetField('mdl_course','shortname',$cuota['id']); ?><b></td>
							<?php for($x=1;$x<6;$x++):?>
								<?php if($cuota['cuota'.$x]=='0'):?>
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
							<td class="ui-widget-content" align="center">
							<select style="width:100px;">
							<?php foreach($cuota['userid'] as $userid):?>
								<option><?= $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$userid);?></option>
									<?php $c++; ?>
							<?php endforeach;?>
							</select>
									<?= $c;$c=0; ?>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>
<?php if ($comprobantes): ?>
	<div class="portlet">
		<div class="portlet-header">Comprobantes</div>
		<div class="portlet-content" >
			<table class="ui-widget" align="center" style="width:100%;">
				<thead class="ui-widget-header">
					<th>Nro. Comprobante</th>
					<th>Fecha</th>
					<th>Importe</th>
					<th>Cancelado</th>
					<th>Concepto</th>
					<th>Tipo</th>
					<th></th>
				</thead>
				<tbody class="ui-widget-content">
					<?php foreach($comprobantes as $comprobante):?>
						<tr style="height: 20px;<?php if($comprobante['anulada']==1) echo "text-decoration: line-through;"; ?>" class="comprobante">
							<td class="ui-widget-content" id="nrocomprobante-<?=$comprobante['id']?>">
								<?php if ($comprobante['numero']>0):?>
									&nbsp;<?= $comprobante['puntodeventa']; ?>-<span class="numero" id="<?=$comprobante['id']?>"><?= sprintf("%08d", $comprobante['numero']);?></span>
								<?php else: ?>
									<form id="nrocomprobante-<?=$comprobante['id']?>" name="nrocomprobante-<?=$comprobante['id']?>" action="<?= $HULK->SELF?>&action=nrocomprobante" method="post">
										&nbsp;<?= $comprobante['puntodeventa']; ?>-<input type='text' name='numero' maxlength='8' style="width:80px;" />
										<input type='hidden' name='comprobanteid' value="<?=$comprobante['id']?>" />
										<input class="button-save2" type='submit' value='Save' style="padding:.4em;">
									</form>
								<?php endif; ?>
							</td>
							<td class="ui-widget-content" align="center"><span class="fecha" id="<?=$comprobante['id']?>"><?= date("d-m-Y", $comprobante['date']) ;?></span></td>
							<td class="ui-widget-content" align="center">$ <?= number_format($comprobante['importe'], 2, ',', '.') ;?></td>
							<td class="ui-widget-content" align="center">$ <?= number_format($_comp->get_valorasociado($comprobante['id']), 2, ',', '.') ;?></td>
							<td class="ui-widget-content"><span class="concepto" id="<?=$comprobante['id']?>"><?= $HULK->conceptos[$comprobante['concepto']] ;?></span></td>
							<td class="ui-widget-content"><span class="tipo" id="<?=$comprobante['id']?>"><?= $HULK->tipos[$comprobante['tipo']] ;?></span></td>
							<td class="ui-widget-content"align="center" style="width:290px">
								<?php if($comprobante['anulada']==0): ?>
									<span id="imprimir<?=$comprobante['id']?>" onClick="window.open('grupos.php?v=pagos-print&id=<?=$comprobante['id'];?>','imprimir','width=600,height=500,scrollbars=NO');" class="button-print2">Imprimir</span>
									<?php if($H_USER->has_capability('comprobante/anular')):	?>
										<span class="button-anular anular" id="<?=$comprobante['id']?>">Anular</span>
									<?php endif; ?>
									<?php if($H_USER->has_capability('comprobante/cancelar')):	?>
										<span class="button-cancelar cancelar" id="<?=$comprobante['id']?>">Cancel</span>
									<?php endif; ?>
									<?php if($H_USER->has_capability('comprobante/editar')):	?>
										<span class="button-editar" id="<?=$comprobante['id']?>" onClick="window.open('contactos.php?v=editar_comprobante&id=<?=$comprobante['id'];?>','editar','width=600,height=500,scrollbars=NO');">Edit</span>
									<?php endif; ?>									
									<?php if($comprobante['pendiente']==1): ?>
										<?php if($H_USER->has_capability('comprobante/cobrar_pend')):	?>
											<span class="button-add2 cobrar" id="<?=$comprobante['id']?>">Cobrado</span>
										<?php endif; ?>
									<?php endif; ?>
									<input type="hidden" name="comp_actual" id="comp_actual" value="" />
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
<?php endif; ?>
</div>
</div>
