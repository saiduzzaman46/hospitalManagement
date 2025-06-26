<?php
session_start();

session_destroy();

if (isset($_COOKIE['doctor_id'])) {
    setcookie('doctor_id', '', time() - 3600, '/'); 
}
header("Location: ../index.php"); 
exit;
