<?php
require_once 'includes/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=account");
    exit;
}

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

// Récupérer les commandes de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();

$success = '';
$error = '';

// Mise à jour du profil
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $full_name = clean_input($_POST['full_name']);
    $email = clean_input($_POST['email']);
    $phone = clean_input($_POST['phone']);
    $address = clean_input($_POST['address']);
    
    $stmt = $pdo->prepare("UPDATE users SET full_name = ?, email = ?, phone = ?, address = ? WHERE id = ?");
    if ($stmt->execute([$full_name, $email, $phone, $address, $_SESSION['user_id']])) {
        $success = "Profil mis à jour avec succès";
        // Recharger les données
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();
    } else {
        $error = "Erreur lors de la mise à jour";
    }
}

// Changement de mot de passe
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['change_password'])) {
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    if (password_verify($current_password, $user['password'])) {
        if ($new_password === $confirm_password) {
            if (strlen($new_password) >= 6) {
                $hashed = password_hash($new_password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
                if ($stmt->execute([$hashed, $_SESSION['user_id']])) {
                    $success = "Mot de passe changé avec succès";
                }
            } else {
                $error = "Le mot de passe doit contenir au moins 6 caractères";
            }
        } else {
            $error = "Les nouveaux mots de passe ne correspondent pas";
        }
    } else {
        $error = "Mot de passe actuel incorrect";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Compte - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <h1 class="section-title">👤 Mon Compte</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 30px;">
                <!-- Informations du profil -->
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: var(--shadow);">
                    <h2 style="margin-bottom: 25px; color: var(--primary-color);">📋 Mes Informations</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Nom complet</label>
                            <input type="text" name="full_name" value="<?php echo htmlspecialchars($user['full_name']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Téléphone</label>
                            <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Adresse</label>
                            <textarea name="address" rows="3"><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                        </div>
                        
                        <button type="submit" name="update_profile" class="btn btn-primary" style="width: 100%;">
                            Mettre à jour le profil
                        </button>
                    </form>
                </div>
                
                <!-- Changement de mot de passe -->
                <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: var(--shadow);">
                    <h2 style="margin-bottom: 25px; color: var(--primary-color);">🔐 Changer le mot de passe</h2>
                    <form method="POST" action="">
                        <div class="form-group">
                            <label>Mot de passe actuel</label>
                            <input type="password" name="current_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Nouveau mot de passe</label>
                            <input type="password" name="new_password" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Confirmer le nouveau mot de passe</label>
                            <input type="password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" name="change_password" class="btn btn-primary" style="width: 100%;">
                            Changer le mot de passe
                        </button>
                    </form>
                </div>
            </div>
            
            <!-- Historique des commandes -->
            <div style="margin-top: 40px; background: white; padding: 30px; border-radius: 10px; box-shadow: var(--shadow);">
                <h2 style="margin-bottom: 25px; color: var(--primary-color);">📦 Mes Commandes</h2>
                
                <?php if (count($orders) > 0): ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: var(--light-bg); text-align: left;">
                                <th style="padding: 15px; border-bottom: 2px solid var(--border-color);">Commande #</th>
                                <th style="padding: 15px; border-bottom: 2px solid var(--border-color);">Date</th>
                                <th style="padding: 15px; border-bottom: 2px solid var(--border-color);">Total</th>
                                <th style="padding: 15px; border-bottom: 2px solid var(--border-color);">Statut</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($orders as $order): ?>
                                <tr>
                                    <td style="padding: 15px; border-bottom: 1px solid var(--border-color);">
                                        #<?php echo $order['id']; ?>
                                    </td>
                                    <td style="padding: 15px; border-bottom: 1px solid var(--border-color);">
                                        <?php echo date('d/m/Y H:i', strtotime($order['created_at'])); ?>
                                    </td>
                                    <td style="padding: 15px; border-bottom: 1px solid var(--border-color);">
                                        <?php echo number_format($order['total'], 2); ?> DA
                                    </td>
                                    <td style="padding: 15px; border-bottom: 1px solid var(--border-color);">
                                        <?php
                                        $status_colors = [
                                            'pending' => '#f59e0b',
                                            'processing' => '#3b82f6',
                                            'shipped' => '#8b5cf6',
                                            'delivered' => '#10b981',
                                            'cancelled' => '#ef4444'
                                        ];
                                        $status_labels = [
                                            'pending' => 'En attente',
                                            'processing' => 'En cours',
                                            'shipped' => 'Expédié',
                                            'delivered' => 'Livré',
                                            'cancelled' => 'Annulé'
                                        ];
                                        ?>
                                        <span style="padding: 5px 15px; border-radius: 20px; background: <?php echo $status_colors[$order['status']]; ?>20; color: <?php echo $status_colors[$order['status']]; ?>; font-weight: 600;">
                                            <?php echo $status_labels[$order['status']]; ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <div style="text-align: center; padding: 40px 0;">
                        <div style="font-size: 4em; margin-bottom: 15px;">📦</div>
                        <p style="color: #6b7280;">Vous n'avez pas encore passé de commande</p>
                        <a href="index.php" class="btn" style="margin-top: 20px;">Commencer mes achats</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
