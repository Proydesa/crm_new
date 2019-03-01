			<?php if($instructores){ ?>
			<table class="ui-widget tablesorterfilter" align="center" style="width:100%;">
					<thead>
							<tr style="height: 16px;" class="ui-widget-header">
								<th width="25%">Nombre</th>
								<th width="20%">Role</th>
								<th>Ult.Per.</th>
								<th width="55%">Carreras</th>
							</tr>
					</thead>
					<tbody class="ui-widget-content">
						<?php foreach($instructores as $instructor):?>
							<tr style="height: 16px;">
								<td class="ui-widget-content" class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $instructor['inst'];?>';">
									<a href="contactos.php?v=view&id=<?= $instructor['id'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<?= $instructor['fullname'];?>
								</td>
								<td class="ui-widget-content" ><?= $instructor['rol'];?></td>
								<td class="ui-widget-content" align="center"><?= $LMS->ultimoPeriodoInstructor($instructor['id']);?></td>
								<td class="ui-widget-content" >
									<?php foreach(explode(",",$instructor['cursos']) as $course):?>
										<?= $LMS->getField("mdl_course","shortname",$course);?>  |  
									<?php endforeach;?>										
								</td>

							</tr>
						<?php endforeach;?>			
					</tbody>
			</table>
			<?php }else{ ?>
			<p>La academia no posee ningun instructor. Para asignarle un instructor debe crear un usuario y darle el role de "Creador de curso" en la academia.</p>

			<?php } ?>
