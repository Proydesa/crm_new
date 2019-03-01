<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'rendicion':

		$data['rendicionid'] = $_REQUEST['id'];
		$data['userid'] = $id = $H_USER->get_property('id');
		$menuroot['ruta'] = array("Rendición"=>"contactos.php?v=view&id={$id}");

		if ($_REQUEST['action']=="rendir"){
			$rendicion['summary'] = $_REQUEST['summary'];

			$rendicion['userid'] = $id;
			$rendicion['date'] = time();
			if ($comprobante['rendicionid'] = $H_DB->insert("h_rendiciones",$rendicion)){
				$H_DB->update("h_comprobantes",$comprobante,"takenby={$id} AND rendicionid=0 AND pendiente=0 AND anulada = 0");
			}else{
				show_error('Error al generar la rendicion.');
			}
		}

		if ($H_USER->has_capability('user/rendicion/viewall')){
			$data['rendiciones']	= $H_DB->GetAll("SELECT * FROM h_rendiciones r ORDER BY date DESC;");
		}else{
			$data['rendiciones']	= $H_DB->GetAll("SELECT * FROM h_rendiciones r WHERE userid={$id} ORDER BY date DESC;");
		}

		if($data['rendicionid']){
			$data['turendicion']	= $H_DB->GetRow("SELECT * FROM h_rendiciones r WHERE id={$data['rendicionid']};");
			$data['comprobantes']	= $H_DB->GetAll("SELECT DISTINCT h.id, h.numero,h.`date` as fecha, h.userid, h.concepto, h.detalle, h.nrocheque, h.importe, c.p_especial, h.tipo, h.puntodeventa,g.name as gruponame
												 FROM h_comprobantes h
												 LEFT JOIN h_comprobantes_cuotas cc ON h.id=cc.comprobanteid
												 LEFT JOIN h_cuotas c ON cc.cuotaid=c.id
												 LEFT JOIN h_grupos g ON g.id = h.grupoid
												 WHERE h.takenby={$data['turendicion']['userid']} AND h.rendicionid={$_REQUEST['id']}
												 ORDER BY h.date ASC;");
			$data['userid'] = $id = $data['turendicion']['userid'];
		}else{
			$data['comprobantes'] = $H_DB->GetAll("SELECT DISTINCT h.id, h.numero,h.`date` as fecha, h.userid, h.concepto, h.detalle, h.nrocheque, h.importe, c.p_especial, h.tipo, h.puntodeventa,g.name as gruponame
												 FROM h_comprobantes h
												 LEFT JOIN h_comprobantes_cuotas cc ON h.id=cc.comprobanteid
												 LEFT JOIN h_cuotas c ON cc.cuotaid=c.id
												 LEFT JOIN h_grupos g ON g.id = h.grupoid												 
												 WHERE h.takenby={$id} AND h.rendicionid=0 AND h.anulada = 0
												 AND h.pendiente = 0
												 ORDER BY h.date ASC;");
		}

		break;
/***********************/
	case 'view':
		break;
/************************/
	default:
		$v	=	'index';
		break;
}
$view->Load('header',$data);
if(empty($print)) $view->Load('menu');
if(empty($print)) $view->Load('menuroot',$menuroot);
$view->Load('users/'.$v,$data);
$view->Load('footer');
?>
