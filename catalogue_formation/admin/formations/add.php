<?php
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

$success = false;
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = trim($_POST['titre'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $duree = trim($_POST['duree'] ?? '');
    $prix = floatval($_POST['prix'] ?? 0);
    $image = trim($_POST['image'] ?? '');
    $categorie = trim($_POST['categorie'] ?? '');
    $niveau = trim($_POST['niveau'] ?? '');
    $prerequisites = trim($_POST['prerequisites'] ?? '');
    $objectifs = trim($_POST['objectifs'] ?? '');
    
    if (empty($titre) || empty($description) || empty($duree) || $prix <= 0 || 
        empty($image) || empty($categorie) || empty($niveau) || 
        empty($prerequisites) || empty($objectifs)) {
        $error = 'Tous les champs sont obligatoires.';
    } else {
        try {
            $stmt = $pdo->prepare("
                INSERT INTO formations (titre, description, duree, prix, image, categorie, niveau, prerequisites, objectifs) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            $stmt->execute([$titre, $description, $duree, $prix, $image, $categorie, $niveau, $prerequisites, $objectifs]);
            $success = true;
        } catch(PDOException $e) {
            $error = 'Erreur lors de l\'ajout de la formation.';
        }
    }
}

$page_title = 'Ajouter une formation - Administration';
include '../includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Ajouter une formation</h1>
                <a href="list.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>
                    Retour à la liste
                </a>
            </div>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    <i class="fas fa-check-circle me-2"></i>
                    Formation ajoutée avec succès ! 
                    <a href="list.php" class="alert-link">Voir la liste</a>
                </div>
            <?php endif; ?>

            <?php if ($error): ?>
                <div class="alert alert-danger">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="titre" class="form-label">Titre *</label>
                                    <input type="text" class="form-control" id="titre" name="titre" 
                                           value="<?php echo htmlspecialchars($_POST['titre'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="description" class="form-label">Description *</label>
                                    <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($_POST['description'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="duree" class="form-label">Durée *</label>
                                            <input type="text" class="form-control" id="duree" name="duree" 
                                                   placeholder="ex: 40 heures"
                                                   value="<?php echo htmlspecialchars($_POST['duree'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="prix" class="form-label">Prix (€) *</label>
                                            <input type="number" class="form-control" id="prix" name="prix" 
                                                   min="0" step="0.01"
                                                   value="<?php echo htmlspecialchars($_POST['prix'] ?? ''); ?>" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="image" class="form-label">URL de l'image *</label>
                                    <input type="url" class="form-control" id="image" name="image" 
                                           placeholder="https://..."
                                           value="<?php echo htmlspecialchars($_POST['image'] ?? ''); ?>" required>
                                </div>
                                
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="categorie" class="form-label">Catégorie *</label>
                                            <select class="form-select" id="categorie" name="categorie" required>
                                                <option value="">Sélectionner</option>
                                                <option value="Informatique" <?php echo ($_POST['categorie'] ?? '') === 'Informatique' ? 'selected' : ''; ?>>Informatique</option>
                                                <option value="Marketing" <?php echo ($_POST['categorie'] ?? '') === 'Marketing' ? 'selected' : ''; ?>>Marketing</option>
                                                <option value="Management" <?php echo ($_POST['categorie'] ?? '') === 'Management' ? 'selected' : ''; ?>>Management</option>
                                                <option value="Data Science" <?php echo ($_POST['categorie'] ?? '') === 'Data Science' ? 'selected' : ''; ?>>Data Science</option>
                                                <option value="Design" <?php echo ($_POST['categorie'] ?? '') === 'Design' ? 'selected' : ''; ?>>Design</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="niveau" class="form-label">Niveau *</label>
                                            <select class="form-select" id="niveau" name="niveau" required>
                                                <option value="">Sélectionner</option>
                                                <option value="Débutant" <?php echo ($_POST['niveau'] ?? '') === 'Débutant' ? 'selected' : ''; ?>>Débutant</option>
                                                <option value="Intermédiaire" <?php echo ($_POST['niveau'] ?? '') === 'Intermédiaire' ? 'selected' : ''; ?>>Intermédiaire</option>
                                                <option value="Avancé" <?php echo ($_POST['niveau'] ?? '') === 'Avancé' ? 'selected' : ''; ?>>Avancé</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="mb-3">
                                    <label for="prerequisites" class="form-label">Prérequis *</label>
                                    <textarea class="form-control" id="prerequisites" name="prerequisites" rows="3" required><?php echo htmlspecialchars($_POST['prerequisites'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="mb-4">
                                    <label for="objectifs" class="form-label">Objectifs *</label>
                                    <textarea class="form-control" id="objectifs" name="objectifs" rows="4" required><?php echo htmlspecialchars($_POST['objectifs'] ?? ''); ?></textarea>
                                </div>
                                
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-save me-2"></i>
                                        Ajouter la formation
                                    </button>
                                    <a href="list.php" class="btn btn-secondary">Annuler</a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>