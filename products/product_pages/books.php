<?php
// filepath: c:\xampp\htdocs\c2c_ecommerce\products\product_pages\electronics.php

$category = 'Books';

// Include your database connection
require_once __DIR__ . '/../../db.php'; // Adjust path if needed

// Fetch products for Books (assuming category_id = 1)
$stmt = $pdo->prepare("
    SELECT p.*, 
           (SELECT image FROM product_images WHERE product_id = p.product_id LIMIT 1) AS main_image
    FROM products p
    WHERE p.category_id = :catid
");
$stmt->execute(['catid' => 6]);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

include '../../buyer/category_template.php';
