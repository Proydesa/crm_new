<?php $border=0; ?>
<?php 
	foreach($cuotas as $cuota):
		if($cuota['libroid']==0){
			if (strrpos($cuotas_str,"Cuota N° {$cuota['cuota']}")===false)
				$cuotas_str .= "Cuota N° {$cuota['cuota']}";
			//Busco si hay otro comprobante para esa cuota
			if($cuota['importe']<$cuota['valor_cuota']){	
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
			$dfct = $dfct + ($cuota['importe'] * $HULK->porcentaje_dfct);
			$periodo_str = $HULK->periodos[substr($cuota['periodo'],-1)]." 20".substr($cuota['periodo'],0,2);
			$curso_str = $LMS->GetField('mdl_course', 'shortname', $cuota['courseid']);
			$importe = $importe + $cuota['importe'];				
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

	<div style="height:0.3cm;"></div>

	<table width="100%" border="<?= $border;?>" style="height:3.5cm">
		<tr>
			<td style="width:62%;">&nbsp;</td>
			<td style="font-size:13px;padding-top:2.4cm;vertical-align:top;"><?= date("d / m / y",$comprobante['date']);?></td>
		</tr> 
	</table>

	<div style="height:1.1cm;"></div>

	<table width="100%" style="vertical-align:top;" border="<?= $border;?>">
		<tr height="32px">
			<td style="width:1.4cm;vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;width:11cm;"><i><?= $comprobante['señores']; ?></i></td>
			<td colspan="4" style="vertical-align:top;padding-left:0.2cm;"><i><?= $comprobante['domicilio']; ?></i></td>
		</tr>	
		<tr height="20px">
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;"><i><?= $comprobante['tel']; ?></i></td>
			<td colspan="2" style="vertical-align:top;width:2.3cm;padding-left:0.2cm;"><i><?= $comprobante['localidad']; ?></i></td>
			<td colspan="2" style="vertical-align:top;padding-left:0.1cm;" align="left"><i><?= $comprobante['cp']; ?></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>	
		<tr height="20px">
			<td>&nbsp;</td>
			<td style="width:10cm;padding-top:3px;">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva0']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva1']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva2']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva3']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva4']; ?>
			</td>
			<td style="width:4cm;"></td>
			<td colspan="3"><?= $comprobante['cuit']; ?></td>
		</tr>	
	</table>
	
	<div style="height:0.9cm;"></div>
	
	<table width="100%" border="<?= $border;?>">
		<tbody style="vertical-align:top;">
			<tr style="height:20px; vertical-align:top;">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php $alto = 110; ?>
			<?php if($cuotas_str): ?>
				<?php $alto -= 50; ?>
				<?php 
					if($becado > 0){
						if($becado < 100){
							$importe_beca = ($importe * $becado / 100);
						}else{
							$importe_beca = 0;
							$importe_beca2 = $importe;
						}					
					}
				?>				
				<tr style="height:35px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - <?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?><?= $comprobante['leyenda']?>, <?= $periodo_str;?></td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ <?= number_format($importe+$dfct+$importe_beca,2,',','.');?></td>
				</tr>
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ -<?= number_format($dfct,2,',','.');?></td>
				</tr>
			<?php endif; ?>
			<?php if($becado>0):?>
				<?php $alto -= 15; ?>
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - Donaci&oacute;n Beca <?= $becado; ?> %</td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ -<?= number_format($importe_beca + $importe_beca2,2,',','.');?></td>
				</tr>				
			<?php endif; ?>			
			<?php if($libroid): ?>
				<?php $alto -= 15; ?>			
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - <?= $H_DB->GetField('h_libros', 'name', $libroid); ?></td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ <?= number_format($libro_valor,2,',','.');?></td>
				</tr>					
			<?php endif; ?>			
			<tr height="<?= $alto; ?>px" style="vertical-align:bottom;">
				<?php if($comision > 0): ?>
					<td width="75%" style="padding-left:0.4cm;">
						<b>Comisión:</b> <?= $LMS->GetField("mdl_course", "fullname", $comision); ?> <b>Aula:</b> <?= $H_DB->GetField("h_academy_aulas", "name", $H_DB->GetField("h_course_config", "aulaid", $comision, "courseid")); ?><br />
						<b>Fecha Inicio:</b> <?= date("d-m-Y", $LMS->GetField("mdl_course", "startdate", $comision)); ?> <b>Horario:</b> <?= $H_DB->GetField("h_horarios", "name", $H_DB->GetField("h_course_config", "horarioid", $comision, "courseid")); ?><br />
						<b>Usuario:</b> <?= $comprobante['username']; ?><?php if($newpass) echo " - <b>Contraseña:</b> ".$newpass; ?>
					</td>
					<td>&nbsp;</td>
				<?php else: ?>
					<td colspan="2">&nbsp;</td>
				<?php endif; ?>
			</tr>
			<tr style="height:15px;">
				<td align="left" style="padding-left: 0cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:30px;">
				<td align="left" style="padding-top:0.3cm;padding-left: 0cm"><?php if($comprobante['concepto']==2) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:20px;">
				<td align="left" style="padding-left: 1.8cm;padding-top:2px;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></td>
				<th align="right" style="padding-right:0.8cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></th>
			</tr>
		</tbody>
	</table>

	<!-- Duplicado -->
	<div style="height:1.1cm;"></div>

	<table width="100%" border="<?= $border;?>" style="height:3.5cm">
		<tr>
			<td style="width:62%;">&nbsp;</td>
			<td style="font-size:13px;padding-top:1.8cm;vertical-align:top;"><?= date("d / m / y",$comprobante['date']);?></td>
		</tr> 
	</table>

	<div style="height:0.4cm;"></div>

	<table width="100%" style="vertical-align:top;" border="<?= $border;?>">
		<tr height="32px">
			<td style="width:1.4cm;vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;width:11cm;"><i><?= $comprobante['señores']; ?></i></td>
			<td colspan="4" style="vertical-align:top;padding-right:0.2cm;"><i><?= $comprobante['domicilio']; ?></i></td>
		</tr>	
		<tr height="20px">
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;"><i><?= $comprobante['tel']; ?></i></td>
			<td colspan="2" style="vertical-align:top;width:2.3cm;padding-right:0.2cm;"><i><?= $comprobante['localidad']; ?></i></td>
			<td colspan="2" style="vertical-align:top;padding-right:0.1cm;" align="left"><i><?= $comprobante['cp']; ?></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>	
		<tr height="20px">
			<td>&nbsp;</td>
			<td style="width:10cm;padding-top:3px;">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva0']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva1']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva2']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva3']; ?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $comprobante['iva4']; ?>
			</td>
			<td style="width:4cm;"></td>
			<td colspan="3" align="left"><?= $comprobante['cuit']; ?></td>
		</tr>	
	</table>
	
	<div style="height:0.7cm;"></div>
	
	<table width="100%" border="<?= $border;?>">
		<tbody style="vertical-align:top;">
			<tr style="height:30px; vertical-align:top;">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php $alto = 110; ?>
			<?php if($cuotas_str): ?>
				<?php $alto -= 50; ?>
				<tr style="height:35px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - <?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?><?= $comprobante['leyenda']?>, <?= $periodo_str;?></td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ <?= number_format($importe+$dfct+$importe_beca,2,',','.');?></td>
				</tr>
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ -<?= number_format($dfct,2,',','.');?></td>
				</tr>
			<?php endif; ?>
			<?php if($becado>0):?>
				<?php $alto -= 15; ?>
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - Donaci&oacute;n Beca <?= $becado; ?> %</td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ -<?= number_format($importe_beca + $importe_beca2,2,',','.');?></td>
				</tr>				
			<?php endif; ?>				
			<?php if($libroid): ?>
				<?php $alto -= 15; ?>			
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"> - <?= $H_DB->GetField('h_libros', 'name', $libroid); ?></td>
					<td width="25%" align="right" style="padding-right:0.8cm;">$ <?= number_format($libro_valor,2,',','.');?></td>
				</tr>					
			<?php endif; ?>			
			<?php if($comprobante['leyenda']): ?>
				<?php $alto -= 15; ?>			
				<tr style="height:15px; vertical-align:top;">
					<td width="75%" style="padding-left:0.2cm;"><?= $comprobante['leyenda'];?></td>
					<td width="25%" align="right" style="padding-right:0.6cm;"></td>
				</tr>					
			<?php endif; ?>			
			<tr height="<?= $alto; ?>px" style="vertical-align:bottom;">
				<td colspan="2">&nbsp;</td>
			</tr>
			<tr style="height:15px;">
				<td align="left" style="padding-left: 0cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:30px;">
				<td align="left" style="padding-top:0.3cm;padding-left: 0cm"><?php if($comprobante['concepto']==2) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:20px;">
				<td align="left" style="padding-left: 1.8cm;padding-top:2px;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></td>
				<th align="right" style="padding-right:0.8cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></th>
			</tr>
		</tbody>
	</table>
	
</body>
</html>
