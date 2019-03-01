<?php

require_once 'config.php';

$H_USER->require_login();
set_time_limit(3600); // 1 hour should be enough

# Vista
$data['v'] = $v = $_REQUEST['v'];

$v();

function view(){

	global $HULK,$LMS,$H_DB,$H_USER,$view;
	$data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
	$menuroot['ruta'] = array("Conversor de curriculas"=>"conversor.php?v=view");

  $dir_origen   = '/var/www/conversor/html';
  $dir_destino  = '/var/www/conversor/php';

  $HULK->SELF = $_SERVER['PHP_SELF']."?v={$v}";
  $view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	//$view->Load('conversor_curricula/'.$v, $data);

  copiar_directorios($dir_origen,$dir_destino);

	echo shell_exec("chown -R sala_tecnica:www-data /var/www/conversor/php/");
	echo shell_exec("chmod -R 777 /var/www/conversor/php/");

	echo "<br /><strong>SE TERMINO DE PROCESAR TODOS LOS ARCHIVOS</strong>";

}

function copiar_directorios($ruta,$destino) {
  if (is_dir($ruta)) {
    mkdir($destino, 0777, true);
    if ($dh = opendir($ruta)) {
      echo "<br /><strong>$ruta</strong>"; // Aqui se imprime el nombre de la carpeta o directorio
        while (($file = readdir($dh)) !== false) {
          flush();
          if ($file!="." && $file!="..") { //solo si el archivo es un directorio, distinto que "." y ".."
            if(!is_dir($ruta . '/' . $file)){
              echo '<br />'.$ruta . '/' . $file; // Aqui se imprime el nombre del Archivo con su ruta relativa
              $extension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
              switch($extension){
                case (in_array($extension,array('html','htm','php'))):
                  procesar_archivo($ruta . '/' . $file, $destino . '/' . cambiar_extension($file,'php'));
                break;
                default:
									if(copy($ruta . '/' . $file, $destino . '/' . $file ))
									{ echo ' COPIADO!'; }else{ echo ' ERROR!'; }
                break;
              }
            }else{
              copiar_directorios($ruta . '/' . $file, $destino . '/' . $file ); // Ahora volvemos a llamar la función
            }
          }
        }
      closedir($dh);
    }
  }
}
function cambiar_extension($archivo,$nuevaextension){
  $extension = strtolower(pathinfo($archivo, PATHINFO_EXTENSION));
  $onlyName = substr($archivo,0,strlen($archivo)-(strlen($extension)+1));
  return $onlyName.'.'.$nuevaextension;
}
function procesar_archivo($origen,$destino){
  echo '<br/>+-+-+-'.$destino;

  if(copy($origen, $destino ))
  {
		$lineas = file($destino);
    $procesado = fopen($destino, "w+b") or die('unable to open file');
		if(stripos($lineas[0],'chk_login_curricula.php')){
			$lineas[0]="<?php require_once('/var/www/html/lms_new/curriculas/chk_login_curricula.php'); ?>\r\n";
		}else{
			array_unshift($lineas,"<?php require_once('/var/www/html/lms_new/curriculas/chk_login_curricula.php'); ?>\r\n");
		}
		foreach($lineas as $linea){
				fwrite($procesado,str_replace('.html','.php',$linea));
		}
		fclose($procesado);
		echo ' PROCESADO!';
	}else{ echo ' ERROR!'; }
  return true;
}
?>
