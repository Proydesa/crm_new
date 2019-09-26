var ResizeHeader = function(){
	$.each($('#listado thead:eq(1) > tr > th'),function(k,v){
		$('#table_fixed thead:eq(1) th:eq('+k+')').width($(this).width());
	});
}
var BuildHeader = function(){
	var head1 = $('#listado thead:eq(0)').clone();
	var head2 = $('#listado thead:eq(1)').clone();
	$('#table_fixed').append(head1);
	$('#table_fixed').append(head2);
	ResizeHeader();
}
var MakeLink = function(str,ids,btn){
	return '<a href="#" onClick="javascript:window.open(\'hd.php?v=lista_notification&id='+ids+'\',\'Notificar\',\'width=800px,height=600px\')" '+(btn===true?'class="btn btn-xs btn-primary"':'')+' >'+str+'</a>';

}
var NotifyAttendance = function(){

	var arrFaltas = [];
	var arrCuotas = [];
	$.each($('tr[data-userid]'),function(ka,va){
		arrFaltas.push({userid:$(va).attr('data-userid'),username:$(va).attr('data-username')});
		arrCuotas.push({userid:$(va).attr('data-userid'),username:$(va).attr('data-username')});
		arrFaltas[ka].asistencias = [];
		/////////////////////
		var sumfalta = 0;
		if($(this).find('td[data-attendance]').attr('data-attendance') != ''){
			$.each($(this).find('td[data-attendance]'),function(kf,vf){
				if($(vf).attr('data-attendance')!=''){
					arrFaltas[ka].asistencias.push($(this).attr('data-attendance'));
				}
				if($(vf).attr('data-attendance')=='A'){
					sumfalta++;
				}
			});
			arrFaltas[ka].faltas = sumfalta;
		}
		/////////////////////
		var sumcuota = 0;
		if($(this).find('td[data-cuota]').attr('data-cuota') != ''){
			$.each($(this).find('td[data-cuota]'),function(kf,vf){
				if( parseFloat($(vf).attr('data-cuota')) < 0 ){
					sumcuota++;
				}
			});
			arrCuotas[ka].cuotas = sumcuota;
		}
	});
	///////// FALTAS //////////////////
	var alumnWarn = '';
	var alumnWarnID = [];
	var alumnDanger = '';
	var alumnDangerID = [];
	var nmAlumnW = 0;
	var nmAlumnD = 0;
	//console.log(arrFaltas[1].asistencias);
	$.each(arrFaltas,function(kf,vf){
		var user = '<a href="#" onClick="javascript:window.open(\'hd.php?v=listaplus&id='+vf.userid+'\',\'Notificar\',\'width=800,height=600\')">'+vf.username+'</a>';
		///var user = MakeLink(vf.username,vf.userid,false);
		if(vf.asistencias.length==1){
			if(vf.asistencias[vf.asistencias.length-1]=='A' ){
				alumnWarn += user+', ';
				alumnWarnID.push(vf.userid);
				nmAlumnW++;
			}
		}
		if(vf.asistencias.length>1){
			if(vf.asistencias[vf.asistencias.length-1]=='A' && vf.asistencias[vf.asistencias.length-2]=='A'){
				alumnDanger += user+', ';
				alumnDangerID.push(vf.userid);
				nmAlumnD++;
			}else if(vf.asistencias[vf.asistencias.length-1]=='A' && vf.asistencias[vf.asistencias.length-2]!='A'){
				alumnWarn += user+', ';
				alumnWarnID.push(vf.userid);
				nmAlumnW++;
			}
		}
	});

	if(alumnWarn != ''){
		$('#faltas_warn').html(alumnWarn+' ha'+(nmAlumnW>1?'n':'')+' faltado a la última clase<br /><br />').append(MakeLink('Notificar a Todos',alumnWarnID.join(','),true));
	}else{
		$('#faltas_warn').hide();
	}
	if(alumnDanger != ''){
		$('#faltas_danger').html(alumnDanger+' ha'+(nmAlumnD>1?'n':'')+' faltado a las 2 últimas clases<br /><br />').append(MakeLink('Notificar a Todos',alumnDangerID.join(','),true));
	}else{
		$('#faltas_danger').hide();
	}
	//////// CUOTAS /////////////////
	var cuotaWarn = '';
	var cuotaWarnID = [];
	var cuotaDanger = '';
	var cuotaDangerID = [];
	var nmCuotaW = 0;
	var nmCuotaD = 0;
	$.each(arrCuotas,function(kf,vf){
		var user = '<a href="#" onClick="javascript:window.open(\'hd.php?v=listaplus&id='+vf.userid+'\',\'Notificar\',\'width=800,height=600\')">'+vf.username+'</a>';
		if(vf.cuotas == 1){
			cuotaWarn += user+', ';
			nmCuotaW++;
			cuotaWarnID.push(vf.userid);
		}
		if(vf.cuotas > 1){
			cuotaDanger += user+', ';
			cuotaDangerID.push(vf.userid);
			nmCuotaD++;
		}
	});
	//////////////////////////////////////////////
	if(cuotaWarn != ''){
		$('#cuotas_warn').html(cuotaWarn+' debe'+(nmCuotaW>1?'n':'')+' 1 cuota<br /><br />').append(MakeLink('Notificar a Todos',cuotaWarnID.join(','),true));
	}else{
		$('#cuotas_warn').hide();
	}
	if(cuotaDanger != ''){
		$('#cuotas_danger').html(cuotaDanger+' debe'+(nmCuotaD>1?'n':'')+' 2 cuotas o más<br /><br />').append(MakeLink('Notificar a Todos',cuotaDangerID.join(','),true));
	}else{
		$('#cuotas_danger').hide();
	}
}
$(function(){
	$(window).scroll(function(){
		if($(this).scrollTop() > $('#listado').offset().top+10){
			$('.table-header-fixed').removeClass('dp-none');
		}else{
			$('.table-header-fixed').addClass('dp-none');
		}
	});
	$(window).resize(function(){
		ResizeHeader();
	});
	BuildHeader();
	NotifyAttendance();
});