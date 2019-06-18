<div class="column-c">
	<div class="calendar">
		<p><small><a href="#" onclick="start_tutorial()" >(ver tutorial)</a></small></p>
		<div class="calendar-block">
			<div class="header-wrapper">
				<h2>CALENDARIO </h2>
				<form class="years" onchange="this.submit()" method="post">
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
					<button class="btn"><i class="fa fa-refresh"></i></button>
				</form>
			</div>
		</div>
		<div class="calendar-block">
			<div class="courses-wrapper">
				<div class="block-course-edit">
					<h4>Generar Curso</h4>
					<div class="inner-block" id="course_generator">
						<input id="course_start" type="text" placeholder="día de inicio" > 
						<select id="course_classes">
							<option value="0">Cant. de Clases</option>
							<?php for($i=1;$i<=20;$i++): ?>
							<option value="<?= $i ?>"><?= $i ?></option>
							<?php endfor; ?>
						</select>
						<ul data-btn-group="days-group" class="days">
							<li data-value="l" class="day">L</li>
							<li data-value="m" class="day">M</li>
							<li data-value="w" class="day">W</li>
							<li data-value="j" class="day">J</li>
							<li data-value="v" class="day">V</li>
							<li data-value="s" class="day">S</li>
							<li data-value="d" class="day">D</li>
						</ul>
						<button data-btn="generate" class="btn btn-success btn-sm">Generar</button>
					</div>		
				</div>
				<div class="block-courses">
					<h4>Cursos generados </h4>
					<div id="courses" class="well"></div>
				</div>
			</div>
		</div>
		<?php if($H_USER->has_capability('calendario/edit')): ?>
		<div class="calendar-block filters">
			<div class="item" id="holidays_buttons" style="width:auto">
				<div data-btn-group="holiday-group" class="btn-group">
					<?php if($H_USER->has_capability('calendario/edit/holidays')): ?>
					<button data-btn="holiday" class="btn btn-primary btn-holiday active">Feriado Nacional</button>
					<?php endif; ?>
					<button data-btn="tech" class="btn btn-primary btn-tech <?= (!$H_USER->has_capability('calendario/edit/holidays') ? 'active' : '') ?>">Feriado Técnico</button>
					<button data-btn="erase" class="btn btn-primary btn-erase" title="reestablecer día"><i class="fa fa-eraser"></i></button>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="calendar-wrapper">
			<?php 
			$arrMonthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
			$arrDaysNames = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
			$arrDaysCodes = ['l','m','w','j','v','s','d'];
			$week = 1;
			$daynum = 1;
			for($mth=1; $mth<=12; $mth++):
				if($mth<3){
					$period=1;
				}else if($mth>=3 && $mth <8){
					$period=2;
				}else{
					$period=3;
				}
			?>
			<?php if(($mth-1)%4 == 0 ): ?>
			<div class="calendar-row">
			<?php endif; ?>
			<div class="month-outer" >
				<div class="month-wrapper" data-month="<?= $mth ?>" data-period="<?= $period ?>" >
					<?php 
					$calendar = new Calendar();
					$calendar->currentYear = $_year;
					$calendar->currentMonth = $mth;
					?>
					<div class="month-title"><?= $arrMonthNames[$mth-1] ?></div>
					<div class="days-wrapper">
					<?php
					for($dd=1;$dd<=7;$dd++){
						echo '<div class="day-cell day-name">'.$arrDaysNames[$dd-1].'</div>';
					}
					?>
					</div>
					<?php
					for( $i=0; $i<$calendar->weeksInMonth(); $i++ ){
						echo '<div class="days-wrapper" data-week="'.$week.'" >';
						$hasnull = false;
						for($j=1;$j<=7;$j++){
							$day = $calendar->showDay(($i*7)+$j);
							echo '<div class="day-cell '.(is_null($day) ? 'day-null' : $_calendar->findtype($daynum,$_selected)).'" '.(!is_null($day) ? 'data-daynum="'.$daynum.'" data-daycode="'.$arrDaysCodes[$j-1].'"' : '').' >'.$day.'</div>';
							$daynum = !is_null($day) ? $daynum+1 : $daynum;
							$hasnull = is_null($day) ? true : false;
						}
						echo '</div>';
						$week = $hasnull ? $week : $week+1;
					}
					?>
				</div>
			</div>
			<?php if($mth%4 == 0 ): ?>
			</div>
			<?php endif; ?>
			<?php endfor; ?>
		</div>
		<div class="calendar-block">
			<h4>Referencias:</h4>
			<div class="ref-wrapper">				
				<div class="mod mod-holiday">Feriado Nacional</div>
				<div class="mod mod-course">Inicio de Clases</div>
				<div class="mod mod-tech">Feriado Técnico</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?= $HULK->javascript.'/introjs.min.js'?>"></script>
