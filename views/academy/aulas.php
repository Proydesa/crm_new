<br/><br/><br/>
<div class="ui-widget">
	<table class="ui-widget" align="center" style="width:70%;">
		<tr class="ui-widget-header" style="height: 20px;">
			<th>Nombre</th>
			<th>Capacidad</th>
			<th>Acciones</th>
		</tr>
			<?php foreach($aulas as $aula):?>
				<form id="form_<?= $aula['id'];?>" action="academy.php?v=aulas&action=new" method="post" style="margin:0;padding:0;">
					<tr class="ui-widget-content">			
						<td><?= $aula['name'];?></td>		
						<td align="center">
							<input type="text" name="capacity" value="<?= $aula['capacity'];?>" style="width:90%;">
							<input type="hidden" name="id" value="<?= $aula['id'];?>"> 
							<input type="hidden" name="academyid" value="<?= $academyid;?>"> 
						</td>
						<td align="center">
							<a href="#" class="button" onClick="$('#form_<?= $aula['id'];?>').submit();" >Editar</a>
							<a href="academy.php?v=aulas&action=delete&id=<?= $aula['id'];?>&academyid=<?= $academyid;?>" class="button" >Borrar</a>
						</td>
					</tr>
				</form>
			<?php endforeach;?>
			<tr class="ui-widget-content">
				<td colspan="5" height="20px">&nbsp;</td>
			</tr>				
			<form id="form_0" action="academy.php?v=aulas&action=new" method="post" style="margin:0;padding:0;">
				<tr class="ui-widget-content">			
					<td align="center"><input type="text" name="name" value="" style="width:90%;"></td>				
					<td align="center">
						<input type="text" name="capacity" value="" style="width:90%;">
						<input type="hidden" name="id" value="0"> 
						<input type="hidden" name="academyid" value="<?= $academyid;?>"> 
					</td>
					<td align="center">
						<a href="#" class="button" onClick="$('#form_0').submit();" >Nuevo</a>
					</td>
				</tr>
			</form>
	</table>
</div>
