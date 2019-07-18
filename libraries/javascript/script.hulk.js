	$(function(){

	// Mensaje de cargando de ajax
		$("<div id='busy'  class='ui-state-highlight'> &nbsp;Cargando...</div>")
			.ajaxStart(function() {$(this).show();})
		.ajaxStop(function() {$(this).hide();})
			.appendTo("body");
		$().ajaxError(
			function(event, request, settings){
				alert("¡Tomátelo con calma!, vas muy rápido, te recomiendo actualizar la página. (Error requesting page " + settings.url + ")");
			}
		);

	// Mensaje de confirmación de link generico
	$(".confirmLink").click(function(e) {
		e.preventDefault();
		var targetUrl = $(this).attr("href");

		$("<div id='confirmlink' title='Confirmar acción'><p><span class='ui-icon ui-icon-alert' style='float:left; margin:0 7px 20px 0;'></span>Para completar esta acción debe confirmar. <br/> ¿Est&aacute; seguro?</p></div>")
		.appendTo("body");
		$("#confirmlink").dialog({
			resizable: false,
			height:250,
			width:450,
			modal: true,
			autoOpen: false,
			buttons: {
				"Si" : function() {
					window.location.href = targetUrl;
				},
				"No" : function() {
					$("#confirmlink").remove();
				}
			}
		});
		$("#confirmlink").dialog("open");
	});

	// Validacion de campos de formulario
	$('.validate').validate();

	// Menues
	$('.fg-button').hover(
		function(){ $(this).removeClass('ui-state-default').addClass('ui-state-focus'); },
		function(){ $(this).removeClass('ui-state-focus').addClass('ui-state-default'); }
	);
		/*$('#opcion-global').fgmenu({
			content: $('#opcion-global').next().html(),
			flyOut: true
		});
		$('#opcion-buscar').fgmenu({ content: $('#opcion-buscar').next().html(), backLink: false,topLinkText: 'Buscar',crumbDefaultText: 'Elija una opción:' });
		$('#opcion-nuevo').fgmenu({ content: $('#opcion-nuevo').next().html(), backLink: false,topLinkText: 'Menu',crumbDefaultText: 'Elija una opción:' });
		$('#hierarchy3').fgmenu({	content: $('#hierarchy3').next().html(), backLink: false,topLinkText: 'Menu',crumbDefaultText: 'Elija una opción:' });
		$('#hierarchy4').fgmenu({	content: $('#hierarchy4').next().html(), backLink: false,topLinkText: 'Menu',crumbDefaultText: 'Elija una opción:' });
		$('#opcion-configuraciones').fgmenu({
			content: $('#opcion-configuraciones').next().html(),
			flyOut: true
		});
		$('#opcion-reportes').fgmenu({
			content: $('#opcion-reportes').next().html(),
			flyOut: true
		});
		$('#opcion-cuenta').fgmenu({	content: $('#opcion-cuenta').next().html(), flyOut: true  });
		$('#opcion-enlaces').fgmenu({	content: $('#opcion-enlaces').next().html(), flyOut: true  });
		$('#opcion-hd').fgmenu({	content: $('#opcion-hd').next().html(), flyOut: true  });
		$('#opcion-campaña').fgmenu({	content: $('#opcion-campaña').next().html(), flyOut: true  });
		$('#opcion-capacitacion').fgmenu({	content: $('#opcion-capacitacion').next().html(), flyOut: true  });*/

		// BUTTONS
		$( ".searchbutton" ).button({ icons: { primary: "ui-icon-search" } });
		$( "#backbutton" ).button({ icons: { primary: "ui-icon-arrowthick-1-w" } });
		$( ".agregar" ).button({ icons: { primary: "ui-icon-plusthick" } });
		$( ".cancel" ).button({ icons: { primary: "ui-icon-circle-close" } });
		$( ".add" ).button({ icons: { primary: "ui-icon-circle-check" } });
		$( ".button-seek-start" ).button({ icons: {	primary: "ui-icon-seek-start"	}	});
		$( ".button-seek-prev" ).button({ icons: {	primary: "ui-icon-seek-prev"	}	});
		$( ".button-seek-next" ).button({ icons: {	primary: "ui-icon-seek-next"	}	});
		$( ".button-seek-end" ).button({ icons: {	primary: "ui-icon-seek-end"	}	});
		$( "#check" ).button();
		$( "#format" ).buttonset();
		$( ".radio" ).buttonset();
		$( ".button" ).button({ });
		$( ".mail" ).button({ icons: { primary: "ui-icon-mail-closed"} });
		$( ".phone" ).button({ icons: { primary: "ui-icon-calculator"} });
		$( ".mail-closed" ).button({ icons: { primary: "ui-icon-mail-closed"} });
		$( ".calculator" ).button({ icons: { primary: "ui-icon-calculator"} });
		$( ".lightbulb" ).button({ icons: { primary: "ui-icon-lightbulb"} });
		$( ".pencil" ).button({ icons: { primary: "ui-icon-pencil"} });
		$( ".script" ).button({ icons: { primary: "ui-icon-script"} });
		$( ".refresh" ).button({ icons: { primary: "ui-icon-refresh"} });
		$( ".button_file" ).button({ icons: { secondary: "ui-icon-document" }, text: false });

		$( ".button-add" ).button({ icons: { primary: "ui-icon-circle-check" } });
		$( ".button-add2" ).button({ icons: { primary: "ui-icon-circle-plus" }, text: false });
		$( ".button-save" ).button({ icons: { primary: "ui-icon-check" } });
		$( ".button-save2" ).button({ icons: { primary: "ui-icon-check" }, text: false });
		$( ".button-print" ).button({ icons: { primary: "ui-icon-print" } });
		$( ".button-print2" ).button({ icons: { primary: "ui-icon-print" }, text: false });
		$( ".button-anular" ).button({ icons: { primary: "ui-icon-cancel" } });
		$( ".button-anular2" ).button({ icons: { primary: "ui-icon-cancel" }, text: false });
		$( ".button-cancelar" ).button({ icons: { primary: "ui-icon-document" } });
		$( ".button-cancelar2" ).button({ icons: { primary: "ui-icon-document" }, text: false });
		$( ".button-editar" ).button({ icons: { primary: "ui-icon-pencil" } });
		$( ".button-editar2" ).button({ icons: { primary: "ui-icon-pencil" }, text: false });
		$( ".button-borrar" ).button({ icons: { primary: "ui-icon-trash" } });

		$( ".cambio_comi" ).button({ icons: { primary: "ui-icon-refresh" }, text: true });
		$( ".baja" ).button({ icons: { primary: "ui-icon-circle-close" }, text: true });
		$( ".alta" ).button({ icons: { primary: "ui-icon-circle-plus" }, text: true });

		$(".portlet").addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
		 	.find( ".portlet-header" )
		 	.addClass( "ui-widget-header ui-corner-all" );

		$("#search").watermark("Buscar...");

		// Dialog

		$( "#dialog:ui-dialog" ).dialog( "destroy" );

		$( ".dialog" ).dialog({
			modal: true,
			autoOpen: true,
			position: 'center',
			height: 'auto',
			width: 'auto',
			resizable: true,
			buttons: {
				Ok: function() {
					$( this ).dialog( "close" );
				}
			}
		});

		$.extend($.expr[":"], {
			"containsIgnoreCase": function(elem, i, match, array) {
				return (elem.textContent || elem.innerText || "").toLowerCase().indexOf((match[3] || "").toLowerCase()) >= 0;
			}
		});

		$(".button-xls").button({ }).click(function(event) {
			$("#"+$(this).attr("id")+"-table-xls").val( $("<div>").append( $("#"+$(this).attr("id")+"-export").eq(0).clone()).html());
			$("#"+$(this).attr("id")+"-form").submit();
		});

	// Estilo para las tablas
	$('#listado').table();

	$(".tablesorter").tablesorter({
	    theme : 'jui',
	    headerTemplate : '{content} {icon}',
	    widgets : ['uitheme', 'zebra'],
	    widgetOptions : {
	      zebra   : ["even", "odd"],
	    }
  	});
	$(".tablesorterfilter").tablesorter({
	    theme : 'jui',
	    headerTemplate : '{content} {icon}',
	    widgets : ['uitheme', 'zebra','filter'],
	    widgetOptions : {
	      zebra   : ["even", "odd"],
	    }
	});
	$(".tablesorterfilterpager").tablesorter({
	    theme : 'jui',
	    headerTemplate : '{content} {icon}',
	    widgets : ['uitheme', 'zebra','filter'],
	    widgetOptions : {
	      zebra   : ["even", "odd"],
	    }
	}).tablesorterPager({container: $("#pager")});



	$(window).scroll(function(){
		if(this.pageYOffset>100){
			$('#header,.menu-spacer').addClass('fixed');
		}else{
			$('#header,.menu-spacer').removeClass('fixed');
		}
	});

	$('#header .main-menu .btn').click(function(e){
		///e.stopPropagation();
		$('#header .main-menu .menu-item').hide();
		$('#header .main-menu .submenu').hide();
		$(this).parent().find('.menu-item').slideDown();
	});
	$('#header .main-menu .item').click(function(e){
		var submenu = $(this).parent().find('.submenu');
		if(submenu.length!=0){
			e.preventDefault();

			$('#header .main-menu .submenu').not(submenu).slideUp();
			submenu.slideToggle();
		}
	});

	$(document).mouseup(function(e){
    var container = $("#header .main-menu .menu-item");
    // if the target of the click isn't the container nor a descendant of the container
    if(container.has(e.target).length === 0){
			container.hide();
    }
	});

});
