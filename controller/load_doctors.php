<?php
require_once '../config/connection.php';
require_once '../model/time_formate.php';

$name = $_GET['name'] ?? '';
$specialty = $_GET['specialty'] ?? '';

$query = "SELECT * FROM `doctorregister` WHERE 1";
if (!empty($name)) {
    $query .= " AND fname LIKE '%" . $name . "%'";
}
if (!empty($specialty)) {
    $query .= " AND specialty LIKE '%" . $specialty . "%'";
}

$result = mysqli_query($conn, $query);

if ($result && mysqli_num_rows($result) > 0) {
    while ($doctor = mysqli_fetch_assoc($result)) {
        echo '<div class="doctor-card">';
        echo '<div class="doctor-avatar"><i class="fas fa-user-md"></i></div>';
        echo '<h3>' . htmlspecialchars($doctor['fname']) . '</h3>';
        echo '<p class="doctor-specialty">' . htmlspecialchars($doctor['specialty']) . '</p>';
        echo '<p>' . htmlspecialchars($doctor['info']) . '</p>';
        echo '<div class="available-times"><strong>Available:</strong> ' . htmlspecialchars(format_availability($doctor['availablity'])) . '</div>';
        echo '<div class="card-buttons">
            <a href="?section=appointment_form&id=' . htmlspecialchars($doctor['did']) . '" class="button button-secondary">Appointment</a>
          </div>';
        echo '</div>';
    }
} else {
    echo '<p>No doctors found.</p>';
}
