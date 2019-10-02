$(function(){
	var TheCanvas='';
	$('#btn_download').unbind('click').on('click',function(e){
		e.preventDefault();
		html2canvas($('.schedule')).then(function(canvas) {
			TheCanvas = canvas;
			TheCanvas = Canvas2Image.convertToJPEG(canvas);
			var ajx = $.ajax({
				type:'POST',
				url:'views/calendario/ajaxCalendario.php',
				data:{
					rawdata:TheCanvas.src,
					year:$('[name="years"]').val(),
					period:$('[name="periods"]').val(),
					mode:'generate_image'
				},
				dataType:'json',
				cache:false
			});
			ajx.done(function(data){
				if(data.status != 'ok') alert(data.message);
				window.location.reload();
			});
			ajx.fail(function(data){z
				alert('Hubo un error al generar la imagen. Intenta nuevamente.');
			});
		});
	});

	$('[data-btn="delete-image"]').click(function(){
		var id = $(this).attr('data-id');
		var ajx = $.ajax({
			type:'POST',
			url:'views/calendario/ajaxCalendario.php',
			data:{
				mode:'delete_image',
				id:id
			},
			dataType:'json',
			cache:false
		})
		.done(function(data){
			if(data.status != 'ok') alert('Hubo un error al generar la imagen. Intenta nuevamente.');
			window.location.reload();
		})
		.fail(function(data){
			alert('Hubo un error al generar la imagen. Intenta nuevamente.');
			console.log(data);
		});
	});

	$('[data-toggle=editable]').on('click',function(){
		var target = $(this).attr('data-target');
		var text = $(this).find(target).text();
		$(this).find(target).hide();
		$(this).append('<textarea rows="5" style="width:100%">'+text+'</textarea>');
		$(this).find('textarea').focus();
	});
	$('[data-toggle=editable]').on('focusout','textarea',function(){
		var target = $(this).parent().attr('data-target');
		var text = $(this).val();
		$(this).parent().find(target).show().html(text);
		$(this).parent().find('textarea').remove();
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
			intro:'Click para guardar una imagen del calendario. Esta imagen luego podrá ser vista y descargada por los usuarios que no tengan permiso de edición de esta pantalla. Puedes generar más de una imagen para este cronograma. Se listarán debajo con la posibilidad de borrar cada uno.'
		}
	]
});
