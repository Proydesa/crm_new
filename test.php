<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>
		CRM
	</title>
	<meta name="icon" href="favicon.ico" sizes="32x32" ></meta> 
	<!-- CSS del JqueryUI -->
	<link href="themes/verdesin/jquery-ui.css" rel="stylesheet" type="text/css"  />
	<!-- Jquery y JqueryUI -->
	<script type="text/javascript" src="libraries/javascript/jquery-1.4.4.min.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery-ui-1.8.11.min.js"></script>
	<!-- Javascript y CSS del menu -->
	<script type="text/javascript" src="libraries/javascript/fg_menu/fgmenu.pack.js"></script>
	<link type="text/css" href="libraries/javascript/fg_menu/fg.menu.css" media="screen" rel="stylesheet" ></link>
	<!-- Javascript adicionales (tabla, highlight) -->
	<script type="text/javascript" src="libraries/javascript/jquery.table.pack.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.watermark.pack.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.validate.pack.js"></script>			
	<script type="text/javascript" src="libraries/javascript/jquery.autoresize.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.columns.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.jeditable.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.ui.tabs.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.ui.progressbar.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.tablesorter.min.js"></script>
	<script type="text/javascript" src="libraries/javascript/jquery.tablesorter.widgets.min.js"></script>		
	<script type="text/javascript" src="libraries/javascript/jquery.tablesorter.pager.js"></script>		
	<link type="text/css" href="libraries/javascript/theme.jui.css" rel="stylesheet">
	<!-- Correccions y extras al estilo general -->
	<link href="themes/style.css" rel="stylesheet" type="text/css"  ></link>	
	<link href="themes/print.css" rel="stylesheet" type="text/css" media="print" ></link>	
	<!-- Ejecucion de los scripts generales del hulk -->
	<script type="text/javascript" src="libraries/javascript/script.hulk.js"></script>
</head>
<body>


<!-- FIXED COLUMN -->
<div class="table-header-fixed column-c dp-none" >
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="portlet-content">
			<table id="table_fixed" class="ui-widget" align="center" style="width:100%;">
			</table>
		</div>
	</div>
</div>


<div class="column-c">

	<!-- ALERTAS -->
	<div class="ui-widget">
		
		<div class="flex-container">
			<div class="flex-item alert-container">
				<h4>Alertas Asistencias</h4>
	
				<div class="bg-warning alert-item" >
					<a href="#">Nombre Alumno</a> tiene 2 faltas seguidas
				</div>
				<div class="bg-danger alert-item" >
					<a href="#">Nombre Alumno</a>, <a href="#">Nombre Alumno</a>, <a href="#">Nombre Alumno</a> tienen 3 faltas
				</div>
	
			</div>
		
			<div class="flex-item alert-container">
				<h4>Alertas Cuotas</h4>

				<div class="bg-warning alert-item" >
					<a href="#">Nombre Alumno</a> tiene 1 cuota adeudadas
				</div>
				
	
			</div>
		</div>

	</div>

	<!-- TABLA -->
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<div class="portlet-header ui-widget-header ui-corner-all">Comisiones</div>
		<div class="portlet-content">
			<table id="listado" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height:20px;font-size:7pt">
						<th colspan="6"></th>						
						<th colspan="6">Cuotas</th>
						<th colspan="20">Asistencia</th>
						<th colspan="8">Exámenes</th>
				</tr>
				</thead>

				<thead>
					<tr class="ui-widget-header" style="height:20px;font-size:7pt">
						<th>N°</th>
						<th>DNI</th>
						<th>Alumno</th>
						<th>Cat.</th>
						<th>Estatus</th>
						<th>Enrolado</th>

						<!-- Cuotas-->
						<th>1</th>
						<th>2</th>
						<th>3</th>
						<th>4</th>
						<th>5</th>
						<th>KIT</th>

						<!-- Asistencia-->
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Feb</th>
						<th class="textCenter sz-6">Lun<br />00<br />Mar</th>
						<th class="textCenter sz-6">Lun<br />00<br />Mar</th>
						<th class="textCenter sz-6">Lun<br />00<br />Mar</th>
						<th class="textCenter sz-6">Lun<br />00<br />Mar</th>
						<th class="textCenter sz-6">Mar<br />00<br />Mar</th>
						<th class="textCenter sz-6">Mar<br />00<br />Mar</th>
						<th class="textCenter sz-6">Mar<br />00<br />Feb</th>
						<th class="textCenter sz-6">Mar<br />00<br />Abr</th>
						<th class="textCenter sz-6">Mar<br />00<br />Abr</th>
						<th class="textCenter sz-6">Mar<br />00<br />Abr</th>
						<th class="textCenter sz-6">Mar<br />00<br />Abr</th>
						<th class="textCenter sz-6">Mar<br />00<br />Abr</th>
						<th class="textCenter sz-6">Mar<br />00<br />Abr</th>
						<!-- Exámenes -->
						<th>P1</th>
						<th>P2</th>
						<th>P3</th>
						<th>P4</th>
						<th>P5</th>
						<th>P6</th>
						<th>T</th>
						<th>G</th>
				</tr>
				</thead>

				<!-- ROWS -->
				<tbody>
				<?php for ($i=1; $i<=50; $i++): ?>
					<tr>
						<td><?= $i ?></td>
						<td>26943429</td>
						<td><a href="contactos.php?v=view&id=100001">Nombre Alumno</a></td>
						<td class="textCenter">C</td>
						<td align="center">I</td>
						<td>00-Feb-00</td>
						<!-- Cuotas-->
						<td class="bg-success textCenter">ok</td>
						<td class="bg-success textCenter">ok</td>
						<td class="bg-success textCenter">ok</td>
						<td class="bg-success textCenter">ok</td>
						<td class="bg-danger textCenter">no</td>
						<td class="textCenter">-</td>
						<!-- Asistencia-->
						<td class="bg-success textCenter">P</td>
						<td class="bg-success textCenter">P</td>
						<td class="bg-success textCenter">P</td>
						<td class="bg-success textCenter">P</td>
						<td class="bg-success textCenter">P</td>
						<td class="bg-danger textCenter" >A</td>
						<td class="bg-danger textCenter" >A</td>
						<td class="bg-danger textCenter" >A</td>
						<td class="bg-danger textCenter" >A</td>
						<td class="bg-danger textCenter" >A</td>
						<td class="bg-danger textCenter" >A</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<td class="textCenter">-</td>
						<!-- Exámenes -->
						<td class="bg-success textCenter">75%</td>
						<td class="bg-danger textCenter">35%</td>
						<td class="bg-danger textCenter">55%</td>
						<td class="bg-success textCenter">70%</td>
						<td class="bg-success textCenter">75%</td>
						<td class="bg-success textCenter">75%</td>
						<td class="bg-success textCenter">75%</td>
						<td class="bg-success textCenter">80%</td>
					</tr>
				<?php endfor; ?>
				</tbody>
				<tfoot>
					<tr>
						<th colspan="32" align="left" style="padding:16px">
							Total Alumnos: <?= $i-1 ?>
						</th>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>

