<div class="column-c">
	<div class="calendar">
		<p><small><a href="#" onclick="start_tutorial()">(ver tutorial)</a></small></p>
		<div class="calendar-block">
			<div class="header-wrapper">
				<form class="years" onchange="this.submit()" method="post" style="display:inline-block">
					<span>Elegir Año:</span>
					<select name="years">
						<?php for($i=2017; $i<=date('Y')+1; $i++): ?>
						<option value="<?= $i ?>" <?= $i==$_year ? 'selected' : '' ?> ><?= $i ?></option>
						<?php endfor; ?>
					</select>
					<select name="periods">
						<?php if($_periods): foreach($_periods as $period): ?>
						<option value="<?= $period['id'] ?>" <?= $period['id']==$_period ? 'selected' : '' ?> data-min="<?= $period['month_min'] ?>" data-max="<?= $period['month_max'] ?>" ><?= $period['period'].' ('.$period['mode'].')' ?></option>
						<?php endforeach; endif; ?>
					</select>
				</form>
			</div>
		</div>
		<?php if($H_USER->has_capability('calendario/cronograma')): ?>
		<div class="calendar-block">
			<div class="schedule">
				<div class="heading">
					<div class="period">
						<h1 class="title" data-toggle="editable" data-target="span" title="click para editar el texto"><span>Calendario Ciclo Lectivo</span></h1>
						<h1 class="title" data-toggle="editable" data-target="span" title="click para editar el texto"><span><?= $_periods[$_node]['period'].' '.$_year ?></span></h1>
					</div>
					<div class="logo">
						<img src="images/logo-proydesa.jpg" alt="">
					</div>
				</div>
				<div class="title-bar">
					<h2 class="title"><span>Cursos Tecnológicos Dictados en</span> <span class="proy">Fundación Proydesa</span></h2>
					<h3 class="subtitle"><b>Modalidad <?= $_periods[$_node]['mode'] == 'Intensivo' ? 'Intensiva' : $_periods[$_node]['mode'] ?></b> | <b><?= $_periods[$_node]['mode'] == 'Intensivo' ? '2 Veces por Semana' : '1 Vez por Semana' ?></b></h3>
				</div>
				<div class="logos-courses">
					<img src="images/logos-cursos.jpg" alt="">
				</div>
				<div class="body">
					<div class="schedule-body">
						<?php $start = false; foreach($_schedules as $sch): ?>
						<div class="month">
							<div class="month-inner">
								<div class="month-head">
									<div class="month-name"><?= $sch['dayname'] ?></div>
								</div>
								<div class="month-body">
									<?php
									if(!empty($sch['days'])):
										foreach($sch['days'] as $k=>$days):
											if(preg_match('/Inicio/', $days)){
												$start = true;
											}
									?>
									<div class="month-row<?= (preg_match('/Nacional/', $days) ? ' holiday' : '').(preg_match('/Técnico/', $days) ? ' tech' : '').(preg_match('/Cancelada/', $days) ? ' forced' : '') ?>" title="(click para ocultar este día)">
										<div class="day">
											<div class="num"><?= date('d',strtotime($k)) ?></div>
											<div class="dayname"><?= $_monthnamesmin[date('n',strtotime($k))-1] ?></div>
										</div>
										<div class="caption"><?= $days ?></div>
									</div>
									<?php
										endforeach;
									endif;
									for($i=count($sch['days']); $i<$_maxrows; $i++):
									?>
									<div class="month-row blank">
										<div class="day">
											<div class="num">&nbsp;</div>
											<div class="dayname">&nbsp;</div>
										</div>
										<div class="caption">-</div>
									</div>
									<?php endfor; ?>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					</div>


					<p id="description_text" data-toggle="editable" data-target="span" title="click para editar el texto"><span>Todas las comisiones cumplen con la misma carga horaria, independientemente de los feriados que se presenten</span></p>

				</div>
				<div class="title-bar">
					<div class="wrapper">
						<div class="col">
							<h3>Información Adicional</h3>
							<ul>
								<li>Vencimientos</li>
								<?php $nm=1; for($i=$_periods[$_node]['month_min']; $i<$_periods[$_node]['month_max']; $i++): ?>
								<li><?= $nm+1 ?>° Cuota: 05/<?= $_periods[$_node]['month_min']+$nm < 10 ? '0'.($_periods[$_node]['month_min']+$nm) : $_periods[$_node]['month_min']+$nm ?></li>
								<?php $nm++; endfor; ?>
							</ul>
						</div>
						<div class="col">
							<h3>Formas de Pago</h3>
							<ul>
								<li>Efectivo</li>
								<li>Cheque a la Orden de Fundación Proydesa</li>
								<li>Tarjeta de Crédito</li>
								<li>Tarjeta de Débito</li>
								<li>Transferencia Electrónica</li>
								<li>Pagos mis Cuentas</li>
							</ul>
						</div>
					</div>
				</div>
				<p>&nbsp;</p>
				<div class="logos-courses">
					<img src="images/logos-redes.jpg" alt="">
				</div>
				<div class="footer">
					<p> academia@proydesa.org | [+54] 11 4327-1888 (líneas rotativas) | Suipacha 280 Entrepiso | Ciudad Autónoma de Buenos Aires | Argentina</p>
				</div>
			</div>
		</div>
		<div class="calendar-block">
			<a id="btn_download" href="#" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Generar Imagen</a>
			<p class="status-image">
				<?php if(!empty($_images)): ?>
				Ya se generó una o varias imágenes descargables:
				<ul>
					<?php foreach($_images as $img): ?>
					<li style="margin:4px 0">
						<a href="images/calendario/<?=$img['image']?>.jpg" target="_blank" title="descargar"><?=$img['image']?></a>
						<span data-btn="delete-image" data-id="<?=$img['id']?>" title="borrar" style="cursor:pointer" >(<i class="fa fa-times"></i>)</span>
					</li>
				<?php endforeach; ?>
				</ul>
				<?php endif; ?>
			</p>
		</div>
		<?php else: ?>
			<!--- --->
			<?php if(!empty($_images)): ?>
				<?php foreach($_images as $img): ?>
				<div class="calendar-block" style="margin-bottom:26px">
					<div style="text-align: center"><img src="images/calendario/<?=$img['image'].'.jpg?id='.rand(1111,9999)?>" alt="" style="width:100%;max-width: 1660px;margin:4px auto"></div>
					<hr>
					<a href="#" download="<?=$img['image']?>.jpg" class="btn btn-success"><i class="fa fa-download fa-fw"></i> Descargar Imagen</a>
				</div>
				<?php endforeach; ?>
			<?php else: ?>
				<div class="calendar-block">
					<p>No se encontró ninguna calendario para el ciclo lectivo seleccionado</p>
				</div>
			<?php endif; ?>
		<?php endif; ?>
	</div>
</div>
<?php if($H_USER->has_capability('calendario/cronograma')): ?>
<script src="libraries/javascript/html2canvas.js"></script>
<script src="libraries/javascript/canvas2image.js"></script>
<script type="text/javascript" src="<?= $HULK->javascript.'/introjs.min.js'?>"></script>
<script type="text/javascript" src="views/calendario/cronograma.js"></script>

<?php endif; ?>