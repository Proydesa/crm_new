<div class="column-c" style="width:1000px">

<form action="" method="POST">
Mostrar los inscriptos de los cursos del periodo
<select name="periodo">
<?php foreach($LMS->getPeriodos() as $per): ?>
	<option value="<?= $per;?>" <?php if($per == $periodo) echo "SELECTED";?>><?= $per;?></option>
<?php endforeach;?>
</select>
<input type="submit" style="font-weight: bold" name="Mostrar" value="Mostrar"></input>
<br/>
</form>
	<div class="portlet">
		<div class="portlet-header">Inscriptos por carrera mensuales</div>
		<!--<div class="portlet-content"  id="table_inscriptos">

		</div>-->
		<div class="portlet-content">
			<?php if(count($carreras)):
			$totaldeAcum=0;?>
			<table class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr style="height: 20px;" class="ui-widget-header">
						<th align="center"></th>
						<th>Carrera</th>
						<th>Comision</th>
                        <th>Acumulado</th>
 						<?php foreach($meses[$periodo[2]] as $mes):?>
							<th align="center" style="width: 20px;"><?= $HULK->meses[$mes];?></th>
						<?php endforeach;?>
 						<th>Total</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
						<?php foreach($carreras as $nombre_comi => $comision):?>
							<tr style="height: 20px;">
								<?php $y++;?>
								<td align="center" class="ui-widget-content"><b><?= $y;?></b></td>
								<?php if ($carrera==$comision['carrera']):
										$z++;
									else:
										$z=1;$carrera=$comision['carrera'];?>
									<td rowspan="<?=$cantidad[$comision['carrera']];?>" align="center" class="ui-widget-content"><b><?= $comision['carrera'];?></b> (<?=$cantidad[$comision['carrera']];?>)</td>
								<?php endif;?>
								<td class="press ui-widget-content" ondblclick="window.location.href='courses.php?v=view&id=<?= $comision['courseid'];?>';">
									<a href="courses.php?v=view&id=<?= $comision['courseid'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
										</a><?= $nombre_comi;?>
								</td>
                                <?php
								$resultado = array_diff(array(1,2,3,4,5,6,7,8,9,10,11,12),$meses[$periodo[2]]);
								$totalA=0;
								foreach($resultado as $mes):
									$totalA+=$comision[$mes]['enrolados'];
								endforeach;
								$totaldeAcum+=$totalA;
								?>
                                <td class="ui-widget-content" align="center"><?= $totalA;?></td>
		 						<?php foreach($meses[$periodo[2]] as $mes):?>
									<td class="ui-widget-content" align="center" ><?= $comision[$mes]['enrolados'];?></td>
									<?php
										$total[$nombre_comi]['enrol']+=$comision[$mes]['enrolados'];
										$total[$mes]['enrol']+=$comision[$mes]['enrolados'];
									?>
								<?php endforeach;?>
									<td class="ui-widget-content" align="center" ><b><?= $total[$nombre_comi]['enrol']+$totalA?></b></td>
							</tr>
						<?php endforeach;?>
					<tr style="height: 20px;" class="ui-widget-header">
						<th align="center"></th>
						<th>Carrera</th>
						<th>Comision</th>
						<th></th>
 						<?php foreach($meses[$periodo[2]] as $mes):?>
							<th align="center" style="width: 20px;"><?= $HULK->meses[$mes];?></th>
						<?php endforeach;?>
 						<th>Total</th>
					</tr>
					<tr style="height: 20px;" class="ui-widget-content">
						<th align="center"></th>
						<th align="center"></th>
						<th align="right">Total por mes <b><?= $periodo;?></b>:</th>
						<th align="center" style="width: 20px;" class="ui-widget-content"><?=$totaldeAcum?></th>
 						<?php foreach($meses[$periodo[2]] as $mes):?>
							<th align="center" style="width: 20px;" class="ui-widget-content"><b><?= $total[$mes]['enrol'];?></b></th>
							<?php $ultratotal +=$total[$mes]['enrol'];?>
						<?php endforeach;?>
 						<th class="ui-widget-content"></th>
					</tr>
					<tr style="height: 20px;" class="ui-widget-content">
						<th align="center"></th>
						<th align="center"></th>
						<th align="right">Total acumulado <b><?= $periodo;?></b>:</th>
                        <th align="center" style="width: 20px;" class="ui-widget-content"><?=$totaldeAcum?></th>

 						<?php 
						$totalacumulado=$totaldeAcum;
						foreach($meses[$periodo[2]] as $mes):?>
 							<?php $totalacumulado+=$total[$mes]['enrol'];?>
							<th align="center" style="width: 20px;" class="ui-widget-content"><b><?= $totalacumulado;?></b></th>
						<?php endforeach;?>
 						<th class="ui-widget-content"><b><?= $ultratotal+$totaldeAcum;?></b></th>
					</tr>
				</tbody>
			</table>
			<?php else:?>
			<div class="portlet" align="center" style="padding:10px">
				No hay comisiones creadas en este periodo para esta academia.
			</div>
			<?php endif;?>			
		</div>
	</div>
