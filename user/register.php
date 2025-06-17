<?php
session_start();
require '../db.php';

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $first_name = trim($_POST['first_name'] ?? '');
    $last_name = trim($_POST['last_name'] ?? '');

    // Validation
    if (empty($username) || empty($email) || empty($password) || empty($confirm_password) || empty($first_name) || empty($last_name)) {
        $error = 'All fields are required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Invalid email format.';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        try {
            // Check if email or username already exists
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR username = ?");
            $stmt->execute([$email, $username]);
            if ($stmt->fetch()) {
                $error = 'Email or username already exists.';
            } else {
                $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password, first_name, last_name, role, is_verified) VALUES (?, ?, ?, ?, ?, 'buyer', TRUE)");
                $stmt->execute([$username, $email, $hashedPassword, $first_name, $last_name]);
                $success = 'Registration successful. You can now <a href="login.php">login</a>.';
            }
        } catch (PDOException $e) {
            $error = 'Database error: ' . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register - C2C Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .register-container {
            max-width: 500px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        .brand {
            font-weight: bold;
            font-size: 24px;
            color: #0d6efd;
        }
    </style>
</head>
<body>
<!-- Navigation Bar -->
<nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
    <div class="container">
        <!-- Logo -->
        <a class="navbar-brand" href="../index.php">
            <img src="../assets/images/marketplace_logo.jpg" alt="Marketplace Logo" style="height:40px;">
        </a>
        
        <!-- Mobile Toggle Button -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        
        <!-- Navigation Links and Search -->
        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link active" href="../index.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../buyer/category.php">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../buyer/deals.php">Deals</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="about.html">About</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="contact.html">Contact</a>
                </li>
            </ul>
            
        
            <!-- User Actions -->
            <div class="user-actions d-flex align-items-center ms-lg-3">
                <?php if (isset($_SESSION['user'])): ?>
                    <span class="me-2">Hello, <?= htmlspecialchars($_SESSION['user']['username']) ?></span>
                    <a href="user/logout.php" class="btn btn-outline-danger ms-2">Logout</a>
                <?php else: ?>
                    <a href="../user/login.php" class="btn btn-outline-primary me-2">
                        <i class="fas fa-user me-1"></i> Login
                    </a>
                   
                <?php endif; ?>
                
            </div>
        </div>
    </div>
</nav>

<div class="register-container">
    <h3 class="mb-3 text-center">Register</h3>

    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php elseif (!empty($success)): ?>
        <div class="alert alert-success"><?= $success ?></div>
    <?php endif; ?>

    <form method="post" action="register.php">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Email address</label>
            <input type="email" name="email" class="form-control" required value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>First Name</label>
            <input type="text" name="first_name" class="form-control" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Last Name</label>
            <input type="text" name="last_name" class="form-control" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" class="form-control" required>
        </div>
        <div class="d-grid">
            <button class="btn btn-primary">Register</button>
        </div>
    </form>
</div>

</body>
</html>
