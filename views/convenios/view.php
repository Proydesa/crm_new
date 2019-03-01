<div class="ui-widget" align="left">
	<div class="column" style="width:50%">
	<div class="portlet">
			<div align="center"><h2><?= $row['fullname'];?></h2></div>
			<div class="portlet-content">
			<form class="form-view" action="convenios.php?v=new&id=<?= $row['id'];?>" method="post" name="edit-convenio" class="ui-widget">
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody>
							<tr style="height: 20px;">
								<td class="ui-widget-content" align="right" style="width:30%"><b>Nombre:</b></td>
								<td class="ui-widget-content" colspan="3" style="width:70%"><input  disabled name="fullname" type="text" value="<?= $row['fullname'];?>"/></td>
							</tr>
							<tr style="height: 20px;">
								<td class="ui-widget-content" align="right"><b>Nombre corto:</b></td>
								<td class="ui-widget-content" colspan="3"><input  disabled name="shortname" type="text" value="<?= $row['shortname'];?>"/></td>
							</tr>
							<tr style="height: 20px;">
								<td class="ui-widget-content" align="right"><b>Categoria en moodle:</b></td>
								<td class="ui-widget-content" colspan="3"><a href="<?= $HULK->lms_wwwroot."/course/index.php?categoryid=".$row['categoryid'];?>" target="_blank"><?= $row['categoryid'];?></a></td>
							</tr>
							<tr style="max-height: 200px;">
								<td class="ui-widget-content" align="right"><b>Descripción:</b></td>
								<td class="ui-widget-content" colspan="3"><textarea disabled name="summary" style="width:100%; max-height: 200px;"><?= $row['summary'];?></textarea></td>
							</tr>
							<tr style="height: 20px;">
								<td class="ui-widget-content" align="right"><b>Link de portal <a href="<?= $row['link'];?>" target="_blank">[Ver]</a>:</b></td>
								<td class="ui-widget-content" colspan="3"><input  disabled name="link" type="text" value="<?= $row['link'];?>"/></td>
							</tr>
							<tr style="height: 20px;">
								<td class="ui-widget-content" align="right"><b>Orden en listados de convenios:</b></td>
								<td class="ui-widget-content" colspan="3"><input  disabled name="weight" type="text" value="<?= $row['weight'];?>"/></td>
							</tr>
						</tbody>
					</table>
				</form>
				</div>
		</div>
		<div class="portlet">
			<div class="portlet-header">Carreras</div>
			<div class="portlet-content"  style="overflow: auto; max-height: 380px;">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead>
							<tr style="height: 20px;" class="ui-widget-header">
								<th></th>
								<th>Nombre</th>
							</tr>
					</thead>
					<tbody class="ui-widget-content">
						<?php foreach($carreras as $carrera):?>
							<tr style="height: 20px;">
								<td><b><?= $carrera['shortname'];?></b></td>
								<td><?= $carrera['fullname'];?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>

	<div class="column" style="width:50%">
		<div class="portlet" align="center" style="padding:10px">
			<a class="button" href="convenios.php?v=new">Agregar convenio</a>
			<a class="button" href="#" onClick="$('.form-view *').removeAttr('disabled');$('.edit').toggle();">Editar convenio</a>
			<a class="button" href="academy.php?v=agregar_convenio">Asociar convenios</a>
		</div>
		<div class="portlet">
			<div class="portlet-header">Academias</div>
			<div class="portlet-content"  style="overflow: auto; max-height: 380px;">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead>
							<tr style="height: 20px;" class="ui-widget-header">
								<th>Nombre</th>
								<th></th>
								<th>Inicio</th>
								<th>Fin</th>
								<th>Detalle</th>
								<th>Pago</th>
							</tr>
					</thead>
					<tbody class="ui-widget-content">
						<?php foreach($academys as $academy):?>
							<tr style="height: 20px;">
								<td style="width:60%;" ondblclick="window.location.href='academy.php?v=view&id=<?= $academy['id'];?>';">
								<a href="academy.php?v=view&id=<?= $academy['academyid'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<b><?= $academy['name'];?></b></td>
								<td><?= $academy['shortname'];?></td>
								<td><?= date('d-m-Y',$academy['startdate']);?></td>
								<td><?= date('d-m-Y',$academy['enddate']);?></td>
								<td><?= $academy['sumary'];?></td>
								<td><?= $academy['forma_de_pago'];?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
