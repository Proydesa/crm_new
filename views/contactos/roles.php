<script>
	$(document).ready(function(){  
	
		$("#selec").click(function(event){   		 
			$('.academias').each( function() {			
				$(this).attr("checked","checked");
			});
		});

		$("#unselec").click(function(event){   		 
			$('.academias').each( function() {			
				$(this).removeAttr("checked","checked");
			});
		});
	}); 	
</script>
<div class="column-c" style="width:900px">
	<form id="form-role" action="contactos.php?v=roles&id=<?= $id; ?>" method="post">
		<b>Rol:</b>
		<select name="roleid" id="roleid">
			<option value="">Seleccione un rol</option>
			<?php foreach($roles as $role): ?>
				<option value="<?= $role['id']; ?>" <?php if($role['id'] == $roleid) echo "selected"; ?>><?= $role['name'];?></option>
			<?php endforeach; ?>
		</select>
		<input type="submit" id="" name="Ver" value="Ver" class="button" style=" font-weight: bold;" />
	</form>
<?php if ($roleid):?>
	<form id="form-academy" name="role" action="contactos.php?v=roles&id=<?= $id; ?>" method="post">
		<div class="portlet">
			<div align="center">
			<span class="button" type="marcar" id="selec"  class="button">Todas</span> |
			<span class="button" type="desmarcar"  id="unselec" class="button">Ninguna</span> | |
			<input type="submit" class="button" name="update" value="Aplicar"  style=" font-weight: bold;"/>
			</div><br/>
			<div class="portlet-content">
				<table id="listado" class="ui-widget">
					<?php $i=0;?>
					<?php foreach($academias as $academia):?>
						<?php if(($i%2)==0):?>
							<tr>
								<td><input type="checkbox" name="academias[]" class="academias" value="<?= $academia['id'];?>" <?php if(in_array($academia['id'],$existentes)) echo "checked"; ?>/></td>
								<td><label for="academias[]"><b><?= $academia['shortname'];?></b></label></td>
								<td><label for="academias[]"><?= $academia['name'];?></label> </td>
						<?php else:?>
								<td><input type="checkbox" name="academias[]" class="academias" value="<?= $academia['id'];?>" <?php if(in_array($academia['id'],$existentes)) echo "checked"; ?>/></td>
								<td><label for="academias[]"><b><?= $academia['shortname'];?></b></label> </td>
								<td><label for="academias[]"><?= $academia['name'];?></label> </td>	
							</tr>		
						<?php endif;?>	
						<?php $i++;?>
					<?php endforeach;?>
				</table>
				<input type="hidden" name="roleid" value="<?= $roleid;?>" />
			</div>					
		</div>
	</form>		
<?php endif;?>	
</div>

