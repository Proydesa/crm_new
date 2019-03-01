<div class="column-c" style="width:600px">
<form action="" method="POST">
	<div id="fechas">
	<?php $view->jquery_datepicker("#startdate, #enddate");?>
Este informe muestra la cantidad de inscriptos desde el	<input type="text" id="startdate" name="startdate" style="width:70px;" value="<?= date('d-m-Y',$qstartdate);?>" /> 
	hasta el <input type="text" id="enddate" name="enddate" style="width:70px;" value="<?= date('d-m-Y',$qenddate);?>" />
</div>
<br/>
<span class="button" onClick="$('#acaselec').slideToggle();"><b>Selecciónar academias</b></span>
<br/>
<div id="acaselec" style="overflow:auto;height:300px; display:none;">
<div align="right">
	<span class="button" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = true;});"><b>Todas</b></span> |
	<span class="button" onClick="$('input[type=checkbox][name=\'academias[]\']').each( function() {	this.checked = false;});"><b>Ninguna</b></span>
</div>
	<script>$(function(){	$('#acalist').makeacolumnlists({cols: 4, colWidth: "80px", equalHeight: 'ul', startN: 1});});</script>
	<ul id="acalist" class="noBullet">
		<?php foreach($academias_user as $academia_user): ?>
			<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?>/><label for="academia[]"><?= $academia_user['shortname']?></label></li>
			<?php if(in_array($academia_user['id'],$acad_sel)) $graf_acad .= "&academy[]=".$academia_user['id'];?>
		<?php endforeach; ?>	
	</ul>

</div>
<div align="right">
<input type="submit" name="Mostrar" value="Mostrar" class="button" style=" font-weight: bold;"></input>
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
						<th class="ui-widget-content">Carrera</th>
						<?php for($x=1;$x<$cantdias;$x++):?>
							<th align="center" style="width: 20px;"  class="ui-widget-content"><?= $x;?></th>
						<?php endfor;?>						
						<th  class="ui-widget-content">Total</th>
					</tr>
				</thead>
				<tbody class="ui-widget-content">
						<?php foreach($comparativa as $carrera => $datos):?>
							<tr style="height: 20px;">
								<td class="press" ondblclick="">
									<a href="#" target="_blank">	
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a><?= $carrera;?>
								</td>
								<td align="center"><b><?= $datos;?></b></td>
							</tr>
							<?php $total+=$datos;?>
						<?php endforeach;?>
					<tr style="height: 20px;">
						<td align="left"><b>Total</b></td>
							<td align="center"><b><?= $total;?></b></td>
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
								<td align="center"><b><?= $datos;?></b></td>
							</tr>
							<?php $total2+=$datos;?>							
						<?php endforeach;?>
					<tr style="height: 20px;">
						<td align="left"><b>Total</b></td>
							<td align="center"><b><?= $total;?></b></td>
					</tr>
				</tbody>
			</table>	
		</div>
	</div>	
	El origen se carga cúando se genera un nuevo usuario en la red proydesa. Los usuarios que ya existían en la base de datos antes de la implementación de este sistema se van a encontrar en la fila <b>"Otros"</b>.
