<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function txt_cortar($src_texto,$largo=30){
	$contador = 0;
	$texto = $src_texto;

	if (strlen($texto) <= $largo) return $texto;

	// Cortamos la cadena por los espacios
	$arrayTexto = split(' ',$texto);

	// Reconstruimos la cadena
	$texto="";

	while($largo >= (strlen($texto) + strlen($arrayTexto[$contador]))){
	    $texto .= ' '.$arrayTexto[$contador];
	    $contador++;
	}

	return $texto."...";
}
// Asi es como lo arma moodle, la dejo pero no la estamos usando, se está usando la de la libreria User.php
function generate_paswword(){
	define ('PASSWORD_LOWER', 'abcdefghijklmnopqrstuvwxyz');
	define ('PASSWORD_UPPER', 'ABCDEFGHIJKLMNOPQRSTUVWXYZ');
	define ('PASSWORD_DIGITS', '0123456789');
	//define ('PASSWORD_NONALPHANUM', '.,;:!?_-+/*@#&$');

  	$maxlen = 8;
	$digits = 1;
	$lower = 1;
	$upper = 1;
	$nonalphanum = 1;
	$additional = $maxlen - ($lower + $upper + $digits + $nonalphanum);

	$passworddigits = PASSWORD_DIGITS;
	while ($digits > strlen($passworddigits)) {
	  $passworddigits .= PASSWORD_DIGITS;
	}
	$passwordlower = PASSWORD_LOWER;
	while ($lower > strlen($passwordlower)) {
	  $passwordlower .= PASSWORD_LOWER;
	}
	$passwordupper = PASSWORD_UPPER;
	while ($upper > strlen($passwordupper)) {
	  $passwordupper .= PASSWORD_UPPER;
	}
	/*      $passwordnonalphanum = PASSWORD_NONALPHANUM;
	        while ($nonalphanum > strlen($passwordnonalphanum)) {
	            $passwordnonalphanum .= PASSWORD_NONALPHANUM;
	        }
	*/
      // Now mix and shuffle it all
      $password = str_shuffle (substr(str_shuffle ($passwordlower), 0, $lower) .
                               substr(str_shuffle ($passwordupper), 0, $upper) .
                               substr(str_shuffle ($passworddigits), 0, $digits) .
                               substr(str_shuffle ($passwordnonalphanum), 0 , $nonalphanum) .
                               substr(str_shuffle ($passwordlower .
                                                   $passwordupper .
                                                   $passworddigits .
                                                   $passwordnonalphanum), 0 , $additional));
	return substr ($password, 0, $maxlen);
}
function show_fecha($fecha,$format=""){
	global $HULK;

	if($format==""){
		return date("d",$fecha)." de ".$HULK->meses[date("n",$fecha)]." de ".date("Y",$fecha);
	} else {
		return date($format,$fecha);
	}
}
function show_name($id){
	global $LMS;
	$user = $LMS->GetRow("SELECT firstname,lastname FROM mdl_user	WHERE id={$id};");
	return $user['firstname']." ".$user['lastname'];
}
function redireccionar($url){
	global $HULK,$view;

	if (!@header("Location: {$url}")){
		$view->js("$(location).attr('href','{$url}');");
		$view->js("window.location='{$url}';");
		die();
	}
	die();
}
function numero($number){
	return number_format($number,2,",",".");
}
function paginado($pagina,$consulta , $parametros = array()){

	$resultados=30;

	$self = $_SERVER['PHP_SELF'];
	$param = "?";

	$total	= count($maximo);

	if ($pagina<1) $pagina= 1;
	$totalPag = ceil($total/$resultados);
	if ($pagina>$totalPag) $pagina = 1;
	$inicio		= ($pagina-1)*$resultados;

	$x2=0;
	$data['links_pages'] = "Row: {$inicio} - ".($inicio+$resultados)." de {$total} | P&aacute;gina:  ";
	if ($pagina > 1){
		$data['links_pages'] .= "	<span id='1' class='button-seek-start pager'>&nbsp;</span>
															<span id='".($pagina-1)."' class='button-seek-prev pager'> Prev</span>";
		for ($x=$pagina-3;$x<$pagina;$x++){
			if ($x>0){
				$data['links_pages'] .= " <span id='".($x)."' class='button pager'>{$x}</span> ";
				$x2++;
			}
		}
	}
	$data['links_pages'] .= " <span class='ui-button ui-state-disabled'><b>{$pagina}</b></span> ";

	for ($x=($pagina+1);$x<($pagina+11-$x2);$x++){
		if ($x<=$totalPag){
			$data['links_pages'] .= " <span id='".($x)."' class='button pager'>{$x}</span> ";
			$x2++;
		}
	}
	if ($max > $pagina+$resultados)  $data['links_pages'] .= "<span id='".($pagina+1)."' class='button-seek-next pager'>Sig </span>
																						<span id='{$totalPag}' class='button-seek-end pager'>&nbsp;</span>";

	$LIMIT 	= "LIMIT {$inicio},{$resultados}";

	return $data['links_pages'];
}
function getPeriodo($time){
	// Calculo el ultimo numero del periodo.
	$n2=substr($time,3,2);
	if ($n2 <= '2'){$n='1';}
	elseif( $n2 <= '7' ){$n='2';}
	elseif( $n2 <= '12' ){$n='3';}
	$sdays=array('','L','M','W','J','V','S');
	$periodo = substr($time,8,2).$n;
	return $periodo;
}
function unixToPeriodo($unixtime){
	$result = getPeriodo(date("d/m/Y",$unixtime));
	return $result;
}
function tounixtime($date){
	if($date){
		list($day, $month, $year) = explode('-', $date);
		return mktime($hours, $minutes, $seconds, $month, $day, $year);
	}else{
		return NULL;
	}
}
function fromunixtime($unixtime){
	return date("d-m-Y",$unixtime);
}
// Convierte los nombres de los días de Inglés a Español
function ConvertDays($day){
	switch ($day) {
		case 'Monday':
			$dayconverted = 'Lunes';
			break;
		case 'Mon':
			$dayconverted = 'Lun';
			break;
		case 'Tuesday':
			$dayconverted = 'Martes';
			break;
		case 'Tue':
			$dayconverted = 'Mar';
			break;
		case 'Wednesday':
			$dayconverted = 'Miércoles';
			break;
		case 'Wed':
			$dayconverted = 'Mie';
			break;
		case 'Thursday':
			$dayconverted = 'Jueves';
			break;
		case 'Thu':
			$dayconverted = 'Jue';
			break;
		case 'Friday':
			$dayconverted = 'Viernes';
			break;
		case 'Fri':
			$dayconverted = 'Vie';
			break;
		case 'Saturday':
			$dayconverted = 'Sábado';
			break;
		case 'Sat':
			$dayconverted = 'Sab';
			break;
		case 'Sunday':
			$dayconverted = 'Domingo';
			break;
		case 'Sun':
			$dayconverted = 'Dom';
			break;
		default:
			$dayconverted = $day;
	}
	return $dayconverted;
}
// Convierte los nombres de los meses de Inglés a Español
function ConvertMonth($month){
	switch ($month) {
		case 'January':
			$monthconverted = 'Enero';
			break;
		case 'Jan':
			$monthconverted = 'Ene';
			break;
		case 'February':
			$monthconverted = 'Febrero';
			break;
		case 'March':
			$monthconverted = 'Marzo';
			break;
		case 'April':
			$monthconverted = 'Abril';
			break;
		case 'Apr':
			$monthconverted = 'Abr';
			break;
		case 'May':
			$monthconverted = 'Mayo';
			break;
		case 'June':
			$monthconverted = 'Junio';
			break;
		case 'July':
			$monthconverted = 'Julio';
			break;
		case 'August':
			$monthconverted = 'Agosto';
			break;
		case 'Aug':
			$monthconverted = 'Ago';
			break;
		case 'September':
			$monthconverted = 'Septiembre';
			break;
		case 'October':
			$monthconverted = 'Octubre';
			break;
		case 'November':
			$monthconverted = 'Noviembre';
			break;
		case 'December':
			$monthconverted = 'Diciembre';
			break;
		case 'Dec':
			$monthconverted = 'Dic';
			break;
		default:
			$monthconverted = $month;
	}
	return $monthconverted;
}
function flush_buffers($text=NULL){ 
	
	if(isset($text)){
		if(is_array($text) OR is_object($text)){
			show_array($text);
		}else{
			echo '<p>'.$text.'</p>';
		}
	} 
	ob_end_flush(); 
    @ob_flush(); 
    flush(); 
    ob_start(); 
}