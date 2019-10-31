<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){


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

		$where_rendiciones = "";
		if(!$H_USER->has_capability('user/rendicion/viewall')){
			$where_rendiciones = "WHERE userid={$id}";
		}

		$data['rendiciones']	= $H_DB->GetAll("SELECT * FROM h_rendiciones r {$where_rendiciones} ORDER BY date DESC;");


		$where_pendientes = "WHERE pendiente=1 AND anulada=0";

		if($data['rendicionid']){

			$data['turendicion']	= $H_DB->GetRow("SELECT * FROM h_rendiciones r WHERE id={$data['rendicionid']};");


			$where_comprobantes = "WHERE h.takenby={$data['turendicion']['userid']} AND h.rendicionid={$_REQUEST['id']}";
			$data['userid'] = $id = $data['turendicion']['userid'];

			//date_default_timezone_set('America/Argentina/Buenos_Aires');
			$qdate = $data['turendicion']['date'];

			$where_pendientes .= " AND takenby={$id} AND FROM_UNIXTIME(date,'%Y%m%d') = FROM_UNIXTIME({$qdate},'%Y%m%d')";

		}else{
			$where_comprobantes = "WHERE h.takenby={$id} AND h.rendicionid=0 AND h.anulada = 0";
			$where_pendientes .= " AND takenby={$id} AND FROM_UNIXTIME(date,'%Y%m%d') = FROM_UNIXTIME(UNIX_TIMESTAMP(NOW()),'%Y%m%d')";
		}

		$data['comprobantes'] = $H_DB->GetAll(
			"SELECT DISTINCT h.id, h.numero, h.`date` as fecha, h.userid, h.concepto, h.detalle, h.nrocheque, h.importe, c.p_especial, h.tipo, h.puntodeventa,g.name as gruponame
			FROM h_comprobantes h
			LEFT JOIN h_comprobantes_cuotas cc ON h.id=cc.comprobanteid
			LEFT JOIN h_cuotas c ON cc.cuotaid=c.id
			LEFT JOIN h_grupos g ON g.id = h.grupoid
			{$where_comprobantes}
			AND h.pendiente = 0
			ORDER BY h.date ASC;"
		);



		$data['pendientes'] = $H_DB->GetAll(
			"SELECT *
			FROM h_comprobantes
			{$where_pendientes}
			ORDER BY `date` DESC
			LIMIT 0,50"
		);


		break;

	case 'view':
		break;

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
