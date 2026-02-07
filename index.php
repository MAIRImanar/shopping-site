<?php
require_once 'includes/config.php';

// Récupérer les produits en vedette
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     WHERE p.featured = 1 
                     ORDER BY p.created_at DESC LIMIT 8");
$featured_products = $stmt->fetchAll();

// Récupérer tous les produits pour la section principale
$stmt = $pdo->query("SELECT p.*, c.name as category_name FROM products p 
                     LEFT JOIN categories c ON p.category_id = c.id 
                     ORDER BY p.created_at DESC");
$all_products = $stmt->fetchAll();

// Récupérer les catégories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ShopHub - Votre Boutique en Ligne</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Header -->
    <header>
        <div class="header-top">
            <div class="container">
                <div>📧 mairimanar2021@gmail.com | ☎️ +213 697 177 549</div>
                <div>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        Bienvenue, <?php echo htmlspecialchars($_SESSION['username']); ?> | 
                        <a href="logout.php" style="color: white;">Déconnexion</a>
                    <?php else: ?>
                        <a href="login.php" style="color: white;">Connexion</a> | 
                        <a href="register.php" style="color: white;">Inscription</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <div class="header-main">
            <div class="container">
                <a href="index.php" class="logo">🛍️ ShopHub</a>
                
                <div class="search-bar">
                    <form action="search.php" method="GET">
                        <input type="text" name="q" placeholder="Rechercher des produits..." required>
                        <button type="submit">🔍</button>
                    </form>
                </div>
                
                <div class="header-actions">
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <a href="account.php">👤 Mon Compte</a>
                    <?php endif; ?>
                    <a href="cart.php">
                        🛒 Panier
                        <?php if(count($_SESSION['cart']) > 0): ?>
                            <span class="cart-count"><?php echo count($_SESSION['cart']); ?></span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
        
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <?php foreach($categories as $category): ?>
                    <li><a href="category.php?id=<?php echo $category['id']; ?>">
                        <?php echo htmlspecialchars($category['name']); ?>
                    </a></li>
                <?php endforeach; ?>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
    </header>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Bienvenue chez ShopHub</h1>
            <p>Découvrez nos meilleures offres et produits de qualité</p>
            <a href="#products" class="btn">Voir les Produits</a>
        </div>
    </section>

    <!-- Featured Products -->
    <?php if(count($featured_products) > 0): ?>
    <section class="products-section" style="background: var(--light-bg); padding: 60px 0;">
        <div class="container">
            <h2 class="section-title">⭐ Produits en Vedette</h2>
            <div class="products-grid">
                <?php foreach($featured_products as $product): ?>
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
        </div>
    </section>
    <?php endif; ?>

    <!-- All Products -->
    <section class="products-section" id="products">
        <div class="container">
            <h2 class="section-title">Tous nos Produits</h2>
            <div class="products-grid">
                <?php foreach($all_products as $product): ?>
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
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="container">
            <div class="footer-content">
                <div class="footer-section">
                    <h3>À Propos</h3>
                    <p>ShopHub est votre destination shopping en ligne pour tous vos besoins. Qualité garantie et livraison rapide.</p>
                </div>
                <div class="footer-section">
                    <h3>Liens Rapides</h3>
                    <ul>
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="cart.php">Panier</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="about.php">À Propos</a></li>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Catégories</h3>
                    <ul>
                        <?php foreach($categories as $category): ?>
                            <li><a href="category.php?id=<?php echo $category['id']; ?>">
                                <?php echo htmlspecialchars($category['name']); ?>
                            </a></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
                <div class="footer-section">
                    <h3>Contact</h3>
                    <ul>
                        <li>📧 mairimanar2021@gmail.com</li>
                        <li>☎️ +213 697 177 549</li>
                        <li>📍 Sétif, Algérie</li>
                    </ul>
                </div>
            </div>
            <div class="footer-bottom">
                <p>&copy; 2026 ShopHub. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script src="js/main.js"></script>
</body>
</html>
