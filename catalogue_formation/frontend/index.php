<?php
require_once '../db/config.php';

$page_title = 'Accueil - FormaCatalogue';
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/script.js';
$logo_path = '../assets/images/logo.png';
$contact_path = 'contact.php';
$admin_path = '../admin/login.php';

// Récupération des formations
try {
    $stmt = $pdo->query("SELECT * FROM formations ORDER BY date_creation DESC");
    $formations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $formations = [];
    $error = "Erreur lors du chargement des formations : " . $e->getMessage();
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

include '../includes/header.php';
include '../includes/navbar.php';
?>

<!-- Hero Section -->
<section class="hero-section">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-8">
                <h1 class="display-4 fw-bold mb-4">Boostez votre carrière avec nos formations continues à Dakar</h1>
                <p class="lead mb-2">Découvrez un large éventail de formations pratiques adaptées au marché sénégalais.</p>
                <p class="lead mb-4">Obtenez des compétences professionnelles recherchées par les entreprises locales et internationales.</p>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="bg-white bg-opacity-10 p-4 rounded mb-3">
                            <h3 class="h2 fw-bold"><?php echo count($formations); ?></h3>
                            <p class="mb-0">Formations disponibles</p>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="bg-white bg-opacity-10 p-4 rounded mb-3">
                            <h3 class="h2 fw-bold">500+</h3>
                            <p class="mb-0">Apprenants formés</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Formations Section -->
<section class="py-5">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="h1 fw-bold mb-3">Nos Formations</h2>
            <p class="lead text-muted">Découvrez notre sélection de formations pour booster votre carrière</p>
        </div>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <div class="row">
            <?php foreach ($formations as $formation): ?>
                <div class="col-lg-4 col-md-6 mb-4">
                    <div class="card h-100 formation-card">
                        <div class="position-relative">
                            <?php 
                            $image_src = isset($formation['image']) && !empty($formation['image']) 
                                ? $formation['image'] 
                                : '../assets/images/default-formation.jpg';
                            ?>
                            <img src="<?php echo htmlspecialchars($image_src); ?>" 
                                 class="card-img-top" 
                                 alt="<?php echo htmlspecialchars($formation['titre']); ?>"
                                 onerror="this.src='../assets/images/default-formation.jpg'">
                            <span class="badge bg-primary position-absolute top-0 start-0 m-3">
                                <?php echo htmlspecialchars($formation['categorie'] ?? 'Formation'); ?>
                            </span>
                        </div>
                        
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title fw-bold"><?php echo htmlspecialchars($formation['titre']); ?></h5>
                            <p class="card-text text-muted flex-grow-1">
                                <?php echo htmlspecialchars(substr($formation['description'], 0, 120)) . '...'; ?>
                            </p>
                            
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-clock me-1"></i>
                                    <?php echo htmlspecialchars($formation['duree'] ?? 'Non définie'); ?>
                                </small>
                                <small class="text-muted">
                                    <i class="fas fa-tag me-1"></i>
                                    <?php echo htmlspecialchars($formation['niveau'] ?? 'Tous niveaux'); ?>
                                </small>
                            </div>
                            
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="prix-formation">
                                    <?php 
                                    $prix = getFixedPrice($formation['titre']);
                                    echo displayPrice($prix);
                                    ?>
                                </div>
                                <div>
                                    <a href="formation.php?id=<?php echo $formation['id']; ?>" 
                                       class="btn btn-outline-primary btn-sm me-2">Détails</a>
                                    <a href="inscription.php?id=<?php echo $formation['id']; ?>" 
                                       class="btn btn-primary btn-sm">S'inscrire</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php if (empty($formations)): ?>
            <div class="text-center py-5">
                <i class="fas fa-graduation-cap text-muted" style="font-size: 4rem;"></i>
                <h3 class="mt-3 text-muted">Aucune formation disponible</h3>
                <p class="text-muted">Les formations seront bientôt disponibles.</p>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php include '../includes/footer.php'; ?>