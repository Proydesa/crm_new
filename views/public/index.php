<script type="text/javascript">

	$(function () {

		$('.activity').click(function(){ $(this).next('tr').toggle();});	

	});		

</script>	
<br/>
<div class="column" style="width:20%">
	<div class="portlet">
		<div class="portlet-header"><?= $H_USER->get_property('username');?></div>
		<div class="portlet-content" >
			<br/>
			<br/>
			<center><img src="http://www.proydesa.org/lms/user/pix.php/<?= $H_USER->get_property('id');?>/f1.jpg"/></center>
			<br/>
			<br/>
		</div>
	</div>
	<div class="portlet">
		<div class="portlet-header">Enlaces externos</div>
		<div class="portlet-content" >
			<ul>
				<li> Sitios p&uacute;blicos
					<ul>
						<li><a href="http://www.proydesa.org/portal" target="_blank">Portal</a></li>
						<li><a href="http://rrhh.proydesa.org/" target="_blank">Web Site Laboral</a></li>
						<li><a href="http://estudiemos.proydesa.org/" target="_blank">Estudiemos .org</a></li>
					</ul>
				</li>
				<li>Redes sociales
					<ul>
						<li><a href="http://www.facebook.com/pages/Buenos-Aires-Argentina/Fundacion-Proydesa/329367770317?ref=ts" target="_blank">Facebook</a></li>
						<li><a href="http://www.flickr.com/photos/48765985@N08/sets/" target="_blank">Flickr</a></li>
						<li><a href="http://www.slideshare.net/proydesa" target="_blank">Slideshare</a></li>
						<li><a href="http://twitter.com/proydesa" target="_blank">Twitter</a></li>
						<li><a href="http://www.youtube.com/user/fundacionproydesa" target="_blank">Youtube</a></li>
						<li><a href="http://fundacionproydesa.blogspot.com" target="_blank">Blogspot</a></li>
						<li><a href="http://www.linkedin.com/groups?home=&gid=2911029&trk=anet_ug_hm" target="_blank">Linkedin</a></li>
						<li><a href="http://www.plaxo.com/groups/profile/231928512564" target="_blank">Plaxo</a></li>
					</ul>
				</li>
				<li>Academias
					<ul>
						<li><a href="" target="_blank">Listado</a></li>
					</ul>
				</li>

			</ul>
		</div>
	</div>
</div>
<div class="column" style="width:80%">
		<?php if ($cursos): ?>
			<div class="portlet">
				<div class="portlet-header">Certificaciones</div>
				<div class="portlet-content"  style="overflow: auto; max-height: 300px;">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content">
							<?php foreach($cursos as $curso):?>
									<tr style="height: 20px;">
										<!--<td class="ui-widget-content press" ondblclick="window.location.href='courses.php?v=view&id=<?= $curso['id'];?>';">
											<a href="courses.php?v=view&id=<?= $curso['id'];?>" target="_blank">
												<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
											</a><b><?= $curso['course'];?></b>
										</td>-->
										<td class="ui-widget-content"><?= $curso['model'];?></td>
										<td class="ui-widget-content" align="center">
											<?= $curso['periodo'];?>
										</td>
									</tr>
									<?php if($certificados[$curso['id']]): ?>
										<tr style="height: 20px;">
											<td colspan="2" class="ui-widget-content" align="right">
												Certicado emitido el <?= date("d/m/Y",$certificados[$curso['id']]['timecreate']);?> por  <?= $certificados[$curso['id']]['usercreator'];?> 
											</td>
										</tr>
									<?php endif; ?>
							<?php endforeach;?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>

		<?php if ($cursos_ins): ?>	
			<div class="portlet">
				<div class="portlet-header">Cursos: Instructor</div>
				<div class="portlet-content"  style="overflow: auto; max-height: 300px;">
					<table class="ui-widget" align="center" style="width:100%;">
						<tbody class="ui-widget-content">
							<?php foreach($cursos_ins as $curso):?>
									<tr style="height: 20px;">
										<td class="press" ondblclick="window.location.href='courses.php?v=view&id=<?= $curso['id']; ?>';">
											<a href="courses.php?v=view&id=<?= $curso['id']; ?>" target="_blank">
												<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
											</a><b><?= $curso['course'];?></b>
										</td>
										<td><?= $curso['model']; ?></td>
										<td><?= $curso['rol']; ?></td>
									</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?></div>

