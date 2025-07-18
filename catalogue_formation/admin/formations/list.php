<?php
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Fonction pour obtenir le prix fixe d'une formation
function getFixedPrice($formation_titre) {
    $prix_fixes = [
        'Gestion de Projet Agile' => 9000,
        'Développement Web Full Stack' => 15000,
        'Marketing Digital' => 7500,
        'Data Science avec Python' => 12000
    ];
    
    return isset($prix_fixes[$formation_titre]) ? $prix_fixes[$formation_titre] : 35000;
}

// Suppression d'une formation
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM formations WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Formation supprimée avec succès.";
    } catch(PDOException $e) {
        $error = "Erreur lors de la suppression.";
    }
}

// Récupération des formations
try {
    $stmt = $pdo->query("SELECT * FROM formations ORDER BY date_creation DESC");
    $formations = $stmt->fetchAll();
} catch(PDOException $e) {
    $formations = [];
    $error = "Erreur lors du chargement des formations.";
}

$page_title = 'Gestion des formations - Administration';
include '../includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des formations</h1>
                <a href="add.php" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Ajouter une formation
                </a>
            </div>

            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <i class="fas fa-check-circle me-2"></i>
                    <?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <?php echo $error; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <?php if (empty($formations)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-book-open text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-muted">Aucune formation</h4>
                            <p class="text-muted">Commencez par ajouter votre première formation.</p>
                            <a href="add.php" class="btn btn-primary">Ajouter une formation</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Formation</th>
                                        <th>Catégorie</th>
                                        <th>Durée</th>
                                        <th>Prix</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($formations as $formation): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <img src="<?php echo htmlspecialchars($formation['image']); ?>" 
                                                         class="rounded me-3" 
                                                         style="width: 50px; height: 50px; object-fit: cover;"
                                                         alt="<?php echo htmlspecialchars($formation['titre']); ?>">
                                                    <div>
                                                        <div class="fw-semibold"><?php echo htmlspecialchars($formation['titre']); ?></div>
                                                        <small class="text-muted"><?php echo htmlspecialchars($formation['niveau']); ?></small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td><?php echo htmlspecialchars($formation['categorie']); ?></td>
                                            <td><?php echo htmlspecialchars($formation['duree']); ?></td>
                                            <td class="fw-semibold text-success"><?php echo number_format(getFixedPrice($formation['titre']), 0, ',', ' '); ?> FCFA</td>
                                            <td>
                                                <div class="btn-group btn-group-sm">
                                                    <a href="../../frontend/formation.php?id=<?php echo $formation['id']; ?>" 
                                                       class="btn btn-outline-primary" target="_blank" title="Voir">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <a href="edit.php?id=<?php echo $formation['id']; ?>" 
                                                       class="btn btn-outline-secondary" title="Modifier">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="?delete=<?php echo $formation['id']; ?>" 
                                                       class="btn btn-outline-danger btn-delete" title="Supprimer">
                                                        <i class="fas fa-trash"></i>
                                                    </a>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>
</div>

<?php include '../includes/admin_footer.php'; ?>