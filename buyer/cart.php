<?php
session_start(); // startup session to access user data to see whether they are logged in or not
require_once __DIR__ . '/../db.php';

// Initialize cart if not set
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handles remove action for the cart
if (isset($_GET['action']) && $_GET['action'] === 'remove' && isset($_GET['product_id'])) {
    $remove_id = $_GET['product_id'];
    unset($_SESSION['cart'][$remove_id]);
    header('Location: cart.php');
    exit;
}

$cartItems = [];
$totalPrice = 0.00;

if (!empty($_SESSION['cart'])) {
    $placeholders = implode(',', array_fill(0, count($_SESSION['cart']), '?'));
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute(array_keys($_SESSION['cart']));
    $cartItems = $stmt->fetchAll();

    foreach ($cartItems as &$item) {
        $quantity = $_SESSION['cart'][$item['product_id']];
        $price = $item['discount_price'] ?? $item['price'];
        $item['quantity'] = $quantity;
        $item['subtotal'] = $price * $quantity;
        $totalPrice += $item['subtotal'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Cart</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="/c2c_platform/index.php">
            <img src="../assets/images/marketplace_logo.jpg" alt="Marketplace Logo" style="height:40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../buyer/category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="../sellers/sellers.php">Sell an Item</a></li>
            </ul>
            <div class="user-actions d-flex align-items-center ms-lg-3">
                <a href="cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h2 class="mb-4">Shopping Cart</h2>

    <?php if (empty($cartItems)): ?>
        <div class="text-center p-5 border rounded">
            <p class="lead">You don't have any items in your cart.</p>
            <a href="../index.php" class="btn btn-primary">Start Shopping</a>
        </div>
    <?php else: ?>
        <div class="row">
            <div class="col-lg-8">
                <?php foreach ($cartItems as $item): ?>
                    <div class="card mb-3">
                        <div class="row g-0 align-items-center">
                            <div class="col-md-3">
                                <img src="<?= htmlspecialchars($item['image'] ?? 'placeholder.jpg') ?>" alt="Product Image" class="img-fluid p-2">
                            </div>
                            <div class="col-md-6">
                                <div class="card-body">
                                    <h5 class="card-title mb-1"><?= htmlspecialchars($item['name']) ?></h5>
                                    <p class="mb-1">Quantity: <?= $item['quantity'] ?></p>
                                    <p class="mb-1">Price: R<?= number_format($item['discount_price'] ?? $item['price'], 2) ?></p>
                                    <p class="mb-1 text-muted">Subtotal: R<?= number_format($item['subtotal'], 2) ?></p>
                                </div>
                            </div>
                            <div class="col-md-3 text-end pe-3">
                                <a href="cart.php?action=remove&product_id=<?= $item['product_id'] ?>" class="btn btn-outline-danger mt-3">Remove</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="col-lg-4">
                <div class="card border-dark">
                    <div class="card-body">
                        <h5 class="card-title">Summary</h5>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal</span>
                            <span>R<?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>Shipping</span>
                            <span>R0.00</span>
                        </div>
                        <hr>
                        <div class="d-flex justify-content-between fw-bold">
                            <span>Total</span>
                            <span>R<?= number_format($totalPrice, 2) ?></span>
                        </div>
                        <a href="../cart/checkout.php" class="btn btn-primary w-100 mt-3">Go to Checkout</a>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
