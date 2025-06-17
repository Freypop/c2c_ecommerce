<?php
// filepath: c:\xampp\htdocs\c2c_platform\account.php
session_start();
require_once '../db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: user/login.php');
    exit;
}

// Handle become seller request
$becomeSellerMsg = '';
if (isset($_POST['become_seller']) && $_SESSION['user']['role'] === 'buyer') {
    $stmt = $pdo->prepare("UPDATE users SET role = 'seller', is_verified = 0 WHERE user_id = ?");
    $stmt->execute([$_SESSION['user']['user_id']]);
    $_SESSION['user']['role'] = 'seller';
    $_SESSION['user']['is_verified'] = 0;
    $becomeSellerMsg = '<div class="alert alert-success mt-3">Your request to become a seller has been submitted! Please wait for admin approval.</div>';
}

// Fetch latest user info
$stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
$stmt->execute([$_SESSION['user']['user_id']]);
$user = $stmt->fetch();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Account - C2C Marketplace</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="../assets/images/marketplace_logo.jpg" alt="Marketplace Logo" style="height:40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="../buyer/category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="../buyer/deals.php">Deals</a></li>
                <li class="nav-item"><a class="nav-link" href="../about.html">About</a></li>
                <li class="nav-item"><a class="nav-link" href="../contact.html">Contact</a></li>
            </ul>
            <div class="user-actions d-flex align-items-center ms-auto">
                <span class="me-2">Hello, <?= htmlspecialchars($user['username']) ?></span>
                <a href="../user/logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <a href="../buyer/cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-5">
    <h2>My Account</h2>
    <div class="card mb-4" style="max-width: 500px;">
        <div class="card-body">
            <p><strong>Username:</strong> <?= htmlspecialchars($user['username']) ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
            <p><strong>Role:</strong> <?= htmlspecialchars(ucfirst($user['role'])) ?></p>
            <?php if ($user['role'] === 'seller'): ?>
                <p><strong>Seller Status:</strong>
                    <?= $user['is_verified'] ? '<span class="text-success">Verified</span>' : '<span class="text-warning">Pending Approval</span>' ?>
                </p>
            <?php endif; ?>
        </div>
    </div>

    <?php if ($becomeSellerMsg) echo $becomeSellerMsg; ?>

    <?php if ($user['role'] === 'buyer'): ?>
        <form method="post">
            <button type="submit" name="become_seller" class="btn btn-primary">
                <i class="fas fa-store"></i> Become a Seller
            </button>
        </form>
    <?php elseif ($user['role'] === 'seller' && !$user['is_verified']): ?>
        <div class="alert alert-info">Your seller account is pending admin approval.</div>
    <?php elseif ($user['role'] === 'seller' && $user['is_verified']): ?>
        <a href="../sellers/sellers.php" class="btn btn-success">Go to Seller Dashboard</a>
    <?php endif; ?>
</div>
</body>
</html>