<?php

	/**
	 * First page, first we check if the user has already logged in
	 * If so, redirect to the main application page
	 */
	require_once __DIR__ . '/../includes/header.php';
?>
<?php
	if (!isset($_SESSION['app']['user'])): ?>
		<div class="container py-4">
			<div class="alert alert-primary text-center" role="alert">
				<h4 class="alert-heading">¡Bienvenido a Mountain Connect!</h4>
				<p>
					Descubre rutas, comparte tus aventuras y conecta con otros amantes de la montaña.
				</p>
			</div>
			<div class="alert alert-info text-center" role="alert">
				Para aprovechar todas las funcionalidades del sitio, <a href="register.php" style="text-decoration: none"><b>crea una cuenta</b></a> o <a href="login.php" style="text-decoration: none"><b>inicia sesión</b></a>.<br>
				Solo los usuarios registrados pueden ver el contenido completo y participar activamente en la comunidad.
			</div>
			<div class="alert alert-warning text-center" role="alert">
				¿A qué esperas? ¡Únete hoy y sé parte de nuestra comunidad de senderistas y exploradores!
			</div>
		</div>
	<?php
	else: ?>
		<div class="alert alert-success text-center" role="alert">
			No olvides visitar las diferentes categorías del sitio: <b>rutas</b>, <b>vías ferratas</b> y <b>zonas de escalada</b>. ¡Hay mucho por descubrir y compartir en cada sección!
		</div>
	<?php
	endif; ?>
<?php
	require_once __DIR__ . '/../includes/footer.php';