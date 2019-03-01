
<div class="column-c" style="width:90%">

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
						<h1 class="title">Calendario Ciclo Lectivo</h1>
						<h1 class="title"><?= $_periods[$_node]['period'].' '.$_year ?></h1>
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
									
									<div class="month-row<?= (preg_match('/Nacional/', $days) ? ' holiday' : '').(preg_match('/Técnico/', $days) ? ' tech' : '') ?>" title="(click para ocultar este día)">
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


					<p id="description_text" title="click para editar el texto"><span>Todas las comisiones cumplen con la misma carga horaria, independientemente de los feriados que se presenten</span></p>
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
				<?php if(file_exists('images/calendario/grilla-'.$_year.'-'.$_period.'.jpg')): ?>
				Ya se generaró una imagen descargable, <a href="images/calendario/grilla-<?=$_year.'-'.$_period?>.jpg" target="_blank">click aquí</a> para verla
				<?php endif; ?>
			</p>
		</div>

		<?php else: ?>

			<!--- --->
			<?php if(file_exists('images/calendario/grilla-'.$_year.'-'.$_period.'.jpg')): ?>
			
			<div style="text-align: center"><img src="images/calendario/grilla-<?=$_year.'-'.$_period.'.jpg?id='.rand(1111,9999)?>" alt="" style="width:100%;max-width: 1660px;margin:4px auto"></div>		

			<div class="calendar-block">
				<a href="#" download="grilla-<?=$_year.'-'.$_period?>.jpg" class="btn btn-success"><i class="fa fa-download fa-fw"></i> Descargar Imagen</a>
			</div>
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

<script>
$(function(){
	var TheCanvas='';	
	$('#btn_download').unbind('click').on('click',function(e){
		e.preventDefault();
		html2canvas($('.schedule')).then(function(canvas) {
			TheCanvas = canvas;
			TheCanvas = Canvas2Image.convertToJPEG(canvas);
			///$('#btn_hidden').attr({'href':TheCanvas.src}).find('span').trigger('click');
			///return console.log(TheCanvas.src);
			var ajx = $.ajax({
				type:'POST',
				url:'views/calendario/ajaxCalendario.php',
				data:{
					rawdata:TheCanvas.src,
					filename:'grilla-'+$('[name="years"]').val()+'-'+$('[name="periods"]').val(),
					mode:'generate_image'
				},
				dataType:'json',
				cache:false
			});
			ajx.done(function(data){
				if(data.status != 'ok') alert(data.message);
				$('.status-image').html('La imagen se generó con éxito. <a href="images/calendario/grilla-'+$('[name="years"]').val()+'-'+$('[name="periods"]').val()+'.jpg?id='+Math.random(1111,9999)+'" target="_blank">click para verla</a>');
			});
			ajx.fail(function(data){z
				alert('Hubo un error al generar la imagen. Intenta nuevamente.');
			});
		});
	});

	$('#description_text').on('click','span',function(){
		var text = $(this).text();
		$(this).parent().html('<textarea rows="5" style="width:100%">'+text+'</textarea>');
		$('#description_text textarea').focus();
	});
	$('#description_text').on('focusout','textarea',function(){
		var text = $(this).val();
		$(this).parent().html('<span>'+text+'</span>');
	});

	$('.calendar .month-row').click(function(){
		$(this).parent().append('<div class="month-row blank"><div class="day"><div class="num">&nbsp;</div><div class="dayname">&nbsp;</div></div><div class="caption">-</div></div>');
		$(this).remove();
	})
});	

var intro = introJs();
var start_tutorial = function(){
	intro.start();
}
intro.setOptions({
	prevLabel:'Anterior',
	nextLabel:'Siguiente',
	doneLabel:'Listo',
	skipLabel:'Cerrar',
	showButtons:true,
	hidePrev:true,
	steps:[
		{
			element:document.querySelector('.years'),
			intro:'Elige el año y el período para ver el cronograma del ciclo lectivo.'
		},
		{
			element:document.querySelector('.month-row'),
			intro:'Click en cada fila para ocultarla para luego tomar la captura en imagen.'
		},
		{
			element:document.querySelector('#btn_download'),
			intro:'Click para guardar una imagen del calendario. Esta imagen luego podrá ser vista y descargada por los usuarios que no tengan permiso de edición de esta pantalla.'
		}
	]
});

</script>
<?php endif; ?>