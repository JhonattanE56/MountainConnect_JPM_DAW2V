<?php

	/*
	 * Registration page
	 */
	require_once __DIR__ . '/../includes/header.php';

	// Validate form
	if (isset($_POST['_form_sent']) && (bool)$_POST['_form_sent'] === true) {
		$data = $_POST;
		$database = $_SESSION;
		$user = FunctionsMountainConnect::crudAction($data['_form_data'], $data['_form_action'], $data, $database);
		if (!isset($user['errors'])) {
			$_SESSION['app']['users'][$user['id']] = $user;
			echo 'Usuario creado exitosamente, redireccionando al formulario de inicio de sesión.';
			header('refresh:3; url=login.php');
			exit();
		}
	}
	// Prepare error output
	$error_html = '';
	if (!empty($user['errors'])) {
		$error_html .= <<<HTML
        <div class="alert alert-danger">
            <h6>Se encontraron errores en el formulario:</h6>
            <ul class="mb-0">
    HTML;
		foreach ($user['errors'] as $field => $message) {
			$error_html .= <<<HTML
            <li>{$message}</li>
        HTML;
		}
		$error_html .= <<<HTML
            </ul>
        </div>
    HTML;
	}
?>
	<div class="container mt-5" style="max-width: 520px;">
		<h1 class="mb-4 text-center">Registro de usuario</h1>
		<div class="card shadow">
			<div class="card-body">
				<?= $error_html ?>
				<form action="#" method="post" autocomplete="off">
					<input type="hidden" name="_form_sent" value="true">
					<input type="hidden" name="_form_data" value="user">
					<input type="hidden" name="_form_action" value="register">

					<div class="mb-3">
						<label for="username" class="form-label">Usuario</label>
						<input type="text" name="username" id="username" class="form-control" value="<?= $_POST['username'] ?? '' ?>" required>
					</div>
					<div class="mb-3">
						<label for="email" class="form-label">Correo</label>
						<input type="email" name="email" id="email" class="form-control" value="<?= $_POST['email'] ?? '' ?>" required>
					</div>
					<div class="mb-3">
						<label for="pwd" class="form-label">Contraseña</label>
						<input type="password" name="pwd" id="pwd" class="form-control" minlength="<?= FunctionsMountainConnect::FORM_MIN_USER_PWD_LENGTH ?>" required>
					</div>
					<div class="mb-3">
						<label for="pwd_c" class="form-label">Confirmar contraseña</label>
						<input type="password" name="pwd_c" id="pwd_c" class="form-control" minlength="<?= FunctionsMountainConnect::FORM_MIN_USER_PWD_LENGTH ?>" required>
					</div>
					<div class="mb-3">
						<label for="experience" class="form-label">Nivel de experiencia</label>
						<select name="experience" id="experience" class="form-select" required>
							<option value="" selected disabled>Seleccione un valor</option>
							<option value="low">Bajo</option>
							<option value="medium">Medio</option>
							<option value="high">Alto</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="specialization" class="form-label">Especialidad</label>
						<input type="text" name="specialization" id="specialization" class="form-control" value="<?= $_POST['specialization'] ?? '' ?>" required>
					</div>
					<div class="mb-3">
						<label for="province" class="form-label">Provincia</label>
						<select name="province" id="province" class="form-select" required>
							<option value="" selected disabled>Seleccione provincia</option>
							<option value="andalucia">Andalucía</option>
							<option value="aragon">Aragón</option>
							<option value="asturias">Asturias</option>
							<option value="baleares">Baleares</option>
							<option value="canarias">Canarias</option>
							<option value="cantabria">Cantabria</option>
							<option value="castilla_leon">Castilla y León</option>
							<option value="castilla_mancha">Castilla La Mancha</option>
							<option value="catalunya">Cataluña</option>
							<option value="ceuta">Ceuta</option>
							<option value="valencia">Comunidad Valenciana</option>
							<option value="extremadura">Extremadura</option>
							<option value="galicia">Galicia</option>
							<option value="la_rioja">La Rioja</option>
							<option value="madrid">Madrid</option>
							<option value="melilla">Melilla</option>
							<option value="murcia">Murcia</option>
							<option value="navarra">Navarra</option>
							<option value="pais_vasco">País Vasco</option>
							<option value="other">Otra</option>
						</select>
					</div>
					<button type="submit" class="btn btn-success w-100">Crear cuenta</button>
				</form>
				<div class="mt-3 text-center">
					<span>¿Tienes cuenta? Inicia sesión aquí</span><br>
					<a href="login.php" class="btn btn-outline-primary mt-2">Login</a>
				</div>
			</div>
		</div>
	</div>
<?php
	require_once __DIR__ . '/../includes/footer.php';