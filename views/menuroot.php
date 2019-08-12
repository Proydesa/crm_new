<div class="main-breadcrumb" >
	<div class="breadcrumb" >
		<a href="index.php"><i class="fa fa-home"></i> Inicio</a>
		<?php if (is_array($ruta)): foreach($ruta as $name=>$link):?>
		-> <a href="<?= $link;?>"><?= $name;?></a>
		<?php endforeach;endif;?>
	</div>
	<div class="date" ><?= show_fecha(time());?></div>
</div>