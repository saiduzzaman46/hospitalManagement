<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'add') {
    require_once '../config/connection.php';
    require_once '../model/get_user_info.php';
    require_once '../model/add_doctor_mod.php';


    $name     = $_POST['doctorName'];
    $specialty = $_POST['doctorSpecialty'];
    $email    = $_POST['doctorContact'];
    $phone    = $_POST['doctorPhone'];
    $password = $_POST['doctorPassword'];
    $fees     = $_POST['doctorFees'];
    $info     = $_POST['doctorInfo'];
    $fromDay  = $_POST['doctorFromDay'];
    $toDay    = $_POST['doctorToDay'];
    $start    = $_POST['doctorStartTime'];
    $end      = $_POST['doctorEndTime'];

    $availability = "$fromDay to $toDay, $start - $end";

    // Validate input data
    if (!isset($conn)) {
        echo "Database connection failed.";
        exit;
    }

    try {

        $errors = [];

        if (empty($name) || empty($specialty) || empty($email) || empty($phone) || empty($password) || empty($fees) || empty($info) || empty($fromDay) || empty($toDay) || empty($start) || empty($end)) {
            $errors[] = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } elseif (strlen($password) < 8) {
            $errors[] = "Password must be at least 8 characters long.";
        } elseif (!is_numeric($fees) || $fees <= 0) {
            $errors[] = "Fees must be a positive number.";
        } elseif (get_doctor_email($conn, $email)) {
            $errors[] = "Email already exists.";
        } elseif (get_doctor_phone($conn, $phone)) {
            $errors[] = "Phone number already exists.";
        }
        // Check if doctor ID already exists
        $lastDid = get_last_did($conn);
        if ($lastDid) {
            $lastDid = intval(substr($lastDid, 1)) + 1;
            $lastDid = 'D' . str_pad($lastDid, 3, '0', STR_PAD_LEFT);
        } else {
            $lastDid = 'D001';
        }

        if ($errors) {
            $_SESSION['errorAddDoctor'] = $errors;
            header("Location: ../view/admin/adminDash.php?section=doctors&action=add");
            exit;
        }


        if (insert_doctor($conn, $lastDid, $name, $specialty, $email, $phone, $password, $fees, $info, $availability)) {
            $_SESSION['successAddDoctor'] = "Doctor added successfully.";
            header("Location: ../view/admin/adminDash.php?section=doctors&action=view");
        } else {
            throw new Exception("Failed to add doctor. Please try again.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
    header("Location: ../view/admin/adminDash.php?section=doctors&action=view");
    exit;
}
 
        // Insert doctor information