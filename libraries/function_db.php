<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase manejo del moodle

*/
class H_Database extends H_Database_Conn {

	function H_Database(){
		global $HULK;

	}

	function getCuotasDefault($comisionid){
	// Obtener las cuotas por defecto de una comisión
		global $LMS;
		$modelo	= $LMS->GetField("mdl_course","from_courseid",$comisionid);
		$plan_periodo	= $LMS->GetField("mdl_course","periodo",$comisionid);
		$plan_periodo	= substr($plan_periodo,-1);
		$periodos		= array(1=>"periodo_uno",2=>"periodo_dos",3=>"periodo_tres");
		//Valido si el plan es el genérico
		//Chequeo si es comisión intensiva o regular
		if($LMS->GetField("mdl_course", "intensivo", $comisionid)==1){
			$cuotas_default = $this->GetRow("SELECT cuotas_intensivo,{$periodos[$plan_periodo]}_int FROM h_cuotas_curso cc WHERE courseid={$modelo};");
			if ($cuotas_default[$periodos[$plan_periodo]."_int"] != ""){
				$cuotas_default = explode("#",$cuotas_default[$periodos[$plan_periodo]."_int"]);
			}else{
				$cuotas_default = explode("#",$cuotas_default['cuotas_intensivo']);
			}
		}else{
			$cuotas_default = $this->GetRow("SELECT cuotas,{$periodos[$plan_periodo]} FROM h_cuotas_curso WHERE courseid={$modelo}");
			if ($cuotas_default[$periodos[$plan_periodo]] != ""){
				$cuotas_default = explode("#",$cuotas_default[$periodos[$plan_periodo]]);
			}else{
				$cuotas_default = explode("#",$cuotas_default['cuotas']);
			}
		}
		return $cuotas_default;
	}
	
	function pagarCuotas(){

		return true;
	}

	function compararCuotas($cuotas,$cuotas_default){
		global $LMS;
		//Primero valido que tengan la misma cantidad de cuotas
		$p_especial	= false;
		if(count($cuotas)-1 != count($cuotas_default)){
			//es un plan especial
			$p_especial	= true;
		}else{
			if($becado > 0){
				$p_especial = true;
			}else{
				foreach ($cuotas as $indice=>$cuota){
					//indice = 0 -> es el libro
					if($indice > 0){
						if($cuota != $cuotas_default[$indice-1]){
							$p_especial = true;
						}
					}
				}
			}
		}
		if($p_especial){
			//TODO: Registrar case
			// Registro la actividad a nombre de flor
			$activity['userid'] 		= $H_USER->get_property('id'); //Userid de Flor
			$activity['contactid']	= 32730; //Userid de Flor
			$activity['typeid'] 		= 4;
			$activity['statusid'] 	= 7;
			$activity['subject'] 		= "Modificación de Plan por defecto";
			$activity['summary'] 		= "El usuario
																<a href='http://www.proydesa.org/crm/contactos.php?v=view&id={$H_USER->get_property('id')}'>{$LMS->GetField('mdl_user', 'CONCAT(firstname, " ", lastname)', $H_USER->get_property('id'))}</a>
																modificó el Plan por defecto para el contacto
																<a href='http://www.proydesa.org/crm/contactos.php?v=view&id={$id}'>{$LMS->GetField('mdl_user', 'CONCAT(firstname, " ", lastname, " (DNI: ", username, ")")', $id)}</a>
																en la comisión {$LMS->GetField('mdl_course','shortname',$comision)}.";
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();

		/*	$mail = new H_Mail();
			$mail->Subject("CRM: ".$activity['subject']);
			$mail->Body($activity['summary']);
			$mail->AddAddress("administracion@proydesa.org");
			$mail->Send();
	*/
			if(!$this->insert("h_activity",$activity)){
				show_error("Error al registrar la actividad.");
			}

		}
		return $p_especial;
	}
	function h_gradebooks($return,$campo,$value){
		if ($value)	$rs = $this->getCol("SELECT {$return} FROM h_gradebooks WHERE {$campo}={$value}");
		if ($rs) return $rs[0];
		return false;
	}

	function getCourseConfig($id){

		if (!$result = $this->GetRow("SELECT c.* FROM crm.h_course_config c WHERE c.courseid={$id};")){
			return false;
		}
		return $result;
	}

