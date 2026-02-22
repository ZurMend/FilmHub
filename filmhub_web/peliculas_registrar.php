<?php
$currentPage = 'peliculas_registrar';
$pageTitle = 'Registrar Pelicula';

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

        $apiUrl = "http://localhost/FilmHub/filmhub_api/index.php?route=peliculas/crear";

        $ch = curl_init();

        $postData = [
            'nombre'       => $nombre,
            'genero'       => $genero,
            'descripcion'  => $descripcion,
            'link_trailer' => $linkTrailer
        ];

        // Si hay imagen
        if (!empty($_FILES['imagen']['tmp_name'])) {
            $postData['imagen'] = new CURLFile(
                $_FILES['imagen']['tmp_name'],
                $_FILES['imagen']['type'],
                $_FILES['imagen']['name']
            );
        }

        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        curl_close($ch);

        $result = json_decode($response, true);

        if ($result && $result['status'] === 'success') {
            $success = 'Pelicula registrada exitosamente.';
            $_POST = []; // Limpia el formulario
        } else {
            $error = 'Error al registrar la pelicula desde la API.';
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
            <a href="peliculas_consultar.php" style="color: #4ade80; margin-left: 10px; font-weight: 600;">
                Ver peliculas &rarr;
            </a>
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
                <input type="text" name="nombre" class="form-control"
                       value="<?= htmlspecialchars($_POST['nombre'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Genero <span style="color: #ef4444;">*</span></label>
                <input type="text" name="genero" class="form-control"
                       value="<?= htmlspecialchars($_POST['genero'] ?? '') ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Cargar Imagen</label>
                <input type="file" name="imagen" class="form-control"
                       accept="image/jpeg,image/png,image/gif,image/webp">
                <small style="color: var(--text-muted);">
                    La imagen se guardara en la API (/filmhub_api/uploads).
                </small>
            </div>

            <div class="mb-3">
                <label class="form-label">Descripcion <span style="color: #ef4444;">*</span></label>
                <textarea name="descripcion" class="form-control" rows="4"
                          required><?= htmlspecialchars($_POST['descripcion'] ?? '') ?></textarea>
            </div>

            <div class="mb-4">
                <label class="form-label">Link del Trailer</label>
                <input type="url" name="link_trailer" class="form-control"
                       value="<?= htmlspecialchars($_POST['link_trailer'] ?? '') ?>">
            </div>

            <button type="submit" class="btn btn-orange">
                <i class="bi bi-check-lg me-1"></i> Registrar Pelicula
            </button>

        </form>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>