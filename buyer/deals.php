<?php
session_start();
require_once __DIR__ . '/../db.php';

try {
    $stmt = $pdo->query("SELECT product_id, name, price, discount_price, image FROM products WHERE discount_price IS NOT NULL ORDER BY RAND() LIMIT 12");
    $deals = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Error fetching deals: " . $e->getMessage());
}


function getRandomSeller() {
    return 'seller' . rand(100, 999);
}

function getRandomDate() {
    return date('d M Y', strtotime('-' . rand(1, 10) . ' days'));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Featured Deals</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        .card-img-top {
            height: 200px;
            object-fit: cover;
        }
        .product-badges {
            position: absolute;
            top: 10px;
            left: 10px;
        }
        .product-badges .badge {
            margin-right: 4px;
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

<div class="container mt-5">
    <h3 class="text-center mb-4 text-primary">FEATURED DEALS</h3>

    <div class="row g-4">
        <?php foreach ($deals as $deal): ?>
            <?php
                $id = $deal['product_id'];
                $name = htmlspecialchars($deal['name']);
                $image = htmlspecialchars($deal['image']);
                $price = number_format($deal['discount_price'], 2);
                $seller = getRandomSeller();
                $date = getRandomDate();
            ?>
            <div class="col-6 col-md-4 col-lg-3">
                <div class="card h-100 border-0 shadow-sm product-card">
                    <div class="position-relative">
                        <a href="../products/product_view.php?id=<?php echo $id; ?>">
                            <img src="../assets/images/<?php echo $image; ?>" class="card-img-top" alt="<?php echo $name; ?>">
                        </a>
                        <div class="product-badges">
                            <span class="badge bg-success">New</span>
                            <span class="badge bg-info">Brand New</span>
                        </div>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="product_view.php?id=<?php echo $id; ?>" class="text-decoration-none text-dark">
                                <?php echo $name; ?>
                            </a>
                        </h5>
                        <p class="card-text text-primary fw-bold">R<?php echo $price; ?></p>
                        <p class="card-text small text-muted">
                            <i class="fas fa-user me-1"></i> <?php echo $seller; ?><br>
                            <i class="fas fa-clock me-1"></i> <?php echo $date; ?>
                        </p>
                    </div>
                    <div class="card-footer bg-white border-top-0 d-flex justify-content-between">
                        <a href="../products/product_view.php?id=<?php echo $id; ?>" class="btn btn-sm btn-outline-primary">View Details</a>
                        <form method="post" action="../../admin/products/add_to_cart.php" class="d-inline">
                            <input type="hidden" name="product_id" value="<?php echo $id; ?>">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-cart-plus"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <?php if (count($deals) === 0): ?>
            <p class="text-center">No featured deals available.</p>
        <?php endif; ?>
    </div>
</div>
</body>
</html>
