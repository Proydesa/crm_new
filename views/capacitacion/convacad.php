<SCRIPT TYPE="text/javascript">
//alan//
	$(document).ready(function(){
   $("#seleccon").click(function(event){   		 
		$('.convenio').each( function() {			
			$(this).attr("checked","checked");
			} );
		});
	});
	
	$(document).ready(function(){
		 $("#unseleccon").click(function(event){   		 
			$('.convenio').each( function() {			
				$(this).removeAttr("checked","checked");
				} );
			});
		});
	
	$(document).ready(function(){
   $("#selecacad").click(function(event){   		 
		$('.academia').each( function() {			
			$(this).attr("checked","checked");
			} );
		});
	});
	
	$(document).ready(function(){
   $("#unselecacad").click(function(event){   		 
		$('.academia').each( function() {			
			$(this).removeAttr("checked","checked");
			} );
		});
	});
	//alan//
</SCRIPT>
<!-- alan -->
<div>
	<form action="capacitacion.php?v=convacad" method="post">	
		<div class="column" style="width:50%">		
			<div class="portlet">
				<div class="portlet-header">Convenios</div>
					<div class="portlet-content" style="overflow:auto;max-height:300px;">	
						<ul class="noBullet">	
							<?php foreach($convenios as $convenio):?>
								<li><input type="checkbox" name="convenios[]" class="convenio" value="<?= $convenio['id'];?>" <?php if(in_array($convenio['id'],$convsel)) echo "checked"; ?> /><label for="convenios[]"><?= $convenio['name']?></label></li>
							<?php endforeach;?>	
						</ul>	
					</div>	
			</div>
			<span id="seleccon" class="button">Todas</span> |
			<span id="unseleccon" type="desmarcar" class="button">Ninguna</span>
			<input type="submit" class="button" value="Consultar" />	
		</div>	
	</form>	
	<form action="capacitacion.php?v=convacad" method="post">	
		<div class="column" style="width:50%">		
			<div class="portlet">
				<div class="portlet-header">Academias</div>
					<div class="portlet-content" style="overflow:auto;max-height:300px;">	
						<ul class="noBullet">	
							<?php foreach($academias as $academia):?>
								<li><input type="checkbox" name="academias[]" class="academia" value="<?= $academia['id'];?>" <?php if(in_array($academia['id'],$acadsel)) echo "checked"; ?> /><label for="academias[]"><?= $academia['name']?></label></li>
							<?php endforeach;?>	
						</ul>	
					</div>	
			</div>
			<span id="selecacad" class="button">Todas</span> |
			<span id="unselecacad" type="desmarcar" class="button">Ninguna</span>
			<input type="submit" class="button" value="Consultar" />	
		</div>	
	</form>
</div>
<div>
	<div class="column" style="width:100%">	
		<div class="portlet">
			<div class="portlet-header">Academias por convenio</div>
				<div class="portlet-content" style="overflow:auto;max-height:500px;">		
					<table  class="ui-widget" style="width:100%">
						<tr style="height: 20px;" class="ui-widget-header">	
							<?php $cols2=sizeof($conveniosolos); ?>
							<th COLSPAN=<?= $cols2; ?>>Convenios</th>		
						</tr>	
						<tr>
							<?php foreach($conveniosolos as $convenio): ?>
								<td><?= $LMS->GetField('mdl_convenios','name',$convenio);?></td>
								<?php endforeach; ?>	
						</tr>
						<tr style="height: 20px;" class="ui-widget-header">	
							<th COLSPAN=<?= $cols2; ?>>Academias</th>	
						</tr>
						<tr>
							<?php foreach($conveniosolos as $convenio): ?>
								<td valign="top">
									<table  class="ui-widget">	
										<?php if($academy!=""){ ?>
											<?php foreach($academy as $academia => $valor): ?>
												<?php foreach($valor as $das): ?>
													<?php if($convenio==$das['id']){ ?>
														<tr>
															<td  VALIGN="top"><li><?= $das['name'];?></li></td> 
														</tr>
													<?php } ?>	
												<?php endforeach; ?>	
											<?php endforeach; ?>
										<?php } ?>		
									</table>
								</td>	
							<?php endforeach; ?>				
						</tr>	
					</table>	
				</div>
		</div>						
	</div>
	<div class="column" style="width:100%">	
		<div class="portlet">
			<div class="portlet-header">Convenios por academia</div>
				<div class="portlet-content" style="overflow:auto;max-height:500px;">		
					<table  class="ui-widget" style="width:100%">
						<tr style="height: 20px;" class="ui-widget-header">	
							<?php $cols1=sizeof($academiasolas); ?>
							<th COLSPAN=<?= $cols1; ?>>Academias</th>		
						</tr>	
						<tr>
							<?php foreach($academiasolas as $academia): ?>
								<td><?= $LMS->GetField('mdl_proy_academy','name',$academia);?></td>
								<?php endforeach; ?>	
						</tr>
						<tr style="height: 20px;" class="ui-widget-header">	
							<th COLSPAN=<?= $cols1; ?>>Convenios</th>	
						</tr>
						<tr>							
							<?php foreach($academiasolas as $academia): ?>
							
								<td valign="top">
									<table  class="ui-widget">	
										<?php if($conveny!=""){ ?>
											<?php foreach($conveny as $convenio => $valor): ?>
												<?php foreach($valor as $sas): ?>
													<?php if($academia==$sas['id']){ ?>
														<tr>
															<td><li><?= $sas['name'];?></li></td> 
														</tr>
													<?php } ?>	
												<?php endforeach; ?>	
											<?php endforeach; ?>
										<?php } ?>		
									</table>
								</td>	
							<?php endforeach; ?>				
						</tr>	
					</table>	
				</div>
		</div>						
	</div>
</div>	
<!-- alan -->							