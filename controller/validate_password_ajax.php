<?php
header('Content-Type: application/json');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current = trim($_POST['currentPassword'] ?? '');
    $new = trim($_POST['newPassword'] ?? '');
    $confirm = trim($_POST['confirmNewPassword'] ?? '');

    if (empty($current)) $errors['current'] = "Current password required.";
    if (strlen($new) < 8) $errors['new'] = "New password must be 8+ characters.";
    if ($new !== $confirm) $errors['confirm'] = "Passwords do not match.";

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    } else {
        echo json_encode(['status' => 'success']);
        exit;
    }
}
