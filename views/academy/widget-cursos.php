<table class="ui-widget" align="center" style="width:100%;">
	<thead>
		<tr style="height: 20px;" class="ui-widget-header">
			<th>Comisi&oacute;n</th>
			<th>Curso</th>
			<th>E</th>
		</tr>
	</thead>
	<tbody class="ui-widget-content">
		<?php if ($cursos):?>
		<?php foreach($cursos as $curso):?>
			<tr style="height: 20px;">
				<td class="press" ondblclick="window.location.href='courses.php?v=view&id=<?= $curso['id'];?>';">
					<a href="courses.php?v=view&id=<?= $curso['id'];?>" target="_blank">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
					</a><b><?= $curso['course'];?></b>
				</td>
				<td><?= $curso['model'];?></td>
				<td><?= $curso['estudiantes'];?></td>
			</tr>
		<?php endforeach;?>
		<?php endif;?>
	</tbody>
</table>
<a href="courses.php?v=list&academias[]=<?= $id;?>">Ver todos los cursos de la academia</a>
