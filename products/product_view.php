<?php
session_start(); // startup session to access user data to see whther they are logged in or not
require_once __DIR__ . '/../db.php';
$product_id = $_GET['id'] ?? null;

if (!$product_id) {
    echo "Product not found.";
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

if (!$product) {
    echo "Product not found.";
    exit;
}

// Fetch all images for this product
$img_stmt = $pdo->prepare("SELECT image FROM product_images WHERE product_id = ?");
$img_stmt->execute([$product_id]);
$product_images = $img_stmt->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($product['name']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .product-img { max-width: 100%; border: 1px solid #ccc; border-radius: 5px; }
        .price-original { text-decoration: line-through; color: grey; font-size: 14px; }
        .price-discount { color: green; font-size: 24px; font-weight: bold; }
        .sale-tag { position: absolute; top: 10px; left: 10px; background-color: #007bff; color: #fff; padding: 5px; font-size: 12px; }
    </style>
</head>
<body>
<!-- Navbar (consistent with index.php) -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="../../index.php">
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
                    <a class="nav-link" href="../buyer/deals.php">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../about.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../contact.html">Contact</a>
                </li>
            </ul>
            <!-- User Actions -->
            <div class="user-actions d-flex align-items-center ms-lg-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="../logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="../login.php" class="btn btn-outline-primary">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                <?php endif; ?>
                <?php
                $cart_count = 0;
                if (isset($_SESSION['cart'])) {
                    $cart_count = array_sum($_SESSION['cart']);
                }
                ?>
                <a href="../buyer/cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        <?= $cart_count ?>
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="row">
        <!-- Image & thumbnails -->
        <div class="col-md-6 position-relative">
            <div class="sale-tag">SALE</div>
            <!-- Main Image -->
            <img src="/c2c_platform/assets/images/<?= htmlspecialchars($product_images[0] ?? $product['image']) ?>" class="product-img mb-2" alt="<?= htmlspecialchars($product['name']) ?>">

            <!-- Thumbnails -->
            <div class="d-flex gap-2">
                <?php foreach ($product_images as $img): ?>
                    <img src="uploads/<?= htmlspecialchars($img) ?>" class="img-thumbnail" width="80">
                <?php endforeach; ?>
                <?php if (count($product_images) > 4): ?>
                    <div class="img-thumbnail text-center" style="width:80px; height:80px; line-height:60px; font-size:20px; font-weight:bold;">
                        +<?= count($product_images) - 4 ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Product details -->
        <div class="col-md-6">
            <h3><?= htmlspecialchars($product['name']) ?></h3>
            <p class="text-muted"><?= htmlspecialchars($product['brand'] ?? 'Brand Name') ?></p>
            <p><?= nl2br(htmlspecialchars($product['description'])) ?></p>

            <p>
                <?php if (!empty($product['discount_price'])): ?>
                    <span class="price-discount">R <?= number_format($product['discount_price'], 2) ?></span>
                    <span class="price-original">R <?= number_format($product['price'], 2) ?></span>
                <?php else: ?>
                    <span class="price-discount">R <?= number_format($product['price'], 2) ?></span>
                <?php endif; ?>
            </p>

            <p>
                <strong>In Stock</strong> <span class="text-success">(Available for delivery)</span>
            </p>

            <ul>
                <li>Free next-day delivery available</li>
                <li>Free returns within 30 days</li>
                <li>12-month limited warranty</li>
            </ul>

            <form action="../cart/add_to_cart.php" method="post" class="d-flex gap-2">
                <input type="hidden" name="product_id" value="<?= $product['product_id'] ?>">
                <input type="number" name="quantity" value="1" min="1" class="form-control w-25">
                <button type="submit" class="btn btn-success">Add to Cart</button>
            </form>
        </div>
    </div>
</div>

<!-- Product listing section (newly added) -->
<div class="container py-4">
    <h2>Related Products</h2>
    <div class="row">
        <?php foreach ($products as $product): ?>
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <!-- Product Image -->
                    <img src="/c2c_platform/assets/images/<?= htmlspecialchars($product['main_image'] ?? 'default.jpg') ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($product['name']) ?>" 
                         style="height: 180px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                        <p class="card-text">
                            <?php if (!empty($product['discount_price'])): ?>
                                <span class="price-discount">R <?= number_format($product['discount_price'], 2) ?></span>
                                <span class="price-original">R <?= number_format($product['price'], 2) ?></span>
                            <?php else: ?>
                                <span class="price-discount">R <?= number_format($product['price'], 2) ?></span>
                            <?php endif; ?>
                        </p>
                        <a href="product.php?id=<?= $product['product_id'] ?>" class="btn btn-primary">View Details</a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
