<?php
require_once '../db/config.php';

$formation_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if (!$formation_id) {
    header('Location: index.php');
    exit;
}

// Récupération de la formation
try {
    $stmt = $pdo->prepare("SELECT * FROM formations WHERE id = ?");
    $stmt->execute([$formation_id]);
    $formation = $stmt->fetch();
    
    if (!$formation) {
        header('Location: index.php');
        exit;
    }
} catch (PDOException $e) {
    header('Location: index.php');
    exit;
}

// Fonction pour obtenir le prix fixe d'une formation
function getFixedPrice($formation_titre)
{
    $prix_fixes = [
        'Gestion de Projet Agile' => 9000,
        'Développement Web Full Stack' => 15000,
        'Marketing Digital' => 7500,
        'Data Science avec Python' => 12000
    ];
    
    return isset($prix_fixes[$formation_titre]) ? $prix_fixes[$formation_titre] : 35000;
}

// Fonction pour afficher le prix formaté
function displayPrice($prix)
{
    return number_format($prix, 0, ',', ' ') . ' F CFA';
}

$prix_corrige = getFixedPrice($formation['titre']);
$page_title = htmlspecialchars($formation['titre']) . ' - FormaCatalogue';
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/script.js';
$home_path = 'index.php';
$contact_path = 'contact.php';
$admin_path = '../admin/login.php';

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container py-5">
    <div class="row">
        <!-- Contenu principal -->
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <img src="<?php echo htmlspecialchars($formation['image']); ?>" 
                     class="card-img-top" 
                     alt="<?php echo htmlspecialchars($formation['titre']); ?>"
                     style="height: 300px; object-fit: cover;">
                
                <div class="card-body">
                    <div class="d-flex gap-2 mb-3">
                        <span class="badge bg-primary"><?php echo htmlspecialchars($formation['categorie']); ?></span>
                        <span class="badge bg-secondary"><?php echo htmlspecialchars($formation['niveau']); ?></span>
                    </div>
                    
                    <h1 class="card-title fw-bold mb-4"><?php echo htmlspecialchars($formation['titre']); ?></h1>
                    <p class="lead text-muted mb-4"><?php echo htmlspecialchars($formation['description']); ?></p>
                    
                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-clock text-primary me-2"></i>
                                <div>
                                    <div class="fw-semibold">Durée</div>
                                    <div class="text-muted"><?php echo htmlspecialchars($formation['duree']); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-users text-primary me-2"></i>
                                <div>
                                    <div class="fw-semibold">Niveau</div>
                                    <div class="text-muted"><?php echo htmlspecialchars($formation['niveau']); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="d-flex align-items-center">
                                <i class="fas fa-coins text-success me-2"></i>
                                <div>
                                    <div class="fw-semibold">Prix</div>
                                    <div class="text-success fw-bold"><?php echo displayPrice($prix_corrige); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="h5 fw-bold mb-3">
                            <i class="fas fa-target text-primary me-2"></i>
                            Objectifs de la formation
                        </h3>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($formation['objectifs'])); ?></p>
                    </div>
                    
                    <div class="mb-4">
                        <h3 class="h5 fw-bold mb-3">
                            <i class="fas fa-check-circle text-primary me-2"></i>
                            Prérequis
                        </h3>
                        <p class="text-muted"><?php echo nl2br(htmlspecialchars($formation['prerequisites'])); ?></p>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="col-lg-4">
            <div class="card shadow-sm sticky-top" style="top: 2rem;">
                <div class="card-body text-center">
                    <div class="mb-4">
                        <div class="h2 text-success fw-bold mb-2">
                            <?php echo displayPrice($prix_corrige); ?>
                        </div>
                        <p class="text-muted">Prix de la formation</p>
                    </div>
                    
                    <a href="inscription.php?id=<?php echo $formation['id']; ?>" 
                       class="btn btn-primary btn-lg w-100 mb-3">
                        <i class="fas fa-user-plus me-2"></i>
                        S'inscrire maintenant
                    </a>
                    
                    <a href="index.php" 
                       class="btn btn-outline-secondary w-100 mb-4">
                        <i class="fas fa-arrow-left me-2"></i>
                        Voir toutes les formations
                    </a>
                    
                    <div class="border-top pt-4">
                        <h6 class="fw-bold mb-3">
                            <i class="fas fa-gift me-2"></i>
                            Inclus dans la formation
                        </h6>
                        <div class="text-start small text-muted">
                            <div class="mb-2">
                                <i class="fas fa-certificate text-primary me-2"></i>
                                Certificat de formation
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-book text-primary me-2"></i>
                                Support de cours complet
                            </div>
                            <div class="mb-2">
                                <i class="fas fa-laptop text-primary me-2"></i>
                                Accès aux ressources en ligne
                            </div>
                            <div>
                                <i class="fas fa-headset text-primary me-2"></i>
                                Suivi post-formation
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>