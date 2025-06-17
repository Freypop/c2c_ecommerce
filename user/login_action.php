<?php
session_start();
require_once __DIR__ . '/../db.php'; // Adjust path if necessary

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        header('Location: login.php?error=Email and password are required');
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Set session with user info
            $_SESSION['user'] = [
                'user_id'     => $user['user_id'],
                'username'    => $user['username'],
                'role'        => $user['role'],
                'is_verified' => $user['is_verified']
            ];
            header("Location: /c2c_platform/index.php"); // Redirect to homepage
            exit;
        } else {
            header("Location: login.php?error=Invalid email or password");
            exit;
        }

    } catch (PDOException $e) {
        // Optional: log error
        header("Location: login.php?error=Database error");
        exit;
    }

} else {
    header("Location: login.php");
    exit;
}