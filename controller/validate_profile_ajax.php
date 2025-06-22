<?php
header('Content-Type: application/json');

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $gender = trim($_POST['gender']);
    $dob = trim($_POST['dob']);

    if (empty($fullname)) $errors['fullname'] = "Full name is required.";
    if (empty($email)) $errors['email'] = "Email is required.";
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = "Invalid email.";

    if (empty($phone)) $errors['phone'] = "Phone number is required.";
    elseif (!preg_match('/^[0-9]{10,15}$/', $phone)) $errors['phone'] = "Phone must be 10-15 digits.";

    if (empty($address)) $errors['address'] = "Address is required.";

    if (empty($gender)) $errors['gender'] = "Gender is required.";

    if (empty($dob)) {
        $errors['dob'] = "Date of birth is required.";
    } else {
        $today = date("Y-m-d");
        if ($dob >= $today) $errors['dob'] = "Date of birth cannot be in the future.";
    }

    if (!empty($errors)) {
        echo json_encode(['status' => 'error', 'errors' => $errors]);
        exit;
    } else {
        echo json_encode(['status' => 'success']);
        exit;
    }
}
