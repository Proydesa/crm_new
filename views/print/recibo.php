<?php $border=0; ?>
<?php 
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
<title>Recibo</title> 
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
<body onload="Print()"> 

<div style="position:absolute; top:2.3cm; left:14cm;"><?= date("d/m/y",$comprobante['date']);?></div>

<div style="position:absolute; top:4.9cm; left:3cm;"><i><?= $comprobante['señores'];?></i></div>
<div style="position:absolute; top:4.9cm; left:13cm;"><i><?=$comprobante['domicilio'];?></i></div>
<div style="position:absolute; top:5.5cm; left:2.5cm;"><i><?= $comprobante['tel']; ?></i></div>
<div style="position:absolute; top:5.5cm; left:13.5cm;"><i><?= $comprobante['localidad']; ?></i></div>
<div style="position:absolute; top:5.5cm; left:19cm;"><i><?=$comprobante['cp'];?></i></div>

<div style="position:absolute; top:6.5cm; left:3.5cm;"><?=$comprobante['iva0'];?></div>
<div style="position:absolute; top:6.5cm; left:6.2cm;"><?=$comprobante['iva1'];?></div>
<div style="position:absolute; top:6.5cm; left:8.1cm;"><?=$comprobante['iva2'];?></div>
<div style="position:absolute; top:6.5cm; left:10cm;"><?=$comprobante['iva3'];?></div>
<div style="position:absolute; top:6.5cm; left:12.5cm;"><?=$comprobante['iva4'];?></div>

<div style="position:absolute; top:6.5cm; left:15.5cm;"><?= $comprobante['cuit'];?></div>

	<?php if($cuotas_str): ?>
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
		<?php endif; ?>
		<?php if($becado>0):?>
			<div style="position:absolute; top:9.7cm;left:1.5cm;">  - Donaci&oacute;n Beca <?= $becado; ?> %</div>
			<div style="position:absolute; top:9.7cm;left:17.5cm;"> $ -<?= number_format($importe_beca + $importe_beca2,2,',','.');?></div>
		<?php endif; ?>
		<?php if($libroid): ?>
			<div style="position:absolute; top:10.3cm;left:1.5cm;"> - <?= $H_DB->GetField('h_libros', 'name', $libroid); ?></div>
			<div style="position:absolute; top:10.3cm;left:17.5cm;"> $ <?= number_format($libro_valor,2,',','.');?></div>
		<?php endif; ?>	
		<?php if($comision > 0): ?>
				<div style="position:absolute; top:10.9cm;left:4.5cm;"><b>Comisión:</b> <?= $LMS->GetField("mdl_course", "fullname", $comision); ?> <b>Aula:</b> <?= $H_DB->GetField("h_academy_aulas", "name", $H_DB->GetField("h_course_config", "aulaid", $comision, "courseid")); ?></div>
				<div style="position:absolute; top:11.3cm;left:4.5cm;"><b>Fecha Inicio:</b> <?= date("d-m-Y", $LMS->GetField("mdl_course", "startdate", $comision)); ?> <b>Horario:</b> <?= $H_DB->GetField("h_horarios", "name", $H_DB->GetField("h_course_config", "horarioid", $comision, "courseid")); ?></div>
				<div style="position:absolute; top:11.7cm;left:4.5cm;"><b>Usuario:</b> <?= $comprobante['username']; ?><?php if($newpass) echo " - <b>Contraseña:</b> ".$newpass; ?></div>
		<?php endif; ?>
		<div style="position:absolute; top:12.1cm;left:1.5cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></div>
		<div style="position:absolute; top:12.9cm;left:1.5cm;"><?php if($comprobante['concepto']==2) echo "X"; ?></div>

		<div style="position:absolute; top:13.7cm;left:2.5cm;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></div>
		<div style="position:absolute; top:13.7cm;left:17.5cm; font-size:13px;"><b>$ <?= number_format($comprobante['importe'],2,',','.');?></b></div>

	<div style="position:absolute; top:17.5cm; left:14cm;"><?= date("d/m/y",$comprobante['date']);?></div>

	<div style="position:absolute; top:20cm; left:3cm;"><i><?= $comprobante['señores'];?></i></div>
	<div style="position:absolute; top:20cm; left:13cm;"><i><?=$comprobante['domicilio'];?></i></div>
	<div style="position:absolute; top:20.6cm; left:2.5cm;"><i><?= $comprobante['tel']; ?></i></div>
	<div style="position:absolute; top:20.6cm; left:13.5cm;"><i><?= $comprobante['localidad']; ?></i></div>
	<div style="position:absolute; top:20.6cm; left:19cm;"><i><?=$comprobante['cp'];?></i></div>

<div style="position:absolute; top:21.7cm; left:3.5cm;"><?=$comprobante['iva0'];?></div>
<div style="position:absolute; top:21.7cm; left:6.2cm;"><?=$comprobante['iva1'];?></div>
<div style="position:absolute; top:21.7cm; left:8.1cm;"><?=$comprobante['iva2'];?></div>
<div style="position:absolute; top:21.7cm; left:10cm;"><?=$comprobante['iva3'];?></div>
<div style="position:absolute; top:21.7cm; left:12.5cm;"><?=$comprobante['iva4'];?></div>

<div style="position:absolute; top:21.7cm; left:15.5cm;"><?= $comprobante['cuit'];?></div>
	
<?php if($cuotas_str): ?>
	<div style="position:absolute; top:23.7cm; left:1.5cm;"> - <?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?><?= $comprobante['leyenda']?>, <?= $periodo_str;?></div>
	<div style="position:absolute; top:23.7cm; left:17.5cm;"> $ <?= number_format($importe+$dfct,2,',','.');?></div>
	<div style="position:absolute; top:24.3cm; left:1.5cm;"> - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</div>
	<div style="position:absolute; top:24.3cm; left:17.5cm;"> $ -<?= number_format($dfct,2,',','.');?></div>
<?php endif; ?>
<?php if($becado>0):?>
	<div style="position:absolute; top:24.9cm; left:1.5cm;"> - Donaci&oacute;n Beca <?= $becado; ?> %</div>
	<div style="position:absolute; top:24.9cm; left:17.5cm;"> $ -<?= number_format($importe_beca + $importe_beca2,2,',','.');?></div>
<?php endif; ?>
<?php if($libroid): ?>
	<div style="position:absolute; top:25.5cm; left:1.5cm;"> - <?= $H_DB->GetField('h_libros', 'name', $libroid); ?></div>
	<div style="position:absolute; top:25.5cm; left:17.5cm;"> $ <?= number_format($libro_valor,2,',','.');?></div>
<?php endif; ?>
<?php if($comprobante['leyenda']): ?>
	<div style="position:absolute; top:26.3cm; left:1.5cm;"> <?= $comprobante['leyenda'];?></div>
<?php endif; ?>				
	<div style="position:absolute; top:27cm;left:1.5cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></div>
	<div style="position:absolute; top:27.7cm;left:1.5cm;"><?php if($comprobante['concepto']==2) echo "X"; ?></div>

	<div style="position:absolute; top:28cm;left:2.5cm;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></div>
	<div style="position:absolute; top:28cm;left:17.5cm; font-size:13px;"><b>$ <?= number_format($comprobante['importe'],2,',','.');?></b></div>
</body>
</html>