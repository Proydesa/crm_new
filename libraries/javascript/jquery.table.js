$.fn.table = function(options) {

	var defaults = {
    foreground: 'red',
    background: 'yellow'
  };
  var opts = $.extend(defaults, options);

	// Alternar colores de las filas.
	$(this).find('tr:odd td').removeClass('ui-widget-content').addClass('ui-widget-content').css('cursor','pointer');
	$(this).find('tr:even td').removeClass('ui-widget-content').addClass('ui-widget-content').css('cursor','pointer');

	// Cuando paso por ensmia	
	$(this).find('tbody tr').hover(function(){
	  $(this).find('td').addClass('ui-state-hover');
	}, function(){
	  $(this).find('td').removeClass('ui-state-hover');
	});

	// Cuando hago click
/*	$(this).find('tbody tr').click(function(){
	  $(this).find('td').toggleClass('ui-state-focus');
	});
	*/
	// Todo visible
	$(this).find('tbody tr').addClass('visible');

	// Paginacion
/*    var currentPage = 0;
    var numPerPage = 10;
    var $table = $(this);
    
  	$(this).bind('repaginate', function() {
	    $(this).find('tbody tr').show();
			$(this).find("tbody tr:lt("+ currentPage * numPerPage+")").hide().removeClass('visible');
      $(this).find("tbody tr:gt("+(currentPage + 1) * numPerPage - 1+")").hide().removeClass('visible');
			console.log("Current Page: %s",currentPage * numPerPage);
    });

    var numRows = $(this).find('tbody tr').length;
    var numPages = Math.ceil(numRows / numPerPage);

    var $pager = $('<div class="pager" align="right"></div>');

		for (var page = 0; page < numPages; page++) {
			$('<span class="page-number">' + (page + 1) + '</span> ')
				.bind('click', {'newPage': page}, function(event) {
					currentPage = event.data['newPage'];
					$table.trigger('repaginate');
				})
       .appendTo($pager).addClass('press');
		}
    $pager.insertBefore($table);
    $table.trigger('repaginate');
*/
};
