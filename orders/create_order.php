<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id']);
    $buyer_id = $_SESSION['user']['user_id'];

    // Fetch product and seller info
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$product_id]);
    $product = $stmt->fetch();

    if (!$product) {
        header('Location: ../index.php?error=Product not found');
        exit;
    }

    $seller_id = $product['seller_id'];
    $total_price = $product['price']; // Adjust for quantity/discount if needed

    // Insert order
    $order_stmt = $pdo->prepare("INSERT INTO orders (product_id, buyer_id, seller_id, total_price) VALUES (?, ?, ?, ?)");
    $order_stmt->execute([$product_id, $buyer_id, $seller_id, $total_price]);

    $order_id = $pdo->lastInsertId();

    // Redirect to payment page
    header("Location: payment.php?order_id=$order_id");
    exit;
}
?>