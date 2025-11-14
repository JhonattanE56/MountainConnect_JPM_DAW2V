<?php
    
    require_once __DIR__ . '/../../includes/header.php';
?>
	<div class="container py-4">
		<?php if (empty($_SESSION['app']['routes'])): ?>
			<div class="alert alert-info text-center my-5" role="alert">
				<h4 class="alert-heading">No hay rutas disponibles</h4>
				<p>¡Todavía no se ha publicado ninguna ruta!<br>
					Sé el primero en dar de alta un recorrido para la comunidad.</p>
			</div>
		<?php else: ?>
			<?php foreach($_SESSION['app']['routes'] as $id => $route): ?>
				<div class="card mb-4 shadow-sm flex-row" style="max-height: 220px;">
					<div style="width:260px;">
						<?php if (!empty($route['images'])): ?>
							<div id="carouselRoute<?= $id ?>" class="carousel slide h-100" data-bs-ride="carousel">
								<div class="carousel-inner h-100">
									<?php foreach($route['images'] as $img_idx => $img_path): ?>
										<div class="carousel-item<?= $img_idx === 0 ? ' active' : '' ?> h-100">
											<img src="../../uploads/photos/<?= htmlspecialchars($img_path) ?>"
												 class="d-block w-100 h-100"
												 alt="Imagen de la ruta"
												 style="object-fit:cover; border-radius:0.5rem 0 0 0.5rem; max-height: 220px;">
										</div>
									<?php endforeach; ?>
								</div>
								<?php if (count($route['images']) > 1): ?>
									<button class="carousel-control-prev" type="button" data-bs-target="#carouselRoute<?= $id ?>" data-bs-slide="prev">
										<span class="carousel-control-prev-icon"></span>
										<span class="visually-hidden">Anterior</span>
									</button>
									<button class="carousel-control-next" type="button" data-bs-target="#carouselRoute<?= $id ?>" data-bs-slide="next">
										<span class="carousel-control-next-icon"></span>
										<span class="visually-hidden">Siguiente</span>
									</button>
								<?php endif; ?>
							</div>
						<?php else: ?>
							<img src="../../assets/images/placeholder.png"
								 class="d-block w-100 h-100"
								 alt="Sin imagen"
								 style="object-fit:cover; border-radius:0.5rem 0 0 0.5rem; max-height: 220px;">
						<?php endif; ?>
					</div>
					<div class="card-body d-flex flex-column justify-content-between">
						<div>
							<h4 class="card-title"><?= $route['name'] ?></h4>
							<p class="card-text"><?= $route['description'] ?></p>
						</div>
						<div>
							<a href="view.php?module=routes&id=<?= $route['id'] ?>" class="btn btn-primary btn-sm me-2">Ver Detalles</a>
							<span class="badge bg-secondary">Nivel: <?= $route['physical_level']?></span>
						</div>
					</div>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>

		<div class="text-end mt-4">
			<a href="create.php" class="btn btn-success btn-lg">
				+ Crear nueva ruta
			</a>
		</div>
	</div>

<?php
    require_once __DIR__ . '/../../includes/footer.php';
