<form action="reportes.php?v=becados" name="form" method="post">	
	<div class="column" style="width:15%">		
		<div class="portlet">
		<div class="portlet-header">Periodos</div>
			<div class="portlet-content" style="overflow:auto;max-height:300px;">	
				<ul class="noBullet">	
					<select  name="periodos" id="periodos">
						<?php foreach($periodos as $periodo):?>									
							<li><option value="<?= $periodo['periodo'];?>" <?php if($periodo['periodo']==$persel) echo 'selected="selected"'; ?>><?= $periodo['periodo'];?></option></li>
						<?php endforeach;?>	
					</select>	
				</ul>	
			</div>	
			<div class="portlet-content">
				<center>
					<input type="submit" name="boton"  style="height: 25px; width: 75px; font-size:10px" class="button"  value="Consultar" />
				</center>	
			</div>
		</div>
	</div>
	<?php if($boton):?>	
		<?php 
		foreach($becados as $becado):
			$cursos[]=$becado['comisionid'];
			$idcursos=implode(",",$cursos);		
		endforeach;

		$session=$LMS->GetOne("SELECT COUNT(ats.id) as suma, ats.courseid FROM mdl_attendance_sessions ats	
														INNER JOIN mdl_course cr ON cr.id=ats.courseid													
														WHERE ats.courseid IN ({$idcursos})
														AND cr.periodo={$persel}
														GROUP BY ats.courseid	
														ORDER BY suma DESC
																	;");

		$parciales=$LMS->GetOne("SELECT COUNT(gi.id) as suma, gi.courseid FROM mdl_grade_items gi
														INNER JOIN mdl_course cr ON cr.id=gi.courseid												
														WHERE gi.courseid IN ({$idcursos})
														AND cr.periodo={$persel}
														GROUP BY gi.courseid	
														ORDER BY suma DESC
																	;");
		$parciales2=$parciales;															
										
		$sessions=$session+1;																
		?>	
		
		<?php $primero=true;?>
		<div class="column" style="width:140%">
			<div class="portlet">		
				<div class="portlet-content" >	
				<?php $id=0;?>	
					<table  class="ui-widget">	
						<?php foreach($porcentajes as $por):?>	
							<?php if($primero==false):?>
								<tr>
								<td COLSPAN=<?= $sessions+$parciales+6;?>>
								&nbsp;
								</td>
								</tr>
								<tr>
								<td COLSPAN=<?= $sessions+$parciales+6;?>>
								&nbsp;
								</td>
								</tr>
							<?php endif;?>
							<?php $primero=false;?>
							<?php $bool=false;?>					
							<?php $boolclases=false;?>
							<?php $mesesboolean=false;?>						
							<tr>
								<td COLSPAN=6 class="ui-widget-header"  >Becado <?= $por['becado'];?>% </td>
								<td COLSPAN=<?= $sessions-1;?> align="center" class="ui-widget-header" >Asistencias</td>
								<td COLSPAN=<?= $parciales;?> align="center" class="ui-widget-header" >Ex&aacute;menes</td>
							</tr>				
							<?php $course="";?>							
							<tr>
							<?php foreach($becados as $becado):?>								
								<?php if($becado['becado']==$por['becado']):?>
									<?php if(($becado['courseid']==$courseid)&&($por['becado']!=$porcentaje)):?>
										<?php $porcentaje=$por['becado'];?>
										<?php $courseid=$becado['courseid'];?>
										<?php $course=$LMS->GetField('mdl_course', 'shortname', $becado['courseid']);?>
										<tr>
											<td class="ui-widget-header"  align=center COLSPAN=6><?= $course;?></td>
											<?php if($boolclases==true):?>
												<?php for($i=1;$i<$sessions;$i++):?>
													<td class="ui-widget-header"  align=center><?= $i;?></td>
												<?php endfor;?>		
											<?php endif;?>									
											<?php foreach($becados as $becadomes):													
												$idcursosmeses[]=$becadomes['comisionid'];
											endforeach;
											$idcursosmeses2=implode(",",$idcursosmeses);
													
											$cursos=$LMS->GetAll("SELECT ats.courseid FROM mdl_attendance_sessions ats	
																					INNER JOIN mdl_course cr ON cr.id=ats.courseid													
																					WHERE ats.courseid IN ({$idcursosmeses2})
																					AND cr.periodo={$persel}
																					GROUP BY ats.courseid	
														;");			
											foreach($cursos as $curso):	
												$cursosmeses[]=$curso['courseid'];	
											endforeach;
											$cursomes=implode(",",$cursosmeses);	
											$clasesmeses=$LMS->GetAll("SELECT ats.sessdate FROM mdl_attendance_sessions ats	
																						INNER JOIN mdl_course cr ON cr.id=ats.courseid													
																						WHERE ats.courseid IN ({$cursomes})
																						AND cr.periodo={$persel} ;");		
											foreach($clasesmeses as $clasemes){
												$contador++;
												$month = date('m', $clasemes['sessdate']);	 													
												switch ($month) {
													case 1:
														$enero++;
													break;
													case 2:
														$febrero++;
													break;
													case 3:
														$marzo++;
													break;
													case 4:
														$abril++;
													break;
													case 5:
														$mayo++;
													break;
													case 6:
														$junio++;
													break;
													case 7:
														$julio++;
													break;
													case 8:
														$agosto++;
													break;
													case 9:
														$septiembre++;
													break;
													case 10:
														$octubre++;
													break;
													case 11:
														$noviembre++;
													break;
													case 12:
														$diciembre++;
													break;
												}							
											}	?>											
											<?php if($mesesboolean==false):?>											
												<?php if($enero>0):?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $enero;?>>Enero</td>														
												<?php endif;?>	
												<?php if($febrero>0):?>	
													<td class="ui-widget-header"  align=center COLSPAN=<?= $febrero;?>>Febrero</td>													
												<?php endif;?>	
												<?php if($marzo>0):?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $marzo;?>>Marzo</td>													
												<?php endif;?>	
												<?php if($abril>0):?>	
													<td class="ui-widget-header"  align=center COLSPAN=<?= $abril;?>>Abril</td>														
												<?php endif;?>	
												<?php if($mayo>0):?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $mayo;?>>Mayo</td>
												<?php endif;?>	
												<?php if($junio>0):?>	
													<td class="ui-widget-header"  align=center COLSPAN=<?= $junio;?>>Junio</td>														
												<?php endif;?>	
												<?php if($julio>0):?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $julio;?>>Julio</td>														
												<?php endif;?>	
												<?php if($agosto>0):?>	
													<td class="ui-widget-header"  align=center COLSPAN=<?= $agosto;?>>Agosto</td>														
												<?php endif;?>	
												<?php if($septiembre>0):?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $septiembre;?>>Septiembre</td>														
												<?php endif;?>	
												<?php if($octubre>0):?>	
													<td class="ui-widget-header"  align=center COLSPAN=<?= $octubre;?>>Octubre</td>														
												<?php endif;?>	
												<?php if($noviembre>0):?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $noviembre;?>>Noviembre</td>														
												<?php endif;?>	
												<?php if($diciembre>0):?>	
													<td class="ui-widget-header"  align=center COLSPAN=<?= $diciembre;?>>Diciembre</td>														
												<?php endif;?>													
												<?php $mesesboolean=true;?>
											<?php endif;?>
											<?php for($q=0;$q<$sessions-($contador+1);$q++):?>
												<td><?= $contador;?></td>
											<?php endfor;?>	
											<?php 	
											$contador=0;
											$julio=0;
											$diciembre=0;
											$enero=0;
											$febrero=0;
											$marzo=0;
											$abril=0;
											$mayo=0;
											$junio=0;
											$julio=0;
											$agosto=0;
											$septiembre=0;
											$octubre=0;
											$noviembre=0;
											$diciembre=0;?>														
											<?php   
											$parcialcomision=$LMS->GetOne("SELECT COUNT(gi.id) as suma, gi.courseid FROM mdl_grade_items gi
																											INNER JOIN mdl_course cr ON cr.id=gi.courseid
																											WHERE gi.courseid = {$becado['comisionid']}
																											GROUP BY gi.courseid
																											ORDER BY suma DESC;");?>
											<?php for($h=1;$h<$parcialcomision+1;$h++):?>
													<td  class="ui-widget-header"  align=center><?= $h;?></td>
													<?php $parciales2--;?>
											<?php endfor;?>
											<?php for($y=1;$y<$parciales2+1;$y++):?>
												<td class="ui-widget-header"  align=center>&nbsp;</td>													
											<?php endfor;?>
											<?php $parciales2=$parciales;?>
										</tr> 									
											<?php if($bool==false):?>
												<tr>
													<td class="ui-widget-header" >Alumno</td>
													<td class="ui-widget-header" >DNI</td>
													<td class="ui-widget-header" >Comisi&oacute;n</td>
													<td class="ui-widget-header" >Instructor</td>
													<td class="ui-widget-header" >Entrevista</td>
													<td class="ui-widget-header" >Pasant&iacute;a</td>
													<?php $bool=true;?>
													<?php if($boolclases==false):?>											
														<?php for($i=1;$i<$sessions;$i++):?>
															<td class="ui-widget-header"  align=center><?= $i;?></td>
														<?php endfor;?>	
														<?php $boolclases=true;?>
													<?php endif;?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $parciales;?>></td>
												</tr>
											<?php endif;?>
										<?php endif;?>
										<?php if(($becado['courseid']!=$courseid)):?>
											<?php $porcentaje=$por['becado'];?>
											<?php $courseid=$becado['courseid'];?>
											<?php $course=$LMS->GetField('mdl_course', 'shortname', $becado['courseid']);?>
											<tr>
												<td class="ui-widget-header"  align=center COLSPAN=6><?= $course;?></td>
												<?php if($boolclases==true):?>
													<?php for($i=1;$i<$sessions;$i++):?>
														<td class="ui-widget-header"  align=center><?= $i;?></td>
													<?php endfor;?>		
												<?php endif;?>										
												<?php foreach($becados as $becadomes):													
													$idcursosmeses[]=$becadomes['comisionid'];
												endforeach;
												$idcursosmeses2=implode(",",$idcursosmeses);
														
												$cursos=$LMS->GetAll("SELECT ats.courseid FROM mdl_attendance_sessions ats	
																		INNER JOIN mdl_course cr ON cr.id=ats.courseid													
																		WHERE ats.courseid IN ({$idcursosmeses2})
																		AND cr.periodo={$persel}
																		GROUP BY ats.courseid;");	
																		
												foreach($cursos as $curso):	
													$cursosmeses[]=$curso['courseid'];	
												endforeach;
												$cursomes=implode(",",$cursosmeses);	
												$clasesmeses=$LMS->GetAll("SELECT ats.sessdate FROM mdl_attendance_sessions ats	
																						INNER JOIN mdl_course cr ON cr.id=ats.courseid													
																						WHERE ats.courseid IN ({$cursomes})
																						AND cr.periodo={$persel};");																										
																																
												foreach($clasesmeses as $clasemes){
													$contador++;
													$month = date('m', $clasemes['sessdate']);	 													
													switch ($month) {
														case 1:
															$enero++;
														break;
														case 2:
															$febrero++;
														break;
														case 3:
															$marzo++;
														break;
														case 4:
															$abril++;
														break;
														case 5:
															$mayo++;
														break;
														case 6:
															$junio++;
														break;
														case 7:
															$julio++;
														break;
														case 8:
															$agosto++;
														break;
														case 9:
															$septiembre++;
														break;
														case 10:
															$octubre++;
														break;
														case 11:
															$noviembre++;
														break;
														case 12:
															$diciembre++;
														break;
													}							
												}	?> 	
												<?php if($mesesboolean==false):?>											
													<?php if($enero>0):?>
														<td class="ui-widget-header"  align=center COLSPAN=<?= $enero;?>>Enero</td>
													<?php endif;?>	
													<?php if($febrero>0):?>	
														<td class="ui-widget-header"  align=center COLSPAN=<?= $febrero;?>>Febrero</td>
													<?php endif;?>	
													<?php if($marzo>0):?>
														<td class="ui-widget-header"  align=center COLSPAN=<?= $marzo;?>>Marzo</td>
													<?php endif;?>	
													<?php if($abril>0):?>	
														<td class="ui-widget-header"  align=center COLSPAN=<?= $abril;?>>Abril</td>
													<?php endif;?>	
													<?php if($mayo>0):?>
														<td class="ui-widget-header"  align=center COLSPAN=<?= $mayo;?>>Mayo</td>
													<?php endif;?>	
													<?php if($junio>0):?>	
														<td class="ui-widget-header"  align=center COLSPAN=<?= $junio;?>>Junio</td>
													<?php endif;?>	
													<?php if($julio>0):?>
														<td class="ui-widget-header"  align=center COLSPAN=<?= $julio;?>>Julio</td>
													<?php endif;?>	
													<?php if($agosto>0):?>	
														<td class="ui-widget-header"  align=center COLSPAN=<?= $agosto;?>>Agosto</td>
													<?php endif;?>	
													<?php if($septiembre>0):?>
														<td class="ui-widget-header"  align=center COLSPAN=<?= $septiembre;?>>Septiembre</td>
													<?php endif;?>	
													<?php if($octubre>0):?>	
														<td class="ui-widget-header"  align=center COLSPAN=<?= $octubre;?>>Octubre</td>
													<?php endif;?>	
													<?php if($noviembre>0):?>
														<td class="ui-widget-header"  align=center COLSPAN=<?= $noviembre;?>>Noviembre</td>
													<?php endif;?>	
													<?php if($diciembre>0):?>	
														<td class="ui-widget-header"  align=center COLSPAN=<?= $diciembre;?>>Diciembre</td>
													<?php endif;?>													
												<?php $mesesboolean=true;?>
												<?php endif;?>	
												<?php for($q=0;$q<$sessions-($contador+1);$q++):?>
													<td><?= $contador;?></td>
												<?php endfor;?>	
												<?php 	$contador=0;
														$diciembre=0;
														$enero=0;
														$febrero=0;
														$marzo=0;
														$abril=0;
														$mayo=0;
														$junio=0;
														$julio=0;
														$agosto=0;
														$septiembre=0;
														$octubre=0;
														$noviembre=0;
														$diciembre=0;?>
												
												<?php $parcialcomision=$LMS->GetOne("SELECT COUNT(gi.id) as suma, gi.courseid FROM mdl_grade_items gi
																							INNER JOIN mdl_course cr ON cr.id=gi.courseid
																							WHERE gi.courseid = {$becado['comisionid']}
																							GROUP BY gi.courseid
																							ORDER BY suma DESC;");
																										
												?>	
												
												<?php for($h=1;$h<$parcialcomision+1;$h++):?>
													<td class="ui-widget-header"  align=center><?= $h;?></td>
													<?php $parciales2--;?>
												<?php endfor;?>
												<?php for($y=1;$y<$parciales2+1;$y++):?>
													<td class="ui-widget-header"  align=center>&nbsp;</td>													
												<?php endfor;?>
												<?php $parciales2=$parciales;?>
											</tr> 									
											<?php if($bool==false):?>
												<tr>
													<td class="ui-widget-header" >Alumno</td>
													<td class="ui-widget-header" >DNI</td>
													<td class="ui-widget-header" >Comisi&oacute;n</td>
													<td class="ui-widget-header" >Instructor</td>
													<td class="ui-widget-header" >Entrevista</td>
													<td class="ui-widget-header" >Pasant&iacute;a</td>
													<?php $bool=true;
													if($boolclases==false):											
														for($i=1;$i<$sessions;$i++):?>
															<td class="ui-widget-header"  align=center><?= $i;?></td>
														<?php endfor;	
														$boolclases=true;
													endif;?>
													<td class="ui-widget-header"  align=center COLSPAN=<?= $parciales;?>></td>
												</tr>
											<?php endif;?>
										<?php endif;?>
										<tr>
											<td><?php if($bool==false){ echo"falso";}?> <?= $LMS->GetField('mdl_user', 'firstname', $becado['userid'])." ".$LMS->GetField('mdl_user', 'lastname', $becado['userid']);?></td>
											<td><?= $LMS->GetField('mdl_user', 'username', $becado['userid']);?></td>
											<td><?= $LMS->GetField('mdl_course', 'fullname', $becado['comisionid']);?></td>
											<td><?= $LMS->GetOne("SELECT lastname AS inst
																								 FROM mdl_user u 
																								 INNER JOIN mdl_role_assignments ra ON u.id=ra.userid
																								 INNER JOIN mdl_context ctx ON ra.contextid=ctx.id
																								 WHERE ra.roleid=4 AND ctx.instanceid={$becado['comisionid']};");?></td>
											<td>&nbsp;&nbsp;</td>
											<td>&nbsp;&nbsp;</td>	
											<?php $sesiones=$LMS->GetAll("SELECT ats.id FROM mdl_attendance_sessions ats														
																		WHERE ats.courseid={$becado['comisionid']}														
																		ORDER BY ats.sessdate;");?>	
											<?php $j=$sessions;?>
											<?php foreach($sesiones as $sesion):
														$asistencia = $LMS->GetOne("SELECT al.status
																							FROM mdl_attendance_log al 
																							INNER JOIN mdl_attendance_sessions ass ON al.attsid=ass.id
																							WHERE ass.id={$sesion['id']} AND al.studentid={$becado['userid']};");?>
												<?php $j--;?>								
												<td align=center><?= $asistencia['status'];?></td>
											<?php endforeach;?>	
											<?php for($k=1;$k<$j;$k++):?>
														<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<?php endfor;?>	
											<?php $x=$parciales;?>	
											<?php $grades = $LMS->GetAll("SELECT gg.finalgrade FROM mdl_grade_grades gg
																							INNER JOIN mdl_grade_items gi ON gi.id=gg.itemid
																							INNER JOIN mdl_course c ON c.id=gi.courseid	
																							WHERE c.periodo !='0'
																							AND (gi.itemname like '%Final%'
																							OR gi.itemname like '%Parcial%')
																							AND c.periodo = {$persel}
																							AND gg.userid = {$becado['userid']}
																							AND gi.courseid = {$becado['comisionid']}");
											$cont=0;												
											?>
											<?php $w=0;?>																				
											<?php foreach($grades as $grade):?>							
												<td align=center><?= number_format($grade['finalgrade']);?></td>	
												<?php $guionexamen=true;?>
												<?php $x--;?>	
												<?php $w++;?>	
											<?php endforeach;?>
											<?php if($guionexamen==true):?>
												<?php for($n=1;$n<$parcialcomision+1-$w;$n++):?>
													<td align=center>- &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
													<?php $x--;?>
												<?php endfor;?>		
											<?php endif;?>	
											<?php for($y=1;$y<$x+1;$y++):?>
												<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
											<?php endfor;?>	
											<?php $guionexamen=false;?>
										</tr>
								 <?php endif;?>	
							<?php endforeach;?>	
							</tr>
						<?php endforeach;?>						
					</table>	
				</div>
			</div>
		</div>
	<?php endif;?>	
</form>		
