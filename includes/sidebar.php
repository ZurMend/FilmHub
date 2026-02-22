<?php
/**
 * Sidebar global - se incluye en todas las paginas del panel.
 * Variable esperada: $currentPage (string) para marcar la pagina activa.
 */
$currentPage = $currentPage ?? 'dashboard';
$userName = $_SESSION['user_name'] ?? 'Admin';
?>
<!-- Sidebar Overlay (mobile) -->
<div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleSidebar()"></div>

<!-- Sidebar -->
<nav class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h2 class="sidebar-brand"><i class="bi bi-film"></i> FilmHub</h2>
        <button class="sidebar-close d-lg-none" onclick="toggleSidebar()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="sidebar-nav">
        <a href="dashboard.php" class="sidebar-link <?= $currentPage === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-grid-1x2-fill"></i>
            <span>Dashboard</span>
        </a>
        <a href="peliculas_registrar.php" class="sidebar-link <?= $currentPage === 'peliculas_registrar' ? 'active' : '' ?>">
            <i class="bi bi-plus-circle-fill"></i>
            <span>Registrar Pelicula</span>
        </a>
        <a href="peliculas_consultar.php" class="sidebar-link <?= $currentPage === 'peliculas_consultar' ? 'active' : '' ?>">
            <i class="bi bi-collection-play-fill"></i>
            <span>Consultar Peliculas</span>
        </a>
        <a href="clientes.php" class="sidebar-link <?= $currentPage === 'clientes' ? 'active' : '' ?>">
            <i class="bi bi-people-fill"></i>
            <span>Clientes</span>
        </a>
        <a href="usuarios.php" class="sidebar-link <?= $currentPage === 'usuarios' ? 'active' : '' ?>">
            <i class="bi bi-person-badge-fill"></i>
            <span>Usuarios</span>
        </a>
    </div>

    <div class="sidebar-footer">
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <i class="bi bi-person-fill"></i>
            </div>
            <div class="sidebar-user-info">
                <span class="sidebar-user-name"><?= htmlspecialchars($userName) ?></span>
                <span class="sidebar-user-role">Administrador</span>
            </div>
        </div>
        <a href="logout.php" class="sidebar-link sidebar-logout">
            <i class="bi bi-box-arrow-left"></i>
            <span>Cerrar Sesion</span>
        </a>
    </div>
</nav>

<!-- Toggle button (mobile) -->
<button class="sidebar-toggle d-lg-none" onclick="toggleSidebar()">
    <i class="bi bi-list"></i>
</button>

<script>
function toggleSidebar() {
    document.getElementById('sidebar').classList.toggle('show');
    document.getElementById('sidebarOverlay').classList.toggle('show');
}
</script>
