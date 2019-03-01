<?php
function getMeses($periodo){
	if ($periodo[2]==1){
		return "(Enero Febrero 20".$periodo[0].$periodo[1].")";
	}elseif($periodo[2]==2){
		return "(Marzo Julio 20".$periodo[0].$periodo[1].")";
	}else{
		return "(Agosto Diciembre 20".$periodo[0].$periodo[1].")";
	}	
}
?>

<div class="ui-widget" align="left">
	<div class="column" style="width:70%">
		<div class="portlet">
			<div align="center"><h2><?= $datosAcademia['name'];?></h2></div>
			<div class="portlet-content">
			<form class="form-view" action="contactos_de_la_red.php?v=search&idAcademia=<?= $_REQUEST['idAcademia'] ?>" method="post" id="principal" name="principal" class="ui-widget">
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody class="ui-widget-content">
						<tr style="height: 20px;">
							<td class="ui-widget-content" width="135" align="right"><b>Nombre:</b></td>
							<td class="ui-widget-content" width="230">
								<input  disabled name="name" type="text" value="<?= $datosAcademia['name'];?>"/>
							</td>
							<td class="ui-widget-content" width="135" align="right"><b>Nombre Corto:</b></td>
							<td class="ui-widget-content">
								<input  disabled name="shortname" type="text" value="<?= $datosAcademia['shortname'];?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>E-Mail:</b></td>
							<td class="ui-widget-content">
								<input  disabled name="email" type="text" value="<?= $datosAcademia['email'];?>"/>
							</td>
							<td class="ui-widget-content" align="right"><b>Tel&eacute;fono:</b></td>
							<td class="ui-widget-content">
								<input  disabled name="phone" type="text" value="<?= $datosAcademia['phone'];?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Direcci&oacute;n:</b></td>
							<td class="ui-widget-content">
								<input  disabled name="address" type="text" value="<?= $datosAcademia['address'];?>"/>
							</td>
							<td class="ui-widget-content" align="right"><b>Localidad:</b></td>
							<td class="ui-widget-content">
								<input  disabled name="city" type="text" value="<?= $datosAcademia['city'];?>"/>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Ciudad:</b></td>
							<td class="ui-widget-content">
								<input  disabled name="state" type="text" value="<?= $datosAcademia['state'];?>"/>
							</td>
							<td class="ui-widget-content" align="right"><b>Feed</b></td>
							<td class="ui-widget-content">
								<select id="feed" name="feed" onChange="cambioFeed(this)">
								<option <?= $datosAcademia['feed'] == 'S'? ' selected ':''?>value="S">SI</option>
								<option <?= $datosAcademia['feed'] == 'N'? ' selected ':''?> value="N">No</option>
								</select>
							</td>
						</tr>
						<tr style="height: 20px;">
							<td class="ui-widget-content" align="right"><b>Situacion:</b></td>
							<td class="ui-widget-content" colspan="3" align="center">
								<textarea  name="situacion" id="situacion" style="width:100%;height:212px"><?= $datosAcademia['situacion']?></textarea>
								<input type="button" onClick="cambioSituacion(document.getElementById('situacion'));" name="boton" style="height: 30px; font-size:13px; width:50%; font-weight: bold;" class="button ui-button ui-widget ui-state-default ui-corner-all" value="Guardar" role="button" aria-disabled="false">
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			</div>
		</div>
	</div>
	<div class="ui-widget" align="left">
	<div class="column" style="width:30%">
		<div class="portlet">
			<div align="center"><h2><?= $datosAcademia['name'];?></h2></div>
			<div class="portlet-content">
			<form class="form-view" action="academy.php?v=new&id=<?= $datosAcademia['id'];?>&stage=1" method="post" name="inscripcion" class="ui-widget">
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody class="ui-widget-content">
					<tr style="height: 20px;">
							<td class="ui-widget-content" colspan="4">
							<span onClick="" class="button" align="center"><b>Links y redes sociales</b></span>
									<ul id="links" style="display:block;">
										<li><a href="<?= $datosAcademia['url'];?>" target="_blank">Web</a> (incluir http://): <input  disabled name="url" type="text" value="<?= $datosAcademia['url'];?>"/></li>
										<li><a href="skype:<?= $datosAcademia['skype'];?>?call" target="_blank">Skype</a>: <input  disabled name="skype" type="text" value="<?= $datosAcademia['skype'];?>"/></li>
										<li><a href="<?= $datosAcademia['msn'];?>" target="_blank">MSN</a>: <input  disabled name="msn" type="text" value="<?= $datosAcademia['msn'];?>"/></li>
										<li><a href="http://twitter.com/#!/<?= $datosAcademia['twitter'];?>" target="_blank">Twitter</a> (usuario): <input  disabled name="twitter" type="text" value="<?= $datosAcademia['twitter'];?>"/></li>
										<li><a href="https://www.facebook.com/<?= $datosAcademia['facebook'];?>" target="_blank">Facebook</a> (usuario): <input  disabled name="facebook" type="text" value="<?= $datosAcademia['facebook'];?>"/></li>
										<li><a href="<?= $datosAcademia['googleplus'];?>" target="_blank">Google Plus</a>: <input  disabled name="googleplus" type="text" value="<?= $datosAcademia['googleplus'];?>"/></li>
										<li><a href="<?= $datosAcademia['googlemaps'];?>" target="_blank">Google Maps</a> (coordenadas "x,y"): <input  disabled name="googlemaps" type="text" value="<?= $datosAcademia['googlemaps'];?>"/></li>
										<li><a href="<?= $datosAcademia['linkedin'];?>" target="_blank">LinkedIn</a>: <input  disabled name="linkedin" type="text" value="<?= $datosAcademia['linkedin'];?>"/></li>
									</ul>
							</td>
						</tr>
					</tbody>
				</table>
			</form>
			</div>
		</div>
	</div>
	<span style="clear"></span>
	<div class="column center" style="width:50%">
		<div class="portlet">
			<div class="portlet-header">Usuarios Administrativos</div>
			<div class="portlet-content"  style="overflow: auto; max-height:180px">
				<table class="ui-widget" align="center" style="width:100%;">
					<tbody class="ui-widget-content">
						<?php foreach($u_admins as $u_admin):?>
							<tr style="height: 20px;">
								<td class="ui-widget-content" class="press" ondblclick="window.location.href='contactos.php?v=view&id=<?= $u_admin['id'];?>';">
									<a href="contactos.php?v=view&id=<?= $u_admin['id'];?>" target="_blank">
										<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<?= $u_admin['nombre'];?>
								</td>
								<td class="ui-widget-content"><?= $u_admin['rol'];?></td>
							</tr>
						<?php endforeach;?>
					</tbody>
				</table>
			</div>
		</div>
		</div>
	</div>
	<div class="column center" style="width:50%">
		<div class="portlet">
			<div class="portlet-header">Convenios</div>
			<div class="portlet-content" id="convenios"  style="overflow: auto; max-height: 250px;"></div>
		</div>
	</div>
	<div class="column center" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Alumnos</div>
			<div class="portlet-content"  style="overflow: auto; max-height:180px">
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th><?= $periodos[3]. getMeses($periodos[3]) ?></th>
						<th><?= $periodos[4]. getMeses($periodos[4]) ?></th>
						<th><?= $periodos[5]. getMeses($periodos[5]) ?></th>
					</thead>
					<tbody class="ui-widget-content">
							<tr style="height: 20px;">
							<td style="width:33%;" ><b><?= $totales[3]?></b></td>
							<td style="width:33%;"><b><?= $totales[4]?></b></td>
							<td style="width:33%;"><b><?= $totales[5]?></b></td>
							</tr>
					</tbody>
				</table>
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th><?= $periodos[0]. getMeses($periodos[0]) ?></th>
						<th><?= $periodos[1]. getMeses($periodos[1]) ?></th>
						<th><?= $periodos[2]. getMeses($periodos[2]) ?></th>
					</thead>
					<tbody class="ui-widget-content">
							<tr style="height: 20px;">
							<td style="width:33%;"><b><?= $totales[0]?></b></td>
							<td style="width:33%;"><b><?= $totales[1]?></b></td>
							<td style="width:33%;"><b><?= $totales[2]?></b></td>
							</tr>
					</tbody>
				</table>
				</br>
				<table class="ui-widget" align="center" style="width:100%;">
				<thead>
				<th colspan="3" class="ui-widget-header" style="text-align: left;">Diferencia</th>
				</thead>
				<tbody class="ui-widget-content">
							<tr style="height: 20px;">
							<td style="width:33%;<?= $totales[0]-$totales[3]<0?'color:red':'' ?>"><?= $totales[0]-$totales[3]?></td>
							<td style="width:33%;<?= $totales[1]-$totales[4]<0?'color:red':'' ?>"><?= $totales[1]-$totales[4]?></td>
							<td style="width:33%;<?= $totales[2]-$totales[5]<0?'color:red':'' ?>"><?= $totales[2]-$totales[5]?></td>
							</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="column center" style="width:100%">
		<div class="portlet">
			<div class="portlet-header" style="width:100%" >Contactos de la Red <button id="verTodos" onclick="verTodos()">Ver Todos</button><button style="display:none" id="ultimos" onclick="ultimos()">Ultimos Contactos</button></div>
			<div class="portlet-content"  style="overflow: auto; max-height:180px">
				<table class="ui-widget" id="listado" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th width="10%">Fecha</th>
						<th width="10%">Medio de Contacto</th>
						<th width="35%">Resumen</th>
						<th width="35%">Temas Pendientes</th>
						<th width="10%"> </th>
					</thead>
					<tbody id="tbody_contactos" style="" class="ui-widget-content">
					<?php $contador+=0;?>
					<?php foreach($contactos as $contacto):?>
					<?php $contador+=1;?>
							<tr class="ui-widget-content" id="tr_<?=$contacto['id_contactos_de_la_red']?>" <?= $contador>5 ? 'hidden':''?>>
							<td class="ui-widget-content" id="td_fecha_<?=$contacto['id_contactos_de_la_red']?>"><?= $contacto['fecha_alta'] ?></td>
							<td class="ui-widget-content"><input  type="hidden" id="cod_tipo_contacto_<?=$contacto['id_contactos_de_la_red']?>" value="<?= $contacto['cod_tipo_contacto'] ?>" /><?= $contacto['desc_tipo_contacto'] ?></td>
							<td class="ui-widget-content" id="td_descripcion_<?=$contacto['id_contactos_de_la_red']?>" ><?= $contacto['descripcion'] ?></td>
							<td class="ui-widget-content" id="td_pendientes_<?=$contacto['id_contactos_de_la_red']?>" <?= $contacto['pendientes'] =='S' ? 'style="color:red;"' : ''?>><?= $contacto['descripcion_pendientes'] ?></td>
							<td class="ui-widget-content">
							<button type="button" style="width:20%;height:20px;<?= $contacto['esMiPendiente']== '1' ?'':'display:none;'?>" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="check(<?= $contacto['id_contactos_de_la_red'] ?>)" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-check"></span></button>
							<button type="button" style="width:20%;height:20px" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="editar(<?= $contacto['id_contactos_de_la_red']?>)" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-pencil"></span></button>
							<button type="button" style="width:20%;height:20px" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="personas(<?= $contacto['id_contactos_de_la_red']?>)" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-person"></span></button>
							<button type="button" style="width:20%;height:20px" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="eliminar(<?= $contacto['id_contactos_de_la_red']?>)" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span></button></td>
							</tr>
						<?php endforeach;?>	
					</tbody>
				</table>
			</div>
		</div>
		</div>
	<form class="form-view" action="" method="post" id="formGuardar" class="ui-widget">
		<div class="column center" style="width:100%">
			<div class="portlet">
				<div class="portlet-header">Contacto</div>
				<div class="portlet-content"  style="overflow: auto; max-height:180px">
				<table class="ui-widget" align="center" style="width:100%;">
						<thead class="ui-widget-header">
							<th width="10%">Fecha</th>
							<th width="10%">Medio de Contacto</th>
							<th width="40%">Resumen</th>
							<th width="35%">Temas Pendientes</th>
							<th width="5%"> </th>
						</thead>
						<tbody class="ui-widget-content">
								<tr>
								<td><input type="hidden" id="idContacto" name="idContacto" value="" /><input disabled type="text" id="fecha" value="<?= date("d-m-Y H:i");?>" /></td>
								<td><select id="tipoContacto" name="tipoContacto">
								<?php foreach($tipoContactos as $tipoContacto):?>
									<option value="<?=$tipoContacto['cod_tipo_contacto']?>"><?=$tipoContacto['desc_tipo_contacto']?></option>
								<?php endforeach;?>	
								</select>
								</td>
								<td><textarea style="width:100%" id="descripcion" name="descripcion"></textarea></td>
								<td><textarea style="width:100%" id="descripcion_pendientes" name="descripcion_pendientes"></textarea></td>
								<td><button id="btn_crear" class="button-editar ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" title="Crear un nuevo contacto" onclick="save()" type="button" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-disk"></span>Crear</button>
									<button id="btn_actualizar"  class="button-editar ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" style="display:none"  title="Actualizar el contacto" onclick="update()" type="button" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-refresh"></span>Actualizar</button></td>
								</tr>
						</tbody>
					</table>
				</div>
			</div>
			</div>
		</div>
	</form>
	</div>
	</div>
	</div>
	<div>

		<div id="tabs">
			<ul>
				<li><a href="#tabs-1">Inscriptos Por Carrera</a></li>
				<li><a href="#tabs-2">Instructores</a></li>
				<li><a href="#tabs-3">Help Desk</a></li>
			</ul>
			<div id="tabs-1">
				<table class="ui-widget" align="" style="overflow: auto; max-height: 250px;width: 50%;">
								<thead>
									<tr style="height: 20px;" class="ui-widget-header">
										<th rowspan="2">Carrera</th>
										<th align="center" style="width: 20px;" colspan="3"><?=$periodo_anterior;?></th>
										<th align="center" style="width: 20px;" colspan="3"><?=$periodo_actual;?></th>
										<th align="center" style="width: 20px;" colspan="3">Diferencia</th>
									</tr>
									<tr style="height: 20px;" class="ui-widget-header">
										<th align="center" style="width: 20px;">Insc.</th>
										<th align="center" style="width: 20px;">Bajas</th>
										<th align="center" style="width: 20px;">Cambios</th>
										<th align="center" style="width: 20px;">Insc.</th>
										<th align="center" style="width: 20px;">Bajas</th>
										<th align="center" style="width: 20px;">Cambios</th>
										<th align="center" style="width: 20px;">Insc.</th>
										<th align="center" style="width: 20px;">Bajas</th>
										<th align="center" style="width: 20px;">Cambios</th>
									</tr>					
								</thead>
								<tbody class="ui-widget-content">
										<?php foreach($comparativa as $carrera => $datos):?>
											<tr style="height: 20px;">
												<td class="press" ondblclick=""  class="ui-widget-content">
													<a href="#" target="_blank">	
														<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
													</a><?= $carrera;?>
												</td>
												<td align="center" class="ui-widget-content"><?= $datos[$periodo_anterior]['insc'];?></td>
												<td align="center" class="ui-widget-content"><?= $datos[$periodo_anterior]['bajas'];?></td>
												<td align="center" class="ui-widget-content"><?= $datos[$periodo_anterior]['cambios'];?></td>
												<td align="center" class="ui-widget-content"><?= $datos[$periodo_actual]['insc'];?></td>
												<td align="center" class="ui-widget-content"><?= $datos[$periodo_actual]['bajas'];?></td>
												<td align="center" class="ui-widget-content"><?= $datos[$periodo_actual]['cambios'];?></td>
												<td align="center" class="ui-widget-content"><b><?= ($datos[$periodo_actual]['insc']-$datos[$periodo_anterior]['insc']);?></b></td>
												<td align="center" class="ui-widget-content"><b><?= ($datos[$periodo_actual]['bajas']-$datos[$periodo_anterior]['bajas']);?></b></td>
												<td align="center" class="ui-widget-content"><b><?= ($datos[$periodo_actual]['cambios']-$datos[$periodo_anterior]['cambios']);?></b></td>
											</tr>
											<?php $total[$periodo_anterior]['insc'] +=$datos[$periodo_anterior]['insc'];?>
											<?php $total[$periodo_actual]['insc'] +=$datos[$periodo_actual]['insc'];?>
											<?php $total[$periodo_anterior]['bajas'] +=$datos[$periodo_anterior]['bajas'];?>
											<?php $total[$periodo_actual]['bajas'] +=$datos[$periodo_actual]['bajas'];?>
											<?php $total[$periodo_anterior]['cambios'] +=$datos[$periodo_anterior]['cambios'];?>
											<?php $total[$periodo_actual]['cambios'] +=$datos[$periodo_actual]['cambios'];?>
										<?php endforeach;?>
									<tr style="height: 20px;">
										<td align="left" class="ui-widget-content"><b>Total</b></td>
										<th align="center" class="ui-widget-content"><b><?= $total[$periodo_anterior]['insc'];?></b></th>
										<th align="center" class="ui-widget-content"><b><?= $total[$periodo_anterior]['bajas'];?></b></th>
										<th align="center" class="ui-widget-content"><b><?= $total[$periodo_anterior]['cambios'];?></b></th>
										<th align="center" class="ui-widget-content"><b><?= $total[$periodo_actual]['insc'];?></b></th>
										<th align="center" class="ui-widget-content"><b><?= $total[$periodo_actual]['bajas'];?></b></th>
										<th align="center" class="ui-widget-content"><b><?= $total[$periodo_actual]['cambios'];?></b></th>
										<th align="center" class="ui-widget-content"><b><?= ($total[$periodo_actual]['insc']-$total[$periodo_anterior]['insc']);?></b></th>
										<th align="center" class="ui-widget-content"><b><?= ($total[$periodo_actual]['bajas']-$total[$periodo_anterior]['bajas']);?></b></th>
										<th align="center" class="ui-widget-content"><b><?= ($total[$periodo_actual]['cambios']-$total[$periodo_anterior]['cambios']);?></b></th>
									</tr>
								</tbody>
				</table>
			</div>
			<div id="tabs-2">
				<div class="" style="">
						<div class="" id="instructores"  style="overflow: auto; max-height: 250px;width: 100%;"></div>
				</div>
			</div>
			<div id="tabs-3">
				<div class="" style="">
					<div class="" id="helpDesk"  style="overflow: auto; max-height: 250px;width: 100%;">
					<table name="listado" class="ui-widget" align="" style="width:50%;">
							<thead>
								<tr style="height: 20px;" class="ui-widget-header">
									<th rowspan="2">Tema</th>
									<th colspan="2" title="<?=getMeses($periodos[0])?>"><?=$periodos[0] ?></th>						
									<th colspan="2" title="<?=getMeses($periodos[1])?>"><?=$periodos[1] ?></th>						
									<th colspan="2" title="<?=getMeses($periodos[2])?>"><?=$periodos[2] ?></th>						
								</tr>
								<tr class="ui-widget-header">
										<td>Cantidad</td>
										<td>Promedio</td>
										<td>Cantidad</td>
										<td>Promedio</td>
										<td>Cantidad</td>
										<td>Promedio</td>
								</tr>
							</thead>
							<tbody class="ui-widget-content">
									<?php
									$contCol1=0;
									$contCol2=0;
									$contCol3=0;
									$contCol4=0;
									$contCol5=0;
									$contCol6=0;
									?>
									<?php foreach($help_desk as $key=>$tema):?>
									<?php if ($tema[$periodos[0]] !='' || $tema[$periodos[1]] !='' || $tema[$periodos[2]] !=''){ 
									$contCol1+=$tema[$periodos[0]]['cantidad'];
									$contCol2+=$tema[$periodos[0]]['promedio'];
									$contCol3+=$tema[$periodos[1]]['cantidad'];
									$contCol4+=$tema[$periodos[1]]['promedio'];
									$contCol5+=$tema[$periodos[2]]['cantidad'];
									$contCol6+=$tema[$periodos[2]]['promedio'];				
									?>
										<tr style="height: 20px;">
											<td align="center" class="ui-widget-content"><?= $key;?></td>
											<td align="center" class="ui-widget-content"><?= $tema[$periodos[0]]['cantidad']?></td>
											<td align="center" class="ui-widget-content"><?= $tema[$periodos[0]]['promedio']. ($tema[$periodos[0]]['promedio']!=''?'%':'')?></td>
											<td align="center" class="ui-widget-content"><?= $tema[$periodos[1]]['cantidad']?></td>
											<td align="center" class="ui-widget-content"><?= $tema[$periodos[1]]['promedio']. ($tema[$periodos[1]]['promedio']!=''?'%':'')?></td>
											<td align="center" class="ui-widget-content"><?= $tema[$periodos[2]]['cantidad']?></td>
											<td align="center" class="ui-widget-content"><?= $tema[$periodos[2]]['promedio']. ($tema[$periodos[2]]['promedio']!=''?'%':'')?></td>
										</tr>
									<?php }?>
									<?php endforeach;?>
									<tr style="height: 20px;">
											<td align="right" class="ui-widget-content"><b>Totales</b></td>
											<td align="center" class="ui-widget-content"><?= $contCol1?></td>
											<td align="center" class="ui-widget-content"><?= $contCol2."%"?></td>
											<td align="center" class="ui-widget-content"><?= $contCol3?></td>
											<td align="center" class="ui-widget-content"><?= $contCol4."%"?></td>
											<td align="center" class="ui-widget-content"><?= $contCol5?></td>
											<td align="center" class="ui-widget-content"><?= $contCol6."%"?></td>
									</tr>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
<div id="dialog" title="Usuarios Asignados">
<input type="hidden" id="idContactoSeleccionado"/>
<div id="tablaUsuarios"></div>
Usuario:<input oninput="onInput()" list="brow" id="inputUsuarios" style="">
					<datalist id="brow">
					<?php foreach($usuariosParaContactos as $usuario): ?>
					<option id="<?= $usuario['id']?>"><?= $usuario['name']?></option>
						<?php endforeach;?>
					</datalist> 
</div>
<div id="success"></div>

 	<script>
function check(id){
$("#success").load("./contactos_de_la_red.php?v=check&idContacto="+id);
alert("El Tarea fue actualizada");
window.location.reload();
}
function onInput (){
    var options = $('datalist')[0].options;
    for (var i=0;i<options.length;i++){
       if (options[i].value == $("#inputUsuarios").val()) 
         {
			$("#academias").val(options[i].id);
			$( "#success" ).load( "contactos_de_la_red.php?v=asociacionUsuario&idContacto="+$("#idContactoSeleccionado").val()+"&idUsuario="+options[i].id, function( response, status, xhr ) {
			if ( status == "error" ) {
				alert("Error al guardar el usuario al contacto");
			}else{
				alert("Usuario asignado correctamente");
				$( "#dialog" ).dialog( "close" );
	
			}
			});
		 	break;
		 }
    }
}
function eliminarUsuario(idContacto,idUsuario){
	$( "#success" ).load( "contactos_de_la_red.php?v=desasociacionUsuario&idContacto="+idContacto+"&idUsuario="+idUsuario, function( response, status, xhr ) {
			if ( status == "error" ) {
				alert("Error al eliminar el usuario del contacto");
			}else{
				alert("Usuario desasignado correctamente");
				$( "#dialog" ).dialog( "close" );
			}
			});
	
}
	$( function() {
		$( "#tabs" ).tabs();
		$( "#tabs ul" ).removeClass('ui-widget-header');
		$( "#tabs ul" ).attr("style",'height:10px');
		$( "#dialog" ).dialog({
		autoOpen: false,
		show: {
			effect: "blind",
			duration: 1000
		},
		hide: {
			effect: "explode",
			duration: 1000
		}
		});
	} );
	</script>

	<script type="text/javascript">
	function verTodos(){
		$('#tbody_contactos tr').each(function(){
		$(this).show();
	});
		document.getElementById('verTodos').style.display="none";
		document.getElementById('ultimos').style.display="";
	}
	function ultimos(){
		var contador=0;
		$('#tbody_contactos tr').each(function(){
			contador = contador + 1;
			if (contador<6){
			$(this).show();
		}else{
			$(this).hide();
		}
	});
		document.getElementById('ultimos').style.display="none";
		document.getElementById('verTodos').style.display="";
	}
	function personas(id){
		$("#idContactoSeleccionado").val(id);
		$("#inputUsuarios").val('');
		$( "#tablaUsuarios" ).load( "contactos_de_la_red.php?v=cargarTablaUsuarios&idContacto="+$("#idContactoSeleccionado").val(), function( response, status, xhr ) {
		});
      $( "#dialog" ).dialog( "open" );
	}
	function save(){
		document.getElementById('formGuardar').action="contactos_de_la_red.php?v=search&tipo=save&idAcademia=<?= $_REQUEST['idAcademia'] ?>";
		document.getElementById('formGuardar').submit();

	}
	function update(){
		document.getElementById('formGuardar').action="contactos_de_la_red.php?v=search&tipo=update&idAcademia=<?= $_REQUEST['idAcademia'] ?>";
		document.getElementById('formGuardar').submit();

	}
	function cambioFeed(combo){
		document.getElementById('principal').action="contactos_de_la_red.php?v=search&tipo=cambioFeed&idAcademia=<?= $_REQUEST['idAcademia'] ?>";
		document.getElementById('principal').submit();
	}
	function cambioSituacion(textarea){
		document.getElementById('principal').action="contactos_de_la_red.php?v=search&tipo=cambioSituacion&idAcademia=<?= $_REQUEST['idAcademia'] ?>";
		document.getElementById('principal').submit();
	}
	function editar(id){
		
		document.getElementById('idContacto').value=id;
		document.getElementById('fecha').value=document.getElementById('td_fecha_'+id).innerHTML;
		document.getElementById('tipoContacto').value=document.getElementById('cod_tipo_contacto_'+id).value;
		document.getElementById('descripcion').value=document.getElementById('td_descripcion_'+id).innerHTML;
		document.getElementById('descripcion_pendientes').value=document.getElementById('td_pendientes_'+id).innerHTML;
		document.getElementById('btn_crear').style.display="none";
		document.getElementById('btn_actualizar').style.display="block";
	}
	function eliminar(id){
		if (confirm("Esta seguro que desea eliminar este contacto")){
			document.getElementById('formGuardar').action="contactos_de_la_red.php?v=search&idAcademia=<?= $_REQUEST['idAcademia'] ?>&tipo=delete&idContacto="+id;
			document.getElementById('formGuardar').submit();
		}
	}

		$(function () {
			$('#instructores').html("<p align='center'><img src='themes/cargando.gif' valign='middle' border='0' /> Cargando...</p>").load('academy.php?v=widget-instructores&id=<?= $datosAcademia['id'];?>');
			$('#convenios').html("<p align='center'><img src='themes/cargando.gif' valign='middle' border='0' /> Cargando...</p>").load('academy.php?v=widget-convenios&id=<?= $datosAcademia['id'];?>');
		});

		var contador = 0;
	function nuevo_adjunto(){
			$("#files").append("<div id=\""+contador+"\" style=\"width:300px;margin:auto;\"><input type=\"file\" name=\"archivos[]\" class=\"press\"  style=\"height:30px;\" /><span class=\"ui-icon ui-icon-close\" onclick=\"elimina_adjunto("+contador+")\" class=\"press\" style=\"float:right;\"></span></div>");
			contador++;
	}

	function elimina_adjunto(elemento){
			$("#"+elemento).remove();
	}
	</script>
