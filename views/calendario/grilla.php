
<div class="column-c" style="width:100%">

	<div class="calendar">
		<p><small><a href="#" onclick="start_tutorial()">(ver tutorial)</a></small></p>
		<div class="calendar-block no-print">
			<div class="header-wrapper">
				<h2>Grilla de Aulas</h2>
				
				<form class="years" method="post" autocomplete="off" style="display:inline-block;">
					<span>Elegir Rango de Fechas:</span> 

					<input name="startdate" id="date_start" type="text" placeholder="Inicio" value="<?= isset($_POST['startdate']) ? $_POST['startdate'] : date('d/m/Y') ?>" required >
					<input name="finishdate" id="date_finish" type="text" placeholder="Fin" value="<?= isset($_POST['finishdate']) ? $_POST['finishdate'] : date('d/m/Y',time()+604800) ?>" required >
					<button class="btn btn-success" type="submit">Obtener</button>
				</form>

			</div>

		</div>

		<?php if(!empty($_POST)): ?>

		<div class="calendar-block no-print">
			<h4>Crear Evento Especial</h4>
			<form id="form_special" class="special-events" >
				<div class="mod">
					<input name="name" type="text" placeholder="Nombre del Curso / Instructor" required>
				</div>
				<div class="mod">
					<select name="day" required >
						<option value="">-- Día --</option>
						<?php $nn=0; for($i=date('z',strtotime($_startdate));$i<=date('z',strtotime($_finishdate));$i++): ?>
						<option value="<?= date('z',strtotime($_startdate)+(86400*$nn)) ?>"><?= $_daynames[date('N',strtotime($_startdate)+(86400*$nn))-1].' '.date('d',strtotime($_startdate)+(86400*$nn)) ?></option>
						<?php $nn++; endfor; ?>
					</select>
				</div>
				
				<div class="mod">
					<select name="turno" required >
						<option value="">-- Turno --</option>
						<?php foreach($_schedules as $turno): ?>
						<option value="<?= $turno['turno'] ?>"><?= $turno['turno'].' ('.$turno['desc_franja_horaria'].')' ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="mod">
					<select name="flag" required >
						<option value="">-- Etiqueta --</option>
						<option value="stp">STP</option>
						<option value="recovery">Recuperación</option>
						<option value="special">Especial</option>
					</select>
				</div>
				<div class="mod">
					<select name="aula" required >
						<option value="">-- Aula --</option>
						<?php foreach($_aulas as $aula): ?>
						<option value="<?= $aula['id'] ?>"><?= $aula['name'].' ('.$aula['capacity'].')' ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="mod">
					<button id="btn_special" class="btn btn-success" type="submit">Crear</button>
				</div>
			</form>
		</div>


		
		<div class="calendar-block">

			<div class="grid">
				<h3 class="main-title">Grilla de aulas Período <?= $_period.' semana del '.date('d',strtotime($_startdate)).' de '.$_monthnames[date('n',strtotime($_startdate))-1].' al '.date('d',strtotime($_finishdate)).' de '.$_monthnames[date('n',strtotime($_finishdate))-1].' de '.date('Y',strtotime($_startdate)) ?></h3>

				<p class="course-overlap"><span class="mod mod-overlap">Hay superposición de cursos en un aula</span></p>


				<table class="table-grid" cellpadding="0" cellspacing="1">
					<thead>
						<tr>
							<th>Día</th>
							<th>Hora</th>
							<?php foreach($_aulas as $aula): ?>
							<th><?= $aula['name'].' ('.$aula['capacity'].')' ?></th>
							<?php endforeach; ?>
						</tr>
					</thead>
					<tbody>

						<?php $num=0; for($i=date('z',strtotime($_startdate));$i<=date('z',strtotime($_finishdate));$i++): ?>
						<tr data-day="<?= $i ?>">
							<td >
								<div class="tag"><?= $_daynames[date('N',strtotime($_startdate)+(86400*$num))-1].' '.date('d',strtotime($_startdate)+(86400*$num)) ?></div>
							</td>
							<td>
								<?php foreach($_schedules as $time): ?>
								<div class="row turnos">
									<div class="data">
										<span class="code"><?= $time['desc_turno'] ?></span> 
										<span class="desc"><?= $time['desc_franja_horaria'] ?></span>
									</div>
								</div>
								<?php endforeach; ?>
							</td>

							<!-- GET COURSES -->
							<?php foreach($_aulas as $aula): ?>
							<td data-aula="<?= $aula['id'] ?>" class="course-cell">
								<?php foreach($_schedules as $time): ?>
								<div class="row">&nbsp;</div>
								<?php endforeach; ?>

								<?php 
								if($_courses = $LMS->getCourseByDay(date('Y-m-d',(strtotime($_startdate)+(86400*$num))),$aula['id'])): 
									foreach($_courses as $course):
										//$h = explode(' a ',$course['horario']);
										//$from = explode(':', $h[0]);
										//$to = explode(':', $h[1]);
										//$top = (($from[0]-8)*(18*2))+($from[1] == '30' ? 18 : 0);
										$h_parsed = date_parse($course['inicio']);
										$top = 0;
										$modposition = 'top';
										if($h_parsed['hour']>=18){
											$top = 49*2;
											$modposition = 'bottom';
										}
										if($h_parsed['hour']>=13 && $h_parsed['hour']<18){
											$top = 49;
											$modposition = 'middle';
										}
										if($course['intensivo']){
											$modcolor = 'mod-intensivo';
										}else{
											$modcolor = 'mod-regular';
										}
										if(preg_match('/track/i', $course['cursomodelo'])){
											$modcolor = 'mod-fastrack';
										}
										if(preg_match('/furukawa/i', $course['cursomodelo'])){
											$modcolor = 'mod-furukawa';
										}
										if(preg_match('/cableado/i', $course['cursomodelo'])){
											$modcolor = 'mod-furukawa';
										}
										if(preg_match('/empleartec/i', $course['cursomodelo'])){
											$modcolor = 'mod-empleartec';
										}
										if(preg_match('/workshop/i', $course['cursomodelo'])){
											$modcolor = 'mod-workshop';
										}
										/*$node = 0;
										foreach($_schedules as $k=>$time){
											if($course['horario'] == $time['turno']){
												$node = $k;
											}
										}*/
								?>
								<div class="row <?= $modcolor ?> mod row-1" data-mod-position="<?= $modposition ?>" style="top:<?= $top ?>px" >
									<div class="remove">x</i></div> 
									<div class="data">
										<span class="name"><?= $course['cursomodelo_shortname'] ?></span>
										<span class="instructor"> <?= $course['instructor'] ?></span>
									</div>
								</div>
								<?php endforeach; endif; ?>

								<?php if($aula['id'] == 26): ?>
								<div class="row mod mod-testingcenter row-1" data-duration="4" style="top:<?= 49 ?>px"> 
									<div class="remove">x</i></div>
									<span class="name">Testing Center</span>
								</div>
								<?php endif; ?>


							</td>
							<?php endforeach; ?>
						</tr>
						<?php $num++; endfor; ?>

					</tbody>
				</table>

				<hr>
				<h5>Referencias:</h5>
				<div class="table-ref">
					<div class="mod mod-regular" title="Curso regular">Regular</div>
					<div class="mod mod-intensivo" title="Curso intensivo">Intensivo</div>
					<div class="mod mod-fastrack">Fastrack</div>
					<div class="mod mod-furukawa" title="Carreras Furukawa">Furukawa</div>
					<div class="mod mod-empleartec">Empleartec</div>
					<div class="mod mod-testingcenter" title="Person Vue">Testing Center</div>
					<div class="mod mod-workshop" title="Seminarios pagos">Workshop</div>
					<div class="mod mod-special" title="Recuperación de una clase perdida por motivos de fuerza mayor | del instructor | etc">Especial</div>
					<div class="mod mod-recovery" title="Esapacio de recuperación">Recuperación</div>
					<div class="mod mod-stp" title="Servicio técnico profesional">STP</div>					
				</div>
			</div>
		</div>

		<div class="calendar-block no-print">
			<a id="btn_download" href="#" class="btn btn-success"><i class="fa fa-save fa-fw"></i> Descargar Grilla</a>
			<a id="btn_hidden" href="#" download="<?= 'grilla-'.rand(11111,99999) ?>.jpg" style="display:none"><span>image</span></a>
		</div>

		<?php endif; ?>

	</div>
