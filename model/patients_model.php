<?php
require_once __DIR__ . '/../config/connection.php';
require_once 'appointment_model.php';
require_once 'time_formate.php';
require_once 'get_user_info.php';

function patientsDashboard($user_id)
{
    global $conn;
    $data = [];

    // Get user info
    $user_info = get_patients_info($conn, $user_id);
    $data['user_info'] = $user_info;
    $data['phone'] = get_user_phone($conn, $user_info['pid']);

    // Total doctors count
    $totalQuery = "SELECT COUNT(*) as total FROM `doctorregister`";
    $totalResult = mysqli_query($conn, $totalQuery);
    $data['totalDoctors'] = ($totalResult && mysqli_num_rows($totalResult) > 0)
        ? mysqli_fetch_assoc($totalResult)['total']
        : 0;

    // Appointment booking form doctor info
    $data['appointmentDoctor'] = null;
    $data['availablityDoctor'] = 'Not specified';
    if (isset($_GET['section']) && $_GET['section'] === 'appointment_form' && isset($_GET['id'])) {
        $doctorId = $_GET['id'];
        $doctorInfo = get_doctor_info($conn, $doctorId);
        $data['appointmentDoctor'] = $doctorInfo;
        $data['availablityDoctor'] = format_availability($doctorInfo['availablity'] ?? 'Not specified');
    }

    // Appointments split
    $allAppointments = get_patient_appointments($conn, $user_info['pid']);
    $data['upcomingAppointments'] = [];
    // $data['upcomingAppointmentsStatus'] = [];
    $data['pastAppointments'] = [];

    date_default_timezone_set('Asia/Dhaka');
    $currentDateTime = date('Y-m-d H:i:s');

    foreach ($allAppointments as $appt) {
        $status = strtolower(trim($appt['status']));
        $apptDateTime = $appt['appointment_date'] . ' ' . $appt['appointment_time'];
        if ($apptDateTime >= $currentDateTime && ($status === 'pending') || ($status === 'confirmed')) {
            $data['upcomingAppointments'][] = $appt;
        } else {
            $data['pastAppointments'][] = $appt;
        }
    }

    $data['upcomingAppointmentsCount'] = count($data['upcomingAppointments']);
    $data['pastAppointmentsCount'] = count($data['pastAppointments']);

    return $data;
}
