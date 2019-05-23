<script>
function enviarMail(id,mail){
	var mensajetext =$("#mensajeMail").val().replace(/\r\n|\r|\n/g,"<br />")
	var param = { 'para' : mail, 'asunto': 'Reporte de Ausentes','mensaje':mensajetext+"<br />"+ $("#"+id).html() };
// OpenWindowWithPost("http://200.32.12.29/crm_new/envioMail.php", "width=1000, height=600, left=100, top=100, resizable=yes, scrollbars=yes", "NewFile", param);
$.ajax({ 
       data: param,
       type:'POST',
       url:'envioMail.php',
       cache:false,
       success: function(response) { 
		   if (response.includes("1")!==false){
			   alert("Mail enviado exitosamente");
		   }else{
			   alert("Mail no enviado");
		   }
        }
      });
}
function OpenWindowWithPost(url, windowoption, name, params)
{
 var form = document.createElement("form");
 form.setAttribute("method", "post");
 form.setAttribute("action", url);
 form.setAttribute("target", name);
 for (var i in params)
 {
   if (params.hasOwnProperty(i))
   {
     var input = document.createElement('input');
     input.type = 'hidden';
     input.name = i;
     input.value = params[i];
     form.appendChild(input);
   }
 }
 document.body.appendChild(form);
 //note I am using a post.htm page since I did not want to make double request to the page
 //it might have some Page_Load call which might screw things up.
 window.open("post.htm", name, windowoption);
 form.submit();
 document.body.removeChild(form);
}
</script>
<?php
if (!isset($_POST['idAcademia'] )){
	$_POST['idAcademia'] = 1;
	$dia=date('d-m-Y');
}

?> 
<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='overflow: auto; height: 510px'";
	}else{
		$menufixed = "";
	}
