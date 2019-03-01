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
			<td colspan="2"><b>Instructor:</b> <?php foreach($instructor as $ins){ echo $ins['fullname']." | ";}?></td>
		</tr>
		<tr>
			<td><b>Cant. de alumnos inscriptos:</b> <?= count($estudiantes); ?></td>
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
				<th></th>
				<th>Username</th>
				<th>Nombre</th>
				<th>E-Mail</th>
				<th>Celular</th>
				<th>Estado</th>
				<th>Deuda</th>
			</tr>
		</thead>
		<tbody>
			<?php foreach($estudiantes as $estudiante):?>
					<?php $x++;?>
					<?php $estudiante['estado'] = $LMS->user_status($estudiante['id'],$courseid);?>
					<?php $gradebook	= array ('E'=>'ui-widget-content','I'=>'ui-state-error','F'=>'ui-state-error','P'=>'ui-state-highlight');?>
				<tr class="ui-widget-content">
					<td class="ui-widget-content" align="center"><?= $x;?></td>						
					<td class="ui-widget-content" align="center"><?= $estudiante['username'];?></td>
					<td class="ui-widget-content"><b><?= $estudiante['fullname'];?></b></td>							
					<td class="ui-widget-content" align="center"><?= $estudiante['email'];?></td>							
					<td class="ui-widget-content" align="center"><?= $estudiante['phone'];?></td>							
					<td class="<?= $gradebook[$estudiante['estado']];?>" align="center"><?= $estudiante['estado'];?></td>
					<td align="right"><?php if($estudiante['deuda'] > 0){ echo "$ ".number_format($estudiante['deuda'], 2, ',','.'); } ?></td>
				</tr>
			<?php endforeach;?>
		</tbody>
		<tfoot>
		</tfoot>		
	</table>
	
</body>
</html>