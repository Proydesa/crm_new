<script>
	$(document).ready(function(){
		$('.add').click(function(){
			$("#dialog-confirm").dialog('open');
      var libro = $(this).attr("id");
		});
		$('.add2').click(function(){
			$("#dialog-confirm2").dialog('open');
           var libro = $(this).attr("id");
		});

		$("#dialog-confirm").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					$('#libro_'+libro).submit();
					$(this).dialog("close");
					return true;
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});
		$("#dialog-confirm2").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Confirmar": function() {
					$('#libros').submit();
					$(this).dialog("close");
					return true;
				},
				"Cancelar": function() {
					$(this).dialog("close");
					return false;
				}
			}
		});
	});
</script>
<br/><br/><br/>
<div id="dialog-confirm" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por guardar los cambios en el libro, desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>
<div id="dialog-confirm2" title="Confirmar acci&oacute;n">
	<p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Est&aacute; por eliminar el libro, desea continuar?<div id='extra' align="center" style="font-size:17px;font-weight:bold;"></div></p>
</div>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:70%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>Nombre</th>
			<th>Descripción</th>
			<th>Valor</th>
			<th>Modelo</th>
			<th COLSPAN=2></th>
		</tr>
			<?php foreach($libros as $libro):?>
				<form id="form_<?= $libro['id'];?>" action="admin.php?v=libros&stage=1" method="post" style="margin:0;padding:0;">
				<tr class="ui-widget-content">
					<td><input <?php if($activar!=$libro['id']){?> disabled="disabled" <?php } ?> type="text" name="name" value="<?= $libro['name'];?>"</td>
					<td><input  <?php if($activar!=$libro['id']){?> disabled="disabled" <?php } ?> type="text" name="detalle" value="<?= $libro['detalle'];?>"</td>
					<td align="center">
						<input <?php if($activar!=$libro['id']){?> disabled="disabled" <?php } ?> type="text" name="value" value="<?= $libro['valor'];?>" style="width:90%;">
						<input type="hidden" name="id" value="<?= $libro['id'];?>">
						<input type="hidden" name="deleted" value="0">
					</td>
					<td>
						<select <?php if($activar!=$libro['id']){?> disabled="disabled" <?php } ?> name="modelid">
							<option value="0"></option>
							<?php foreach($models as $model):?>
								<option value="<?= $model['id'] ?>" <?php if($model['id']==$libro['modelid']) echo "selected"; ?>><?= $model['fullname'] ?></option>
							<?php endforeach;?>
						</select>
					</td>
					<td align="center">
						<?php if($activar!=$libro['id']):?>
							<button type="submit" class="button" value="<?= $libro['id'];?>" name="botoneditar">Editar</button>
						<?php else: ?>
							<button type="submit" class="button add" value="<?= $libro['id'];?>" id="<?= $libro['id'];?>" name="botonguardar">Guardar</button>
						<?php endif;?>
					</td>
					<td align="center">
						<button type="submit" id="<?= $libro['id'];?>" class="button add2" name="botondelete">Eliminar</button>
					</td>
				</tr>
				</form>
			<?php endforeach;?>
			<tr class="ui-widget-content">
			<td colspan="5" height="20px">&nbsp;</td>
			</tr>
			<form id="form_0" action="admin.php?v=libros&stage=1" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">
				<td align="center"><input type="text" name="name" value="" style="width:90%;"></td>
				<td align="center"><input type="text" name="detalle" value="" style="width:90%;"></td>
				<td align="center">
					<input type="text" name="valor" value="" style="width:90%;">
					<input type="hidden" name="id" value="0">
				</td>
				<td>
					<select name="modelid">
						<option value="0"></option>
						<?php foreach($models as $model):?>
							<option value="<?= $model['id'] ?>"><?= $model['fullname'] ?></option>
						<?php endforeach;?>
					</select>
				</td>
				<td align="center">
					<span class="button" href="#" onClick="$('#form_0').submit();" >Nuevo</span>
				</td>
			</tr>
			</form>

	</table>
</div>
