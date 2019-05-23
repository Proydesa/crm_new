<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = " style='width:90%; overflow: auto; height: 510px'";
	}else{
		$menufixed = "";
	}
?>
<div class="column-c"<?= $menufixed ?>>

	<!--======== FILTROS ========-->
	<div class="column" style="width:100%">
		<div class="portlet">
			<div class="portlet-header">Filtros</div>
			<div class="portlet-content" >

				<form id="form_period" action="<?= $HULK->SELF?>" method="POST" >
					<div class="flex-container">
						<div class="flex-item">
							<label class="label" for="fd_date_from">Desde</label>
							<input class="field" type="text" id="fd_date_from" name="fd_date_from" value="<?= isset($_POST['fd_date_from']) ? $_POST['fd_date_from'] : date('d/m/Y',time()-(3*31*24*60*60)) ?>" >
						</div>
						<div class="flex-item">
							<label class="label" for="fd_date_to">Hasta</label>
							<input class="field" type="text" id="fd_date_to" name="fd_date_to"  value="<?= isset($_POST['fd_date_to']) ? $_POST['fd_date_to'] : date('d/m/Y',time()) ?>" >
						</div>
						<div class="flex-item" style="background-color:#6e6e6e;width:1px"></div>
						<div class="flex-item">
							<label class="label" for="fd_search">Palabra Clave</label>
							<input class="field" type="text" id="fd_search" name="fd_date_search" >
						</div>
					</div>
					<hr>

					<!--====== ACADEMIAS ======-->
					<div style="padding:16px;background-color:rgba(255,255,255,0.40);width:46%;float:left">
						<h4>Academias</h4>
						<input type="checkbox" class="pais" id="select_all_academies" />
						<label for="select_all_academies">TODAS</label>
						<?php
						foreach($LMS->getPaises() as $pais):
							if (!$H_USER->has_capability("reportes/academycourse/internacionales") AND $pais!="AR"){
								$estado="disabled=disabled";
							}else{
								$estado="";
							}
							?>
							|
							<input type="checkbox" <?= $estado;?> name="pais[]" class="pais" id="<?=$pais;?>" value="<?= $pais;?>" />
							<label for="<?= $pais;?>"><?=$HULK->countrys[$pais]; ?></label>
						<?php
						endforeach;
						?>

						<ul id="list_academies" class="noBullet" style="overflow:auto;max-height:110px;padding:0;border:1px solid #dfdfdf;padding:8px 6px">
							<?php foreach($academias_list as $academia): ?>
								<li>
									<input id="fd_academy_<?= $academia['id'];?>" type="checkbox" name="academias[]" class="academia <?= $academia['country'];?>" value="<?= $academia['id'];?>" <?php if(in_array($academia['id'],$acad_sel)) echo "checked"; ?> />
									<label for="fd_academy_<?= $academia['id'];?>"><?= $academia['name']?></label>
								</li>
							<?php endforeach;?>
						</ul>
					</div>

					<!--========== USUARIOS =========-->
					<div style="padding:16px;background-color:rgba(255,255,255,0.4);width:46%;float:left">
						<h4>Usuarios</h4>
						<input type="checkbox" class="pais" id="select_all_users" checked />
						<label for="select_all_users">TODOS</label>


						<ul class="noBullet" style="overflow:auto;max-height:110px;padding:0;border:1px solid #dfdfdf;padding:8px 6px">
							<?php foreach($academias_users as $user): ?>
							<li><input id="fd_user_<?= $user['userid'] ?>" type="checkbox" name="users[]" class="academia" value="<?= $user['userid'] ?>" <?php if(in_array($user['userid'],$user_sel)) echo "checked"; ?> > <label for="fd_user_<?= $user['userid'] ?>"><?= $user['username'] ?></label></li>
							<?php endforeach;?>
						</ul>
					</div>

					<div class="clearfix"></div>

					<hr>

					<div style="padding:16px;text-align:center">
						<input id="fd_radio_dates" name="radio_views" value="dates" type="radio" <?= isset($_POST['radio_views']) ? ($_POST['radio_views'] == 'dates' ? 'checked' : '') : 'checked' ?> > <label for="fd_radio_dates">Agrupar vista por fechas</label> |
						<input id="fd_radio_academy" name="radio_views" value="academy" type="radio" <?= isset($_POST['radio_views']) ? ($_POST['radio_views'] == 'academy' ? 'checked' : '') : '' ?> > <label for="fd_radio_academy">Agrupar vista por academias</label>
					</div>
				</form>

				<hr>
				<div class="text-center">
					<button id="btn_filter" class="fg-button ui-state-default ui-corner-all">FILTRAR / BUSCAR</button>
				</div>

			</div>
		</div>
	</div>



	<div class="clearfix"></div>

	<?php
	///echo $_POST['fd_date_from'].' - '.$_POST['radio_views'];
	if(isset($_POST['fd_date_from']) && isset($_POST['fd_date_to'])){
		$from  = tounixtime(str_replace('/', '-', $_POST['fd_date_from']));
		$to = tounixtime(str_replace('/', '-', $_POST['fd_date_to']));
	}else{
		$from = time()-(31*24*60*60);
		$to = time();
	}
	?>

	<?php if($_POST['radio_views'] == 'academy'): ?>
	<!--============ VIEW X ACADEMY ============-->
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<?php foreach($activities as $ka => $va):
			$academy = $LMS->getAcademy($va['academyid']);
			$mails = explode(',',str_replace('/', ',', $academy['email']));
			$academy_mails = '';
			foreach ($mails as $km=>$mail) {
				$academy_mails .= '<a href="mailto:'.$mail.'">'.$mail.'</a>';
				$km++;
				if(count($mails)!=$km){
					$academy_mails .= ' &bullet; ';
				}
			}
		?>
		<div class="panel">
			<div class="panel-header">
				<h2><?= '<a href="academy.php?v=view&id='.$academy['id'].'">'.$academy['name'].'</a>'; ?></h2>
				<p><?= $academy['address'].', '.$academy['state'].' <br /> '.$academy_mails.' <br /> Tel: '.$academy['phone']; ?></p>
			</div>
			<div class="panel-body">
				<div class="panel-item">
					<table>
						<thead class="ui-widget-header">
							<tr>
								<th class="td-std">Fecha</th>
								<th class="td-std">Estado</th>
								<th class="td-std">Usuario</th>
								<th class="td-std">Nombre del Contacto</th>
								<th class="td-std">Medio del Contacto</th>
								<th>Resumen</th>
							</tr>
						</thead>
						<?php
						foreach($LMS->getActivitiesByAcademy($va['academyid'],$from,$to,$_POST['users'],$_POST['fd_date_search']) as $kd=>$vd):
							$contactuser = $LMS->getUser($vd['contactid']);
							if($vd['contactid'] == 0){
								$contactuser = 'Contacto directo con la Academia';
							}else{
								$cuser = $LMS->getUser($vd['contactid']);
								$contactuser = $cuser['firstname'].' '.$cuser['lastname'];
							}
							$username = $LMS->getUser($vd['userid']);
							$statusname = explode(' - ',$activity_status[$vd['statusid']]['name']);
							$arrColours = array('A'=>'bg-success','ACT'=>'bg-success','COB'=>'bg-danger','PND'=>'bg-warning','CEC'=>'bg-warning','CA'=>'bg-success','CO'=>'bg-success','CV'=>'bg-success','CNC'=>'bg-success','ET'=>'bg-warning');
						?>
						<tbody>
							<tr>
								<td class="td-std" style="width:100px"><?= fromunixtime($vd['startdate']); ?></td>
								<td class="td-std <?= $arrColours[$statusname[0]] ?>"><?= $statusname[1] ?></td>
								<td class="td-std"><?= $username['firstname'].' '.$username['lastname'] ?></td>
								<td class="td-std"><?= $contactuser ?></td>
								<td class="td-std"><?= $activity_type[$va['typeid']]['name']; ?></td>
								<td class="td-std" style="width:490px;text-align:left">
									<h4><a href="hd.php?v=details&id=<?= $vd['id'] ?>" target="_blank"><?= $vd['subject'] ?></a></h4>
									<p><?= $vd['summary'] ?></p>
									<?php
									$files = $LMS->getFiles($vd['id']);
									if(count($files)):
									?>
									<p style="background:rgba(120,255,0,0.3);padding:10px">
										<b>Archivos: </b><br />
										<?php
										foreach ($files as $file) {
											echo '<a href="'.$file['locate'].'\\'.$file['name'].'" target="_blank">'.$file['name'].'</a><br />';
										}
										?>
									</p>
									<?php
									endif;
									$children = $LMS->getActivityChildren($vd['id']);
									if(count($children)):
									?>
									<b>Comentarios: </b><br />
									<?php
									foreach ($children as $child) {
										$uschild = $LMS->getUser($child['userid']);
										echo '<p style="background:rgba(255,255,0,0.3);padding:6px">'.$child['summary'].' ('.$uschild['firstname'].' '.$uschild['lastname'].')'.'</p>';
									}
									?>

									<?php endif; ?>

								</td>
							</tr>
						</tbody>
						<?php endforeach; ?>
					</table>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

	<?php else: ?>
	<!--============ VIEW X FECHA ============-->
	<div class="portlet ui-widget ui-widget-content ui-helper-clearfix ui-corner-all">
		<?php foreach($activities as $kd=>$vd):	?>

		<div class="panel">
			<div class="panel-header">
				<h1><?= $vd['fecha']; ?></h1>
			</div>
			<div class="panel-body">
				<div class="panel-item">
					<table>
						<thead class="ui-widget-header">
							<tr>
								<th class="td-std">Academia</th>
								<th class="td-std">Estado</th>
								<th class="td-std">Usuario</th>
								<th class="td-std">Nombre del Contacto</th>
								<th class="td-std">Medio de Contacto</th>
								<th >Resumen</th>
							</tr>
						</thead>
						<?php
						foreach($LMS->getActivitiesByDate($vd['startdate'],$_POST['academias'],$_POST['users'],$_POST['fd_date_search']) as $ka=>$va){
							$academy = $LMS->getAcademy($va['academyid']);
							$mails = explode(',',str_replace('/', ',', $academy['email']));
							$academy_mails = '';
							foreach ($mails as $km=>$mail) {
								$academy_mails .= '<a href="mailto:'.$mail.'">'.$mail.'</a>';
								$km++;
								if(count($mails)!=$km){
									$academy_mails .= ' &bullet; ';
								}
							}
							$contactuser = $LMS->getUser($va['contactid']);
							if($va['contactid'] == 0){
								$contactuser = 'Contacto directo con la Academia';
							}else{
								$cuser = $LMS->getUser($va['contactid']);
								$contactuser = $cuser['firstname'].' '.$cuser['lastname'];
							}
							$username = $LMS->getUser($va['userid']);
							$statusname = explode(' - ',$activity_status[$va['statusid']]['name']);
							$arrColours = array('A'=>'bg-success','ACT'=>'bg-success','COB'=>'bg-danger','PND'=>'bg-warning','CEC'=>'bg-warning','CA'=>'bg-success','CO'=>'bg-success','CV'=>'bg-success','CNC'=>'bg-success','ET'=>'bg-warning');
						?>
						<tbody>
							<tr>
								<td class="td-std" style="width:180px">
									<h4><?= '<a href="academy.php?v=view&id='.$academy['id'].'">'.$academy['name'].'</a>'; ?></h4>
									<p><?= $academy['address'].', '.$academy['state'].' <br /> '.$academy_mails.' <br /> Tel: '.$academy['phone']; ?></p>
								</td>
								<td class="td-std <?= $arrColours[$statusname[0]] ?>"><?= $statusname[1] ?></td>
								<td class="td-std"><?= $username['firstname'].' '.$username['lastname'] ?></td>
								<td class="td-std"><?= $contactuser ?></td>
								<td class="td-std"><?= $activity_type[$va['typeid']]['name']; ?></td>
								<td class="td-std" style="width:460px;text-align:left">
									<h4><a href="hd.php?v=details&id=<?= $va['id'] ?>" target="_blank"><?= $va['subject'] ?></a></h4>
									<p><?= $va['summary'] ?></p>
									<?php
									$files = $LMS->getFiles($va['id']);
									if(count($files)):
									?>
									<p style="background:rgba(120,255,0,0.3);padding:10px">
										<b>Archivos: </b><br />
										<?php
										foreach ($files as $file) {
											echo '<a href="'.$file['locate'].'\\'.$file['name'].'" target="_blank">'.$file['name'].'</a><br />';
										}
										?>
									</p>
									<?php
									endif;
									$children = $LMS->getActivityChildren($va['id']);
									if(count($children)):
									?>
									<b>Comentarios: </b><br />
									<?php
									foreach ($children as $child) {
										$uschild = $LMS->getUser($child['userid']);
										echo '<p style="background:rgba(255,255,0,0.3);padding:10px">'.$child['summary'].' ('.$uschild['firstname'].' '.$uschild['lastname'].')'.'</p>';
									}
									?>
									<?php endif; ?>
								</td>
							</tr>
						</tbody>
						<?php } ?>
					</table>
				</div>
			</div>
		</div>
		<?php endforeach; ?>
	</div>

	<?php endif; ?>




