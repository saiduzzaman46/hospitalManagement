<?php

function insert_appointment($conn, $aid, $doctor_id, $patient_id, $patient_name,$gender, $patients_phone, $appointment_date, $appointment_time, $status = 'Pending')
{
    date_default_timezone_set('Asia/Dhaka');
    $current_date = date('Y-m-d H:i:s');
    $query = "INSERT INTO `appointments` (`aid`, `doctor_id`, `patient_id`, `patientsName`,`patients_gender`, `patientsPhone`, `appointment_date`, `appointment_time`, `status`, `created_at`)
            VALUES ('$aid', '$doctor_id', '$patient_id','$patient_name','$gender','$patients_phone', '$appointment_date', '$appointment_time', '$status','$current_date');";
    if ($conn->query($query)) {
        return true;
    } else {
        return false;
    }
}


function get_appointment_by_id($conn, $appointment_id)
{
    $query = "SELECT * FROM `appointments` WHERE `appointment_id` = '$appointment_id'";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}

function get_last_appointment_id($conn)
{
    $query = "SELECT `aid` FROM `appointments` ORDER BY `aid` DESC LIMIT 1";
    $result = $conn->query($query);

    if ($result && $result->num_rows > 0) {
        return $result->fetch_assoc()['aid'];
    } else {
        return null;
    }
}

function get_patient_appointments($conn, $patient_id)
{
    $query = "SELECT a.*, d.fname AS doctor_name, d.specialty 
              FROM appointments a 
              JOIN doctorregister d ON a.doctor_id = d.did 
              WHERE a.patient_id = '$patient_id'
              ORDER BY a.appointment_date, a.appointment_time";

    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $appointments = [];
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
        return $appointments;
    } else {
        return [];
    }
}

function get_doctor_appointments($conn, $doctor_id)
{
    $query = "SELECT * FROM appointments 
              WHERE doctor_id = '$doctor_id' 
              ORDER BY appointment_date, appointment_time;";

    $result = mysqli_query($conn, $query);
    $appointments = [];

    if ($result && mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            $appointments[] = $row;
        }
    }

    return $appointments;
}
