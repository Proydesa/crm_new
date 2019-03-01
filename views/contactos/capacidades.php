<script>
	$(document).ready(function(){  
	
		$("#selec").click(function(event){   		 
			$('.cursos').each( function() {			
				$(this).attr("checked","checked");
			});
		});

		$("#unselec").click(function(event){   		 
			$('.cursos').each( function() {			
				$(this).removeAttr("checked","checked");
			});
		});
	}); 	
</script>
<div class="column-c" style="width:900px">
		<form id="form-academy" name="role" action="contactos.php?v=capacidades&id=<?= $id; ?>" method="post">
		<div class="portlet">
			<div class="portlet-header">
					Certificaciones del usuario
			</div>
			<div class="portlet-content">
				<div align="right">
					Seleccionar: 
					<span class="button" type="marcar" id="selec"  class="button">Todas</span> |
					<span class="button" type="desmarcar"  id="unselec" class="button">Ninguna</span>
				</div>
				<?php foreach($convenios as $convenio):?>
					<div class="block">
						<span class="title"><?= $convenio['shortname'];?></span>
						<ul>
							<?php foreach($convenio['cursos'] as $curso):?> 
								<li><?= $curso['shortname'];?> <input type="checkbox" name="cursos[]" class="cursos" value="<?= $curso['id'];?>" <?php if(in_array($curso['id'],$existentes)) echo "checked"; ?>/></li>
							<?php endforeach;?>
						</ul>
					</div>
				<?php endforeach;?>
				<div class="clear"></div>
				<div align="center">
					<input type="submit" class="button" name="update" value="Aplicar Cambios"  style=" font-weight: bold;"/>
				</div>				
			</div>					
		</div>
	</form>		
</div>

