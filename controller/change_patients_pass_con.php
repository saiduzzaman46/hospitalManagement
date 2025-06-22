<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once '../config/connection.php';
    require_once '../model/get_user_info.php';
    require_once '../model/upddate_patients_mod.php';

    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmNewPassword = $_POST['confirmNewPassword'];

    $id = $_COOKIE['user_id'] ?? null;

    $getCurrentPassword = get_password_by_id($conn, $id, $currentPassword);
    $error = [];

    if (empty($currentPassword)) {
        $error['currentPassword'] = "Current password is required.";
    } elseif (!$getCurrentPassword) {
        $error['currentPassword'] = "Current password is incorrect.";
    }

    if (empty($newPassword)) {
        $error['newPassword'] = "New password is required.";
    } elseif (strlen($newPassword) < 8) {
        $error['newPassword'] = "New password must be at least 8 characters.";
    }

    if ($newPassword !== $confirmNewPassword) {
        $error['confirmNewPassword'] = "Passwords do not match.";
    }

    if (!empty($error)) {
        $_SESSION['errorPassChange'] = $error;
        header("Location: ../view/patients/patientsDash.php?section=profile");
        exit;
    }

    if (update_password($conn, $id, $newPassword)) {
        $_SESSION['successPassChange'] = "Password changed successfully.";
    } else {
        $_SESSION['errorPassChange']['general'] = "Password update failed.";
    }

    header("Location: ../view/patients/patientsDash.php?section=profile");
    exit;
}
