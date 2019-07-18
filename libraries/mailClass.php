<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class H_Mail {

	public $mail;
	
	function H_Mail() {

		$CFG = new stdClass();

		$CFG->libdir = "../lms/lib";
		$CFG->directorypermissions = 00777;
		$CFG->dataroot  = 'D:\AppServ\moodledata';

		//require_once "{$CFG->libdir}/setuplib.php";
		//require_once "{$CFG->libdir}/textlib.class.php";	
		require_once "{$CFG->libdir}/phpmailer/class.phpmailer.php";	
		require_once "{$CFG->libdir}/phpmailer/class.smtp.php";	
		///global $HULK;
		
		$this->mail	= new PHPMailer();

		/*$this->mail->IsSMTP(); // telling the class to use SMTP
		$this->mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
                                           // 1 = errors and messages
                                           // 2 = messages only
		$this->mail->SMTPAuth   = true;                  // enable SMTP authentication
		$this->mail->Host       = "localhost"; // sets the SMTP server
		$this->mail->Port       = 25;                    // set the SMTP port for the GMAIL server
		$this->mail->Username   = "websitelaboral@proydesa.org"; // SMTP account username
		$this->mail->Password   = "WSLaboral123";        // SMTP account password

		$this->mail->From	   = 'noreply@proydesa.org';
		$this->mail->FromName= 'Fundaci�n Proydesa';
		$this->mail->AddReplyTo("noreply@proydesa.org","Fundaci�n Proydesa");
		*/
		$this->mail->IsSMTP();
		$this->mail->SMTPDebug  = 0;    
		$this->mail->SMTPAuth   = true; 
		$this->mail->SMTPSecure = false;                 // enable SMTP authentication
		$this->mail->Host       = "smtp.correoseguro.co"; // sets the SMTP server
		$this->mail->Port       = 25;                    // set the SMTP port for the GMAIL server
		$this->mail->Username   = "academia@fproydesa.com.ar"; // SMTP account username
		$this->mail->Password   = "WSProy365";        // SMTP account password
		$this->mail->From	   = 'academia@fproydesa.com.ar';
		$this->mail->FromName= 'Fundacion Proydesa';
		//$this->mail->AddAttachment("themes/cargando.gif");      // attachment
		$this->mail->IsHTML(true);

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