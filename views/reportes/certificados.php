<script>
/*alan*/
	function seleccionar_todo(){

	form = document.forms["academiasc"];

		for (i=0;i<form.elements.length;i++)

    	{

    		if(form.elements[i].type == "checkbox")form.elements[i].checked=1;

    	}

	}
	function deseleccionar_todo(){

	form = document.forms["academiasc"];

		for (i=0;i<form.elements.length;i++)

    	{

    		if(form.elements[i].type == "checkbox")form.elements[i].checked=0;

    	}
	}
	/*alan*/
</script>
<br>
<div class="column" style="width:20%">
	<form name="academiasc" action="reportes.php?v=certificados"  method="post">
		<div class="portlet">
			<div class="portlet-header">Filtro</div>
			<div class="portlet-content" style="overflow: auto;">
				<table class="ui-widget">
					<tr>
						<td>    
							Desde
							<?php $view->jquery("$('#fecha1').datepicker({	changeMonth: true,	changeYear: true, yearRange: '2000:2012', dateFormat: 'dd-mm-yy', monthNamesShort: ['".implode($HULK->meses,"','")."']});");?>
							<input id="fecha1" name="fecha1" type="text" value="<?php echo ($fecha1f);?> " readonly />
						</td>
					</tr>
					<tr>
						<td> 
							Hasta
							<?php $view->jquery("$('#fecha2').datepicker({	changeMonth: true,	changeYear: true, yearRange: '2000:2012', dateFormat: 'dd-mm-yy', monthNamesShort: ['".implode($HULK->meses,"','")."']});");?>
							<input id="fecha2" name="fecha2" type="text" value="<?php echo ($fecha2f) ;?> " readonly  />
						</td>						
					</tr>
				</table>
				<br />
			</div>
		</div>	
		<div class="portlet" >
			<div class="portlet-header">Academias</div>
			<span class="button" type="marcar" onclick="seleccionar_todo()" class="button">Todas</span> |
			<span class="button" type="desmarcar"  onclick="deseleccionar_todo()" class="button">Ninguna</span>
			<br/>
			<div class="portlet-content" style="overflow:auto;max-height:200px;">
				<ul class="noBullet">
					<?php foreach($academias_user as $academia_user): ?>
						<li><input type="checkbox" name="academias[]" value="<?= $academia_user['id'];?>" <?php if(in_array($academia_user['id'],$acad_sel)) echo "checked"; ?> /><label for="academia[]"><?= $academia_user['shortname']?></label></li>                                 
					<?php endforeach; ?>
				</ul>
			</div>										
		</div>
		<button type="submit" class="button">Consultar</button>
	</form>
</div>
<div class="column" style="width:80%" align="left">
	<div class="portlet">
		<div class="portlet-header" >Listado</div>
		<div class="portlet-content"  style="overflow: auto; max-height: 400px;">
			<table id="listado" align="right" class="ui-widget" align="center" style="width:100%;">
				<thead>
					<tr class="ui-widget-header" style="height: 20px;">
						<th width="20%">Nombre y apellido</th>
						<th>DNI</th>
						<th>Comision</th>
						<th>Curso</th>
						<th>Fecha</th>
						<th>Emitido por</th>
					</tr> 
				</thead>
				
					<?php foreach($traer as $row):?>
						<tr style="height: 20px;">
							<td style="height: 20%;"><?= $row['firstname']. " ".$row['lastname'];?></td>
							<td style="height: 10%;">
								<a href="contactos.php?v=view&id=<?=$row['id'];?>"><?=$row['DNI'];?>
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							</td>
							<td style="height: 20%;"><?= $row['comision'];?></td>
							<td style="height: 10%;"><?= $row['completo'];?></td>                 
							<td style="height: 10%;"><?= date('d/m/Y',$row['timecreate'])."   ".date('h:m',$row['timecreate'])." horas";?></td>
							<td style="height: 10%;"><?= $row['nombre']." ".$row['apellido'];?></td>
						</tr>
					<?php endforeach; ?>
				
			</table>   
		</div>
	</div>
</div>	

