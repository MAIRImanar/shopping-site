<?php
require_once 'includes/config.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $full_name = clean_input($_POST['full_name']);
    
    if (empty($username) || empty($email) || empty($password) || empty($full_name)) {
        $error = "Tous les champs sont obligatoires";
    } elseif ($password !== $confirm_password) {
        $error = "Les mots de passe ne correspondent pas";
    } elseif (strlen($password) < 6) {
        $error = "Le mot de passe doit contenir au moins 6 caractères";
    } else {
        // Vérifier si l'utilisateur existe déjà
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $email]);
        
        if ($stmt->fetch()) {
            $error = "Ce nom d'utilisateur ou email existe déjà";
        } else {
            // Créer le compte
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name) VALUES (?, ?, ?, ?)");
            
            if ($stmt->execute([$username, $email, $hashed_password, $full_name])) {
                $success = "Compte créé avec succès ! Vous pouvez maintenant vous connecter.";
            } else {
                $error = "Une erreur est survenue lors de la création du compte";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="header-main">
            <div class="container">
                <a href="index.php" class="logo">🛍️ ShopHub</a>
            </div>
        </div>
    </header>

    <section class="products-section">
        <div class="container">
            <div style="max-width: 500px; margin: 0 auto;">
                <h1 class="section-title">📝 Inscription</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <?php if ($success): ?>
                    <div class="alert alert-success">
                        <?php echo $success; ?>
                        <br><a href="login.php" style="color: var(--success-color); font-weight: 600;">Se connecter maintenant</a>
                    </div>
                <?php endif; ?>
                
                <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: var(--shadow);">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Nom complet *</label>
                            <input type="text" name="full_name" required 
                                   value="<?php echo isset($_POST['full_name']) ? htmlspecialchars($_POST['full_name']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Nom d'utilisateur *</label>
                            <input type="text" name="username" required 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Email *</label>
                            <input type="email" name="email" required 
                                   value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Mot de passe * (minimum 6 caractères)</label>
                            <input type="password" name="password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmer le mot de passe *</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Créer mon compte
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                        <p>Déjà un compte ? <a href="login.php" style="color: var(--primary-color); font-weight: 600;">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/main.js"></script>
</body>
</html>
