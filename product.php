<?php
require_once 'includes/config.php';

$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                       LEFT JOIN categories c ON p.category_id = c.id 
                       WHERE p.id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    header("Location: index.php");
    exit;
}

// Produits similaires
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? AND id != ? LIMIT 4");
$stmt->execute([$product['category_id'], $product_id]);
$related_products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($product['name']); ?> - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 50px; margin-bottom: 60px;">
                <div>
                    <div style="background: var(--light-bg); border-radius: 10px; padding: 60px; text-align: center; font-size: 8em;">
                        📦
                    </div>
                </div>
                
                <div>
                    <div style="margin-bottom: 15px;">
                        <a href="category.php?id=<?php echo $product['category_id']; ?>" 
                           style="color: var(--primary-color); text-decoration: none; font-weight: 600;">
                            <?php echo htmlspecialchars($product['category_name']); ?>
                        </a>
                    </div>
                    
                    <h1 style="font-size: 2.5em; margin-bottom: 20px; color: var(--text-color);">
                        <?php echo htmlspecialchars($product['name']); ?>
                    </h1>
                    
                    <div style="font-size: 2em; color: var(--primary-color); font-weight: bold; margin-bottom: 20px;">
                        <?php echo number_format($product['price'], 2); ?> DA
                    </div>
                    
                    <div style="padding: 20px; background: var(--light-bg); border-radius: 10px; margin-bottom: 30px;">
                        <p style="line-height: 1.8; color: var(--text-color);">
                            <?php echo nl2br(htmlspecialchars($product['description'])); ?>
                        </p>
                    </div>
                    
                    <div style="margin-bottom: 30px;">
                        <strong>Stock disponible :</strong> 
                        <?php if ($product['stock'] > 0): ?>
                            <span style="color: var(--success-color);">
                                ✓ <?php echo $product['stock']; ?> unités
                            </span>
                        <?php else: ?>
                            <span style="color: var(--danger-color);">✗ Rupture de stock</span>
                        <?php endif; ?>
                    </div>
                    
                    <?php if ($product['stock'] > 0): ?>
                        <div style="display: flex; gap: 15px;">
                            <div class="form-group" style="width: 120px; margin: 0;">
                                <label>Quantité</label>
                                <input type="number" id="quantity" value="1" min="1" max="<?php echo $product['stock']; ?>" 
                                       style="text-align: center; font-size: 1.1em;">
                            </div>
                            
                            <button onclick="addToCartWithQuantity(<?php echo $product['id']; ?>)" 
                                    class="btn btn-primary" 
                                    style="flex: 1; font-size: 1.1em;">
                                🛒 Ajouter au panier
                            </button>
                        </div>
                    <?php else: ?>
                        <button class="btn" disabled style="width: 100%; background: #ccc; cursor: not-allowed;">
                            Produit indisponible
                        </button>
                    <?php endif; ?>
                </div>
            </div>
            
            <?php if (count($related_products) > 0): ?>
                <h2 class="section-title">Produits similaires</h2>
                <div class="products-grid">
                    <?php foreach($related_products as $related): ?>
                        <div class="product-card">
                            <div class="product-image">📦</div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($related['name']); ?></h3>
                                <p class="product-description">
                                    <?php echo htmlspecialchars(substr($related['description'], 0, 80)) . '...'; ?>
                                </p>
                                <div class="product-price"><?php echo number_format($related['price'], 2); ?> DA</div>
                                <div class="product-actions">
                                    <a href="product.php?id=<?php echo $related['id']; ?>" class="btn btn-outline btn-sm">
                                        Détails
                                    </a>
                                    <button onclick="addToCart(<?php echo $related['id']; ?>)" class="btn btn-primary btn-sm">
                                        🛒 Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    
    <script src="js/main.js"></script>
    <script>
        function addToCartWithQuantity(productId) {
            const quantity = document.getElementById('quantity').value;
            addToCart(productId, quantity);
        }
    </script>
</body>
</html>
