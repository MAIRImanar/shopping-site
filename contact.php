<?php
require_once 'includes/config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = clean_input($_POST['name']);
    $email = clean_input($_POST['email']);
    $subject = clean_input($_POST['subject']);
    $message = clean_input($_POST['message']);
    
    if (!empty($name) && !empty($email) && !empty($subject) && !empty($message)) {
        // Dans un vrai projet, vous enverriez un email ici
        // mail($to, $subject, $message, $headers);
        
        $success = "Merci pour votre message ! Nous vous répondrons dans les plus brefs délais.";
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
    <title>Contact - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <h1 class="section-title">📧 Contactez-nous</h1>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 40px;">
                <!-- Formulaire de contact -->
                <div>
                    <?php if ($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <?php if ($error): ?>
                        <div class="alert alert-error"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: var(--shadow);">
                        <h2 style="margin-bottom: 25px; color: var(--primary-color);">💬 Envoyez-nous un message</h2>
                        
                        <form method="POST" action="">
                            <div class="form-group">
                                <label>Nom complet *</label>
                                <input type="text" name="name" required 
                                       value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" name="email" required 
                                       value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Sujet *</label>
                                <input type="text" name="subject" required 
                                       value="<?php echo isset($_POST['subject']) ? htmlspecialchars($_POST['subject']) : ''; ?>">
                            </div>
                            
                            <div class="form-group">
                                <label>Message *</label>
                                <textarea name="message" rows="6" required><?php echo isset($_POST['message']) ? htmlspecialchars($_POST['message']) : ''; ?></textarea>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%;">
                                📤 Envoyer le message
                            </button>
                        </form>
                    </div>
                </div>
                
                <!-- Informations de contact -->
                <div>
                    <div style="background: white; padding: 40px; border-radius: 10px; box-shadow: var(--shadow); margin-bottom: 30px;">
                        <h2 style="margin-bottom: 25px; color: var(--primary-color);">📍 Nos coordonnées</h2>
                        
                        <div style="margin-bottom: 25px;">
                            <div style="display: flex; align-items: start; gap: 15px; margin-bottom: 20px;">
                                <div style="font-size: 1.8em;">📍</div>
                                <div>
                                    <strong>Adresse</strong>
                                    <p style="margin-top: 5px; color: #6b7280;">
                                        123 Avenue de la République<br>
                                        Sétif 19000, Algérie
                                    </p>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: start; gap: 15px; margin-bottom: 20px;">
                                <div style="font-size: 1.8em;">☎️</div>
                                <div>
                                    <strong>Téléphone</strong>
                                    <p style="margin-top: 5px; color: #6b7280;">
                                        +213 697 177 549<br>
                                        +213 555 789 012
                                    </p>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: start; gap: 15px; margin-bottom: 20px;">
                                <div style="font-size: 1.8em;">📧</div>
                                <div>
                                    <strong>Email</strong>
                                    <p style="margin-top: 5px; color: #6b7280;">
                                        mairimanar2021@gmail.com<br>
                                        support@shophub.com
                                    </p>
                                </div>
                            </div>
                            
                            <div style="display: flex; align-items: start; gap: 15px;">
                                <div style="font-size: 1.8em;">🕐</div>
                                <div>
                                    <strong>Horaires d'ouverture</strong>
                                    <p style="margin-top: 5px; color: #6b7280;">
                                        Lundi - Vendredi: 9h00 - 18h00<br>
                                        Samedi: 9h00 - 13h00<br>
                                        Dimanche: Fermé
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); padding: 40px; border-radius: 10px; color: white;">
                        <h3 style="margin-bottom: 15px; font-size: 1.5em;">💡 Besoin d'aide ?</h3>
                        <p style="line-height: 1.8; margin-bottom: 20px;">
                            Notre équipe de support client est disponible pour répondre à toutes vos questions concernant nos produits, les commandes et les livraisons.
                        </p>
                        <a href="index.php" class="btn" style="background: white; color: var(--primary-color);">
                            Voir nos produits
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
