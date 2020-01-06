<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase manejo del moodle

*/
class H_LMS extends H_LMS_CONN {

	var $tbl= array();
	var $base_crm;
	var $base_moodle;

	function H_LMS(){
		global $HULK;
		$this->base_crm = $HULK->dbname;
		$this->base_moodle = $HULK->lms_dbname;

		$this->tbl['course']			=	$this->base_moodle.'.mdl_course';
		$this->tbl['course_categories']	=	$this->base_moodle.'.mdl_course_categories';
		$this->tbl['user']				=	$this->base_moodle.'.mdl_user';
		$this->tbl['role_assignments']	=	$this->base_moodle.'.mdl_role_assignments';
	}
	// Funciones de academias
	function getAcademys($q="",$limit = 999,$inicio = 0,$params=NULL){
		$result = $this->GetAll("SELECT DISTINCT a.* FROM mdl_proy_academy a
						WHERE a.deleted = 0
						{$q}
						ORDER BY a.country,a.name
						LIMIT {$inicio},{$limit};");
		return $result;
	}
	function getAcademy($id){
		if($result = $this->GetRow("SELECT a.* FROM mdl_proy_academy a
									WHERE a.id={$id};")){
			return $result;
		}else{
			return false;
		}
	}
	function getAcademyCtx($academyid){
		$categoryid = $this->getField("mdl_proy_academy","categoryid",$academyid);
		$context	= $this->getRow("SELECT ctx.id FROM mdl_context ctx WHERE ctx.contextlevel=40 AND ctx.instanceid={$categoryid};");
		return $context['id'];
	}
	function getContextAcad($contextid){
		$categoryid	= $this->getField("mdl_context","instanceid",$contextid);
		$academyid	= $this->getField("mdl_proy_academy","id",$categoryid,"categoryid");
		return $academyid;
	}
	// Discontinuado, ya no hay categorizas de convenio dentro de la academia.
	// recibe id de academia y id del convenio (subcategoria de la categoria de cursos modelo) y devuelve el id de la categoria correspondiente a esa academia.
	function getAcademyConvenioId($academyid,$convenioid){
		$categoryid= $this->GetField_sql("SELECT ac.categoryid FROM mdl_proy_academy_convenio ac
											WHERE ac.convenioid={$convenioid}
											AND ac.academyid={$academyid};");
		return $categoryid;
	}
	// Devuelve el id de la categoria de la academia en moodle.
	function getAcademyCategory($academyid){
		$categoryid= $this->GetField_sql("SELECT a.categoryid FROM mdl_proy_academy a
											WHERE a.id={$academyid};");
		return $categoryid;
	}
	// Recibe la categoria del curso modelo y devuelve id de convenio
	function getConvenioId_fromCategory($modelo_categoria){
		$convenioid= $this->GetField_sql("SELECT c.id FROM mdl_proy_convenios c
											WHERE c.categoryid={$modelo_categoria};");
		return $convenioid;
	}
	function getAcademyAdmins($id){
		$result =	$this->GetAll("SELECT u.id, CONCAT(lastname, ', ', firstname) AS nombre, r.shortname AS rol
									FROM {$this->tbl['role_assignments']} ra
									INNER JOIN mdl_user u ON u.id=ra.userid
									INNER JOIN mdl_role r ON r.id=ra.roleid
									INNER JOIN mdl_context ctx ON ctx.id = ra.contextid
									WHERE ctx.contextlevel=40 AND ctx.depth=2 AND ctx.instanceid={$this->getField("mdl_proy_academy","categoryid",$id)}
									AND r.id!=2
									ORDER BY u.lastname ASC");
		return $result;
	}
	function getAcademyInstructor($academyid,$curso=NULL){

		if($curso){
			$curso_modelo = "AND ins.courseid={$curso}";
		}

		$result =	$this->GetAll("SELECT u.id, CONCAT(u.lastname, ', ', u.firstname) AS fullname, r.name AS rol, cc.name, GROUP_CONCAT(ins.courseid) as cursos
									FROM {$this->tbl['role_assignments']} ra
									INNER JOIN mdl_user u ON u.id=ra.userid
									INNER JOIN mdl_role r ON r.id=ra.roleid
									INNER JOIN mdl_context ctx ON ctx.id = ra.contextid
									INNER JOIN mdl_course_categories cc ON cc.id = ctx.instanceid
									LEFT JOIN mdl_proy_instructores ins ON u.id=ins.userid
									WHERE ctx.contextlevel=40 AND ctx.depth=2 AND ctx.instanceid={$this->getField("mdl_proy_academy","categoryid",$academyid)}
									AND r.id=2 {$curso_modelo}
									GROUP BY u.id
									ORDER BY u.lastname, ins.courseid ASC");
		return $result;
	}
	function getCursosModelo(){
		global $HULK;
		// Devuelve los cursos modelo
		$result		= $this->GetAll("SELECT c.id, c.fullname, c.shortname
									FROM mdl_course c
									INNER JOIN {$this->tbl['course_categories']} cc ON c.category = cc.id
					 				WHERE cc.parent = {$HULK->cat_cursos_modelo}
			   						ORDER BY c.fullname;");
		return $result;
	}
	function getCategories($id){
		$result		= $this->GetAll("SELECT  cc.id
									FROM {$this->tbl['course_categories']} cc
					 				WHERE cc.parent={$this->getField("mdl_proy_academy","categoryid",$id)};");
		if ($result){
			foreach($result as $row){
				$cat[] = $row['id'];
			}
			return implode($cat,",");
		}else{
			return false;
		}

	}
	function getAcademyCourse($academyid,$periodo=0,$modelo=0){
		global $HULK;
		if ($modelo>0){
			$dondemodeloes = "AND from_courseid={$modelo}";
		}
		if ($periodo>0){
			$dondeperiodoes="AND c.periodo={$periodo}";
		}
		$result		= $this->GetAll("SELECT c.id, c.fullname AS course, c.intensivo, c.startdate, c.enddate
										FROM {$HULK->dbname}.vw_course c
						 				WHERE c.academyid = {$academyid}
						 				{$dondeperiodoes}
						 				{$dondemodeloes}
			   						ORDER BY c.periodo, course;");
		return $result;
	}
	function getAcademyConvenios($id,$list=0,$field='id'){
		$result		= $this->GetAll("SELECT  ac.id, ac.convenioid,ac.categoryid, c.fullname, cc.visible, ac.startdate, ac.enddate, ac.forma_de_pago
									FROM mdl_proy_convenios c
									INNER JOIN mdl_proy_academy_convenio ac ON ac.convenioid = c.id
									INNER JOIN mdl_course_categories cc ON cc.id = ac.categoryid
					 				WHERE ac.academyid={$id};");
		if($list==0){
			return $result;
		}else{
			foreach($result as $row){
				$resultlist[]=$row[$field];
			}
			return $resultlist;
		}
	}
	function getAcademyConveniosActivos($id,$periodo,$list=0){
		if ($periodo==0){
			$periodo=172;
		}
		$result		= $this->GetAll("	SELECT  ac.id, ac.convenioid,ac.categoryid, c.fullname, cc.visible, ac.startdate, ac.enddate, ac.forma_de_pago, COUNT(cou.id)
												FROM mdl_proy_convenios c
												INNER JOIN mdl_proy_academy_convenio ac ON ac.convenioid = c.id
												INNER JOIN mdl_course_categories cc ON cc.id = ac.categoryid
			                  INNER JOIN mdl_course cou ON cou.convenioid=c.id
								 				WHERE ac.academyid={$id} AND cou.academyid={$id} AND cou.periodo={$periodo} GROUP BY (c.id);");
		if($list==0){
			return $result;
		}else{
			foreach($result as $row){
				$resultlist[]=$row[$field];
			}
			return $resultlist;
		}
	}

	function setAcademy($academy){

		$academy = array_map(utf8_decode,$academy);
		$this->update("mdl_proy_academy",$academy,"id = {$academy['id']}");

		if($academy['categoryid']>0){
			$this->update($this->tbl['course_categories'], array("name"=>$academy['name']),"id = {$academy['categoryid']}");
		}else{
			if($this->record_exists($this->tbl['course_categories'],$academy['name'],'name')){
				$categoryid=$this->getField($this->tbl['course_categories'], "id", $academy['name'],'name');
				$this->update("mdl_proy_academy", array("categoryid"=>$categoryid),"id = {$academy['id']}");
			}else{
				$this->setAcademyCategory($academy['id']);
			}
		}
		return true;
	}
	function newAcademy($academy){
		$academy['timemodified']=time();
		$id = $this->insert("mdl_proy_academy",$academy);

		$this->setAcademyCategory($id);

		return $id;
	}
	function setAcademyCategory($academyid){
		$category['name']=utf8_decode($this->getField("mdl_proy_academy", "name", $academyid));
		$category['idnumber']="";
		$category['description']="";
		$category['descriptionformat']=1;
		$category['parent']=0;
		$category['sortorder']=90000;
		$category['coursecount']=0;
		$category['visible']=1;
		$category['visibleold']=1;
		$category['timemodified']=time();
		$category['depth']=1;
		$category['path']="";
		$category['theme']=NULL;

		$categoryid = $this->insert($this->tbl['course_categories'],$category);
		$category['path']="/".$categoryid;

		$this->update($this->tbl['course_categories'], $category,"id = {$categoryid}");
		$this->update("mdl_proy_academy", array("categoryid"=>$categoryid),"id = {$academyid}");

		// Contexto de categoria
		$context['contextlevel']=40;
		$context['instanceid']=$categoryid;
		$context['path']="/1/";
		$context['depth']=2;
		$contextid = $this->insert("mdl_context",$context);
		$context['path']="/1/".$contextid;
		$this->update("mdl_context", $context,"id = {$contextid}");

		return true;
	}
	function setConvenio($academyid,$convenioid){

		$parent = $this->getField("mdl_proy_academy","categoryid",$academyid);
		$category['name']=$this->getField("mdl_proy_convenios", "shortname", $convenioid);

		if($this->record_exists_sql("SELECT cc.id FROM mdl_course_categories cc WHERE cc.name LIKE '{$category['name']}' AND cc.parent={$parent};")){
			return $this->GetField_sql("SELECT cc.id FROM mdl_course_categories cc WHERE cc.name LIKE '{$category['name']}' AND cc.parent={$parent};");
		}
		$category['idnumber']="";
		$category['description']="";
		$category['descriptionformat']=1;
		$category['parent']= $parent;
		$sortorder=$this->GetField_sql("SELECT MAX(cc.sortorder) FROM mdl_course_categories cc WHERE cc.parent={$parent};");
		$category['sortorder']=$sortorder+10000;
		$category['coursecount']=0;
		$category['visible']=1;
		$category['visibleold']=1;
		$category['timemodified']=time();
		$category['depth']=2;
		$category['path']="";
		$category['theme']=NULL;

		$categoryid = $this->insert($this->tbl['course_categories'],$category);
		$category['path']="/".$parent."/".$categoryid;

		$this->update($this->tbl['course_categories'], $category,"id = {$categoryid}");

		// Contexto de categoria
		$context['contextlevel']=40;
		$context['instanceid']=$categoryid;
		$context['path']="/1/";
		$context['depth']=3;
		$contextid = $this->insert("mdl_context",$context);
		$context['path']="/1/".$contextid;
		$this->update("mdl_context", $context,"id = {$contextid}");
		return $categoryid;
	}

	function newConvenio($convenio){
		$id = $this->insert("mdl_proy_convenios",$convenio);
		$this->setConvenioCategory($id);

		return $id;
	}
	function setConvenioCategory($convenioid){
		$category['name']=utf8_decode($this->getField("mdl_proy_convenios", "fullname", $convenioid));
		$category['idnumber']="";
		$category['description']="";
		$category['descriptionformat']=1;
		$category['parent']=9;
		$category['sortorder']=90000;
		$category['coursecount']=0;
		$category['visible']=0;
		$category['visibleold']=1;
		$category['timemodified']=time();
		$category['depth']=2;
		$category['path']="/9";
		$category['theme']=NULL;

		$categoryid = $this->insert($this->tbl['course_categories'],$category);
		$category['path'] .="/".$categoryid;

		$this->update($this->tbl['course_categories'], $category,"id = {$categoryid}");
		$this->update("mdl_proy_convenios", array("categoryid"=>$categoryid),"id = {$convenioid}");

		// Contexto de categoria
		$context['contextlevel']=40;
		$context['instanceid']=$categoryid;
		$context['path']="/1/60";
		$context['depth']=3;
		$contextid = $this->insert("mdl_context",$context);
		$context['path'] .="/".$contextid;
		$this->update("mdl_context", $context,"id = {$contextid}");

		return true;
	}
	function getAcademyActivity($academy){
		global $H_DB;
		$result['u_admins']	=	$this->getAcademyAdmins($academy);

		if ($result['u_admins']){
			foreach($result['u_admins'] as $uadmin){
				$uadminslist[] = $uadmin['id'];
			}
			$uadminslist = implode(",",$uadminslist);
			$uadminslist = "(a.contactid IN ({$uadminslist})) OR";
		}

		$result['activity_type']		= $H_DB->GetAll("SELECT * FROM h_activity_type;");
		$result['activity_status']		= $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		$result['activity_priority']	= $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		$result['activitys']			= $H_DB->GetAll("SELECT a.*, s.name as status,t.name as type, t.icon as icon, t.name as typename
												 FROM h_activity a INNER JOIN h_activity_status s ON a.statusid=s.id
												 INNER JOIN h_activity_type t ON a.typeid=t.id
												 WHERE ({$uadminslist} (a.academyid={$academy}))
												 AND a.subject NOT LIKE '%INSERT ->%'
												 AND a.subject NOT LIKE '%UPDATE ->%'
												 AND a.subject NOT LIKE '%DELETE ->%'
												 AND a.parent=0
												 ORDER BY a.startdate DESC,a.id DESC LIMIT 0,10;");
		return $result;
	}
	/////////// EDIT 30.03.2017 //////////////////
	function getActivitiesByGroup($start='',$end='',$idacademies=array(),$view='dates',$idusers=array(),$search){
		global $H_DB;
		$where = "WHERE academyid != 0 AND (subject NOT LIKE '%INSERT ->%' AND subject NOT LIKE '%UPDATE ->%' AND subject NOT LIKE '%DELETE ->%') AND parent = 0";

		if(count($idacademies)){
			$where .= " AND academyid IN (".implode(',',$idacademies).")";
		}
		if(count($idusers)){
			$where .= " AND userid IN (".implode(',',$idusers).")";
		}
		if(!empty($start) && !empty($end)){
			$where .= " AND (startdate >= {$start} AND startdate <= {$end})";
		}
		if(!empty($search)){
			$where .= " AND (";
			$q = explode(' ',$search);
			foreach ($q as $ks=>$vs) {
				$where .= "subject LIKE '%{$vs}%' OR summary LIKE '%{$vs}%'";
				if(count($q) != $ks+1){
					$where .= " OR ";
				}else{
					$where .= ")";
				}
			}
		}

		$result['activity_type'] = $H_DB->GetAll("SELECT * FROM h_activity_type;");
		$result['activity_status'] = $H_DB->GetAll("SELECT * FROM h_activity_status ORDER BY id;");
		//$result['activity_priority'] = $H_DB->GetAll("SELECT * FROM h_activity_priority;");
		if($view == 'academy'){
			$sql = "SELECT academyid FROM h_activity {$where} GROUP BY academyid ORDER BY startdate DESC LIMIT 0,50";
		}else{
			$sql = "SELECT startdate, FROM_UNIXTIME(startdate, '%d/%m/%Y') fecha FROM h_activity {$where} GROUP BY FROM_UNIXTIME(startdate, '%d/%m/%Y') ORDER BY startdate DESC LIMIT 0,50";
		}
		$result['activities'] = $H_DB->GetAll($sql);
		return $result;
	}
	///////////// EDIT 12.04.2017 //////////////////
	function getActivitiesByDate($date='',$idacademies=array(),$idusers=array(),$search){
		global $H_DB;
		///$date = date('Y-m-d',$date);
		if (empty($date)) $date=time()-(31*24*60*60);
		$where = "WHERE FROM_UNIXTIME(startdate,'%Y-%m-%d')=FROM_UNIXTIME({$date},'%Y-%m-%d') AND academyid != 0 AND (subject NOT LIKE '%INSERT ->%' AND subject NOT LIKE '%UPDATE ->%' AND subject NOT LIKE '%DELETE ->%') AND parent = 0";
		if(count($idacademies)){
			$where .= " AND academyid IN (".implode(',',$idacademies).")";
		}
		if(count($idusers)){
			$where .= " AND userid IN (".implode(',',$idusers).")";
		}
		if(!empty($search)){
			$where .= " AND (";
			$q = explode(' ',$search);
			foreach ($q as $ks=>$vs) {
				$where .= "subject LIKE '%{$vs}%' OR summary LIKE '%{$vs}%'";
				if(count($q) != $ks+1){
					$where .= " OR ";
				}else{
					$where .= ")";
				}
			}
		}
		$sql = "SELECT * FROM h_activity {$where} ORDER BY academyid ASC LIMIT 0,50";
		$result = $H_DB->GetAll($sql);
		return $result;
	}
	function getActivitiesByAcademy($academyid, $start='', $end='',$idusers=array(),$search){
		global $H_DB;

		$where = "WHERE academyid={$academyid} AND (subject NOT LIKE '%INSERT ->%' AND subject NOT LIKE '%UPDATE ->%' AND subject NOT LIKE '%DELETE ->%') AND (startdate >= {$start} AND startdate <= {$end}) AND parent = 0";
		if(count($idusers)){
			$where .= " AND userid IN (".implode(',',$idusers).")";
		}
		if(!empty($search)){
			$where .= " AND (";
			$q = explode(' ',$search);
			foreach ($q as $ks=>$vs) {
				$where .= "subject LIKE '%{$vs}%' OR summary LIKE '%{$vs}%'";
				if(count($q) != $ks+1){
					$where .= " OR ";
				}else{
					$where .= ")";
				}
			}
		}
		$sql = "SELECT * FROM h_activity a {$where} LIMIT 0,50";
		$result = $H_DB->GetAll($sql);
		return $result;
	}
	function getFiles($activityid){
		global $H_DB;
		$result = $H_DB->GetAll("SELECT * FROM h_activity_files af LEFT JOIN h_files f ON f.id=af.fileid WHERE activityid={$activityid}");
		return $result;
	}
	function getActivityChildren($idactivity){
		global $H_DB;
		$result = $H_DB->GetAll("SELECT * FROM h_activity WHERE parent={$idactivity}");
		return $result;
	}
	///////////////////////////////////////////////
	///////////////////////////////////////////////
	///////////////////////////////////////////////
	///////////////////////////////////////////////
	function getUsersActivities(){
		global $HULK;

		$where = "WHERE a.academyid != 0 AND a.userid != 0 AND username != '' AND (a.subject NOT LIKE '%INSERT ->%' AND a.subject NOT LIKE '%UPDATE ->%' AND a.subject NOT LIKE '%DELETE ->%')";

		$result = $this->GetAll("SELECT a.userid, CONCAT(u.lastname, ', ', u.firstname) username FROM {$HULK->dbname}.h_activity a LEFT JOIN mdl_user u ON u.id=a.userid {$where} GROUP BY a.userid ORDER BY username ASC");
		return $result;
	}
	/////////// END EDIT //////////////////

	function getInstructores(){

		return $this->GetAll("SELECT i.userid, CONCAT(u.lastname, ', ', u.firstname) AS username, i.courseid, c.fullname, i.startdate
							FROM mdl_proy_instructores i
							INNER JOIN mdl_user u ON i.userid=u.id
							INNER JOIN mdl_course c ON i.courseid=c.id
							ORDER BY i.startdate DESC;");

	}
	// Funciones de convenios
	function getConvenios(){
		global $HULK;
		$result		= $this->GetAll("SELECT  c.*
									FROM mdl_proy_convenios c ORDER BY c.weight;");
		return $result;
	}
	function getConvenio($id){
		$result		= $this->GetRow("SELECT  c.*
									FROM mdl_proy_convenios c
					 				WHERE c.id={$id};");
		return $result;
	}
	function getConvenioIdnumber($id){
		return $id+1000;
	}
	function getConvenioCourse($convenio){

		$result		= $this->GetAll("SELECT c.id, c.fullname, c.shortname, c.intensivo
									FROM mdl_course c
									INNER JOIN mdl_proy_convenios co ON co.categoryid = c.category
					 				WHERE co.id IN ({$convenio})
		   						ORDER BY c.shortname;");
		return $result;
	}
	// Devuelve los cursos en los cuales la academia tienen generadas comisiones para ese periodo en ese convenio
	function getConvenioCourseActivos($convenio,$periodo,$academy){

		$result		= $this->GetAll("SELECT DISTINCT c.id, c.fullname, c.shortname, c.intensivo
									FROM mdl_course c
									INNER JOIN mdl_proy_convenios co ON co.categoryid = c.category
									INNER JOIN mdl_course comi ON comi.from_courseid = c.id
					 				WHERE co.id={$convenio} AND comi.periodo={$periodo} AND comi.academyid={$academy}
		   						ORDER BY c.shortname;");
		return $result;
	}
	// Devuelve los cursos en los cuales la academia tienen generadas comisiones para ese periodo
	function getModelCourseActivos($periodo,$academy){
		$result		= $this->GetAll("SELECT DISTINCT c.id, c.fullname, c.shortname, c.intensivo
									FROM mdl_course c
									INNER JOIN mdl_course comi ON comi.from_courseid = c.id
					 				WHERE comi.periodo={$periodo} AND comi.academyid={$academy}
		   						ORDER BY c.shortname;");
		return $result;
	}
	function getConvenioAcademys($convenioid){
		$result		= $this->GetAll("SELECT  ac.*, a.*
									FROM mdl_proy_academy_convenio ac
									INNER JOIN mdl_proy_academy a ON ac.academyid=a.id
					 				WHERE ac.convenioid={$convenioid};");
		return $result;
	}



	// Funciones de manejo de usuario
	function getUser($id){
		$result =	$this->GetRow("SELECT u.*
									FROM mdl_user u
									WHERE u.id={$id}");
		return $result;
	}
	function getUserRoles($id){
		$result =	$this->GetAll("SELECT cc.name as category, r.shortname AS rol
									FROM {$this->tbl['role_assignments']} ra
									INNER JOIN mdl_user u ON u.id=ra.userid
									INNER JOIN mdl_role r ON r.id=ra.roleid
									INNER JOIN mdl_context ctx ON ctx.id = ra.contextid
									INNER JOIN {$this->tbl['course_categories']} cc ON cc.id=ctx.instanceid
									WHERE ctx.contextlevel=40 AND ctx.depth=2 AND u.id={$id}
									ORDER BY u.lastname ASC");
		return $result;
	}
	function getUserCourse($id){
		global $HULK;
		$result =	$this->GetAll("SELECT c.id, c.fullname AS course, r.name AS rol, e.roleid, c.periodo, c.from_courseid
									FROM {$HULK->dbname}.vw_enrolados e
									INNER JOIN mdl_course c ON c.id=e.id
									INNER JOIN mdl_role r ON e.roleid=r.id
									WHERE e.userid={$id} AND e.roleid=5
									ORDER BY periodo DESC, course, rol;");
		return $result;
	}
	function getUserCourseInstructor($id){
		global $HULK;
		$result =	$this->GetAll("SELECT c.id, c.fullname AS course, r.shortname AS rol, e.roleid, c.periodo
														FROM {$HULK->dbname}.vw_enrolados e
														INNER JOIN mdl_course c ON c.id=e.id
														INNER JOIN mdl_role r ON e.roleid=r.id
														WHERE e.userid={$id} AND e.roleid!=5
														ORDER BY course, rol;");
		return $result;
	}
	// Funciones de manejos de cursos
	function getCourse($id){
		global $HULK;
		if (!$result = $this->GetRow("SELECT c.* FROM {$HULK->dbname}.vw_course c WHERE c.id={$id};")){
			show_404();
		}
		return $result;
	}
	function getCourseStudents($id,$periodo=''){
		global $H_DB;
		global $HULK;
		// Traigo todas las bajas
		$bajas = $H_DB->GetOne("SELECT GROUP_CONCAT(userid) FROM h_bajas WHERE comisionid={$id} AND cancel=0 AND periodo='{$periodo}';");
		if($bajas){
			$WHERE .= " AND u.id NOT IN({$bajas})";
		}
		$result = $this->GetAll("SELECT
									u.id,
									u.username,
									CONCAT(u.lastname, ', ', u.firstname) AS fullname,
									CONCAT(u.firstname, ' ', u.lastname) AS namefull,
									e.timestart,
									u.email,
									u.phone2 as phone,
									u.obs,
									case
									  when u.autorizousoimg is null then 'N'
									  when u.autorizousoimg = 0 then 'N'
									  when u.autorizousoimg = 1 then 'S'
									end as autorizousoimg
								FROM {$HULK->dbname}.vw_enrolados e
								INNER JOIN mdl_user u ON e.userid = u.id
								WHERE e.roleid = 5 AND e.id = {$id}
								{$WHERE}
								ORDER BY u.lastname, u.firstname;");

		return $result;
	}
	function getCourseInstructors($id){
		global $HULK;
		$result = $this->GetAll("SELECT e.roleid, u.id, u.username, CONCAT(u.lastname, ', ', u.firstname) AS fullname
								FROM {$HULK->dbname}.vw_enrolados e
								INNER JOIN mdl_user u ON e.userid = u.id
								WHERE e.roleid IN(3,4,11) AND e.id = {$id};");
		return $result;
	}
	// Genera las asistencias de un curso donde $day es el vector con los numeros de dia de la semana seleccionados.
	function setCourseAttendance_old($courseid,$startdate,$enddate,$day){

		define('ONE_DAY', 86400);   // Seconds in one day

		define('ONE_WEEK', 604800);   // Seconds in one week

		// Le agrego un día a la fecha de finalización para incluirlo.

		$enddate += ONE_DAY;

		$days = (int) (floor(($enddate - $startdate) / ONE_DAY)) + 1;	// +1 is to include enddate

		if($days <= 0){

			show_error("Error de fechas","La fecha de cierre debe posterior a la fecha de inicio.");

		}

		$attendanceid = $this->getfield('mdl_attendance','id',$courseid,'course');

		// Adding sessions

		while ($startdate <= $enddate) {

			if(in_array(date('w',$startdate),$day)) {

				$rec = new stdClass();

				$rec->attendanceid = $attendanceid;

				$rec->sessdate = $startdate;

				$rec->description = '';

				$rec->descriptionformat = '1';

				if(!$this->insert('mdl_attendance_sessions', $rec)){

					show_error("Error de fecha", "Error al generar la session");

				}

			}

			$startdate += ONE_DAY;

		}

		return;

	}
	/*------- EDIT RODO - MARZO 2018 ----*/


	function getHolidays($date,$tech=false,$ignore_days=array()){

		global $H_DB;
		$date = date('Y',$date);
		$where = "(c.type='holiday' OR c.type='tech')";
		/*if(!$tech){
			$where .= "";
		}
		$where .= ")";*/

		if($tech){
			if(!empty($ignore_days)){
				foreach($ignore_days as $day){
					$where .= " AND c.date != '{$day}'";
				}
			}
		}

		$q = "SELECT c.date
			FROM h_calendario c
			WHERE YEAR(c.date) = '{$date}' AND {$where}
			ORDER BY c.date ASC";

		$result = $H_DB->GetAll($q);

		if(!$result) return false;
		$holidays = array();
		foreach($result as $dt){
			$holidays[] = $dt['date'];
		}
		return $holidays;

	}


	function setCourseAttendance($courseid=0,$startdate=0,$clases=1,$ignoretech=true,$daycodes='',$schedules='',$ignore_days=array()){

		global $H_DB;

		$attendanceid = $this->getfield('mdl_attendance','id',$courseid,'course');

		define('ONE_DAY', 86400);

		$arrdaycodes = array('D','L','M','W','J','V','S');

		$holidays = $this->getHolidays($startdate,$ignoretech,$ignore_days);

		$numclase = 1;
		$sumday = 0;
		$arr_days = explode(',',$daycodes);
		$arr_schedules = explode(',',$schedules);

		while ($numclase <= $clases){

			$currentdate = $startdate+(ONE_DAY*$sumday);

			if(in_array($arrdaycodes[date('w',$currentdate)], $arr_days) && !in_array(date('Y-m-d',$currentdate), $holidays ? $holidays : array())){

				$node = array_search($arrdaycodes[date('w',$currentdate)], $arr_days);
				$horarioid = $arr_schedules[$node];
				$q_horario = $H_DB->GetRow("SELECT inicio, fin FROM h_horarios WHERE id={$horarioid}");
				$horario_inicio = 0;
				$duration = 0;
				if($q_horario){
					$horario_inicio_parsed = date_parse($q_horario['inicio']);
					$horario_fin_parsed = date_parse($q_horario['fin']);
					$horario_inicio = ($horario_inicio_parsed['hour']*3600)+($horario_inicio_parsed['minute']*60);
					$horario_fin = ($horario_fin_parsed['hour']*3600)+($horario_fin_parsed['minute']*60);
					$duration = $horario_fin-$horario_inicio;
				}

				$rec = array();
				$rec['attendanceid'] = $attendanceid;
				$rec['sessdate'] = $currentdate+$horario_inicio;
				$rec['duration'] = $duration;
				$rec['description'] = '';
				$rec['descriptionformat'] = '1';

				if(!$this->insert('mdl_attendance_sessions', $rec)){
					show_error("Error de fecha", "Error al generar la session");
				}

				$numclase++;

			}

			$sumday++;

		}


		return $currentdate;

	}

	function getCourseAttendance($id){
		$result	=	$this->GetAll("SELECT sess.id, sess.lasttakenby as creator, sess.sessdate, sess.lasttakenby as takenby, sess.lasttaken, sess.description
		FROM mdl_attendance_sessions sess
		INNER JOIN mdl_attendance a ON sess.attendanceid=a.id
		WHERE a.course={$id}
		ORDER BY sess.sessdate;");

		return $result;

	}
	function getCourseAttendanceCancelled($id){

		$result	=	$this->GetAll("SELECT sess.id, sess.sessdate, sess.description
									FROM mdl_attendance_sessions_cancelled sess
									INNER JOIN mdl_attendance a ON sess.attendanceid=a.id
									WHERE a.course={$id} AND sess.deleted=0
									ORDER BY sess.sessdate;");

		return $result;

	}

	function getCourseAttendanceLogStatus($sessionid,$status){
		$result	=	$this->GetField_sql("SELECT COUNT(*) FROM mdl_attendance_log attl
									INNER JOIN mdl_attendance_statuses atts ON attl.statusid=atts.id
									WHERE attl.sessionid={$sessionid} AND atts.acronym='{$status}';");
		return $result;
	}
	///// Agregado Rodo 20/02/2017 /////
	function getCourseAttendanceStudent($sessionid,$studentid){
		$result = $this->GetField_sql("SELECT acronym FROM mdl_attendance_log attl INNER JOIN mdl_attendance_statuses atts ON attl.statusid=atts.id WHERE attl.sessionid={$sessionid} AND studentid={$studentid}");
		return $result;
	}
	function getStudentNotes($itemid,$studentid){
		$result = $this->GetField_sql("SELECT MAX(finalgrade) FROM mdl_grade_grades WHERE itemid={$itemid} AND userid={$studentid}");
		return $result;
	}
	//////////////// EDIT 06.02.2018 //////////////
	function getValorCuota($courseid,$studentid,$cuota){
		//$result = $H_DB->GetOne("SELECT (valor_pagado-valor_cuota) as diferencia FROM h_cuotas WHERE courseid={$courseid} AND userid={$studentid} AND cuota={$cuota}");
		global $H_DB,$HULK;
		$result = $H_DB->GetOne(
			"SELECT (c.valor_pagado-(c.valor_cuota-(c.valor_cuota*(i.becado/100)))) as diferencia
			FROM h_cuotas as c
			LEFT JOIN h_inscripcion as i ON i.id=c.insc_id
			LEFT JOIN {$HULK->lms_dbname}.mdl_course as mc ON mc.id=i.comisionid
			WHERE
				i.comisionid={$courseid}
				AND i.userid={$studentid}
				AND c.cuota={$cuota}
				AND UNIX_TIMESTAMP()-(UNIX_TIMESTAMP(CONCAT(DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(mc.startdate, '%Y-%m-%d %H:%i:%S'), INTERVAL {$cuota}-1 MONTH),'%Y'),'-',DATE_FORMAT(DATE_ADD(FROM_UNIXTIME(mc.startdate, '%Y-%m-%d %H:%i:%S'), INTERVAL {$cuota}-1 MONTH),'%m'),'-05 00:00:00' ) )) > 0"
		);
		return $result;
	}
	///////// END EDIT //////////////////

// Nuevo usuario
	function insertUser($user_data){
		global $HULK;

		$user = (array) $user_data;
		$user['confirmed'] = 1;
		$user['timemodified'] = time();
		$user['lang']	=	'es_utf8';
		$user['auth']	=	'manual';
		$user['deleted']	=	0;
		$user['mnethostid']	=	1;

		if (empty($user['username']) OR empty($user['email'])) {
				return false;
		}
		if($this->record_exists_sql("SELECT id FROM mdl_user
									 WHERE username LIKE '{$user['username']}' OR email LIKE '{$user['email']}'
									 LIMIT 1;")) return false;
		$id = $this->insert('mdl_user',$user);
		return $id;
	}

	/* cambiar estatus de usuario en una comisión */
	function user_status($userid,$courseid,$status="GET"){
		global $HULK;

		$gradebook 	= array ('E'=>0.00000,'I'=>1.00000,'F'=>2.00000,'P'=>3.00000);
		$estados	= array ('0.00000'=>'E','1.00000'=>'I','2.00000'=>'F','3.00000'=>'P');

		if (!$itemid = $this->GetField_sql("SELECT id FROM mdl_grade_items WHERE itemname LIKE '%Graduaci%' AND courseid={$courseid}")){
			//return false;
		}

		if ($status=="GET"){
			if ($status = $this->GetField_sql("SELECT finalgrade FROM {$HULK->dbname}.vw_enrolados WHERE userid={$userid} AND id={$courseid}")){
				return $estados[$status];
			}else{
				return "E";
			}
		}

		$grade_grades['rawgrade'] = $gradebook[$status];
		$grade_grades['rawgrademax'] = $gradebook[$status];
		$grade_grades['finalgrade'] = $gradebook[$status];
		if ($status=="E"){
			$this->delete('mdl_grade_grades', "itemid={$itemid} AND userid={$userid}");
			$this->delete('mdl_grade_grades_history', "itemid={$itemid} AND userid={$userid}");
			return true;
		}else{
			if (!$grade_grades['id'] = $this->GetField_sql("SELECT id FROM mdl_grade_grades WHERE userid={$userid} AND itemid={$itemid}")) {
				$grade_grades['itemid'] = $itemid;
				$grade_grades['userid'] = $userid;
				$grade_grades['rawgrademin'] = '1';
				$grade_grades['rawscaleid'] = '1';
				$grade_grades['usermodified'] = '1';
				$grade_grades['hidden'] = '0';
				$grade_grades['locked'] = '0';
				$grade_grades['locktime'] = '0';
				$grade_grades['exported'] = '0';
				$grade_grades['overridden'] = '0';
				$grade_grades['excluded'] = '0';
				$grade_grades['informationformat'] = '0';
				if ($new_grade['id'] = $this->insert('mdl_grade_grades', $grade_grades)) {
					return true;
				}else{
					return false;
				}
			}else{
				if (!$this->record_exists_sql("SELECT id FROM mdl_grade_grades WHERE userid={$userid} AND itemid={$itemid} AND finalgrade={$gradebook[$status]}")) {
					if ($this->update('mdl_grade_grades', $grade_grades, "id={$grade_grades['id']}")) {
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}
		}
	}

	/* cambiar nota final */
	function user_final($userid,$courseid,$status){
		global $HULK;

		if (!$itemid = $this->GetField_sql("SELECT id FROM mdl_grade_items WHERE itemname LIKE '%Final T%' AND courseid={$courseid}")){
			return false;
		}
		$grade_grades['rawgrade'] = $status;
		$grade_grades['rawgrademax'] = 100;
		$grade_grades['finalgrade'] = $status;


		if ($status>0){
			if (!$grade_grades['id'] = $this->GetField_sql("SELECT id FROM mdl_grade_grades WHERE userid={$userid} AND itemid={$itemid}")) {
				$grade_grades['itemid'] = $itemid;
				$grade_grades['userid'] = $userid;
				$grade_grades['rawgrademin'] = '1';
				$grade_grades['rawscaleid'] = '1';
				$grade_grades['usermodified'] = '1';
				$grade_grades['hidden'] = '0';
				$grade_grades['locked'] = '0';
				$grade_grades['locktime'] = '0';
				$grade_grades['exported'] = '0';
				$grade_grades['overridden'] = '0';
				$grade_grades['excluded'] = '0';
				$grade_grades['informationformat'] = '0';
				if ($new_grade['id'] = $this->insert('mdl_grade_grades', $grade_grades)) {
					return true;
				}else{
					return false;
				}
			}else{
				if (!$this->record_exists_sql("SELECT id FROM mdl_grade_grades WHERE userid={$userid} AND itemid={$itemid} AND finalgrade={$grade_grades['finalgrade']}")) {
					if ($this->update('mdl_grade_grades', $grade_grades, "id={$grade_grades['id']}")) {
						return true;
					}else{
						return false;
					}
				}else{
					return true;
				}
			}
		}else{
			$this->delete('mdl_grade_grades', "itemid={$itemid} AND userid={$userid}");
			$this->delete('mdl_grade_grades_history', "itemid={$itemid} AND userid={$userid}");
			return true;
		}
	}
	/* Enrolar usuario */
	function enrolUser($userid, $courseid, $roleid) {
		global $H_USER,$H_DB;

		/// Do some data validation
		if (empty($roleid)) return false;

		if (empty($userid) OR $userid<2) return false;

		if ($userid && !$this->record_exists('mdl_user', $userid)) return false;

		if (empty($courseid)) return false;

		$context 	= $this->GetField_sql("SELECT id FROM mdl_context ctx
										WHERE ctx.instanceid={$courseid} AND ctx.contextlevel = 50;");
		$enrolid 	= $this->GetField_sql("SELECT e.id FROM mdl_enrol e
										WHERE e.courseid={$courseid} AND e.enrol LIKE '%manual%';");
		if (!$context OR !$enrolid) {
			echo "no existe contexto o enrolid";
			return false;
		}

	  	// Check for existing entry
		$ra = $this->GetField_sql("SELECT id FROM mdl_role_assignments ra
							 		WHERE roleid={$roleid} AND userid={$userid} AND contextid={$context};");

		if (empty($ra)) {             // Create a new entry
			$ra = array(
				'roleid'	=> $roleid,
				'contextid'	=> $context,
				'userid'	=> $userid,
				'timemodified' 	=> time(),
				'modifierid'	=> $H_USER->get_property('id')
			);
			if (!$ra = $this->insert('mdl_role_assignments', $ra)) {
				return false;
			}
		}

		$enrol_user = $this->GetField_sql("SELECT id FROM mdl_user_enrolments ur
							 		WHERE enrolid={$enrolid} AND userid={$userid};");
		if (empty($enrol_user)) {
			$enrol_user = array (
				'status' 	=> 0,
				'enrolid'	=> $enrolid,
				'userid'	=> $userid,
				'timestart'	=> time(),
				'timeend'	=> 0,
				'modifierid'=> $H_USER->get_property('id'),
				'timecreated'	=> time(),
				'timemodified'	=> time()
			);
			if (!$enrol_user = $this->insert('mdl_user_enrolments', $enrol_user)) {
				return false;
			}
		}

		// Módulo de actividad
		$modelo = $this->GetField('mdl_course','from_courseid',$courseid);
		$activity = array(
			'userid'	=> $H_USER->get_property('id'),
			'contactid'	=> $userid,
			'typeid' 	=> 4,
			'statusid'	=> 7,
			'subject'	=> "Inscripción en {$this->GetField('mdl_course','shortname',$modelo)}",
			'summary'	=> "Se enroló al contacto en la comisión {$this->GetField('mdl_course','shortname',$courseid)}.",
			'startdate' => time(),
			'enddate'	=> time()
		);

		if(!$H_DB->insert("h_activity",$activity)){
			show_error("Error al registrar la actividad.");
		}

		return true;
	}
	/* Enrolar usuario */
	function unenrolUser($userid, $courseid, $roleid) {
		global $H_USER;
		/// Do some data validation
 		if (empty($roleid)) {	return false; }
 	  if (empty($userid)) {	return false; }
 	  if (empty($courseid)) { return false; }

		$context = $this->GetField_sql("SELECT id FROM mdl_context ctx
																		WHERE ctx.instanceid={$courseid} AND ctx.contextlevel = 50;");
		$academy = $this->GetField_sql("SELECT academyid FROM mdl_course c WHERE c.id={$courseid};");
		$enrolid = $this->GetField_sql("SELECT id FROM mdl_enrol WHERE enrol='manual' AND courseid={$courseid};");
		if (!$context) { echo "no existe contexto";	return false;	}
  		// Check for existing entry
		$ra = $this->GetField_sql("SELECT id FROM mdl_role_assignments ra
													 		WHERE roleid={$roleid} AND userid={$userid} AND contextid={$context};");
		if (!$this->delete('mdl_role_assignments', "roleid={$roleid} AND userid={$userid} AND contextid={$context}")) {
			return false;
		}
		if (!$this->delete('mdl_user_enrolments', "enrolid={$enrolid} AND userid={$userid}")) {
			return false;
		}
		return true;
	}

	function getPeriodos(){
		global $HULK;

			$rows = $this->GetAll("SELECT c.periodo as field
									FROM {$HULK->dbname}.vw_course c
									WHERE c.periodo != ''
									AND c.periodo !='0'
									GROUP BY periodo
									ORDER BY periodo DESC;");
			foreach($rows as $row){
				$result[] = $row['field'];
			}
			return $result;
	}
	function getPaises(){
			$rows = $this->GetAll("SELECT	a.country as field
									FROM mdl_proy_academy a
									GROUP BY a.country ORDER BY a.country ASC;");
			foreach($rows as $row){
				$result[] = $row['field'];
			}
			return $result;
	}
	function getCarreras(){
			$result = $this->GetAll("select distinct cm.id ,cm.shortname
									from mdl_course c
									inner join mdl_course cm on c.from_courseid = cm.id
									inner join mdl_convenio_course cc  on cc.courseid = c.from_courseid
									inner join mdl_proy_academy_convenio ac on (cc.convenioid = ac.convenioid and c.academyid = ac.academyid)
									where
									cm.fullname not like _utf8'%instructor%'
									and cm.fullname not like _utf8'%bridge%'
									and ac.timestart < UNIX_TIMESTAMP(now())
									and (ac.timeend > UNIX_TIMESTAMP(now()) OR ac.timeend=0)
									and c.academyid NOT IN (0,50)
									and ac.forma_de_pago NOT LIKe '%plan_social%'
									order by ac.convenioid, cm.shortname ASC;");
			return $result;
	}
	function getAcademias(){
			$result = $this->GetAll("SELECT ac.id,ac.name, ac.country
									FROM mdl_proy_academy ac
									WHERE ac.id NOT IN (6,12,21,33,46,54,55,50)
									AND ac.deleted != 1
									ORDER BY ac.name ASC;");
			return $result;
	}
	function get_formas_de_pago(){

		$rows = $this->GetAll("SELECT DISTINCT forma_de_pago FROM mdl_proy_academy_convenio ;");
		foreach($rows as $row){
			$pagos[] = $row['forma_de_pago'];
		}
		return $pagos;
	}
	function create_comision($data){

		global $HULK;

		set_time_limit(3600); // 1 hour should be enough

		$data = (object) $data;

		//flush_buffers('Comenzando Creación de comisión');
		flush_buffers();

		require_once($HULK->libdir.'/lms_lib/setupmoodle.php');
		require_once($HULK->libdir.'/lms_lib/course/lib.php');
		require_once($HULK->libdir.'/lms_lib/course/edit_form.php');

		//require_once($HULK->libdir.'/lms_lib/lib/modinfolib.php');

		// Vuelvo a invocar la variable $DB por error que aparecio que pierde valor.

		global $DB;


		if (!$course = create_course($data)) {
			print_error('coursenotcreated');
		}

		$context = context_course::instance($course->id);

		if($sections = $DB->get_records('course_sections', array('course'=>$data->from_courseid))){
			foreach($sections as $section){
				//La section 0 es donde agrega el foro si no hay sequencia tampoco va.
				if ($section->section > 0 AND $section->sequence!=''){
					///flush_buffers('Sección:'.$section->name);
					flush_buffers();
			  	$seqs = explode(',',$section->sequence);
			  	$section->course = $course->id;
			  	unset($section->id);
					//sequence vacio para poner los nuevos id de course_module
					unset($section->sequence);
				  	if($section->id = $DB->insert_record('course_sections', $section)){
						foreach($seqs as $seq){
							unset($course_module);
							unset($module);
							unset($question);
						   	if ($course_module = $DB->get_record("course_modules", array("id"=>$seq))) {
								//Obtengo el nombre del modulo para invocar a su libreria
								if ($module = $DB->get_record("modules", array("id"=>$course_module->module))){
									//flush_buffers('&nbsp;&nbsp;&nbsp;Actividad: '.$module->name);
									flush_buffers();
									$libreria = $HULK->lms_dirroot.'/mod/'.$module->name.'/lib.php';
									if (file_exists($libreria)){
										include_once($libreria);
										$module_instance = $DB->get_record($module->name, array('id'=>$course_module->instance));
									}else{
										flush_buffers('&nbsp;&nbsp;&nbsp;Actividad: ERROR -> No se encontro la libreria de '.$module->name);
										continue;
									}
								}else{
									flush_buffers('&nbsp;&nbsp;&nbsp;Actividad: ERROR -> No se encontro en BBDD '.$module->name);
									continue;
								}

								$mod->id = $module_instance->id;
								unset($module_instance->id);
								$module_instance->course       = $course->id;

								$addinstancefunction    = $module->name."_add_instance";

								//Para evitar que tire el error. Luego hay que cambiarle por el nuevo coursemodule
								$module_instance->coursemodule = $course_module->id;
								if ($module->name=="quiz"){

									$module_instance->created = time();
									$result = quiz_process_options($module_instance);
								    $module_instance->id = $DB->insert_record('quiz', $module_instance);
								    @$DB->insert_record('quiz_sections', array('quizid' => $module_instance->id, 'firstslot' => 1, 'heading' => '', 'shufflequestions' => 0));
								    // Do the processing required after an add or an update.
								   // quiz_after_add_or_update($module_instance);
								}else{
									$module_instance->id = $addinstancefunction($module_instance,null);
								}

								$course_module->course =  $course->id;
								$course_module->instance =  $module_instance->id;
								$course_module->section =  $section->id;

								//$course_module->id = add_course_module($course_module);
								$course_module->added = time();
							    unset($course_module->id);
    							if($course_module->id = $DB->insert_record("course_modules", $course_module)){
	    							rebuild_course_cache($course_module->course, true);
									course_add_cm_to_section($course->id,$course_module->id,$section->section);
    							}

								if ($module->name == 'quiz'){

									$quiz->id = $module_instance->id;
									//$quiz->questions = $questions;
									$quiz->reviewattempt = 69904;
									$quiz->reviewcorrectness = 4368;
									$quiz->reviewmarks = 4368;
									$quiz->reviewspecificfeedback = 4368;
									$quiz->reviewgeneralfeedback = 4368;
									$quiz->reviewrightanswer = 4368;
									$quiz->reviewoverallfeedback = 4368;

									if (!$DB->update_record('quiz', $quiz)) {
									   show_error("On update quiz");
									}
									if ($question_instances = $DB->get_records('quiz_slots', array('quizid'=>$mod->id))) {
									   foreach($question_instances as $question_instance){
										   $question_instance->id = -1;
										   $question_instance->quizid = $module_instance->id;
										   if (!$DB->insert_record('quiz_slots', $question_instance)) {
											 $error("Could not create quiz_slots");
										   }
									   }
									}
									if ($quiz_feedbacks = $DB->get_records('quiz_feedback', array('quizid'=>$mod->id))) {
									   foreach($quiz_feedbacks as $quiz_feedback){
										   $quiz_feedback->id = -1;
										   $quiz_feedback->quizid = $module_instance->id;
										   if (!$DB->insert_record('quiz_feedback', $quiz_feedback)) {
											 $error("Could not create quiz_feedback");
										   }
										}
									}
								}
							}
						}
				  	}
				}
			}
		}
		//$this->resetCourseBlocks($course->id);
		return $course->id;
	}
	function resetCourseBlocks($courseid){
		global $HULK, $CFG;
		require_once($HULK->libdir.'/lms_lib/setupmoodle.php');
		require_once($HULK->lms_dirroot.'/lib/blocklib.php');
		$CFG->defaultblocks_override = 'course_proydesa,activity_modules:attendance,news_items,calendar_upcoming,recent_activity';
		$CFG->defaultblocks_site = 'site_main_menu,site_proydesa:html,calendar_month';
		$course = get_course($courseid);//can be feed categoryid to just effect one category
   		$context = context_course::instance($course->id);
   		@blocks_delete_all_for_context($context->id);
   		if(!@blocks_add_default_course_blocks($course)){
			show_error("Error en función","No se pudieron configurar los bloques del curso {$course->shortname}");
			return false;
		}
		return true;
	}
	function ultimoPeriodoInstructor($userid){
		// Devuelve el ultimo periodo en que está enrolado como instructor
		global $H_DB,$HULK;
		if($periodo = $H_DB->GetOne("SELECT periodo FROM {$HULK->dbname}.vw_enrolados
									where userid={$userid} AND roleid=4
									ORDER BY periodo DESC LIMIT 0,1;")){
			return $periodo;
		}
		return false;
	}
	function ultimoPeriodoEstudiante($userid){
		// Devuelve ultimo periodo en el que está enrolado como estudiante
		global $H_DB;
		if($periodo = $H_DB->GetOne("SELECT periodo FROM {$HULK->dbname}.vw_enrolados
									where userid={$userid} AND roleid=5
									ORDER BY periodo DESC LIMIT 0,1;")){
			return $periodo;
		}
		return false;
	}
	function getAcademyConveniosActivosField($academyid){

		if($result = $this->GetOne("SELECT DISTINCT GROUP_CONCAT(c.shortname)
									FROM mdl_proy_convenios c
									INNER JOIN mdl_proy_academy_convenio ac ON ac.convenioid = c.id
								 	WHERE ac.academyid={$academyid}
								 	GROUP BY (ac.academyid) ORDER BY c.shortname;")){
			return $result;
		}
		return false;
	}
	function clone_section($section_from,$section_to){

			return true;
	}
	function getTotalesAsistenciaEnAcademiaParaFecha($idAcademia,$fecha){

		global $HULK;

		$result = $this->GetAll("SELECT sta.acronym, count(1) total
									FROM mdl_attendance_sessions sess,
										mdl_attendance a ,
										mdl_course c,
										mdl_attendance_log attl ,
										mdl_attendance_statuses sta,
										mdl_user u
									WHERE
										c.academyid = ".$idAcademia."
									and	c.id= a.course
									and a.id = sess.attendanceid
									and sess.id =attl.sessionid
									and attl.studentid= u.id
									and attl.statusid=sta.id
									and date_format(FROM_UNIXTIME(sess.sessdate),'%d-%m-%Y')= '{$fecha}'
									and  not exists (SELECT 1 FROM {$HULK->dbname}.h_bajas b WHERE b.comisionid=c.id AND b.cancel=0 and b.userid=u.id and unix_timestamp(date_format(FROM_UNIXTIME(b.date),'%Y-%m-%d'))<=sess.sessdate)
									group by sta.acronym");
		return $result;
	}

	function getTotalesAsistenciaEnCursoParaAlumnoConTipo($idCurso,$idAlumno,$tipo){
		global $HULK;
		$result = $this->GetAll("SELECT sta.acronym,count(1) total
									FROM mdl_attendance_sessions sess,
										mdl_attendance a ,
										mdl_course c,
										mdl_attendance_log attl ,
										mdl_attendance_statuses sta,
										mdl_user u
									WHERE
										c.id= a.course
									and a.id = sess.attendanceid
									and sess.id =attl.sessionid
									and attl.studentid= u.id
									and attl.statusid=sta.id
									and sta.acronym in (".$tipo.")
									and c.id =".$idCurso."
									and u.id=".$idAlumno."
									and  not exists (SELECT 1 FROM {$HULK->dbname}.h_bajas b WHERE b.comisionid=c.id AND b.cancel=0 and b.userid=u.id and unix_timestamp(date_format(FROM_UNIXTIME(b.date),'%Y-%m-%d'))<=sess.sessdate)
									group by sta.acronym");
		return $result;
	}
	function getAlumnosAusentesEnAcademiaEnFechaConTipo($idAcademia,$fecha){

		global $HULK;
		$sql ="SELECT u.firstname nombre ,u.lastname apellido ,c.id idCurso ,u.id idAlumno,c.fullname nombre_curso
		FROM mdl_attendance_sessions sess,
			mdl_attendance a ,
			mdl_course c,
			mdl_attendance_log attl ,
			mdl_attendance_statuses sta,
			mdl_user u
		WHERE
			c.academyid = ".$idAcademia."
		and	c.id= a.course
		and a.id = sess.attendanceid
		and sess.id =attl.sessionid
		and attl.studentid= u.id
		and attl.statusid=sta.id
		and sta.acronym in ('A')
		and date_format(FROM_UNIXTIME(sess.sessdate),'%d-%m-%Y')= '{$fecha}'
		and  not exists (SELECT 1 FROM {$HULK->dbname}.h_bajas b WHERE b.comisionid=c.id AND b.cancel=0 and b.userid=u.id and unix_timestamp(date_format(FROM_UNIXTIME(b.date),'%Y-%m-%d'))<=sess.sessdate)";

		return $this->GetAll($sql);
	}
	function getCursosEnAcademiaQueSeDictanEnDia($idAcademia,$fecha){
		global $HULK;
		$fechaReverse = substr($fecha,-4).'-'.substr($fecha,3,2).'-'.substr($fecha,0,2);
		$fechacero = strtotime($fechaReverse."T00:00:00+00:00");
		$fechafin = strtotime($fechaReverse."T23:59:59+00:00");

		$sql = "SELECT
					c.id,
					c.fullname
				FROM {$HULK->lms_dbname}.mdl_course c
					inner join {$HULK->lms_dbname}.mdl_attendance a ON c.id = a.course
					inner join {$HULK->lms_dbname}.mdl_attendance_sessions sess ON a.id = sess.attendanceid
				where
					sess.sessdate between {$fechacero} and {$fechafin}
					and ( (c.academyid = {$idAcademia} and {$idAcademia} != 0 ) or {$idAcademia} = 0 )
					order by c.id";

		return $this->GetAll($sql) ;
	}
	function getCoursesByRange($from='',$to=''){

		global $HULK;

		if(empty($from) || empty($to)) return false;

		return $this->GetAll(
			"SELECT c.id, c.fullname, c.shortname, c.startdate, c.intensivo, c.periodo, FROM_UNIXTIME(c.startdate) inicio, cc.aulaid
			FROM mdl_course c
			LEFT JOIN {$HULK->dbname}.h_course_config cc ON cc.courseid=c.id
			WHERE c.startdate>={$from} AND c.startdate<={$to}
			GROUP BY cc.courseid"
		);

	}
	function getCourseByDay($date='',$aulaid=0){

		global $HULK;

		if(empty($date)) return false;

		$arrmindays = array('L','M','W','J','V','S','D');

		$daycode = $arrmindays[date('N',strtotime($date))-1];

		return $this->GetAll(

			"SELECT ats.sessdate, FROM_UNIXTIME(ats.sessdate,'%Y-%m-%d') fecha, att.id, att.course, c.fullname, c.shortname, c.intensivo, cc.aulaid, cc.horarioid, cc.dias, cc.courseid, h.name horario, h.turno, h.inicio, h.fin, cm.fullname cursomodelo, cm.shortname cursomodelo_shortname, (SELECT CONCAT(u1.lastname, ', ', u1.firstname) AS Nombre FROM {$HULK->dbname}.vw_enrolados e1 INNER JOIN mdl_user u1 ON e1.userid = u1.id WHERE e1.roleid = 4 AND e1.id = c.id LIMIT 1) AS instructor

			FROM {$HULK->lms_dbname}.mdl_attendance_sessions ats

			LEFT JOIN {$HULK->lms_dbname}.mdl_attendance att ON att.id=ats.attendanceid
			LEFT JOIN {$HULK->lms_dbname}.mdl_course c ON c.id=att.course
			LEFT JOIN {$HULK->lms_dbname}.mdl_course cm ON c.from_courseid = cm.id
			LEFT JOIN {$HULK->dbname}.h_course_config cc ON cc.courseid=c.id
			LEFT JOIN {$HULK->dbname}.h_horarios h ON h.id=cc.horarioid

			WHERE FROM_UNIXTIME(ats.sessdate,'%Y-%m-%d') = '{$date}' AND cc.aulaid={$aulaid} AND c.academyid=1 AND cc.horarioid IS NOT NULL AND cc.dias='{$daycode}'"

		);

	}


}