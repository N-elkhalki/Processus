<?php
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

// Récupérer la liste des demandes
$sql = "SELECT * FROM demandes ORDER BY date_creation DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Demandes - Système de Suivi</title>
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
                    <li><a href="liste-demandes.php" class="active">Liste des Demandes</a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="list-section">
                <h2>Liste des Demandes</h2>
                
                <div class="search-filter">
                    <div class="search-box">
                        <input type="text" id="searchInput" placeholder="Rechercher...">
                        <i class="fas fa-search"></i>
                    </div>
                    <div class="filter-options">
                        <select id="statusFilter">
                            <option value="">Tous les statuts</option>
                            <option value="entrevue">Entrevue</option>
                            <option value="confirmation_embauche">Confirmation d'embauche</option>
                            <option value="type_contrat">Type de contrat</option>
                            <option value="demande_acces">Demande d'accès</option>
                            <option value="reception_acces">Réception des accès</option>
                            <option value="preparation_materiel">Préparation du matériel</option>
                            <option value="debut_travail">Début du travail</option>
                            <option value="complete">Complété</option>
                        </select>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom & Prénom</th>
                                <th>Projet</th>
                                <th>Date Prévue de Travail</th>
                                <th>État d'Avancement</th>
                                <th>Date de Création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['nom'] . ' ' . $row['prenom']); ?></td>
                                        <td><?php echo htmlspecialchars($row['projet']); ?></td>
                                        <td><?php echo !empty($row['date_prevue_travail']) ? date('d/m/Y', strtotime($row['date_prevue_travail'])) : '-'; ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $row['etat_avancement']; ?>">
                                                <?php echo getStatusLabel($row['etat_avancement']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['date_creation'])); ?></td>
                                        <td>
                                            <a href="modifier-demande.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-edit" title="Modifier">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <a href="voir-demande.php?id=<?php echo $row['id']; ?>" class="btn-icon btn-view" title="Voir détails">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="no-data">Aucune demande trouvée</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
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