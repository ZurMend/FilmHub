<?php
$currentPage = 'peliculas_registrar';
$pageTitle = 'Registrar Pelicula';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';

$user = requireAdmin();

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $genero      = trim($_POST['genero'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $linkTrailer = trim($_POST['link_trailer'] ?? '');

    if (empty($nombre) || empty($genero) || empty($descripcion)) {
        $error = 'Los campos Nombre, Genero y Descripcion son obligatorios.';
    } else {
        $imagenNombre = null;

        // Procesar imagen si se subio
        if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
            $fileType = $_FILES['imagen']['type'];

            if (!in_array($fileType, $allowedTypes)) {
                $error = 'Solo se permiten imagenes JPG, PNG, GIF o WebP.';
            } else {
                $ext = pathinfo($_FILES['imagen']['name'], PATHINFO_EXTENSION);
                $imagenNombre = uniqid('pelicula_') . '.' . $ext;
                $uploadPath = UPLOAD_DIR . $imagenNombre;

                // Crear directorio si no existe
                if (!is_dir(UPLOAD_DIR)) {
                    mkdir(UPLOAD_DIR, 0755, true);
                }

                if (!move_uploaded_file($_FILES['imagen']['tmp_name'], $uploadPath)) {
                    $error = 'Error al subir la imagen. Verifica los permisos de la carpeta uploads.';
                    $imagenNombre = null;
                }
            }
        }

        if (empty($error)) {
            try {
                $db = Database::getConnection();
                $stmt = $db->prepare("
                    INSERT INTO peliculas (nombre, genero, descripcion, imagen, link_trailer, estado)
                    VALUES (:nombre, :genero, :desc, :img, :trailer, 'activa')
                ");
                $stmt->execute([
                    ':nombre'  => $nombre,
                    ':genero'  => $genero,
                    ':desc'    => $descripcion,
                    ':img'     => $imagenNombre,
                    ':trailer' => $linkTrailer ?: null,
                ]);
                $success = 'Pelicula registrada exitosamente.';
            } catch (Exception $e) {
                $error = 'Error al registrar la pelicula: ' . $e->getMessage();
            }
        }
    }
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header">
        <h2><i class="bi bi-plus-circle me-2" style="color: var(--orange);"></i>Registrar Pelicula</h2>
        <p>Agrega una nueva pelicula al catalogo de FilmHub.</p>
    </div>

    <?php if ($success): ?>
        <div class="alert-success-custom mb-4">
            <i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?>
            <a href="peliculas_consultar.php" style="color: #4ade80; margin-left: 10px; font-weight: 600;">Ver peliculas &rarr;</a>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert-danger-custom mb-4">
            <i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?>
        </div>
    <?php endif; ?>

    <div class="card-custom" style="max-width: 700px;">
        <form method="POST" action="peliculas_registrar.php" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nombre de la Pelicula <span style="color: #ef4444;">*</span></label>
                <input type="text" name="nombre" class="form-control" placeholder="Ej: Avengers: Endgame"
                       value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Genero <span style="color: #ef4444;">*</span></label>
                <input type="text" name="genero" class="form-control" placeholder="Ej: Accion, Ciencia Ficcion"
                       value="<?= htmlspecialchars($_POST['genero'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Cargar Imagen</label>
                <input type="file" name="imagen" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                <small style="color: var(--text-muted);">Formatos: JPG, PNG, GIF, WebP. Se guardara en la carpeta /uploads.</small>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripcion <span style="color: #ef4444;">*</span></label>
                <textarea name="descripcion" class="form-control" rows="4" placeholder="Sinopsis o descripcion de la pelicula..."
                          required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Link del Trailer (YouTube)</label>
                <input type="url" name="link_trailer" class="form-control" placeholder="https://www.youtube.com/watch?v=..."
                       value="<?= htmlspecialchars($_POST['link_trailer'] ?? '') ?>">
                <small style="color: var(--text-muted);">Pega el enlace completo de YouTube.</small>
            </div>

            <button type="submit" class="btn btn-orange">
                <i class="bi bi-check-lg me-1"></i> Registrar Pelicula
            </button>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