?>
<div<?= $menufixed ?>>
	<form action="reportes_lms.php?v=segAsistenciaParaInstructores" method="POST" style="margin:0; padding:0;">
		<input type="hidden" id="esReporte" value="0"/>
		<div class="column" style="width:80%">
			<div class="portlet">
				<div class="portlet-header">Filtros</div>
				<div class="portlet-content" >
				<table class="ui-widget" align="center">
						<tr>
							<td>Fecha: </td>
							<td><?php $view->jquery_datepicker2("#startdate, #enddate");?>
								<input id="startdate" style="width:90px;" name="startdate" type="text" align="center" value="<?= trim($dia); ?>" />
							</td>
						</tr>
						<tr>
								<td><b>Academias</b></td>
								<td class="ui-widget-content">
									<select name="idAcademia"  style="width:500px;">
										<option value="0">Todos...</option>
										<?php foreach($academies as $academia): ?>
											<option value="<?= $academia['id']; ?>" <?=$academia['id']== $_POST['idAcademia'] ? 'selected': '' ?> ><?= $academia['name']; ?></option>
										<?php endforeach; ?>
									</select>
								</td>
						</tr>
						<tr>
							<td></td>
							<td><input type="submit" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" class="button"  value="Ver reporte" /><input type="submit" name="boton"  style="height: 30px; font-size:13px; width:25%; font-weight: bold;" class="button"  value="Exportar" onClick='this.form.action="reportes_lms.php?v=segAsistenciaParaInstructoresXLS";' /></td>			
						</tr>
					</table>
				</div>
			</div>
		</div>		
	</form>
	<div class="column" style="width:80%<?= $ejecuto==1 ? '': ';display:none;' ?>">
		<div class="portlet">
			<div class="portlet-header">Asistencias</div>
			<div class="portlet-content" >
			<table class="ui-widget"  style="width:100%;" id="asistencia-export">
					<thead>
						<th>  </th>
						<th>  </th>	
					</thead>
					<tbody>
						<tr>
							<td> Dia </td>
							<td class="ui-widget-content textCenter"><?= $dia;?></td>
						</tr>
						<tr>
							<td> Total de Alumnos </td>
							<td class="ui-widget-content textCenter"><?= $totalDeAlumnos;?></td>
						</tr>
					</tbody>
				</table>
				<table class="ui-widget"  style="width:100%;" id="asistencia-export">
					<thead class="ui-widget-header">
						<th></th>
						<th>Presentes</th>
						<th>Ausentes</th>
						<th>Excusados</th>
					</thead>
					<tbody>
						<tr>
							<td> Alumnos </td>
							<td class="ui-widget-content textCenter bg-success"><?= $presentes;?></td>
							<td class="ui-widget-content textCenter bg-danger"><?= $ausentes;?></td>
							<td class="ui-widget-content textCenter bg-success" style="background-color: #337ab7;"><?= $enrolados;?></td>
						</tr>
						
					</tbody>
				</table>
				<br>
				<table class="ui-widget"  style="width:100%;" >
					<thead class="ui-widget-header">
						<th>Mensaje Mail</th>
					</thead>
					<tbody>
						<tr>
							<td><textarea id="mensajeMail" style="width:100%"></textarea></td>
						</tr>
						
					</tbody>
				</table>
			<br><br>
			<?php foreach($alumnosPorCurso as $row){  ?>
				<button id="btn_enviar_<?= $row['idCurso']?>" onclick="enviarMail(<?= $row['idCurso']?>,'<?= $row['mailDocente']?>')">Mail</button>
				<div id="<?= $row['idCurso']?>" style="display:none;">
				<table align="center" style="width:100%;" cellspacing="0" cellpadding="10" border="0">
					<tr>
					<td  class="textCenter"><?= $row['curso']?> - <?= $row['nombre']?> - Total Ausentes (<?= count($row['1'])+count($row['2'])+count($row['3'])?>)</td>
					</tr>
				</table>
				<table align="center" style="width:100%;" cellspacing="0" cellpadding="10" border="0">
					<thead class="">
						<th >1 Inasistencia</th>
						<th >2 Inasistencia</th>
						<th >3 o mas Inasistencia</th>
					</thead>
					<tbody>
					
					<?php for($i=0;$i<$row['mayorColumna'];$i++){?>
						<tr  data-userid="" class="" data-username="" >
						<td style="width:33%" class="textCenter"><?= isset($row[1][$i]) ? $row[1][$i]['apellido']." ".$row[1][$i]['nombre']."<br><mark>".$row[1][$i]['debe']."</mark>" : '<br>&nbsp' ?></td>
						<td style="width:33%" class="textCenter"><?= isset($row[2][$i]) ? $row[2][$i]['apellido']." ".$row[2][$i]['nombre']."<br><mark>".$row[2][$i]['debe']."</mark>" : '<br>&nbsp' ?></td>
						<td style="width:33%" class="textCenter"><?= isset($row[3][$i]) ? $row[3][$i]['apellido']." ".$row[3][$i]['nombre']."<br><mark>".$row[3][$i]['debe']."</mark>" : '<br>&nbsp' ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				<br>
				<br>
				</div>
				<table class="ui-widget" align="center" style="width:100%;">
					<tr>
					<td class="ui-widget-header textCenter"><a href="courses.php?v=view&id=<?= $row['idCurso']?>"><?= $row['curso']?></a>- <?= $row['nombre']?> - Total Ausentes (<?= count($row['1'])+count($row['2'])+count($row['3'])?>)</td>
					</tr>
				</table>
				<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th >1 Inasistencia</th>
						<th >2 Inasistencia</th>
						<th >3 o mas Inasistencia</th>
					</thead>
					<tbody>
					
					<?php for($i=0;$i<$row['mayorColumna'];$i++){?>
						<tr  data-userid="" class="ui-widget-content" data-username="" >
						<td style="width:33%" class="ui-widget-content textCenter"><?= isset($row[1][$i]) ? "<a href='contactos.php?v=view&id=".$row[1][$i]['idAlumno']."'>" .$row[1][$i]['apellido']." ".$row[1][$i]['nombre']."<br><mark>".$row[1][$i]['debe']."</mark></a>" : '<br>&nbsp' ?></td>
						<td style="width:33%" class="ui-widget-content textCenter"><?= isset($row[2][$i]) ? "<a href='contactos.php?v=view&id=".$row[2][$i]['idAlumno']."'>" .$row[2][$i]['apellido']." ".$row[2][$i]['nombre']."<br><mark>".$row[2][$i]['debe']."</mark></a>" : '<br>&nbsp' ?></td>
						<td style="width:33%" class="ui-widget-content textCenter"><?= isset($row[3][$i]) ? "<a href='contactos.php?v=view&id=".$row[3][$i]['idAlumno']."'>" .$row[3][$i]['apellido']." ".$row[3][$i]['nombre']."<br><mark>".$row[3][$i]['debe']."</mark></a>" : '<br>&nbsp' ?></td>
					</tr>
					<?php } ?>
					</tbody>
				</table>
				<br>
				<br>
					<?php } ?>
				
					<table class="ui-widget" align="center" style="width:100%;">
					<thead class="ui-widget-header">
						<th >Nro.</th>					
						<th >Instructor</th>
						<th >Comision</th>
						<th >Llegada/Inicio de clase</th>
						<th >Finalización de clase</th>
						<th >Observacion</th>
					</thead>
					<tbody>
						<?php $nro=1; ?>
						<?php foreach($asistenciaInstructores as $row){
							$colorFin ="";
							$colorFila="";
							$colorInicio="";
							if ($row['Asistencia']!="AUSENTE"){
								if (strtotime($row['fin'])< strtotime($row['deberia_finalizar'].' - 30 minutes')){
									$colorFin = ' bg-danger ';
								}
								if (strtotime($row['fin'])>= strtotime($row['deberia_finalizar'].' - 30 minutes') && strtotime($row['fin'])< strtotime($row['deberia_finalizar'].' - 15 minutes')){
									$colorFin = ' bg-warning ';
								}
								if (strtotime($row['fin'])>= strtotime($row['deberia_finalizar'].' - 15 minutes') && strtotime($row['fin'])<= strtotime($row['deberia_finalizar']) ){
									$colorFin = ' bg-success ';
								}
								if (strtotime($row['fin'])> strtotime($row['deberia_finalizar'])  ){
									$colorFin = ' bg-danger ';
								}
								$colorInicio = strtotime($row['inicio']) <= strtotime($row['deberia_iniciar']) ? ' bg-success ' : (strtotime($row['inicio']) <= strtotime($row['deberia_iniciar'].' + 15 minutes') ? ' bg-warning ' : ' bg-danger ') ;
								if ($row['inicio']==""){
									$colorInicio='';
								}
								if ($row['fin']==""){
									$colorFin='';
								}
								
							}else{
								$colorFila= ' bg-danger ';
								$row['inicio']="";
								$row['fin']="";	
							}
							?>
							<tr  data-userid="" class="ui-widget-content" data-username="" >
								<td style="width:5%" class="ui-widget-content textCenter <?= $colorFila?>"><?=  $nro;$nro++; ?></td>
								<td style="width:20%" class="ui-widget-content textCenter <?= $colorFila?>"><?=  $row['nombre_instructor']; ?></td>
								<td style="width:25%" class="ui-widget-content textCenter <?= $colorFila?>"><a href='./courses.php?v=view&id=<?= $row['id_comision']; ?>'><?= $row['nombre_comision']; ?></a></td>
								<td style="width:5%" class="ui-widget-content textCenter <?= $colorFila?> <?= $colorInicio?> "><?=  $row['inicio']; ?></td>
								<td style="width:5%" class="ui-widget-content textCenter <?= $colorFila?> <?= $colorFin?> "><?=  $row['fin']; ?></td>
								<td style="width:30%" class="ui-widget-content textCenter <?= $colorFila?> "><?=  $row['Observacion']; ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>