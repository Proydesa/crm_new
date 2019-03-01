<br/><br/><br/>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:50%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>Nombre</th>
			<th>Descripci&oacute;n</th>
			<th></th>
		</tr>
			<?php foreach($asuntos as $asunto):?>
				<form id="form_<?= $asunto['id'];?>" action="admin.php?v=asuntos&stage=1" method="post" style="margin:0;padding:0;">
					<tr class="ui-widget-content">			
						<td>
							<input type="text" name="name" value="<?= $asunto['name'];?>" style="width:80%;">
							<input type="hidden" name="id" value="<?= $asunto['id'];?>">			
						</td>
						<td>
							<textarea name="summary" rows="2" cols="70"><?= $asunto['summary'];?></textarea>
						</td>
						<td align="center">
							<a href="#" class="button" onClick="$('#form_<?= $asunto['id'];?>').submit();" >Guardar</a>
						</td>
					</tr>
				</form>
			<?php endforeach;?>
				<tr class="ui-widget-content">
					<td colspan="5" height="20px">&nbsp;</td>
				</tr>				
				<form id="form_0" action="admin.php?v=asuntos&stage=1" method="post" style="margin:0;padding:0;">
					<tr class="ui-widget-content">			
						<td>
							<input type="text" name="name" value="" style="width:50%;">
							<input type="hidden" name="id" value="0"> 
						</td>
						<td>
							<textarea name="summary"></textarea>
						</td>		
						<td align="center">
							<a href="#" class="button" onClick="$('#form_0').submit();">Nuevo</a>
						</td>
					</tr>
				</form>
	</table>
</div>
