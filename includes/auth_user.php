<?php
require_once '../session.php';

if (!isset($_SESSION['user'])) {
    header("Location: /user/login.php");
    exit();
}
?>
