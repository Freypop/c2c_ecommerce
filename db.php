<?php

// session_start();
$host = 'localhost';
$db   = 'c2c_platform_database';      // Your database name
$user = 'root';                 // Default username in XAMPP/WAMP
$pass = '';                     // Empty password by default in XAMPP
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";

try {
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
} catch (\PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// MySQLi connection (for legacy code)
$conn = mysqli_connect($host, $user, $pass, $db);

if (!$conn) {
    die('Database connection error: ' . mysqli_connect_error());
}
?>
