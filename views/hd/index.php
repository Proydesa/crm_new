<div class="column-c" style="width:50%">
	<div class="portlet">
    	<div class="portlet-header">Red Proydesa</div>
		<div class="portlet-content">
			<div align="right" class="ui-widget">
				<span align="left"><b>Mas reciente:</b> <a href="hd.php?v=details&id=<?=$ultima_act['id']?>"><?=$ultima_act['subject']?></a>&nbsp;&nbsp;</span>
			</div>
              <br />			
            <div align="center">
				<h3>Help Desk</h3>
            </div>
            <div align="center">
              <b><a href="hd.php?v=nuevo">Registrar un incidente nuevo</a></b>
            </div>
              <br />
            <div align="center">
              <a href="hd.php?v=view">Ver lista de incidentes</a>
            </div>
              <br />
            <div align="center">
              <form method="POST" action="hd.php?v=details">
                <input type="text" size="6" name="id" value=""> <input type="submit" value="Abrir un ID específico">
              </form>
            </div>
            <div align="center">
              <h3>Base de conocimientos</h3>
            </div>
			<div align="center">
				<a href="hd.php?v=details2">Buscar en la base de conocimientos</a>
			</div>
			<div align="center">
				<br />
				<form method="POST" action="hd.php?v=details">
					<input type="text" size="6" name="id"> <input type="submit" value="Buscar por ID">
				</form>
			</div>
            <div align="center">
				<h3>Otro</h3>
			</div>
			<div align="center">
				<a href="contactos.php?v=view">Editar Información</a>
			</div>
			<br/>
			<br/>
		</div>
	</div>
</div>
