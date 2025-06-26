<?php
session_start();
require_once '../config/connection.php';
require_once '../model/delete_model.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['patient_id'])) {
        $patient_id = $_POST['patient_id'];

        // Validate patient ID
        if (empty($patient_id)) {
            $_SESSION['deleteError'] = "Patient ID is required.";
            header("Location: ../view/admin/adminDash.php?section=patients");
            exit;
        }

        // Delete patient information
        if (delete_user_info($conn, $patient_id)) {
            $_SESSION['deleteSuccess'] = "Patient deleted successfully.";
        } else {
            $_SESSION['deleteError'] = "Failed to delete patient. Please try again.";
        }
        header("Location: ../view/admin/adminDash.php?section=patients");
        exit;
    } else {
        $_SESSION['deleteError'] = "Invalid request.";
    }

    if (isset($_POST['doctor_id'])) {
        $doctor_id = $_POST['doctor_id'];

        // Validate doctor ID
        if (empty($doctor_id)) {
            $_SESSION['deleteError'] = "Doctor ID is required.";
            header("Location: ../view/admin/adminDash.php?section=doctors");
            exit;
        }

        // Delete doctor information
        if (delete_doctor_info($conn, $doctor_id)) {
            $_SESSION['deleteSuccess'] = "Doctor deleted successfully.";
        } else {
            $_SESSION['deleteError'] = "Failed to delete doctor. Please try again.";
        }
        header("Location: ../view/admin/adminDash.php?section=doctors");
        exit;
    } else {
        $_SESSION['deleteError'] = "Invalid request.";
    }

    if(isset($_POST['appointment_id'])) {
        $appointment_id = $_POST['appointment_id'];

        // Validate appointment ID
        if (empty($appointment_id)) {
            $_SESSION['deleteError'] = "Appointment ID is required.";
            header("Location: ../view/admin/adminDash.php?section=appointments");
            exit;
        }

        // Delete appointment information
        if (delete_appointment_info($conn, $appointment_id)) {
            $_SESSION['deleteSuccess'] = "Appointment deleted successfully.";
        } else {
            $_SESSION['deleteError'] = "Failed to delete appointment. Please try again.";
        }
        header("Location: ../view/admin/adminDash.php?section=appointments");
        exit;
    }
} else {
    $_SESSION['deleteError'] = "Invalid request.";
}

// Redirect back to admin dashboard
