<?php
session_start();
require_once '../../db/config.php';

if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: ../login.php');
    exit;
}

// Suppression d'un utilisateur
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM utilisateurs WHERE id = ?");
        $stmt->execute([$_GET['delete']]);
        $success = "Utilisateur supprimé avec succès.";
    } catch(PDOException $e) {
        $error = "Erreur lors de la suppression.";
    }
}

// Récupération des utilisateurs
try {
    $stmt = $pdo->query("SELECT * FROM utilisateurs ORDER BY date_inscription DESC");
    $utilisateurs = $stmt->fetchAll();
} catch(PDOException $e) {
    $utilisateurs = [];
    $error = "Erreur lors du chargement des utilisateurs.";
}

$page_title = 'Gestion des utilisateurs - Administration';
include '../includes/admin_header.php';
?>

<div class="container-fluid">
    <div class="row">
        <?php include '../includes/admin_sidebar.php'; ?>
        
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Gestion des utilisateurs</h1>
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
                    <?php if (empty($utilisateurs)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted" style="font-size: 3rem;"></i>
                            <h4 class="mt-3 text-muted">Aucun utilisateur</h4>
                            <p class="text-muted">Les utilisateurs apparaîtront ici après leurs inscriptions.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Utilisateur</th>
                                        <th>Contact</th>
                                        <th>Date d'inscription</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($utilisateurs as $user): ?>
                                        <tr>
                                            <td>
                                                <div class="fw-semibold"><?php echo htmlspecialchars($user['prenom'] . ' ' . $user['nom']); ?></div>
                                            </td>
                                            <td>
                                                <div class="small">
                                                    <i class="fas fa-envelope me-1"></i>
                                                    <?php echo htmlspecialchars($user['email']); ?>
                                                </div>
                                                <div class="small text-muted">
                                                    <i class="fas fa-phone me-1"></i>
                                                    <?php echo htmlspecialchars($user['telephone']); ?>
                                                </div>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($user['date_inscription'])); ?></td>
                                            <td>
                                                <a href="?delete=<?php echo $user['id']; ?>" 
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