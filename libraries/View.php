<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
Clase de vista
Funcion se le pasa la el nombre de archivo de la vista y se le pasa un vector con las variables o vectores a usar en la vista.
*/
class H_View {

	function load($file,$vars=NULL){
		global $HULK, $H_USER, $LMS, $H_DB, $view,$_comp, $_arrcss, $jquerynew;

		$file=$HULK->viewdir.'/'.$file.EXT;

		if ( ! file_exists($file)){
			/* Hacer funcion de manejo de errores al estilo show_error y manejo de un archivo de log y mensajes de sistema para 				*	el debug.
			*	show_error('Unable to load the requested file: '.$file);
			*/
			echo 'Unable to load the requested file: '.$file;
			die;
		}
		if (is_array($vars)){
			extract($vars);
		}
		ob_start();
		//		if ((bool) @ini_get('short_open_tag') === FALSE AND config_item('rewrite_short_tags') == TRUE)
		if ((bool) @ini_get('short_open_tag') === FALSE){
			echo eval('?>'.preg_replace("/;*\s*\?>/", "; ?>", str_replace('<?=', '<?php echo ', file_get_contents($file))));
			//include($file);
		}else{
			echo 'fuck';
			include($file);
		}
	}
	function js_link($file){
		global $HULK;

		$file =	"{$HULK->javascript}/{$file}";
		if ( ! file_exists($file)){
			// TODO: Manejo de errores.
			echo 'Unable to load the requested file: '.$file;
			die;
		}
		echo "<script src='{$file}' type='text/javascript'></script>";
		return true;
	}

	function js($script){
		echo '<script type="text/javascript">';
		echo $script;
		echo '</script>
		';
		return true;
	}

	function jquery($script){

		$script2='
			    $(function(){
			    '.$script.'
					});';
		return $this->js($script2);
	}

	function jquery_datepicker($dom="#startdate, #enddate"){

		$script='
			    $(function(){
			    	var dates = $( "'.$dom.'" ).datepicker({
							dateFormat: "dd-mm-yy",
							defaultDate: "+1w",
							changeMonth: true,
							numberOfMonths: 3,
							onSelect: function( selectedDate ) {
								var option = this.id == "startdate" ? "minDate" : "maxDate",
								instance = $( this ).data( "datepicker" );
								date = $.datepicker.parseDate(
									instance.settings.dateFormat ||
									$.datepicker._defaults.dateFormat,
									selectedDate, instance.settings );
								dates.not( this ).datepicker( "option", option, date );
							}
						});
					});';
		return $this->js($script);
	}

	function jquery_datepicker2($dom="#startdate, #enddate"){
		global $HULK;

		$script='
			    $(function(){
			    	var dates = $( "'.$dom.'" ).datepicker({
							changeMonth: true,
							changeYear: true,
							defaultDate: "+0d",
							yearRange: "2010:2020",
							dateFormat: "dd-mm-yy",
							monthNamesShort: ["'.implode($HULK->meses,'","').'"],
							onSelect: function( selectedDate ) {
								var option = this.id == "startdate" ? "minDate" : "maxDate",
								instance = $( this ).data( "datepicker" );
								date = $.datepicker.parseDate(
									instance.settings.dateFormat ||
									$.datepicker._defaults.dateFormat,
									selectedDate, instance.settings );
								dates.not( this ).datepicker( "option", option, date );
							}
						});
					});';
		return $this->js($script);
	}

	function jquery_portlet(){
		$script='
			    $(function(){
						$( ".column" ).sortable({
							connectWith: ".column",
							handle: "div.portlet-header",
							cancel: ".disabled",
							cursor: "move",
							forcePlaceholderSize: true,
							opacity: 0.6
						});

						$( ".portlet" ).addClass( "ui-widget ui-widget-content ui-helper-clearfix ui-corner-all" )
							.find( ".portlet-header" )
							.addClass( "ui-widget-header ui-corner-all" )
							.prepend( "<span class=\'ui-icon ui-icon-minusthick\'></span>")
							.end()
							.find( ".portlet-content" );

						$( ".portlet-header .ui-icon" ).click(function() {
							$( this ).toggleClass( "ui-icon-minusthick" ).toggleClass( "ui-icon-plusthick" );
							$( this ).parents( ".portlet:first" ).find( ".portlet-content" ).toggle();
						});
					});';
		return $this->js($script);
	}
}