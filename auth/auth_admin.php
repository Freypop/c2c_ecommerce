<?php
// session_start();
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: ../user/login.php');
    exit;
}
?>