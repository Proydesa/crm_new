<h2 class="ui-widget">Comisi&oacute;n: <?= $comision['fullname']; ?></h2>

<div class="column" style="width:100%">
	<div class="portlet">
		<div class="portlet-header">Gradebook</div>
		<div class="portlet-content" >
			<table class="ui-widget" width="100%">
				<tr class="ui-widget-header">
					<th>Instructor</th>
					<?php foreach($examenes as $examen): ?>
						<th width="90px"><?= $examen['itemname']; ?></th>
					<?php endforeach; ?>
					<th>Observaciones</th>
				</tr>
				<?php foreach($instructores as $id => $data): ?>
					<tr class="ui-widget-content" height="20px">
						<td><?= $data['inst']; ?></td>
						<?php foreach($data['notas'] as $id_nota => $nota): ?>
							<td align="center">
								<?php 
									if($nota>0){
										$nota = number_format($nota,2);
									}else{
										$nota = "";
									}
								?>
								<input type="text" name="<?= $id.":".$id_nota; ?>" value="<?= $nota; ?>" style="width:50px;text-align:center" />
							</td>
						<?php endforeach; ?>
						<td align="center">
							<select name="graduacion" style="width:50px;text-align:center">
								<option value="<?= $id.":"; ?>E">E</option>
								<?php if($data['graduacion_nota']==3.00000): $selected = "selected"; else: $selected = ""; endif; ?>
								<option value="<?= $id.":"; ?>3.00000" <?= $selected; ?>>P</option>
								<?php if($data['graduacion_nota']==2.00000): $selected = "selected"; else: $selected = ""; endif; ?>
								<option value="<?= $id.":"; ?>2.00000" <?= $selected; ?>>F</option>
								<?php if($data['graduacion_nota']==1.00000): $selected = "selected"; else: $selected = ""; endif; ?>
								<option value="<?= $id.":"; ?>1.00000" <?= $selected; ?>>I</option>
							</select>
						</td>
						<td><textarea name="obs" cols="50"></textarea></td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>
</div>