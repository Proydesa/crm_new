<?php

class Courses {

	private 	$_hdb,
						$_user,
						$_lms;

	public function __construct(){
		global $H_DB, $H_USER, $LMS;
		$this->_hdb = $H_DB;
		$this->_user = $H_USER;
		$this->_lms = $LMS;
	}


	public function update_cancelled_class_description($id=0,$description=''){
		return $this->_lms->update("mdl_attendance_sessions_cancelled",array('description'=>$description),"id={$id}");
	}


}