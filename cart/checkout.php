<?php
session_start(); // startup session to access user data to see whether they are logged in or not
require_once __DIR__ . '/../db.php';

if (!isset($_SESSION['user']) || empty($_SESSION['cart'])) {
    header('Location: ../buyer/cart.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['payment_method'])) {
    $user_id = $_SESSION['user']['user_id'];
    $cart = $_SESSION['cart'];
    $payment_method = $_POST['payment_method'];

    // Calculate total price
    $placeholders = implode(',', array_fill(0, count($cart), '?'));
    $stmt = $pdo->prepare("SELECT product_id, price FROM products WHERE product_id IN ($placeholders)");
    $stmt->execute(array_keys($cart));
    $products = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $total_price = 0;
    foreach ($cart as $product_id => $qty) {
        $total_price += ($products[$product_id] ?? 0) * $qty;
    }

    // Create order
    $stmt = $pdo->prepare("INSERT INTO orders (user_id, total_price, payment_method, status, created_at) VALUES (?, ?, ?, 'pending', NOW())");
    $stmt->execute([$user_id, $total_price, $payment_method]);
    $order_id = $pdo->lastInsertId();

    // Insert order items
    $stmt_item = $pdo->prepare("INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)");
    foreach ($cart as $product_id => $qty) {
        $stmt_item->execute([$order_id, $product_id, $qty]);
    }

    // Redirect to payment page with order_id
    header("Location: ../orders/payment.php?order_id=$order_id");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Checkout</title>
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
    <h2>Checkout</h2>
    <form action="checkout.php" method="post" class="mb-4">
        <div class="mb-3">
            <!-- payment method selection -->
            <label for="payment_method" class="form-label">Select Payment Method:</label>
            <select name="payment_method" id="payment_method" class="form-select" required>
                <option value="">-- Choose --</option>
                <option value="credit_card">Credit Card</option>
                <option value="eft">EFT (Bank Transfer)</option>
                <option value="cod">Cash on Delivery</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success">Proceed to Payment</button>
        <a href="../buyer/cart.php" class="btn btn-secondary ms-2">Back to Cart</a>
    </form>
</div>
</body>
</html>