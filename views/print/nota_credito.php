<?php $border=0; ?>
<?php
	if($comps){
		foreach($comps as $comp):
			$str .= $comp['puntodeventa'].sprintf("%08d", $comp['numero']).", ";
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
<body onload="Print()">

<div style="position:absolute; top:2.3cm; left:14cm;"><?= date("d/m/y",$comprobante['date']);?>&nbsp;&nbsp;&nbsp;&nbsp;</div>

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


<div style="position:absolute; top:8.5cm;left:1.5cm;"> - Por cancelación de comprobante(s): <?= $str; ?></div>
<div style="position:absolute; top:8.5cm;left:17.5cm;">$ <?= number_format($comprobante['importe'],2,',','.');?></div>

<div style="position:absolute; top:12.1cm;left:1.5cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></div>
<div style="position:absolute; top:12.9cm;left:1.5cm;"><?php if($comprobante['concepto']==2) echo "X"; ?></div>

<div style="position:absolute; top:13.7cm;left:2.5cm;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></div>
<div style="position:absolute; top:13.7cm;left:17.5cm; font-size:13px;"><b>$ <?= number_format($comprobante['importe'],2,',','.');?></b></div>

<div style="position:absolute; top:17.5cm; left:14cm;"><?= date("d/m/y",$comprobante['date']);?>&nbsp;&nbsp;&nbsp;&nbsp;</div>

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

<div style="position:absolute; top:23.7cm; left:1.5cm;"> - Por cancelación de comprobante(s): <?= $str; ?></div>
<div style="position:absolute; top:23.7cm; left:17.5cm;"> $ <?= number_format($comprobante['importe'],2,',','.');?></div>

<div style="position:absolute; top:27cm;left:1.5cm;"><?php if($comprobante['concepto']==1) echo "X"; ?></div>
<div style="position:absolute; top:27.7cm;left:1.5cm;"><?php if($comprobante['concepto']==2) echo "X"; ?></div>

<div style="position:absolute; top:28cm;left:2.5cm;"><i><?php if($comprobante['concepto']==2) echo $comprobante['nrocheque']; ?></i></div>
<div style="position:absolute; top:28cm;left:17.5cm; font-size:13px;"><b>$ <?= number_format($comprobante['importe'],2,',','.');?></b></div>

</body>
</html>
