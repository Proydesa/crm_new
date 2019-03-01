<div class="ui-widget acadlogin">
	<form action="login.php" method="GET" > 
		<div class="ui-widget-content textCenter">	
			<h3 class="ui-widget-header"> CRM | Fundaci&oacute;n Proydesa</h3> 
					<select name="academyid" id="academyid" required>
						<option value="0">Seleccione una academia</option>
						<?php foreach($rows as $row):?>
						<option value="<?= $row['id']?>"><?= $row['name']?></option>
						<?php endforeach;?>
					</select>					
			<p align="center"><input id="signin_submit" value="Loguearse" tabindex="6" type="submit" class="ui-button ui-state-default ui-corner-all ui-button-text-only" /></p> 
		</div>
	</form>
	<?php
		if ($H_USER->get_role()):
			echo '<a href="index.php">No seleccionar academia.</a>';
		endif;
	?>
</div>