<hr>


<div class="ui-widget">
	<h3>Referencias:</h3>
	<div class="flex-container">
		<div class="flex-item">
			<h4>Categoría:</h4>
			<ul class="list-ref">
				<li>C: Corporativo</li>
				<li>I: Individual</li>
			</ul>
		</div>
		<div class="flex-item">
			<h4>Estatus:</h4>
			<ul class="list-ref">
				<li>P: Pass (Pasó)</li>
				<li>E: Enroll (Enrolado)</li>
				<li>F: Failed (Falló)</li>
				<li>I: Incomplete (Incompleto)</li>
			</ul>
		</div>
		<div class="flex-item">
			<h4>Asistencias:</h4>
			<ul class="list-ref">
				<li>P: Presente</li>
				<li>A: Ausente</li>
				<li>E: Excusado</li>
				<li>T: Tarde</li>
			</ul>
		</div>
	</div>
</div>



<?php 
//// GET USERS ///
/*SELECT u.id, u.username, CONCAT(u.lastname, ', ', u.firstname) AS fullname, ra.timemodified, u.email, u.obs
FROM moodle.mdl_role_assignments ra 
INNER JOIN moodle.mdl_user u ON ra.userid = u.id
INNER JOIN moodle.mdl_context ctx ON ra.contextid = ctx.id
WHERE ra.roleid = 5 AND ctx.instanceid = 100095
ORDER BY u.lastname, u.firstname*/


/// GET CUOTAS ///
/*SELECT DISTINCT c.*
FROM crm.h_cuotas c
WHERE c.userid=100003
ORDER BY c.courseid, c.periodo, c.cuota
*/

/*
//// GET SESSIONS ///
SELECT atts.* FROM mdl_attendance_sessions atts INNER JOIN mdl_attendance a ON a.id=atts.attendanceid WHERE a.course=100095 ORDER BY atts.sessdate
--> id=94 

/// GET ATTENDANCE ///
SELECT atts.acronym as status
FROM moodle.mdl_attendance_log al
INNER JOIN moodle.mdl_attendance_sessions ass ON al.sessionid=ass.id
INNER JOIN moodle.mdl_attendance_statuses atts ON atts.id=al.statusid
WHERE ass.id=94 AND al.studentid=100003
*/

///echo date('d/M/Y',1473714000);


//// GET EXAMNS ///
/*SELECT id, itemname FROM mdl_grade_items WHERE itemname IS NOT NULL AND courseid=100095 AND itemname != 'Asistencia' AND itemname != 'Graduación' AND itemname NOT LIKE '%e-Kit%' ORDER BY sortorder
-->id=654

SELECT MAX(finalgrade)
FROM mdl_grade_grades
WHERE itemid=654 AND userid=100003;
*/
?>

<script>
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
$(function(){	
	$(window).scroll(function(){
		if($(this).scrollTop() > 120){
			$('.table-header-fixed').removeClass('dp-none');			
		}else{
			$('.table-header-fixed').addClass('dp-none');
		}
	});
	$(window).resize(function(){
		ResizeHeader();
	});
	BuildHeader();
});
</script>
</body>
</html>