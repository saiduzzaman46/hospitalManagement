<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/connection.php';
    require_once '../model/get_user_info.php';

    $phone = $_POST['phone'];
    $password = $_POST['password'];



    try {
        $error = [];

        if (!get_phone($conn, $phone)) {
            $error['phone'] = "Phone number does not exist.";
        }

        if (!get_password($conn, $phone, $password)) {
            $error['password'] = "Incorrect password.";
        }

        if ($error) {
            $_SESSION['errorLogin'] = $error;
            header("Location: ../view/login/login.php");
            exit;
        }

        $user_ref_id = get_user_ref_id($conn, $phone);
        $role = get_role($conn, $phone);
        // $user_info = get_user_info($conn, $user_ref_id);


        if ($role === 'patient') {
            setcookie('user_id', $user_ref_id, time() + 86400, "/");

            header("Location: ../view/patients/patientsDash.php");
            exit();
        } else if ($role === 'doctor') {
            setcookie('doctor_id', $user_ref_id, time() + 86400, "/");
            header("Location: ../view/doctor/doctorDash.php");
            exit();
        } else if ($role === 'admin') {
            setcookie('admin_id', $user_ref_id, time() + 86400, "/");
            header("Location: ../view/admin/adminDash.php");
            exit();
        }
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
