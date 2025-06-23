<?php
session_start();
require_once '../config/connection.php';
require_once '../model/get_user_info.php';
require_once '../model/add_doctor_mod.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['action']) && $_POST['action'] === 'add') {
        $password = $_POST['doctorPassword'];
    }

    $name     = $_POST['doctorName'];
    $specialty = $_POST['doctorSpecialty'];
    $email    = $_POST['doctorContact'];
    $phone    = $_POST['doctorPhone'];
    $fees     = $_POST['doctorFees'];
    $info     = $_POST['doctorInfo'];
    $fromDay  = $_POST['doctorFromDay'];
    $toDay    = $_POST['doctorToDay'];
    $start    = $_POST['doctorStartTime'];
    $end      = $_POST['doctorEndTime'];

    $availability = "$fromDay to $toDay, $start - $end";

    $errors = [];
    try {

        // Common validation
        if (empty($name) || empty($specialty) || empty($email) || empty($phone) || empty($fees) || empty($info) || empty($fromDay) || empty($toDay) || empty($start) || empty($end)) {
            $errors[] = "All fields are required.";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format.";
        } elseif (!is_numeric($fees) || $fees <= 0) {
            $errors[] = "Fees must be a positive number.";
        }

        // Insert Operation
        if (isset($_POST['adddoctor'])) {
            if (empty($password) || strlen($password) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            }
            if (get_doctor_email($conn, $email)) {
                $errors[] = "Email already exists.";
            }
            if (get_doctor_phone($conn, $phone)) {
                $errors[] = "Phone number already exists.";
            }

            if ($errors) {
                $_SESSION['errorAddDoctor'] = $errors;
                header("Location: ../view/admin/adminDash.php?section=doctors&action=add");
                exit;
            }

            // Generate doctor ID
            $lastDid = get_last_did($conn);
            $lastDid = $lastDid ? 'D' . str_pad((intval(substr($lastDid, 1)) + 1), 3, '0', STR_PAD_LEFT) : 'D001';

            if (insert_doctor($conn, $lastDid, $name, $specialty, $email, $phone, $password, $fees, $info, $availability)) {
                $_SESSION['successAddDoctor'] = "Doctor added successfully.";
            } else {
                throw new Exception("Failed to add doctor.");
            }
            header("Location: ../view/admin/adminDash.php?section=doctors&action=view");
            exit;
        }

        // Update Operation
        if (isset($_POST['savechange']) && isset($_POST['doctor_id'])) {
            $doctorId = $_POST['doctor_id'];

            if ($errors) {
                $_SESSION['errorEditDoctor'] = $errors;
                header("Location: ../view/admin/adminDash.php?section=doctors&action=edit_doctor&id=" . $doctorId);
                exit;
            }

            if (update_doctor($conn, $doctorId, $name, $specialty, $email, $phone, $fees, $info, $availability)) {
                $_SESSION['successEditDoctor'] = "Doctor updated successfully.";
            } else {
                throw new Exception("Failed to update doctor.");
            }
            header("Location: ../view/admin/adminDash.php?section=doctors&action=view");
            exit;
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
