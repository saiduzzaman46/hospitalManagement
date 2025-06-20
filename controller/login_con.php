<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/connection.php';
    require_once '../model/login_model.php';

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
        $user_info = get_user_info($conn, $user_ref_id);


        if ($role === 'patient') {
            $_SESSION['user_info'] = get_user_info($conn, $user_ref_id);
            header("Location: ../view/patients/patientsDash.php");
            $_SESSION['login_success'] = [
                'fname' => $user_info['fname'],
                'email' => $user_info['email'],
                'dob' => $user_info['dob'],
                'gender' => $user_info['gender'],
                'address' => $user_info['address'],
                'phone' => $phone,
                'password' => $password,
            ];
            exit();
        } else if ($role === 'doctor') {
            $_SESSION['user_info'] = get_user_info($conn, $user_ref_id);
            header("Location: ../view/doctors/doctorDash.php");
            exit();
        } else if ($role === 'admin') {
            $_SESSION['user_info'] = get_user_info($conn, $user_ref_id);
            header("Location: ../view/admin/adminDash.php");
            exit();
        }
        exit;
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
}
