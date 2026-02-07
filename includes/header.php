<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/config.php';
}

// Récupérer les catégories
$stmt = $pdo->query("SELECT * FROM categories");
$categories = $stmt->fetchAll();
?>
<header>
    <div class="header-top">
        <div class="container">
            <div>📧 contact@shophub.com | ☎️ +213 555 123 456</div>
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
                    <?php if(isset($_SESSION['cart']) && count($_SESSION['cart']) > 0): ?>
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
