<link rel="stylesheet" type="text/css" href="<?= $HULK->javascript;?>/plot/jquery.jqplot.css" />

<div id="chart1" style="margin-top:20px; margin-left:20px; width:90%;"></div>

<script type="text/javascript">
	$(function () {
    $.jqplot.config.enablePlugins = true;

		<?=$graf;?>

		plot1 = $.jqplot('chart1', [<?= $lineas;?>], {
			legend:{show:true, location: 'nw'},
      series:[<?= $labels;?>],
			highlighter: { bringSeriesToFront: true, tooltipLocation: 'e', tooltipOffset: 0, formatString: '' },
			axes:{
				xaxis:{
						renderer:$.jqplot.CategoryAxisRenderer,
				}
			}
		});	
	});	
</script>