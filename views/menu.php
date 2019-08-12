<div class="ui-widget-content" id="header" >

	<div class="header-wrapper">

		<ul class="main-menu">
		
			<?php if ($H_USER->has_capability('menu/crm')):	?>
			<li>
				<a tabindex="3" href="#menu-reportes" class="btn" id="opcion-global">
					<span class="ui-icon ui-icon-triangle-1-s"></span><span>CRM</span>
				</a>

				<ul id="menu-global" class="menu-item">
				
					<li>
						<a href="#" class="item" ><span>Usuarios / Particular</span> <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							
							<?php if ($H_USER->has_capability('contact/view')):	?>
							<li>
								<a href="contactos.php?v=list">Listar</a>
							</li>
							<?php endif;?>
							
							<?php if ($H_USER->has_capability('contact/new')):	?>
							<li>
								<a href="contactos.php?v=nuevo_usuario" >Agregar</a>
							</li>
							<?php endif;?>					

						</ul>
					</li>

					<?php if ($H_USER->has_capability('grupos/view')):	?>
					<li>
						<a href="#" class="item">Usuarios / Empresas <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li>
								<a href="grupos.php" >Listar</a>
							</li>
							<?php if ($H_USER->has_capability('group/new')): ?>
							<li>
								<a href="grupos.php?v=new" >Agregar</a>
							</li>
							<?php endif;?>
						</ul>
					</li>
					<?php endif;?>

					<li>
						<a href="#" class="item" >Comisiones <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">

							<?php if ($H_USER->has_capability('course/view')):	?>
							<li>
								<a href="courses.php?v=list">Listar</a>
							</li>
							<?php endif;?>

							<?php if ($H_USER->has_capability('course/new')):	?>
								<li>
									<a href="courses.php?v=new" >Agregar</a>
								</li>
							<?php endif;?>
						</ul>
					</li>

					<li>
						<a href="#" class="item" >Calendario <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<?php if($H_USER->has_capability('calendario/view')): ?>
							<li>
								<a href="calendario.php?v=view">Editar Calendario</a>
							</li>
							<?php endif; ?>
							
							<li>
								<a href="calendario.php?v=cronograma" >Ciclo Lectivo</a>
							</li>						
							<li>
								<a href="calendario.php?v=grilla" >Grilla de Aulas</a>
							</li>
						</ul>
					</li>

					<li>
						<a href="#" class="item" >Academias <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							
							<?php if ($H_USER->has_capability('academy/view')):	?>
							<li>
								<a href="academy.php?v=list" >Listar todas</a>
							</li>
							<?php endif;?>

							<?php if ($H_USER->has_capability('academy/view')):	?>
							<li>
								<a href="academy.php?v=new" >Agregar</a>
							</li>
							<?php endif;?>

							<?php if ($H_USER->has_capability('academy/view')):	?>
							<li>
								<a href="academy.php?v=view&id=1" >Fundación Proydesa</a>
							</li>
							<?php endif;?>

							<?php if ($H_USER->has_capability('academy/view')):	?>
							<li>
								<a href="academy.php?v=reportes" >Contactos de la Red</a>
							</li>
							<?php endif;?>

							<?php if ($H_USER->has_capability('llamados_a_la_red/all')):	?>
							<li>
								<a href="contactos_de_la_red.php?v=view" >Llamados a la Red</a>
							</li>
							<?php endif;?>

							<?php if ($H_USER->has_capability('academy/view')):	?>
							<li>
								<a href="academy.php?v=view&id=200" >Proydesa ITC (Capacitación de instructores)</a>
							</li>
							<?php endif;?>

						</ul>

					</li>


					<li>
						<a href="#" class="item" >Convenios <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<?php if ($H_USER->has_capability('convenios/view')):	?>
							<li>
								<a href="convenios.php?v=list" style="width:75%;">Listar</a>
							</li>
							<li>
								<a href="convenios.php?v=new" style="width:75%;">Agregar</a>
							</li>
							<li>
								<a href="academy.php?v=agregar_convenio" style="width:75%;">Asociar</a>
							</li>
							<?php endif;?>

						</ul>
					</li>					

				</ul>
			</li>
			<?php endif;?>


			<?php if ($H_USER->has_capability('activity/view_hd')):	?>
			<li>
				<a tabindex="7" href="#menu-hd" class="btn" id="opcion-hd"><span class="ui-icon ui-icon-triangle-1-s"></span><span>Help Desk</span></a>
				
				<ul class="menu-item">
					<?php if ($H_USER->has_capability('activity/view')):	?>
					<li>
						<a href="hd.php" class="item">Panel del Help Desk</a>
					</li>
					<?php endif;?>
					<?php if ($H_USER->has_capability('activity/new')):	?>
					<li>
						<a href="hd.php?v=nuevo" class="item">Registrar un incidente nuevo</a>
					</li>
					<?php endif;?>
					<?php if ($H_USER->has_capability('activity/view')):	?>
					<li>
					<a href="hd.php?v=view" class="item">Ver lista de incidentes</a></li>
					<?php endif;?>
					<?php if ($H_USER->has_capability('activity/representante')):	?>
						<li>
							<a href="reportes_hd.php?v=reporte" class="item">Reporte</a>
						</li>
					<?php endif;?>
					<?php if ($H_USER->has_capability('hd/soporteenlinea')):	?>
						<li>
							<a href="hd.php?v=soporte" class="item">Soporte en linea</a>
						</li>
					<?php endif;?>
					<?php if ($H_USER->has_capability('hd/config')):	?>
					<li class="separator"><b>Administración</b></li>
					<li>
						<a href="hd.php?v=categories" class="item">Categorias</a>
					</li>
					<?php endif;?>
				</ul>
				
			</li>
			<?php endif;?>


			<?php if ($H_USER->has_capability('reportes/view')): ?>
			<li>
				<a tabindex="3" href="#menu-reportes" class="btn" id="opcion-reportes"><span class="ui-icon ui-icon-triangle-1-s"></span><span>Reportes</span></a>				
				<ul class="menu-item">
					
					<?php if ($H_USER->has_capability('reportes/alum_acad')): ?>
					<li><a href="#" class="item">Alumnos por Academia de la red <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li>
								<a href="reportes.php?v=academycourse" >Tabla</a></li>
							<li>
								<a href="reportes.php?v=enrolados2" >Listado</a>
							</li>
						</ul>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/asistencia')): ?>
					<li>
						<a href="#" class="item">Asistencias <i class="fa fa-caret-down"></i></a>
						<ul class="submenu"> 
							<li>
								<a href="reportes_lms.php?v=asistencia" >Asistencia</i></a>
							</li>
							<li>
								<a href="asistencia.php?v=view" >Instructores</a>
							</li>
							<li>
								<a href="asistencia.php?v=reporte">Reporte Asistencia Instructores</a>
							</li>
							<li>
								<a href="reportes_lms.php?v=segAsistenciaParaInstructores">Seguimiento de Asistencia (Instructores)</a>
							</li>
						</ul>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/deudores')): ?>
					<li>
						<a href="reportes.php?v=deudores" class="item" >Inscriptos - Deudores</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/ekits')):	?>
					<li>
						<a href="reportes.php?v=ekits" class="item">Ekits</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/listados')):	?>
					<li>
						<a href="reportes_lms.php?v=listados_difusion" class="item" >Listados Difusión</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/inscriptos')):	?>
					<li>
						<a href="#" class="item">Inscriptos  <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li>
								<a href="reportes.php?v=inscriptos&t=detalle">Inscriptos detallado</a>
							</li>
							<li>
								<a href="reportes.php?v=inscriptos_dia" >Informe de Marketing (Inscriptos por día)</a>
							</li>
							<li>
								<a href="reportes.php?v=inscriptos_mensual" >Informe de Marketing (Inscriptos por mes)</a>
							</li>
							<li>
								<a href="reportes.php?v=inscriptos_periodo" >Comparativa de inscriptos</a>
							</li>
							<li>
								<a href="reportes.php?v=inscriptos_periodo_calendario" >Inscriptos por rango de fechas</a>
							</li>
						</ul>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/bajas')):	?>
					<li>
						<a href="reportes.php?v=bajas" class="item">Bajas</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/comp_pend')):	?>
					<li>
						<a href="reportes.php?v=comp_pendientes" class="item">Comprobantes Pendientes</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/facturacion')):	?>
					<li>
						<a href="reportes.php?v=facturacion" class="item">Facturaci&oacute;n</a>
					</li>
					<li>
						<a href="reportes.php?v=facturacion_ekits" class="item" >Facturaci&oacute;n de eKits</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/iva-ventas')):	?>
					<li>
						<a href="reportes.php?v=iva_ventas" class="item">IVA - Ventas</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/donaciones')):	?>
					<li>
						<a href="reportes.php?v=donaciones" class="item">Donaciones</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/becados')):	?>
					<li>
						<a href="reportes.php?v=becados" class="item" >Becados</a>
					</li>
					<?php endif;?>

					<?php if ($H_USER->has_capability('reportes/empresas')):	?>
					<li>
						<a href="reportes.php?v=empresas" class="item">Empresas</a>
					</li>
					<?php endif;?>

					<li class="separator"><p><b>Herramientas</b></p></li>
					<li>
						<a href="#" class="item">Pagomiscuentas <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<?php if ($H_USER->has_capability('pagomiscuentas/descarga')):	?>
							<li>
								<a href="admin.php?v=pagomiscuentas">Descarga pagomiscuentas</a>
							</li>

							<li>
								<a href="admin.php?v=leerpagomiscuentas">Informe pagomiscuentas</a>
							</li>
							<?php endif;?>
						</ul>
					</li>
				</ul>				
			</li>
			<?php endif;?>


			<?php if ($H_USER->has_capability('admin/view')):	?>
			<li>
				<a tabindex="4" href="#menu-configuraciones" class="btn" id="opcion-configuraciones"><span class="ui-icon ui-icon-triangle-1-s"></span><span>Administraci&oacute;n</span></a>
				<ul class="menu-item">
					<li>
						<a href="admin.php?v=configuracion" class="item">Configuraci&oacute;n general</a>
					</li>
					<li>
						<a href="admin.php?v=roles" class="item">Roles y permisos</a></li>
					<li>
						<a href="sincronizadores.php?v=view" class="item">Sincronizadores</a>
					</li>
					<li>
						<a href="#" class="item">Cursos  <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li>
								<a href="admin.php?v=cuotas">Cuotas</a>
							</li>
							<li>
								<a href="admin.php?v=libros">Libros</a>
							</li>
							<li>
								<a href="admin.php?v=horarios">Horarios</a>
							</li>
						</ul>
					</li>
					<li><a href="#" class="item">Actividades <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li><a href="admin.php?v=asuntos">Asuntos</a></li>
						</ul>
					</li>
					<?php if ($H_USER->has_capability('hd/config')):	?>
					<li>
						<a href="#" class="item">Help Desk  <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li><a href="hd.php?v=categories">Categorias</a></li>
						</ul>
					</li>
					<?php endif;?>
					<li>
						<a href="#" class="item">Herramientas  <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<li><a href="ftp.php" >Acceso FTP</a></li>
						</ul>
					</li>
					<li><a href="admin.php?v=bancos" class="item">Bancos</a></li>
				</ul>
			</li>
			<?php endif;?>

			<li>
				
				<a tabindex="6" href="#menu-cuenta" class="btn" id="opcion-cuenta"><span class="ui-icon ui-icon-triangle-1-s"></span><span>Cuenta</span></a>
				
				<ul class="menu-item">
					<center>
						<div class="portlet-header ui-widget-header ui-corner-all"><b><?= $H_USER->getName();?></b></div>
						<a href="index.php"><img src="https://www.proydesa.org/lms_new/user/pix.php/<?= $H_USER->get_property('id');?>/f1.jpg"/></a></center>

					<li>
						<a href="contactos.php?v=view&id=<?= $H_USER->get_property('id');?>" class="item" >Perfil</a>
					</li>
					<?php if ($H_USER->has_capability('user/rendicion/rendir')):	?>
						<li>
							<a href="users.php?v=rendicion" class="item">Carga Manual Rendici&oacute;n</a></li>
						<li>
							<a href="users.php?v=rendicion" class="item" >Rendici&oacute;n</a>
						</li>
					<?php endif;?>

					<li><a href="#" class="item">Estilos <i class="fa fa-caret-down"></i></a>
						<ul class="submenu">
							<?php foreach($HULK->themes as $theme):?>
								<li><a href="<?=$HULK->SELF?>&theme=<?= $theme;?>"><?php if($HULK->theme==$theme) echo " *" ?> <?= $theme;?></a></li>
							<?php endforeach;?>
						</ul>
					</li>
					<li><a href="#" class="item">Idiomas</a>
						<ul class="submenu">
							<li><a href="#">* Español</a></li>
						</ul>
					</li>
					<li><hr/></li>
					<li><a href="login.php?logout=1" class="item">Cerrar Sesi&oacute;n</a></li>
				</ul>
				
			</li>


			<li>
				<a tabindex="7" href="#menu-enlaces" class="btn" id="opcion-enlaces"><span class="ui-icon ui-icon-triangle-1-s"></span><span>Enlaces</span></a>
				<ul class="menu-item">
					<li class="separator">
						<p><b>Sitios Públicos</b></p></li>
					<li>
						<a href="http://www.proydesa.org/portal" class="item" target="_blank">Portal</a>
					</li>
					<li>
						<a href="http://rrhh.proydesa.org/" class="item" target="_blank">Web Site Laboral</a>
					</li>
					<li>
						<a href="http://estudiemos.proydesa.org/" class="item" target="_blank">Estudiemos .org</a>
					</li>
					<li class="separator">
						<p><b>Redes Sociales</b></p>
					</li>
					<li>
						<a href="http://www.facebook.com/pages/Buenos-Aires-Argentina/Fundacion-Proydesa/329367770317?ref=ts" class="item" target="_blank">Facebook</a>
					</li>
					<li>
						<a href="http://www.flickr.com/photos/48765985@N08/sets/" class="item" target="_blank">Flickr</a>
					</li>
					<li>
						<a href="http://www.slideshare.net/proydesa" class="item" target="_blank">Slideshare</a>
					</li>
					<li>
						<a href="http://twitter.com/proydesa" class="item" target="_blank">Twitter</a>
					</li>
					<li>
						<a href="http://www.youtube.com/user/fundacionproydesa" class="item" target="_blank">Youtube</a>
					</li>
					<li>
						<a href="http://fundacionproydesa.blogspot.com" class="item" target="_blank">Blogspot</a>
					</li>
					<li>
						<a href="http://www.linkedin.com/groups?home=&gid=2911029&trk=anet_ug_hm" class="item" target="_blank">Linkedin</a>
					</li>
					<li>
						<a href="http://www.plaxo.com/groups/profile/231928512564" class="item" target="_blank">Plaxo</a>
					</li>
					<li class="separator">
						<p><b>Internos</b></p>
					</li>
					<li>
						<a href="http://office365.proydesa.org" class="item" target="_blank">WebMail Proydesa</a>
					</li>
					<li>
						<a href="http://192.168.0.9/" class="item" target="_blank">Intranet Proydesa</a>
					</li>
					<li>
						<a href="http://dev.proycore.proydesa.org/lms" class="item" target="_blank">LMS Desarrollo</a>
					</li>
				</ul>
			</li>

		</ul>

		<div class="options">

			<div class="ui-widget" align="right">
				<div class="ui-widget" align="right" style="width:90%;">
					<form action="contactos.php" method="get" name="buscador" id="buscador">
						<input type="hidden" name="v" value="list" />
						<input class="search" id="search" type="search" autocomplete="off" size="20" maxlength="255" name="q" title="Buscar en Hulk" value="<?= $q;?>"  spellcheck="false" />
						<button type="submit" id="searchbuttoncont" value="open contacts" onClick="buscador.action='contactos.php';return true;" style="padding:4px" ><i class="fa fa-search fa-fw"></i> Contactos</button>
						<button type="submit" id="searchbuttoncomi" value="opens comisiones" onClick="buscador.action='courses.php';return true;" style="padding:4px" ><i class="fa fa-search fa-fw"></i> Comisiones</button>
					</form>
				</div>
			</div>			
		</div>

	</div>


	<div class="header-wrapper">
		<div class="ui-widget menu-comisiones">
			<span align="left">
				<b>Nuevo:</b>
				<?php if ($H_USER->has_capability('course/new')):	?>
					<a href="courses.php?v=new">Comisión</a> |
				<?php endif;?>
				<?php if ($H_USER->has_capability('contact/new')):	?>
				<a href="contactos.php?v=nuevo_usuario">Contacto</a> |
				<?php endif;?>
				<?php if ($H_USER->has_capability('group/new')):	?>
					<a href="grupos.php?v=new">Empresa</a>
				<?php endif;?>
			</span>
			<?php if ($H_USER->has_capability('config/periodo')):	?>
			<span> - <b>Periodo: </b></span>
			<span>
				<select name="sessperiodo" id="periodos" style="font-size: 10px; height: 19px;"
				onChange="javascript:location.href = '<?=$HULK->SELF?>&sessperiodo='+this.value;">
					<?php foreach($LMS->getPeriodos() as $periodo):?>
						<option value="<?= $periodo;?>" <?php if($periodo==$HULK->periodo) echo 'selected="selected"'; ?>><?= $periodo;?>
							<?php
							if ($periodo[2]==1){
								echo "(Enero Febrero 20".$periodo[0].$periodo[1].")";
							}elseif($periodo[2]==2){
								echo "(Marzo Julio 20".$periodo[0].$periodo[1].")";
							}else{
								echo "(Agosto Diciembre 20".$periodo[0].$periodo[1].")";
							}
							?>
						</option>
					<?php endforeach;?>
				</select>
			</span>
			<?php endif;?>
		</div>

		<div class="ui-widget" align="right">
			<span align="right">
				Usted se ha autentificado como
				<a href="<?= $HULK->STANDARD_SELF?>&loginas=<?= $H_USER->userID;?>"><?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$H_USER->userID);?></a>
				(<?= $H_USER->get_role('name');?>)
				<?php if ($H_USER->has_capability('site/loginas',$H_USER->userID)):?>
					<?php if($H_USER->loggedas):?>
						Logeado como: <?= $LMS->GetField('mdl_user','CONCAT(lastname,", ",firstname)',$H_USER->loggedas);?>
					<?php endif;?>
				<?php endif;?>
			</span>
		</div>
	</div>


</div>


<div class="menu-spacer"></div>