<?php 

class Calendar {

	public 	$currentYear=0,
					$currentMonth=0,
					$currentDay=0;

	private $_currentDate=null,
					$_daysInMonth=0,
					$_dayLabels = array("Mon","Tue","Wed","Thu","Fri","Sat","Sun"),
					$_hdb,
					$_data;

	public function __construct(){
		global $H_DB, $H_USER, $LMS;
		$this->_hdb = $H_DB;
		$this->_user = $H_USER;
	}

	public function showDay($cellNumber){
			 
		if($this->currentDay==0){
			$firstDayOfTheWeek = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));		 
			if(intval($cellNumber) == intval($firstDayOfTheWeek)){
				$this->currentDay=1;
			}
		}		 
		if( ($this->currentDay!=0) && ($this->currentDay<=$this->_daysInMonth) ){
			$cellContent = $this->currentDay;
			$this->currentDay++;
		}else{
			$cellContent=null;
		}
		///return '<li id="li-'.$this->_currentDate.'" class="'.($cellNumber%7==1?' start ':($cellNumber%7==0?' end ':' ')).($cellContent==null?'mask':'').'">'.$cellContent.'</li>';
		return $cellContent;
	}

	public function weeksInMonth(){
		if( null==($this->currentYear) ) {
			$this->currentYear =  date("Y",time()); 
		}
		if(null==($this->currentMonth)) {
			$this->currentMonth = date("m",time());
		}
		$_daysInMonths = $this->daysInMonth();
		$numOfweeks = ($_daysInMonths%7==0?0:1) + intval($_daysInMonths/7);			 
		$monthEndingDay= date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-'.$_daysInMonths));
		$monthStartDay = date('N',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
		if($monthEndingDay<$monthStartDay){
				$numOfweeks++;
		}
		return $numOfweeks;
	}

	public function daysInMonth(){
		if(null==($this->currentYear)) $this->currentYear =  date("Y",time());
		if(null==($this->currentMonth)) $this->currentMonth = date("m",time());
		$this->_daysInMonth = date('t',strtotime($this->currentYear.'-'.$this->currentMonth.'-01'));
		return $this->_daysInMonth;
	}

	public function get(){
		return $this->_hdb->GetRow("SELECT * FROM h_calendario LIMIT 0,1");
	}

	public function saveold($id=0){

		if(!$this->_hdb->delete('h_calendario','YEAR(date)='.Input::get('year'))) return false;
		if(!empty(Input::get('calendar'))){
			foreach(Input::get('calendar') as $cal){
				$sql = array(
					'date'=>$cal['date'],
					'daynum'=>$cal['daynum'],
					'daycode'=>$cal['daycode'],
					'type'=>$cal['type']
				);
				$check = $this->_hdb->GetRow("SELECT id FROM h_calendario WHERE date='".$cal['date']."'");
				if(empty($check)){
					$this->_hdb->insert('h_calendario',$sql);
				}else{
					$id=$check['id'];
					$this->_hdb->update('h_calendario',$sql,"id={$id}");
				}
			}
		}
		return true;
	}

	public function save(){
		
		$check = $this->_hdb->GetRow("SELECT * FROM h_calendario WHERE date='".Input::get('date')."'");
		$sql = array(
			'date'=>Input::get('date'),
			'daynum'=>Input::get('daynum'),
			'daycode'=>Input::get('daycode'),
			'type'=>Input::get('type')
		);
		///print_r($check);
		if($check['type']=='holiday' && !$this->_user->has_capability('calendario/edit/holidays')){
			return false;
		}
		if(Input::get('type')=='erase'){
			$this->_hdb->delete('h_calendario',"date='".Input::get('date')."'");				
		}else{			
			if(empty($check)){
				$this->_hdb->insert('h_calendario',$sql);
			}else{
				$id=$check['id'];
				$this->_hdb->update('h_calendario',$sql,"id={$id}");
			}
		}
		return true;
	}

	public function getholidays($month='',$year=''){
		$where = "";
		if(!empty($month)){
			$where = "WHERE MONTH(c.date) = '{$month}'";
		}
		if(!empty($year)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "YEAR(c.date) = '{$year}'";
		}
		return $this->_hdb->GetAll(
			"SELECT c.date, c.type 
			FROM h_calendario c 
			{$where}
			ORDER BY c.date ASC"
		);
	}
	public function getholidaysbyperiod($from='',$to='',$type=''){
		$where = "";
		if(!empty($from)){
			$where = "WHERE c.date >= '{$from}'";
		}
		if(!empty($to)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "c.date <= '{$to}'";
		}
		if(!empty($type)){
			$where .= empty($where) ? "WHERE " : " AND ";
			$where .= "c.type = '{$type}'";
		}
		return $this->_hdb->GetAll(
			"SELECT c.date, c.type 
			FROM h_calendario c 
			{$where}
			ORDER BY c.date ASC"
		);
	}

	public function savecourse(){
		$check = $this->_hdb->GetRow("SELECT id FROM h_calendario_courses WHERE start='".Input::get('start')."'");
		$start = explode('/',Input::get('start'));
		$sql = array(
			'idperiod'=>Input::get('idperiod'),
			'start'=>$start[2].'-'.$start[1].'-'.$start[0],
			'amount'=>Input::get('amount'),
			'days'=>Input::get('days')
		);
					
		if(empty($check)){
			$this->_hdb->insert('h_calendario_courses',$sql);
		}else{
			$id=$check['id'];
			$this->_hdb->update('h_calendario_courses',$sql,"id={$id}");
		}
		
		return true;
	}

	public function getcourses(){
		
		$year = Input::get('year');
		$output = array();
		$idperiod = Input::get('idperiod');

		$output = $this->_hdb->GetAll(
			"SELECT cc.id, cc.idperiod, cc.start, DAYOFYEAR(cc.start) daynum, DATE_FORMAT(cc.start,'%d/%m/%Y') inicia, cc.amount, cc.days
			FROM h_calendario_courses cc 
			WHERE YEAR(cc.start)={$year} AND cc.idperiod={$idperiod} 
			ORDER BY cc.start ASC"
		);

		foreach($output as $k=>$class){
			$output[$k]['finaliza'] = date('d/m/Y',$this->getfinalclass(strtotime($class['start']),$class['amount'],$class['days']));
			$output[$k]['enddate'] = $this->getfinalclass(strtotime($class['start']),$class['amount'],$class['days']);
		}

		return $output;
	}

	public function findcourses($idperiod=0,$year=0,$month=0,$day=0){
		$where = "WHERE cc.idperiod={$idperiod}";
		if($month){
			$where .= " AND MONTH(cc.start)={$month}";
		}
		if($year){
			$where .= " AND YEAR(cc.start)={$year}";
		}
		if($day){
			$where .= " AND DAY(cc.start)={$day}";
		}
		return $this->_hdb->GetAll(
			"SELECT cc.id, cc.idperiod, cc.start, cc.amount, cc.days 
			FROM h_calendario_courses cc 
			{$where}
			ORDER BY cc.start ASC"
		);
	}

	public function get_courses_by_date($date=''){
		global $LMS;

		return $LMS->GetAll(
			"SELECT atss.id sessid, atss.attendanceid, atss.sessdate, atss.duration, c.*
			FROM mdl_attendance_sessions atss
			LEFT JOIN mdl_attendance att ON att.id=atss.attendanceid
			LEFT JOIN mdl_course c ON c.id=att.course
			WHERE c.academyid=1 AND FROM_UNIXTIME(atss.sessdate, '%Y-%m-%d') = '{$date}'"
		);
	}
	public function change_attendance($courses=array(),$date='',$type=''){


		global $LMS;

		date_default_timezone_set('America/Argentina/Buenos_Aires');

		if(!is_array($courses)) return true;

		foreach($courses as $course){
			if(!empty($course['newdate'])){

				$arr_date = explode('/', $course['newdate']); 
				$sessdate = strtotime($arr_date[2].'-'.$arr_date[1].'-'.$arr_date[0].' '.date('H',$course['olddate']).':'.date('i',$course['olddate']).':00');
				//$sessdate = $timestamp+(abs($timestamp-$course['olddate']));
			

				$LMS->insert('mdl_attendance_sessions_cancelled',array(
					'attendanceid'=>$course['attendanceid'],
					'sessdate'=>$course['olddate'],
					'description'=>'Feriado '.($type=='tech' ? 'Tecnico' : 'Nacional')
				));
				$LMS->delete('mdl_attendance_sessions',"id=".$course['sessid']);


				$LMS->insert('mdl_attendance_sessions',array(
					'attendanceid'=>$course['attendanceid'],
					'sessdate'=>$sessdate,
					'duration'=>$course['duration'],
					'descriptionformat'=>1
				));

			}
			
		}


		return true;
	}

	////public function findcoursesbyperiod($from='',$to='')

	public function getfinalclass($startdate=0,$clases=1,$daycodes=''){
		define('ONE_DAY', 86400);
		$arrdaycodes = array('D','L','M','W','J','V','S');
		$days = str_split($daycodes);
		$getholidays = $this->_hdb->GetAll("SELECT c.date FROM h_calendario c WHERE YEAR(c.date) = '".date('Y',$startdate)."' ORDER BY c.date ASC");
		$holidays = array();
		if(!empty($getholidays)){
			foreach($getholidays as $dt){
				$holidays[] = $dt['date'];
			}
		}
		$numclase = 1;
		$sumday = 0;
		while ($numclase <= $clases){
			$currentdate = $startdate+(ONE_DAY*$sumday);
			if(in_array($arrdaycodes[date('w',$currentdate)], $days)){
				if(!in_array(date('Y-m-d',$currentdate), $holidays)){
					$numclase++;
				}
			}
			$sumday++;
		}

		return $currentdate;

	}

	public function deletecourse($id=0){
		if(!$id) return false;
		$this->_hdb->delete('h_calendario_courses',"id={$id}");
		return true;
	}

	public function findtype($daynum=0,$arr=array()){
		$type = '';
		if(!empty($arr)){
			foreach($arr as $k=>$day){
				if($daynum == $day['daynum']){
					$type = $day['type'];
				}
			}
		}

		return $type;
	}

	public function getperiods(){
		return $this->_hdb->GetAll("SELECT * FROM h_course_periods");
	}


	public function generate_image(){
		$filename = 'grilla-'.Input::get('year').'-'.Input::get('period').'-'.rand(1111,9999);
		$rawdata = str_replace('data:image/jpeg;base64,', '', Input::get('rawdata'));
		$base64 = base64_decode($rawdata);
		$im = imagecreatefromstring($base64);
		imagejpeg($im,'../../images/calendario/'.$filename.'.jpg',100);
    imagedestroy($im);

    $this->_hdb->insert('h_calendario_images',array(
    	'year'=>Input::get('year'),
    	'period'=>Input::get('period'),
    	'image'=>$filename,
    ));

    return $filename;
	}
	public function get_images($year=0,$period=0){
		return $this->_hdb->GetAll(
			"SELECT * 
			FROM h_calendario_images
			WHERE year={$year} AND period={$period}"
		);
	}
	public function delete_image($id=0){
		$image = $this->_hdb->GetRow("SELECT * FROM h_calendario_images WHERE id={$id}");
		if(empty($image)) return false;

		$this->_hdb->delete('h_calendario_images',"id={$id}");
		if(file_exists('../../images/calendario/'.$image['image'].'.jpg')){
			unlink('../../images/calendario/'.$image['image'].'.jpg');
		}
		return true;
	}

}


?>