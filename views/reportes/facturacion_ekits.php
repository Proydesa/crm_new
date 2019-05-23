<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 510px'";
	}else{
		$menufixed = "";
	}
?>
<div class="column-c"<?= $menufixed ?>>
	<div class="portlet">
		<div class="portlet-header" align="center">Facturación eKit</div>
		<div class="portlet-content" >
			<table id="detalle-export" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th>Año</th>
						<th>Mes</th>
						<th>Facturado</th>
						<th>Cobrado</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($ekits as $row):?>
					<tr class="ui-widget-content" style="height: 20px;">
						<td class="ui-widget-content"><?= $row['ano'];?></td>
						<td class="ui-widget-content"><?= $row['mes'];?></td>
						<td class="ui-widget-content"><?= $row['facturado'];?></td>
						<td class="ui-widget-content"><?= $row['cobrado'];?></td>
					</tr>
				<?php endforeach;?>
				</tbody>
			</table>
		</div>
	</div>
</div>