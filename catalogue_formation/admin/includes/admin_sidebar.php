<?php
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';
$host = $_SERVER['HTTP_HOST'];
$base_url = $protocol . $host;

$script_dir = dirname($_SERVER['SCRIPT_NAME']);
$project_root = '';

if (strpos($script_dir, '/admin') !== false) {
    $project_root = substr($script_dir, 0, strpos($script_dir, '/admin'));
}

$admin_base_url = $base_url . $project_root . '/admin/';
$current_file = basename($_SERVER['PHP_SELF']);
?>

<nav class="col-md-3 col-lg-2 d-md-block bg-dark sidebar">
    <div class="position-sticky pt-3">
        <div class="text-center text-white mb-4">
            <i class="fas fa-graduation-cap fa-2x mb-2"></i>
            <h5 class="fw-bold">FormaCatalogue</h5>
            <small class="text-white-50">Panel Admin</small>
        </div>
        
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white <?php echo $current_file == 'dashboard.php' ? 'active' : ''; ?>" 
                   href="<?php echo $admin_base_url; ?>dashboard.php">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Tableau de bord
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'formations') !== false ? 'active' : ''; ?>" 
                   href="<?php echo $admin_base_url; ?>formations/list.php">
                    <i class="fas fa-book-open me-2"></i>
                    Formations
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'utilisateurs') !== false ? 'active' : ''; ?>" 
                   href="<?php echo $admin_base_url; ?>utilisateurs/list.php">
                    <i class="fas fa-users me-2"></i>
                    Utilisateurs
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-white <?php echo strpos($_SERVER['PHP_SELF'], 'inscriptions') !== false ? 'active' : ''; ?>" 
                   href="<?php echo $admin_base_url; ?>inscriptions/list.php">
                    <i class="fas fa-user-check me-2"></i>
                    Inscriptions
                </a>
            </li>
        </ul>
        
        <div class="mt-auto mb-3">
            <a href="../frontend/index.php" class="btn btn-outline-light btn-sm d-block mb-2" target="_blank">
                <i class="fas fa-external-link-alt me-2"></i>
                Voir le site
            </a>
            <a href="<?php echo $admin_base_url; ?>logout.php" class="btn btn-danger btn-sm d-block">
                <i class="fas fa-sign-out-alt me-2"></i>
                Déconnexion
            </a>
        </div>
    </div>
</nav>