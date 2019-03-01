<span class="ui-widget" style="font-size: 12px; float:left;">
		<a href="#" onclick="window.open('index.php');" style="width:10%;">
			<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
		</a>
		<a href="index.php">Inicio</a>
<?php if (is_array($ruta)):
		foreach($ruta as $name=>$link):?>
	 -> <a href="<?= $link;?>"><?= $name;?></a>
<?php endforeach;endif;?>
</span>
<span class="ui-widget" style="float:right;"><?= show_fecha(time());?> &nbsp;&nbsp;&nbsp;</span>
