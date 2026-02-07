<?php
require_once 'includes/config.php';

$error = '';
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : 'index.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = clean_input($_POST['username']);
    $password = $_POST['password'];
    
    if (!empty($username) && !empty($password)) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['is_admin'] = $user['is_admin'];
            
            header("Location: $redirect.php");
            exit;
        } else {
            $error = "Nom d'utilisateur ou mot de passe incorrect";
        }
    } else {
        $error = "Veuillez remplir tous les champs";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - ShopHub</title>
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
                <h1 class="section-title">🔐 Connexion</h1>
                
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: var(--shadow);">
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Nom d'utilisateur ou Email</label>
                            <input type="text" name="username" required 
                                   value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Mot de passe</label>
                            <input type="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            Se connecter
                        </button>
                    </form>
                    
                    <div style="text-align: center; margin-top: 20px; padding-top: 20px; border-top: 1px solid var(--border-color);">
                        <p>Pas encore de compte ? <a href="register.php" style="color: var(--primary-color); font-weight: 600;">Créer un compte</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script src="js/main.js"></script>
</body>
</html>
