<br/><br/><br/>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:40%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>Variable</th>
			<th>Valor</th>
			<th></th>
		</tr>
		<?php foreach($rows as $row):?>
			<form id="form_<?= $row['id'];?>" action="admin.php?v=configuracion&stage=1" method="post" style="margin:0;padding:0;">
				<tr class="ui-widget-content">
					<td><b><?= $row['name'];?></b>
						<?php if($row['summary']):?>
						<p><?= $row['summary'];?></p>
						<?php endif;?>
					</td>				
					<td align="center">
						<input type="text" name="value" value="<?= $row['value'];?>" style="width:90%;">
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
		<form id="form_0" action="admin.php?v=configuracion&stage=1" method="post" style="margin:0;padding:0;">
			<tr class="ui-widget-content">			
				<td align="center">
					<input type="text" name="name" value="" style="width:45%;">
					<input type="text" name="summary" value="" style="width:45%;">
				</td>				
				<td align="center">
					<input type="text" name="value" value="" style="width:90%;">
					<input type="hidden" name="id" value="0"> 
				</td>
				<td align="center">
					<a href="#" onClick="$('#form_0').submit();">Nueva variable</a>
				</td>
			</tr>
		</form>
	</table>
</div>
