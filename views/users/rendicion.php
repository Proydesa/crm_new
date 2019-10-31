<script>
	function Print(){
		$(".rendicion").width("100%");
		document.body.offsetHeight;
		window.print();
		$(".rendicion").width("80%");
	}
</script>

<div class="ui-widget" align="left">
	<div class="column noprint" style="width:20%;">
		<div class="portlet">
			<div class="portlet-header">Hist&oacute;rico de rendiciones</div>
			<div class="portlet-content"  style="max-height:400px;overflow:auto;">
				<table class="ui-widget">
					<?php foreach($rendiciones as $rendicion):?>
						<?php if(show_fecha($dia,"") != show_fecha($rendicion['date'],"")): ?>
							<tr><td><b><?= show_fecha($rendicion['date'],"") ;?></b></td></tr>
						<?php endif;?>
						<?php $dia = $rendicion['date'];?>
						<tr><td>&nbsp;&nbsp;&nbsp;<a href="?v=rendicion&id=<?= $rendicion['id'];?>#<?= $rendicion['id'];?>" name="<?= $rendicion['id'];?>"><?= $LMS->GetField("mdl_user","CONCAT(lastname,', ',firstname)",$rendicion['userid']) ;?></a></td></tr>
					<?php endforeach;?>
				</table>
			</div>
		</div>
	</div>
	<div class="column rendicion" style="width:80%;">
		<div class="portlet">
			<div class="portlet-header">Rendici&oacute;n</div>
			<div class="portlet-content" >
				<table class="ui-widget" align="center" style="width:100%;">
					<thead >
						<tr>
							<td colspan="5">
								<h2>&nbsp;&nbsp;&nbsp;<?= $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname," (", username,")")',$userid);?></h2>
							</td>
							<td colspan="3" align="right">
								<h3><?php if($turendicion['date']): echo show_fecha($turendicion['date'],""); else: echo show_fecha(time(),""); endif;?>&nbsp;&nbsp;&nbsp;</h3>
							</td>
						</tr>
					<tr class="ui-widget-header">
						<th>Hora</th>
						<th>Nro. Comprobante</th>
						<th>Tipo</th>
						<th>DNI</th>
						<th>Apellido</th>
						<th>Empresa</th>
						<th>Forma de pago</th>
						<th>Observaciones</th>
						<th>Importe</th>
					</tr>
					</thead>
					<tbody class="ui-widget-content">
						<?php foreach($comprobantes as $comprobante):?>

							<?php
								$especial = "";
								if($comprobante['p_especial']==1){
									$especial = "ui-state-error";
								}
							?>
							<tr style="height: 20px;" class="<?= $especial; ?>">
								<td class="ui-widget-content"><?=  date('h:i',$comprobante['fecha']); ?>hs</td>
								<td class="ui-widget-content" nowrap id="nrocomprobante-<?=$comprobante['id']?>">
									<?php if ($comprobante['numero']>0):?>
										&nbsp;<?= $comprobante['puntodeventa']; ?>-<?= sprintf("%08d",$comprobante['numero']);?>
									<?php else: ?>
										<form name="nrocomprobante-<?=$comprobante['id']?>" action="contactos.php?v=pagos&id=<?= $comprobante['userid'];?>&action=nrocomprobante" method="post" class="validate">
											&nbsp;<?= $comprobante['puntodeventa']; ?>-<input type='text' name='numero' style="width:80px;" />
											<input type='hidden' name='comprobanteid' value="<?=$comprobante['id']?>" />
											<input class="button" type='submit' value='Guardar' style="padding:.4em;">
										</form>
										<?php $nopodesrendir=1; ?>
									<?php endif; ?>
								</td>
								<td class="ui-widget-content"><?= $HULK->tipos[$comprobante['tipo']]; ?></td>
								<td class="ui-widget-content" align="left"><?= $LMS->getField('mdl_user','username',$comprobante['userid']);?></td>
								<td class="ui-widget-content" align="left"><?= $LMS->getField('mdl_user','lastname',$comprobante['userid']);?></td>
								<td class="ui-widget-content" align="center"><?= $comprobante['gruponame'];?></td>
								<td class="ui-widget-content" align="left">
									<?= $HULK->conceptos[$comprobante['concepto']];?>
									<?php if($comprobante['concepto']==2) echo "(Nro.: ".$comprobante['nrocheque'].")"; ?>
								</td>
								<td class="ui-widget-content" align="left"><?= $comprobante['detalle'] ;?></td>
								<td class="ui-widget-content" align="right"><?php if($comprobante['tipo']==3) echo "- "; ?>$ <?= number_format($comprobante['importe'],2,',','.') ;?></td>
							</tr>
							<?php
								if($comprobante['tipo']!=3){
									$total = $total+($comprobante['importe']);
									$totales[$comprobante['concepto']] += $comprobante['importe'];
								}else{
									$total = $total-($comprobante['importe']);
									$totales[$comprobante['concepto']] -= $comprobante['importe'];
								}
							?>
						<?php endforeach;?>

						<tr class="ui-widget-header">
							<td colspan="8" align="right"><b>Total:</b></td>
							<td align="right"><b>$<?= number_format($total,2, ',','.');?></b></td>
						</tr>
						<tr>
							<td colspan="9" align="center" style="padding:1rem">
								<?php foreach($HULK->conceptos as $con=>$cepto):?>
								Total <?=$cepto;?>: $ <?= number_format($totales[$con],2, ',','.');?> &nbsp; &nbsp; &nbsp;<br/>
								<?php endforeach;?>
							</td>
							<td align="right"></td>
						</tr>
					</tbody>
				</table>
				<form action="?v=rendicion&action=rendir" method="POST">
				<?php if ($rendicionid): ?>
					Observaciones: <span><?= $turendicion['summary'];?></span>
				<?php elseif($nopodesrendir==0): ?>
					Observaciones: <input width="70%" name="summary" class="noprint" value="" />
				<?php endif;?>
				<div align="center" class="noprint">
					<?php if ($rendicionid): ?>
						<!--<a href="javascript:window.print();return false;" type="button" class="button-print">Imprimir</a>-->
						<span class="button-print" OnClick="Print()">Imprimir</span>
					<?php elseif($nopodesrendir==0): ?>
						<input type="submit" class="button-add" name="rendir" value="rendir" />
					<?php endif;?>
				</div>

				<p>Ir al procedimiento de caja</p>


				<?php if($H_USER->has_capability('reportes/comp_pend')): ?>

				<hr>
				<div class="portlet-header">Comprobantes Pendientes de cobro</div>

				<?php if($pendientes): ?>

				<table class="ui-widget" id="comp_pendientes-export" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th>Número</th>
						<th>Tipo</th>
						<th>Concepto</th>
						<th>Fecha</th>
						<th>Importe</th>
						<th>Detalle</th>
						<th>Contacto / Empresa</th>
						<th>Usuario</th>
					</thead>
					<tbody>
						<?php foreach($pendientes as $row): ?>
							<tr class="ui-widget-content" style="height: 20px;">
								<td class="ui-widget-content" align="center" nowrap><?= $row['puntodeventa'].'-'.sprintf("%08d",$row['numero']); ?></td>
								<td class="ui-widget-content"><?= $HULK->tipos[$row['tipo']]; ?></td>
								<td class="ui-widget-content"><?= $HULK->conceptos[$row['concepto']]; ?></td>
								<td class="ui-widget-content" align="center" nowrap><?= date("Y-m-d", $row['date']); ?></td>
								<td class="ui-widget-content" align="right" nowrap>$ <?= number_format($row['importe'],2,",","."); ?></td>
								<td class="ui-widget-content"><?= $row['detalle']; ?></td>
								<?php if($row['grupoid'] > 0) : ?>
									<td class="ui-widget-content" ondblclick="window.location.href='grupos.php?v=view&id=<?= $row['grupoid'];?>';">
										<a href="grupos.php?v=view&id=<?= $row['grupoid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
										<b><?= $H_DB->GetField("h_grupos", "name", $row['grupoid']); ?></b>
									</td>
								<?php else: ?>
									<td class="ui-widget-content" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['userid'];?>';">
										<a href="contactos.php?v=view&id=<?= $row['userid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
										<b><?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname, ' - DNI: ', username)", $row['userid']); ?></b>
									</td>
								<?php endif; ?>
								<td class="ui-widget-content" ondblclick="window.location.href='contactos.php?v=view&id=<?= $row['takenby'];?>';">
									<a href="contactos.php?v=view&id=<?= $row['takenby'];?>" target="_blank">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span></a>
									<b><?= $LMS->GetField("mdl_user", "CONCAT(lastname, ', ', firstname)", $row['takenby']); ?></b>
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
					<tfoot>
					</tfoot>
				</table>
				<?php else: ?>
				<p>No se encontraron comprobantes pendientes de cobro</p>
				<?php endif; ?>

				<?php endif; ?>

				</form>
			</div>
		</div>
		<div align="right" width="100%" class="print">Code: #000<?= $userid;?>000<?=$rendicionid;?>000<?=$turendicion['date']?></div>
	</div>
</div>