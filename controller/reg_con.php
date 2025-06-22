<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/connection.php';
    require_once '../model/reg_mod.php';
    require_once '../model/get_user_info.php';

    $fname = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    
    if (strpos($phone, '+88') === 0) {
        $phone = substr($phone, 3);
    }


    $lastPid = get_last_pid($conn); // Assume this returns something like 'P001'
    if ($lastPid) {
        $num = intval(substr($lastPid, 1)) + 1;
        $pid = 'P' . str_pad($num, 3, '0', STR_PAD_LEFT);
    } else {
        $pid = 'P001';
    }

    try {
        $error = [];

        if (get_email($conn, $email)) {
            $error['email'] = "Email already exists.";
        }
        if (get_phone($conn, $phone)) {
            $error['phone'] = "Phone number already exists.";
        }

        if ($error) {
            $_SESSION['errorRegister'] = $error;
            header("Location: ../view/patients/register.php");
            exit;
        }
        if (insert_patient($conn,$pid, $fname, $email, $password, $phone, $dob, $gender, $address)) {
            header("Location: ../view/login/login.php");
            exit;
        } else {
            throw new Exception("Registration failed. Please try again.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
