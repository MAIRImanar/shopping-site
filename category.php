<?php
require_once 'includes/config.php';

$category_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Récupérer la catégorie
$stmt = $pdo->prepare("SELECT * FROM categories WHERE id = ?");
$stmt->execute([$category_id]);
$category = $stmt->fetch();

if (!$category) {
    header("Location: index.php");
    exit;
}

// Récupérer les produits de cette catégorie
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = ? ORDER BY created_at DESC");
$stmt->execute([$category_id]);
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($category['name']); ?> - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <h1 class="section-title"><?php echo htmlspecialchars($category['name']); ?></h1>
            
            <?php if ($category['description']): ?>
                <p style="text-align: center; color: #6b7280; max-width: 700px; margin: -30px auto 50px; line-height: 1.8;">
                    <?php echo htmlspecialchars($category['description']); ?>
                </p>
            <?php endif; ?>
            
            <?php if (count($products) > 0): ?>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <div class="product-image">📦</div>
                            <div class="product-info">
                                <h3 class="product-name"><?php echo htmlspecialchars($product['name']); ?></h3>
                                <p class="product-description">
                                    <?php echo htmlspecialchars(substr($product['description'], 0, 80)) . '...'; ?>
                                </p>
                                <div class="product-price"><?php echo number_format($product['price'], 2); ?> DA</div>
                                <div class="product-actions">
                                    <a href="product.php?id=<?php echo $product['id']; ?>" class="btn btn-outline btn-sm">
                                        Détails
                                    </a>
                                    <button onclick="addToCart(<?php echo $product['id']; ?>)" class="btn btn-primary btn-sm">
                                        🛒 Ajouter
                                    </button>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else: ?>
                <div style="text-align: center; padding: 60px 0;">
                    <div style="font-size: 5em; margin-bottom: 20px;">📦</div>
                    <h2>Aucun produit disponible</h2>
                    <p style="margin: 20px 0; color: #6b7280;">Cette catégorie ne contient pas encore de produits.</p>
                    <a href="index.php" class="btn">Retour à l'accueil</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
