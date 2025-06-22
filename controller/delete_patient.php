<?php
session_start();
require_once '../config/connection.php';
require_once '../model/delete_user_info.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['patient_id'])) {
    $id = $_POST['patient_id'];

    if ($id <= 0) {
        $_SESSION['deleteError'] = "Invalid patient ID.";
        header("Location: ../view/admin/adminDash.php?section=patients");
        exit;
    }

    if (delete_user_info($conn, $id)) {
        $_SESSION['deleteSuccess'] = "Patient deleted successfully.";
    } else {
        $_SESSION['deleteError'] = "Failed to delete patient. Please try again.";
    }
} else {
    $_SESSION['deleteError'] = "Invalid request.";
}

// Redirect back to admin dashboard
header("Location: ../view/admin/adminDash.php?section=patients");
exit;
