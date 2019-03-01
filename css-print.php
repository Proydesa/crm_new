<?php 	require_once 'config.php'; ?>

/* Correccion del UI jquery */
.ui-widget { 
	font-size: 0.7em; 
}
.ui-widget .ui-widget { font-size: 1em; }
.ui-state-normal, .ui-widget-content .ui-state-normal { border: 1px solid #cdd5da; background: #f6f6f6 url(images/ui-bg_highlight-hard_100_f6f6f6_1x100.png) 50% 50% repeat-x; font-weight: normal; color: #111111; }
.ui-state-fnormal, .ui-widget-content .ui-state-fnormal { border: 1px solid #cdd5da; background: #f6f6f6 url(images/ui-bg_highlight-hard_100_f6f6f6_1x100.png) 50% 50% repeat-x; font-weight: normal; color: #111111; }
.ui-widget-content2 { border: 1px solid #ffffff; background: #ffffff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x; color: #333333; }

#sidebar, #header, .footer, .noprint{
	display:none !important;
}

body {
	margin: 0px;
	padding: 0px;
}

/* Factura */
.factura {
	width: 800px;
	height: 900px;
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	text-align: center;
	border: #000 solid 1px;
}

#posletra{
	position:absolute;
	top:60px;
	left:355px;
}


.fact_datos{
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	border: 0px;
	border-bottom: #000 solid 1px;
	float:left;
	width:49%;
	height: 160px;
}

.fact_info{
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 10px;
	border: 0px;
	border-left: #000 solid 1px;
	border-bottom: #000 solid 1px;
	float:left;
	width: 49%;
	height: 160px;
	text-align: left;
}
.fact_info p{
	padding: 10px 10px 10px 10px;
}
.fact_cliente{
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	clear:both;
	width: 100%;
	text-align: left;
	height: 120px;
}
.fact_cliente_left{
	border: #000 solid 0px;
	float:left;
	width: 480px;
	padding-left:10px;
}
.fact_cliente_left2{
	border: #000 solid 0px;
	float:left;
	padding-left:10px;
}
.fact_condiciones{
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	border: 0px;
	clear:both;
	width: 99%;
	text-align:left;
	border-top: #000 solid 1px;
}

.fact_div{
	margin: 0px 0px 0px 0px;
	padding: 0px 0px 0px 0px;
	border: #000 solid 0.5px;
	clear:both;
	width: 99%;
	
}

.fact_div table thead tr th{
	border: #000 solid 1px;
	color: #000;
	font-family: Arial, "Times New Roman", Times, serif;
	font-weight:bold;
	background-color:#EEE;

}
#fact_table{
	height:580px;
}
p.break{
	page-break-before: always;
}

.div_reportes{
	overflow:hidden;
	height:auto;
}

.tabla_reportes{
	width:100%;
}

/*----------- Global Classes -------------*/

.clear { clear: both }

.floatLeft { float: left }

.floatRight { float: right }

.textLeft { text-align: left }

.textRight { text-align: right }

.textCenter { text-align: center }

.textJustify	{ text-align: justify }

.bold { font-weight: bold !important }

.italic { font-style: italic }

.underline { border-bottom: 1px solid }

.highlight { background: #ffc }

.noBG { background: none !important}

.noPadding { padding: 0 !important}

.noMargin { margin: 0 !important }

.noIndent { margin-left: 0; padding-left: 0 }

.noBullet { list-style: none; list-style-image: none }

.noBorder { border: none !important }
