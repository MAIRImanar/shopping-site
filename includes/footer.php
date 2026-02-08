<?php
if (!isset($pdo)) {
    require_once __DIR__ . '/config.php';
}

$stmt = $pdo->query("SELECT * FROM categories");
$footer_categories = $stmt->fetchAll();
?>
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
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li><a href="account.php">Mon Compte</a></li>
                    <?php endif; ?>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Catégories</h3>
                <ul>
                    <?php foreach($footer_categories as $category): ?>
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
