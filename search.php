<?php
session_start();
require_once 'db.php';

$q = trim($_GET['q'] ?? '');

$results = [];
if ($q !== '') {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE name LIKE ? OR description LIKE ?");
    $stmt->execute(['%' . $q . '%', '%' . $q . '%']);
    $results = $stmt->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Search Results for "<?= htmlspecialchars($q) ?>"</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3 shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="index.php">
            <img src="assets/images/marketplace_logo.jpg" alt="Marketplace Logo" style="height:40px;">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="buyer/category.php">Categories</a></li>
                <li class="nav-item"><a class="nav-link" href="buyer/deals.php">Deals</a></li>
                <li class="nav-item"><a class="nav-link" href="about.html">About</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.html">Contact</a></li>
            </ul>
            <form class="search-form d-flex" action="search.php" method="GET" style="max-width:400px;">
                <div class="input-group">
                    <input class="form-control" type="search" name="q" placeholder="Search products..." aria-label="Search" value="<?= htmlspecialchars($q) ?>">
                    <button class="btn btn-primary" type="submit">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
            </form>
            <div class="user-actions d-flex align-items-center ms-auto">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="user/login.php" class="btn btn-outline-primary ms-2">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                <?php endif; ?>
                <a href="buyer/cart.php" class="btn btn-outline-dark position-relative ms-2">
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
    <h2>Search Results for "<?= htmlspecialchars($q) ?>"</h2>
    <?php if ($q === ''): ?>
        <div class="alert alert-warning">Please enter a search term.</div>
    <?php elseif (empty($results)): ?>
        <div class="alert alert-info">No products found.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($results as $product): ?>
                <div class="col-md-4">
                    <div class="card h-100">
                        <img src="assets/images/<?= htmlspecialchars($product['image'] ?? 'default.png') ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']) ?>" style="height:200px;object-fit:cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($product['name']) ?></h5>
                            <p class="card-text"><?= htmlspecialchars($product['description']) ?></p>
                            <p class="fw-bold text-primary">R<?= number_format($product['price'], 2) ?></p>
                            <a href="products/product_view.php?id=<?= $product['product_id'] ?>" class="btn btn-outline-primary btn-sm">View Details</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
