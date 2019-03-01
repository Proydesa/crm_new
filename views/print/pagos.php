<html> 
<head> 
<meta http-equiv=Content-Type content="text/html; charset=UTF-8"> 
<title>Factura</title> 
<style type="text/css"> 
body, td{font-family:arial,sans-serif;font-size:10px;} 
a:link, a:active, a:visited{color:#0000CC} 
img{border:0} 
pre { white-space: pre; white-space: -moz-pre-wrap; white-space: -o-pre-wrap; white-space: pre-wrap; word-wrap: break-word; overflow: auto;}
</style> 
<script> 
function Print(){document.body.offsetHeight;window.print()}
</script> 
</head> 
<body onload="Print()" > 

	<table width="100%" height="145px" border="1">
		<tr>
			<td style="width:70%;">&nbsp;</td>
			<td style="vertical-align:middle;"><?= date("d/m/y",$comprobante['date']);?>&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>

	<div style="height:7px;"></div>

	<table width="100%" style="vertical-align:top;" height="80px" border="1">
		<tr height="35px">
			<td width="8%" style="vertical-align:top;" >&nbsp;</td>
			<td width="50%" style="vertical-align:top;" colspan="5"><i><?= $contacto['lastname'];?>, <?= $contacto['firstname'];?></i></td>
			<td width="2%" style="vertical-align:top;" >&nbsp;</td>
			<td colspan="3" style="vertical-align:top;" ><?=$contacto['address'];?></td>
		</tr>	
		<tr height="25px">
			<td width="8%" style="vertical-align:top;" >&nbsp;</td>
			<td width="50%" style="vertical-align:top;" colspan="5"><i><?= $contacto['phone1'];?><br /><?= $contacto['phone2'];?></i></td>
			<td width="2%" style="vertical-align:top;" >&nbsp;</td>
			<td width="20%" style="vertical-align:top;" ><i><?=$contacto['city'];?></i></td>
			<td width="1%" style="vertical-align:top;" >&nbsp;</td>
			<td style="vertical-align:top;" align="right"><i>asfs<?=$contacto['cp'];?></i></td>
		</tr>	
		<tr height="20px">
			<td colspan="1">&nbsp;</td>
			<td colspan="1" align="center" style="vertical-align:top;font-size:10px;">X</td>
			<td colspan="1" align="right" style="vertical-align:top;font-size:10px;">X</td>
			<td colspan="1" align="right" style="vertical-align:top;font-size:10px;">X</td>
			<td colspan="1" align="right" style="vertical-align:top;font-size:10px;">X</td>
			<td colspan="2" align="right" style="vertical-align:top;font-size:10px;">X</td>
			<td colspan="1">&nbsp;</td>			
			<td colspan="1">&nbsp;</td>			
			<td colspan="2" align="left"><i></i></td>
		</tr>	
	</table>	
	<div style="height:10px;"></div>
	<table width="100%" border="1">
		<tbody style="height:250px; vertical-align:top;">
			<tr style="height:30px; vertical-align:top;">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php $cuotas_str = "&nbsp;&nbsp; - "; ?>
			<?php foreach($cuotas as $cuota):
				$cuotas_str .= "Cuota N° {$cuota['cuota']}, ";
				$dfct = $dfct + ($cuota['importe'] * $HULK->porcentaje_dfct);
				$periodo_str = $HULK->periodos[substr($cuota['periodo'],-1)]." 20".substr($cuota['periodo'],0,2);
				$curso_str = $LMS->GetField('mdl_course','shortname',$cuota['courseid']);
				$importe = $importe + $cuota['importe'];
			 endforeach; ?>
			<tr style="height:15px; vertical-align:top;">
				<td width="80%"><?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?>, <?= $periodo_str;?></td>
					<td width="20%" align="right">$ <?= number_format($importe+($importe*$HULK->porcentaje_dfct),2,',','.');?></td>
				</tr>
			<tr style="height:15px; vertical-align:top;">
				<td width="80%">&nbsp;&nbsp; - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</td>
				<td width="20%" align="right">$ -<?= number_format($dfct,2,',','.');?></td>
			</tr>
			<tr height="80px">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">X</td>
				<td>&nbsp;</td>
			</tr>
			<tr height="10px">
				<td align="left">X</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 123456</td>
				<th align="right">$ <?= number_format($comprobante['importe'],2,',','.');?></th>
			</tr>
		</tbody>
	</table>

	<div style="height:10px;"></div>
	
	<table width="100%" height="145px" border="1">
		<tr>
			<td style="width:70%;">&nbsp;</td>
			<td style="vertical-align:middle;"><?= date("d/m/y",$comprobante['date']);?>&nbsp;&nbsp;&nbsp;
			</td>
		</tr>
	</table>

	<table width="100%" style="vertical-align:top;" height="80px" border="1">
		<tr height="35px">
			<td width="8%" style="vertical-align:top;" >&nbsp;</td>
			<td width="50%" style="vertical-align:top;" colspan="5"><i><?= $contacto['lastname'];?>, <?= $contacto['firstname'];?></i></td>
			<td width="2%" style="vertical-align:top;" >&nbsp;</td>
			<td colspan="3" style="vertical-align:top;" ><?=$contacto['address'];?></td>
		</tr>	
		<tr height="25px">
			<td width="8%" style="vertical-align:top;" >&nbsp;</td>
			<td width="50%" style="vertical-align:top;" colspan="5"><i><?= $contacto['phone1'];?><br /><?= $contacto['phone2'];?></i></td>
			<td width="2%" style="vertical-align:top;" >&nbsp;</td>
			<td width="20%" style="vertical-align:top;" ><i><?=$contacto['city'];?></i></td>
			<td width="1%" style="vertical-align:top;" >&nbsp;</td>
			<td style="vertical-align:top;" align="right"><i>asfs<?=$contacto['cp'];?></i></td>
		</tr>	
		<tr height="20px">
			<td colspan="1">&nbsp;</td>
			<td colspan="1" align="left" style="font-size:10px;">X</td>
			<td colspan="1" align="left" style="font-size:10px;">X</td>
			<td colspan="1" align="left" style="font-size:10px;">X</td>
			<td colspan="1" align="left" style="font-size:10px;">X</td>
			<td colspan="1" align="left" style="font-size:10px;">X</td>
			<td colspan="1">&nbsp;</td>			
			<td colspan="1">&nbsp;</td>			
			<td colspan="2" align="left"><i></i></td>
		</tr>	
	</table>	
	<div style="height:10px;"></div>
	<table width="100%" border="1">
		<tbody style="height:250px; vertical-align:top;">
			<tr style="height:30px; vertical-align:top;">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:15px; vertical-align:top;">
				<td width="80%"><?= $cuotas_str; ?> de un total de <?= $total_cuotas; ?> del Curso de <?= $curso_str; ?>, <?= $periodo_str;?></td>
					<td width="20%" align="right">$ <?= number_format($importe+($importe*$HULK->porcentaje_dfct),2,',','.');?></td>
				</tr>
			<tr style="height:15px; vertical-align:top;">
				<td width="80%">&nbsp;&nbsp; - Donaci&oacute;n al Favorecimiento de la Capacitaci&oacute;n Tecnol&oacute;gica</td>
				<td width="20%" align="right">$ -<?= number_format($dfct,2,',','.');?></td>
			</tr>
			<tr height="100px">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			<tr>
				<td align="left">X</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">X</td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left">&nbsp;&nbsp; 123456</td>
				<th align="right">$ <?= number_format($comprobante['importe'],2,',','.');?></th>
			</tr>
		</tbody>
	</table>
	
</body>
</html>
