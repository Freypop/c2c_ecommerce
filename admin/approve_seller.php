<?php
session_start();
require_once '../auth/auth_admin.php';
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'])) {
    $user_id = intval($_POST['user_id']);

    $stmt = $pdo->prepare("UPDATE users SET is_verified = 1 WHERE user_id = ?");
    $stmt->execute([$user_id]);

    header('Location: admin_dashboard.php');
    exit();
} else {
    echo "Invalid request.";
}
