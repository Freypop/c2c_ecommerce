<?php
// filepath: c:\xampp\htdocs\c2c_platform\sellers\edit_listing.php

session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

$seller_id = $_SESSION['user']['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id <= 0) {
    header('Location: my_listings.php?error=Invalid product ID');
    exit;
}

// Fetch the product and check ownership
$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ? AND seller_id = ?");
$stmt->execute([$product_id, $seller_id]);
$product = $stmt->fetch();

if (!$product) {
    header('Location: my_listings.php?error=Product not found or unauthorized');
    exit;
}

// Fetch categories for the dropdown
$cat_stmt = $pdo->query("SELECT category_id, name FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Edit Product Listing</h2>
    <form method="post" action="update_listing.php">
        <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']) ?>">
        <div class="mb-3">
            <label class="form-label">Product Name</label>
            <input type="text" name="name" class="form-control" value="<?= htmlspecialchars($product['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" required><?= htmlspecialchars($product['description']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Price</label>
            <input type="number" step="0.01" name="price" class="form-control" value="<?= htmlspecialchars($product['price']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Discount Price</label>
            <input type="number" step="0.01" name="discount_price" class="form-control" value="<?= htmlspecialchars($product['discount_price']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="category_id" class="form-select" required>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['category_id'] ?>" <?= $cat['category_id'] == $product['category_id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <!-- Optional: Add image upload here -->
        <button type="submit" class="btn btn-primary">Update Listing</button>
        <a href="my_listings.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>
</body>
</html>