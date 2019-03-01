<?php

require_once 'config.php';

$H_USER->require_login();

# Vista
$data['v'] = $v = $_REQUEST['v'];

switch($v){
/***********************/
	case 'reporte':
	
		//show_array($_POST); die();
		
		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Reporte Help Desk"=>"#");
	
		if($_POST['academias']){
			$WHERE = " AND a.academyid = ".$_POST['academias'];
			$data['acad_sel'] = $_POST['academias'];
		}
		if($_POST['usuarios']){
			$WHERE .= " AND a.userid = ".$_POST['usuarios'];
			$data['user_sel'] = $_POST['usuarios'];
		}		
		if($_POST['representantes']){
			$WHERE .= " AND a.assignto = ".$_POST['representantes'];
			$data['rep_sel'] = $_POST['representantes'];
		}
		if($_POST['prioridades']){
			$WHERE .= " AND a.priorityid = ".$_POST['prioridades'];
			$data['prio_sel'] = $_POST['prioridades'];
		}
		if($_POST['estados']){
			$WHERE .= " AND a.statusid = ".$_POST['estados'];
			$data['est_sel'] = $_POST['estados'];
		}
		if($_POST['categorias']){
			$WHERE .= " AND a.categoryid = ".$_POST['categorias'];
			$data['cat_sel'] = $_POST['categorias'];
		}
		
		$rows = $H_DB->GetAll("SELECT a.id AS id, a.subject, a.userid, a.assignto, a.startdate, ap.name AS priority, 
													 ast.name AS status, a.academyid AS acad, ac.name AS category, 
													 UNIX_TIMESTAMP(now()) - a.startdate AS horas
													 FROM h_activity a
													 INNER JOIN h_activity_priority ap ON a.priorityid=ap.id
													 INNER JOIN h_activity_category ac ON a.categoryid=ac.id
													 INNER JOIN h_activity_status ast ON a.statusid=ast.id
													 WHERE a.typeid=5 AND a.statusid IN (1, 2, 3, 4)
													 {$WHERE}
													 ORDER BY ap.id DESC, a.startdate ASC;");
																	 
		foreach($rows as $row){
			echo $row['id']."<br />";
			echo "Inicio: ".$row['startdate']. " - Fin: ".time()."<br />";
			/*
			for($day_val = $row['startdate']; $day_val <= time(); $day_val += 86400){
				echo $day_val;
				$pointer_day = date("w", $day_val);
				echo " (".$pointer_day.")<br />";
				if(($pointer_day == 0) || ($pointer_day == 6)){ 

				}else{
					$d++;
				}
			}
			*/
			$day_val = $row['startdate'];
			$limit = time();
			$dia = 86400;
			while($day_val <= $limit){
				$pointer_day = date("w", $day_val);
				if(($pointer_day == 0) || ($pointer_day == 6)){ 

				}else{
					$segundos++;
				}
				
				if(($day_val + $dia) > $limit){
					$day_val = $limit;
				}else{
					$day_val += $dia;
				}

			}
			echo "<br /><br />";
			$row['dias'] = $segundos;
			$data['rows'][] = $row;
			unset($d);			
		}

		$academias = $H_DB->GetOne("SELECT GROUP_CONCAT(DISTINCT academyid) FROM h_activity 
																WHERE typeid=5 AND statusid IN (1, 2, 3, 4);");
																
		if($academias){		
			$data['academias'] = $LMS->GetAll("SELECT id, name FROM mdl_proy_academy 
																				 WHERE deleted=0 AND id != 50 
																				 AND id IN({$academias})
																				 ORDER BY name;");
		}
		
		$usuarios = $H_DB->GetOne("SELECT GROUP_CONCAT(DISTINCT userid) FROM h_activity 
							 								 WHERE typeid=5 AND statusid IN (1, 2, 3, 4);");

		if($usuarios){															 
			$data['usuarios'] = $LMS->GetAll("SELECT id, CONCAT(firstname, ' ', lastname) AS user 
																				FROM mdl_user 
																				WHERE deleted=0 
																				AND id IN({$usuarios})
																				ORDER BY user;");
		}
		
		$representantes = $H_DB->GetOne("SELECT GROUP_CONCAT(DISTINCT assignto) FROM h_activity 
																		 WHERE typeid=5 AND statusid IN (1, 2, 3, 4);");
		
		if($representantes){
			$data['representantes'] = $LMS->GetAll("SELECT id, CONCAT(firstname, ' ', lastname) AS user 
																							FROM mdl_user 
																							WHERE deleted=0 
																							AND id IN({$representantes})
																							ORDER BY user;");
		}
		
		$data['prioridades'] = $H_DB->GetAll("SELECT DISTINCT ap.id, ap.name 
																	  FROM h_activity a
																	  INNER JOIN h_activity_priority ap ON a.priorityid=ap.id
																	  WHERE a.typeid=5 AND a.statusid IN (1, 2, 3, 4)
																	  ORDER BY ap.id;");
																					
		$data['estados'] = $H_DB->GetAll("SELECT DISTINCT ast.id, ast.name 
																 FROM h_activity a
																 INNER JOIN h_activity_status ast ON a.statusid=ast.id
																 WHERE a.typeid=5 AND a.statusid IN (1, 2, 3, 4)
																 ORDER BY ast.id;");																					

		$data['categorias'] = $H_DB->GetAll("SELECT DISTINCT ac.id, ac.name 
											 FROM h_activity a
											 INNER JOIN h_activity_category ac ON a.categoryid=ac.id
											 WHERE a.typeid=5 AND a.statusid IN (1, 2, 3, 4)
											 ORDER BY ac.id;");		
																			
		//show_array($data);die();
																			 
		break;

		
	default:
		$v	=	'index';
		break;
}

$view->Load('header');
$view->Load('menu');
$view->Load('menuroot',$menuroot);
$view->Load('reportes_hd/'.$v, $data);
//$view->Load('footer');
?>
