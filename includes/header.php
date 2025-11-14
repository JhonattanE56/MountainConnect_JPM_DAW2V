<?php

	/**
	 * Main header file
	 */

	// Start the session
	session_start();
	// Initialize session data structure if missing
	if (!isset($_SESSION['app'])) {
		$_SESSION['app'] = [
				'users'   => [],
				'climbings' => [],
				'ferratas' => [],
				'routes'  => [],
		];
	}
	require_once __DIR__ . '/../includes/functions.php';
	// Fix routes up to one (1) subfolder
	$check_path = FunctionsMountainConnect::generatePath($_SERVER['REQUEST_URI']);
?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js" integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous"></script>
	<title>Mountain Connect</title>
</head>
<body>
<header class="container-fluid bg-primary text-white mb-4 py-3">
	<div class="container d-flex align-items-center justify-content-between">
		<div>
			<h1 class="h3 mb-0">Mountain Connect</h1>
		</div>
		<div class="d-flex align-items-center gap-2">
			<a class="btn btn-outline-light" href="<?= $check_path ?>routes/list.php">Rutas</a>
			<a class="btn btn-outline-light" href="#">Escaladas</a>
			<a class="btn btn-outline-light" href="#">Ferratas</a>

			<?php
				if (isset($_SESSION['app']['user'])): ?>
					<a class="btn btn-outline-light" href="<?= $check_path ?>profile.php?module=users&id=<?= $_SESSION['app']['user']['id'] ?>">
						<?= htmlspecialchars($_SESSION['app']['user']['username']) ?>
					</a>
					<a class="btn btn-danger" href="<?= $check_path ?>logout.php">Logout</a>
				<?php
				else: ?>
					<a class="btn btn-outline-light" href="<?= $check_path ?>login.php">Login</a>
					<a class="btn btn-outline-light" href="<?= $check_path ?>register.php">Registrarse</a>
				<?php
				endif; ?>
		</div>
	</div>
</header>

<main class="container">
	<!-- Main content -->
