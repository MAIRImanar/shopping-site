<?php
require_once 'includes/config.php';

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=checkout");
    exit;
}

// Vérifier si le panier n'est pas vide
if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations de l'utilisateur
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

// Calculer le total du panier
$cart_items = [];
$total = 0;

$product_ids = array_keys($_SESSION['cart']);
$placeholders = str_repeat('?,', count($product_ids) - 1) . '?';

$stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($placeholders)");
$stmt->execute($product_ids);

while ($product = $stmt->fetch()) {
    $quantity = $_SESSION['cart'][$product['id']];
    $subtotal = $product['price'] * $quantity;
    $total += $subtotal;
    
    $cart_items[] = [
        'product' => $product,
        'quantity' => $quantity,
        'subtotal' => $subtotal
    ];
}

$success = '';
$error = '';

// Traitement de la commande
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $shipping_address = clean_input($_POST['shipping_address']);
    $phone = clean_input($_POST['phone']);
    
    if (!empty($shipping_address) && !empty($phone)) {
        try {
            $pdo->beginTransaction();
            
            // Créer la commande
            $stmt = $pdo->prepare("INSERT INTO orders (user_id, total, shipping_address, status) VALUES (?, ?, ?, 'pending')");
            $stmt->execute([$user_id, $total, $shipping_address]);
            $order_id = $pdo->lastInsertId();
            
            // Ajouter les articles de la commande
            $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
            
            foreach ($cart_items as $item) {
                $stmt->execute([
                    $order_id,
                    $item['product']['id'],
                    $item['quantity'],
                    $item['product']['price']
                ]);
                
                // Mettre à jour le stock
                $update_stock = $pdo->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
                $update_stock->execute([$item['quantity'], $item['product']['id']]);
            }
            
            $pdo->commit();
            
            // Vider le panier
            $_SESSION['cart'] = [];
            
            $success = "Commande passée avec succès ! Numéro de commande : #$order_id";
        } catch (Exception $e) {
            $pdo->rollBack();
            $error = "Erreur lors du traitement de la commande : " . $e->getMessage();
        }
    } else {
        $error = "Veuillez remplir tous les champs obligatoires";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paiement - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <h1 class="section-title">💳 Finaliser la commande</h1>
            
            <?php if ($success): ?>
                <div class="alert alert-success">
                    <?php echo $success; ?>
                    <div style="margin-top: 20px;">
                        <a href="account.php" class="btn btn-primary">Voir mes commandes</a>
                        <a href="index.php" class="btn btn-outline">Continuer mes achats</a>
                    </div>
                </div>
            <?php else: ?>
                <?php if ($error): ?>
                    <div class="alert alert-error"><?php echo $error; ?></div>
                <?php endif; ?>
                
                <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px;">
                    <!-- Formulaire de livraison -->
                    <div style="background: white; padding: 30px; border-radius: 10px; box-shadow: var(--shadow);">
                        <h2 style="margin-bottom: 25px; color: var(--primary-color);">📍 Informations de livraison</h2>
                        
                        <form method="POST" action="" id="checkout-form">
                            <div class="form-group">
                                <label>Nom complet *</label>
                                <input type="text" value="<?php echo htmlspecialchars($user['full_name']); ?>" readonly style="background: var(--light-bg);">
                            </div>
                            
                            <div class="form-group">
                                <label>Email *</label>
                                <input type="email" value="<?php echo htmlspecialchars($user['email']); ?>" readonly style="background: var(--light-bg);">
                            </div>
                            
                            <div class="form-group">
                                <label>Téléphone *</label>
                                <input type="tel" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>" required>
                            </div>
                            
                            <div class="form-group">
                                <label>Adresse de livraison complète *</label>
                                <textarea name="shipping_address" rows="4" required><?php echo htmlspecialchars($user['address'] ?? ''); ?></textarea>
                                <small style="color: #6b7280;">Incluez la ville, le code postal et les détails nécessaires</small>
                            </div>
                            
                            <div style="background: var(--light-bg); padding: 20px; border-radius: 5px; margin: 20px 0;">
                                <h3 style="margin-bottom: 15px; font-size: 1.1em;">💰 Mode de paiement</h3>
                                <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                                    <input type="radio" name="payment_method" value="cod" checked>
                                    <span>💵 Paiement à la livraison (Cash on Delivery)</span>
                                </label>
                            </div>
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%; font-size: 1.1em;">
                                🛒 Confirmer la commande
                            </button>
                        </form>
                    </div>
                    
                    <!-- Résumé de la commande -->
                    <div>
                        <div class="cart-summary">
                            <h3 style="margin-bottom: 20px;">📋 Résumé de la commande</h3>
                            
                            <?php foreach ($cart_items as $item): ?>
                                <div style="display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid var(--border-color);">
                                    <div>
                                        <strong><?php echo htmlspecialchars($item['product']['name']); ?></strong>
                                        <br>
                                        <small style="color: #6b7280;">Quantité: <?php echo $item['quantity']; ?></small>
                                    </div>
                                    <div>
                                        <?php echo number_format($item['subtotal'], 2); ?> DA
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            
                            <div class="summary-row">
                                <span>Sous-total</span>
                                <span><?php echo number_format($total, 2); ?> DA</span>
                            </div>
                            
                            <div class="summary-row">
                                <span>Livraison</span>
                                <span style="color: var(--success-color); font-weight: 600;">Gratuite</span>
                            </div>
                            
                            <div class="summary-row summary-total">
                                <span>Total à payer</span>
                                <span><?php echo number_format($total, 2); ?> DA</span>
                            </div>
                        </div>
                        
                        <div style="background: #fef3c7; padding: 15px; border-radius: 5px; margin-top: 20px; border-left: 4px solid var(--accent-color);">
                            <strong>ℹ️ Informations importantes :</strong>
                            <ul style="margin: 10px 0 0 20px; line-height: 1.8;">
                                <li>Livraison gratuite</li>
                                <li>Délai: 3-5 jours ouvrables</li>
                                <li>Paiement à la livraison</li>
                            </ul>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
