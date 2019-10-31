<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class H_Mail {

	public $mail;

	function H_Mail() {

		global $HULK;

		$CFG = new stdClass();

		$CFG->libdir = "../lms/lib";
		$CFG->directorypermissions = 00777;
		$CFG->dataroot  = 'D:\AppServ\moodledata';

		//require_once "{$CFG->libdir}/setuplib.php";
		//require_once "{$CFG->libdir}/textlib.class.php";
		require_once "{$HULK->lms_dirroot}/lib/phpmailer/class.phpmailer.php";
		require_once "{$HULK->lms_dirroot}/lib/phpmailer/class.smtp.php";
		///global $HULK;

		$this->mail	= new PHPMailer();

		$this->mail->IsSMTP();
		$this->mail->SMTPAuth   = true;

		$this->mail->SMTPDebug  = false;
		$this->mail->SMTPSecure = 'tls';
		$this->mail->Host       = "smtp.office365.com";
		$this->mail->Port       = 587;
		$this->mail->Username   = "noreply@proydesa.org";
		$this->mail->Password   = "Nry5160!";
		$this->mail->From   		= "noreply@proydesa.org";
		//$this->mail->CharSet   	= 'UTF-8';
		$this->mail->FromName		= 'Fundación Proydesa';

		//$this->mail->AddAttachment("themes/cargando.gif");      // attachment
		$this->mail->IsHTML(true);

		return true;
	}

	function AddReplyTo($address="",$name=""){
		$this->mail->addReplyTo($address,$name);
		return true;
	}

	function AddAddress($address,$addressName=""){
			$this->mail->AddAddress($address, $addressName);
			return true;
	}
	function AddBCC($address,$addressName=""){
			$this->mail->addBCC($address, $addressName);
			return true;
	}
	function Subject($subject){
		$this->mail->Subject    = $subject;
		return true;
	}
	function Body($body){
		$this->mail->Body   = $body;
		return true;
	}
	function AltBody($altbody){
		$this->mail->AltBody    = $altbody;
		return true;
	}
	function Send($subject = NULL,$body = NULL,$address = NULL){

		if($subject) $this->mail->Subject	= $subject;
		if($body) $this->mail->Body    		= $body;
		if($address) $this->mail->AddAddress($address);

		if(!$this->mail->Send()) {
			echo "Mailer Error: " . $this->mail->ErrorInfo;
			return false;
		}
		return true;
	}

}


?>