<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Home | Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        /* Custom styles for the navigation bar */
        .navbar {
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .navbar-brand img {
            height: 40px;
        }
        .nav-link {
            font-weight: 500;
            color: #495057;
            padding: 0.5rem 1rem !important;
            transition: color 0.3s ease;
        }
        .nav-link:hover {
            color: #007bff;
        }
        .navbar-nav .active {
            color: #007bff;
            border-bottom: 2px solid #007bff;
        }
        .search-form {
            width: 100%;
            max-width: 400px;
        }
        .user-actions .btn {
            margin-left: 0.5rem;
        }
        @media (max-width: 991.98px) {
            .search-form {
                max-width: 100%;
                margin: 1rem 0;
            }
            .user-actions {
                margin-top: 1rem;
            }
        }
    </style>
</head>

<body>
<?php session_start(); ?>
<?php
require_once 'db.php';

// Fetch products with their main image
$stmt = $pdo->query("
    SELECT p.*, pi.image 
    FROM products p
    LEFT JOIN product_images pi ON p.product_id = pi.product_id
    GROUP BY p.product_id
");
$products = $stmt->fetchAll();
?>

<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container"> 
        <!-- Logo -->
        <a class="navbar-brand" href="./index.php">
            <img src="./assets/images/marketplace_logo.jpg" alt="Marketplace Logo">
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links and Search -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="./index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./buyer/category.php">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="./buyer/deals.php">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
            </ul>
            
            <!-- Search Form -->
            <form class="search-form d-flex" action="search.php" method="GET">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search products..." aria-label="Search">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            
            <!-- User Actions -->
           
<div class="user-actions d-flex align-items-center ms-auto">
    <?php if (isset($_SESSION['user'])): ?>
        <a href="./user/account.php" class="btn btn-outline-secondary ms-2" title="My Account">
            <i class="fas fa-user-circle"></i>
        </a>
        <span class="me-2 ms-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
        <a href="./user/logout.php" class="btn btn-outline-danger ms-2">Logout</a>
    <?php else: ?>
        <a href="./user/login.php" class="btn btn-outline-primary ms-2">
            <i class="fas fa-user me-1"></i> Login
        </a>
    <?php endif; ?>
    <a href="./buyer/cart.php" class="btn btn-outline-dark position-relative ms-2">
        <i class="fas fa-shopping-cart"></i>
        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
            <?= isset($_SESSION['cart']) ? array_sum($_SESSION['cart']) : 0 ?>
        </span>
    </a>
</div>

            <!-- Seller Link -->
            <?php
$sellLink = "user/login.php"; // Default for guests
if (isset($_SESSION['user'])) {
    if ($_SESSION['user']['role'] === 'seller') {
        $sellLink = "sellers/sellers.php";
    } else {
        $sellLink = "sellers/become_seller.php";
    }
}
?>
        </div>
    </div>
</nav>

<!-- Hero Section -->
<section class="hero-section py-5 bg-light">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">Buy and Sell with Confidence</h1>
                <p class="lead mb-4">South Africa's premier customer-to-customer marketplace.</p>
                <div class="d-grid gap-2 d-md-flex justify-content-md-start">
                    <a href="./buyer/category.php" class="btn btn-primary btn-lg px-4 me-md-2">Shop Now</a>
                    <a href="<?= $sellLink ?>" class="nav-link">Sell an Item</a>
                </div>
            </div>
            <div class="col-lg-6">
                <img src="./assets/images/hero-image.webp" class="img-fluid rounded" alt="Hero Image">
            </div>
        </div>
    </div>
</section>

<!-- Popular Categories -->
<section class="py-5">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold">Popular Categories</h2>
                <p class="text-muted">Explore our most popular product categories</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="./buyer/category.php" class="btn btn-outline-primary">View All Categories</a>
            </div>
        </div>

        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-2"> <!--This line defines the column size based on screen size-->
                <a href="./products/product_pages/electronics.php" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="./assets/images/electronics_image.png" class="img-fluid" alt="Electronics" style="height: 80px;">
                            </div>
                            <h5 class="card-title">Electronics</h5>
                            <p class="text-muted small">120 products</p>
                        </div>
                    </div>
                </a>
            </div>
            <!-- Repeat for more categories -->

            <!-- Card 2 -->
            <div class="col-6 col-md-4 col-lg-2">
                <a href="./products/product_pages/furniture.php" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="./assets/images/furniture_image.jpg" class="img-fluid" alt="Furniture" style="height: 80px;">
                            </div>
                            <h5 class="card-title">Furniture</h5>
                            <p class="text-muted small">80 products</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 3 -->
            <!-- New Card (added horizontally) -->
            <div class="col-6 col-md-4 col-lg-2">
                <a href="./products/product_pages/musical_instruments.php" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="./assets/images/musical_instruments.webp" class="img-fluid" alt="Furniture" style="height: 80px;">
                            </div>
                            <h5 class="card-title">Musical Instruments</h5>
                            <p class="text-muted small">45 products</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 4 -->
             <!-- New Card (added horizontally) -->
            <div class="col-6 col-md-4 col-lg-2">
                <a href="./products/product_pages/cars_motorcycles.php" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="./assets/images/cars_and_bikes.jpg" class="img-fluid" alt="Furniture" style="height: 80px;">
                            </div>
                            <h5 class="card-title">Cars & Motorcycles</h5>
                            <p class="text-muted small">122 products</p>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Card 5 -->
             <!-- New Card (added horizontally) -->
            <div class="col-6 col-md-4 col-lg-2">
                <a href="./products/product_pages/home_and_garden.php" class="text-decoration-none">
                    <div class="card h-100 border-0 shadow-sm">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <img src="./assets/images/home_and_garden.jpg" class="img-fluid" alt="Furniture" style="height: 80px;">
                            </div>
                            <h5 class="card-title">Home and Garden</h5>
                            <p class="text-muted small">50 products</p>
                        </div>
                    </div>
                </a>
            </div>


            
        </div>
    </div>
</section>

<!-- Latest Products -->
<section class="py-5 bg-light">
    <div class="container">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2 class="fw-bold">Latest Products</h2>
                <p class="text-muted">Check out the most recent items on our marketplace</p>
            </div>
            <div class="col-md-4 text-end">
                <a href="./products/products.php" class="btn btn-outline-primary">View All Products</a>
            </div>
        </div>

        <!-- Only ONE row for all product cards -->
        <div class="row g-4">
            <div class="col-6 col-md-4 col-lg-3">
                <!-- Product Card 1 -->
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <a href="/products/product_view.php">
                            <img src="./assets/images/Asus_Tuff_Laptop.jpg" class="card-img-top" alt="Laptop" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="product-badges">
                            <span class="badge bg-success">New</span>
                            <span class="badge bg-info">Brand New</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="./products/product_view.php?id=20" class="text-decoration-none text-dark">Asus Tuff Laptop</a>
                        </h5>
                        <p class="card-text text-primary fw-bold">R8,999.00</p>
                        <p class="card-text small text-muted">
                            <i class="fas fa-user me-1"></i> seller123<br>
                            <i class="fas fa-clock me-1"></i> 11 May 2025
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <a href="./products/product_view.php?id=20" class="btn btn-sm btn-outline-primary">View Details</a>
                        <form method="post" action="admin/products/add_to_cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="20"><!-- Change 1 to the actual product ID -->
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <!-- Product Card 2 -->
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <a href="./products/product_view.php?id=21">
                            <img src="./assets/images/Electric_Kettle.jpg" class="card-img-top" alt="Laptop" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="product-badges">
                            <span class="badge bg-success">New</span>
                            <span class="badge bg-info">Brand New</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="./products/product_view.php?id=21" class="text-decoration-none text-dark">Electric Kettle - Stainless Steel</a>
                        </h5>
                        <p class="card-text text-primary fw-bold">R150.00</p>
                        <p class="card-text small text-muted">
                            <i class="fas fa-user me-1"></i> seller456<br>
                            <i class="fas fa-clock me-1"></i> 10 May 2025
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <a href="./products/product_view.php?id=21" class="btn btn-sm btn-outline-primary">View Details</a>
                        <form method="post" action="admin/products/add_to_cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="21"><!-- Change 1 to the actual product ID -->
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <!-- Product Card 3 -->
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <a href="./products/product_view.php?id=22">
                            <img src="./assets/images/camping_chair.jpg" class="card-img-top" alt="Laptop" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="product-badges">
                            <span class="badge bg-success">New</span>
                            <span class="badge bg-info">Brand New</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="./products/product_view.php?id=22" class="text-decoration-none text-dark">Camping Tri-leg Chair</a>
                        </h5>
                        <p class="card-text text-primary fw-bold">R200.00</p>
                        <p class="card-text small text-muted">
                            <i class="fas fa-user me-1"></i> seller789<br>
                            <i class="fas fa-clock me-1"></i> 09 May 2025
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <a href="./products/product_view.php?id=22" class="btn btn-sm btn-outline-primary">View Details</a>
                        <form method="post" action="admin/products/add_to_cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="22"><!-- Change 1 to the actual product ID -->
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-6 col-md-4 col-lg-3">
                <!-- Product Card 4 -->
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <a href="./products/product_view.php?id=23">
                            <img src="./assets/images/Honda_Brio.jpg" class="card-img-top" alt="Laptop" style="height: 200px; object-fit: cover;">
                        </a>
                        <div class="product-badges">
                            <span class="badge bg-success">New</span>
                            <span class="badge bg-info">Brand New</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="./user/products/product_view.php?id=23" class="text-decoration-none text-dark">2014 Honda Brio 1.2ltr</a>
                        </h5>
                        <p class="card-text text-primary fw-bold">R69,999.00</p>
                        <p class="card-text small text-muted">
                            <i class="fas fa-user me-1"></i> seller101<br>
                            <i class="fas fa-clock me-1"></i> 08 May 2025
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <a href="./user/products/product_view.php?id=23" class="btn btn-sm btn-outline-primary">View Details</a>
                        <form method="post" action="./cart/add_to_cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="23"><!-- Change 1 to the actual product ID -->
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- How It Works -->
<section class="py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">How It Works</h2>
        <p class="text-muted">Simple steps to start buying and selling on our platform</p>
        <div class="row g-4">
            <div class="col-md-3">
                <div class="card h-100 border-0 shadow-sm text-center">
                    <div class="card-body">
                        <div class="rounded-circle bg-primary text-white mx-auto mb-4 d-flex align-items-center justify-content-center" style="width: 80px; height: 80px;">
                            <i class="fas fa-user-plus fa-2x"></i>
                        </div>
                        <h4>1. Create Account</h4>
                        <p class="text-muted">Register for free and set up your profile</p>
                    </div>
                </div>
            </div>
            <!-- Steps 2–4 similar -->
        </div>
        <div class="mt-4">
            <a href="./user/register.php" class="btn btn-primary btn-lg">Get Started Now</a>
        </div>
    </div>
</section>

<!-- Testimonials -->
<section class="py-5 bg-light">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Customer Testimonials</h2>
        <p class="text-muted">See what our users have to say about us</p>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="card h-100 border-0 shadow-sm">
                    <div class="card-body">
                        <div class="mb-3 text-warning">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i><i class="fas fa-star"></i>
                            <i class="fas fa-star"></i>
                        </div>
                        <p>"Easy to use and efficient! I sold my furniture quickly."</p>
                        <div class="d-flex align-items-center mt-4">
                            <img src="assets/images/testimonials/user1.jpg" class="rounded-circle" width="50" alt="Sarah">
                            <div class="ms-3">
                                <h5 class="mb-0">Sarah Johnson</h5>
                                <p class="text-muted small mb-0">Cape Town</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- More testimonials -->
        </div>
    </div>
</section>

<!-- App Banner -->
<section class="py-5 bg-primary text-white">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-7">
                <h2 class="fw-bold mb-3">Download Our Mobile App</h2>
                <p class="lead mb-4">Buy, sell, and chat on the go with our easy-to-use mobile application.</p>
                <div class="d-flex gap-3">
                    <a href="#" class="btn btn-light"><i class="fab fa-google-play me-2"></i> Google Play</a>
                    <a href="#" class="btn btn-light"><i class="fab fa-apple me-2"></i> App Store</a>
                </div>
            </div>
            <div class="col-md-5 text-center">
                <img src="assets/images/banner/app-mockup.png" class="img-fluid" alt="App Mockup">
            </div>
        </div>
    </div>
</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/index.js"></script>
</body>
</html>