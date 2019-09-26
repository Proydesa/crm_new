var Messages = {
	dialog:function(obj){
		if(obj.show){
			$('#add_holiday').addClass('active').find('.body').html(obj.message);
		}else{
			$('#add_holiday').removeClass('active').find('.body').html('');
		}
		$('#add_holiday [data-button="close"]').unbind('click').click(function(){
			Messages.dialog({show:false});
		});
		if('callback' in obj){
			$('#add_holiday [data-button="ok"]').show().unbind('click').click(function(){
				Messages.dialog({show:false});
				obj.callback();
			});			
		}else{
			$('#add_holiday [data-button="ok"]').hide();
		}
	}
}
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
				//console.log(v);
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
	save_holiday:function(obj){
		this.ajax({
			mode:'save',
			date:obj.date,
			daynum:obj.daynum,
			daycode:obj.daycode,
			type:obj.type,
			courses:obj.courses
		},function(data){
			if(data.status != 'ok'){
				alert(data.message); 
				return false;
			}
			$(obj.btn.currentTarget).removeClass(Calendar.styles.join(' '));
			if(obj.type!='erase'){
				$(obj.btn.currentTarget).addClass(obj.type);
			}
				
			Calendar.getcourses();
		});

	},
	init:function(){
		if(hascapability=='1'){


			$('.calendar').on('click','.day-cell:not(.day-null,.day-name)',$.proxy(function(btn){
				var type = $('[data-btn-group="holiday-group"] button.active').attr('data-btn');
				var hascourse = $(btn.currentTarget).hasClass('course');

				var month = $(btn.currentTarget).parent().parent().attr('data-month');
				var year = $('select[name="years"]').val();
				var day = $(btn.currentTarget).text();
				var daynum = $(btn.currentTarget).attr('data-daynum');
				var daycode = $(btn.currentTarget).attr('data-daycode');

				$('#add_holiday .courses').html('');


				Promise.all([
					ajax('views/calendario/ajaxCalendario',{
						mode:'get_courses_by_day',
						date:year+'-'+month+'-'+day
					}),
					get_template('change-day-course'),
					get_template('add-holiday-form')
				])
					.then(function(promise){
						var data = promise[0];

						if(type=='erase' || data.results==false) {
							Calendar.save_holiday({
								date:year+'-'+month+'-'+day,
								type:type,
								daynum:daynum,
								daycode:daycode,
								btn:btn,
								courses:[]
							});
							return false;
						};


						var form = $(promise[2]);
						form.find('.date').html(day+'/'+month+'/'+year);

						$.each(data.results, function(k,v){
							var mod = $(promise[1]);
							mod.find('.course a').text(v.fullname).attr('href','courses.php?v=view&id='+v.id);
							mod.find('input').attr({
								'data-courseid':v.id,
								'data-sessid':v.sessid,
								'data-sessdate':v.sessdate,
								'data-attendanceid':v.attendanceid,
								'data-duration':v.duration
							});
							form.find('.courses').append(mod);

						});
						$('#add_holiday .body').append(form);


						$('[data-toggle="datepicker"]').datepicker({
							setDate:day+'/'+month+'/'+year,
							dateFormat:'dd/mm/yy'
						});


						Messages.dialog({show:true});

						$('#add_holiday form').submit(function(e){
							e.preventDefault();
							var courses = [];
							$.each($('#add_holiday form input[type="text"]'),function(k,v){								
								courses.push({
									courseid:$(this).attr('data-courseid'),
									newdate:$(this).val(),
									olddate:$(this).attr('data-sessdate'),
									sessid:$(this).attr('data-sessid'),
									attendanceid:$(this).attr('data-attendanceid'),
									duration:$(this).attr('data-duration')
								});
							});

							Calendar.save_holiday({
								date:year+'-'+month+'-'+day,
								type:type,
								daynum:daynum,
								daycode:daycode,
								btn:btn,
								courses:courses
							});

							///console.log(courses,year+'-'+month+'-'+day)
							Messages.dialog({show:false});
						});

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
	//Messages.dialog({show:true})
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