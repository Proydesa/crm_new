<?php include("views/header.php"); ?>
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<title>Inscriptos</title> 
<style type="text/css"> 
body, td{font-family:arial,sans-serif;font-size:11px;} 
a:link, a:active, a:visited{color:#0000CC} 
img{border:0} 
pre { white-space: pre; white-space: -moz-pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; overflow: auto;}
</style> 
<script> 
	function Print(){document.body.offsetHeight;window.print()}
</script> 
</head> 
<body onload="Print()" style="width:19.7cm;"> 
	<h2 style="text-decoration: underline;">Alumnos Inscriptos por Comisi&oacute;n</h2>
	<table width="100%" class="ui-widget">
		<tr>
			<td colspan="2"><b>Comisi&oacute;n:</b> <?= $LMS->GetField("mdl_course", "fullname", $courseid); ?></td>
		</tr>
		<tr>
			<td colspan="2"><b>Instructor:</b> <?= $instructor['fullname']; ?></td>
		</tr>
		<tr>
			<td><b>Cant. de alumnos:</b> <?= count($estudiantes); ?></td>
			<td><b>Capacidad:</b> <?= $capacidad['capacity']; ?></td>
		</tr>
		<tr>
			<td><b>Fecha Inicio:</b> <?= date("d-m-Y", $comi['startdate']); ?></td>
			<td><b>Fecha Fin:</b> <?= date("d-m-Y", $comi['enddate']); ?></td>
		</tr>
		<tr>
			<td colspan="2"><b>Horario:</b> <?= $horario['name']; ?></td>
		</tr>
	</table>

	<table width="100%" border="0" class="ui-widget" style="background-color:#E6E6E6;">
		<thead>
			<tr class="ui-widget-header" style="font-size:12px;">
				<th>DNI</th>
				<th>Apellido</th>
				<th>Nombre</th>
				<th>Correo Electrónico</th>
				<th>Teléfono</th>
				<th>Celular</th>
				<th>Deuda</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($estudiantes as $est): ?>
				<tr class="ui-widget-content">
					<td><?= $est['username']; ?></td>
					<td><?= $est['lastname']; ?></td>
					<td><?= $est['firstname']; ?></td>
					<td><?= $est['email']; ?></td>
					<td><?= $est['phone1']; ?></td>
					<td><?= $est['phone2']; ?></td>
					<td align="right"><?php if($est['deuda'] > 0) echo "$ ".number_format($est['deuda'], 2, ',','.'); ?></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
		<tfoot>
		</tfoot>		
	</table>
	
</body>
</html>
