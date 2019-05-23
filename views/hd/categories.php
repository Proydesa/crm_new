<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 530px'";
	}else{
		$menufixed = "";
	}
?>
<div class="ui-widget"<?= $menufixed ?>>
	<table class="ui-widget" align="center">
		<tr class="ui-widget-header" style="height: 20px;">
			<th  style="width:220px;">Nombre</th>
			<th  style="width:60px;">Orden</th>
			<th>Descripci&oacute;n</th>
			<th></th>
		</tr>
		<?php foreach($categorias as $cat):?>
		<form id="form_<?= $cat['id'];?>" action="hd.php?v=categories&stage=update" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">			
				<td align="center">
					<input type="text" name="name" value="<?= $cat['name'];?>" style="width:200px;">
					<input type="hidden" name="id" value="<?= $cat['id'];?>">			
				</td>
				<td align="center">
					<input type="text" name="weight" value="<?= $cat['weight'];?>" style="width:30px;">
				</td>
				<td align="center">
					<textarea name="summary" rows="2" cols="70"><?= $cat['summary'];?></textarea>
				</td>
				<td align="center">
					<a href="hd.php?v=categories&stage=delete&id=<?= $cat['id'];?>" class="button confirmlink">Borrar</a>
					<a href="hd.php?v=category_form&id=<?= $cat['id'];?>" class="button">Customizar</a>
					<a href="#" class="button" onClick="$('#form_<?= $cat['id'];?>').submit();" >Guardar</a>
				</td>
			</tr>
		</form>
		<?php endforeach;?>
		<tr class="ui-widget-content">
			<td colspan="5" height="20px">&nbsp;</td>
		</tr>				
		<form id="form_0" action="hd.php?v=categories&stage=update" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">			
				<td align="center">
					<input type="text" name="name" value="" style="width:200px;">
					<input type="hidden" name="id" value="0"> 
				</td>
				<td align="center">
					<input type="text" name="weight" value="" style="width:30px;">
				</td>
				<td align="center">
					<textarea name="summary" rows="2" cols="70"></textarea>
				</td>		
				<td align="center">
					<a href="#" class="button" onClick="$('#form_0').submit();">Nuevo</a>
				</td>
			</tr>
		</form>
	</table>
</div>
