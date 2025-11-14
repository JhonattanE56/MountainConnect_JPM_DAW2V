<?php

	/**
	 * Login page
	 */

	require_once __DIR__ . '/../includes/header.php';
	// Form validation
	if (isset($_POST['_form_sent']) && (bool)$_POST['_form_sent'] === true) {
		// Process the login form
		$data = $_POST;
		$database = $_SESSION;
		$user = FunctionsMountainConnect::crudAction($data['_form_data'], $data['_form_action'], $data, $database);
		if (!empty($user)) {
			$_SESSION['app']['user'] = [
					'id' => $user['id'],
					'username' => $user['username'],
			];
			header('Location: index.php');
			exit();
		}
//		$user = htmlspecialchars($_POST['user']);
//		$password = htmlspecialchars($_POST['pwd']);
//		// Determine if we are using username or email, we check if the input contains '@'.
//		$is_email = str_contains($user, '@') === true;
//		/*
//		 * Look if the user exists, this code takes the whole array, looks for every user if the value from the form matches the password
//		 */
//		$user_auth = array_filter($_SESSION['app']['users'], static fn($registered) =>
//				$user === ($is_email === true ? $registered['email'] : $registered['username']) && // Validate User or Email
//				password_verify($password, $registered['pwd'])); // Validate password
//		if (!empty($user_auth)) {
//			$_SESSION['app']['user'] = $user_auth[array_key_first($user_auth)]; // return the array values from inside the users array
//			header('Location: index.php');
//			exit();
//		}
		echo 'Usuario no encontrado';
	}
?>
	<div class="container mt-5" style="max-width: 400px;">
		<h1 class="mb-4 text-center">Inicio de sesión</h1>
		<div class="card shadow">
			<div class="card-body">
				<form action="#" method="post" autocomplete="off">
					<input type="hidden" name="_form_sent" value="true">
					<input type="hidden" name="_form_data" value="user">
					<input type="hidden" name="_form_action" value="login">
					<div class="mb-3">
						<label for="user" class="form-label">Usuario / Correo</label>
						<input type="text" name="user" id="user" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="pwd" class="form-label">Contraseña</label>
						<input type="password" name="pwd" id="pwd" class="form-control" required>
					</div>
					<button type="submit" class="btn btn-primary w-100">Enviar</button>
				</form>
				<div class="mt-3 text-center">
					<span>¿No tienes una cuenta? ¡Date de alta aquí!</span><br>
					<a href="register.php" class="btn btn-outline-success mt-2">Registro</a>
				</div>
			</div>
		</div>
	</div>
<?php
	require_once __DIR__ . '/../includes/footer.php';