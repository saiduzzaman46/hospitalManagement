<?php
session_start();

session_destroy();

if (isset($_COOKIE['admin_id'])) {
    setcookie('admin_id', '', time() - 3600, '/'); 
}
header("Location: ../index.php"); 
exit;
