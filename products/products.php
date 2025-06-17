<?php
require_once __DIR__ . '/../db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Products</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>
<body>
<!-- Navbar (copied from index.php) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
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
                <a href="../buyer/cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-4">
    <h2 class="mb-4">All Products</h2>
    <div class="row g-4">
        <?php
        // Fetch products
        $query = "SELECT * FROM products LIMIT 12";
        $result = mysqli_query($conn, $query);
        while($product = mysqli_fetch_assoc($result)): ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 shadow-sm position-relative">
                    <?php if (!empty($product['discount_price'])): ?>
                        <span class="badge bg-success position-absolute top-0 start-0 m-2">
                            <?= round(100 * ($product['price'] - $product['discount_price']) / $product['price']) ?>% OFF
                        </span>
                    <?php endif; ?>
                    <img src="../assets/images/<?= htmlspecialchars($product['image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height: 180px; object-fit: cover;">
                    <div class="card-body">
                        <h6 class="card-title" style="min-height: 45px;"><?= htmlspecialchars($product['name']) ?></h6>
                        <p class="text-muted small mb-1"><?= htmlspecialchars($product['description']) ?></p>
                        <div class="mb-2">
                            <?php if (!empty($product['discount_price'])): ?>
                                <strong>R <?= number_format($product['discount_price'], 2) ?></strong>
                                <span class="text-muted text-decoration-line-through small">R <?= number_format($product['price'], 2) ?></span>
                            <?php else: ?>
                                <strong>R <?= number_format($product['price'], 2) ?></strong>
                            <?php endif; ?>
                        </div>
                        <div class="mb-2">
                            <span class="badge bg-secondary"><?= htmlspecialchars($product['location']) ?></span>
                        </div>
                        <div class="d-grid">
                            <a href="product_view.php?id=<?= $product['product_id'] ?>" class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-eye"></i> View Details
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
</div>

<!-- Footer (copied from index.php, adjust as needed) -->
<footer class="bg-light py-4 mt-5">
    <div class="container text-center">
        <p class="mb-0">&copy; <?= date('Y') ?> C2C Marketplace. All rights reserved.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
