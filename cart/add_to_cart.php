<?php
session_start();

$product_id = $_POST['product_id'] ?? null;
$quantity = $_POST['quantity'] ?? 1;

if (!$product_id) {
    header('Location: ../products/products.php');
    exit;
}

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add or update product in cart
if (isset($_SESSION['cart'][$product_id])) {
    $_SESSION['cart'][$product_id] += $quantity;
} else {
    $_SESSION['cart'][$product_id] = $quantity;
}

// Redirect back to product page or cart
header('Location: ../buyer/cart.php');
exit;
?>