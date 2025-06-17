<?php
// filepath: c:\xampp\htdocs\c2c_platform\orders\order_details.php

session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

$order_id = intval($_GET['order_id'] ?? 0);
$user_id = $_SESSION['user']['user_id'];
$user_role = $_SESSION['user']['role'] ?? '';

// Fetch order details, product, buyer, and seller info
$stmt = $pdo->prepare("
    SELECT o.*, 
           p.name AS product_name, p.description AS product_description, p.price AS product_price,
           buyer.username AS buyer_name, seller.username AS seller_name
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    JOIN users buyer ON o.buyer_id = buyer.user_id
    JOIN users seller ON o.seller_id = seller.user_id
    WHERE o.order_id = ?
");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='alert alert-danger'>Order not found.</div>";
    exit;
}

// Only allow buyer, seller, or admin to view
if (
    $user_role !== 'admin' &&
    $user_id != $order['buyer_id'] &&
    $user_id != $order['seller_id']
) {
    echo "<div class='alert alert-danger'>Access denied.</div>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Details #<?= htmlspecialchars($order_id) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Order Details #<?= htmlspecialchars($order_id) ?></h2>
    <table class="table table-bordered">
        <tr>
            <th>Product</th>
            <td><?= htmlspecialchars($order['product_name']) ?></td>
        </tr>
        <tr>
            <th>Description</th>
            <td><?= htmlspecialchars($order['product_description']) ?></td>
        </tr>
        <tr>
            <th>Price</th>
            <td>$<?= htmlspecialchars($order['product_price']) ?></td>
        </tr>
        <tr>
            <th>Buyer</th>
            <td><?= htmlspecialchars($order['buyer_name']) ?></td>
        </tr>
        <tr>
            <th>Seller</th>
            <td><?= htmlspecialchars($order['seller_name']) ?></td>
        </tr>
        <tr>
            <th>Status</th>
            <td><?= htmlspecialchars($order['status']) ?></td>
        </tr>
        <tr>
            <th>Created At</th>
            <td><?= htmlspecialchars($order['created_at']) ?></td>
        </tr>
        <tr>
            <th>Updated At</th>
            <td><?= htmlspecialchars($order['updated_at']) ?></td>
        </tr>
    </table>
    <a href="my_orders.php" class="btn btn-secondary">Back to My Orders</a>
</div>
</body>
</html>