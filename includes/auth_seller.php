<?php
require_once 'auth_user.php'; // Ensures the user is logged in

$user = $_SESSION['user'];

if (!isset($user['role']) || $user['role'] !== 'seller' || $user['is_verified'] != 1) {
    echo "Access denied. You must be a verified seller.";
    exit();
}
?>
