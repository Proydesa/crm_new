<?php

require_once 'config.php';

if (isset($_REQUEST['v'])){
	$data['v'] = $v = $_REQUEST['v'];
}else{
	$data['v'] = $v	= "index";
}

$H_USER->require_login();

$v();

function nuevo(){
	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		$H_USER->require_capability('activity/new');

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Registrar nuevo incidente"=>"#");
		if ($_REQUEST['action']=='crear'){

			$activity['userid'] 	= $H_USER->get_property('id');
			$activity['typeid'] 	= 5;
			$activity['subject'] 	= $_POST['subject'];
			$activity['priorityid'] = 1;			
			$activity['summary'] 	= stripcslashes(nl2br($_POST['summary']));
			$activity['startdate'] 	= time();
			$activity['enddate']	= time();
			$activity['statusid'] 	= 2;
			$activity['private'] 	= 0;
			$activity['academyid'] 	= $_POST['academy'];
			$activity['categoryid'] = $_POST['category'];
			$activity['contactid'] 	= $_POST['contactid'];

			if ($_POST['category']){
				$category_form	= $H_DB->GetAll("SELECT acf.* FROM h_activity_category_form acf WHERE acf.categoryid={$_POST['category']};");
				if ($category_form){
					foreach($category_form as $field){
						if ($field['type']!="file"){
						$aserializar[$field['name']]=$_POST[str_replace(" ","_",$field['name'])];
						}else{
						
						
						}
					}
					$activity['serialize_data']=serialize($aserializar);
				}
			}
			if(!$activity_file['activityid'] = $H_DB->insert("h_activity",$activity)){
				show_error("Error al insertar h_activity.");
			}else{
			
				$mail = new H_Mail();
				$mail->Subject("HELPDESK: Incidente {$activity_file['activityid']} Creado");
				$mail->Body("<br/>Gracias por registrar su incidente en Fundación Proydesa Help Desk.  Puede ver o actualizar el incidente en: http://www.proydesa.org/crm/hd.php?v=details&id={$activity_file['activityid']}
							<br/>
							<br/>DETALLES DEL INCIDENTE
							<p>---------------
							<br/>ID:  {$activity_file['activityid']} 
							<br/>Usuario: {$activity['userid']}
							<br/>Fecha: {$activity['startdate']}
							<br/>Título: {$activity['subject']}
							</p> 
							<br/>DESCRIPCIÓN
							<br/>-----------
							<p>{$activity['summary']}
							</p> ");
				
				foreach(explode(',',$HULK->hd_user_notif) as $touser){
					$mail->AddAddress($LMS->GetField('mdl_user','email',$touser), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$touser));
				}
				
				$mail->Send();
			
				$ext_permitidas	=	explode(",",$HULK->hd_adjuntos_ext);

				if (isset($_FILES["archivos"])) {
			
					foreach ($_FILES["archivos"]["error"] as $key => $error) {
						if ($error == UPLOAD_ERR_OK) {

							$files['userid']	= $H_USER->get_property('id');
							$files['name']		= $_FILES['archivos']['name'][$key];
							$files['size']		= $_FILES['archivos']['size'][$key];
							$files['type']		= $_FILES['archivos']['type'][$key];
							$temp_ext 				= explode(".",$files['name']);
							$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
							$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
							$files['date']		= time();

							
							if(in_array($files['ext'],$ext_permitidas)){
								if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key]))
								{
									if(!is_dir($files['locate'])) 	
										@mkdir($files['locate'], 0777);
									copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
									if(!$activity_file['fileid'] = $H_DB->insert("h_files",$files)){
										show_error("Error al insertar en h_files.");
									}else{
										if(!$fileid = $H_DB->insert("h_activity_files",$activity_file)){
											show_error("Error al insertar en h_activity_files.");
										}
									}
								}
							}
						}
					}
				}	
				redireccionar("hd.php?v=details&id={$activity_file['activityid']}");
				die();
			}
			die();
		}
		
		$data['academias'] = $LMS->getAcademys();
		$data['activity_type']		= $H_DB->GetAll("SELECT * FROM h_activity_type;");
		$data['activity_status']	= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$data['activity_priority']	= $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		$data['activity_category']	= $H_DB->GetAll("SELECT * FROM h_activity_category ORDER BY weight;");

		$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}		
