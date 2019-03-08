<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){

/************************/
	case 'view':

		$menuroot['ruta'] = array("Llamados de la Red"=>"contactos_de_la_red.php?v=view");

		
		$data['academias_list'] = $LMS->getAcademys();
		//$data['academias_user'] = $LMS->getAcademy();
  break;
  case 'search':

    if ($_REQUEST['tipo']!=''){
      if ($_REQUEST['tipo']=='save'){
        $now = new DateTime();
        $nuevaContacto = array(
				'id_academia' => $_REQUEST['idAcademia'],
				'cod_tipo_contacto' => $_REQUEST['tipoContacto'],
				'descripcion' => $_REQUEST['descripcion'],
				'pendientes' => $_REQUEST['descripcion_pendientes']!="" ? 'S':'N',
				'descripcion_pendientes' => $_REQUEST['descripcion_pendientes'],
				'cod_estado' => 'A',
				'usu_alta' => $H_USER->get_property('id'),
				'fec_alta' =>   $now->getTimestamp()
      );
      $cantidadContactosPendientes=0;
      if ($_REQUEST['descripcion_pendientes']!=""){
        $cantidadContactosPendientes = $LMS->GetAll("SELECT count(1) cantidad FROM {$HULK->dbname}.h_contactos_de_la_red c
                                          INNER JOIN {$HULK->dbname}.h_tipo_contacto t ON c.cod_tipo_contacto=t.cod_tipo_contacto
                                          WHERE c.pendientes='S' AND c.usu_alta={$H_USER->get_property('id')}
                                          AND c.cod_estado='A';")[0]['cantidad'];

      }
      if ($cantidadContactosPendientes<5){
        $H_DB->insert('h_contactos_de_la_red',$nuevaContacto);
      }else{
        echo '<script>alert("Ud posee 5 contactos que tienen tareas pendientes. No puede registrar mas pendientes")</script>';
      }
      }
      if ($_REQUEST['tipo']=='update'){
        $now = new DateTime();
        $nuevaContacto = array(
          'id_contactos_de_la_red' => $_REQUEST['idContacto'],
          'cod_tipo_contacto' => $_REQUEST['tipoContacto'],
          'descripcion' => $_REQUEST['descripcion'],
  				'pendientes' => $_REQUEST['descripcion_pendientes']!="" ? 'S':'N',
  				'descripcion_pendientes' => $_REQUEST['descripcion_pendientes'],
  				'cod_estado' => 'A',
  				'usu_mod' => $H_USER->get_property('id'),
  				'fec_mod' =>   $now->getTimestamp()
        );
				$H_DB->update('h_contactos_de_la_red',$nuevaContacto,"id_contactos_de_la_red = {$_REQUEST['idContacto']}");
      }
      if ($_REQUEST['tipo']=='delete'){
        $now = new DateTime();
        $nuevaContacto = array(
          'cod_estado' => 'E',
  				'usu_mod' => $H_USER->get_property('id'),
  				'fec_mod' =>   $now->getTimestamp()
        );
				$H_DB->update('h_contactos_de_la_red',$nuevaContacto,"id_contactos_de_la_red = {$_GET['idContacto']}");
      }
      if ($_REQUEST['tipo']=='cambioFeed'){
        $nuevaContacto = array(
          'feed' => $_REQUEST['feed']
        );
				$H_DB->update('moodle.mdl_proy_academy',$nuevaContacto,"id = {$_REQUEST['idAcademia']}");
      }
      if ($_REQUEST['tipo']=='cambioSituacion'){
        $nuevaContacto = array(
          'situacion' => $_REQUEST['situacion']
        );
				$H_DB->update('moodle.mdl_proy_academy',$nuevaContacto,"id = {$_REQUEST['idAcademia']}");
      }
    }

    $menuroot['ruta'] = array("Llamados de la Red"=>"contactos_de_la_red.php?v=view");
    $data	=	$LMS->getAcademyActivity($_REQUEST['idAcademia']);

    $data['datosAcademia'] = $LMS->getAcademy($_REQUEST['idAcademia']);
    $periodosLMS=$LMS->getPeriodos();
    for($i=0; $i<6; $i++){
      $totalPeriodo=0;
      $academias	= array($_REQUEST['idAcademia']);
      $periodos	= strval($periodosLMS[$i]);
      $data['acad_sel'] = $academias;
      $WHERE='';
      $WHERE .= " AND c.academyid IN(";
      foreach($academias as $academy){
        $WHERE .= "{$academy},";
      }
      $WHERE = substr($WHERE,0,strlen($WHERE)-1).")";
    
  
      $data['paisel']=array();
		
			$data['periodos_sel'] = $periodos;
			$WHERE .= " AND c.periodo ={$periodos} ";
		
		
    

		  $data['carrerasb']=$LMS->GetAll("SELECT 		cr2.id as idcr,cr2.shortname
															FROM mdl_course cr
															INNER JOIN mdl_course cr2 ON cr2.id=cr.from_courseid
															group by cr2.id
															order by cr2.shortname ASC");
      if($_POST['carrera']){
          $data['carsel']=$_POST['carrera'];
          $carsel=implode(",",$_POST['carrera']);
          $WHERECA="AND cm.id IN ({$carsel})";
      }
      else{
          $data['carsel']=array(0);
      }
      
      // Traigo todas las comisiones
      $rows = $LMS->GetAll("SELECT c.id, c.from_courseid, c.shortname, c.startdate, c.enddate, cm.fullname model
                            FROM {$HULK->dbname}.vw_course c INNER JOIN mdl_course cm ON c.from_courseid=cm.id
                            WHERE
                            c.periodo > 0
                            {$WHERE}
                            {$WHERECA}
                            ORDER BY c.fullname;");

      foreach($rows as $row):

        
        $bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$row['id']} AND cancel=0;");

        if($bajas){
          $WHERE2 = " AND u.id NOT IN ({$bajas}) ";
        }else{
          $WHERE2 = "";
        }

        $row['alumnos'] = $LMS->GetAll("SELECT u.id AS uid, CONCAT(u.lastname, ', ', u.firstname) AS alumno,
                                        u.username, u.email, u.acid, u.phone1, u.phone2
                                        FROM {$HULK->dbname}.vw_enrolados enr
                                        INNER JOIN mdl_user u  ON u.id=enr.userid
                                        WHERE enr.id={$row['id']}
                                        AND enr.roleid = 5
                                        {$WHERE2}
                                        ORDER BY u.lastname, u.firstname;");
        $totalPeriodo+=count($row['alumnos']);
        $data['rows'][] = $row;
      endforeach;
     
      $data['carreras'] = $LMS->GetAll("SELECT cm.fullname, cm.shortname, COUNT(*) AS alumnos
                                        FROM {$HULK->dbname}.vw_enrolados e
                                        INNER JOIN mdl_course cm ON e.modelid=cm.id
                                        INNER JOIN {$HULK->dbname}.vw_course c ON c.id=e.id
                                        WHERE (EXISTS(SELECT 1 AS Not_used FROM {$HULK->dbname}.vw_gradebook
                                                      WHERE c.id = vw_gradebook.courseid
                                                      AND e.userid = vw_gradebook.userid) = 0)
                                                      AND e.roleid = 5
                                        {$WHERE}
                                        GROUP BY cm.fullname, cm.shortname
                                        ORDER BY cm.fullname;");
      $total[$i]= $totalPeriodo;
  }
  $data['periodos']=(array_slice($periodosLMS,0,6));
  $data['totales']=$total;
  $data['contactos'] = $LMS->GetAll("SELECT c.*, t.desc_tipo_contacto, (SELECT concat(firstname,' ',lastname) FROM moodle.mdl_user where id= c.usu_alta) usuario,date_format(FROM_UNIXTIME(c.fec_alta),'%d/%m/%Y %H:%i') fecha_alta
                                        ,(SELECT count(1) cantidad FROM crm.h_contactos_de_la_red_usuarios cu WHERE c.pendientes='S' AND cu.cod_estado='A' AND cu.id_contactos_de_la_red= c.id_contactos_de_la_red AND cu.id_usuario = {$H_USER->get_property('id')}) esMiPendiente
                                        FROM {$HULK->dbname}.h_contactos_de_la_red c
                                        INNER JOIN {$HULK->dbname}.h_tipo_contacto t ON c.cod_tipo_contacto=t.cod_tipo_contacto
                                        WHERE c.id_academia={$_REQUEST['idAcademia']}
                                        AND c.cod_estado='A'
                                        ORDER BY c.id_contactos_de_la_red DESC ;");

$data['tipoContactos'] = $LMS->GetAll("SELECT * FROM crm.h_tipo_contacto where cod_estado='A';");

  $data['periodo'] =	$LMS->GetField_sql("SELECT MAX(c.periodo) FROM mdl_course c WHERE c.periodo NOT IN('',0);");

  $data['acad_sel'] = $_REQUEST['idAcademia'];

  $data['periodo_actual']=$periodo_actual = $HULK->periodo;
$data['periodo_anterior']=$periodo_anterior = $HULK->periodo-10;

$data['hoy'] = time();

$sql = "SELECT  COUNT(*) as cant, c2.shortname as carrera,c.periodo
    FROM {$HULK->dbname}.vw_enrolados u
    INNER JOIN {$HULK->dbname}.vw_course c ON u.id=c.id
    INNER JOIN mdl_course c2 ON c2.id =c.from_courseid
      LEFT JOIN  {$HULK->dbname}.h_bajas bajas ON bajas.comisionid=c.id AND bajas.`date` <= '{$data['hoy']}'  AND bajas.cancel=0 AND bajas.userid=u.userid
    WHERE c.academyid IN ({$_REQUEST['idAcademia']}) AND
    u.timestart <= '{$data['hoy']}' AND
    c.periodo IN ({$periodo_actual})
    AND bajas.userid IS NULL
    AND u.roleid = 5
    GROUP BY c2.shortname
    ORDER BY c2.shortname ASC;";
$compa_actual=$LMS->GetAll($sql);

$data['hoy_anterior'] = date('U',strtotime('-1 year'));

$sql = "SELECT  COUNT(*) as cant, c2.shortname as carrera, c.periodo
    FROM {$HULK->dbname}.vw_enrolados u
    INNER JOIN {$HULK->dbname}.vw_course c ON u.id=c.id
    INNER JOIN mdl_course c2 ON c2.id =c.from_courseid
    LEFT JOIN  {$HULK->dbname}.h_bajas bajas ON bajas.comisionid=c.id AND bajas.`date` <= '{$data['hoy']}' AND bajas.cancel=0 AND bajas.userid=u.userid
    WHERE  c.academyid IN ({$_REQUEST['idAcademia']}) AND
    u.timestart <= '{$data['hoy']}' AND
    c.periodo IN ({$periodo_anterior})
      AND bajas.userid IS NULL
      AND u.roleid = 5
    GROUP BY c2.shortname
    ORDER BY c2.shortname ASC;";
$compa_anterior=$LMS->GetAll($sql);

foreach($compa_actual as $compa){
  $data['comparativa'][$compa['carrera']][$compa['periodo']]['insc'] = $compa['cant'];
}
foreach($compa_anterior as $compa){
  $data['comparativa'][$compa['carrera']][$compa['periodo']]['insc'] = $compa['cant'];
}

$bajas_actual=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy']}' AND cancel=0 AND periodo IN ({$periodo_actual}) AND detalle NOT LIKE '%tica por cambio de comisi%' GROUP BY courseid;");
$cambios_actual=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy']}' AND cancel=0 AND periodo IN ({$periodo_actual})  AND detalle LIKE '%tica por cambio de comisi%' GROUP BY courseid;");

$bajas_anterior=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy']}' AND cancel=0 AND periodo IN ({$periodo_anterior}) AND detalle NOT LIKE '%tica por cambio de comisi%' GROUP BY courseid;");
$cambios_anterior=$H_DB->GetAll("SELECT COUNT(*) as cant, periodo, courseid FROM h_bajas h WHERE `date`<='{$data['hoy']}' AND cancel=0 AND periodo IN ({$periodo_anterior}) AND detalle LIKE '%tica por cambio de comisi%' GROUP BY courseid;");

foreach($bajas_actual as $compa){
  if ($LMS->GetField("mdl_course","shortname",$compa['courseid'])!='' && $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!='' ){
    $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['bajas'] = $compa['cant'];
  }
}
foreach($bajas_anterior as $compa){
  if ($LMS->GetField("mdl_course","shortname",$compa['courseid'])!='' && $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!='' ){
    $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['bajas'] = $compa['cant'];
  }
}
foreach($cambios_actual as $compa){
  if ($LMS->GetField("mdl_course","shortname",$compa['courseid'])!='' && $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!='' ){
    $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['cambios'] = $compa['cant'];
  }
}
foreach($cambios_anterior as $compa){
  if ($LMS->GetField("mdl_course","shortname",$compa['courseid'])!='' && $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['insc']!='' ){
    $data['comparativa'][$LMS->GetField("mdl_course","shortname",$compa['courseid'])][$compa['periodo']]['cambios'] = $compa['cant'];
  }
}


$help_desk=$LMS->GetAll("select a.Tema,a.periodo,a.cantidad,
round(a.cantidad*100/(select count(1) 
from crm.h_contactos_help_desk  where id_academia={$_REQUEST['idAcademia']} and periodo=a.periodo ) ,0) promedio 
from (
    
SELECT Tema,periodo, count(1) cantidad 
FROM  crm.h_contactos_help_desk  c
where c.id_academia={$_REQUEST['idAcademia']} group by Tema,periodo) a order by 3 desc");

foreach ($help_desk as $row){
  $data['help_desk'][$row['Tema']][$row['periodo']]=array("cantidad"=>$row['cantidad'],"promedio"=>$row['promedio']);
}
$data['usuariosParaContactos']=$LMS->GetAll("SELECT u.id, concat(lastname,' ',firstname) name FROM crm.h_role_assignments r, moodle.mdl_user u where r.userid=u.id
order by 2 asc;");


break;
case 'check':
$v="";
$now = new DateTime();
        $nuevaContacto = array(
          'pendientes' => 'N',
  				'usu_mod' => $H_USER->get_property('id'),
  				'fec_mod' =>   $now->getTimestamp()
        );
				$H_DB->update('h_contactos_de_la_red',$nuevaContacto,"id_contactos_de_la_red = {$_GET['idContacto']}");
break;
case 'asociacionUsuario':
$v="";
$now = new DateTime();
        $nuevaContacto = array(
          'id_contactos_de_la_red' => $_GET['idContacto'],
          'id_usuario' =>  $_GET['idUsuario'],
  				'usu_alta' => $H_USER->get_property('id'),
  				'fec_alta' =>   $now->getTimestamp()
        );
        $H_DB->insert('h_contactos_de_la_red_usuarios',$nuevaContacto);
break;
case 'desasociacionUsuario':
$v="";
$now = new DateTime();
        $nuevaContacto = array(
          'cod_estado' => 'E',
  				'usu_mod' => $H_USER->get_property('id'),
  				'fec_mod' =>   $now->getTimestamp()
        );
				$H_DB->update('h_contactos_de_la_red_usuarios',$nuevaContacto,"id_contactos_de_la_red = {$_REQUEST['idContacto']} and id_usuario = {$_REQUEST['idUsuario']} ");
break;
case 'cargarTablaUsuarios':
$v="";
$usuarios=$LMS->GetAll("select cu.id_contactos_de_la_red,cu.id_usuario,concat(u.lastname,' ',u.firstname) name FROM crm.h_contactos_de_la_red_usuarios cu, moodle.mdl_user u where cu.id_usuario=u.id and cu.cod_estado='A' and cu.id_contactos_de_la_red={$_GET['idContacto']}");
echo '<table class="ui-widget" style="width:100%"><thead class="ui-widget-header" ><th style="width:80%">Usuario</th><th style="width:20%">&nbsp;</th></thead><tbody class="ui-widget-content">';
foreach ($usuarios as $usuario){
echo '<tr><td>'.$usuario['name'].'</td><td><button type="button" style="width:40px;height:20px" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" onclick="eliminarUsuario('.$usuario['id_contactos_de_la_red'].','.$usuario['id_usuario'].')" role="button" aria-disabled="false"><span class="ui-button-icon-primary ui-icon ui-icon-trash"></span></button></td></tr>';
}
echo '</tbody></table>';
break;

	default:
		$v	=	'index';
    break;
 
}
if ($v!=""){
$view->Load('header',$data);
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('contactos_de_la_red/'.$v,$data);
$view->Load('footer');
}
?>
