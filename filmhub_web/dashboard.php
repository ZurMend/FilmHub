<?php
$currentPage = 'dashboard';
$pageTitle = 'Dashboard';

require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/helpers/auth_helper.php';

$user = requireAdmin();

// Obtener las ultimas 5 peliculas
$db = Database::getConnection();
$stmt = $db->query("SELECT * FROM peliculas ORDER BY created_at DESC LIMIT 5");
$peliculas = $stmt->fetchAll();

// Estadisticas
$totalPeliculas = $db->query("SELECT COUNT(*) FROM peliculas")->fetchColumn();
$totalClientes  = $db->query("SELECT COUNT(*) FROM users WHERE role = 'cliente'")->fetchColumn();
$totalUsuarios  = $db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
$peliculasActivas = $db->query("SELECT COUNT(*) FROM peliculas WHERE estado = 'activa'")->fetchColumn();

require_once __DIR__ . '/includes/header.php';
require_once __DIR__ . '/includes/sidebar.php';
define('UPLOADS_URL', '../filmhub_api/uploads/');
?>

<div class="main-content">
    <div class="page-header">
        <h2>Dashboard</h2>
        <p>Bienvenido, <?= htmlspecialchars($user['nombre']) ?>. Aqui tienes un resumen de FilmHub.</p>
    </div>

    <!-- Stats Cards -->
    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="card-custom text-center">
                <div style="font-size: 2rem; color: var(--orange);"><i class="bi bi-film"></i></div>
                <div style="font-size: 1.8rem; font-weight: 700; color: #fff;"><?= $totalPeliculas ?></div>
                <div style="color: var(--text-secondary); font-size: 0.85rem;">Peliculas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-custom text-center">
                <div style="font-size: 2rem; color: #4ade80;"><i class="bi bi-check-circle"></i></div>
                <div style="font-size: 1.8rem; font-weight: 700; color: #fff;"><?= $peliculasActivas ?></div>
                <div style="color: var(--text-secondary); font-size: 0.85rem;">Activas</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-custom text-center">
                <div style="font-size: 2rem; color: #60a5fa;"><i class="bi bi-people"></i></div>
                <div style="font-size: 1.8rem; font-weight: 700; color: #fff;"><?= $totalClientes ?></div>
                <div style="color: var(--text-secondary); font-size: 0.85rem;">Clientes</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="card-custom text-center">
                <div style="font-size: 2rem; color: #c084fc;"><i class="bi bi-person-badge"></i></div>
                <div style="font-size: 1.8rem; font-weight: 700; color: #fff;"><?= $totalUsuarios ?></div>
                <div style="color: var(--text-secondary); font-size: 0.85rem;">Admins</div>
            </div>
        </div>
    </div>

    <!-- Ultimas 5 Peliculas -->
    <div class="card-custom">
        <h5 style="color: var(--orange); font-weight: 700; margin-bottom: 20px;">
            <i class="bi bi-clock-history me-2"></i>Ultimas Peliculas Agregadas
        </h5>

        <?php if (empty($peliculas)): ?>
            <div class="text-center py-5" style="color: var(--text-muted);">
                <i class="bi bi-collection-play" style="font-size: 3rem;"></i>
                <p class="mt-2">Aun no hay peliculas registradas.</p>
                <a href="peliculas_registrar.php" class="btn btn-orange btn-sm mt-2">
                    <i class="bi bi-plus-circle me-1"></i> Registrar Primera Pelicula
                </a>
            </div>
        <?php else: ?>
            <div class="row g-3">
                <?php foreach ($peliculas as $peli): ?>
                    <div class="col-12 col-sm-6 col-lg-4">
                        <div class="movie-card" 
                             data-trailer="<?= htmlspecialchars($peli['link_trailer'] ?? '') ?>"
                             style="background: var(--dark-input); border: 1px solid var(--border-color); border-radius: 12px; overflow: hidden; cursor: pointer; position: relative;">
                            
                            <!-- Imagen -->
                            <div class="movie-poster" style="position: relative; height: 200px; overflow: hidden;">
                                <?php if ($peli['imagen']): ?>
                                    <img src="<?= UPLOADS_URL . htmlspecialchars($peli['imagen']) ?>" 
                                        alt="<?= htmlspecialchars($peli['nombre']) ?>"
                                        style="width: 100%; height: 100%; object-fit: cover; object-position: center;">
                                <?php else: ?>
                                    <div style="width: 100%; height: 100%; background: #222; display: flex; align-items: center; justify-content: center;">
                                        <i class="bi bi-film" style="font-size: 3rem; color: #333;"></i>
                                    </div>
                                <?php endif; ?>
                                
                                <!-- Iframe de trailer (oculto por defecto) -->
                                <div class="trailer-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; display: none; background: #000;">
                                    <iframe class="trailer-iframe" width="100%" height="100%" frameborder="0" 
                                            allow="autoplay; encrypted-media" allowfullscreen
                                            style="pointer-events: none;"></iframe>
                                </div>

                                <!-- Badge de estado -->
                                <span style="position: absolute; top: 8px; right: 8px;" 
                                      class="<?= $peli['estado'] === 'activa' ? 'badge-active' : 'badge-inactive' ?>">
                                    <?= ucfirst($peli['estado']) ?>
                                </span>
                            </div>

                            <!-- Info -->
                            <div style="padding: 14px;">
                                <h6 style="color: #fff; font-weight: 600; margin: 0 0 4px; font-size: 0.95rem;">
                                    <?= htmlspecialchars($peli['nombre']) ?>
                                </h6>
                                <span style="color: var(--orange); font-size: 0.8rem; font-weight: 500;">
                                    <?= htmlspecialchars($peli['genero']) ?>
                                </span>
                                <p style="color: var(--text-secondary); font-size: 0.8rem; margin: 8px 0 0; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden;">
                                    <?= htmlspecialchars($peli['descripcion']) ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
// Hover para reproducir trailer
document.querySelectorAll('.movie-card').forEach(card => {
    const trailerUrl = card.dataset.trailer;
    if (!trailerUrl) return;

    const overlay = card.querySelector('.trailer-overlay');
    const iframe = card.querySelector('.trailer-iframe');
    let timeout;

    // Extraer video ID de YouTube
    function getYouTubeEmbedUrl(url) {
        let videoId = '';
        // youtu.be/VIDEO_ID
        const shortMatch = url.match(/youtu\.be\/([a-zA-Z0-9_-]+)/);
        if (shortMatch) { videoId = shortMatch[1]; }
        // youtube.com/watch?v=VIDEO_ID
        const longMatch = url.match(/[?&]v=([a-zA-Z0-9_-]+)/);
        if (longMatch) { videoId = longMatch[1]; }
        // youtube.com/embed/VIDEO_ID
        const embedMatch = url.match(/embed\/([a-zA-Z0-9_-]+)/);
        if (embedMatch) { videoId = embedMatch[1]; }

        if (videoId) {
            return 'https://www.youtube.com/embed/' + videoId + '?autoplay=1&mute=1&controls=0&loop=1&playlist=' + videoId;
        }
        return null;
    }

    card.addEventListener('mouseenter', () => {
        const embedUrl = getYouTubeEmbedUrl(trailerUrl);
        if (embedUrl) {
            timeout = setTimeout(() => {
                iframe.src = embedUrl;
                overlay.style.display = 'block';
            }, 300);
        }
    });

    card.addEventListener('mouseleave', () => {
        clearTimeout(timeout);
        iframe.src = '';
        overlay.style.display = 'none';
    });
});
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
