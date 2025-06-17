<?php
session_start();
require_once '../auth/auth_admin.php';
require_once '../db.php';

// Fetch all users
$stmt = $pdo->query("SELECT user_id, username, email, role, is_verified, created_at FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();

// Fetch pending sellers
$stmt_pending = $pdo->query("SELECT * FROM users WHERE role = 'seller' AND is_verified = 0");
$pending_sellers = $stmt_pending->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - C2C Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
    <div class="container">
        <a class="navbar-brand" href="#"><strong>C<sup>2</sup>C Admin</strong></a>
        <div class="ms-auto">
            <span class="me-3">Logged in as <strong><?= htmlspecialchars($_SESSION['user']['username']) ?></strong></span>
            <a href="../logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </div>
</nav>

<div class="container mt-5">
    <h2 class="mb-4">Admin Dashboard</h2>

    <!-- Section 1: All Users -->
    <div class="mb-5">
        <h4>All Registered Users</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Verified</th>
                    <th>Registered On</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= htmlspecialchars($user['user_id']) ?></td>
                    <td><?= htmlspecialchars($user['username']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['role']) ?></td>
                    <td><?= $user['is_verified'] ? 'Yes' : 'No' ?></td>
                    <td><?= htmlspecialchars($user['created_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Section 2: Pending Seller Verifications -->
    <div>
        <h4>Pending Seller Verifications</h4>
        <?php if (count($pending_sellers) === 0): ?>
            <div class="alert alert-success">No pending sellers to verify.</div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($pending_sellers as $seller): ?>
                <div class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                        <strong><?= htmlspecialchars($seller['username']) ?></strong>
                        <span class="text-muted">(<?= htmlspecialchars($seller['email']) ?>)</span>
                    </div>
                    <form method="POST" action="../admin/dashboard.php" class="mb-0">
                        <input type="hidden" name="user_id" value="<?= $seller['user_id'] ?>">
                        <button type="submit" class="btn btn-success btn-sm">Approve</button>
                    </form>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
