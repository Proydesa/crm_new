
<section class="p-2">

	<div class="container">


		<h4>Inscripciones Online</h4>
		<hr>

		<form id="form_search" action="<?= $HULK->SELF ?>" method="post" class="noprint" >

			<div class="row">
				<div class="col-md-4">
					<div class="form-group">
						<label for="">Búsqueda</label>
						<input name="keywords" type="text" class="form-control form-control-sm" placeholder="Buscar por nombre, apellido, email o dni" value="<?=$keywords?>">
					</div>
				</div>
				<div class="col-md-4">
					<label for="">Períodos</label>
					<select name="periods" class="form-control form-control-sm">
						<option value="">--Todos--</option>
						<?php for($y=date('y'); $y<=20; $y++): for($p=1;$p<=3;$p++): ?>
						<option value="<?=$y.$p?>" <?= $period == $y.$p ? 'selected' : '' ?>><?=$y.$p?></option>
						<?php endfor; endfor; ?>
					</select>
				</div>
				<div class="col-md-4">
					<div class="form-group">
						<label for="">&nbsp;</label>
						<button class="btn btn-primary btn-block btn-sm"><i class="fa fa-search fa-fw"></i> Buscar</button>
					</div>
				</div>
			</div>

		</form>
		<hr>

	</div>

</section>

<section class="p-5">
	<div class="container-fluid">

		<div class="bg-light table-responsive">
			<table class="table table-bordered table-striped small">
				<thead>
					<tr>
						<th>ID</th>
						<th>Nombre</th>
						<th>Email</th>
						<th>Teléfono</th>
						<th>Curso</th>
						<th>Fecha</th>
						<th>Forma de Pago</th>
						<th>Estado</th>
						<th class="text-right">Inscripción</th>
					</tr>
				</thead>
				<tbody>
					<?php if($rows): foreach($rows as $k=>$row): $row = (object) $row; ?>
					<tr>
						<td><?=$row->userid?></td>
						<td>
							<a href="contactos.php?v=view&id=<?=$row->userid?>" target="_blank"><?=$row->fullname?></a><br>
							<small>DNI: <?=$row->dni?></small>
						</td>
						<td><a href="mailto:<?=$row->email?>"><?=$row->email?></a></td>
						<td><?=$row->phone?></td>
						<td>
							<a href="course.php?v=view&id=<?=$row->courseid?>"><?=$row->shortname?></a><br>
							<small>Período: <?=$row->period?></small><br>
							<small><?=$row->schedules?></small><br>

							<?php if($row->modality=='Intensivo'): ?>
							<span class="badge badge-danger">intensivo</span>
							<?php endif; ?>

						</td>
						<td><?=$row->added?></td>
						<td>
							<span><?=$row->payment=='bank' ? 'Transferencia Bancaria' : 'MercadoPago' ?></span><br>
						</td>
						<td><span class="badge badge-<?=set_status($row->status)->colour?>"><?=set_status($row->status)->label?></span></td>
						<td align="right">
							<?php if(!check_role_assignment($row->courseid,$row->userid)): ?>
							<a href="contactos.php?v=inscribir_usuario&id=<?=$row->userid?>" target="_blank" class="btn btn-primary btn-xs"><i class="fa fa-angle-right fa-fw"></i> Inscribir alumno</a>
							<?php else: ?>
							<a href="contactos.php?v=pagos&id=<?=$row->userid?>" target="_blank" class="btn btn-primary btn-xs">Comprobantes</a>
							<?php endif; ?>

						</td>
					</tr>
					<?php endforeach; endif; ?>
				</tbody>
			</table>
		</div>

	</div>
</section>