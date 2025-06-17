<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../../user/login.php?error=Please log in to list an item');
    exit;
}

require_once '../db.php';

// Fetch categories for the form
$cat_stmt = $pdo->query("SELECT category_id, name FROM categories ORDER BY name ASC");
$categories = $cat_stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sell Your Item</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="assets\css\sellers.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
            min-height: 100vh;
        }
        .category-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }
        .category-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
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
                    <a class="nav-link" href="deals.php">Deals</a>
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
                    <a href="../logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="../login.php" class="btn btn-outline-primary">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                <?php endif; ?>
                <a href="../cart.php" class="btn btn-outline-dark position-relative ms-2">
                    <i class="fas fa-shopping-cart"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                        0
                    </span>
                </a>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content (no extra containers) -->
<main class="py-5">
    <h2 class="mt-4 text-center text-3xl fw-bold text-dark">
        What are you planning on selling?
    </h2>
    <p class="mt-2 text-center text-muted">
        Select the most relevant category to get started.
    </p>

    <form id="listing-form" action="process_listing.php" method="POST" enctype="multipart/form-data">
        <div class="row g-3 mb-4">
            <?php
            // Map category names to Font Awesome icons
            $categoryIcons = [
                'Electronics' => 'fa-tv',
                'Furniture' => 'fa-couch',
                'Musical Instruments' => 'fa-guitar',
                'Cars & Motorcycles' => 'fa-car',
                'Home and Garden' => 'fa-home',
                'Books' => 'fa-book',
                'Toys' => 'fa-puzzle-piece',
                'Fashion' => 'fa-tshirt'
            ];
            foreach ($categories as $cat):
                $icon = isset($categoryIcons[$cat['name']]) ? $categoryIcons[$cat['name']] : 'fa-box';
            ?>
                <div class="col-6 col-md-4 col-lg-2">
                    <label for="category-<?= $cat['category_id'] ?>" class="category-card d-flex flex-column align-items-center justify-content-center p-3 rounded border">
                        <input type="radio" id="category-<?= $cat['category_id'] ?>" name="category" value="<?= $cat['category_id'] ?>" class="d-none" required>
                        <i class="fas <?= $icon ?> fa-2x mb-2"></i>
                        <span class="text-sm text-center"><?= htmlspecialchars($cat['name']) ?></span>
                    </label>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mb-3">
            <label for="listing-title" class="form-label">Listing title</label>
            <input id="listing-title" name="listing_title" type="text" required class="form-control" placeholder="What are you selling?" maxlength="100">
            <div class="form-text text-end">0/100</div>
        </div>


        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea id="description" name="description" rows="6" class="form-control" placeholder="Add any additional information here that might be useful to the buyer." maxlength="3000"></textarea>
            <div class="form-text text-end">0/3000</div>
        </div>
        
        <div class="mb-3">
    <label for="location" class="form-label">Location</label>
    <select id="location" name="location" class="form-select" required>
        <option value="">Select location</option>
        <option value="Gauteng">Gauteng</option>
        <option value="Western Cape">Western Cape</option>
        <option value="KwaZulu-Natal">KwaZulu-Natal</option>
        <option value="Eastern Cape">Eastern Cape</option>
        <!-- Add more as needed -->
    </select>
</div>

<div class="mb-3">
    <label for="mobile" class="form-label">Mobile number</label>
    <input type="tel" id="mobile" name="mobile" class="form-control" placeholder="084 123 4567" pattern="[0-9]{3} [0-9]{3} [0-9]{4}" required>
    <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" id="show_number" name="show_number" checked>
        <label class="form-check-label" for="show_number">
            Show this number on my ad
        </label>
    </div>
</div>

<div class="mb-4">
    <label class="form-label d-block">Photos <small class="text-muted ms-1">(Optional)</small></label>
    <p class="text-muted mb-2 small">
        Ads with more photos get more views and sell faster.
        <em>(hold & drag to re-arrange)</em><br>
        Upload JPEG, JPG, PNG, BMP, WEBP, HEIC or AVIF images (minimum 320×320 pixels, maximum 10MB).
    </p>
    <input type="file" name="photos[]" accept=".jpeg,.jpg,.png,.bmp,.webp,.heic,.avif" multiple class="form-control">
</div>




        <div class="pt-3">
            <button type="submit" class="btn btn-primary w-100">
                Submit
            </button>
        </div>
    </form>
</main>
</body>
</html>
