<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user'])) {
    header('Location: ../user/login.php');
    exit;
}
if ($_SESSION['user']['role'] !== 'seller' && $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../index.php?error=Only sellers can list items');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $seller_id = $_SESSION['user']['user_id'];
    $name = trim($_POST['listing_title']);
    $description = trim($_POST['description']);
    $category_id = intval($_POST['category']);
    // var_dump($category_id); exit; // Add this line to debug
    $location = trim($_POST['location']);
    $mobile = trim($_POST['mobile']);
    // Handle file upload if needed...

    $stmt = $pdo->prepare("INSERT INTO products (seller_id, name, description, category_id, location, mobile) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$seller_id, $name, $description, $category_id, $location, $mobile]);

    header('Location: my_listing.php?success=Your item has been listed!');
    exit;
}
?>