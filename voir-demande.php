<?php
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$demande = null;

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    // Récupérer la demande
    $sql = "SELECT * FROM demandes WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $demande = $result->fetch_assoc();
    } else {
        header("Location: liste-demandes.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de la Demande - Système de Suivi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">
                     <img src="assets/Logo.svg" style="width: 3rem; height: 3rem; color: var(--primary-color);">
                    <h1>Processus Participatif</h1>
                </div>
                <ul class="nav-links">
                    <li><a href="index.php">Accueil</a></li>
                    <li><a href="nouvelle-demande.php">Nouvelle Demande</a></li>
                    <li><a href="liste-demandes.php">Liste des Demandes</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <?php if ($demande): ?>
                <section class="detail-section">
                    <div class="detail-header">
                        <h2>Détails de la Demande #<?php echo $demande['id']; ?></h2>
                        <div class="detail-actions">
                            <a href="modifier-demande.php?id=<?php echo $demande['id']; ?>" class="btn btn-primary">
                                <i class="fas fa-edit"></i> Modifier
                            </a>
                            <a href="liste-demandes.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                        </div>
                    </div>
                    
                    <div class="detail-card">
                        <div class="detail-status">
                            <span class="status-badge status-<?php echo $demande['etat_avancement']; ?>">
                                <?php echo getStatusLabel($demande['etat_avancement']); ?>
                            </span>
                            <span class="status-date">
                                Créé le: <?php echo date('d/m/Y H:i', strtotime($demande['date_creation'])); ?>
                            </span>
                        </div>
                        
                        <div class="detail-section">
                            <h3>Informations Personnelles</h3>
                            <div class="detail-grid">
                                <div class="detail-item">
                                    <span class="label">Nom Complet:</span>
                                    <span class="value"><?php echo htmlspecialchars($demande['nom'] . ' ' . $demande['prenom']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Date de Naissance:</span>
                                    <span class="value"><?php echo date('d/m/Y', strtotime($demande['date_naissance'])); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Numéro CIN:</span>
                                    <span class="value"><?php echo htmlspecialchars($demande['numero_cin']); ?></span>
                                </div>
                                <div class="detail-item">
                                    <span class="label">Projet:</span>
                                    <span class="value"><?php echo htmlspecialchars($demande['projet']); ?></span>
                                </div>
                                <?php if (!empty($demande['telephone'])): ?>
                                <div class="detail-item">
                                    <span class="label">Téléphone:</span>
                                    <span class="value"><?php echo htmlspecialchars($demande['telephone']); ?></span>
                                </div>
                                <?php endif; ?>
                                <?php if (!empty($demande['email'])): ?>
                                <div class="detail-item">
                                    <span class="label">Email:</span>
                                    <span class="value"><?php echo htmlspecialchars($demande['email']); ?></span>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="process-timeline">
                            <h3>Historique du Processus</h3>
                            <ul class="timeline">
                                <!-- Entrevue -->
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Entrevue</h4>
                                        <p class="timeline-date">Date: <?php echo date('d/m/Y', strtotime($demande['date_entrevue'])); ?></p>
                                        <?php if (!empty($demande['commentaire_entrevue'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_entrevue'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                
                                <!-- Confirmation d'embauche -->
                                <?php if (!empty($demande['date_confirmation']) || $demande['etat_avancement'] == 'confirmation_embauche' || checkPreviousStep($demande['etat_avancement'], 'confirmation_embauche')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Confirmation d'Embauche</h4>
                                        <?php if (!empty($demande['date_confirmation'])): ?>
                                            <p class="timeline-date">Date: <?php echo date('d/m/Y', strtotime($demande['date_confirmation'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($demande['commentaire_confirmation'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_confirmation'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Type de contrat -->
                                <?php if (!empty($demande['type_contrat']) || $demande['etat_avancement'] == 'type_contrat' || checkPreviousStep($demande['etat_avancement'], 'type_contrat')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Type de Contrat</h4>
                                        <?php if (!empty($demande['type_contrat'])): ?>
                                            <p class="timeline-detail">
                                                Type: <?php 
                                                echo htmlspecialchars($demande['type_contrat']);
                                                if ($demande['type_contrat'] === 'AUTRE' && !empty($demande['autre_contrat'])) {
                                                    echo ' (' . htmlspecialchars($demande['autre_contrat']) . ')';
                                                }
                                                ?>
                                            </p>
                                        <?php endif; ?>
                                        <?php if (!empty($demande['commentaire_contrat'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_contrat'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Demande d'accès -->
                                <?php if ($demande['etat_avancement'] == 'demande_acces' || checkPreviousStep($demande['etat_avancement'], 'demande_acces')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Demande d'Accès</h4>
                                        <?php if (!empty($demande['commentaire_demande_acces'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_demande_acces'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Réception des accès -->
                                <?php if ($demande['etat_avancement'] == 'reception_acces' || checkPreviousStep($demande['etat_avancement'], 'reception_acces')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Réception des Accès</h4>
                                        <?php if (!empty($demande['commentaire_reception_acces'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_reception_acces'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Préparation du matériel -->
                                <?php if ($demande['etat_avancement'] == 'preparation_materiel' || checkPreviousStep($demande['etat_avancement'], 'preparation_materiel')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Préparation du Matériel</h4>
                                        <?php if (!empty($demande['commentaire_materiel'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_materiel'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>

                                <!-- Remise du matériel -->
                                <?php if ($demande['etat_avancement'] == 'remise_materiel' || checkPreviousStep($demande['etat_avancement'], 'remise_materiel')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Remise du Matériel</h4>
                                        <?php if (!empty($demande['date_remise_materiel'])): ?>
                                            <p class="timeline-date">Date de remise: <?php echo date('d/m/Y', strtotime($demande['date_remise_materiel'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($demande['date_signature_contrat'])): ?>
                                            <p class="timeline-date">Date de signature: <?php echo date('d/m/Y', strtotime($demande['date_signature_contrat'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($demande['commentaire_remise_materiel'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_remise_materiel'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Début du travail -->
                                <?php if ($demande['etat_avancement'] == 'debut_travail' || checkPreviousStep($demande['etat_avancement'], 'debut_travail')): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Début du Travail</h4>
                                        <?php if (!empty($demande['date_debut_travail'])): ?>
                                            <p class="timeline-date">Date: <?php echo date('d/m/Y', strtotime($demande['date_debut_travail'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($demande['commentaire_debut'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_debut'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                                
                                <!-- Processus complété -->
                                <?php if ($demande['etat_avancement'] == 'complete'): ?>
                                <li class="timeline-item active">
                                    <div class="timeline-marker"></div>
                                    <div class="timeline-content">
                                        <h4>Processus Complété</h4>
                                        <?php if (!empty($demande['date_completion'])): ?>
                                            <p class="timeline-date">Date: <?php echo date('d/m/Y', strtotime($demande['date_completion'])); ?></p>
                                        <?php endif; ?>
                                        <?php if (!empty($demande['commentaire_final'])): ?>
                                        <div class="timeline-comment">
                                            <p><?php echo nl2br(htmlspecialchars($demande['commentaire_final'])); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </li>
                                <?php endif; ?>
                            </ul>
                        </div>
                    </div>
                </section>
            <?php else: ?>
                <div class="alert alert-danger">Demande non trouvée</div>
            <?php endif; ?>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> ProcessTrack - Système de Suivi des Processus</p>
        </footer>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>