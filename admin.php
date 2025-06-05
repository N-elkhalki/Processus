<?php
session_start();
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'includes/auth.php';

checkAuth();

// Check if user is admin
if (!isset($_SESSION['username']) || $_SESSION['username'] !== 'admin') {
    header("Location: index.php");
    exit;
}

$message = '';

// Handle user creation
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'create') {
        $username = trim($_POST['username']);
        $password = $_POST['password'];
        
        if (!empty($username) && !empty($password)) {
            $hashedPassword = hashPassword($password);
            
            $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ss", $username, $hashedPassword);
            
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Utilisateur créé avec succès!</div>';
            } else {
                $message = '<div class="alert alert-danger">Erreur lors de la création: ' . $stmt->error . '</div>';
            }
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['user_id'])) {
        $user_id = $_POST['user_id'];
        
        // Prevent deleting admin user
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();
        
        if ($user['username'] !== 'admin') {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $user_id);
            
            if ($stmt->execute()) {
                $message = '<div class="alert alert-success">Utilisateur supprimé avec succès!</div>';
            } else {
                $message = '<div class="alert alert-danger">Erreur lors de la suppression: ' . $stmt->error . '</div>';
            }
        } else {
            $message = '<div class="alert alert-danger">Impossible de supprimer l\'utilisateur admin!</div>';
        }
    }
}

// Get all users
$sql = "SELECT * FROM users ORDER BY created_at DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Gestion des Utilisateurs</title>
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
                    <li><a href="admin.php" class="active">Administration</a></li>
                    <li><a href="logout.php" class="btn-secondary">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </a></li>
                </ul>
            </nav>
        </header>

        <main>
            <section class="form-section">
                <h2>Gestion des Utilisateurs</h2>
                <?php echo $message; ?>
                
                <!-- Formulaire de création d'utilisateur -->
                <form action="admin.php" method="post" class="process-form">
                    <input type="hidden" name="action" value="create">
                    
                    <div class="form-group">
                        <label for="username">Nom d'utilisateur:</label>
                        <input type="text" id="username" name="username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="password">Mot de passe:</label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    
                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-user-plus"></i> Créer l'utilisateur
                        </button>
                    </div>
                </form>
            </section>

            <section class="list-section">
                <h2>Liste des Utilisateurs</h2>
                <div class="table-responsive">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nom d'utilisateur</th>
                                <th>Date de création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while($row = $result->fetch_assoc()): ?>
                                    <tr>
                                        <td><?php echo $row['id']; ?></td>
                                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                                        <td><?php echo date('d/m/Y H:i', strtotime($row['created_at'])); ?></td>
                                        <td>
                                            <?php if ($row['username'] !== 'admin'): ?>
                                                <form action="admin.php" method="post" style="display: inline;">
                                                    <input type="hidden" name="action" value="delete">
                                                    <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                                    <button type="submit" class="btn-icon btn-edit" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="4" class="no-data">Aucun utilisateur trouvé</td>
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