<script>
	$(document).ready(function(){
			$("#filter_comp").keyup(function(){
			$(".opt-exist").css('display','none').filter(":containsIgnoreCase('"+ $("#filter_comp").val() +"')").show();
			return true;
		});
	});
</script>
<br/><br/>
<div class="ui-widget">
	<form id="form-role" action="academy.php?v=addrole&academyid=<?= $academyid; ?>" method="post">
		<table class="ui-widget" align="center" style="width:50%;">
			<thead>
				<p align="center">
					<b>Rol:</b>
					<select name="role" OnChange="$('#form-role').submit();">
						<option value="0"></option>
						<?php foreach($roles as $role): ?>
							<option value="<?= $role['id']; ?>" <?php if($role['id'] == $roleid) echo "selected"; ?>><?= $role['name'];?></option>
						<?php endforeach; ?>
					</select>
				</p>
				<tr class="ui-widget-header" style="height: 20px;">
					<th style="width: 50%">Usuario Existentes</th>
					<th style="width: 50%">Usuario Potenciales</th>
				</tr>
			</thead>
			<tbody>
				<tr class="ui-widget-content">
					<td>
						<select name="existentes" id="existentes" multiple="multiple" size="20">
							<?php foreach($existentes as $existente): ?>
								<option value="<?= $existente['id']; ?>" class="opt-exist"><?= $existente['usuario']; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<td>
						<select name="potenciales" multiple="multiple" size="20">
							<?php foreach($users as $user): ?>
								<option value="<?= $user['id']; ?>"><?= $user['usuario']; ?></option>
							<?php endforeach; ?>
						</select>
					</td>				
				</tr>
			</tbody>
			<tfoot>
				<tr>
					<td>
						<input type="text" name="comp" value="" id="filter_comp" size="30" />
					</td>
					<td>
					
					</td>
				</tr>
			</tfoot>
		</table>
	</form>
</div>
