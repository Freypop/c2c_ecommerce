<?php
// filepath: c:\xampp\htdocs\c2c_ecommerce\products\product_pages\electronics.php

$category = 'Electronics';

// Include your database connection
require_once __DIR__ . '/../../db.php'; // Adjust path if needed

// Fetch products for Books (assuming category_id = 1)
$stmt = $pdo->prepare("SELECT * FROM products WHERE category_id = :catid");
$stmt->execute(['catid' => 1]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../buyer/category_template.php';
