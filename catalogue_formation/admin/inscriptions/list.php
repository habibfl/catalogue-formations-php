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

// Suppression d'une inscription
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM inscriptions WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Inscription supprimée avec succès.";
    } catch(PDOException $e) {
        $error = "Erreur lors de la suppression.";
    }
}

// Récupération des inscriptions avec détails
try {
    $stmt = $pdo->query("
        SELECT i.*, f.titre as formation_titre, f.prix as formation_prix,
               u.nom, u.prenom, u.email
        FROM inscriptions i 
        JOIN formations f ON i.formation_id = f.id 
        JOIN utilisateurs u ON i.utilisateur_id = u.id 
        ORDER BY i.date_inscription DESC
    ");
    $inscriptions = $stmt->fetchAll();
} catch(PDOException $e) {
    $inscriptions = [];
    $error = "Erreur lors du chargement des inscriptions.";
}

$page_title = 'Gestion des inscriptions - Administration';
include '../includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des inscriptions</h1>
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
                    <?php if (empty($inscriptions)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-user-check text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-muted">Aucune inscription</h4>
                            <p class="text-muted">Les inscriptions apparaîtront ici quand les utilisateurs s'inscriront aux formations.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Formation</th>
                                        <th>Prix</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($inscriptions as $inscription): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($inscription['prenom'] . ' ' . $inscription['nom']); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($inscription['email']); ?></small>
                                            </td>
                                            <td><?php echo htmlspecialchars($inscription['formation_titre']); ?></td>
                                            <td class="fw-semibold text-success"><?php echo number_format(getFixedPrice($inscription['formation_titre']), 0, ',', ' '); ?> FCFA</td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($inscription['date_inscription'])); ?></td>
                                            <td>
                                                <span class="badge bg-warning">
                                                    <?php echo htmlspecialchars($inscription['statut']); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <a href="?delete=<?php echo $inscription['id']; ?>" 
                                                   class="btn btn-outline-danger btn-sm btn-delete" 
                                                   title="Supprimer">
                                                    <i class="fas fa-trash"></i>
                                                </a>
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