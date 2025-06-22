<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    require_once '../config/connection.php';
    require_once '../model/get_user_info.php';
    require_once '../model/upddate_patients_mod.php';

    $id = $_POST['patient_id'];
    $fname = trim($_POST['fullname']);
    $email = trim($_POST['email']);
    $dob = trim($_POST['dob']);
    $gender = trim($_POST['gender']);
    $address = trim($_POST['address']);
    $phone = trim($_POST['phone']);

    $error = [];

    // Basic validation
    if (empty($fname)) $error['fullname'] = "Full name is required.";
    if (empty($email)) $error['email'] = "Email is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error['email'] = "Invalid email format.";
    if (empty($dob)) $error['dob'] = "Date of birth is required.";
    if (strtotime($dob) >= time()) $error['dob'] = "Date of birth cannot be in the future.";
    if (empty($gender)) $error['gender'] = "Gender is required.";
    if (empty($address)) $error['address'] = "Address is required.";
    if (empty($phone)) $error['phone'] = "Phone number is required.";
    if (!preg_match('/^[0-9]{10,15}$/', $phone)) $error['phone'] = "Invalid phone number format.";

    // Check for duplicate email/phone
    if (get_email_update($conn, $email, $id)) $error['email'] = "Email already exists.";
    if (get_phone_update($conn, $phone, $id)) $error['phone'] = "Phone number already exists.";

    // If errors exist
    if (!empty($error)) {
        $_SESSION['errorUpdate'] = $error;
        if(isset($_POST['submit-btn'])) {
            header("Location: ../view/patients/patientsDash.php?section=profile&edit=1");
        }elseif(isset($_POST['save-change'])) {
            header("Location: ../view/admin/adminDash.php?section=patients&action=edit_patient&id=$id");
        }
        exit;
    }

    // Update if no errors
    if (update_patient($conn, $id, $fname, $email, $dob, $gender, $address) && update_phone($conn, $id, $phone)) {
        $_SESSION['successUpdate'] = "Profile updated successfully.";
    } else {
        $_SESSION['errorUpdate']['general'] = "Update failed. Please try again.";
    }

    // header("Location: ../view/patients/patientsDash.php?section=profile");
    if(isset($_POST['submit-btn'])) {
        header("Location: ../view/patients/patientsDash.php?section=profile");
    } elseif(isset($_POST['save-change'])) {
        header("Location: ../view/admin/adminDash.php?section=patients");
    }
    exit;
}
