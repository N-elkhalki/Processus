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
        $commentaire = isset($_POST['commentaire']) ? htmlspecialchars($_POST['commentaire']) : '';
        
        // Mise à jour selon l'état d'avancement
        switch ($etat_avancement) {
            case 'confirmation_embauche':
                $date_confirmation = $_POST['date_confirmation'];
                $sql = "UPDATE demandes SET etat_avancement = ?, date_confirmation = ?, commentaire_confirmation = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $etat_avancement, $date_confirmation, $commentaire, $id);
                break;
                
            case 'type_contrat':
                $type_contrat = $_POST['type_contrat'];
                $autre_contrat = '';
                
                if ($type_contrat === 'AUTRE') {
                    $autre_contrat = htmlspecialchars($_POST['autre_contrat']);
                }
                
                $sql = "UPDATE demandes SET etat_avancement = ?, type_contrat = ?, autre_contrat = ?, commentaire_contrat = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssssi", $etat_avancement, $type_contrat, $autre_contrat, $commentaire, $id);
                break;
                
            case 'demande_acces':
                $sql = "UPDATE demandes SET etat_avancement = ?, commentaire_demande_acces = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;
                
            case 'reception_acces':
                $sql = "UPDATE demandes SET etat_avancement = ?, commentaire_reception_acces = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;
                
            case 'preparation_materiel':
                $sql = "UPDATE demandes SET etat_avancement = ?, commentaire_materiel = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $etat_avancement, $commentaire, $id);
                break;
                
            case 'debut_travail':
                $date_debut_travail = $_POST['date_debut_travail'];
                $sql = "UPDATE demandes SET etat_avancement = ?, date_debut_travail = ?, commentaire_debut = ? WHERE id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("sssi", $etat_avancement, $date_debut_travail, $commentaire, $id);
                break;
                
            case 'complete':
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
                                <span class="label">Date prevue de travail:</span>
                                <span class="value"><?php echo date('d/m/Y', strtotime($demande['date_prevue_travail'])); ?></span>
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
                            <li class="<?php echo ($demande['etat_avancement'] == 'debut_travail' || checkPreviousStep($demande['etat_avancement'], 'debut_travail')) ? 'active' : ''; ?>">
                                <span class="step-number">7</span>
                                <span class="step-label">Début du Travail</span>
                            </li>
                            <li class="<?php echo ($demande['etat_avancement'] == 'complete') ? 'active' : ''; ?>">
                                <span class="step-number">8</span>
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
                        
                        <!-- Champs dynamiques selon l'étape -->
                        <div id="etape-fields">
                            <!-- Ces champs seront chargés dynamiquement par JavaScript -->
                            <div id="confirmation_embauche_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="date_confirmation">Date de Confirmation d'Embauche:</label>
                                    <input type="date" id="date_confirmation" name="date_confirmation">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire">Commentaire:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div id="type_contrat_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="type_contrat">Type de Contrat:</label>
                                    <select id="type_contrat" name="type_contrat">
                                        <option value="CDD">CDD</option>
                                        <option value="CDI">CDI</option>
                                        <option value="ANAPEC">ANAPEC</option>
                                        <option value="AUTRE">AUTRE</option>
                                    </select>
                                </div>
                                <div id="autre_contrat_field" class="form-group" style="display: none;">
                                    <label for="autre_contrat">Préciser le Type de Contrat:</label>
                                    <input type="text" id="autre_contrat" name="autre_contrat">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire">Commentaire:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div id="demande_acces_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire">Commentaire sur la Demande d'Accès:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3" placeholder="Détails sur les accès demandés..."></textarea>
                                </div>
                            </div>
                            
                            <div id="reception_acces_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire">Commentaire sur la Réception des Accès:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3" placeholder="Détails sur les accès reçus..."></textarea>
                                </div>
                            </div>
                            
                            <div id="preparation_materiel_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire">Commentaire sur la Préparation du Matériel:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3" placeholder="Détails sur le matériel préparé..."></textarea>
                                </div>
                            </div>
                            
                            <div id="debut_travail_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="date_debut_travail">Date de Début du Travail:</label>
                                    <input type="date" id="date_debut_travail" name="date_debut_travail">
                                </div>
                                <div class="form-group">
                                    <label for="commentaire">Commentaire:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3"></textarea>
                                </div>
                            </div>
                            
                            <div id="complete_fields" class="etape-field">
                                <div class="form-group">
                                    <label for="commentaire">Commentaire Final:</label>
                                    <textarea id="commentaire" name="commentaire" rows="3" placeholder="Commentaire de clôture du processus..."></textarea>
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