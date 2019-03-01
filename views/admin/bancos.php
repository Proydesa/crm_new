<br/><br/><br/>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:70%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th style="width:25%;">Banco</th>
			<th>Descripci&oacute;n</th>
			<th>Acciones</th>
		</tr>
		<?php foreach($rows as $row):?>
			<form id="form_<?= $row['id'];?>" action="admin.php?v=bancos&stage=1" method="post" style="margin:0;padding:0;">
				<tr class="ui-widget-content">
					<td><input type="text" name="name" value="<?= $row['name'];?>" style="width:200px;"></td>				
					<td align="center">
						<textarea name="summary" style="width:90%;"><?= $row['summary'];?></textarea>
						<input type="hidden" name="id" value="<?= $row['id'];?>"> 
					</td>
					<td align="center">
						<a href="#" onClick="$('#form_<?= $row['id'];?>').submit();">Guardar</a>
					</td>
				</tr>
			</form>
		<?php endforeach;?>
		<tr class="ui-widget-content">
			<td colspan="3" height="20px">&nbsp;</td>
		</tr>				
		<form id="form_0" action="admin.php?v=bancos&stage=1" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">			
				<td><input type="text" name="name" value="" style="width:200px;"></td>				
				<td align="center">
					<textarea name="summary" style="width:90%;"></textarea>
					<input type="hidden" name="id" value="0"> 
				</td>
				<td align="center">
					<a href="#" onClick="$('#form_0').submit();" >Nuevo Banco</a>
				</td>
			</tr>
		</form>
	</table>
</div>
