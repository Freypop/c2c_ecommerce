<?php
// filepath: c:\xampp\htdocs\c2c_platform\sellers\delete_listing.php

session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}

$seller_id = $_SESSION['user']['user_id'];
$product_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($product_id > 0) {
    // Check if the product belongs to the logged-in seller
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ? AND seller_id = ?");
    $stmt->execute([$product_id, $seller_id]);
    $product = $stmt->fetch();

    if ($product) {
        // Delete the product
        $del = $pdo->prepare("DELETE FROM products WHERE product_id = ?");
        $del->execute([$product_id]);
        header('Location: my_listings.php?success=Product deleted');
        exit;
    } else {
        header('Location: my_listings.php?error=Unauthorized or product not found');
        exit;
    }
} else {
    header('Location: my_listings.php?error=Invalid product ID');
    exit;
}