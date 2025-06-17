<?php
session_start();
require_once '../db.php';

// Get order_id from URL
$order_id = intval($_GET['order_id'] ?? 0);

// Fetch order info (make sure it belongs to the logged-in user)
$stmt = $pdo->prepare("SELECT * FROM orders WHERE order_id = ? AND user_id = ?");
$stmt->execute([$order_id, $_SESSION['user']['user_id']]);
$order = $stmt->fetch();

if (!$order) {
    echo "<div class='alert alert-danger'>Order not found or access denied.</div>";
    exit;
}

// Fetch order items and product info
$stmt = $pdo->prepare("
    SELECT oi.*, p.name AS product_name, p.price, p.image
    FROM order_items oi
    JOIN products p ON oi.product_id = p.product_id
    WHERE oi.order_id = ?
");
$stmt->execute([$order_id]);
$order_items = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Order Payment - C2C Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>

<!-- Navbar (consistent with index.php) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/images/marketplace_logo.jpg" alt="Marketplace Logo" style="height:40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../buyer/category.php">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="../buyer/deals.php">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../about.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../contact.html">Contact</a>
                </li>
            </ul>
            <!-- User Actions -->
            <div class="user-actions d-flex align-items-center ms-lg-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="../logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="../user/login.php" class="btn btn-outline-primary">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                <?php endif; ?>
                <a href="cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2 class="mb-4">Order Payment</h2>
    <div class="card mb-4">
        <div class="card-body">
            <h5>Order #<?= $order['order_id'] ?></h5>
            <p><strong>Status:</strong> <?= htmlspecialchars(ucfirst($order['status'])) ?></p>
            <p><strong>Payment Method:</strong> <?= htmlspecialchars(ucfirst($order['payment_method'])) ?></p>
            <p><strong>Total:</strong> <span class="fw-bold text-primary">R<?= number_format($order['total_price'], 2) ?></span></p>
        </div>
    </div>
    <h4>Products in this Order:</h4>
    <div class="row g-4 mb-4">
        <?php foreach ($order_items as $item): ?>
            <div class="col-md-4">
                <div class="card h-100">
                    <img src="../assets/images/<?= htmlspecialchars($item['image'] ?? 'default.png') ?>" class="card-img-top" alt="<?= htmlspecialchars($item['product_name']) ?>" style="height:150px;object-fit:cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($item['product_name']) ?></h5>
                        <p>Quantity: <?= $item['quantity'] ?></p>
                        <p>Price: R<?= number_format($item['price'], 2) ?></p>
                        <p class="fw-bold">Subtotal: R<?= number_format($item['price'] * $item['quantity'], 2) ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <div class="alert alert-info">
        <strong>Next Step:</strong> Please follow the instructions for your selected payment method to complete your purchase.
    </div>
    <a href="../index.php" class="btn btn-secondary">Back to Home</a>
</div>
</body>
</html>