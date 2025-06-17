<?php
// filepath: c:\xampp\htdocs\c2c_platform\admin\reports\products.php
session_start();
require_once '../../db.php';

// Fetch all products
$stmt = $pdo->query("SELECT * FROM products ORDER BY created_at DESC");
$products = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Product Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h2>Product Report</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Price</th><th>Stock</th><th>Created</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $p): ?>
            <tr>
                <td><?= $p['product_id'] ?></td>
                <td><?= htmlspecialchars($p['name']) ?></td>
                <td>R<?= number_format($p['price'], 2) ?></td>
                <td><?= $p['stock'] ?? '-' ?></td>
                <td><?= $p['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>