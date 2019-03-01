<div class="ui-widget-content" id="header" >
		<?php if ($H_USER->has_capability('menu/crm')):	?>
		<a tabindex="3" href="#menu-reportes" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-global"><span class="ui-icon ui-icon-triangle-1-s"></span>CRM</a>
		<div id="menu-global" class="hidden">
			<ul>
              <li><a href="#" style="font-weight:bold">Usuarios</a>
                <ul>
                  <li><a href="#">Particular</a>
                    <ul>
                      	<?php if ($H_USER->has_capability('contact/view')):	?>
							<li>
                                <a href="#" onclick="window.open('contactos.php?v=list');" style="width:10%;">
                                    <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="contactos.php?v=list" style="width:75%;">Listar</a>
                            </li>
						<?php endif;?>
		<!--			<?php if ($H_USER->has_capability('contact/view')):	?>
							<li>
							<a href="#" onclick="window.open('contactos.php?v=list');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<a href="contactos.php?v=list" style="width:75%;">Listar Contactos Administrativos</a></li>
						<?php endif;?>
		-->				<?php if ($H_USER->has_capability('contact/new')):	?>
							<li>
                                <a href="#" onclick="window.open('contactos.php?v=new');" style="width:10%;">
                                    <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="contactos.php?v=nuevo_usuario" style="width:75%;">Agregar</a>
                            </li>
						<?php endif;?>
                    </ul>
                  </li>
				<?php if ($H_USER->has_capability('grupos/view')):	?>
					<li><a href="#">Empresas</a>
						<ul>
							<li>
								<a href="#" onclick="window.open('grupos.php');" style="width:10%;">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<a href="grupos.php" style="width:75%;">Listar</a>
                            </li>
							<?php if ($H_USER->has_capability('group/new')):	?>
								<li>
									<a href="#" onclick="window.open('grupos.php?v=new');" style="width:10%;">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
									</a>
									<a href="grupos.php?v=new" style="width:75%;">Agregar</a>
                            </li>
							<?php endif;?>
						</ul>
					</li>
				<?php endif;?>
			</ul>
					<li><a href="#" style="font-weight:bold">Comisiones</a>
                    	<ul>
							<?php if ($H_USER->has_capability('course/view')):	?>
                                <li>
                                    <a href="#" onclick="window.open('courses.php?v=list');" style="width:10%;">
                                    <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                    </a>
                                    <a href="courses.php?v=list" style="width:75%;">Listar</a>
                                </li>
                            <?php endif;?>
                            <?php if ($H_USER->has_capability('course/new')):	?>
                                <li>
                                    <a href="#" onclick="window.open('courses.php?v=new');" style="width:10%;">
                                    <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                    </a>
                                    <a href="courses.php?v=new" style="width:75%;">Agregar</a>
                                </li>
                            <?php endif;?>
						</ul>
					</li>
				<li><a href="#" style="font-weight:bold">Calendario</a>
					<ul>
							<?php if($H_USER->has_capability('calendario/view')): ?>
                            <li>
                                <a href="#" onclick="window.open('calendario.php?v=view');" style="width:10%;"><span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a> 
                                <a href="calendario.php?v=view" style="width:75%;">Editar Calendario</a>
                            </li>
                            <?php endif; ?>
            
                            <li>
                                <a href="#" onclick="window.open('calendario.php?v=cronograma');" style="width:10%;"><span class="ui-icon ui-icon-newwin" style="float:left;"></span></a> 
                                <a href="calendario.php?v=cronograma" style="width:75%;">Ciclo Lectivo</a>
                            </li>
            
                            <li>
                                <a href="#" onclick="window.open('calendario.php?v=grilla');" style="width:10%;"><span class="ui-icon ui-icon-newwin" style="float:left;"></span></a> 
                                <a href="calendario.php?v=grilla" style="width:75%;">Grilla de Aulas</a>
                            </li>
					</ul>
				</li>
				<li><a href="#" style="font-weight:bold">Academias</a>
                	<ul>
						<?php if ($H_USER->has_capability('academy/view')):	?>
                            <li>
                                <a href="#" onclick="window.open('academy.php?v=list');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="academy.php?v=list" style="width:75%;">Listar todas</a>
                            </li>
                        <?php endif;?>
                        <?php if ($H_USER->has_capability('academy/view')):	?>
                            <li>
                                <a href="#" onclick="window.open('academy.php?v=new');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="academy.php?v=new" style="width:75%;">Agregar</a>
                            </li>
                        <?php endif;?>
						<?php if ($H_USER->has_capability('academy/view')):	?>
                            <li>
                                <a href="#" onclick="window.open('academy.php?v=view&id=1');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="academy.php?v=view&id=1" style="width:75%;">Fundación Proydesa</a>
                            </li>
                        <?php endif;?>
                        <?php if ($H_USER->has_capability('academy/view')):	?>
                            <li>
                                <a href="#" onclick="window.open('academy.php?v=reportes');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="academy.php?v=reportes" style="width:75%;">Contactos de la Red</a>
                            </li>
                        <?php endif;?>
                        <?php if ($H_USER->has_capability('llamados_a_la_red/all')):	?>
                            <li>
                                <a href="#" onclick="window.open('contactos_de_la_red.php?v=view');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="contactos_de_la_red.php?v=view" style="width:75%;">Llamados a la Red</a>
                            </li>
                        <?php endif;?>
                        <?php if ($H_USER->has_capability('academy/view')):	?>
                            <li>
                                <a href="#" onclick="window.open('academy.php?v=view&id=200');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="academy.php?v=view&id=200" style="width:75%;">Proydesa ITC (Capacitación de instructores)</a>
                            </li>
                        <?php endif;?>
					</ul>
				</li>
				<li><a href="#" style="font-weight:bold">Convenios</a>
                    <ul>
						<?php if ($H_USER->has_capability('convenios/view')):	?>
                            <li>
                                <a href="#" onclick="window.open('convenios.php?v=list');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="convenios.php?v=list" style="width:75%;">Listar</a>
                                </li>
                            <li>
                                <a href="#" onclick="window.open('convenios.php?v=new');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="convenios.php?v=new" style="width:75%;">Agregar</a>
                                </li>
                            <li>
                                <a href="#" onclick="window.open('academy.php?v=agregar_convenio');" style="width:10%;">
                                <span class="ui-icon ui-icon-newwin" style="float:left;"></span>
                                </a>
                                <a href="academy.php?v=agregar_convenio" style="width:75%;">Asociar</a>
                                </li>
                        <?php endif;?>
					</ul>
				</li>
			</ul>
		</div>
		<?php endif;?>
	<?php if ($H_USER->has_capability('activity/view_hd')):	?>
	<a tabindex="7" href="#menu-hd" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-hd"><span class="ui-icon ui-icon-triangle-1-s"></span>Help Desk</a>
	<div id="menu-hd" class="hidden">
		<ul>
			<?php if ($H_USER->has_capability('activity/view')):	?>
			<li>
				<a href="#" onclick="window.open('hd.php');" style="width:10%;">
				<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a href="hd.php" style="width:75%;">Panel del Help Desk</a>
			</li>
			<?php endif;?>
			<?php if ($H_USER->has_capability('activity/new')):	?>
			<li>
				<a href="#" onclick="window.open('hd.php?v=nuevo');" style="width:10%;">
				<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a href="hd.php?v=nuevo" style="width:75%;">Registrar un incidente nuevo</a>
			</li>
			<?php endif;?>
			<?php if ($H_USER->has_capability('activity/view')):	?>
			<li>
				<a href="#" onclick="window.open('hd.php?v=view');" style="width:10%;">
				<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
			<a href="hd.php?v=view" style="width:75%;">Ver lista de incidentes</a></li>
			<?php endif;?>
			<?php if ($H_USER->has_capability('activity/representante')):	?>
				<li>
					<a href="#" onclick="window.open('reportes_hd.php?v=reporte');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
					</a>
					<a href="reportes_hd.php?v=reporte" style="width:75%;">Reporte</a>
				</li>
			<?php endif;?>
			<?php if ($H_USER->has_capability('hd/soporteenlinea')):	?>
				<li>
					<a href="#" onclick="window.open('hd.php?v=soporte');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
					</a>
					<a href="hd.php?v=soporte" style="width:75%;">Soporte en linea</a>
				</li>
			<?php endif;?>
			<?php if ($H_USER->has_capability('hd/config')):	?>
			<li><p><b>Administración</b></p></li>
			<li>
				<a href="#" onclick="window.open('hd.php?v=new');" style="width:10%;">
				<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
			<a href="hd.php?v=categories" style="width:75%;">Categorias</a></li>
			<?php endif;?>
		</ul>
	</div>
	<?php endif;?>
	<?php if ($H_USER->has_capability('reportes/view')):	?>
		<a tabindex="3" href="#menu-reportes" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-reportes"><span class="ui-icon ui-icon-triangle-1-s"></span>Reportes</a>
		<div id="menu-reportes" class="hidden">
			<ul>
				<li><p><b>Reportes</b></p></li>
				<?php if ($H_USER->has_capability('reportes/alum_acad')): ?>
					<li><a href="#">Alumnos por Academia de la red</a>
						<ul>
							<li>
								<a href="#" onclick="window.open('reportes.php?v=academycourse');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=academycourse" style="width:75%;">Tabla</a></li>
							<li>
								<a href="#" onclick="window.open('reportes.php?v=enrolados2');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=enrolados2" style="width:75%;">Listado</a></li>
						</ul>
					</li>
					<?php endif;?>
					<?php if ($H_USER->has_capability('reportes/asistencia')): ?>
				<li><a href="#">Asistencias</a>
					<ul> 
						<li>
							<a href="#" onclick="window.open('reportes_lms.php?v=asistencia');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="reportes_lms.php?v=asistencia" style="width:75%;">Asistencia</a>
						</li>
						<li>
							<a href="#" onclick="window.open('asistencia.php?v=view');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="asistencia.php?v=view" style="width:75%;">Instructores</a>
						</li>
						<li>
							<a href="#" onclick="window.open('reportes_lms.php?v=segAsistenciaParaInstructores');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="reportes_lms.php?v=segAsistenciaParaInstructores" style="width:75%;">Seguimiento de Asistencia (Instructores)</a>
						</li>
					</ul>
				</li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/deudores')): ?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=deudores');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=deudores" style="width:75%;">Inscriptos - Deudores</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/ekits')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=ekits');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=ekits" style="width:75%;">Ekits</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/listados')):	?>
					<li>
						<a href="#" onclick="window.open('reportes_lms.php?v=listados_difusion');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes_lms.php?v=listados_difusion" style="width:75%;">Listados Difusión</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/inscriptos')):	?>
					<li><a href="#">Inscriptos</a>
						<ul>
							<!--<li>
								<a href="#" onclick="window.open('reportes.php?v=inscriptos&t=basico');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=inscriptos&t=basico" style="width:75%;">B&aacute;sico</a></li>-->
							<li>
								<a href="#" onclick="window.open('reportes.php?v=inscriptos&t=detalle');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=inscriptos&t=detalle" style="width:75%;">Inscriptos detallado</a></li>
							<li>
								<a href="#" onclick="window.open('reportes.php?v=inscriptos_dia');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=inscriptos_dia" style="width:75%;">Informe de Marketing (Inscriptos por día)</a></li>
							<li>
								<a href="#" onclick="window.open('reportes.php?v=inscriptos_mensual');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=inscriptos_mensual" style="width:75%;">Informe de Marketing (Inscriptos por mes)</a></li>
							<li>
								<a href="#" onclick="window.open('reportes.php?v=inscriptos_periodo');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=inscriptos_periodo" style="width:75%;">Comparativa de inscriptos</a></li>
							<li>
								<a href="#" onclick="window.open('reportes.php?v=inscriptos_periodo_calendario');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
							<a href="reportes.php?v=inscriptos_periodo_calendario" style="width:75%;">Inscriptos por rango de fechas</a></li>
						</ul>
					</li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/bajas')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=bajas');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=bajas" style="width:75%;">Bajas</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/comp_pend')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=comp_pendientes');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=comp_pendientes" style="width:75%;">Comprobantes Pendientes</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/facturacion')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=facturacion');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=facturacion" style="width:75%;">Facturaci&oacute;n</a></li>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=facturacion_ekits');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=facturacion_ekits" style="width:75%;">Facturaci&oacute;n de eKits</a></li>
				<?php endif;?>

				<?php if ($H_USER->has_capability('reportes/iva-ventas')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=iva_ventas');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=iva_ventas" style="width:75%;">IVA - Ventas</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/donaciones')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=donaciones');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=donaciones" style="width:75%;">Donaciones</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/becados')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=becados');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=becados" style="width:75%;">Becados</a></li>
				<?php endif;?>
				<?php if ($H_USER->has_capability('reportes/empresas')):	?>
					<li>
						<a href="#" onclick="window.open('reportes.php?v=empresas');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
						</a>
					<a href="reportes.php?v=empresas" style="width:75%;">Empresas</a></li>
				<?php endif;?>
				<li><p><b>Herramientas</b></p></li>
					<li><a href="#">Pagomiscuentas</a>
						<ul>
							<?php if ($H_USER->has_capability('pagomiscuentas/descarga')):	?>
							<li>
								<a href="#" onclick="window.open('admin.php?v=pagomiscuentas');" style="width:10%;">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<a style="width:75%;"  href="admin.php?v=pagomiscuentas">Descarga pagomiscuentas</a>
							</li>
							<?php if ($H_USER->has_capability('pagomiscuentas/informe')):	?>
							<?php endif;?>
							<li>
								<a href="#" onclick="window.open('admin.php?v=leerpagomiscuentas');" style="width:10%;">
									<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
								</a>
								<a style="width:75%;"  href="admin.php?v=leerpagomiscuentas">Informe pagomiscuentas</a>
							</li>
							<?php endif;?>
						</ul>
					</li>
					<!--<li><a href="#"><b>Otros reportes</b></a>
					<ul>
					<?php if ($H_USER->has_capability('reportes/estadisticas')):	?>
						<li><p><b>Estadisticas</b></p></li>
						<li>
							<a href="#" onclick="window.open('http://dev.www.proydesa.org/estadisticashd/estadisticas.asp');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="http://dev.www.proydesa.org/estadisticashd/estadisticas.asp" style="width:75%;" target="_blank">Estado de Situación de Incidentes en el Help Desk</a></li>
					<?php endif;?>
						<li><p><b>Otros informes</b></p></li>
						<li>
							<a href="#" onclick="window.open('reportes.php?v=errores_sincro');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="reportes.php?v=errores_sincro" style="width:75%;">Errores de sincronizaci&oacute;n de usuarios</a></li>
						<li>
							<a href="#" onclick="window.open('reportes.php?v=errores_sincro_comi');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="reportes.php?v=errores_sincro_comi" style="width:75%;">Errores de sincronizaci&oacute;n de comisiones</a></li>
						<li>
							<a href="#" onclick="window.open('reportes.php?v=charlas');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="reportes.php?v=charlas" style="width:75%;">Charlas</a></li>
					<?php if ($H_USER->has_capability('reportes/proyectcrm')):	?>
						<li>
							<a href="#" onclick="window.open('reportes.php?v=proyect_crm');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
						<a href="reportes.php?v=proyect_crm" style="width:75%;">Proyect CRM</a></li>
					<?php endif; ?>
					</ul></li>-->
			</ul>
		</div>
	<?php endif;?>
