<?php
session_start();
require_once '../db.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit();
}

// Get user info from session
$user_id = $_SESSION['user']['user_id'];

// Fetch user details from database
$stmt = $pdo->prepare("SELECT username, email, role, is_verified FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo "User not found.";
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Your Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Your Profile</h2>
    <table class="table table-bordered w-50">
        <tr>
            <th>Username</th>
            <td><?php echo htmlspecialchars($user['username']); ?></td>
        </tr>
        <tr>
            <th>Email</th>
            <td><?php echo htmlspecialchars($user['email']); ?></td>
        </tr>
        <tr>
            <th>Role</th>
            <td><?php echo htmlspecialchars($user['role']); ?></td>
        </tr>
        <tr>
            <th>Verified</th>
            <td><?php echo $user['is_verified'] ? 'Yes' : 'No'; ?></td>
        </tr>
    </table>
    <a href="edit_profile.php" class="btn btn-primary">Edit Profile</a>
    <a href="logout.php" class="btn btn-secondary">Logout</a>
</div>
</body>
</html>