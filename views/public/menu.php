<div class="ui-widget-content" id="header" >
	<a href="index.php" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-inicio">Inicio</a>

	<a tabindex="0" href="#menu-buscar" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-buscar"><span class="ui-icon ui-icon-triangle-1-s"></span>Listados</a>
	<div id="menu-buscar" class="hidden">
		<ul>
			<?php if ($H_USER->has_capability('academy/view')):	?>	
				<li><a href="academy.php?v=list">Academias</a></li> 
			<?php endif;?>			
			<?php if ($H_USER->has_capability('activity/view_hd')):	?>	
				<li><a href="hd.php?v=list">Actividades</a></li>
			<?php endif;?>	
			<?php if ($H_USER->has_capability('course/view')):	?>	
				<li><a href="courses.php?v=list">Comisiones</a></li> 
			<?php endif;?>
			<?php if ($H_USER->has_capability('contact/view')):	?>	
				<li><a href="contactos.php?v=list">Contactos</a></li> 
			<?php endif;?>
			<?php if ($H_USER->has_capability('convenios/view')):	?>	
				<li><a href="convenios.php?v=list">Convenios</a></li> 
			<?php endif;?>
			<?php if ($H_USER->has_capability('grupos/view')):	?>	
				<li><a href="grupos.php">Empresas</a></li> 
			<?php endif;?>
		</ul> 
	</div>
	<?php if ($H_USER->has_capability('activity/view_hd')):	?>	
	<a tabindex="7" href="#menu-hd" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-hd"><span class="ui-icon ui-icon-triangle-1-s"></span>HD</a>
	<div id="menu-hd" class="hidden">
		<ul> 
			<li><a href="hd.php">Help Desk</a></li>
			<li><a href="hd.php?v=new">Registrar un incidente nuevo</a></li>
			<li><a href="hd.php?v=list">Ver lista de incidentes</a></li>
			<li><a href="hd.php?v=list">Buscar incidentes</a></li>
			<li><hr/></li> 		
			<li><a href="hd.php?v=newlist">Procedimiento petición listado</a></li>
		</ul> 
	</div> 
	<?php endif;?>
	<?php if ($H_USER->has_capability('reportes/view')):	?>	
		<a tabindex="3" href="#menu-reportes" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-reportes"><span class="ui-icon ui-icon-triangle-1-s"></span>Reportes</a>
		<div id="menu-reportes" class="hidden">
			<ul> 
				<li><p><b>Reportes</b></p></li>
				<?php if ($H_USER->has_capability('reportes/asistencia')):	?>	
					<li><a href="reportes.php?v=asistencia">Asistencia</a></li>
				<?php endif;?>		
				<?php if ($H_USER->has_capability('reportes/bajas')):	?>	
					<li><a href="reportes.php?v=bajas">Bajas</a></li>
				<?php endif;?>						
				<?php if ($H_USER->has_capability('reportes/deudores')):	?>	
					<li><a href="reportes.php?v=deudores">Deudores</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/ekits')):	?>	
					<li><a href="reportes.php?v=ekits">Ekits</a></li>
				<?php endif;?>					
				<?php if ($H_USER->has_capability('reportes/enrolados')):	?>	
					<li><a href="reportes.php?v=enrolados">Enrolados</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/enrolados_completo')):	?>	
					<li><a href="reportes.php?v=enrolados2">Enrolados (Completo)</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/inscriptos')):	?>	
					<li><a href="#">Inscriptos</a>
						<ul>
							<li><a href="reportes.php?v=inscriptos&t=basico">B&aacute;sico</a></li>
							<li><a href="reportes.php?v=inscriptos&t=detalle">Detallado</a></li>
						</ul>
					</li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/listados')):	?>	
					<li><a href="reportes.php?v=listados">Listados</a></li>
				<?php endif;?>						
				<?php if ($H_USER->has_capability('reportes/estadisticas')):	?>	
					<li><p><b>Estadisticas</b></p></li>
					<li><a href="http://dev.www.proydesa.org/estadisticashd/estadisticas.asp" target="_blank">Estado de Situación de Incidentes en el Help Desk</a></li>
				<?php endif;?>
				<li><p><b>Otros informes</b></p></li>
				<li><a href="reportes.php?v=errores_sincro">Errores de sincronizaci&oacute;n de usuarios</a></li>			
				<li><a href="reportes.php?v=errores_sincro_comi">Errores de sincronizaci&oacute;n de comisiones</a></li>			
				<li><a href="reportes.php?v=charlas">Charlas</a></li>
				<?php if ($H_USER->has_capability('reportes/proyectcrm')):	?>	
					<li><a href="reportes.php?v=proyect_crm">Proyect CRM</a></li>
				<?php endif; ?>
			</ul> 
		</div> 
	<?php endif;?>
	<?php if ($H_USER->has_capability('campaing/view')):	?>	
	<a tabindex="1" href="#menu-campaña" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-campaña"><span class="ui-icon ui-icon-triangle-1-s"></span>Campañas</a>
	<div id="menu-campaña" class="hidden">
		<ul> 
			<li><a href="#">Listado</a></li>
			<li><a href="campaign.php?v=new">Nueva campaña</a></li>
			<li><hr/></li> 		
			<li><p><b>Generar para campaña</b></p></li> 
			<li><a href="#">Correo Electronico</a></li> 
			<li><a href="#">Formularios Web</a></li> 
		</ul> 
	</div> 
	<?php endif;?>
	<?php if ($H_USER->has_capability('capacitacion/view')):	?>	
		<a tabindex="1" href="#menu-capacitacion" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-capacitacion"><span class="ui-icon ui-icon-triangle-1-s"></span>Cap. Instructores</a>
		<div id="menu-capacitacion" class="hidden">
			<ul> 
				<li><p><b>Listados</b></p></li> 			
				<li><a href="capacitacion?v=instructores">Instructores</a></li>
				<li><a href="capacitacion?v=comisiones">Comisiones</a></li>
				<li><hr/></li> 		
				<li><a href="#">Otros</a></li> 
			</ul> 
		</div> 
	<?php endif;?>	
	<?php if ($H_USER->has_capability('admin/view')):	?>	
		<a tabindex="4" href="#menu-configuraciones" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-configuraciones"><span class="ui-icon ui-icon-triangle-1-s"></span>Administraci&oacute;n</a>
		<div id="menu-configuraciones" class="hidden">
			<ul> 
				<li><a href="admin.php?v=configuracion">Configuraci&oacute;n general</a></li> 
				<li><a href="admin.php?v=roles">Roles y permisos</a></li>
				<li><a href="#">Cursos</a>
					<ul>
						<li><a href="admin.php?v=cuotas">Cuotas</a></li>
						<li><a href="admin.php?v=libros">Libros</a></li>
						<li><a href="admin.php?v=horarios">Horarios</a></li>
					</ul>
				</li>
				<li><a href="#">Actividades</a>
					<ul>
						<li><a href="admin.php?v=asuntos">Asuntos</a></li>
					</ul>					
				</li>		
				<?php if ($H_USER->has_capability('hd/config')):	?>					
				<li><a href="#">Help Desk</a>
					<ul>
						<li><a href="hd.php?v=categories">Categorias</a></li>
					</ul>					
				</li>		
				<?php endif;?>
				<li><a href="#">Herramientas</a>
					<ul>
						<li><a href="ftp.php">Acceso FTP</a></li>
					</ul>
				</li>
			</ul>
		</div>
	<?php endif;?>
	<a tabindex="6" href="#menu-cuenta" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-cuenta"><span class="ui-icon ui-icon-triangle-1-s"></span>Cuenta</a>
	<div id="menu-cuenta" class="hidden">
		<ul> 
			<li><a href="contactos.php?v=view&id=<?= $H_USER->get_property('id');?>">Perfil</a></li>
			<?php if ($H_USER->has_capability('user/rendicion/rendir')):	?>	
				<li><a href="users.php?v=rendicion">Rendici&oacute;n</a></li> 		
			<?php endif;?>
			<li><a href="#">Estilos</a>
				<ul>
					<?php foreach($HULK->themes as $theme):?>
						<li><a href="<?=$HULK->SELF?>&theme=<?= $theme;?>"><?php if($HULK->theme==$theme) echo " *" ?> <?= $theme;?></a></li>
					<?php endforeach;?>
				</ul>
			</li> 
			<li><a href="#">Idiomas</a>
				<ul>
						<li><a href="#">* Español</a></li>
				</ul>
			</li> 
			<li><hr/></li>
			<li><a href="login.php?logout=1">Cerrar Sesi&oacute;n</a></li> 
		</ul>
	</div> 
	<div class="ui-widget" align="right">
		<div class="ui-widget" align="right" style="width:90%;">
			<form action="contactos.php" method="get" name="buscador" id="buscador">
				<input type="hidden" name="v" value="list" />
				<input class="search" id="search" type="search" autocomplete="off" size="50" maxlength="2048" name="q" title="Buscar en Hulk" value="<?= $q;?>"  spellcheck="false" />
				<button  type="submit" id="searchbuttoncont" value="open contacts" onClick="buscador.action='contactos.php';return true;" class="searchbutton" style="margin:0px;" >Contactos</button>
				<button  type="submit" id="searchbuttoncomi" value="opens comisiones" onClick="buscador.action='courses.php';return true;" class="searchbutton" style="margin:0px;" >Comisiones</button>
			</form>
		</div>
	</div>
	<div class="ui-widget" align="left" style="float:left;">
		<span align="left">
			<b>Nuevo:</b>
			<?php if ($H_USER->has_capability('course/new')):	?>	
				<a href="courses.php?v=new">Comisión</a> | 
			<?php endif;?>
			<?php if ($H_USER->has_capability('contact/new')):	?>	
			<a href="contactos.php?v=new">Contacto</a> | 
			<?php endif;?>
			<?php if ($H_USER->has_capability('group/new')):	?>	
				<a href="grupos.php?v=new">Empresa</a> | 
			<?php endif;?>
			<?php if ($H_USER->has_capability('activity/new')):	?>	
			<a href="hd.php?v=new">Ticket</a> |
			<?php endif;?>
			<?php if ($H_USER->has_capability('campaign/new')):	?>	
			<a href="campaign.php?v=new">Campaña</a>
			<?php endif;?>
		</span>
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

<div id="message" align="center">
	<!--<div class="men ui-widget ui-state-highlight" align="left" style="margin-top:10px; padding:10px; width:50%"> 
		<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span> 
		<strong>Alert:</strong>	<?= $H_USER->get_property('firstname')?> Te falta elegir un tipo de pago.
	</div>
	<script type='text/javascript' language='javascript'>      
		var t1=setTimeout("$('div.men').remove();", 3000);  
	</script>-->
</div>

