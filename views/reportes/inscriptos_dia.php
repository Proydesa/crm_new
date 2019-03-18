<div class="column-c" style="width:1000px">
<form action="" method="POST">
Este informe muestra la cantidad de inscriptos por día de
<select name="mes">
<?php foreach($HULK->meses as $numeromes => $namemes): ?>
	<option value="<?= $numeromes;?>" <?php if($numeromes == $mes) echo "SELECTED";?>><?= $namemes;?></option>
<?php endforeach;?>
</select> de
<select name="ano">
	<?php for($x=2010; $x<(date('Y')+1);$x++):?>
	<option value="<?=$x;?>" <?php if($x == $ano) echo "SELECTED";?>><?=$x;?></option>
	<?php endfor;?>
</select>

de comisiones del período
<select name="periodo">
<?php foreach($LMS->getPeriodos() as $per): ?>
	<option value="<?= $per;?>" <?php if($per == $periodo) echo "SELECTED";?>><?= $per;?></option>
<?php endforeach;?>
</select>
<input type="submit" style="font-weight: bold" name="Mostrar" value="Mostrar"></input>
<span  title="Solo se restan las inscripciones que luego sufren un cambio de comision, estos dejan de aparecer en el día que se inscribieron por primera ves y pasan a aparecer en el día en que se hizo el cambio de comisíon.
Esto es por como se guarda la información de inscripción en la base de datos.">(?)</span>
<br/>
<br/><span class="button" style=" width:20%; font-weight: bold; height:25px;" onClick="$('#acaselec').slideToggle();">Selecciónar academias</span>
<div id="acaselec" style="overflow:auto;height:300px; display:none;">
<br/>
		<span class="button" style="height: 25px; font-size:11px; width:20%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = true;});">Todas</span> |
		<span class="button" style="height: 25px; font-size:11px; width:20%; font-weight: bold;" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = false;});">Ninguna</span>
		<br/>
		<br/>
	<script>$(function(){	$('#acalist').makeacolumnlists({cols: 8, colWidth: "100px", equalHeight: 'ul', startN: 1});});</script>
	<ul id="acalist" class="noBullet">
		<?php foreach($academias_user as $academia_user): ?>
			<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]"><?= $academia_user['shortname']?></label></li>
			<?php if(in_array($academia_user['id'],$acad_sel)) $graf_acad .= "&academy[]=".$academia_user['id'];?>
		<?php endforeach; ?>
	</ul>
</div>
</form>
	<div class="portlet">
		<div class="portlet-header">Inscriptos por carrera</div>
		<!--<div class="portlet-content"  id="table_inscriptos">

		</div>-->
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr style="height: 20px;" class="ui-widget-header">
						<th align="center">Ord</th>
						<th>Carrera</th>
						<?php for($x=1;$x<$cantdias;$x++):?>
							<th align="center" style="width: 20px;"><?= $x;?></th>
						<?php endfor;?>
						<th>Total</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
						<?php foreach($comparativa as $carrera => $datos):?>
							<tr style="height: 20px;">
							<?php $y++;?>
							<td align="center"><b><?= $y;?></b></td>
								<td class="press" ondblclick="">
									<a href="#" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><?= $carrera;?>
								</td>
								<?php for($x=1;$x<$cantdias;$x++):?>
								<?php if (in_array(date("w", strtotime($x."-".$mes."-".$ano)),array(0,6))){ $back="feriado";}else{$back="";}?>
								<td align="center" class="<?= $back;?>" ><?= $datos[$x];?></td>
									<?php $total[$x] += $datos[$x]; $total[$carrera] += $datos[$x];?>
								<?php endfor;?>
								<td align="center"><b><?= $total[$carrera]?></b></td>
							</tr>
						<?php endforeach;?>
					<tr style="height: 20px;">
						<td align="center"></td>
						<td align="left"><b>SubTotal</b></td>
						<?php for($x=1;$x<$cantdias;$x++):?>
							<td align="center"><b><?= $total[$x];?></b></td>
							<?php $totalisimo +=$total[$x];?>
						<?php endfor;?>
							<td align="center"><b><?= $totalisimo;?></b></td>
					</tr>
				<tr style="height: 20px;">
					<td align="center"></td>
					<td align="left"><b>Totales acumulados:</b></td>
					<?php for($x=1;$x<$cantdias;$x++):?>
						<?php $totalacumulado+=$total[$x];?>
						<td align="center"><b><?= $totalacumulado;?></b></td>
					<?php endfor;?>
					<td align="center"><b><?= $totalisimo;?></b></td>
				</tr>
					<tr style="height: 20px;">
						<td align="center"></td>
						<td align="left"><b>Cambios de comisi&oacute;n:</b></td>
						<?php 	$puntero=$totalcambio=0;
								for($x=1;$x<$cantdias;$x++):
									if($cambios[$puntero]['dia']==$x){?>
										<td align="center"><b><?= $cambios[$puntero]['cant'] ?></b></td>
								<?php   $totalcambio+= $cambios[$puntero]['cant'];
										$puntero++;
									}else{ ?>
										<td align="center"><b>0</b></td>
								<?php } 
								endfor;?>
						<td align="center"><b><?= $totalcambio ?></b></td>
					</tr>
			</tbody>
			</table>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Inscriptos por origen</div>
		<!--<div class="portlet-content"  id="table_inscriptos">

		</div>-->
		<div class="portlet-content">
			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr style="height: 20px;" class="ui-widget-header">
						<th>Origen</th>
						<?php for($x=1;$x<$cantdias;$x++):?>
							<th align="center" style="width: 20px;"><?= $x;?></th>
						<?php endfor;?>
						<th>Total</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
						<?php foreach($origenes as $origen => $datos):?>
							<tr style="height: 20px;">
								<td class="press" ondblclick="">
									<a href="#" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><?= $origen;?>
								</td>
								<?php for($x=1;$x<$cantdias;$x++):?>
								<?php if (in_array(date("w", strtotime($x."-".$mes."-".$ano)),array(0,6))){ $back="feriado";}else{$back="";}?>
								<td align="center" class="<?= $back;?>" ><?= $datos[$x];?></td>
									<?php $total3[$x] += $datos[$x];$total[$origen]+= $datos[$x];?>
								<?php endfor;?>
								<td align="center"><b><?= $total[$origen]?></b></td>
							</tr>
						<?php endforeach;?>
					<tr style="height: 20px;">
						<td align="left"><b>SubTotal</b></td>
						<?php for($x=1;$x<$cantdias;$x++):?>
							<td align="center"><b><?= $total3[$x];?></b></td>
						<?php endfor;?>
						<td align="center"><b><?= $totalisimo;?></b></td>
					</tr>
					<tr style="height: 20px;">
						<td align="left"><b>Totales acumulados:</b></td>
						<?php for($x=1;$x<$cantdias;$x++):?>
							<?php $totalacumulado2+=$total3[$x];?>
						<td align="center"><b><?= $totalacumulado2;?></b></td>
					<?php endfor;?>
							<td align="center"><b><?= $totalisimo;?></b></td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	El origen se carga cúando se genera un nuevo usuario en la red proydesa. Los usuarios que ya existían en la base de datos antes de la implementación de este sistema se van a encontrar en la fila <b>"Otros"</b>.
