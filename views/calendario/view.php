<div class="column-c">
	<div class="calendar">
		<p><small><a href="#" onclick="start_tutorial()" >(ver tutorial)</a></small></p>
		<div class="calendar-block">
			<div class="header-wrapper">
				<h2>CALENDARIO </h2>
				<form class="years" onchange="this.submit()" method="post">
					<span>Elegir Año:</span>
					<select name="years">
						<?php for($i=2017; $i<=date('Y')+1; $i++): ?>
						<option value="<?= $i ?>" <?= $i==$_year ? 'selected' : '' ?> ><?= $i ?></option>
						<?php endfor; ?>
					</select>
					<select name="periods">
						<?php if($_periods): foreach($_periods as $period): ?>
						<option value="<?= $period['id'] ?>" <?= $period['id']==$_period ? 'selected' : '' ?> data-min="<?= $period['month_min'] ?>" data-max="<?= $period['month_max'] ?>" ><?= $period['period'].' ('.$period['mode'].')' ?></option>
						<?php endforeach; endif; ?>
					</select>
					<button class="btn"><i class="fa fa-refresh"></i></button>
				</form>
			</div>
		</div>
		<div class="calendar-block">
			<div class="courses-wrapper">
				<div class="block-course-edit">
					<h4>Generar Curso</h4>
					<div class="inner-block" id="course_generator">
						<input id="course_start" type="text" placeholder="día de inicio" >
						<select id="course_classes">
							<option value="0">Cant. de Clases</option>
							<?php for($i=1;$i<=20;$i++): ?>
							<option value="<?= $i ?>"><?= $i ?></option>
							<?php endfor; ?>
						</select>
						<ul data-btn-group="days-group" class="days">
							<li data-value="l" class="day">L</li>
							<li data-value="m" class="day">M</li>
							<li data-value="w" class="day">W</li>
							<li data-value="j" class="day">J</li>
							<li data-value="v" class="day">V</li>
							<li data-value="s" class="day">S</li>
							<li data-value="d" class="day">D</li>
						</ul>
						<button data-btn="generate" class="btn btn-success btn-sm">Generar</button>
					</div>
				</div>
				<div class="block-courses">
					<h4>Cursos generados </h4>
					<div id="courses" class="well"></div>
				</div>
			</div>
		</div>
		<?php if($H_USER->has_capability('calendario/edit')): ?>
		<div class="calendar-block filters">
			<div class="item" id="holidays_buttons" style="width:auto">
				<div data-btn-group="holiday-group" class="btn-group">
					<?php if($H_USER->has_capability('calendario/edit/holidays')): ?>
					<button data-btn="holiday" class="btn btn-primary btn-holiday active">Feriado Nacional</button>
					<?php endif; ?>
					<button data-btn="tech" class="btn btn-primary btn-tech <?= (!$H_USER->has_capability('calendario/edit/holidays') ? 'active' : '') ?>">Feriado Técnico</button>
					<button data-btn="forced" class="btn btn-primary btn-forced">Fuerza Mayor</button>
					<button data-btn="erase" class="btn btn-primary btn-erase" title="reestablecer día"><i class="fa fa-eraser"></i></button>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<div class="calendar-wrapper">
			<?php
			$arrMonthNames = ['Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre'];
			$arrDaysNames = ['Lun','Mar','Mié','Jue','Vie','Sáb','Dom'];
			$arrDaysCodes = ['l','m','w','j','v','s','d'];
			$week = 1;
			$daynum = 1;
			for($mth=1; $mth<=12; $mth++):
				if($mth<3){
					$period=1;
				}else if($mth>=3 && $mth <8){
					$period=2;
				}else{
					$period=3;
				}
			?>
			<?php if(($mth-1)%4 == 0 ): ?>
			<div class="calendar-row">
			<?php endif; ?>
			<div class="month-outer" >
				<div class="month-wrapper" data-month="<?= $mth ?>" data-period="<?= $period ?>" >
					<?php
					$calendar = new Calendar();
					$calendar->currentYear = $_year;
					$calendar->currentMonth = $mth;
					?>
					<div class="month-title"><?= $arrMonthNames[$mth-1] ?></div>
					<div class="days-wrapper">
					<?php
					for($dd=1;$dd<=7;$dd++){
						echo '<div class="day-cell day-name">'.$arrDaysNames[$dd-1].'</div>';
					}
					?>
					</div>
					<?php
					for( $i=0; $i<$calendar->weeksInMonth(); $i++ ){
						echo '<div class="days-wrapper" data-week="'.$week.'" >';
						$hasnull = false;
						for($j=1;$j<=7;$j++){
							$day = $calendar->showDay(($i*7)+$j);
							echo '<div class="day-cell '.(is_null($day) ? 'day-null' : $_calendar->findtype($daynum,$_selected)).'" '.(!is_null($day) ? 'data-daynum="'.$daynum.'" data-daycode="'.$arrDaysCodes[$j-1].'"' : '').' >'.$day.'</div>';
							$daynum = !is_null($day) ? $daynum+1 : $daynum;
							$hasnull = is_null($day) ? true : false;
						}
						echo '</div>';
						$week = $hasnull ? $week : $week+1;
					}
					?>
				</div>
			</div>
			<?php if($mth%4 == 0 ): ?>
			</div>
			<?php endif; ?>
			<?php endfor; ?>
		</div>
		<div class="calendar-block">
			<h4>Referencias:</h4>
			<div class="ref-wrapper">
				<div class="mod mod-holiday">Feriado Nacional</div>
				<div class="mod mod-course">Inicio de Clases</div>
				<div class="mod mod-tech">Feriado Técnico</div>
				<div class="mod mod-forced">Fuerza Mayor</div>
			</div>
		</div>
	</div>
</div>




<div id="add_holiday" class="pop-messages">
	<div class="content">
		<div class="pop-header">
			<button type="button" class="close" data-button="close"><i class="fa fa-times"></i></button>
		</div>

		<div class="pop-body"></div>


	</div>
</div>


<script type="text/javascript" src="<?= $HULK->javascript.'/introjs.min.js'?>"></script>
<script type="text/javascript" src="<?= $HULK->javascript.'/bluebird.min.js'?>"></script>
<script type="text/javascript" src="<?= $HULK->javascript.'/functions.js'?>"></script>
<script>
	var hascapability = '<?=$H_USER->has_capability('calendario/edit')?>';
</script>
<script type="text/javascript" src="views/calendario/view.js" ></script>