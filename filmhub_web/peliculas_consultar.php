<?php
$currentPage = 'peliculas_consultar';
$pageTitle = 'Consultar Peliculas';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';

$user = requireAdmin();
$db = Database::getConnection();

$success = '';
$error = '';

// ===== ACCIONES =====
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int) $_GET['id'];
    $action = $_GET['action'];

    if ($action === 'activar' || $action === 'desactivar') {

        $nuevoEstado = $action === 'activar' ? 'activa' : 'inactiva';

        $apiUrl = "http://localhost/FilmHub/filmhub_api/index.php?route=peliculas/estado";

        $data = [
            "id" => $id,
            "estado" => $nuevoEstado
        ];

        $options = [
            "http" => [
                "header"  => "Content-Type: application/json",
                "method"  => "POST",
                "content" => json_encode($data)
            ]
        ];

        $context = stream_context_create($options);
        $response = file_get_contents($apiUrl, false, $context);

        if ($response === false) {
            $error = "No se pudo conectar con la API.";
        } else {
            $result = json_decode($response, true);

            if ($result && $result['status'] === 'success') {
                $success = "Estado actualizado correctamente.";
            } else {
                $error = "Error al actualizar estado desde la API.";
            }
        }
    }
}

// ===== MODIFICAR =====
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['edit_id'])) {

    $apiUrl = "http://localhost/FilmHub/filmhub_api/index.php?route=peliculas/editar";

    $ch = curl_init();

    $postData = [
        'id' => $_POST['edit_id'],
        'nombre' => $_POST['nombre'],
        'genero' => $_POST['genero'],
        'descripcion' => $_POST['descripcion'],
        'link_trailer' => $_POST['link_trailer']
    ];

    // Si hay imagen nueva
    if (!empty($_FILES['imagen']['tmp_name'])) {
        $postData['imagen'] = new CURLFile($_FILES['imagen']['tmp_name'], $_FILES['imagen']['type'], $_FILES['imagen']['name']);
    }

    curl_setopt($ch, CURLOPT_URL, $apiUrl);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: multipart/form-data']);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result && $result['status'] === 'success') {
        $success = "Película actualizada correctamente.";
    } else {
        $error = "Error al actualizar película.";
    }
}

// ===== OBTENER PELICULAS DESDE API =====
// Obtener todas las peliculas
//$peliculas = $db->query("SELECT * FROM peliculas ORDER BY created_at DESC")->fetchAll();

// Consumir API
$apiUrl = "http://localhost/FilmHub/filmhub_api/index.php?route=peliculas/admin";

$response = @file_get_contents($apiUrl);

if ($response === false) {
    $peliculas = [];
    $error = "No se pudo conectar con la API.";
} else {
    $data = json_decode($response, true);

    if (isset($data['status']) && $data['status'] === 'success') {
        $peliculas = $data['data'];
    } else {
        $peliculas = [];
        $error = "Error al obtener peliculas desde la API.";
    }
}

// Pelicula a editar (si aplica)
$editPeli = null;
if (isset($_GET['action']) && $_GET['action'] === 'editar' && isset($_GET['id'])) {
    $stmt = $db->prepare("SELECT * FROM peliculas WHERE id = :id");
    $stmt->execute([':id' => (int) $_GET['id']]);
    $editPeli = $stmt->fetch();
}

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
?>

