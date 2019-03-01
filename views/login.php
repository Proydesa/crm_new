<div class="ui-widget login">
	<form action="login.php" method="post" id="login">
		<div class="ui-widget-content textCenter">
			<h3 class="ui-widget-header"> CRM | Fundaci&oacute;n Proydesa</h3>
			<table class="reset ui-widget" width="100%">
			<tr>
				<td>
					<label for="username">Usuario:</label>
				</td>
				<td>
					<input name="username" id="username" type="text" required />
				</td>
			</tr>
			<tr>
				<td>
					<label for="password">Contraseña:</label>
				</td>
				<td>
					<input name="password" id="password" type="password" required />
				</td>
			</tr>
			<tr>
				<td colspan="2" class="textCenter">
					<!--<input type="checkbox" id="remember" value="1" />
					<label for="remember">Recordarme en este equipo</label>-->
					<a href="https://www.proydesa.org/lms_new/login/forgot_password.php">¿Olvidó su nombre de usuario o contraseña?</a>
				</tr>
				</td>
			</table>
			<p align="center"><input id="signin_submit" value="Loguearse" tabindex="6" type="submit" class="ui-button ui-state-default ui-corner-all ui-button-text-only" /></p>
		</div>
	</form>
</div>
