<?php
require_once '../includes/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = isset($_POST['product_id']) ? (int)$_POST['product_id'] : 0;
    
    if ($product_id > 0 && isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Produit retiré du panier',
            'cart_count' => count($_SESSION['cart'])
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Produit non trouvé dans le panier'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Méthode non autorisée'
    ]);
}
?>
