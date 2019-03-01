<?php
	$title = "Deudores de Cuota {$cuota}";
	if($comision){
		 $title.= " de la comisi&oacute;n {$LMS->GetField('mdl_course','fullname',$comision)}";
	}
?>
<script type="text/javascript">

    function MostrarActividad(usuario) {
	    //$('#dialog').dialog('option', 'modal', true).empty().dialog('open');
		//$("#dialog").load("pendientes/alumn_cuotas/"+academia+"/"+cuota);
		window.open("hd.php?v=listaplus&id="+usuario, 'detallepopup', 'scrollbars=1,width=710,height=420');
    	return true;
    }
</script>

<!-- Barra de ruta y botones de accion -->
<div class="ui-widget" align="left" style="float:left;">
	<span align="left">
		<h2 class="ui-widget"><?= $title; ?></h2>
		<h3 class="ui-widget">A cargo de: <?= $instructor;?></h3>
	</span>
</div>

<div class="ui-widget noprint" align="right">
	<span align="right">
		<form action="reportes.php?v=xls" method="post" id="xls-form">
			<span class="button-print" onClick="document.body.offsetHeight;window.print();" style="font-size: 9px;">Imprimir</span>
			<!--<span class="button-xls" style="font-size: 9px; ">XLS</span>-->
			<input type="hidden" id="table-xls" name="table-xls" />
			<input type="hidden" id="name-xls" name="name-xls" value="reporte.deudores" />
		</form>
	</span>
</div>

<div class="column" style="width:80%" align="center">
	<table class="ui-widget printable" align="center" style="width:90%;">
		<thead>
			<tr class="ui-widget-header">
				<th>&nbsp;</th>
				<th>DNI</th>
				<th>Nombre</th>
				<th>Apellido</th>
				<th>Correo Electrónico</th>
				<th>Teléfono</th>
				<th>Celular</th>
				<?php if(!$comision): ?>
					<th>Comisi&oacute;n</th>
				<?php endif; ?>
				<th>Deuda</th>
				<th class="noprint"></th>
			</tr>
		</thead>
		<tbody>
			<?php if($deudores): ?>
				<?php $i=1; ?>
				<?php foreach($deudores as $deudor): ?>
					<tr class="ui-widget-content">
						<td align="right"><b><?= $i; ?></b></td>
						<td>
							<a href="contactos.php?v=view&id=<?= $deudor['id'];?>" target="_blank">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							&nbsp;<?= $deudor['username']; ?>
						</td>
						<td align="center"><?= $deudor['firstname']; ?></td>
						<td align="center"><?= $deudor['lastname']; ?></td>
						<td align="center"><?= $deudor['email']; ?></td>
						<td align="center"><?= $deudor['phone1']; ?></td>
						<td align="center"><?= $deudor['phone2']; ?></td>
						<?php if(!$comision): ?>
							<td><?= $LMS->GetField('mdl_course', 'fullname', $deudor['courseid']) ?></td>
						<?php endif; ?>
						<td align="right">$<?= number_format($deudor['deuda'], 2, ',', '.'); ?></td>
						<td align="right" class="noprint"><a href="#" onClick="MostrarActividad('<?= $deudor['id'];?>');">Notificar</td>
					</tr>
					<?php $i++; ?>
				<?php endforeach; ?>
			<?php else: ?>
				<tr class="ui-widget-content">
					<td>No hay resultados</td>
				</tr>
			<?php endif; ?>
		</tbody>
	</table>
</div>
