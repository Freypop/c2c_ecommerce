<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=c2c_ecommerce_database", "root", "");
    echo "Connection successful!";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
