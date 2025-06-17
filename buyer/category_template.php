<?php
session_start();
require_once __DIR__ . '/../db.php';
$category = $category ?? 'All Products';
$products = $products ?? [];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($category) ?> - Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        .product-card {
            transition: transform 0.2s;
        }
        .product-card:hover {
            transform: translateY(-5px);
        }
        .product-badges {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
        }
    </style>
</head>
<body>
<!-- Navbar (consistent with index.php) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <img src="./../assets/images/marketplace_logo.jpg" alt="Marketplace Logo" style="height:40px;">
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
                    <a class="nav-link" href="../buyer/deals.php">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../about.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../contact.html">Contact</a>
                </li>
            </ul>
            <div class="user-actions d-flex align-items-center ms-lg-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="../../user/logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="../../user/login.php" class="btn btn-outline-primary">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                <?php endif; ?>
                <a href="../../user/cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2 class="mb-4"><?= htmlspecialchars($category) ?></h2>
    <div class="row">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="col-6 col-md-4 col-lg-3 mb-4">
                    <div class="card h-100 border-0 shadow-sm product-card">
                        <div class="position-relative">
                            <a href="../../user/products/product_view.php?id=<?= urlencode($product['product_id'] ?? '') ?>">
                                <img src="../uploads/<?= htmlspecialchars($product['image'] ?? 'default.png') ?>"
                                     class="card-img-top"
                                     alt="<?= htmlspecialchars($product['name'] ?? 'Product') ?>"
                                     style="height: 200px; object-fit: cover;">
                            </a>
                            <div class="product-badges">
                                <?php if (!empty($product['discount_price'])): ?>
                                    <span class="badge bg-success">Sale</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="../../user/products/product_view.php?id=<?= urlencode($product['product_id'] ?? '') ?>" class="text-decoration-none text-dark">
                                    <?= htmlspecialchars($product['name'] ?? 'Product') ?>
                                </a>
                            </h5>
                            <p class="card-text text-primary fw-bold">
                                R<?= htmlspecialchars($product['discount_price'] ?? $product['price'] ?? '0.00') ?>
                            </p>
                            <p class="card-text small text-muted">
                                <i class="fas fa-user me-1"></i> <?= htmlspecialchars($product['seller'] ?? 'seller') ?><br>
                                <i class="fas fa-clock me-1"></i> <?= htmlspecialchars($product['created_at'] ?? '') ?>
                            </p>
                        </div>
                        <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                            <a href="../../products/product_view.php?id=<?= urlencode($product['product_id']) ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                            <form method="post" action="../../admin/products/add_to_cart.php" class="d-inline">
                                <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id'] ?? '') ?>">
                                <button type="submit" class="btn btn-sm btn-primary">
                                    <i class="fas fa-cart-plus"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No products available in this category.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
