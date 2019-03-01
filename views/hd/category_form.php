<div class="ui-widget">
	<table class="ui-widget" align="center">
		<tr class="ui-widget-header" style="height: 20px;">
			<th  style="width:220px;">Nombre</th>
			<th  style="width:60px;">Descripción</th>
			<th>Tipo</th>
			<th>Valores</th>
			<th>Requerido</th>
			<th>Validación</th>
			<th></th>
		</tr>
		<?php foreach($category_form as $campo):?>
		<form id="form_<?= $campo['id'];?>" action="hd.php?v=category_form&id=<?=$category['id']?>&stage=update" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">			
				<td align="center">
					<input type="text" name="name" value="<?= $campo['name'];?>" style="width:150px;">
					<input type="hidden" name="id" value="<?= $campo['id'];?>">			
				</td>
				<td align="center">
					<textarea name="summary" rows="2" cols="40"><?= $campo['summary'];?></textarea>
				</td>
				<td align="center">
					<select name="type">
						<option value="text" <?= ($campo['type']=="text")?"selected":"";?>>Texto</option>
						<option value="select" <?= ($campo['type']=="select")?"selected":"";?>>Seleccion</option>
						<option value="textarea" <?= ($campo['type']=="textarea")?"selected":"";?>>Area de texto</option>
						<option value="checkbox" <?= ($campo['type']=="checbox")?"selected":"";?>>Checkbox</option>
						<option value="radio" <?= ($campo['type']=="radio")?"selected":"";?>>Radio</option>
						<option value="file" <?= ($campo['type']=="file")?"selected":"";?>>File</option>
					</select>					
				</td>
				<td align="center">
					<textarea name="autovalue" rows="2" cols="40"><?= $campo['autovalue'];?></textarea>
				</td>
				<td align="center">
					<input type="checkbox" name="required" value="1" <?= ($campo['required']==1)?"checked":"";?>/>
				</td>
				<td align="center">
					<select name="validate">
						<option value="" <?= ($campo['validate']=="")?"selected":"";?>></option>
						<option value="email" <?= ($campo['validate']=="email")?"selected":"";?>>Email</option>
					</select>
				</td>				
				<td align="center">
					<a href="hd.php?v=category_form&id=<?=$category['id']?>&stage=delete&campoid=<?= $campo['id'];?>" class="button confirmlink">Borrar</a>
					<a href="#" class="button" onClick="$('#form_<?= $campo['id'];?>').submit();" >Guardar</a>
				</td>
			</tr>
		</form>
		<?php endforeach;?>
		<tr class="ui-widget-content">
			<td colspan="5" height="20px">&nbsp;</td>
		</tr>				
		<form id="form_0" action="hd.php?v=category_form&id=<?=$category['id']?>&stage=update" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">			
				<td align="center">
					<input type="text" name="name" value="" style="width:150px;">
					<input type="hidden" name="categoryid" value="<?=$category['id']?>"> 
				</td>
				<td align="center">
					<textarea name="summary" rows="2" cols="40"></textarea>
				</td>		
				<td align="center">
					<select name="type">
						<option value="text">Texto</option>
						<option value="select">Seleccion</option>
						<option value="textarea">Area de texto</option>
						<option value="checkbox">Checkbox</option>
						<option value="radio">Radio</option>
						<option value="file">File</option>
					</select>
				</td>
				<td align="center">
					<textarea name="autovalue" rows="2" cols="40"></textarea>
				</td>
				<td align="center">
					<input type="checkbox" name="required" value="1" />
				</td>				
				<td align="center">
					<select name="validate">
						<option value=""></option>
						<option value="email">Email</option>
					</select>
				</td>
				<td align="center">
					<a href="#" class="button" onClick="$('#form_0').submit();">Nuevo</a>
				</td>
			</tr>
		</form>
	</table>
</div>
