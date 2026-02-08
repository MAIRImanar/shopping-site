<?php
require_once 'includes/config.php';

$search_query = isset($_GET['q']) ? clean_input($_GET['q']) : '';
$products = [];

if (!empty($search_query)) {
    $search_term = "%$search_query%";
    $stmt = $pdo->prepare("SELECT p.*, c.name as category_name FROM products p 
                           LEFT JOIN categories c ON p.category_id = c.id 
                           WHERE p.name LIKE ? OR p.description LIKE ? 
                           ORDER BY p.created_at DESC");
    $stmt->execute([$search_term, $search_term]);
    $products = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche : <?php echo htmlspecialchars($search_query); ?> - ShopHub</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <section class="products-section">
        <div class="container">
            <h1 class="section-title">
                🔍 Résultats de recherche pour "<?php echo htmlspecialchars($search_query); ?>"
            </h1>
            
            <p style="text-align: center; color: #6b7280; margin-bottom: 40px;">
                <?php echo count($products); ?> produit(s) trouvé(s)
            </p>
            
            <?php if (count($products) > 0): ?>
                <div class="products-grid">
                    <?php foreach($products as $product): ?>
                        <div class="product-card">
                            <?php if($product['image'] && file_exists('images/products/' . $product['image'])): ?>
                                <img src="images/products/<?php echo htmlspecialchars($product['image']); ?>" 
                                     alt="<?php echo htmlspecialchars($product['name']); ?>" 
                                     class="product-image" 
                                     style="width: 100%; height: 250px; object-fit: cover;">
                            <?php else: ?>
                                <div class="product-image">📦</div>
                            <?php endif; ?>
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
                    <div style="font-size: 5em; margin-bottom: 20px;">🔍</div>
                    <h2>Aucun résultat trouvé</h2>
                    <p style="margin: 20px 0; color: #6b7280;">
                        Essayez avec d'autres mots-clés ou parcourez nos catégories
                    </p>
                    <a href="index.php" class="btn">Retour à l'accueil</a>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <?php include 'includes/footer.php'; ?>
    <script src="js/main.js"></script>
</body>
</html>
