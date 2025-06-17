<?php
require_once 'auth_user.php'; // Ensures the user is logged in

$user = $_SESSION['user'];

if (!isset($user['role']) || $user['role'] !== 'admin') {
    echo "Access denied. Admin access only.";
    exit();
}
?>
