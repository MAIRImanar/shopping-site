<?php
require_once 'includes/config.php';

$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
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
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <h1 class="section-title">🛒 Mon Panier</h1>
            
            <?php if (empty($cart_items)): ?>
                <div style="text-align: center; padding: 60px 0;">
                    <div style="font-size: 5em; margin-bottom: 20px;">🛒</div>
                    <h2>Votre panier est vide</h2>
                    <p style="margin: 20px 0; color: #6b7280;">Commencez vos achats dès maintenant!</p>
                    <a href="index.php" class="btn">Continuer mes achats</a>
                </div>
            <?php else: ?>
                <div style="display: grid; grid-template-columns: 1fr 400px; gap: 30px; align-items: start;">
                    <div class="cart-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($cart_items as $item): ?>
                                    <tr>
                                        <td>
                                            <div style="display: flex; gap: 15px; align-items: center;">
                                                <div class="cart-item-image">📦</div>
                                                <div>
                                                    <strong><?php echo htmlspecialchars($item['product']['name']); ?></strong>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo number_format($item['product']['price'], 2); ?> DA</td>
                                        <td>
                                            <div class="quantity-control">
                                                <button onclick="updateCartQuantity(<?php echo $item['product']['id']; ?>, -1)">-</button>
                                                <span id="quantity-<?php echo $item['product']['id']; ?>">
                                                    <?php echo $item['quantity']; ?>
                                                </span>
                                                <button onclick="updateCartQuantity(<?php echo $item['product']['id']; ?>, 1)">+</button>
                                            </div>
                                        </td>
                                        <td>
                                            <strong id="item-total-<?php echo $item['product']['id']; ?>">
                                                <?php echo number_format($item['subtotal'], 2); ?> DA
                                            </strong>
                                        </td>
                                        <td>
                                            <button onclick="removeFromCart(<?php echo $item['product']['id']; ?>)" 
                                                    class="btn btn-sm" 
                                                    style="background: var(--danger-color);">
                                                🗑️ Supprimer
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="cart-summary">
                        <h3 style="margin-bottom: 20px;">Résumé de la commande</h3>
                        
                        <div class="summary-row">
                            <span>Sous-total</span>
                            <span id="cart-total"><?php echo number_format($total, 2); ?> DA</span>
                        </div>
                        
                        <div class="summary-row">
                            <span>Livraison</span>
                            <span>Gratuite</span>
                        </div>
                        
                        <div class="summary-row summary-total">
                            <span>Total</span>
                            <span><?php echo number_format($total, 2); ?> DA</span>
                        </div>
                        
                        <?php if (isset($_SESSION['user_id'])): ?>
                            <a href="checkout.php" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 20px;">
                                Procéder au paiement
                            </a>
                        <?php else: ?>
                            <a href="login.php?redirect=checkout" class="btn btn-primary" style="width: 100%; text-align: center; margin-top: 20px;">
                                Connexion pour commander
                            </a>
                        <?php endif; ?>
                        
                        <a href="index.php" class="btn btn-outline" style="width: 100%; text-align: center; margin-top: 10px;">
                            Continuer mes achats
                        </a>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
