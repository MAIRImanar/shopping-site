<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
    
    if ($product_id > 0 && $quantity > 0) {
        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);
        $product = $stmt->fetch();
        
        if ($product) {
            if ($product['stock'] >= $quantity) {
                if (isset($_SESSION['cart'][$product_id])) {
                    $_SESSION['cart'][$product_id] += $quantity;
                } else {
                    $_SESSION['cart'][$product_id] = $quantity;
                }
                if ($_SESSION['cart'][$product_id] > $product['stock']) {
                    $_SESSION['cart'][$product_id] = $product['stock'];
                }
                
                echo json_encode([
                    'success' => true,
                    'message' => 'Produit ajouté au panier',
                    'cart_count' => count($_SESSION['cart'])
                ]);
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Stock insuffisant'
                ]);
            }
        } else {
            echo json_encode([
                'success' => false,
                'message' => 'Produit introuvable'
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