</div>



<script>
$(function(){
	$('#fd_date_from,#fd_date_to').datepicker({onSelect:function(date,obj){}});
	$.datepicker.regional['es'] = {closeText:'Cerrar',prevText:'<Ant',nextText:'Sig>',currentText:'Hoy',monthNames:['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'],monthNamesShort:['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'],dayNames:['Domingo','Lunes','Martes','Miércoles','Jueves','Viernes','Sábado'],dayNamesShort:['Dom','Lun','Mar','Mié','Juv','Vie','Sáb'],dayNamesMin:['Do','Lu','Ma','Mi','Ju','Vi','Sá'],weekHeader: 'Sm',dateFormat:'dd/mm/yy',firstDay:1,isRTL:false,showMonthAfterYear:false,yearSuffix:''};
	$.datepicker.setDefaults($.datepicker.regional['es']);
	/////////////////////////////////////////////////
	$('#btn_filter').click(function(){
		if($('#fd_date_from').val() != '' && $('#fd_date_to').val() != '' ){
			var arrfrom = $('#fd_date_from').val().split('/');
			var arrto = $('#fd_date_to').val().split('/');
			var from = new Date(arrfrom[2]+"-"+arrfrom[1]+"-"+arrfrom[0]+" 00:00:00");
			var to = new Date(arrto[2]+"-"+arrto[1]+"-"+arrto[0]+" 00:00:00");
			if(to.getTime()-from.getTime() < 0){
				alert('La fecha inicial debe ser anterior a la final!');
				return;
			}
		}
		if(($('#fd_date_from').val() == '' && $('#fd_date_to').val() != '') || ($('#fd_date_from').val() != '' && $('#fd_date_to').val() == '')){
			alert('Debes ingresar las dos fechas para buscar entre un período');
			return;
		}
		$('#form_period').trigger('submit');
	});
	///////////////////////////////////
	$('#select_all_academies').click(function(){
		if($(this).is(':checked')){
			$('input[name="academias[]"]').attr("checked","checked");
		}else{
			$('input[name="academias[]"]').attr("checked",false);
		}
	});
	///////////////////////////////////
	$('#select_all_users').click(function(){
		if($(this).is(':checked')){
			$('input[name="users[]"]').attr("checked","checked");
		}else{
			$('input[name="users[]"]').attr("checked",false);
		}
	});
	$('input[name="pais[]"]').click(function(){
		var pais = $(this).val();
		if($(this).is(':checked')){
			$('#list_academies .'+pais).attr("checked","checked");
		}else{
			$('#list_academies .'+pais).attr("checked",false);
		}
	});
});
</script>
