<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Become a Seller - C2C Marketplace</title>
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
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="../user/logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="../user/login.php" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                <?php endif; ?>
                <a href="/buyer/cart.php" class="btn btn-outline-dark position-relative ms-2">
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
    <h2 class="mb-4">How to Become a Seller</h2>
    <ol class="list-group list-group-numbered mb-4">
        <li class="list-group-item">
            <strong>Register or Login:</strong>  
            Make sure you are logged in to your account. If you don’t have one, <a href="../user/register.php">register here</a>.
        </li>
        <li class="list-group-item">
            <strong>Request Seller Access:</strong>  
            Go to your account/profile page and look for the option to “Become a Seller” or “Upgrade to Seller”.  
            <br>If you see a button or link, click it and follow the instructions.
        </li>
        <li class="list-group-item">
            <strong>Wait for Approval:</strong>  
            Your request will be reviewed by an admin. You may need to provide additional information.
            <br>You will be notified by email or on your dashboard when your seller account is approved.
        </li>
        <li class="list-group-item">
            <strong>Start Selling:</strong>  
            Once approved, you can click “Sell an Item” and fill in your product details to list your first product!
        </li>
    </ol>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i>
        If you have any questions or need help, please <a href="../contact.html">contact support</a>.
    </div>
</div>
</body>
</html>