<?php
// filepath: c:\xampp\htdocs\c2c_platform\admin\reports\sales.php
session_start();
require_once '../../db.php';

// Fetch all sales/orders
$stmt = $pdo->query("SELECT o.*, u.username, p.name AS product_name FROM orders o 
    LEFT JOIN users u ON o.user_id = u.user_id 
    LEFT JOIN products p ON o.product_id = p.product_id 
    ORDER BY o.created_at DESC");
$sales = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h2>Sales Report</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Order ID</th><th>User</th><th>Product</th><th>Total</th><th>Status</th><th>Date</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($sales as $s): ?>
            <tr>
                <td><?= $s['order_id'] ?></td>
                <td><?= htmlspecialchars($s['username'] ?? '-') ?></td>
                <td><?= htmlspecialchars($s['product_name'] ?? '-') ?></td>
                <td>R<?= number_format($s['total_price'], 2) ?></td>
                <td><?= htmlspecialchars($s['status']) ?></td>
                <td><?= $s['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>