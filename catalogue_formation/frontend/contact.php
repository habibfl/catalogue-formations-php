<?php
$page_title = 'Contact - FormaCatalogue';
$css_path = '../assets/css/style.css';
$js_path = '../assets/js/script.js';
$logo_path = '../assets/images/logo.png';
$home_path = 'index.php';
$admin_path = '../admin/login.php';

$message_sent = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $message_sent = true;
}

include '../includes/header.php';
include '../includes/navbar.php';
?>

<div class="container py-5">
    <div class="text-center mb-5">
        <h1 class="display-5 fw-bold mb-3">Contactez-nous</h1>
        <p class="lead text-muted">Une question ? Nous sommes là pour vous aider</p>
    </div>

    <div class="row">
        <div class="col-lg-6 mb-5">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <h2 class="h4 fw-bold mb-4">Nos coordonnées</h2>
                    
                    <div class="mb-4">
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-envelope text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Email</h6>
                                <p class="text-muted mb-0">fallhabib659@gmail.com</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-phone text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Téléphone</h6>
                                <p class="text-muted mb-0">+221 77 431 70 99</p>
                            </div>
                        </div>
                        
                        <div class="d-flex align-items-center mb-3">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle me-3">
                                <i class="fas fa-map-marker-alt text-primary"></i>
                            </div>
                            <div>
                                <h6 class="fw-semibold mb-1">Adresse</h6>
                                <p class="text-muted mb-0">Dakar, Sénégal</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-top pt-4">
                        <h6 class="fw-semibold mb-3">Horaires d'ouverture</h6>
                        <div class="row text-sm">
                            <div class="col-6">
                                <div class="mb-2">
                                    <span class="text-muted">Lundi - Vendredi</span>
                                </div>
                                <div class="mb-2">
                                    <span class="text-muted">Samedi</span>
                                </div>
                                <div>
                                    <span class="text-muted">Dimanche</span>
                                </div>
                            </div>
                            <div class="col-6 text-end">
                                <div class="mb-2">9h00 - 18h00</div>
                                <div class="mb-2">9h00 - 12h00</div>
                                <div>Fermé</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h2 class="h4 fw-bold mb-4">Envoyez-nous un message</h2>
                    
                    <?php if ($message_sent): ?>
                        <div class="alert alert-success">
                            <i class="fas fa-check-circle me-2"></i>
                            Votre message a été envoyé avec succès !
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST" id="contactForm">
                        <div class="mb-3">
                            <label for="nom" class="form-label">Nom complet *</label>
                            <input type="text" class="form-control" id="nom" name="nom" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email" class="form-label">Email *</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="sujet" class="form-label">Sujet *</label>
                            <input type="text" class="form-control" id="sujet" name="sujet" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="message" class="form-label">Message *</label>
                            <textarea class="form-control" id="message" name="message" rows="6" required></textarea>
                        </div>
                        
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>
                            Envoyer le message
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>