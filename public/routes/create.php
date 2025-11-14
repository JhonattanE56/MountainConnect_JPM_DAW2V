<?php

	/**
	 * Processes creation of new routes
	 */
	require_once __DIR__ . '/../../includes/header.php';

	if (isset($_POST['_form_sent']) && (bool)$_POST['_form_sent'] === true) {
		$data = array_merge($_POST, $_FILES);
		$database = $_SESSION;
		$route = FunctionsMountainConnect::crudAction($data['_form_data'], $data['_form_action'], $data, $database);
		if (!empty($route)) {
			$_SESSION['app']['routes'][$route['id']] = $route;
			echo 'Ruta creada exitosamente, redireccionando al listado de rutas';
			header('refresh: 2; url=list.php');
			exit();
		}
	}
?>
	<div class="container mt-5" style="max-width: 520px;">
		<h1 class="mb-4 text-center">Nueva ruta</h1>
		<div class="card shadow">
			<div class="card-body">
				<form action="#" method="post" autocomplete="off" enctype="multipart/form-data">
					<input type="hidden" name="_form_sent" value="true">
					<input type="hidden" name="_form_data" value="route">
					<input type="hidden" name="_form_action" value="create">

					<div class="mb-3">
						<label for="name" class="form-label">Nombre de la ruta</label>
						<input type="text" name="name" id="name" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="difficulty" class="form-label">Dificultad</label>
						<select name="difficulty" id="difficulty" class="form-select" required>
							<option selected disabled>Selecciona dificultad</option>
							<option value="easy">Fácil</option>
							<option value="moderate">Moderada</option>
							<option value="hard">Difícil</option>
							<option value="very_hard">Muy Difícil</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="distance" class="form-label">Distancia (KM)</label>
						<input type="number" name="distance" id="distance" class="form-control" step="0.1" min="0" required>
					</div>
					<div class="mb-3">
						<label for="elevation" class="form-label">Desnivel positivo (MTS)</label>
						<input type="number" name="elevation" id="elevation" class="form-control" step="1" min="0" required>
					</div>
					<div class="mb-3">
						<label for="duration" class="form-label">Duración estimada (Horas)</label>
						<input type="number" name="duration" id="duration" class="form-control" step="0.1" min="0" required>
					</div>
					<div class="mb-3">
						<label for="province" class="form-label">Provincia</label>
						<select name="province" id="province" class="form-select" required>
							<option selected disabled>Seleccione provincia</option>
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
					<div class="mb-3">
						<label class="form-label">Época recomendada</label>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="season[]" id="primavera" value="primavera">
							<label class="form-check-label" for="primavera">Primavera</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="season[]" id="verano" value="verano">
							<label class="form-check-label" for="verano">Verano</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="season[]" id="otoño" value="otoño">
							<label class="form-check-label" for="otoño">Otoño</label>
						</div>
						<div class="form-check form-check-inline">
							<input class="form-check-input" type="checkbox" name="season[]" id="invierno" value="invierno">
							<label class="form-check-label" for="invierno">Invierno</label>
						</div>
					</div>
					<div class="mb-3">
						<label for="description" class="form-label">Descripción</label>
						<textarea name="description" id="description" class="form-control" rows="3" required></textarea>
					</div>
					<div class="mb-3">
						<label for="tech_level" class="form-label">Nivel técnico (1-5)</label>
						<select name="tech_level" id="tech_level" class="form-select" required>
							<option selected disabled>Selecciona nivel técnico</option>
							<option value="1">1 - Muy bajo</option>
							<option value="2">2 - Bajo</option>
							<option value="3">3 - Medio</option>
							<option value="4">4 - Alto</option>
							<option value="5">5 - Muy alto</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="physical_level" class="form-label">Nivel físico (1-5)</label>
						<select name="physical_level" id="physical_level" class="form-select" required>
							<option selected disabled>Selecciona nivel físico</option>
							<option value="1">1 - Muy bajo</option>
							<option value="2">2 - Bajo</option>
							<option value="3">3 - Medio</option>
							<option value="4">4 - Alto</option>
							<option value="5">5 - Muy alto</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="images" class="form-label">Imágenes de la ruta (puedes seleccionar varias)</label>
						<input class="form-control" type="file" name="images[]" id="images" accept="image/*" multiple>
					</div>
					<button type="submit" class="btn btn-success w-100">Guardar ruta</button>
				</form>
			</div>
		</div>
	</div>
<?php
	require_once __DIR__ . '/../../includes/footer.php';
