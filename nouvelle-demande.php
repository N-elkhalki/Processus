<?php
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $date_naissance = htmlspecialchars($_POST['date_naissance']);
    $numero_cin = htmlspecialchars($_POST['numero_cin']);
    $projet = htmlspecialchars($_POST['projet']);
    $date_prevue_travail = htmlspecialchars($_POST['date_prevue_travail']);
    $date_entrevue = htmlspecialchars($_POST['date_entrevue']);
    
    // Préparer et exécuter la requête
    $sql = "INSERT INTO demandes (nom, prenom, date_naissance, numero_cin, projet, date_prevue_travail, date_entrevue, etat_avancement, date_creation) 
            VALUES (?, ?, ?, ?, ?, ?, ?, 'entrevue', NOW())";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nom, $prenom, $date_naissance, $numero_cin, $projet, $date_prevue_travail, $date_entrevue);
    
    if ($stmt->execute()) {
        $message = '<div class="alert alert-success">Demande enregistrée avec succès!</div>';
    } else {
        $message = '<div class="alert alert-danger">Erreur lors de l\'enregistrement: ' . $stmt->error . '</div>';
    }
    
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle Demande - Système de Suivi</title>
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
                    <li><a href="nouvelle-demande.php" class="active">Nouvelle Demande</a></li>
                    <li><a href="liste-demandes.php">Liste des Demandes</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="form-section">
                <h2>Nouvelle Demande</h2>
                <?php echo $message; ?>
                
                <form action="nouvelle-demande.php" method="post" class="process-form">
                    <div class="form-group">
                        <label for="nom">Nom:</label>
                        <input type="text" id="nom" name="nom" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="prenom">Prénom:</label>
                        <input type="text" id="prenom" name="prenom" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_naissance">Date de Naissance:</label>
                        <input type="date" id="date_naissance" name="date_naissance" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="numero_cin">Numéro CIN:</label>
                        <input type="text" id="numero_cin" name="numero_cin" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="projet">Projet Désigné:</label>
                        <input type="text" id="projet" name="projet" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_prevue_travail">Date Prévue de Travail:</label>
                        <input type="date" id="date_prevue_travail" name="date_prevue_travail" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="date_entrevue">Date d'Entrevue:</label>
                        <input type="date" id="date_entrevue" name="date_entrevue" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Enregistrer
                        </button>
                        <button type="reset" class="btn btn-secondary">
                            <i class="fas fa-undo"></i> Réinitialiser
                        </button>
                    </div>
                </form>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> ProcessTrack - Système de Suivi des Processus</p>
        </footer>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>