<script>
var hascapability = '<?=$H_USER->has_capability('calendario/edit')?>';

var Calendar = {
	styles:['tech','holiday','course'],
	templates:{
		div:$('<div>'),
		i:$('<i>'),
		button:$('<button>'),
		checkbox:$('<input type="checkbox">'),
		course:function(){
			var mod = this.div.clone().addClass('mod-list').append(
				this.div.clone().addClass('buttons').append(
					this.checkbox.clone().clone()
				),
				this.div.clone().addClass('content').append(
					this.div.clone().addClass('caption')
				),
				this.div.clone().addClass('buttons').append(
					this.button.clone().addClass('btn btn-xs btn-danger delete').append(
						this.i.clone().addClass('fa fa-trash')
					)
				)
			);
			return mod;
		}
	},
	ajax:function(obj,callback){
		$('.day-cell,body').css('cursor','wait');
		$.ajax({
			type:'POST',
			url:'views/calendario/ajaxCalendario.php',
			data:obj,
			cache:false,
			dataType:'json',
			complete:function(data){
				$('.day-cell').css('cursor','pointer');
				$('body').css('cursor','auto');
				if(callback){
					callback(data.responseJSON);
				}
			},
			error:function(data){
				console.log(data.responseText);
				
			}
		});
	},
	buildclasess:function(obj){
		//console.log(obj.start);
		var start = obj.start.split('-');
		var totalclasses = obj.amount;
		var sumclasses = 0;
		var arrDaysCodes = obj.days.toLowerCase().split('');
		var daynum = obj.daynum;
		var countpanic = 0;
		//$('.day-cell[data-daynum="'+daynum+'"]').addClass('course').attr('data-idcourse',obj.id);
		while(sumclasses<totalclasses){
			countpanic++;
			$.each(arrDaysCodes,function(k,v){
				if($('.day-cell[data-daynum="'+daynum+'"]').attr('data-daycode') == v){
					if(!$('.day-cell[data-daynum="'+daynum+'"]').hasClass('holiday') && !$('.day-cell[data-daynum="'+daynum+'"]').hasClass('tech')){
						
						var courseids = [];
						if($('.day-cell[data-daynum="'+daynum+'"]').attr('data-idcourse')!=undefined){
							courseids = $('.day-cell[data-daynum="'+daynum+'"]').attr('data-idcourse').split(',');
							courseids.push(obj.id);
							$('.day-cell[data-daynum="'+daynum+'"]').addClass('course').attr('data-idcourse',courseids);
						}else{
							$('.day-cell[data-daynum="'+daynum+'"]').addClass('course').attr('data-idcourse',obj.id);
						}

						sumclasses++;
					}
				}
			});
			daynum++;
			if(countpanic==146){
				break;
			}
		}
	},
	resetcourses:function(){
		$('.day-cell').removeClass('course inactive');
	},
	getcourses:function(){
		$('#courses').html('');
		this.resetcourses();
		this.ajax({
			mode:'getcourses',
			year:$('select[name="years"]').val(),
			idperiod:$('select[name="periods"]').val()
		},function(data){
			if(data.results==null){return false;}
			$.each(data.results,function(k,v){
				console.log(v);
				var mod = Calendar.templates.course();
				mod.attr('data-id',v.id);
				mod.find('.delete,input[type="checkbox"]').attr('data-id',v.id);
				mod.find('.caption').html(($('select[name="periods"] option:selected').text())+' - Inicia: '+v.inicia+' - Termina: '+v.finaliza+' ('+v.amount+' clases - '+v.days+')');
				mod.find('input').prop('checked',true);
				$('#courses').append(mod);
				Calendar.buildclasess(v);
			});
		});
	},
	init:function(){
		if(hascapability=='1'){
			$('.calendar').on('click','.day-cell:not(.day-null,.day-name)',$.proxy(function(btn){
				var type = $('[data-btn-group="holiday-group"] button.active').attr('data-btn');
				var hascourse = $(btn.currentTarget).hasClass('course');
				

				this.ajax({
					mode:'save',
					date:$('select[name="years"]').val()+'-'+$(btn.currentTarget).parent().parent().attr('data-month')+'-'+$(btn.currentTarget).text(),
					daynum:$(btn.currentTarget).attr('data-daynum'),
					daycode:$(btn.currentTarget).attr('data-daycode'),
					type:type,
				},function(data){
					if(data.status != 'ok'){
						alert(data.message); 
						return false;
					}
					$(btn.currentTarget).removeClass(Calendar.styles.join(' '));
					if(type!='erase'){
						$(btn.currentTarget).addClass(type);
					}
						
					Calendar.getcourses();
				});			
				
			},this));
		}

		$('[data-btn-group="holiday-group"]').on('click','button',function(btn){
			$('[data-btn-group="holiday-group"] button').not(btn.currentTarget).removeClass('active');
			$(btn.currentTarget).addClass('active');
		});
		
		$('[data-btn-group="days-group"]').on('click','li',function(btn){
			$(btn.currentTarget).toggleClass('active');
		});
		/////////// GENERAR CURSO //////////
		$('[data-btn="generate"]').on('click',$.proxy(function(btn){
			if($('#course_start').val()==''){
				alert('Debes elegir una fecha de inicio');
				return false;
			}
			if($('#course_classes').val()=='' || $('#course_classes').val()==0){
				alert('Debes elegir la cantidad de clases');
				return false;
			}
			var daypass = false;
			var days = '';
			$.each($('[data-btn-group="days-group"] li'),function(){
				if($(this).hasClass('active')){
					daypass = true;
					days += $(this).text();
				}
			});
			if(!daypass){
				alert('Debes elegir al menos un día de cursada.');
				return false;
			}
			//////////////////////////
			this.ajax({
				mode:'savecourse',
				idperiod:$('select[name="periods"]').val(),
				start:$('#course_start').val(),
				amount:$('#course_classes').val(),
				days:days
			},function(){
				Calendar.getcourses();
				$('#course_start').val('');
				$('[data-btn-group="days-group"] li').removeClass('active');
			});
		},this));
		$('#courses').on('click','.delete',$.proxy(function(btn){
			var id = $(btn.currentTarget).attr('data-id');
			//$('#courses .mod-list[data-id="'+id+'"]').remove();
			this.ajax({
				mode:'deletecourse',
				id:id
			},function(){
				Calendar.getcourses();
			});
		},this));
		////////////////////////////////////
		$('#courses').on('change','input[type="checkbox"]',$.proxy(function(btn){
			var id = $(btn.currentTarget).attr('data-id');

			/*if(!btn.currentTarget.checked){
				$('.day-cell[data-idcourse="'+id+'"]').addClass('inactive');
			}else{
				$('.day-cell[data-idcourse="'+id+'"]').removeClass('inactive');
			}*/


			var hideids = [];
			$.each($('#courses input[type="checkbox"]'),function(k,v){
				if(!$(this).is(':checked')){
					hideids.push($(this).attr('data-id'));
				}
			});
			$.each($('.day-cell[data-idcourse]'),function(){
				var ids = $(this).attr('data-idcourse').split(',');
				var hits = 0;
				$.each(ids,function(kc,vc){
					$.each(hideids,function(kh,vh){
						if(vh==vc){hits++;}
					});
				});
				if(hits==ids.length){
					$(this).addClass('inactive');
				}else{
					$(this).removeClass('inactive');					
				}
			});
			

		},this));
		////////////////////////////////////
		var maxDate = new Date($('select[name="years"]').val(),parseInt($('select[name="periods"] option:selected').attr('data-max')),1);
		var dayOfMonth = maxDate.getDate();
		maxDate.setDate(dayOfMonth - 1);
		$('#course_start').datepicker({
			dateFormat:'dd/mm/yy',
			minDate:new Date($('select[name="years"]').val(),parseInt($('select[name="periods"] option:selected').attr('data-min'))-1,1),
			maxDate:new Date(maxDate),
			firstDay:1,
			dayNamesMin:["Do", "Lu", "Ma", "Mi", "Ju", "Vi", "Sa"],
			monthNames: [ "Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre" ]
		});
		this.getcourses();
	}
}
$(function(){
	Calendar.init();
});