<div class="main-content">
    <div class="page-header d-flex justify-content-between align-items-start">
        <div>
            <h2><i class="bi bi-collection-play me-2" style="color: var(--orange);"></i>Consultar Peliculas</h2>
            <p>Administra el catalogo de peliculas.</p>
        </div>
        <a href="peliculas_registrar.php" class="btn btn-orange btn-sm">
            <i class="bi bi-plus-circle me-1"></i> Nueva Pelicula
        </a>
    </div>

    <?php if ($success): ?>
        <div class="alert-success-custom mb-4"><i class="bi bi-check-circle me-1"></i> <?= htmlspecialchars($success) ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert-danger-custom mb-4"><i class="bi bi-exclamation-circle me-1"></i> <?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <!-- Modal de Edicion -->
    <?php if ($editPeli): ?>
    <div class="card-custom mb-4">
        <h5 style="color: var(--orange); font-weight: 700; margin-bottom: 16px;">
            <i class="bi bi-pencil-square me-1"></i> Modificar Pelicula
        </h5>
        <form method="POST" action="peliculas_consultar.php" enctype="multipart/form-data">
            <input type="hidden" name="edit_id" value="<?= $editPeli['id'] ?>">
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Nombre</label>
                    <input type="text" name="nombre" class="form-control" value="<?= htmlspecialchars($editPeli['nombre']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Genero</label>
                    <input type="text" name="genero" class="form-control" value="<?= htmlspecialchars($editPeli['genero']) ?>" required>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Imagen (dejar vacio para mantener actual)</label>
                    <input type="file" name="imagen" class="form-control" accept="image/jpeg,image/png,image/gif,image/webp">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Link del Trailer</label>
                    <input type="url" name="link_trailer" class="form-control" value="<?= htmlspecialchars($editPeli['link_trailer'] ?? '') ?>">
                </div>
                <div class="col-12">
                    <label class="form-label">Descripcion</label>
                    <textarea name="descripcion" class="form-control" rows="3" required><?= htmlspecialchars($editPeli['descripcion']) ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-orange me-2"><i class="bi bi-check-lg me-1"></i> Guardar Cambios</button>
                    <a href="peliculas_consultar.php" class="btn btn-outline-custom">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
    <?php endif; ?>

    <!-- Tabla de peliculas -->
    <div class="card-custom" style="overflow-x: auto;">
        <?php if (empty($peliculas)): ?>
            <div class="text-center py-5" style="color: var(--text-muted);">
                <i class="bi bi-collection-play" style="font-size: 3rem;"></i>
                <p class="mt-2">No hay peliculas registradas.</p>
            </div>
        <?php else: ?>
            <table class="table table-custom mb-0">
                <thead>
                    <tr>
                        <th>Imagen</th>
                        <th>Nombre</th>
                        <th>Genero</th>
                        <th>Descripcion</th>
                        <th>Estado</th>
                        <th style="width: 200px;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($peliculas as $peli): ?>
                    <tr>
                        <td>
                            <?php if ($peli['imagen']): ?>
                                <img src="/FilmHub/filmhub_api/uploads/<?= htmlspecialchars($peli['imagen']) ?>"
                                    alt="<?= htmlspecialchars($peli['nombre']) ?>"
                                    style="width: 60px; height: 80px; object-fit: cover; border-radius: 6px;">
                            <?php else: ?>
                                <div style="width: 60px; height: 80px; background: #222; border-radius: 6px; display: flex; align-items: center; justify-content: center;">
                                    <i class="bi bi-image" style="color: #444;"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-weight: 600;"><?= htmlspecialchars($peli['nombre']) ?></td>
                        <td><span style="color: var(--orange);"><?= htmlspecialchars($peli['genero']) ?></span></td>
                        <td>
                            <span style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 0.85rem; color: var(--text-secondary);">
                                <?= htmlspecialchars($peli['descripcion']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="<?= $peli['estado'] === 'activa' ? 'badge-active' : 'badge-inactive' ?>">
                                <?= ucfirst($peli['estado']) ?>
                            </span>
                        </td>
                        <td>
                            <?php if ($peli['estado'] === 'activa'): ?>
                                <a href="peliculas_consultar.php?action=desactivar&id=<?= $peli['id'] ?>" 
                                   class="btn btn-outline-custom btn-sm me-1" title="Desactivar"
                                   onclick="return confirm('Desactivar esta pelicula?')">
                                    <i class="bi bi-x-circle"></i>
                                </a>
                            <?php else: ?>
                                <a href="peliculas_consultar.php?action=activar&id=<?= $peli['id'] ?>" 
                                   class="btn btn-outline-custom btn-sm me-1" title="Activar"
                                   style="color: #4ade80; border-color: rgba(34,197,94,0.3);">
                                    <i class="bi bi-check-circle"></i>
                                </a>
                            <?php endif; ?>
                            <a href="peliculas_consultar.php?action=editar&id=<?= $peli['id'] ?>" 
                               class="btn btn-outline-custom btn-sm" title="Modificar"
                               style="color: var(--orange); border-color: rgba(249,115,22,0.3);">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