	function getCourseConfigFull($id=0){
		if(!$result = $this->GetAll("SELECT c.* FROM h_course_config c WHERE c.courseid={$id};")) return false;
		return $result;
	}

	
	function asociarGrupo($userid,$grupoid){
		if ($grupoid > 0 AND $userid > 0){
			if (!$this->record_exists_sql("SELECT * FROM h_grupos_users WHERE grupoid={$grupoid} AND userid={$userid}")){
				$this->insert("h_grupos_users",array("userid"=>$userid,"grupoid"=>$grupoid));
				return true;
			}
		}else{
			return false;
		}
	}
	function registrarInscripcion($userid, $comisionid, $opciones=array()){
		global $LMS,$H_USER;
		// Registro la inscripción
		$insc->userid 		= $userid;
		$insc->courseid 	= $LMS->GetField("mdl_course","from_courseid",$comisionid);
		$insc->date				= time();
		$insc->takenby		= $H_USER->get_property('id');
		$insc->periodo		= $LMS->GetField("mdl_course","periodo",$comisionid);
		$insc->comisionid	= $comisionid;
		if($opciones){
			$insc->becado			= $opciones['becado'];
			$insc->descuento	= $opciones['descuento'];
		}
		if(!$insc->id = $this->insert("h_inscripcion", $insc)){
			show_error("Error al registrar la inscripción.");
			return false;
		}
		return $insc->id;
	}
	function registrarBaja($userid,$courseid, $opciones){
		global $LMS,$H_USER;
		$baja = new stdClass();
		$baja->userid 		= $userid;
		$baja->comisionid = $courseid;
		$baja->detalle 		= $opciones['detalle'];
		$baja->periodo 		= $LMS->GetField("mdl_course","periodo",$courseid);
		$baja->courseid 	= $LMS->GetField("mdl_course","from_courseid",$courseid);
		$baja->date				= time();
		$baja->user 			= $H_USER->get_property('id');
		$baja->insc_id 		= $opciones['insc_id'];

		if(!$this->insert('h_bajas',$baja)){
			show_error("Error","Error al insertar la baja");
			return false;
		}
	}
	function cancelarBaja($userid,$courseid){
		global $H_USER;
		$baja_e = $this->GetOne("SELECT id FROM h_bajas WHERE userid={$userid} AND comisionid={$courseid} AND cancel=0;");
		if($baja_e > 0){
			$baja_comi_new['cancel'] = 1;
			$baja_comi_new['date_cancel'] = time();
			$baja_comi_new['user_cancel'] = $H_USER->get_property('id');
			if(!$this->update("h_bajas", $baja_comi_new, "id={$baja_e}")){
				show_error("Error","Error al actualizar la tabla h_bajas");
				return false;
			}
		}
	}
	function generarNotaDeCredito($userid,$importe,$detalle){
		global $H_USER;
		// Genero una nota de crédito para cancelar el/los comprobantes
		$nc->numero 		= 0;
		$nc->userid			= $userid;
		$nc->date				= time();
		$nc->importe		= $importe;
		$nc->concepto		= 1;
		$nc->tipo				= 3;
		$nc->detalle		= $detalle;
		$nc->takenby 		= $H_USER->get_property('id');
		$nc->nrocheque 	= 0;

		if(!$nc->id = $this->insert("h_comprobantes",$nc)){
			show_error("Pagos","Error al insertar NC en la base de datos");
			return false;
		}else{
			return $nc->id;
		}
	}
	function generarRecibo($userid,$importe,$detalle,$nrocheque=0){
		global $H_USER;
		// Genero el nuevo comprobante
		$comp->numero 		= 0;
		$comp->userid 		= $userid;
		$comp->date 			= time();
		$comp->importe 		= $importe;
		$comp->concepto 	= 1;
		$comp->tipo 			= 1;
		$comp->detalle 		= $detalle;
		$comp->takenby 		= $H_USER->get_property('id');
		$comp->nrocheque 	= $nrocheque;

		if(!$comp->id = $this->insert("h_comprobantes",$comp)){
			show_error("Pagos","Error al insertar el nuevo comprobante en la base de datos");
			return false;
		}else {
			return $comp->id;
		}
	}

	function setActivityLog($contact = 0,$subject,$summary,array $options = array()){
		global $H_USER,$HULK;

		$options = array_merge(array('typeid' => 4,'statusid' => 7,'academyid' => 0), $options);

		if ($contact==0){	$contact=$H_USER->get_property('id'); }
		$activity['userid'] 		= $H_USER->get_property('id');
		$activity['contactid']	= $contact;
		$activity['typeid'] 		= $options['typeid'];
		$activity['statusid'] 	= $options['statusid'];
		$activity['academyid']	= $options['academyid'];
		$activity['subject'] 		= $subject;
		$activity['summary'] 		= $summary;
		$activity['startdate'] 	= time();
		$activity['enddate']	 	= time();
		if(!$activityid = $this->insert("h_activity",$activity)){
			show_error("Error al registrar la actividad.");
			return false;
		}
		return $activityid;
	}
	function setActivity($act){
		global $H_USER,$HULK;

		$activity_file['activityid'] = $this->setActivityLog(0,$act['subject'],$act['summary'],array('typeid'=>$act['typeid'],'statusid'=>$act['statusid'],'academyid'=>$act['academyid']));

	 	$valida	=	"1";
		$extensiones	=	array("exe","php");
	   	if (isset($_FILES["archivos"])) {
	   		foreach ($_FILES["archivos"]["error"] as $key => $error) {
				if ($error == UPLOAD_ERR_OK) {
					$files['userid']	= $H_USER->get_property('id');
					$files['name']		= $_FILES['archivos']['name'][$key];
					$files['size']		= $_FILES['archivos']['size'][$key];
					$files['type']		= $_FILES['archivos']['type'][$key];
					$temp_ext 			= explode(".",$files['name']);
					$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
					$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
					$files['date']		= time();
					foreach($extensiones as $ext){
						if($ext == $files['ext']) $valida = "0";
					}
				 	if($valida=="1"){
						if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key])){
							if(!is_dir($files['locate'])){
							 	@mkdir($files['locate'], 0777);
							}
							copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
							if(!$activity_file['fileid'] = $this->insert("h_files",$files)){
								show_error("Error al insertar en h_files.");
							}else{
								if(!$fileid = $this->insert("h_activity_files",$activity_file)){
									show_error("Error al insertar en h_activity_files.");
								}
							}
						}
					}
				}
			}
		}
	}
}