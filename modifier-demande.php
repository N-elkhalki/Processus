<?php
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$message = '';
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
    
    // Traitement du formulaire de mise à jour
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $etat_avancement = $_POST['etat_avancement'];
        
        // Mise à jour selon l'état d'avancement
        switch ($etat_avancement) {
            case 'entrevue':
                $commentaire = isset($_POST['commentaire_entrevue']) ? $_POST['commentaire_entrevue'] : '';
                $sql = "UPDATE demandes SET commentaire_entrevue = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("si", $commentaire, $id);
                break;
                
            case 'confirmation_embauche':
                $date_confirmation = $_POST['date_confirmation'];
                $commentaire = isset($_POST['commentaire_confirmation']) ? $_POST['commentaire_confirmation'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, date_confirmation = ?, commentaire_confirmation = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $etat_avancement, $date_confirmation, $commentaire, $id);
                break;
                
            case 'type_contrat':
                $type_contrat = $_POST['type_contrat'];
                $autre_contrat = '';
                $commentaire = isset($_POST['commentaire_contrat']) ? $_POST['commentaire_contrat'] : '';
                
                if ($type_contrat === 'AUTRE') {
                    $autre_contrat = htmlspecialchars($_POST['autre_contrat']);
                }
                
                $sql = "UPDATE demandes SET etat_avancement = ?, type_contrat = ?, autre_contrat = ?, commentaire_contrat = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $etat_avancement, $type_contrat, $autre_contrat, $commentaire, $id);
                break;
                
            case 'demande_acces':
                $commentaire = isset($_POST['commentaire_demande_acces']) ? $_POST['commentaire_demande_acces'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, commentaire_demande_acces = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;
                
            case 'reception_acces':
                $commentaire = isset($_POST['commentaire_reception_acces']) ? $_POST['commentaire_reception_acces'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, commentaire_reception_acces = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;
                
            case 'preparation_materiel':
                $commentaire = isset($_POST['commentaire_materiel']) ? $_POST['commentaire_materiel'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, commentaire_materiel = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;

            case 'remise_materiel':
                $date_remise = $_POST['date_remise_materiel'];
                $date_signature = $_POST['date_signature_contrat'];
                $commentaire = isset($_POST['commentaire_remise_materiel']) ? $_POST['commentaire_remise_materiel'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, date_remise_materiel = ?, date_signature_contrat = ?, commentaire_remise_materiel = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $etat_avancement, $date_remise, $date_signature, $commentaire, $id);
                break;
                
            case 'debut_travail':
                $date_debut_travail = $_POST['date_debut_travail'];
                $commentaire = isset($_POST['commentaire_debut']) ? $_POST['commentaire_debut'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, date_debut_travail = ?, commentaire_debut = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $etat_avancement, $date_debut_travail, $commentaire, $id);
                break;
                
            case 'complete':
                $commentaire = isset($_POST['commentaire_final']) ? $_POST['commentaire_final'] : '';
                $sql = "UPDATE demandes SET etat_avancement = ?, date_completion = NOW(), commentaire_final = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;
        }
        
        if ($stmt->execute()) {
            $message = '<div class="alert alert-success">La demande a été mise à jour avec succès!</div>';
            
            // Recharger les données à jour
            $sql = "SELECT * FROM demandes WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $id);
            $stmt->execute();
            $result = $stmt->get_result();
            $demande = $result->fetch_assoc();
        } else {
            $message = '<div class="alert alert-danger">Erreur lors de la mise à jour: ' . $stmt->error . '</div>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier la Demande - Système de Suivi</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header>
            <nav>
                <div class="logo">
                    <i class="fas fa-users-cog"></i>
                    <h1>ProcessTrack</h1>
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
                <section class="form-section">
                    <h2>Modifier la Demande #<?php echo $demande['id']; ?></h2>
                    <?php echo $message; ?>
                    
                    <div class="applicant-info">
                        <h3>Informations du Candidat</h3>
                        <div class="info-grid">
                            <div class="info-item">
                                <span class="label">Nom:</span>
                                <span class="value"><?php echo htmlspecialchars($demande['nom']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Prénom:</span>
                                <span class="value"><?php echo htmlspecialchars($demande['prenom']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Date de Naissance:</span>
                                <span class="value"><?php echo date('d/m/Y', strtotime($demande['date_naissance'])); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Numéro CIN:</span>
                                <span class="value"><?php echo htmlspecialchars($demande['numero_cin']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Projet:</span>
                                <span class="value"><?php echo htmlspecialchars($demande['projet']); ?></span>
                            </div>
                            <div class="info-item">
                                <span class="label">Date d'Entrevue:</span>
                                <span class="value"><?php echo date('d/m/Y', strtotime($demande['date_entrevue'])); ?></span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="process-progress">
                        <h3>Progression du Processus</h3>
                        <ul class="progress-steps">
                            <li class="<?php echo ($demande['etat_avancement'] == 'entrevue' || checkPreviousStep($demande['etat_avancement'], 'entrevue')) ? 'active' : ''; ?>">
                                <span class="step-number">1</span>
                                <span class="step-label">Entrevue</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'confirmation_embauche' || checkPreviousStep($demande['etat_avancement'], 'confirmation_embauche')) ? 'active' : ''; ?>">
                                <span class="step-number">2</span>
                                <span class="step-label">Confirmation d'Embauche</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'type_contrat' || checkPreviousStep($demande['etat_avancement'], 'type_contrat')) ? 'active' : ''; ?>">
                                <span class="step-number">3</span>
                                <span class="step-label">Type de Contrat</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'demande_acces' || checkPreviousStep($demande['etat_avancement'], 'demande_acces')) ? 'active' : ''; ?>">
                                <span class="step-number">4</span>
                                <span class="step-label">Demande d'Accès</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'reception_acces' || checkPreviousStep($demande['etat_avancement'], 'reception_acces')) ? 'active' : ''; ?>">
                                <span class="step-number">5</span>
                                <span class="step-label">Réception des Accès</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'preparation_materiel' || checkPreviousStep($demande['etat_avancement'], 'preparation_materiel')) ? 'active' : ''; ?>">
                                <span class="step-number">6</span>
                                <span class="step-label">Préparation du Matériel</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'remise_materiel' || checkPreviousStep($demande['etat_avancement'], 'remise_materiel')) ? 'active' : ''; ?>">
                                <span class="step-number">7</span>
                                <span class="step-label">Remise du Matériel</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'debut_travail' || checkPreviousStep($demande['etat_avancement'], 'debut_travail')) ? 'active' : ''; ?>">
                                <span class="step-number">8</span>
                                <span class="step-label">Début du Travail</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'complete') ? 'active' : ''; ?>">
                                <span class="step-number">9</span>
                                <span class="step-label">Processus Complété</span>
                            </li>
                        </ul>
                    </div>
                    
                    <form action="modifier-demande.php?id=<?php echo $demande['id']; ?>" method="post" class="process-form">
                        <h3>Mettre à Jour l'État d'Avancement</h3>
                        
                        <div class="form-group">
                            <label for="etat_avancement">Sélectionner la Prochaine Étape:</label>
                            <select id="etat_avancement" name="etat_avancement" required>
                                <?php
                                $etats = getNextStates($demande['etat_avancement']);
                                foreach ($etats as $etat => $label) {
                                    echo '<option value="'.$etat.'">'.$label.'</option>';
                                }
                                ?>
                            </select>
                        </div>
                        
                        <div id="etape-fields">
                            <!-- Entrevue -->
                            <div id="entrevue_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire_entrevue">Commentaire d'Entrevue:</label>
                                    <textarea id="commentaire_entrevue" name="commentaire_entrevue" rows="3"><?php echo htmlspecialchars($demande['commentaire_entrevue']); ?></textarea>
                                </div>
                            </div>

                            <!-- Confirmation d'embauche -->
                            <div id="confirmation_embauche_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="date_confirmation">Date de Confirmation d'Embauche:</label>
                                    <input type="date" id="date_confirmation" name="date_confirmation" value="<?php echo $demande['date_confirmation']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire_confirmation">Commentaire:</label>
                                    <textarea id="commentaire_confirmation" name="commentaire_confirmation" rows="3"><?php echo htmlspecialchars($demande['commentaire_confirmation']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Type de contrat -->
                            <div id="type_contrat_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="type_contrat">Type de Contrat:</label>
                                    <select id="type_contrat" name="type_contrat">
                                        <option value="CDD" <?php echo $demande['type_contrat'] == 'CDD' ? 'selected' : ''; ?>>CDD</option>
                                        <option value="CDI" <?php echo $demande['type_contrat'] == 'CDI' ? 'selected' : ''; ?>>CDI</option>
                                        <option value="ANAPEC" <?php echo $demande['type_contrat'] == 'ANAPEC' ? 'selected' : ''; ?>>ANAPEC</option>
                                        <option value="AUTRE" <?php echo $demande['type_contrat'] == 'AUTRE' ? 'selected' : ''; ?>>AUTRE</option>
                                    </select>
                                </div>
                                <div id="autre_contrat_field" class="form-group" style="display: <?php echo $demande['type_contrat'] == 'AUTRE' ? 'block' : 'none'; ?>;">
                                    <label for="autre_contrat">Préciser le Type de Contrat:</label>
                                    <input type="text" id="autre_contrat" name="autre_contrat" value="<?php echo htmlspecialchars($demande['autre_contrat']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire_contrat">Commentaire:</label>
                                    <textarea id="commentaire_contrat" name="commentaire_contrat" rows="3"><?php echo htmlspecialchars($demande['commentaire_contrat']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Demande d'accès -->
                            <div id="demande_acces_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire_demande_acces">Commentaire sur la Demande d'Accès:</label>
                                    <textarea id="commentaire_demande_acces" name="commentaire_demande_acces" rows="3" placeholder="Détails sur les accès demandés..."><?php echo htmlspecialchars($demande['commentaire_demande_acces']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Réception des accès -->
                            <div id="reception_acces_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire_reception_acces">Commentaire sur la Réception des Accès:</label>
                                    <textarea id="commentaire_reception_acces" name="commentaire_reception_acces" rows="3" placeholder="Détails sur les accès reçus..."><?php echo htmlspecialchars($demande['commentaire_reception_acces']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Préparation du matériel -->
                            <div id="preparation_materiel_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire_materiel">Commentaire sur la Préparation du Matériel:</label>
                                    <textarea id="commentaire_materiel" name="commentaire_materiel" rows="3" placeholder="Détails sur le matériel préparé..."><?php echo htmlspecialchars($demande['commentaire_materiel']); ?></textarea>
                                </div>
                            </div>

                            <!-- Remise de matériel -->
                            <div id="remise_materiel_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="date_remise_materiel">Date de Remise du Matériel:</label>
                                    <input type="date" id="date_remise_materiel" name="date_remise_materiel" value="<?php echo $demande['date_remise_materiel']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="date_signature_contrat">Date de Signature du Contrat:</label>
                                    <input type="date" id="date_signature_contrat" name="date_signature_contrat" value="<?php echo $demande['date_signature_contrat']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire_remise_materiel">Commentaire sur la Remise du Matériel:</label>
                                    <textarea id="commentaire_remise_materiel" name="commentaire_remise_materiel" rows="3"><?php echo htmlspecialchars($demande['commentaire_remise_materiel']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Début du travail -->
                            <div id="debut_travail_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="date_debut_travail">Date de Début du Travail:</label>
                                    <input type="date" id="date_debut_travail" name="date_debut_travail" value="<?php echo $demande['date_debut_travail']; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire_debut">Commentaire:</label>
                                    <textarea id="commentaire_debut" name="commentaire_debut" rows="3"><?php echo htmlspecialchars($demande['commentaire_debut']); ?></textarea>
                                </div>
                            </div>
                            
                            <!-- Processus complété -->
                            <div id="complete_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire_final">Commentaire Final:</label>
                                    <textarea id="commentaire_final" name="commentaire_final" rows="3" placeholder="Commentaire de clôture du processus..."><?php echo htmlspecialchars($demande['commentaire_final']); ?></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Mettre à Jour
                            </button>
                            <a href="liste-demandes.php" class="btn btn-secondary">
                                <i class="fas fa-arrow-left"></i> Retour à la Liste
                            </a>
                        </div>
                    </form>
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