<!--
	<?php if ($H_USER->has_capability('capacitacion/view')):	?>
		<a tabindex="1" href="#menu-capacitacion" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-capacitacion"><span class="ui-icon ui-icon-triangle-1-s"></span>Cap. Instructores</a>
		<div id="menu-capacitacion" class="hidden">
			<ul>
				<li><p><b>Listados</b></p></li>
				<li>
				<a href="#" onclick="window.open('capacitacion.php?v=instructores');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a  style="width:75%;" href="capacitacion.php?v=instructores">Instructores</a></li>
				<li>
				<a href="#" onclick="window.open('capacitacion.php?v=comisiones');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a style="width:75%;" href="capacitacion.php?v=comisiones">Comisiones</a>
				</li>
				<li><hr/></li>
				<li>
				<a href="#" onclick="window.open('capacitacion.php?v=contactos');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a style="width:75%;" href="capacitacion.php?v=contactos">MC por carrera</a></li>
			</ul>
		</div>
	<?php endif;?>
-->
	<?php if ($H_USER->has_capability('admin/view')):	?>
		<a tabindex="4" href="#menu-configuraciones" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-configuraciones"><span class="ui-icon ui-icon-triangle-1-s"></span>Administraci&oacute;n</a>
		<div id="menu-configuraciones" class="hidden">
			<ul>
				<li>
				<a href="#" onclick="window.open('admin.php?v=configuracion');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a style="width:75%;" href="admin.php?v=configuracion">Configuraci&oacute;n general</a></li>
				<li>
				<a href="#" onclick="window.open('admin.php?v=roles');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a style="width:75%;"  href="admin.php?v=roles">Roles y permisos</a></li>
				<li>
				<a href="#" onclick="window.open('sincronizadores.php?v=view');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a style="width:75%;"  href="sincronizadores.php?v=view">Sincronizadores</a></li>
				<li><a href="#">Cursos</a>
					<ul>
						<li>
							<a href="#" onclick="window.open('admin.php?v=cuotas');" style="width:10%;">
							<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<a style="width:75%;"  href="admin.php?v=cuotas">Cuotas</a></li>
						<li>
							<a href="#" onclick="window.open('admin.php?v=libros');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<a style="width:75%;" href="admin.php?v=libros">Libros</a></li>
						<li>
							<a href="#" onclick="window.open('admin.php?v=horarios');" style="width:10%;">
								<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
							</a>
							<a style="width:75%;" href="admin.php?v=horarios">Horarios</a></li>
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
				<li><a href="admin.php?v=bancos">Bancos</a></li>
			</ul>
		</div>
	<?php endif;?>
	<a tabindex="6" href="#menu-cuenta" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-cuenta"><span class="ui-icon ui-icon-triangle-1-s"></span>Cuenta</a>
	<div id="menu-cuenta" class="hidden">
		<ul>
			<center>
				<div class="portlet-header ui-widget-header ui-corner-all"><b><?= $H_USER->getName();?></b></div>
				<a href="index.php"><img src="https://www.proydesa.org/lms_new/user/pix.php/<?= $H_USER->get_property('id');?>/f1.jpg"/></a></center>
			<li>
				<a href="#" onclick="window.open('contactos.php?v=view&id=<?= $H_USER->get_property('id');?>');" style="width:10%;">
					<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
				</a>
				<a href="contactos.php?v=view&id=<?= $H_USER->get_property('id');?>" style="width:75%;">Perfil</a></li>
			<?php if ($H_USER->has_capability('user/rendicion/rendir')):	?>
				<li>
					<a href="#" onclick="window.open('users.php?v=rendicion');" style="width:10%;">
						<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
					</a>
				<a href="users.php?v=rendicion"  style="width:75%;">Rendici&oacute;n</a></li>
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
	<a tabindex="7" href="#menu-enlaces" class="fg-button fg-button-icon-right ui-widget ui-state-default ui-corner-all" id="opcion-enlaces"><span class="ui-icon ui-icon-triangle-1-s"></span>Enlaces</a>
	<div id="menu-enlaces" class="hidden">
		<ul>
			<li><p><b>Sitios Públicos</b></p></li>
			<li><a href="http://www.proydesa.org/portal" target="_blank">Portal</a></li>
			<li><a href="http://rrhh.proydesa.org/" target="_blank">Web Site Laboral</a></li>
			<li><a href="http://estudiemos.proydesa.org/" target="_blank">Estudiemos .org</a></li>
			<li><p><b>Redes Sociales</b></p></li>
					<li><a href="http://www.facebook.com/pages/Buenos-Aires-Argentina/Fundacion-Proydesa/329367770317?ref=ts" target="_blank">Facebook</a></li>
					<li><a href="http://www.flickr.com/photos/48765985@N08/sets/" target="_blank">Flickr</a></li>
					<li><a href="http://www.slideshare.net/proydesa" target="_blank">Slideshare</a></li>
					<li><a href="http://twitter.com/proydesa" target="_blank">Twitter</a></li>
					<li><a href="http://www.youtube.com/user/fundacionproydesa" target="_blank">Youtube</a></li>
					<li><a href="http://fundacionproydesa.blogspot.com" target="_blank">Blogspot</a></li>
					<li><a href="http://www.linkedin.com/groups?home=&gid=2911029&trk=anet_ug_hm" target="_blank">Linkedin</a></li>
					<li><a href="http://www.plaxo.com/groups/profile/231928512564" target="_blank">Plaxo</a></li>
			<li><p><b>Internos</b></p></li>
					<li><a href="http://office365.proydesa.org" target="_blank">WebMail Proydesa</a></li>
					<li><a href="http://192.168.0.9/" target="_blank">Intranet Proydesa</a></li>
					<li><a href="http://dev.proycore.proydesa.org/lms" target="_blank">LMS Desarrollo</a></li>
					<!--<li><a href="http://dev.ciscorrhh2.proydesa.org/gradebooks/gradebooks.asp" target="_blank">Gradebooks Hist&oacute;ricos</a></li>-->
		</ul>
	</div>

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
	<div class="ui-widget" align="left" style="float:left;">
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
		<span>
			<span> - <b>Periodo: </b></span>
			<select  name="sessperiodo" id="periodos" style="font-size: 10px; height: 19px;"
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
<div id="message" align="center" style="height:3px;">
	<!--<div class="men ui-widget ui-state-highlight" align="left" style="margin-top:10px; padding:10px; width:50%">
		<span class="ui-icon ui-icon-alert" style="float: left; margin-right: .3em;"></span>
		<strong>Alert:</strong>	<?= $H_USER->get_property('firstname')?> Te falta elegir un tipo de pago.
	</div>
	<script type='text/javascript' language='javascript'>
		var t1=setTimeout("$('div.men').remove();", 3000);
	</script>-->
</div>
