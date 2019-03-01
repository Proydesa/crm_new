<?php
	header("Content-Transfer-Encoding: binary"); 
	header('Content-type: application/vnd.ms-excel; charset=utf-8;');
	header('Content-Disposition: attachment; filename="'.$xlsname.'_'.date("d-m-y",time()).'.xls"');
?>
<meta http-equiv="Content-Type" content="application/vnd.ms-excel; charset=utf-8;" />
<?php
	if ($table) echo $table;
?>