<div class="ui-widget">
	<div class="column-c" align="center" style="width:70%">
		<div class="portlet">
			<div class="portlet-header">Cambio de instructor de <?= $course['shortname'];?></div>
			<div class="portlet-content">
				<form action="courses.php?v=cambio_instructor" method="post" name="course" class="ui-widget">
	<table class="ui-widget">
        <tbody><tr>
          <td id="existingcell">
              <p><label for="removeselect">Instructores del curso</label></p>
			<select name="removeselect[]" id="removeselect" multiple="multiple" size="20" style="width:250px">
			    <option disabled="disabled">&nbsp;</option>
				<?php if ($instructores):?>
				<?php foreach($instructores as $ins):?>
					<option value="<?= $ins['id'];?>"><?= $ins['fullname'];?></option>
					<?php $enrolados[]=$ins['id'];?>
				<?php endforeach;?>			    
				<?php endif;?>
			</select>
         </td>
          <td id="buttonscell">
              <div id="addcontrols">
                  <input name="add" id="add" value="◄&nbsp;Agregar" title="Agregar" type="submit" class="button" style="width:100%">
              </div>
              <br/><br/>
              <div id="removecontrols">
                  <input name="remove" id="remove" value="Quitar&nbsp;►" title="Quitar" type="submit" class="button" style="width:100%">
              </div>
          </td>
          <td id="potentialcell">
              <p><label for="addselect">Instructores potenciales</label></p>
			<select name="addselect[]" id="addselect" multiple="multiple" size="20"  style="width:250px;">
			    <option disabled="disabled">&nbsp;</option>
				<?php if ($instructores_potenciales):?>
				<?php foreach($instructores_potenciales as $ins):?>
					<?php if(!in_array($ins['id'],$enrolados)):?>
						<option value="<?= $ins['id']?>"><?= $ins['fullname'];?></option>
					<?php endif;?>
				<?php endforeach;?>			    
				<?php endif;?>
			</select>
          </td>
        </tr>
      </tbody>					
  </table>
					<input type="hidden" name="id" value="<?= $course['id'];?>"/>
					</form>
				</div>
		</div>
	</div>
</div>
