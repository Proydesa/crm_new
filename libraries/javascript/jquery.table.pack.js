$.fn.table=function(options){var defaults={foreground:'red',background:'yellow'};var opts=$.extend(defaults,options);$(this).find('tr:odd td').removeClass('ui-widget-content').addClass('ui-widget-content').css('cursor','pointer');$(this).find('tr:even td').removeClass('ui-widget-content').addClass('ui-widget-content').css('cursor','pointer');$(this).find('tbody tr').hover(function(){$(this).find('td').addClass('ui-state-hover')},function(){$(this).find('td').removeClass('ui-state-hover')});$(this).find('tbody tr').click(function(){$(this).find('td').toggleClass('ui-state-focus')});$(this).find('tbody tr').addClass('visible')};