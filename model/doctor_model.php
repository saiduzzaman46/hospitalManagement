<?php
require_once __DIR__ . '/../config/connection.php';
require_once 'appointment_model.php';
require_once 'time_formate.php';
require_once 'get_user_info.php';

// function doctorDashboard($user_id)
// {
//     global $conn;
//     $data = [];

//     // Get user info
//     $user_info = get_doctor_info($conn, $user_id);
//     $data['user_info'] = $user_info;
//     $data['phone'] = get_user_phone($conn, $user_info['did']);

//     // Total patients count
//     $totalQuery = "SELECT COUNT(*) as total FROM `patientsregister`";
//     $totalResult = mysqli_query($conn, $totalQuery);
//     $data['totalPatients'] = ($totalResult && mysqli_num_rows($totalResult) > 0)
//         ? mysqli_fetch_assoc($totalResult)['total']
//         : 0;

//     // Appointment booking form patient info
//     $data['appointmentPatient'] = null;
//     if (isset($_GET['section']) && $_GET['section'] === 'appointment_form' && isset($_GET['id'])) {
//         $patientId = $_GET['id'];
//         $patientInfo = get_patient_info($conn, $patientId);
//         $data['appointmentPatient'] = $patientInfo;
//     }

//     // Appointments split
//     $allAppointments = get_doctor_appointments($conn, $user_info['did']);
//     $data['upcomingAppointments'] = [];
//     $data['pastAppointments'] = [];

//     $currentDateTime = date('Y-m-d H:i:s');

//     foreach ($allAppointments as $appt) {
//         if ($appt['doctor_ref_id'] == $user_info['did']) {
//             $apptDateTime = $appt['appointment_date'] . ' ' . $appt['appointment_time'];
//             if ($apptDateTime >= $currentDateTime) {
//                 $data['upcomingAppointments'][] = $appt;
//             } else {
//                 $data['pastAppointments'][] = $appt;
//             }
//         }
//     }

//     $data['upcomingAppointmentsCount'] = count($data['upcomingAppointments']);
//     $data['pastAppointmentsCount'] = count($data['pastAppointments']);

//     return $data;
// }

function get_doctor_data($doctorId)
{
    global $conn;
    $query = "SELECT d.did, d.fname, d.specialty, d.email, d.fees, d.info, d.availablity, l.phone 
              FROM doctorregister AS d 
              JOIN login AS l ON d.did = l.user_ref_id 
              WHERE l.role = 'doctor' AND d.did = '$doctorId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}


function doctor_appointments($doctorId)
{
    global $conn;
    $data = [];
    $allAppointments = get_doctor_appointments($conn, $doctorId);
    $data['todayAppointments'] = [];
    $data['upcomingAppointments'] = [];
    $data['pastAppointments'] = [];
     
    date_default_timezone_set('Asia/Dhaka');
    $today = date('Y-m-d'); 

    foreach ($allAppointments as $appt) {
        $apptDate = $appt['appointment_date'];
        $status = strtolower(trim($appt['status'])); // sanitize status

        if ($apptDate === $today && $status === 'confirmed') {
            $data['todayAppointments'][] = $appt;
        } elseif ($apptDate > $today && ($status === 'pending') || ($status === 'confirmed')) {
            $data['upcomingAppointments'][] = $appt;
        } elseif ($apptDate < $today || $status === 'completed') {
            $data['pastAppointments'][] = $appt;
        }
    }

    return $data;
}


function get_all_patients_data($doctorId)
{
    global $conn;
    $result = get_doctor_appointments($conn, $doctorId);

    $result = array_filter($result, function($appointment) {
        return !empty($appointment['visit_date']);
    });
    if ($result) {
        $patients = [];
        foreach ($result as $pastient) {
             $patients[] = $pastient;
        }
        return  $patients;
    } else {
        return [];
    }
}

function get_current_patients_data($appointmentId)
{
    global $conn;
    $result = get_current_patient_info($conn, $appointmentId);
    if ($result) {
        return $result;
    } else {
        return [];
    }
}

function get_name_by_aid($appointmentId)
{
    global $conn;
    $query = "SELECT `patientsName` FROM `appointments` WHERE `aid`= '$appointmentId'";
    $result = mysqli_query($conn, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        return mysqli_fetch_assoc($result);
    } else {
        return false;
    }
}