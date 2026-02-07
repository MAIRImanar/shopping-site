<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 0;
    
    if ($product_id > 0 && $quantity > 0) {
        // Récupérer les infos du produit
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product && $quantity <= $product['stock']) {
            $_SESSION['cart'][$product_id] = $quantity;
            
            // Calculer le total du panier
            $cart_total = 0;
            foreach ($_SESSION['cart'] as $pid => $qty) {
                $stmt = $pdo->prepare("SELECT price FROM products WHERE id = ?");
                $stmt->execute([$pid]);
                $p = $stmt->fetch();
                if ($p) {
                    $cart_total += $p['price'] * $qty;
                }
            }
            
            echo json_encode([
                'success' => true,
                'product_price' => $product['price'],
                'cart_total' => $cart_total,
                'cart_count' => count($_SESSION['cart'])
            ]);
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Stock insuffisant ou produit introuvable'
            ]);
        }
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Données invalides'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>
