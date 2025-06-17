<?php
session_start();
require_once __DIR__ . '/../db.php'; // Go up one directory to 'user', then include 'db.php'

// Fetch all categories
try {
    $stmt = $pdo->query("SELECT category_id, category_name FROM categories ORDER BY category_name ASC");
    $categories = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching categories: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Categories - C2C Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .category-card {
            transition: transform 0.2s;
        }
        .category-card:hover {
            transform: scale(1.02);
        }
    </style>
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

<div class="container my-5">
    <h2 class="mb-4">Product Categories</h2>
    <div class="row">
        <?php if ($categories): ?>
            <?php foreach ($categories as $category): ?>
                <?php
                    $categoryFile = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $category['category_name']))) . '.php';
                    $categoryLink = '../products/product_pages/' . $categoryFile;
                ?>
                <div class="col-md-4 mb-4">
                    <a href="<?= $categoryLink ?>" class="text-decoration-none">
                        <div class="card category-card shadow-sm">
                            <div class="card-body text-center">
                                <h5 class="card-title"><?= htmlspecialchars($category['category_name']) ?></h5>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No categories found.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Popular Categories Section in category.php -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold">Popular Categories</h2>
                <p class="text-muted">Explore our most popular product categories</p>
            </div>
        </div>
        <div class="row g-4">
            <?php
            // Map category_id or category_name to image filenames
            $categoryImages = [
                1 => 'electronics_image.png',
                2 => 'furniture_image.jpg',
                3 => 'musical_instruments.webp',
                4 => 'cars_and_bikes.jpg',
                5 => 'home_and_garden.jpg',
                6 => 'books.jpg',
                7 => 'toys.jpg',
                8 => 'fashion.jpg',
                // Add more mappings as needed
            ];
            foreach ($categories as $cat):
                $img = isset($categoryImages[$cat['category_id']]) ? $categoryImages[$cat['category_id']] : 'default_category.png';
                $categoryFile = preg_replace('/[^a-z0-9_]/', '', strtolower(str_replace(' ', '_', $cat['category_name']))) . '.php';
                $categoryLink = '../products/product_pages/' . $categoryFile;
            ?>
            <div class="col-6 col-md-4 col-lg-2">
                <a href="<?= $categoryLink ?>" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="../assets/images/<?= htmlspecialchars($img) ?>" class="img-fluid" alt="<?= htmlspecialchars($cat['category_name']) ?>" style="height: 80px;">
                            </div>
                            <h5 class="card-title"><?= htmlspecialchars($cat['category_name']) ?></h5>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

</body>
</html>
