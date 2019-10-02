<?php

class Responses {

	private static	$_arrout=array();

	public static function response($status='',$message='',$array=array()){
		self::$_arrout['status'] = $status;
		switch ($status) {
			case 'ok':
				$custommessage = '';
				break;
			case 'fail':
				$custommessage = 'Hubo problemas al procesar la solicitud. Intenta nuevamente más tarde.';
				break;
			case 'restricted':
				$custommessage = 'No tienes permiso para realizar esta operación.';
				break;
			case 'required':
				$custommessage = 'Faltan parámetros.';
				break;

			case 'max_filesize':
				$custommessage = 'El tamaño del archivo no debe superar '.ini_get('upload_max_filesize').'';
				break;
			case 'upload_fail':
				$custommessage = 'Hubo problemas al subir el archivo. Intenta nuevamente más tarde.';
				break;
			case 'folder_fail':
				$custommessage = 'No se pudo crear la carpeta en el servidor.';
				break;
		}
		self::$_arrout['message'] = empty($message) ? $custommessage : $message;
		if(!empty($array)){
			self::$_arrout = array_merge(self::$_arrout,$array);
		}
		return json_encode(self::$_arrout);
	}


}