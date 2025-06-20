<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/connection.php';
    require_once '../model/reg_mod.php';

    $fname = $_POST['fullName'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirmPassword'];
    $phone = $_POST['phone'];
    $dob = $_POST['dob'];
    $gender = $_POST['gender'];
    $address = $_POST['address'];
    try {
        $error = [];

        if (get_email_exist($conn, $email)) {
            $error['email'] = "Email already exists.";
        }
        if (get_phone_exist($conn, $phone)) {
            $error['phone'] = "Phone number already exists.";
        }

        if ($error) {
            $_SESSION['errorRegister'] = $error;
            header("Location: ../view/patients/register.php");
            exit;
        }
        if (insert_patient($conn, $fname, $email, $password, $phone, $dob, $gender, $address)) {
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
