<br/>
	<div class="ui-widget">
		<div class="ui-widget" align="right" style="width:90%;">

			<form action="contactos.php?v=listado" method="post" name="buscador">
		  		<button  id="backbutton" class="search" style="margin:0px;" onClick="window.history.back(-1);" >Volver</button>
		  		<select name="field" class="search">	
					<option value="username" selected>Usuario
					<option value="firstname">Nombre
					<option value="lastname">Apellido
					<option value="email">Correo Electronico		
				</select>
				<input class="search" id="search" type="search" autocomplete="off" size="60" maxlength="2048" name="q" title="Buscar en Hulk" value="<?= $q;?>"  spellcheck="false">
			  	<button  type="submit" id="searchbutton" class="search" style="margin:0px;" >Buscar</button>
			</form>
		</div>
	</div>