///Tutorial
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
			element:document.querySelector('[name="years"]'),
			intro:'Elige el año en el cual se van a ver y aplicar los cambios.'
		},
		{
			element:document.querySelector('[name="periods"]'),
			intro:'Seleccionar el período para poder generar cursos y ver los que ya han sido generados.'
		},
		{
			element:document.querySelector('#course_generator'),
			intro:'Con esta herramienta podrás generar cursos para el período seleccionado. Indicar el día de inicio, la cantidad de clases y los días de cursada. Los cursos generados actúan como simulación y servirá luego para poder ver el cronograma del ciclo lectivo. Esta herramienta no sirve para crear comisiones.'
		},
		{
			element:document.querySelector('#courses'),
			intro:'Listado de los cursos ya generados del período seleccionado. Podrás mostrar/ocultar los días de cursada de cada curso en el calendario a través del checkbox. También tienes la posibilidad de borrar alguno con el botón <i class="fa fa-trash"></i>.'
		},
		{
			element:document.querySelector('#holidays_buttons'),
			intro:'Click en el alguno de los botones y luego selecciona el día dentro del calendario para aplicarlo. Al tocar en cada día se guarda automáticamente.'
		},
		{
			element:document.querySelector('.month-wrapper'),
			intro:'Tocar en el día donde se aplicará o se quitará el feriado (según lo que se haya seleccionado en el paso anterior).'
		}
	]
});

</script>