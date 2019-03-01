<?php $border=0; ?>
<?php 
	if($comps){
		foreach($comps as $comp):
			if($comp['tipo']==1){
				$pv = "0001-";
			}elseif($comp['tipo']==2){
				$pv = "0002-";
			}
			$str .= $pv.sprintf("%08d", $comp['numero']).", ";
		endforeach; 
		$str = substr($str, 0, strlen($str)-2);
	}
?>
<html> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"> 
<title>Nota de Credito</title> 
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
<body onload="Print()" style="width:19.3cm;"> 

	<table width="100%" border="<?= $border;?>" style="height:3.9cm">
		<tr>
			<td style="width:65%;">&nbsp;</td>
			<td style="font-size:13px;padding-top:2.1cm;vertical-align:top;"><?= date("d/m/y",$comprobante['date']);?>&nbsp;&nbsp;&nbsp;&nbsp;<?php if ($comprobante['puntodeventa']==1){echo "NOTA DE CREDITO";}?></td>
		</tr>
	</table>

	<div style="height:0.6cm;"></div>

	<table width="100%" style="vertical-align:top;" border="<?= $border;?>">
		<tr height="35px">
			<td style="width:1.6cm; vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;"><i><?= $comprobante['señores'];?></i></td>
			<td style="vertical-align:top;"><i><?=$comprobante['domicilio'];?></i></td>
			<td colspan="3" style="vertical-align:top;">&nbsp;</td>
		</tr>	
		<tr height="25px">
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;"><i><?= $comprobante['tel']; ?></i></td>
			<td style="vertical-align:top;"><i><?= $comprobante['localidad']; ?></i></td>
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;" align="right"><i><?=$comprobante['cp'];?></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>	
		<tr height="20px">
			<td>&nbsp;</td>
			<td style="width:10cm;padding-top:0.2cm">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva0'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva1'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva2'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva3'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva4'];?>
			</td>
			<td></td>
			<td colspan="3"><?= $comprobante['cuit'];?></td>
			<td></td>
			<td></td>
		</tr>	
	</table>
	
	<div style="height:0.2cm;"></div>
	
	<table width="100%" border="<?= $border;?>">
		<tbody style="height:250px; vertical-align:top;">
			<tr style="height:30px; vertical-align:top;">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php $alto = 119; ?>
			<?php $alto -= 50; ?>
			<tr style="height:35px; vertical-align:top;">
				<td width="75%" style="padding-left:0.2cm;"> - Por cancelación de comprobante(s): <?= $str; ?></td>
				<td width="25%" align="right" style="padding-right:0.2cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></td>
			</tr>
			<tr style="height:15px; vertical-align:top;">
				<td width="75%" style="padding-left:0.2cm;"></td>
				<td width="25%" align="right" style="padding-right:0.2cm;"></td>
			</tr>
			<?php $alto -= 15; ?>			
			<tr style="height:15px; vertical-align:top;">
				<td width="75%" style="padding-left:0.2cm;"></td>
				<td width="25%" align="right" style="padding-right:0.2cm;"></td>
			</tr>					
			<tr height="<?= $alto; ?>px" style="vertical-align:bottom;"></tr>
			<tr style="height:1cm;">
				<td align="left" style="padding-left: 0.2cm;padding-top: 0.4cm"><?php if($comprobante['concepto']==1) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:0.8cm;">
				<td align="left" style="vertical-align:bottom;padding-left: 0.2cm"><?php if($comprobante['concepto']==2) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:0.8cm;">
				<td align="left" style="padding-left: 2.2cm"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></td>
				<th align="right" style="padding-right:0.2cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></th>
			</tr>
		</tbody>
	</table>

	<div style="height:1.4cm;"></div>

	<table width="100%" border="<?= $border;?>" style="height:3.5cm">
		<tr>
			<td style="width:65%;">&nbsp;</td>
			<td style="font-size:13px;padding-top:1.6cm;vertical-align:top;"><?= date("d/m/y",$comprobante['date']);?>&nbsp;&nbsp;&nbsp;&nbsp;NOTA DE CREDITO</td>
		</tr>
	</table>

	<div style="height:0.6cm;"></div>

	<table width="100%" style="vertical-align:top;" border="<?= $border;?>">
			<tr height="35px">
			<td style="width:1.6cm; vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;"><i><?= $comprobante['señores'];?></i></td>
			<td style="vertical-align:top;"><i><?=$comprobante['domicilio'];?></i></td>
			<td colspan="3" style="vertical-align:top;">&nbsp;</td>
		</tr>	
		<tr height="25px">
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;"><i><?= $comprobante['tel']; ?></i></td>
			<td style="vertical-align:top;"><i><?= $comprobante['localidad']; ?></i></td>
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;">&nbsp;</td>
			<td style="vertical-align:top;" align="right"><i><?=$comprobante['cp'];?></i>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
		</tr>	
		<tr height="20px">
			<td>&nbsp;</td>
			<td style="width:10cm;">
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva0'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva1'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva2'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva3'];?>
				&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$comprobante['iva4'];?>
			</td>
			<td></td>
			<td colspan="3"><?= $comprobante['cuit'];?></td>
			<td></td>
			<td></td>
		</tr>	
	</table>
	
	<div style="height:0.2cm;"></div>
	
	<table width="100%" border="<?= $border;?>">
		<tbody style="height:250px; vertical-align:top;">
			<tr style="height:30px; vertical-align:top;">
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<?php $alto = 100; ?>
			<?php $alto -= 50; ?>			
			<tr style="height:35px; vertical-align:top;">
				<td width="75%" style="padding-left:0.2cm;"> - Por cancelación de comprobante(s): <?= $str; ?></td>
				<td width="25%" align="right" style="padding-right:0.2cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></td>
			</tr>
			<tr style="height:15px; vertical-align:top;">
				<td width="75%" style="padding-left:0.2cm;"></td>
				<td width="25%" align="right" style="padding-right:0.2cm;"></td>
			</tr>
			<?php $alto -= 15; ?>			
			<tr style="height:15px; vertical-align:top;">
				<td width="75%" style="padding-left:0.2cm;"></td>
				<td width="25%" align="right" style="padding-right:0.2cm;"></td>
			</tr>					
			<tr height="<?= $alto; ?>px">
			<td>&nbsp;</td>
			<td>&nbsp;</td>
			<tr>
				<td>&nbsp;</td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:0.8cm;">
				<td align="left" style="padding-left: 0.2cm;padding-top: 0.4cm"><?php if($comprobante['concepto']==1) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr style="height:0.8cm;">
				<td align="left" style="vertical-align:bottom;padding-left: 0.2cm"><?php if($comprobante['concepto']==2) echo "X"; ?></td>
				<td>&nbsp;</td>
			</tr>
			<tr>
				<td align="left" style="padding-left: 2.2cm"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></td>
				<th align="right" style="padding-right:0.2cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></th>
			</tr>
		</tbody>
	</table>
	
</body>
</html>
