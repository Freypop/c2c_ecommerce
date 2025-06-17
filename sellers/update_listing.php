<?php
// filepath: c:\xampp\htdocs\c2c_platform\sellers\update_listing.php

session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

$seller_id = $_SESSION['user']['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $product_id = intval($_POST['product_id'] ?? 0);
    $name = trim($_POST['name'] ?? '');
    $description = trim($_POST['description'] ?? '');
    $price = floatval($_POST['price'] ?? 0);
    $discount_price = isset($_POST['discount_price']) ? floatval($_POST['discount_price']) : null;
    $category_id = intval($_POST['category_id'] ?? 0);

    // Optional: handle image upload here if needed

    // Check if the product belongs to the logged-in seller
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ? AND seller_id = ?");
    $stmt->execute([$product_id, $seller_id]);
    $product = $stmt->fetch();

    if ($product) {
        $update = $pdo->prepare("UPDATE products SET name = ?, description = ?, price = ?, discount_price = ?, category_id = ?, updated_at = NOW() WHERE product_id = ?");
        $update->execute([$name, $description, $price, $discount_price, $category_id, $product_id]);
        header('Location: my_listings.php?success=Product updated');
        exit;
    } else {
        header('Location: my_listings.php?error=Unauthorized or product not found');
        exit;
    }
} else {
    header('Location: my_listings.php?error=Invalid request');
    exit;
}