</div>

<!-- MESSAGES -->
<div id="messages" >
	<div class="content">
		<div class="header">
			<button type="button" class="close" data-button="close"><i class="fa fa-times"></i></button>
		</div>
		<div class="body" ></div>
		<div class="footer">
			<button type="button" data-button="ok" class="btn btn-success">Confirmar</button>
		</div>
	</div>
</div>



<script src="libraries/javascript/html2canvas.js"></script>
<script src="libraries/javascript/canvas2image.js"></script>
<script type="text/javascript" src="<?= $HULK->javascript.'/introjs.min.js'?>"></script>

<script>
var schedules = '<?= json_encode($_schedules) ?>';
var Messages = {
	dialog:function(obj){
		if(obj.show){
			$('#messages').addClass('active').find('.body').html(obj.message);
		}else{
			$('#messages').removeClass('active').find('.body').html('');
		}
		$('#messages [data-button="close"]').unbind('click').click(function(){
			Messages.dialog({show:false});
		});
		if('callback' in obj){
			$('#messages [data-button="ok"]').show().unbind('click').click(function(){
				Messages.dialog({show:false});
				obj.callback();
			});			
		}else{
			$('#messages [data-button="ok"]').hide();
		}
	}
}
var Overlapping = {	
	build:function(){
		$.each($('.table-grid .course-cell'),function(kc,vc){
			var _this_cell = this;
			if($(this).find('.mod').length>1){
				$.each($(this).find('.mod'),function(km,vm){
					if(km>0){
						var top = $(this).offset().top;
						var left = $(this).offset().left;
						var prevtop = $(this).prev('.mod').offset().top;
						var prevleft = $(this).prev('.mod').offset().left;
						if(top==prevtop && left==prevleft){
							$(this).addClass('row-overlap');
						}
					}
				});
			}
		});
		this.check();
	},
	check:function(){
		if($('.table-grid .course-cell .row-overlap').length==0){
			$('.course-overlap').hide();
		}
	}
}
var drag_init = function(){
	$('.table-grid .mod').draggable({
		grid:[$('.table-grid tbody td:nth-of-type(3)').width()+0.8,49.3],
		stop:function(e,ui){
			$(this).removeClass('row-overlap');
			Overlapping.build();
		}
	});
}
$(function(){
	$('#date_start,#date_finish').datepicker({
		dateFormat:'dd/mm/yy',
		firstDay:1,
		dayNamesMin:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
		monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
	});	
	
	$('#btn_download').unbind('click').on('click',function(e){
		e.preventDefault();
		$('.grid').css({overflow:'visible'});
		html2canvas($('.table-grid')).then(function(canvas) {
			TheCanvas = canvas;
			TheCanvas = Canvas2Image.convertToJPEG(canvas);
			$('#btn_hidden').attr({'href':TheCanvas.src}).find('span').trigger('click');
		});
	});

	$('#form_special').submit(function(e){
		e.preventDefault();
		var obj = {};
		$.each($(this).find('[name]'),function(k,v){
			obj[$(this).attr('name')] = $(this).val();
		});
		///////////////////
		var node = 0;
		var turnos = $.parseJSON(schedules);
		$.each(turnos,function(k,v){
			if(v.turno == $('#form_special select[name="turno"]').val()){
				node = k;
			}
		});
		var top = 49*node;
		///////////////////
		$(this).find('[name]').val('');
		$('.table-grid tbody tr[data-day="'+obj.day+'"] td[data-aula="'+obj.aula+'"]').append('<div class="mod mod-'+obj.flag+' row row-1" style="top:'+top+'px" style="z-index:2"><div class="data"><span class="instructor">'+obj.name+'</span></div> <div class="remove">x</i></div></div>');

		drag_init();
	});

	$('.table-grid .mod').on('click','.remove',function(){
		var _this = this;
		Messages.dialog({
			show:true,
			message:'¿Está seguro que deseas quitar este curso? No se borrarán datos, sólo se quitará de la grilla. Al recargar la página volverá a aparecer.',
			callback:function(){
				$(_this).parent().remove();
			}
		})
	});

	
	Overlapping.build();
	drag_init();
	
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
			intro:'Elige el rango de fechas para obtener los cursos creados junto a la grilla de aulas y horarios. La información reflejada aquí está conformada por los días de cursada de cada curso ya creado.'
		},
		{
			element:document.querySelector('#form_special'),
			intro:'Puedes crear un evento especial para mostrar en la grilla. Ten en cuenta al momento de crearlo que no haya algún curso en el mismo día, horario y aula. Una vez creado cada evento especial podrás moverlo de lugar o eliminiarlo y crearlo nuevamente. Todos los campos son obligatorios.'
		},
		{
			element:document.querySelector('.course-cell .mod'),
			intro:'Cada clase estará representada con un módulo de color con el nombre del curso y del instructor. Las referencias de colores se encuentra al pie de la página. Cada módulo puede ser arrastrado y movido en cualquier otro casillero dentro de la grilla.'
		},
		{
			element:document.querySelector('#btn_download'),
			intro:'Puedes generar y descargar una imagen de la grilla desde aquí.'
		},
		{
			intro:'Cuando existan más de un curso en una misma aula para un mismo día y horario, cada módulo superpuesto se destacará con un recuadro rojo.'
		}
	]
});
</script>