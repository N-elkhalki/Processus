<?php
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'includes/auth.php';

checkAuth();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Système de Suivi des Demandes</title>
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
                    <li><a href="index.php" class="active">Accueil</a></li>
                    <li><a href="nouvelle-demande.php">Nouvelle Demande</a></li>
                    <li><a href="liste-demandes.php">Liste des Demandes</a></li>
                    <li><a href="logout.php" class="btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="hero">
                <div class="hero-content">
                    <h2>Gestion des Processus de Recrutement</h2>
                    <p>Suivez facilement le parcours des candidats, de l'entrevue à l'intégration.</p>
                    <div class="cta-buttons">
                        <a href="nouvelle-demande.php" class="btn btn-primary">
                            <i class="fas fa-plus-circle"></i> Nouvelle Demande
                        </a>
                        <a href="liste-demandes.php" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Voir les Demandes
                        </a>
                    </div>
                </div>
                <div class="hero-image">
                    <img src="https://images.pexels.com/photos/3184328/pexels-photo-3184328.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1" alt="Processus de recrutement">
                </div>
            </section>

            <section class="features">
                <h3>Fonctionnalités Principales</h3>
                <div class="feature-cards">
                    <div class="feature-card">
                        <i class="fas fa-user-plus"></i>
                        <h4>Enregistrement des Candidats</h4>
                        <p>Saisissez les informations des candidats et planifiez les entrevues.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-tasks"></i>
                        <h4>Suivi d'Avancement</h4>
                        <p>Suivez chaque étape du processus avec mises à jour en temps réel.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-comments"></i>
                        <h4>Commentaires et Notes</h4>
                        <p>Ajoutez des commentaires à chaque étape pour garder une trace des décisions.</p>
                    </div>
                    <div class="feature-card">
                        <i class="fas fa-chart-line"></i>
                        <h4>Tableau de Bord</h4>
                        <p>Visualisez l'état d'avancement de tous les processus en cours.</p>
                    </div>
                </div>
            </section>
        </main>

        <footer>
            <p>&copy; <?php echo date('Y'); ?> ProcessTrack - Système de Suivi des Processus</p>
        </footer>
    </div>

    <script src="assets/js/main.js"></script>
</body>
</html>