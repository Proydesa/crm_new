<script>
function buscarSelect()
{
	var select=document.getElementById("academias");
	var buscar=document.getElementById("buscar").value;
	for(var i=1;i<select.length;i++)
	{
		if(select.options[i].text==buscar)
		{
			select.selectedIndex=i;
		}
	}
}
</script>

<!--======== FILTROS ========-->
<div class="" style="width:100%">
	<div class="portlet">
		<div class="portlet-header">Busqueda</div>
		<div class="portlet-content" >

			<form id="form_period" autocomplete="off" action="contactos_de_la_red.php?v=search" method="POST" >
				<!--====== ACADEMIAS ======-->
				<div style="padding:16px;background-color:rgba(255,255,255,0.40);width:100%;float:left">
					<h4>Academias</h4>
					Ingrese nombre de la academia:<input type="hidden" id="academias" name="idAcademia"/> 
					<input oninput="onInput()" list="brow" id="inputAcademia" style="width:400px">
					<datalist id="brow">
					<?php foreach($academias_list as $academia): ?>
					<option id="<?= $academia['id']?>"><?= $academia['name']?></option>
						<?php endforeach;?>
					</datalist> 
					<!--<input type="text" id="buscar"><input type="button" value="buscar" onclick="buscarSelect()">
					<select id="academias" name="idAcademia">
						<?php foreach($academias_list as $academia): ?>
						<option value="<?= $academia['id']?>"><?= $academia['name']?></option>
						<?php endforeach;?>
						</select>
					<button id="btn_filter" type="submit" class="fg-button ui-state-default ui-corner-all">BUSCAR</button>-->
				</div>
			</form>

			

		</div>
	</div>
</div>

<script>
function onInput (){
    var options = $('datalist')[0].options;
    for (var i=0;i<options.length;i++){
       if (options[i].value == $("#inputAcademia").val()) 
         {
			$("#academias").val(options[i].id);
			document.getElementById("form_period").submit();
		 	break;
		 }
    }
}
</script>