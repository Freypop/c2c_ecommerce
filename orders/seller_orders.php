<?php
// filepath: c:\xampp\htdocs\c2c_platform\orders\seller_orders.php

session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

$seller_id = $_SESSION['user']['user_id'];

// Fetch all orders for this seller
$stmt = $pdo->prepare("
    SELECT o.*, 
           p.name AS product_name, 
           u.username AS buyer_name
    FROM orders o
    JOIN products p ON o.product_id = p.product_id
    JOIN users u ON o.buyer_id = u.user_id
    WHERE o.seller_id = ?
    ORDER BY o.created_at DESC
");
$stmt->execute([$seller_id]);
$orders = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Sales Orders</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Orders for My Products</h2>
    <?php if (count($orders) === 0): ?>
        <div class="alert alert-info">You have no orders yet.</div>
    <?php else: ?>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Product</th>
                    <th>Buyer</th>
                    <th>Total Price</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Details</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($orders as $order): ?>
                <tr>
                    <td><?= htmlspecialchars($order['order_id']) ?></td>
                    <td><?= htmlspecialchars($order['product_name']) ?></td>
                    <td><?= htmlspecialchars($order['buyer_name']) ?></td>
                    <td>$<?= htmlspecialchars($order['total_price']) ?></td>
                    <td><?= htmlspecialchars($order['status']) ?></td>
                    <td><?= htmlspecialchars($order['created_at']) ?></td>
                    <td>
                        <a href="order_details.php?order_id=<?= $order['order_id'] ?>" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
</body>
</html>