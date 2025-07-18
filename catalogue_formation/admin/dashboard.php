<?php
session_start();
require_once '../db/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

function getFixedPrice($formation_titre) {
    $prix_fixes = [
        'Gestion de Projet Agile' => 9000,
        'Développement Web Full Stack' => 15000,
        'Marketing Digital' => 7500,
        'Data Science avec Python' => 12000
    ];
    
    return $prix_fixes[$formation_titre] ?? 35000;
}

$total_formations = $total_users = $total_inscriptions = $total_revenus = 0;
$recent_inscriptions = [];

try {
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM formations");
    $total_formations = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM utilisateurs");
    $total_users = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM inscriptions");
    $total_inscriptions = $stmt->fetch()['total'];
    
    $stmt = $pdo->query("SELECT f.titre FROM inscriptions i JOIN formations f ON i.formation_id = f.id");
    $inscriptions_formations = $stmt->fetchAll();
    
    foreach ($inscriptions_formations as $inscription) {
        $total_revenus += getFixedPrice($inscription['titre']);
    }
    
    $stmt = $pdo->query("
        SELECT i.*, f.titre as formation_titre, u.nom, u.prenom 
        FROM inscriptions i 
        JOIN formations f ON i.formation_id = f.id 
        JOIN utilisateurs u ON i.utilisateur_id = u.id 
        ORDER BY i.date_inscription DESC 
        LIMIT 5
    ");
    $recent_inscriptions = $stmt->fetchAll();
    
} catch(PDOException $e) {
    error_log("Erreur dashboard: " . $e->getMessage());
}

$page_title = 'Tableau de bord - Administration';
include 'includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include 'includes/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">
                    <i class="fas fa-chart-line me-2"></i>
                    Tableau de bord
                </h1>
            </div>

            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card bg-primary">
                        <div class="card-body">
                            <div class="stats-icon">
                                <i class="fas fa-book-open"></i>
                            </div>
                            <div class="stats-content">
                                <div class="stats-number"><?php echo $total_formations; ?></div>
                                <div class="stats-label">Formations</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card bg-success">
                        <div class="card-body">
                            <div class="stats-icon">
                                <i class="fas fa-users"></i>
                            </div>
                            <div class="stats-content">
                                <div class="stats-number"><?php echo $total_users; ?></div>
                                <div class="stats-label">Utilisateurs</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card bg-warning">
                        <div class="card-body">
                            <div class="stats-icon">
                                <i class="fas fa-user-check"></i>
                            </div>
                            <div class="stats-content">
                                <div class="stats-number"><?php echo $total_inscriptions; ?></div>
                                <div class="stats-label">Inscriptions</div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="stats-card bg-info">
                        <div class="card-body">
                            <div class="stats-icon">
                                <i class="fas fa-coins"></i>
                            </div>
                            <div class="stats-content">
                                <div class="stats-number"><?php echo number_format($total_revenus, 0, ',', ' '); ?> FCFA</div>
                                <div class="stats-label">Revenus</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock me-2"></i>
                        Inscriptions récentes
                    </h5>
                </div>
                <div class="card-body">
                    <?php if (empty($recent_inscriptions)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-inbox text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-muted">Aucune inscription</h4>
                            <p class="text-muted">Aucune inscription pour le moment</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Formation</th>
                                        <th>Date</th>
                                        <th>Statut</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($recent_inscriptions as $inscription): ?>
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-user-circle me-2 text-primary"></i>
                                                    <?php echo htmlspecialchars($inscription['prenom'] . ' ' . $inscription['nom']); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-graduation-cap me-2 text-success"></i>
                                                    <?php echo htmlspecialchars($inscription['formation_titre']); ?>
                                                </div>
                                            </td>
                                            <td>
                                                <span class="badge bg-light text-dark">
                                                    <?php echo date('d/m/Y', strtotime($inscription['date_inscription'])); ?>
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge bg-<?php echo $inscription['statut'] === 'active' ? 'success' : 'warning'; ?>">
                                                    <?php echo htmlspecialchars($inscription['statut']); ?>
                                                </span>
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

<?php include 'includes/admin_footer.php'; ?>