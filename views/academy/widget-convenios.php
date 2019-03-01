			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
						<tr style="height: 20px;" class="ui-widget-header">
							<th>Nombre</th>
							<th>Tipo</th>
							<th>Estado</th>
							<th>Inicio</th>
							<th>Fin</th>
							<th></th>
						</tr>
				</thead>
				<tbody class="ui-widget-content">
					<?php foreach($convenios as $convenio):?>
						<tr style="height: 20px;">
							<td style="width:30%;"><b><?= $convenio['fullname'];?></b></td>
							<td align="center"><?= $convenio['forma_de_pago'];?></td>
							<td align="center">
								<?php if($convenio['visible']==1): 
									echo "Activo";
								else:
									echo "Bloqueado";
								endif;?>
							</td>
							<td align="center"><?= ($convenio['startdate'])? date('d-m-Y',$convenio['startdate']): "-";?></td>
							<td align="center"><?= ($convenio['enddate'])? date('d-m-Y',$convenio['enddate']): "-";?></td>
							<td align="center">
								<?php if($convenio['visible']==1): 
									echo '<a href="academy.php?v=bloquear_convenio&academyconvenioid='.$convenio['id'].'" class="button">Bloquear</a>';
								else:
									echo '<a href="academy.php?v=activar_convenio&academyconvenioid='.$convenio['id'].'" class="button">Activar</a>';
								endif;?>
								<a href="academy.php?v=mover_historicos&id=<?= $convenio['id'];?>">Mover a historicos</a>
								<a href="academy.php?v=agregar_convenio&id=<?= $convenio['id'];?>" class="button">Modificar</a>
							</td>
						</tr>
					<?php endforeach;?>
				</tbody>
			</table>
			<p align="center"><a href="academy.php?v=agregar_convenio&academyid=<?= $id;?>">Agregar convenio</a></p>