<script type="text/javascript">
		$(function() {
			$("#dia").change(function(){
				if ($(this).val()=="todos"){
					<?php foreach($dias as $d=>$dia):?>
						$(".<?=$dia;?>").show();
					<?php endforeach;?>					
				}else{
					<?php foreach($dias as $d=>$dia):?>
						$(".<?=$dia;?>").hide();
					<?php endforeach;?>	
					$("."+$(this).val()).show();
				}
			});
			$("#turno").change(function(){
				if ($(this).val()=="todos"){
					$(".manana,.tarde,.noche").show();
				}else{
					$(".manana,.tarde,.noche").hide();
					$("."+$(this).val()).show();
				}
			});
		});
	</script>
<div class="ui-widget" align="center" style="width:100%;">
	Día: <select name="dia" id="dia">
		<option value="todos"></option>
		<?php foreach($dias as $d=>$dia):?>
			<option value="<?=$dia;?>"><?=$dia;?></option>
		<?php endforeach;?>
	</select>
</div>
<!--Turno: <select name="turno" id="turno">
		<option value="todos"></option>
	<option value="manana">Mañana</option>
	<option value="tarde">Tarde</option>
	<option value="noche">Noche</option>
</select>-->

<table class="ui-widget" align="center" style="width:100%;">
	<thead>
		<tr style="height: 20px;" class="ui-widget-header">
			<th colspan="2"></th>
			<th colspan="<?= count($aulas);?>"> Aula - Capacidad</th>
		</tr>
		<tr style="height: 20px;" class="ui-widget-header">
			<th>Día</th>
			<th>Turno</th>
			<?php foreach($aulas as $aula):?>
				<th><?= $aula['name'];?> - <?= $aula['capacity'];?></th>
			<?php endforeach;?>
		</tr>
	</thead>
	<tbody class="ui-widget-content">
		<?php foreach($dias as $d=>$dia):?>
			<tr class="<?= $dia;?>" class="ui-widget-content">
				<td><b><?= $dia;?></b></td>
			</tr>
			<tr class="<?= $dia;?> manana" class="ui-widget-content">
				<td class="ui-widget-content"></td>
				<td height="30px" class="ui-widget-content">Mañana</td>
				<?php foreach($aulas as $aula):?>
					<td class="ui-widget-content">
					<?php if($courses[$d]['M'][$aula['name']]):?>
						<?php foreach($courses[$d]['M'][$aula['name']] as $course):?>
							<a href="courses.php?v=view&id=<?= $course['id'];?>" title="<?= $course['shortname'];?>"><?= $course['modelo'];?></a> (<?=$course['estudiantes'];?>)<br/>
							<?= $course['instructores'];?><br/>
						<?php endforeach;?>
					<?php endif;?>
					</td>
				<?php endforeach;?>
			</tr>
			<tr class="<?= $dia;?> tarde">
				<td class="ui-widget-content"></td>
				<td height="30px" class="ui-widget-content">Tarde</td>
				<?php foreach($aulas as $aula):?>
					<td class="ui-widget-content">
					<?php if($courses[$d]['T'][$aula['name']]):?>
						<?php foreach($courses[$d]['T'][$aula['name']] as $course):?>
							<a href="courses.php?v=view&id=<?= $course['id'];?>" title="<?= $course['shortname'];?>"><?= $course['modelo'];?></a> (<?=$course['estudiantes'];?>)<br/>
							<?= $course['instructores'];?><br/>
						<?php endforeach;?>
					<?php endif;?>
					</td>
				<?php endforeach;?>
			</tr>
			<tr class="<?= $dia;?> noche" class="ui-widget-content">
				<td class="ui-widget-content"></td>
				<td height="30px" class="ui-widget-content">Noche</td>
				<?php foreach($aulas as $aula):?>
					<td class="ui-widget-content">
					<?php if($courses[$d]['N'][$aula['name']]):?>
						<?php foreach($courses[$d]['N'][$aula['name']] as $course):?>
							<a href="courses.php?v=view&id=<?= $course['id'];?>" title="<?= $course['shortname'];?>"><?= $course['modelo'];?></a> (<?=$course['estudiantes'];?>)<br/>
							<?= $course['instructores'];?><br/>
						<?php endforeach;?>
					<?php endif;?>
					</td>
				<?php endforeach;?>
			</tr>
		<?php endforeach;?>
	</tbody>
</table>