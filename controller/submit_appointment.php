<?php
session_start();
require_once '../config/connection.php';
require_once '../model/appointment_model.php';

// insert_appointment($conn,'A003','A001', 'D001', 'P001', '2023-10-01');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctor_id        = $_POST['doctor_id'] ?? '';
    $patient_id       = $_POST['patient_id'] ?? '';
    $patient_name     = $_POST['patient_name'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $patient_phone    = $_POST['patient_phone'] ?? '';
    $appointment_date = $_POST['appointment_date'] ?? '';
    $appointment_time = $_POST['appointment_time'] ?? '';

    $error = [];

    // For testing purposes, override POST values with sample data
    try {
        // Validate inputs
        if (empty($doctor_id) || empty($patient_id) || empty($appointment_date) || empty($appointment_time)) {
            $error[] = "All fields are required.";
        }

        if ($error) {
            $_SESSION['errorSubmitAppointment'] = $error;
            header("Location: ../view/patients/patientsDash.php?section=appointment_form&id=$doctor_id");
            exit;
        }

        // Generate appointment ID
        $last_appoint_id = get_last_appointment_id($conn);
        $appoint_id = $last_appoint_id ? 'A' . str_pad((intval(substr($last_appoint_id, 1)) + 1), 3, '0', STR_PAD_LEFT) : 'A001';

        // Insert appointment
        if (insert_appointment($conn, $appoint_id, $doctor_id, $patient_id, $patient_name,$gender, $patient_phone, $appointment_date, $appointment_time)) {
            $_SESSION['successSubmitAppointment'] = "Appointment successfully booked.";
            header("Location: ../view/patients/patientsDash.php?section=doctors");
            exit;
        } else {
            throw new Exception("Failed to book appointment. Please try again.");
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage();
        exit;
    }
} else {
    echo "Invalid request method.";
    exit;
}
