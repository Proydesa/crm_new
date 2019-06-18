<html>
	<head>
		<title>:: CRM :: <?= ucwords($HULK->FILE);?> -> <?= ucwords($v);?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"></meta>
		<link rel="shortcut icon" href="<?= $HULK->wwwroot;?>/favicon.ico"></link>
		<meta name="application-name" content="CRM Hulk"></meta>
		<meta name="description" content="Sistema de Administración CRM - Hulk" ></meta>
		<meta name="aplication-url" content="<?= $HULK->wwwroot;?>/index.php" ></meta>
		<meta name="icon" href="<?= $HULK->wwwroot;?>/favicon.ico" sizes="32x32" ></meta>

		<!-- CSS del JqueryUI -->
		<link href="<?= $HULK->wwwroot;?>/themes/<?= $HULK->theme;?>/jquery-ui.css" rel="stylesheet" type="text/css"  />

		<!-- jQuery & jQueryUI -->
		<?php if(!isset($jquerynew)): ?>
	  <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery-1.4.4.min.js"></script>
	  <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery-ui-1.8.11.min.js"></script>
		<?php else: ?>
	  <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.min.js"></script>
	  <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery-ui.min.js"></script>
		<?php endif; ?>

		<link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,600" rel="stylesheet">



		<!-- Javascript y CSS del menu -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/fg_menu/fgmenu.pack.js"></script>
    	<link type="text/css" href="<?= $HULK->javascript;?>/fg_menu/fg.menu.css" media="screen" rel="stylesheet" ></link>
		<!-- Javascript adicionales (tabla, highlight) -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.table.pack.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.watermark.pack.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.validate.pack.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.autoresize.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.columns.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.jeditable.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.ui.tabs.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.ui.progressbar.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.tablesorter.min.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.tablesorter.widgets.min.js"></script>
		<script type="text/javascript" src="<?= $HULK->javascript;?>/jquery.tablesorter.pager.js"></script>
		<link type="text/css" href="<?= $HULK->javascript;?>/theme.jui.css" rel="stylesheet">
		<!-- Correccions y extras al estilo general -->
		<link href="<?= $HULK->wwwroot;?>/themes/style.css" rel="stylesheet" type="text/css"  ></link>
		<link href="<?= $HULK->wwwroot;?>/themes/print.css" rel="stylesheet" type="text/css" media="print" ></link>

		<!-- Cargar custom CSS -->
		<?php if(isset($_arrcss)): foreach ($_arrcss as $css): ?>
		<link href="<?= $HULK->wwwroot.'/'.$css['folder'].$css['style'].'.css?id='.rand(1111,9999) ?>" <?= isset($css['media']) ? 'media="'.$css['media'].'"' : '' ?> <?= isset($css['rel']) ? 'rel="'.$css['rel'].'"' : '' ?>> 
		<?php endforeach; endif; ?>

		
		<!-- Ejecucion de los scripts generales del hulk -->
	    <script type="text/javascript" src="<?= $HULK->javascript;?>/script.hulk.js"></script>
	</head>
	<body class="ui-widget-content noBorder">