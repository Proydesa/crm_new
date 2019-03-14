<?php

require_once 'config.php';
require_once $HULK->libdir.'/Calendar.php';
require_once $HULK->libdir.'/Input.php';

$H_USER->require_login();

$_calendar = new Calendar();

$data['v'] = $v = $_REQUEST['v'];
$data['_monthnames'] = array('Enero','Febrero','Marzo','Abril','Mayo','Junio','Julio','Agosto','Septiembre','Octubre','Noviembre','Diciembre');
$data['_monthnamesmin'] = array('Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic');
$data['_daynamesmin'] = array('Mon'=>'Lun','Tue'=>'Mar','Wed'=>'Mie','Thu'=>'Jue','Fri'=>'Vie','Sat'=>'Sáb','Sun'=>'Dom');
$data['_daynames'] = array('Lunes','Martes','Miércoles','Jueves','Viernes','Sábado','Domingo');

switch ($v) {

	case 'view':

		$H_USER->require_capability('calendario/view');
		
		$data['_calendar'] = $_calendar;

		$data['_periods'] = $_calendar->getperiods();

		$data['_year'] =  isset($_POST['years']) ? $_POST['years'] : date('Y');
		$data['_period'] =  isset($_POST['periods']) ? $_POST['periods'] : 1;

		$data['_selected'] = $H_DB->GetAll("SELECT daynum, type FROM h_calendario WHERE YEAR(date) = '".$data['_year']."'");

		break;

	case 'cronograma':
		
		$data['_periods'] = $_calendar->getperiods();
		$data['_calendar'] = $_calendar;
		$data['_year'] =  isset($_POST['years']) ? $_POST['years'] : date('Y');
		$data['_period'] =  isset($_POST['periods']) ? $_POST['periods'] : 1;

		
		/////////////////////////
		$data['_node'] = 0;
		if(!empty($data['_periods'])){
			foreach($data['_periods'] as $k=>$period){
				if($data['_period'] == $period['id']){
					$data['_node'] = $k;
				}
			}
		}
		//////// ARMAR CRONOGRAMA /////////////
		$data['_schedules'] = array();
		$nodesch = 0;
		$practico = array();
		$teorico = array();
		$data['_maxrows'] = 1;

		for($i=1; $i<=6; $i++) {
			//$data['_schedules'][] = ['day'=>$i];
			$arrdays = array();
			if(!empty($courses = $_calendar->findcourses($data['_periods'][$data['_node']]['id'],$data['_year']))){
				foreach($courses as $course){
					if(date('N',strtotime($course['start']))==$i){
						$arrdays[$course['start']] = 'Inicio de Clases';
						$practico[] = $_calendar->getfinalclass(strtotime($course['start']),$course['amount']-1,$course['days']);
						$teorico[] = $_calendar->getfinalclass(strtotime($course['start']),$course['amount'],$course['days']);						
					}
				}
			}

			for($m=$data['_periods'][$data['_node']]['month_min']; $m<=$data['_periods'][$data['_node']]['month_max']; $m++){
				if(!empty($holidays = $_calendar->getholidays($m,$data['_year']))){
					foreach ($holidays as $holiday) {
						if(date('N',strtotime($holiday['date']))==$i && strtotime((array_search('Inicio de Clases', $arrdays))) < strtotime($holiday['date']) ){
							$arrdays[$holiday['date']] = 'Feriado '.($holiday['type'] == 'tech' ? 'Técnico' : 'Nacional');
						}
					}
				}
			}
			if(!empty($teorico)){
				foreach ($teorico as $et){
					if(date('N',$et) == $i){
						$arrdays[date('Y-m-d',$et)] = 'Examen Final Teórico';
					}
				}
			}
			if(!empty($practico)){
				foreach ($practico as $ep){
					if(date('N',$ep) == $i){
						$arrdays[date('Y-m-d',$ep)] = 'Examen Final Práctico';
					}
				}
			}
			
			

			$data['_schedules'][] = array('dayname'=>$data['_daynames'][$nodesch],'days'=>array());
			$data['_maxrows'] = count($arrdays)>$data['_maxrows'] ? count($arrdays) : $data['_maxrows'];

			
			ksort($arrdays);
			$data['_schedules'][$nodesch]['days'] = $arrdays;			

			$nodesch++;

		}

		$data['_images'] = $_calendar->get_images($data['_year'],$data['_period']);

		///////////////////////////////////////
		break;

	case 'cronograma-view':

		$data['_periods'] = $_calendar->getperiods();
		//$data['_calendar'] = $_calendar;
		$data['_year'] =  isset($_POST['years']) ? $_POST['years'] : date('Y');
		$data['_period'] =  isset($_POST['periods']) ? $_POST['periods'] : 1;

		break;

	case 'grilla':

		$startdate = isset($_POST['startdate']) ? $_POST['startdate'] : '';
		$finishdate = isset($_POST['finishdate']) ? $_POST['finishdate'] : '';
		$data['_startdate'] = '';
		$data['_finishdate'] = '';
		if(!empty($startdate)){
			$arrdate_start = explode('/',$startdate);
			$data['_startdate'] = $arrdate_start[2].'-'.$arrdate_start[1].'-'.$arrdate_start[0];
		}
		if(!empty($finishdate)){
			$arrdate_fin = explode('/',$finishdate);
			$data['_finishdate'] = $arrdate_fin[2].'-'.$arrdate_fin[1].'-'.$arrdate_fin[0];
		}
		$month = date('n',strtotime($data['_startdate']));
		$year = date('y',strtotime($data['_startdate']));
		switch($month){
			case $month<3:
				$period = 1;
				break;
			case $month>=3 && $month<8:
				$period = 2;
				break;
			case $month>=8:
				$period = 3;
				break;
			default:
				$period = 1;
				break;
		}

		$data['_period'] = $year.$period;
		$data['_aulas'] = $H_DB->GetAll("SELECT * FROM h_academy_aulas WHERE academyid=1 order by orden asc");

		//Horarios
		$data['_schedules'] = $H_DB->GetAll(
		"SELECT h.*, t.desc_turno, t.desc_franja_horaria
		FROM h_horarios h
		LEFT JOIN h_turnos t ON t.cod_turno=h.turno
		GROUP BY h.turno 
		ORDER BY h.name");

		////////////////////////////////
		///$_calendar->findcourses($idperiod=0,$year=0,$month=0,$day=0)
		date_default_timezone_set('America/Argentina/Buenos_Aires');
		$data['_courses'] = $LMS->getCoursesByRange(strtotime($data['_startdate']),strtotime($data['_finishdate']));
		//$data[]
		////////////////////////////////

		break;
	
	default:
		$v	=	'index';
		break;
}


/// Cargar custom CSS para el view ///
$_arrcss = [
	['folder'=>'themes/','style'=>'calendario','rel'=>'stylesheet'],
	['folder'=>'themes/','style'=>'introjs.min','rel'=>'stylesheet']
];

$jquerynew = true;
$view->Load('header');
if(empty($print)) $view->Load('menu',$data);
if(empty($print)) $view->Load('menuroot',$menuroot);
$view->Load('calendario/'.$v, $data);
if(empty($print)) $view->Load('footer');

?>