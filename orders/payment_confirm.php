<?php
session_start();
require_once '../db.php';

$order_id = intval($_GET['order_id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ?");
$stmt->execute([$order_id]);
$order = $stmt->fetch();

if (!$order) {
    echo "Order not found.";
    exit;
}
?>
<!DOCTYPE html>
<html>
<head><title>Payment Success</title></head>
<body>
    <h2>Payment Successful!</h2>
    <p>Your order #<?= $order_id ?> has been placed and paid.</p>
    <a href="my_orders.php">View My Orders</a>
</body>
</html>