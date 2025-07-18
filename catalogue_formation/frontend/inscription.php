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
$page_title = 'Inscription - ' . htmlspecialchars($formation['titre']);
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/script.js';
$home_path = 'index.php';
$contact_path = 'contact.php';
$admin_path = '../admin/login.php';

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    
    if (empty($nom) || empty($prenom) || empty($email) || empty($telephone)) {
        $error = 'Tous les champs sont obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Email invalide.';
    } else {
        try {
            $pdo->beginTransaction();
            
            // Vérifier si l'utilisateur existe déjà
            $stmt = $pdo->prepare("SELECT id FROM utilisateurs WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch();
            
            if ($user) {
                $user_id = $user['id'];
            } else {
                // Créer un nouvel utilisateur
                $stmt = $pdo->prepare("INSERT INTO utilisateurs (nom, prenom, email, telephone) VALUES (?, ?, ?, ?)");
                $stmt->execute([$nom, $prenom, $email, $telephone]);
                $user_id = $pdo->lastInsertId();
            }
            
            // Vérifier si l'inscription existe déjà
            $stmt = $pdo->prepare("SELECT id FROM inscriptions WHERE formation_id = ? AND utilisateur_id = ?");
            $stmt->execute([$formation_id, $user_id]);
            
            if ($stmt->fetch()) {
                $error = 'Vous êtes déjà inscrit à cette formation.';
                $pdo->rollBack();
            } else {
                // Créer l'inscription
                $stmt = $pdo->prepare("INSERT INTO inscriptions (formation_id, utilisateur_id, statut) VALUES (?, ?, 'En attente')");
                $stmt->execute([$formation_id, $user_id]);
                
                $pdo->commit();
                $success = true;
            }
        } catch (PDOException $e) {
            $pdo->rollBack();
            $error = 'Erreur lors de l\'inscription. Veuillez réessayer.';
        }
    }
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container py-5">
    <?php if ($success): ?>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-sm text-center">
                    <div class="card-body py-5">
                        <div class="mb-4">
                            <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                        </div>
                        <h2 class="mb-3 text-success">Inscription réussie !</h2>
                        <p class="text-muted mb-4 lead">
                            Votre demande d'inscription à la formation 
                            "<strong><?php echo htmlspecialchars($formation['titre']); ?></strong>" 
                            a été enregistrée avec succès.
                        </p>
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Prochaines étapes :</strong> Notre équipe vous contactera sous 24h pour finaliser votre inscription et vous communiquer les modalités de paiement.
                        </div>
                        <div class="d-grid gap-2 d-md-flex justify-content-md-center">
                            <a href="index.php" class="btn btn-primary btn-lg">
                                <i class="fas fa-home me-2"></i>
                                Retour à l'accueil
                            </a>
                            <a href="formation.php?id=<?php echo $formation['id']; ?>" class="btn btn-outline-secondary btn-lg">
                                <i class="fas fa-eye me-2"></i>
                                Voir la formation
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="row">
            <!-- Récapitulatif de la formation -->
            <div class="col-lg-5 mb-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-clipboard-list me-2"></i>
                            Récapitulatif de votre inscription
                        </h5>
                    </div>
                    <div class="card-body">
                        <img src="<?php echo htmlspecialchars($formation['image']); ?>" 
                             class="img-fluid rounded mb-3" 
                             alt="<?php echo htmlspecialchars($formation['titre']); ?>"
                             style="height: 180px; width: 100%; object-fit: cover;">
                        
                        <h6 class="fw-bold text-primary mb-3">
                            <?php echo htmlspecialchars($formation['titre']); ?>
                        </h6>
                        
                        <div class="row text-sm">
                            <div class="col-5">
                                <div class="mb-2">
                                    <i class="fas fa-clock text-muted me-1"></i>
                                    <span class="text-muted">Durée :</span>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-signal text-muted me-1"></i>
                                    <span class="text-muted">Niveau :</span>
                                </div>
                                <div class="mb-2">
                                    <i class="fas fa-tag text-muted me-1"></i>
                                    <span class="text-muted">Catégorie :</span>
                                </div>
                            </div>
                            <div class="col-7 text-end">
                                <div class="mb-2 fw-semibold"><?php echo htmlspecialchars($formation['duree']); ?></div>
                                <div class="mb-2 fw-semibold"><?php echo htmlspecialchars($formation['niveau']); ?></div>
                                <div class="mb-2 fw-semibold"><?php echo htmlspecialchars($formation['categorie']); ?></div>
                            </div>
                        </div>
                        
                        <hr class="my-3">
                        
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <span class="fw-semibold">
                                <i class="fas fa-coins text-success me-2"></i>
                                Prix total :
                            </span>
                            <span class="h4 text-success fw-bold mb-0">
                                <?php echo displayPrice($prix_corrige); ?>
                            </span>
                        </div>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-info-circle me-1"></i>
                                Le paiement s'effectue après validation de votre inscription
                            </small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire d'inscription -->
            <div class="col-lg-7">
                <div class="card shadow-sm">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0">
                            <i class="fas fa-user-edit me-2"></i>
                            Vos informations personnelles
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php if ($error): ?>
                            <div class="alert alert-danger">
                                <i class="fas fa-exclamation-triangle me-2"></i>
                                <?php echo htmlspecialchars($error); ?>
                            </div>
                        <?php endif; ?>
                        
                        <form method="POST" id="inscriptionForm">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="nom" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Nom *
                                        </label>
                                        <input type="text" class="form-control" id="nom" name="nom" 
                                               value="<?php echo htmlspecialchars($_POST['nom'] ?? ''); ?>" 
                                               placeholder="Votre nom de famille" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="prenom" class="form-label">
                                            <i class="fas fa-user me-1"></i>
                                            Prénom *
                                        </label>
                                        <input type="text" class="form-control" id="prenom" name="prenom" 
                                               value="<?php echo htmlspecialchars($_POST['prenom'] ?? ''); ?>" 
                                               placeholder="Votre prénom" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="email" class="form-label">
                                    <i class="fas fa-envelope me-1"></i>
                                    Adresse email *
                                </label>
                                <input type="email" class="form-control" id="email" name="email" 
                                       value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" 
                                       placeholder="votre.email@exemple.com" required>
                            </div>
                            
                            <div class="mb-4">
                                <label for="telephone" class="form-label">
                                    <i class="fas fa-phone me-1"></i>
                                    Numéro de téléphone *
                                </label>
                                <input type="tel" class="form-control" id="telephone" name="telephone" 
                                       value="<?php echo htmlspecialchars($_POST['telephone'] ?? ''); ?>" 
                                       placeholder="+221 77 123 45 67" required>
                            </div>
                            
                            <div class="alert alert-info">
                                <i class="fas fa-shield-alt me-2"></i>
                                <strong>Confidentialité :</strong> Vos données personnelles sont protégées et ne seront utilisées que dans le cadre de votre formation.
                            </div>
                            
                            <button type="submit" class="btn btn-success btn-lg w-100">
                                <i class="fas fa-check me-2"></i>
                                Confirmer mon inscription
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<?php include '../includes/footer.php'; ?>