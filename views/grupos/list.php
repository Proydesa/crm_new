<div class="column-c" style="width:80%">	
	<div clasS="ui-widget" align="center">
		<form class="ui-widget" method="get" action="grupos.php" >
			<span>Periodo de creación: </span>
				<?php $view->jquery_datepicker("#startdate, #enddate");?>
				<input type="text" id="startdate" name="startdate" style="width:90px;" value="<?= date('d-m-Y',$qstartdate);?>" /> / 
				<input type="text" id="enddate" name="enddate" style="width:90px;" value="<?= date('d-m-Y',$qenddate);?>" />
			<input type="submit" class="button" style="font-weight: bold;" value="Consultar">
		</form>	
	</div>
	<div class="portlet">
		<div class="portlet-header">Grupos</div>
		<div class="portlet-content">
				<table  class="tablesorterfilter" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<tr>
							<th width="50px">ID</th>
							<th>Nombre</th>
							<th width="200px">Creado el</th>
						</tr>
					</thead>
					<tbody class="ui-widget-content">
						<?php foreach($grupos as $grupo):?>
						<form action="grupos.php?v=edit" method="post" name="grupos" class="ui-widget"> 
							<tr class="ui-widget-content" style="height: 30px; text-align:center;" class="grupo" >
								<td align="center">
									(<b><?= $grupo['id'];?></b>)
								</td>
								<td align="left" class="press" ondblclick="window.location.href='grupos.php?v=view&id=<?=$grupo['id'];?>';">
									<a href="grupos.php?v=view&id=<?=$grupo['id'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<b><?= $grupo['name'];?></b>
								</td>
								<td>
									<?= show_fecha($grupo['startdate'],"Y-m-d");?>
								</td>
							</tr>
						</form>	
						<?php endforeach;?>
					</tbody>
				</table>
<!--	<div id="pager" class="pager" align="left">
		<form>
			<span class="button-seek-start first">&nbsp;</span>
			<span class="button-seek-prev prev">&nbsp;</span>
			<input type="text" class="pagedisplay"/>
			<span class='button-seek-next next'>&nbsp;</span>
			<span class='button-seek-end last'>&nbsp;</span>		
			<select class="pagesize">
				<option selected="selected"  value="10">10</option>
				<option value="20">20</option>
				<option value="30">30</option>
				<option  value="40">40</option>
			</select>
		</form>
	</div>	-->			
		</div>
	</div>
</div>
