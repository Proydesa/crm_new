<?php
	if ($H_USER->has_capability('menu/fixed')){
		$menufixed = "margin-top: 25px; width: 99%; ";
	}else{
		$menufixed = "width: 99%; ";
	}
?>
<div class="ui-widget-content" id="header" style="<?= $menufixed ?>height: 8px">
	<span class="ui-widget" style="font-size: 12px; float:left">
			<a href="#" onclick="window.open('index.php');" style="width:10%;">
				<span class="ui-icon ui-icon-newwin" style="float:left;"></span>
			</a>
			<a href="index.php">Inicio</a>
	<?php if (is_array($ruta)):
			foreach($ruta as $name=>$link):?>
		 -> <a href="<?= $link;?>"><?= $name;?></a>
	<?php endforeach;endif;?>
	</span><span class="ui-widget" style="float:right;"><?= show_fecha(time());?> &nbsp;&nbsp;&nbsp;</span>
</div>