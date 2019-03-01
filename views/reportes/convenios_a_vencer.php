<h2 class="ui-widget">Convenios a vencer en los pr&oacute;ximos <?= $HULK->venc_convenio;?> d&iacute;as</h2>
<div class="column-c" style="width:90%">
	<div class="portlet-content">
		<table class="ui-widget" align="center" style="width:100%;">
			<thead>
					<tr style="height: 20px;" class="ui-widget-header">
						<th>&nbsp;</th>
						<th>Academia</th>
						<th>Nombre</th>
						<th></th>
						<th>A</th>
						<th>C</th>
						<th>Inicio</th>
						<th>Fin</th>
					</tr>
			</thead>
			<tbody class="ui-widget-content">
				<?php $i=1;?>
				<?php foreach($convenios as $convenio):?>
					<tr style="height: 20px;">
						<td align="right"><b><?= $i; ?></b></td>
						<td><?= $convenio['academy']; ?></td>
						<td><?= $convenio['name'];?></td>
						<td><?= $convenio['shortname'];?></td>
						<td style="width:15px;" align="right"><?= $convenio['opens'];?></td>
						<td style="width:15px;" align="right"><?= $convenio['closes'];?></td>
						<td style="width:80px;" align="right"><?= date('d-m-Y',$convenio['timestart']);?></td>
						<td style="width:80px;" align="right"><?= date('d-m-Y',$convenio['timeend']);?></td>
					</tr>
					<?php $i++;?>
				<?php endforeach;?>
			</tbody>
		</table>
	</div>
</div>