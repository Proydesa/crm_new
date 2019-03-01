<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<html>
	<head>	
 
		<title>:: CRM Hulk :: <?= ucwords($HULK->FILE);?> -> <?= ucwords($v);?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

		<link rel="shortcut icon" href="<?= $HULK->wwwroot;?>/favicon.ico"> 
		<meta name="application-name" content="CRM Hulk"> 
		<meta name="description" content="Sistema de Administración tipo CRM - Hulk"> 
		<meta name="aplication-url" content="<?= $HULK->wwwroot;?>/index.php"> 
		<meta name="icon" href="<?= $HULK->wwwroot;?>/favicon.ico" sizes="32x32"> 

		<!-- CSS del JqueryUI -->

		<link href="<?= $HULK->wwwroot;?>/themes/<?= $HULK->theme;?>/jquery-ui.css" rel="stylesheet" type="text/css"  />	

		<!-- Jquery y JqueryUI -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery-1.4.4.min.js"></script>
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery-ui-1.8.11.min.js"></script>

		<!-- Javascript y CSS del menu -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/fg_menu/fgmenu.pack.js"></script>
    	<link type="text/css" href="<?= $HULK->javascript;?>/fg_menu/fg.menu.css" media="screen" rel="stylesheet" />

		<!-- Javascript adicionales (tabla, highlight) -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.table.pack.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.watermark.pack.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.validate.pack.js"></script>			
			<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.autoresize.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.columns.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.jeditable.js"></script>
	
			<script type="text/javascript" src="<?= $HULK->javascript;?>/plot/jquery.jqplot.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/plot/plugins/jqplot.categoryAxisRenderer.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/plot/plugins/jqplot.barRenderer.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/plot/plugins/jqplot.highlighter.js"></script>
			<script type="text/javascript" src="<?= $HULK->javascript;?>/plot/plugins/jqplot.cursor.js"></script>
	
			<!--<script type="text/javascript" src="<?= $HULK->javascript;?>/wysiwyg/jquery.wysiwyg.pack.js"></script>
    	<link type="text/css" href="<?= $HULK->javascript;?>/wysiwyg/jquery.wysiwyg.css" media="screen" rel="stylesheet" />
-->
		<!-- Correccions y extras al estilo general -->
			<link href="<?= $HULK->wwwroot;?>/themes/style.css" rel="stylesheet" type="text/css"  />	
			<link href="<?= $HULK->wwwroot;?>/themes/print.css" rel="stylesheet" type="text/css" media="print" />	

		<!-- Ejecucion de los scripts generales del hulk -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/script.hulk.js"></script>    

		<!-- Correccions y extras al estilo general -->
			<!--<link href="<?= $CFG->viewwww;?>/css-print.php" rel="stylesheet" type="text/css" media="print" />	
			-->
</head>
<body class="ui-widget-content noBorder"> 
