<?php
require_once __DIR__ . '/../config/connection.php';
global $conn; // Ensure you have a database connection file
function adminDashboardPatients()
{
    global $conn;
    $query = "SELECT p.pid, p.fname, p.email, p.address, p.gender, p.dob, l.phone
              FROM patientsregister AS p
              JOIN login AS l ON p.pid = l.user_ref_id
              WHERE l.role = 'patient'";

    $result = mysqli_query($conn, $query);
    $patients = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $patients[] = $row;
        }
    }

    return $patients;
}

function adminDashboardDoctors()
{
    global $conn;
    $query = "SELECT d.did, d.fname, d.specialty, d.email, d.fees, d.info, d.availablity, l.phone 
              FROM doctorregister AS d 
              JOIN login AS l ON d.did = l.user_ref_id 
              WHERE l.role = 'doctor'";

    $result = mysqli_query($conn, $query);
    
    $doctors = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $doctors[] = $row;
        }
    }

    return $doctors;
}
function adminDashboardAppointments()
{
    global $conn;
    $query = "SELECT * FROM `appointments`";

    $result = mysqli_query($conn, $query);
    $appointments = [];
    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}

function get_doctor_by_id($doctors, $editId)
{
    foreach ($doctors as $doctor) {
        if ($doctor['did'] == $editId) {
            $availability = $doctor['availablity'];
            [$days, $times] = explode(',', $availability);
            [$fromDay, $toDay] = array_map('trim', explode('to', $days));
            [$startTime, $endTime] = array_map('trim', explode('-', $times));
            $doctor['fromDay'] = $fromDay;
            $doctor['toDay'] = $toDay;
            $doctor['startTime'] = $startTime;
            $doctor['endTime'] = $endTime;
            return $doctor;
        }
    }
    return null;
}

function get_appointment_by_id($appointments, $appointmentId)
{
    foreach ($appointments as $appointment) {
        if ($appointment['id'] == $appointmentId) {
            return $appointment;
        }
    }
    return null;
}

function get_patient_by_id($patients, $patientId)
{
    foreach ($patients as $patient) {
        if ($patient['pid'] == $patientId) {
            return $patient;
        }
    }
    return null;
}
