<?php
$current_page = basename($_SERVER['PHP_SELF']);
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="<?php echo isset($home_path) ? $home_path : 'index.php'; ?>">
            <img src="<?php echo isset($logo_path) ? $logo_path : '../assets/images/logo.png'; ?>" alt="Forma" height="40" class="me-2" onerror="this.style.display='none'">
            <span class="fw-bold">FormaCatalogue</span>
        </a>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'index.php') ? 'active' : ''; ?>" 
                       href="<?php echo isset($home_path) ? $home_path : 'index.php'; ?>">
                        <i class="fas fa-home me-1"></i>Accueil
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo ($current_page == 'contact.php') ? 'active' : ''; ?>" 
                       href="<?php echo isset($contact_path) ? $contact_path : 'contact.php'; ?>">
                        <i class="fas fa-envelope me-1"></i>Contact
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?php echo isset($admin_path) ? $admin_path : '../admin/login.php'; ?>">
                        <i class="fas fa-user-shield me-1"></i>Admin
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>