function details(){
	$id = $_REQUEST['id'];
	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';
		$H_USER->require_capability('activity/view');

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Incidente N° {$id}"=>"#");

		if(!$H_USER->has_capability('activity/viewall')){
			$WHERE = " AND a.userid={$H_USER->get_property('id')} ";		
		}
		
		if (!@$data['activity']	= $H_DB->GetRow("SELECT a.*,s.name as status,t.name as type, t.icon as icon 
													FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
													INNER JOIN h_activity_type t ON a.typeid=t.id 
													WHERE a.id={$id} {$WHERE}
													")){
			
			show_404();										
		}
		
		$data['activity']['serialize_data']=unserialize($data['activity']['serialize_data']);
		$data['activity']['files'] = $H_DB->GetAll("SELECT f.name as file
														FROM h_files f 
														INNER JOIN h_activity_files af ON af.fileid=f.id
														WHERE af.activityid = {$data['activity']['id']}  
														ORDER BY f.name;");
		if(!$H_USER->has_capability('activity/viewprivate')){
			$WHERE = " AND a.private=0 ";		
		}
						
		$data['activity_parent']	= $H_DB->GetAll("SELECT a.* , ap.name as priority ,s.name as status,t.name as type, t.icon as icon
													 FROM h_activity a 
													 INNER JOIN h_activity_status s ON a.statusid=s.id								 
													 INNER JOIN h_activity_type t ON a.typeid=t.id
													 INNER JOIN h_activity_priority ap ON a.priorityid=ap.id													 
													 WHERE a.parent={$id} {$WHERE} ORDER BY a.startdate
													");
		

		foreach($data['activity_parent'] as $key=>$parent){
			$data['activity_parent'][$key]['files'] = $H_DB->GetAll("SELECT f.name as file
														FROM h_files f 
														INNER JOIN h_activity_files af ON af.fileid=f.id
														WHERE af.activityid = {$parent['id']}  
														ORDER BY f.name;");
		}
		$data['academias'] = $LMS->GetAcademys();
		$data['assigns_users'] = $H_DB->GetAll("SELECT DISTINCT ra.userid, GROUP_CONCAT(DISTINCT r.name SEPARATOR ' - ') as roles
																			 FROM h_role_assignments ra
																			 INNER JOIN h_role r ON ra.roleid=r.id GROUP BY ra.userid ORDER BY r.name;");

		$data['activity_type']		= $H_DB->GetAll("SELECT * FROM h_activity_type ORDER BY sortorder ASC;");
		$data['activity_status']		= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$data['activity_priority']		= $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		$data['activity_category']		= $H_DB->GetAll("SELECT * FROM h_activity_category;");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function view(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


		$data['id'] = $id = $H_USER->userID;

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Listado de incidentes"=>"#");
		
		$H_USER->require_capability('activity/view');
		
		$WHERE = "WHERE a.parent=0 AND a.statusid IN (1,2,3,4,5,6) AND a.typeid=5 ";

		if(!$H_USER->has_capability('activity/representante')){
			$WHERE .= "AND academyid IN ({$H_USER->getAcademys()})";
		}
		
		if(!$H_USER->has_capability('activity/viewall')){
			$WHERE .= " AND a.userid={$H_USER->userID} ";	
		}
		
		if ($_GET['subject'])
			$WHERE2 .=" AND a.subject LIKE '%{$_GET['subject']}%'";

		if($H_USER->has_capability('activity/representante')){			
			if ($_GET['assignto']>0){
				$WHERE2 .=" AND a.assignto = {$_GET['assignto']}";
				$data['assignselect'][$_GET['assignto']]="SELECTED";
			}
		}
		
		if($H_USER->has_capability('activity/viewall')){
			if ($_GET['quserid']>0){
				$WHERE2 .=" AND a.userid = {$_GET['quserid']}";
				$data['useridselect'][$_GET['quserid']]="SELECTED";
			}
			
		$data['quserid']	= $H_DB->GetAll("SELECT DISTINCT a.userid as id
											FROM h_activity a 
											{$WHERE};");
		}
		if ($_GET['statusid']){
			$WHERE2 .=" AND a.statusid = {$_GET['statusid']}";
			$data['statusselect'][$_GET['statusid']]="SELECTED";
		}
			
		if($_GET['startdate']){
			$data['qstartdate'] = strtotime(date($_GET['startdate']));
			$WHERE2 .= " AND a.startdate >= {$data['qstartdate']}";
		}else{
			$data['qstartdate']	= $H_DB->GetOne("SELECT MIN(a.startdate) FROM h_activity a  {$WHERE};");
		}
		if($_GET['enddate']){
			$data['qenddate'] = strtotime(date($_GET['enddate']));			
			$enddate = $data['qenddate']+86400;
			$WHERE2 .=" AND a.startdate <= {$enddate}";
		}else{
		$data['qenddate']	= $H_DB->GetOne("SELECT MAX(a.startdate) FROM h_activity a  {$WHERE};");
		}		
		//ORDER
		if($_GET['dir']=='ASC'){
			$data['ndir']='DESC';
			$data['dir'] = $dir = 'ASC';
		}else{
			$data['ndir']='ASC';
			$data['dir'] = $dir = 'DESC';
		}
		if($_GET['order']){
			$ORDER = $_GET['order']." ".$dir;
			$data['order']=$_GET['order'];$data;
		}else
			$ORDER = "a.startdate {$dir},a.id {$dir}";
		
		if($_GET['start']){
			$data['start']=$_GET['start'];
			$LIMIT="LIMIT {$_GET['start']},20";
		}else{
			$data['start']=0;
			$LIMIT="LIMIT 0,20";
		}

		$data['activitys']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority,
											(SELECT count(a2.id) FROM h_activity a2 WHERE a2.parent=a.id AND private=0) as updates
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																			 LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
																		   {$WHERE} {$WHERE2}
																			ORDER BY {$ORDER} {$LIMIT}");

		$data['qassignto']	= $H_DB->GetAll("SELECT DISTINCT a.assignto as id
											FROM h_activity a 
											{$WHERE} AND a.assignto !=0
											");
											
		$data['qstatus']	= $H_DB->GetAll("SELECT DISTINCT s.id, s.name
											FROM h_activity_status s
											INNER JOIN h_activity a ON a.statusid=s.id
											{$WHERE}											
											ORDER BY s.name");

		
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function representante(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$data['id'] = $id = $H_USER->userID;

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Listado de incidentes"=>"#");
		
		$H_USER->require_capability('activity/representante');
		
		$WHERE = "WHERE a.parent=0 AND a.typeid!=4";
		
		if ($_GET['subject'])
			$WHERE2 .=" AND a.subject LIKE '%{$_GET['subject']}%'";

		if($H_USER->has_capability('activity/viewall')){
			if ($_GET['quserid']>0){
				$WHERE2 .=" AND a.userid = {$_GET['quserid']}";
				$data['useridselect'][$_GET['quserid']]="SELECTED";
			}
			$data['quserid']	= $H_DB->GetAll("SELECT DISTINCT a.userid as id
											FROM h_activity a 
											{$WHERE};");
		}
		if ($_GET['statusid']){
			$WHERE2 .=" AND a.statusid = {$_GET['statusid']}";
			$data['statusselect'][$_GET['statusid']]="SELECTED";
		}
			
		if($_GET['startdate']){
			$data['qstartdate'] = strtotime(date($_GET['startdate']));
			$WHERE2 .= " AND a.startdate >= {$data['qstartdate']}";
		}else{
			$data['qstartdate']	= $H_DB->GetOne("SELECT MIN(a.startdate) FROM h_activity a  {$WHERE};");
		}
		if($_GET['enddate']){
			$data['qenddate'] = strtotime(date($_GET['enddate']));			
			$enddate = $data['qenddate']+86400;
			$WHERE2 .=" AND a.startdate <= {$enddate}";
		}else{
		$data['qenddate']	= $H_DB->GetOne("SELECT MAX(a.startdate) FROM h_activity a  {$WHERE};");
		}		
		//ORDER
		if($_GET['dir']=='ASC'){
			$data['ndir']='DESC';
			$data['dir'] = $dir = 'ASC';
		}else{
			$data['ndir']='ASC';
			$data['dir'] = $dir = 'DESC';
		}
		if($_GET['order']){
			$ORDER = $_GET['order']." ".$dir;
			$data['order']=$_GET['order'];$data;
		}else
			$ORDER = "a.startdate {$dir},a.id {$dir}";
		
		if($_GET['start']){
			$data['start']=$_GET['start'];
			$LIMIT="LIMIT {$_GET['start']},20";
		}else{
			$data['start']=0;
			$LIMIT="LIMIT 0,20";
		}
			
		$data['activitys_assign']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority 
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																			 LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
 																		 WHERE a.parent=0 AND a.typeid!=4
																		   AND a.assignto={$id}");
		$data['activitys']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority 
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																			 LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
																		   {$WHERE} {$WHERE2}
   																		   AND a.assignto=0
																			ORDER BY {$ORDER} {$LIMIT}");																			
		$data['qassignto']	= $H_DB->GetAll("SELECT DISTINCT a.assignto as id
											FROM h_activity a 
											{$WHERE} AND a.assignto !=0
											");
											
		$data['qstatus']	= $H_DB->GetAll("SELECT DISTINCT s.id, s.name
											FROM h_activity_status s
											INNER JOIN h_activity a ON a.statusid=s.id
											{$WHERE}											
											ORDER BY s.name");

		
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function representante_view(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$id = $_REQUEST['id'];

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Incidente N° {$id}"=>"#");

		$H_USER->require_capability('activity/representante');
		
		$data['activity']			= $H_DB->GetRow("SELECT a.*,s.name as status,t.name as type, t.icon as icon 
													FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
													INNER JOIN h_activity_type t ON a.typeid=t.id 
													WHERE a.id={$id}
													");
		$data['activity']['files'] = $H_DB->GetAll("SELECT f.name as file
														FROM h_files f 
														INNER JOIN h_activity_files af ON af.fileid=f.id
														WHERE af.activityid = {$data['activity']['id']}  
														ORDER BY f.name;");
													
		$data['activity_parent']	= $H_DB->GetAll("SELECT a.* , ap.name as priority ,s.name as status,t.name as type, t.icon as icon
													 FROM h_activity a 
													 INNER JOIN h_activity_status s ON a.statusid=s.id								 
													 INNER JOIN h_activity_type t ON a.typeid=t.id
													 INNER JOIN h_activity_priority ap ON a.priorityid=ap.id													 
													 WHERE a.parent={$id} ORDER BY a.startdate
													");
		
		$data['total_timespent']	= $H_DB->GetField_sql("SELECT SUM(a.timespent) as field
													 FROM h_activity a WHERE a.parent={$id}");

		foreach($data['activity_parent'] as $key=>$parent){
			$data['activity_parent'][$key]['files'] = $H_DB->GetAll("SELECT f.name as file
														FROM h_files f 
														INNER JOIN h_activity_files af ON af.fileid=f.id
														WHERE af.activityid = {$parent['id']}  
														ORDER BY f.name;");
		}
		$data['academias'] = $LMS->getAcademys();
		$data['assigns_users'] = $H_DB->GetAll("SELECT DISTINCT ra.userid, GROUP_CONCAT(DISTINCT r.name SEPARATOR ' - ') as roles
																			 FROM h_role_assignments ra
																			 INNER JOIN h_role r ON ra.roleid=r.id 
																			 WHERE  r.id IN ({$HULK->roles_representantes})
																			 GROUP BY ra.userid ORDER BY r.name;");

		$data['activity_type']		= $H_DB->GetAll("SELECT * FROM h_activity_type ORDER BY sortorder ASC;");
		$data['activity_status']		= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$data['activity_priority']		= $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		$data['activity_category']		= $H_DB->GetAll("SELECT * FROM h_activity_category;");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function representante_update(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

			$H_USER->require_capability('activity/update');
			$H_USER->require_capability('activity/representante');
		
			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	= $_POST['contactid'];
			$activity['typeid'] 		= $_POST['typeid'];
			$activity['subject'] 		= $_POST['subject'];
			$activity['priorityid'] = $_POST['priorityid'];			
			$activity['summary'] 		= stripcslashes(nl2br($_POST['summary']));
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$activity['statusid'] 	= $_POST['statusid'];
			$activity['parent'] 		= $_POST['parent'];
			$activity['private'] 		= ($_POST['private'])?$_POST['private']:0;
			$activity['timespent'] 		= $_POST['timespent'];
			
			if(!$activity_file['activityid'] = $H_DB->insert("h_activity",$activity)){
				show_error("Error al insertar h_activity.");
			}
			$activity_parent['statusid'] = $_POST['statusid'];
			$activity_parent['assignto'] = $_POST['assignto'];
			$activity_parent['academyid'] = $_POST['academyid'];
			$activity_parent['priorityid'] = $_POST['priorityid'];
			$activity_parent['private']	= $_POST['update_private'];
			
			if(!$H_DB->update("h_activity",$activity_parent,"id = {$_POST['parent']}")){
				show_error("Error al actualizar h_activity.");
			}
			
			$userid = $H_DB->GetField('h_activity','userid',$activity['parent']);
			$startdate = date('d-m-Y',$H_DB->GetField('h_activity','startdate',$activity['parent']));
			$mail = new H_Mail();
			$cerrados = array(4,6,7,8,9,10);
			if (in_array($activity_parent['statusid'],$cerrados)){
			$mail->Subject("HELPDESK: Incidente {$activity['parent']} Cerrado");
			$mail->Body("<br/>Su incidente en Fundación Proydesa Help Desk ha sido cerrado.  Puede ver la solución a continuación o en: http://www.proydesa.org/crm/hd.php?v=details&id={$activity['parent']}
							<br/>
							<br/>DETALLES DEL INCIDENTE
							<p>---------------
							<br/>ID:  {$activity['parent']}
							<br/>Usuario: {$userid}
							<br/>Fecha: {$startdate}
							<br/>Título: {$H_DB->GetField('h_activity','subject',$activity['parent'])}
							</p> 
							<br/>SOLUCIÓN
							<br/>-----------
							<p>{$activity['summary']}
							</p>");			
			}else{
			$mail->Subject("HELPDESK: Incidente {$activity['parent']} Actualizado");
			$mail->Body("<br/>Su incidente en Fundación Proydesa Help Desk ha sido actualizado.  Puede verlo en: http://www.proydesa.org/crm/hd.php?v=details&id={$activity['parent']}
							<br/>
							<br/>DETALLES DEL INCIDENTE
							<p>---------------
							<br/>ID:  {$activity['parent']}
							<br/>Usuario: {$userid}
							<br/>Fecha: {$startdate}
							<br/>Título: {$H_DB->GetField('h_activity','subject',$activity['parent'])}
							</p> 
							<br/>DESCRIPCIÓN
							<br/>-----------
							<p>{$H_DB->GetField('h_activity','summary',$activity['parent'])}
							</p>
							<br/>NOTAS
							<br/>-----------
							<br/><a href='http://www.proydesa.org/crm/hd.php?v=details&id={$activity['parent']}'>Ver notas anteriores...</a>
							<br/>-----------
							<p>{$activity['summary']}
							</p>");
			}
			// Si la actualización no es privada le mando notificacón al usuario
			if ($activity['private']==0){
				$mail->AddAddress($LMS->GetField('mdl_user','email',$userid), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$userid));
			}
			
			// Si alguien tiene asignado el incidente le mando copia de la ntificación
			if ($activity_parent['assignto']!=0){
				$mail->AddAddress($LMS->GetField('mdl_user','email',$activity_parent['assignto']), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$activity_parent['assignto']));
			}
			
			// Si el inicdente lo actualizo un representante que no es ni el usuario que lo creo ni el que lo tiene asignado le mando una copia
			if (($activity['userid'] != $userid) AND ($activity['userid'] != $assignto)){
				$mail->AddAddress($LMS->GetField('mdl_user','email',$activity['userid']), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$activity['userid']));
			}
			
			$mail->Send();			
		
		 	$valida	=	"1";		
			$extensiones	=	array("exe","php");

			if (isset($_FILES["archivos"])) {
	   	
				foreach ($_FILES["archivos"]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {

						$files['userid']	= $H_USER->get_property('id');
						$files['name']		= $_FILES['archivos']['name'][$key];
						$files['size']		= $_FILES['archivos']['size'][$key];
						$files['type']		= $_FILES['archivos']['type'][$key];
						$temp_ext 				= explode(".",$files['name']);
						$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
						$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
						$files['date']		= time();

						foreach($extensiones as $ext){
							if($ext == $files['ext']) $valida = "0";
						}
					 	if($valida=="1"){
							if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key]))
							{
								if(!is_dir($files['locate'])) 	
									@mkdir($files['locate'], 0777);
								copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
								if(!$activity_file['fileid'] = $H_DB->insert("h_files",$files)){
									show_error("Error al insertar en h_files.");
								}else{
									if(!$fileid = $H_DB->insert("h_activity_files",$activity_file)){
										show_error("Error al insertar en h_activity_files.");
									}
								}
							}
						}
					}
				}
			}
			redireccionar("hd.php?v=representante-view&id={$_POST['parent']}");
			die();		

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function update(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


			$H_USER->require_capability('activity/update');

			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['summary'] 		= stripcslashes(nl2br($_POST['summary']));
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$activity['statusid'] 	= 1;
			$activity['priorityid'] = 1;			
			$activity['typeid'] 	= 3;			
			$activity['parent'] 	= $_POST['id'];
			$activity['private'] 	= ($_POST['private'])?1:0;
			$activity['timespent'] 	= 0;
			
			if(!$activity_file['activityid'] = $H_DB->insert("h_activity",$activity)){
				show_error("Error al insertar h_activity.");
			}else{
		
			
				$userid = $H_DB->GetField('h_activity','userid',$activity['parent']);
				$startdate = date('d-m-Y',$H_DB->GetField('h_activity','startdate',$activity['parent']));
				$mail = new H_Mail();
				
				$mail->Subject("HELPDESK: Incidente {$activity['parent']} Actualizado");
				$mail->Body("<br/>Su incidente en Fundación Proydesa Help Desk ha sido actualizado.  Puede verlo en: http://www.proydesa.org/crm/hd.php?v=details&id={$activity['parent']}
								<br/>
								<br/>DETALLES DEL INCIDENTE
								<p>---------------
								<br/>ID:  {$activity['parent']}
								<br/>Usuario: {$userid}
								<br/>Fecha: {$startdate}
								<br/>Título: {$H_DB->GetField('h_activity','subject',$activity['parent'])}
								</p> 
								<br/>DESCRIPCIÓN
								<br/>-----------
								<p>{$H_DB->GetField('h_activity','summary',$activity['parent'])}
								</p>
								<br/>NOTAS
								<br/>-----------
								<br/><a href='http://www.proydesa.org/crm/hd.php?v=details&id={$activity['parent']}'>Ver notas anteriores...</a>
								<br/>-----------
								<p>{$H_DB->GetField('h_activity','summary',$activity_file['activityid'])}
								</p>");
				
				// Si la actualización no es privada le mando notificacón al usuario
				if ($activity['private']==0){
					$mail->AddAddress($LMS->GetField('mdl_user','email',$userid), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$userid));
				}
				
				// Si alguien tiene asignado el incidente le mando copia de la ntificación
				$assignto =$H_DB->GetField('h_activity','assignto',$activity['parent']);
				if ($assignto!=0){
					$mail->AddAddress($LMS->GetField('mdl_user','email',$assignto), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$assignto));
				}
				
				// Si el inicdente lo actualizo un representante que no es ni el usuario que lo creo ni el que lo tiene asignado le mando una copia
				if (($activity['userid'] != $userid) AND ($activity['userid'] != $assignto)){
					$mail->AddAddress($LMS->GetField('mdl_user','email',$activity['userid']), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$activity['userid']));
				}
				
				$mail->Send();
				
		
				$ext_permitidas	=	explode(",",$HULK->hd_adjuntos_ext);

				if (isset($_FILES["archivos"])) {
			
					foreach ($_FILES["archivos"]["error"] as $key => $error) {
						if ($error == UPLOAD_ERR_OK) {

							$files['userid']	= $H_USER->get_property('id');
							$files['name']		= $_FILES['archivos']['name'][$key];
							$files['size']		= $_FILES['archivos']['size'][$key];
							$files['type']		= $_FILES['archivos']['type'][$key];
							$temp_ext 				= explode(".",$files['name']);
							$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
							$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
							$files['date']		= time();

							
							if(in_array($files['ext'],$ext_permitidas)){
								if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key]))
								{
									if(!is_dir($files['locate'])) 	
										@mkdir($files['locate'], 0777);
									copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
									if(!$activity_file['fileid'] = $H_DB->insert("h_files",$files)){
										show_error("Error al insertar en h_files.");
									}else{
										if(!$fileid = $H_DB->insert("h_activity_files",$activity_file)){
											show_error("Error al insertar en h_activity_files.");
										}
									}
								}
							}
						}
					}
				}		
				redireccionar("hd.php?v=details&id={$activity['parent']}");
				die();
			}
			die();
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function categories(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


		$H_USER->require_capability('hd/config');

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Administrar categorias"=>"#");

		// Genero o actualizo categorias
		if ($_GET['stage']=="update"){
			if ($_POST['id']>0){
				$H_DB->update('h_activity_category',$_POST,"id = {$_POST['id']}");
			}else{
				$H_DB->insert('h_activity_category',$_POST);
			}
			header("Location: hd.php?v=categories");
		}

		// Borrar categorias
		if ($_GET['stage']=="delete"){
			if ($_GET['id']>0){
				$H_DB->delete('h_activity_category_form',"categoryid = {$_GET['id']}");
				$H_DB->delete('h_activity_category',"id = {$_GET['id']}");
			}
			header("Location: hd.php?v=categories");
		}
		$data['categorias']	= $H_DB->GetAll("SELECT * FROM h_activity_category ac ORDER BY ac.weight ASC;");
		
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function category_form(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$id	=	$_GET['id'];

		$H_USER->require_capability('hd/config');

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Administrar categorias"=>"hd.php?v=categories","{$H_DB->GetField('h_activity_category','name',$id)}"=>"#");

		// Genero o actualizo categorias
		if ($_GET['stage']=="update"){
			if ($_POST['id']>0){
				$H_DB->update('h_activity_category_form',$_POST,"id = {$_POST['id']}");
			}else{
				$H_DB->insert('h_activity_category_form',$_POST);
			}
			header("Location: hd.php?v=category_form&id={$id}");
		}

		// Borrar categorias
		if ($_GET['stage']=="delete"){
			if ($_GET['campoid']>0){
				$H_DB->delete('h_activity_category_form',"id = {$_GET['campoid']}");
			}
			header("Location: hd.php?v=category_form&id={$id}");
		}		
	
		$data['category']	= $H_DB->GetRow("SELECT ac.* FROM h_activity_category ac WHERE ac.id={$id};");
		$data['category_form']	= $H_DB->GetAll("SELECT acf.* FROM h_activity_category_form acf WHERE acf.categoryid={$id};");

		$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function details2(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

			$menuroot['ruta'] = array("Help Desk"=>"hd.php","Base de conocimientos"=>"hd.php?v=details2");

	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function lista(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


		$data['id'] = $id = $_REQUEST['id'];
		$data['academy'] = $academy = $_REQUEST['academy'];

		if(!$academy){
		if ($id){
			$WHERE = "WHERE (a.contactid={$id} OR a.userid={$id}) AND a.parent=0";
		}else{
			$WHERE = "WHERE a.parent=0 AND a.typeid!=4";		
		}
		$data['activitys']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																		   LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
																		    {$WHERE}
																		    AND a.subject NOT LIKE '%INSERT ->%'
																		    AND a.subject NOT LIKE '%UPDATE ->%'
																			AND a.subject NOT LIKE '%DELETE ->%'
																				ORDER BY a.startdate DESC, a.id DESC;");
		}else{
			$id=$academy;
			$data['u_admins'] =	$LMS->GetAll("SELECT u.id, CONCAT(lastname, ', ', firstname) AS nombre, r.name AS rol
																			FROM mdl_role_assignments ra
																			INNER JOIN mdl_user u ON u.id=ra.userid
																			INNER JOIN mdl_role r ON r.id=ra.roleid
																			WHERE ra.contextid=1 AND ra.roleid IN (8,9) AND ra.academyid={$id}
																			ORDER BY u.lastname ASC");
			if ($data['u_admins']){
				foreach($data['u_admins'] as $uadmin){
					$uadminslist[] = $uadmin['id'];
				}
				$uadminslist = implode(",",$uadminslist);
				$uadminslist = "(a.contactid IN ({$uadminslist})) OR";
			}																				
			$data['activitys']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename 
																					 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																					 INNER JOIN h_activity_type t ON a.typeid=t.id 
																					 WHERE ({$uadminslist} (a.contactid=0 AND a.academyid={$id}))
																					 AND a.subject NOT LIKE '%INSERT ->%'
																					 AND a.subject NOT LIKE '%UPDATE ->%'
																					 AND a.subject NOT LIKE '%DELETE ->%'
																					 AND a.parent=0
																					ORDER BY a.startdate DESC,a.id DESC;");																				
		}
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function listaplus(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$data['id'] = $id = $_REQUEST['id'];
		$data['academy'] = $academy = $_REQUEST['academy'];

		// Módulo de actividad
		if ($_REQUEST['action']=='newactivity'){

			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	= $id;
			$activity['typeid'] 		= $_POST['typeid'];
			$activity['subject'] 		= $_POST['subject'];
			$activity['summary'] 		= stripcslashes(nl2br($_POST['summary']));
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$activity['statusid'] 	= $_POST['statusid'];
			

		 	$valida	=	"1";		
			$extensiones	=	array("exe","php");

			if (isset($_FILES["archivos"])) {
	   	
				foreach ($_FILES["archivos"]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {

						$files['userid']	= $H_USER->get_property('id');
						$files['name']		= $_FILES['archivos']['name'][$key];
						$files['size']		= $_FILES['archivos']['size'][$key];
						$files['type']		= $_FILES['archivos']['type'][$key];
						$temp_ext 				= explode(".",$files['name']);
						$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
						$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
						$files['date']		= time();

						foreach($extensiones as $ext){
							if($ext == $files['ext']) $valida = "0";
						}
					 	if($valida=="1"){
							if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key]))
							{
								if(!is_dir($files['locate'])) 	
									@mkdir($files['locate'], 0777);
								copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
								if(!$activity_file['fileid'] = $H_DB->insert("h_files",$files)){
									show_error("Error al insertar en h_files.");
								}else{
									if(!$fileid = $H_DB->insert("h_activity_files",$activity_file)){
										show_error("Error al insertar en h_activity_files.");
									}
								}
							}
						}
					}
				}
			}
			if(!$activity_file['activityid'] = $H_DB->insert("h_activity",$activity)){
				show_error("Error al actualizar el campo.");
			}elseif ($activity['typeid']=="2"){
			
				//header("mailto:{$LMS->GetField('mdl_user','email',$activity['contactid'])}?subject={$activity['subject']}&body={$activity['summary']};"); 
				//$view->js("window.location='mailto:{$LMS->GetField('mdl_user','email',$activity['contactid'])}?subject={$activity['subject']}&body={$activity['summary']}';");
			
				$mail = new H_Mail();
				$mail->Subject(utf8_decode("{$activity['subject']}"));
				$mail->Body(utf8_decode("<p>{$activity['summary']}</p>"));
				$mail->AddAddress($LMS->GetField('mdl_user','email',$activity['userid']), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$activity['userid']));
				$mail->AddAddress($LMS->GetField('mdl_user','email',$activity['contactid']), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$activity['contactid']));
				$mail->CharSet = 'UTF-8';
				$mail->Send();
			}			
			header("Location: {$HULK->STANDARD_SELF}"); 
			$view->js("window.location='{$HULK->STANDARD_SELF}';");
		  exit;
		}		
		$data['activity_type']			= $H_DB->GetAll("SELECT * FROM h_activity_type;");
		$data['activity_status']		= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$data['activity_priority']	= $H_DB->GetAll("SELECT * FROM h_activity_priority;");			

		if(!$academy){
			if ($id){
				$WHERE = "WHERE (a.contactid={$id} OR a.userid={$id}) AND a.parent=0";
			}else{
				$WHERE = "WHERE a.parent=0 AND a.typeid!=4";		
			}
			
	
			$data['activitys']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority
																			   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																			   INNER JOIN h_activity_type t ON a.typeid=t.id 
																			   LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
																				{$WHERE}
																				AND a.subject NOT LIKE '%INSERT ->%'
																				AND a.subject NOT LIKE '%UPDATE ->%'
																				AND a.subject NOT LIKE '%DELETE ->%'
																					ORDER BY a.startdate DESC, a.id DESC;");
		}else{
			$id=$academy;
			$data['u_admins'] =	$LMS->GetAll("SELECT u.id, CONCAT(lastname, ', ', firstname) AS nombre, r.name AS rol
																			FROM mdl_role_assignments ra
																			INNER JOIN mdl_user u ON u.id=ra.userid
																			INNER JOIN mdl_role r ON r.id=ra.roleid
																			WHERE ra.contextid=1 AND ra.roleid IN (8,9) AND ra.academyid={$id}
																			ORDER BY u.lastname ASC");
			if ($data['u_admins']){
				foreach($data['u_admins'] as $uadmin){
					$uadminslist[] = $uadmin['id'];
				}
				$uadminslist = implode(",",$uadminslist);
				$uadminslist = "(a.contactid IN ({$uadminslist})) OR";
			}																				
			$data['activitys']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename 
																					 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																					 INNER JOIN h_activity_type t ON a.typeid=t.id 
																					 WHERE ({$uadminslist} (a.contactid=0 AND a.academyid={$id}))
																					 AND a.subject NOT LIKE '%INSERT ->%'
																					 AND a.subject NOT LIKE '%UPDATE ->%'
																					 AND a.subject NOT LIKE '%DELETE ->%'
																					 AND a.parent=0
																					ORDER BY a.startdate DESC,a.id DESC;");																				
		}

	$view->Load('header');
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
///////////////////////////////////////////////////////////////////////////////
/////////////////////// EDIT RODO 02/03/2017 //////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
function lista_notification(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

	$data['idUsers'] = isset($_REQUEST['id']) ? $_REQUEST['id'] : '';
	$data['userid'] = $H_USER->get_property('id');

	$arrIDs = isset($_REQUEST['id']) ? explode(',',$_REQUEST['id']) : array();
	if ($arrIDs[0]=="") unset($arrIDs[0]);

	if(count($arrIDs)){
		foreach ($arrIDs as $ki=>$vi) {
				$data['rowusers'][] = $LMS->getUser($vi);
		}		
	}else{
		show_error("Array vacio","No hay usuarios para notificar.");
		die();
	}
	$sent = false;

	/////////////////// SEND MAILS //////////////////
	if($_POST['action'] == 'newactivity'){

		$subject = $_POST['subject'];
		$summary = stripcslashes(nl2br($_POST['summary']));

		$mail = new H_Mail();
		$mail->Subject(utf8_decode("{$subject}"));
		$mail->Body(utf8_decode("<p>{$summary}</p>"));
		$mail->AddAddress('fundacion@fproydesa.org', 'Fundación Proydesa');
		if(count($data['rowusers'])){
			foreach ($data['rowusers'] as $ki=>$vi){
				$mail->AddBCC($vi['email'], $vi['firstname'].' '.$vi['lastname']);
			}
		}
		//$mail->AddAddress($LMS->GetField('mdl_user','email',$data['userid']), $LMS->GetField('mdl_user','CONCAT(firstname," ",lastname)',$data['userid']));
		$mail->CharSet = 'UTF-8';
		if($mail->Send()){
			$sent = true;
		}
		//header("Location: {$HULK->STANDARD_SELF}"); 
		//$view->js("window.location='{$HULK->STANDARD_SELF}';");

	}
	/////////////////////////////////////////////////

	$data['activity_type'] = $H_DB->GetAll("SELECT * FROM h_activity_type WHERE id=2;");

	$view->Load('header');
	if(!$sent){
		if(empty($print)) $view->Load('menuroot',$menuroot);
		$view->Load('hd/'.$v, $data);		
	}else{
		echo '<p>Email enviado exitosamente!</p>';
	}
	if(empty($print)) $view->Load('footer');
}
///////////////////////////////////////////////////////////////////////////////
function lista_report(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


		$data['id'] = $id = $_REQUEST['id'];

		if ($id){
			$WHERE = "WHERE (a.contactid={$id} OR a.userid={$id}) AND a.parent=0 AND a.typeid!=4";
		}else{
			$WHERE = "WHERE a.parent=0 AND a.typeid!=4";		
		}
		$data['activitys']	= $H_DB->GetAll("SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority 
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																			 LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
																		   {$WHERE}
																		   AND a.academyid IN (24,22,23)
																		   AND a.startdate > 1325379661
																				ORDER BY a.startdate ASC;");
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}
function timetrack(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


		$data['id'] = $id = $_REQUEST['id'];

		if($id){
			$WHERE = "WHERE a.userid={$id} AND a.typeid NOT IN (4)";
		}else{
			$WHERE = "WHERE a.userid={$H_USER->get_property('id')} AND a.typeid NOT IN (4)";
		}
		$sql = "SELECT a.*,s.name as status,t.name as typename, t.icon as icon, ap.name as priority, c.name as catname, c.summary as catsummary
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																		    LEFT JOIN h_activity_category c ON a.categoryid = c.id 
																			LEFT JOIN h_activity_priority ap ON ap.id=a.priorityid
																		   {$WHERE}
																			ORDER BY a.startdate DESC;";
		$data['activitys']	= $H_DB->GetAll($sql);
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function axlist(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';


		$act = $_GET;

		if($act['t']=="incidentes"){
			$WHERE .= "WHERE (a.assignto={$H_USER->get_property('id')}) AND a.parent=0 AND a.typeid IN (5)";	
		}elseif($act['t']=="notes"){
			$WHERE .= "WHERE a.userid={$H_USER->get_property('id')} AND a.parent=0 AND a.typeid IN (3)";			
		}elseif($act['t']=="log"){
			$WHERE .= "WHERE (a.contactid={$H_USER->get_property('id')} OR a.userid={$H_USER->get_property('id')})
											 AND a.parent=0 AND a.typeid IN (1,2,4)";	
		}
		
		
		$registro = $act['p']*5;
		$LIMIT = "LIMIT {$registro},5";
		
		$data['sig']	= "hd.php?v=axlist&t={$act['t']}&p=".($act['p']+1);
		if ($act['p']>0) $data['ant']	= "hd.php?v=axlist&t={$act['t']}&p=".($act['p']-1);
		$data['t']		=	$act['t'];
		$data['p']		=	$act['p'];
		//alan 22/11/2012
		$SQL="SELECT a.*,s.name as status,t.name as type, t.icon as icon, t.name as typename
																		   FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																		   INNER JOIN h_activity_type t ON a.typeid=t.id 
																			{$WHERE}
																			AND a.subject NOT LIKE '%INSERT ->%'
																			AND a.subject NOT LIKE '%UPDATE ->%'
																			AND a.subject NOT LIKE '%DELETE ->%'
																			ORDER BY a.startdate DESC,a.id DESC {$LIMIT}";
		//alan 22/11/2012																	 
		$data['activitys']	= $H_DB->GetAll($SQL);
		//$view->Load('header',$data);
		$view->Load('hd/axlist',$data);	
		die();
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function soporte(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$menuroot['ruta'] = array("Help Desk"=>"hd.php","Soporte en vivo"=>"#");		
						
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);	
}	
function index(){

	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	require_once 'config.php';

		$v	=	'index';
		$menuroot['ruta'] = array("Help Desk"=>"#");		
		$data['ultima_act']	= $H_DB->GetRow("SELECT a.subject,a.id FROM h_activity a
																		   WHERE a.parent=0 AND a.typeid!=4 AND a.userid={$H_USER->get_property('id')}
																				ORDER BY a.startdate DESC,a.id DESC ");
	$view->Load('header');
	if(empty($print)) $view->Load('menu',$data);
	if(empty($print)) $view->Load('menuroot',$menuroot);
	$view->Load('hd/'.$v, $data);																					
}	
function widget_activity(){
	
	global $HULK,$LMS,$H_DB,$H_USER,$view; $data['v'] = $v = $_REQUEST['v'];
	
	require_once 'config.php';

		if ($_REQUEST['id']){
			$id = $_REQUEST['id'];
			$H_USER->require_capability('contact/view');
		}else{
			$id = $H_USER->get_property('id');		
		}
			
		// Módulo de actividad
		if ($_REQUEST['action']=='newactivity'){

			$activity['userid'] 		= $H_USER->get_property('id');
			$activity['contactid']	= $id;
			$activity['typeid'] 		= $_POST['typeid'];
			$activity['subject'] 		= $_POST['subject'];
			$activity['summary'] 		= stripcslashes(nl2br($_POST['summary']));
			$activity['startdate'] 	= time();
			$activity['enddate']	 	= time();
			$activity['statusid'] 	= $_POST['statusid'];
			
			if(!$activity_file['activityid'] = $H_DB->insert("h_activity",$activity)){
				show_error("Error al actualizar el campo.");
			}

		 	$valida	=	"1";		
			$extensiones	=	array("exe","php");

			if (isset($_FILES["archivos"])) {
	   	
				foreach ($_FILES["archivos"]["error"] as $key => $error) {
					if ($error == UPLOAD_ERR_OK) {

						$files['userid']	= $H_USER->get_property('id');
						$files['name']		= $_FILES['archivos']['name'][$key];
						$files['size']		= $_FILES['archivos']['size'][$key];
						$files['type']		= $_FILES['archivos']['type'][$key];
						$temp_ext 				= explode(".",$files['name']);
						$files['ext'] 		= strtolower($temp_ext[count($temp_ext)-1]);
						$files['locate']	= "{$HULK->dataroot}\\users\\{$files['userid']}";
						$files['date']		= time();

						foreach($extensiones as $ext){
							if($ext == $files['ext']) $valida = "0";
						}
					 	if($valida=="1"){
							if (is_uploaded_file($_FILES['archivos']['tmp_name'][$key]))
							{
								if(!is_dir($files['locate'])) 	
									@mkdir($files['locate'], 0777);
								copy($_FILES['archivos']['tmp_name'][$key], "{$files['locate']}\\{$files['name']}");
								if(!$activity_file['fileid'] = $H_DB->insert("h_files",$files)){
									show_error("Error al insertar en h_files.");
								}else{
									if(!$fileid = $H_DB->insert("h_activity_files",$activity_file)){
										show_error("Error al insertar en h_activity_files.");
									}
								}
							}
						}
					}
				}
			}
			header("Location: {$HULK->STANDARD_SELF}"); 
		  exit;
		}

		$data['activity_type']			= $H_DB->GetAll("SELECT * FROM h_activity_type;");
		$data['activity_status']		= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$data['activity_priority']	= $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		//alan 22/11/2012
		$data['activitys']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename 
																				 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																				 INNER JOIN h_activity_type t ON a.typeid=t.id 
																				 WHERE a.contactid={$id} AND a.parent=0 AND a.typeid IN (4)
																				 AND a.subject NOT LIKE '%INSERT ->%'
																			     AND a.subject NOT LIKE '%UPDATE ->%'
																			     AND a.subject NOT LIKE '%DELETE ->%'		
																				 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,5;");
		//alan 22/11/2012																	 
																				 
		$data['notes']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename 
																				 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																				 INNER JOIN h_activity_type t ON a.typeid=t.id 
																				 WHERE a.contactid={$id} AND a.parent=0 AND a.typeid IN (1,2,3)
																				 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,5;");

		$data['incidentes']	= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename 
																				 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
																				 INNER JOIN h_activity_type t ON a.typeid=t.id 
																				 WHERE (a.contactid={$id} OR a.userid={$id}) AND a.parent=0 AND a.typeid IN (5)
																				 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,5;");

	
	
		$data['promosiones'] = $H_DB->GetAll("	SELECT DISTINCT
													co.detalle,
													co.takenby userid,
													co.date startdate
												FROM h_comprobantes co
													INNER JOIN h_comprobantes_cuotas cc ON co.id = cc.comprobanteid
													INNER JOIN h_cuotas cu ON cc.cuotaid = cu.id
												WHERE
													cu.userid={$id} and 
													co.detalle like '%Promo%' and cu.cuota=1
												ORDER BY co.date;");
	
		$data['id']=$id;																				 
		$view->Load('hd/widget_activity',$data);	
}
?>