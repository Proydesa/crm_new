<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase manejo del moodle

*/
class Comprobantes extends H_Database_Conn {

	function Comprobantes(){
		global $HULK;

	}
	public function get_id_comprobantes_saldo($grupoid){
		
		$return = $this->GetAll("SELECT c.id, c.importe,
									(SELECT SUM(cc.importe) FROM h_comprobantes_cuotas cc WHERE cc.comprobanteid=c.id GROUP by cc.comprobanteid) as pagado
									FROM {$HULK->dbname}.h_comprobantes c  
									WHERE c.importe > IFNULL((SELECT SUM(cc.importe) FROM h_comprobantes_cuotas cc WHERE cc.comprobanteid=c.id GROUP by cc.comprobanteid),0)
									AND tipo IN (1,2)
									AND c.grupoid={$grupoid}
									ORDER BY c.id;");
		return $return;
	}

	public function get_saldo($grupoid){
		
		$return = $this->GetAll("SELECT c.id, c.importe,
									(SELECT SUM(cc.importe) FROM h_comprobantes_cuotas cc WHERE cc.comprobanteid=c.id GROUP by cc.comprobanteid) as pagado
									FROM {$HULK->dbname}.h_comprobantes c  
									WHERE c.importe > IFNULL((SELECT SUM(cc.importe) FROM h_comprobantes_cuotas cc WHERE cc.comprobanteid=c.id GROUP by cc.comprobanteid),0)
									AND tipo IN (1,2)
									AND c.grupoid={$grupoid}
									ORDER BY c.id;");
		foreach($return as $comp){
			$saldo += $comp['importe']-$comp['pagado'];
		}
		return $saldo;
	}

	public function get_valorasociado($comprobanteid){
		$return = $this->GetField_sql("SELECT IFNULL(SUM(cc.importe),0) AS importe FROM h_comprobantes_cuotas cc 
								 WHERE cc.comprobanteid={$comprobanteid} 
								 GROUP by cc.comprobanteid;");
		return $return;
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
}