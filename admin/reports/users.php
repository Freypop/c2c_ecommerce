<?php
// filepath: c:\xampp\htdocs\c2c_platform\admin\reports\users.php
session_start();
require_once '../../db.php';

// Fetch all users
$stmt = $pdo->query("SELECT * FROM users ORDER BY created_at DESC");
$users = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Report</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container py-4">
    <h2>User Report</h2>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>User ID</th><th>Username</th><th>Email</th><th>Role</th><th>Registered</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($users as $u): ?>
            <tr>
                <td><?= $u['user_id'] ?></td>
                <td><?= htmlspecialchars($u['username']) ?></td>
                <td><?= htmlspecialchars($u['email']) ?></td>
                <td><?= htmlspecialchars($u['role'] ?? '-') ?></td>
                <td><?= $u['created_at'] ?></td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>