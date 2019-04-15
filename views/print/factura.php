<?php
//$disenio=1; 
//$comprobante['concepto']==1;
	foreach($cuotas as $cuota):
		if($cuota['libroid']==0){

			$beca = $H_DB->GetField("h_inscripcion", "becado", $cuota['insc_id']);

			if (strrpos($cuotas_str,"Cuota N° {$cuota['cuota']}")===false)
				$cuotas_str .= "Cuota N° {$cuota['cuota']}";

			$cuota_beca = ceil($cuota['valor_cuota'] - ($cuota['valor_cuota']*$beca/100));

			//Busco si hay otro comprobante para esa cuota
			if($cuota['importe']<$cuota_beca){
				$comps = $H_DB->GetAll("SELECT * 
										FROM h_comprobantes_cuotas
										WHERE cuotaid={$cuota['cuotaid']} AND comprobanteid<={$cuota['comprobanteid']};");
				if($comps){
					foreach($comps as $comp):
						$sum += $comp['importe'];
					endforeach;
					if($sum<$cuota['valor_cuota']){
						$cuotas_str .= " (a cuenta)";
					}else{
						$cuotas_str .= " (saldo)";
					}
					unset($sum);
				}else{
					$cuotas_str .= " (a cuenta)";
				}
			}
			if (strrpos($cuotas_str,"Cuota N° {$cuota['cuota']},")===false)
				$cuotas_str .= ", ";
			$dfct = $dfct + (($cuota['importe'] + ($cuota['valor_cuota']*$beca/100)) * $HULK->porcentaje_dfct);
			$periodo_str = $HULK->periodos[substr($cuota['periodo'],-1)]." 20".substr($cuota['periodo'],0,2);
			$curso_str = $LMS->GetField('mdl_course', 'shortname', $cuota['courseid']);
			$importe = $importe + $cuota['importe'] + ($cuota['valor_cuota']*$beca/100);
		}else{
			$libroid = $cuota['libroid'];
			$libro_valor += $H_DB->GetField("h_libros", "valor", $cuota['libroid']);
		}
	endforeach;
?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<title>Factura</title>
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

	<div style="position:absolute; top:2.3cm; left:13cm;"><?= date("d/m/y",$comprobante['date']);?></div>

	<div style="position:absolute; top:4.6cm; left:2cm;"><i><?= $nombre;?></i></div>
	<div style="position:absolute; top:4.6cm; left:13.5cm;"><i><?=$comprobante['domicilio'];?></i></div>
	<div style="position:absolute; top:5.6cm; left:1cm;"><i><?= $comprobante['tel']; ?></i></div>
	<div style="position:absolute; top:5.6cm; left:13.5cm;"><i><?= $comprobante['localidad']; ?></i></div>
	<div style="position:absolute; top:5.6cm; left:18cm;"><i><?=$comprobante['cp'];?></i></div>

	<div style="position:absolute; top:6.3cm; left:2.65cm;"><?= $comprobante['iva0'];?></div>
	<div style="position:absolute; top:6.3cm; left:5.5cm;"><?= $comprobante['iva1'];?></div>
	<div style="position:absolute; top:6.3cm; left:7.38cm;"><?= $comprobante['iva2'];?></div>
	<div style="position:absolute; top:6.3cm; left:9.07cm;"><?= $comprobante['iva3'];?></div>
	<div style="position:absolute; top:6.3cm; left:11.7cm;"><?= $comprobante['iva4'];?></div>
	<div style="position:absolute; top:6.3cm; left:15.5cm;"><?= $comprobante['cuit'];?></div>

	<?php if($cuotas_str){ ?>
		<?php 
			if($becado > 0){
				if($becado < 100){
					$importe_beca = $importe - $comprobante['importe'];
				}else{
					$importe_beca = 0;
					$importe_beca2 = $importe;
				}
			}
		?>
		<div style="position:absolute; top:8.5cm;left:1.5cm;"> - <?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?><?= $comprobante['leyenda']?>, <?= $periodo_str;?></div>
		<div style="position:absolute; top:8.5cm;left:17.5cm;"> $ <?= number_format($importe+$dfct,2,',','.');?></div>

		<div style="position:absolute; top:9.1cm;left:1.5cm;"> - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</div>
		<div style="position:absolute; top:9.1cm;left:17.5cm;"> $ -<?= number_format($dfct,2,',','.');?></div>
	<?php } ?>
	<?php if($becado>0){?>
		<div style="position:absolute; top:9.7cm;left:1.5cm;">  - Donaci&oacute;n Beca <?= $becado; ?> %</div>
		<div style="position:absolute; top:9.7cm;left:17.5cm;"> $ -<?= number_format($importe_beca + $importe_beca2,2,',','.');?></div>
	<?php } ?>
	<?php if($libroid){ ?>
		<div style="position:absolute; top:10.3cm;left:1.5cm;"> - <?= $H_DB->GetField('h_libros', 'name', $libroid); ?></div>
		<div style="position:absolute; top:10.3cm;left:17.5cm;"> $ <?= number_format($libro_valor,2,',','.');?></div>
	<?php } ?>	
	<?php if($comision>0){ ?>
			<div style="position:absolute; top:10.9cm;left:4.5cm;"><b>Comisión:</b> <?= $LMS->GetField("mdl_course", "fullname", $comision); ?> <b>Aula:</b> <?= $H_DB->GetField("h_academy_aulas", "name", $H_DB->GetField("h_course_config", "aulaid", $comision, "courseid")); ?></div>
			<div style="position:absolute; top:11.3cm;left:4.5cm;"><b>Fecha Inicio:</b> <?= date("d-m-Y", $LMS->GetField("mdl_course", "startdate", $comision)); ?> <b>Horario:</b> <?= $H_DB->GetField("h_horarios", "name", $H_DB->GetField("h_course_config", "horarioid", $comision, "courseid")); ?></div>
	<?php } ?>	

	<div style="position:absolute; top:11.5cm;left:0.1cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></div>
	<div style="position:absolute; top:12.3cm;left:0.1cm;"><?php if($comprobante['concepto']==2) echo "X"; ?></div>

	<div style="position:absolute; top:12.7cm;left:0.1cm;"><?php if($comprobante['concepto']>2) echo "X"; ?></div>
	<div style="position:absolute; top:12.7cm;left:1cm;"><?php if($comprobante['concepto']>2) echo $HULK->conceptos[$comprobante['concepto']]; ?></div>

	<div style="position:absolute; top:12.9cm;left:17.8cm; font-size:14px;"><b>$ <?= number_format($comprobante['importe'],2,',','.');?></b></div>
	<div style="position:absolute; top:13.1cm;left:2cm;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></div>

<!-- Duplicado -->

	<div style="position:absolute; top:16.7cm; left:13cm;"><?= date("d/m/y",$comprobante['date']);?></div>

	<div style="position:absolute; top:19cm; left:2cm;"><i><?= $nombre;?></i></div>
	<div style="position:absolute; top:19cm; left:13.5cm;"><i><?=$comprobante['domicilio'];?></i></div>
	<div style="position:absolute; top:20cm; left:1cm;"><i><?= $comprobante['tel']; ?></i></div>
	<div style="position:absolute; top:20cm; left:13.5cm;"><i><?= $comprobante['localidad']; ?></i></div>
	<div style="position:absolute; top:20cm; left:18cm;"><i><?=$comprobante['cp'];?></i></div>

	<div style="position:absolute; top:20.7cm; left:2.65cm;"><?= $comprobante['iva0'];?></div>
	<div style="position:absolute; top:20.7cm; left:5.5cm;"><?= $comprobante['iva1'];?></div>
	<div style="position:absolute; top:20.7cm; left:7.38cm;"><?= $comprobante['iva2'];?></div>
	<div style="position:absolute; top:20.7cm; left:9.07cm;"><?= $comprobante['iva3'];?></div>
	<div style="position:absolute; top:20.7cm; left:11.7cm;"><?= $comprobante['iva4'];?></div>
	<div style="position:absolute; top:20.7cm; left:15.5cm;"><?= $comprobante['cuit'];?></div>

	<?php if($cuotas_str){ ?>
		<?php 
			if($becado > 0){
				if($becado < 100){
					$importe_beca = $importe - $comprobante['importe'];
				}else{
					$importe_beca = 0;
					$importe_beca2 = $importe;
				}
			}
		?>
		<div style="position:absolute; top:22.9cm;left:1.5cm;"> - <?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?><?= $comprobante['leyenda']?>, <?= $periodo_str;?></div>
		<div style="position:absolute; top:22.9cm;left:17.5cm;"> $ <?= number_format($importe+$dfct,2,',','.');?></div>

		<div style="position:absolute; top:23.5cm;left:1.5cm;"> - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</div>
		<div style="position:absolute; top:23.5cm;left:17.5cm;"> $ -<?= number_format($dfct,2,',','.');?></div>
	<?php } ?>
	<?php if($becado>0){?>
		<div style="position:absolute; top:24.1cm;left:1.5cm;">  - Donaci&oacute;n Beca <?= $becado; ?> %</div>
		<div style="position:absolute; top:24.1cm;left:17.5cm;"> $ -<?= number_format($importe_beca + $importe_beca2,2,',','.');?></div>
	<?php } ?>
	<?php if($libroid){ ?>
		<div style="position:absolute; top:24.7cm;left:1.5cm;"> - <?= $H_DB->GetField('h_libros', 'name', $libroid); ?></div>
		<div style="position:absolute; top:24.7cm;left:17.5cm;"> $ <?= number_format($libro_valor,2,',','.');?></div>
	<?php } ?>	
	<?php if($comision>0){ ?>
			<div style="position:absolute; top:25.3cm;left:4.5cm;"><b>Comisión:</b> <?= $LMS->GetField("mdl_course", "fullname", $comision); ?> <b>Aula:</b> <?= $H_DB->GetField("h_academy_aulas", "name", $H_DB->GetField("h_course_config", "aulaid", $comision, "courseid")); ?></div>
			<div style="position:absolute; top:25.7cm;left:4.5cm;"><b>Fecha Inicio:</b> <?= date("d-m-Y", $LMS->GetField("mdl_course", "startdate", $comision)); ?> <b>Horario:</b> <?= $H_DB->GetField("h_horarios", "name", $H_DB->GetField("h_course_config", "horarioid", $comision, "courseid")); ?></div>
	<?php } ?>	

	<div style="position:absolute; top:25.9cm;left:0.1cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></div>
	<div style="position:absolute; top:26.6cm;left:0.1cm;"><?php if($comprobante['concepto']==2) echo "X"; ?></div>

	<div style="position:absolute; top:27.1cm;left:0.1cm;"><?php if($comprobante['concepto']>2) echo "X"; ?></div>
	<div style="position:absolute; top:27.1cm;left:1cm;"><?php if($comprobante['concepto']>2) echo $HULK->conceptos[$comprobante['concepto']]; ?></div>

	<div style="position:absolute; top:27.3cm;left:17.8cm; font-size:14px;"><b>$ <?= number_format($comprobante['importe'],2,',','.');?></b></div>
	<div style="position:absolute; top:27.5cm;left:2cm;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></div>

</body>
</html>
