<?php
require_once '../config/connection.php';

// if ($_SERVER['REQUEST_METHOD'] === 'POST') {
//     $appointmentId = $_POST['id'] ?? '';
//     $status = $_POST['status'] ?? '';

//      if ($status === 'Completed') {
//         $visitDate = date('Y-m-d');
//         $query = "UPDATE appointments SET status = '$status', visit_date = '$visitDate' WHERE aid = '$id'";
//     } else {
//         $query = "UPDATE appointments SET status = '$status' WHERE aid = '$id'";
//     }

//     if (mysqli_query($conn, $query)) {
//         echo "Status updated successfully.";
//     } else {
//         echo "Error updating status.";
//     }
// } else {
//     echo "Invalid request method.";
// }


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'], $_POST['status'])) {
    $id = mysqli_real_escape_string($conn, $_POST['id']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);
    if ($status === 'Completed') {
        $visitDate = date('Y-m-d');
        $query = "UPDATE appointments SET status = '$status', visit_date = '$visitDate' WHERE aid = '$id'";
    } else {
        // Leave visit_date unchanged
        $query = "UPDATE appointments SET status = '$status' WHERE aid = '$id'";
    }

    if (mysqli_query($conn, $query)) {
        echo "Status updated successfully.";
    } else {
        echo "Error updating status.";
    }
} else {
    echo "Invalid request.";
}
?>
