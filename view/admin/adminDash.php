<script>
    window.addEventListener('pageshow', function(event) {
        if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
            window.location.reload();
        }
    });
</script>


<?php
session_start();

if (!isset($_COOKIE['admin_id'])) {
    header("Location: ../login/login.php");
    exit();
}

require_once '../../model/admin_model.php';


$admin_fname = 'Admin User';
$admin_email = 'admin@medcare.com';

// Determine the current section for navigation
$section = isset($_GET['section']) ? $_GET['section'] : 'home';
$editMode = isset($_GET['edit']) && $_GET['edit'] == '1';

$specialties = [
    'Cardiology',
    'Pediatrics',
    'Neurology',
    'Dermatology',
    'Orthopedics',
    'Ophthalmology',
    'General Medicine',
    'Oncology',
    'Gastroenterology',
    'Psychiatry',
    'Urology',
    'ENT',
    'Dentistry'
];

$patients = adminDashboardPatients();
$doctors = adminDashboardDoctors();
$appointments = adminDashboardAppointments();

// 
$doctorToEdit = null;
if (isset($_GET['action']) && $_GET['action'] === 'edit_doctor' && isset($_GET['id'])) {
    $doctorToEdit = get_doctor_by_id($doctors, $_GET['id']);
}

// Appointment Edit Logic
$appointmentToEdit = null;
if ($section === 'appointments' && isset($_GET['action']) && $_GET['action'] === 'edit_appointment' && isset($_GET['id'])) {
    $appointmentToEdit = get_appointment_by_id($appointments, $_GET['id']);
}

// Patient Edit Logic
$patientToEdit = null;
if ($section === 'patients' && isset($_GET['action']) && $_GET['action'] === 'edit_patient' && isset($_GET['id'])) {
    $patientToEdit = get_patient_by_id($patients, $_GET['id']);
}






?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard - MedCare Hospital</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <link rel="stylesheet" href="../../assets/admin/adminDash.css" />

</head>

<body>
    <header>
        <div class="container">
            <div class="header-left">
                <div class="menu-toggle" id="menuToggle">
                    <i class="fas fa-bars"></i>
                </div>
                <a href="#" class="logo">MedCare Admin</a>
            </div>
            <div class="admin-info">
                <i class="fas fa-user-shield"></i>
                <span>Welcome, <?php echo htmlspecialchars($admin_fname); ?>!</span>
            </div>
        </div>
    </header>

    <div class="main-content-area">
        <aside class="sidebar scroll-container" id="sidebar">
            <h3>Admin Menu</h3>
            <ul class="sidebar-menu">
                <li><a href="?section=home" class="menu-item <?php echo $section === 'home' ? 'active' : ''; ?>"><i class="fas fa-tachometer-alt"></i> Dashboard Home</a></li>
                <li><a href="?section=patients" class="menu-item <?php echo $section === 'patients' ? 'active' : ''; ?>"><i class="fas fa-user-injured"></i> Patient List</a></li>
                <li><a href="?section=doctors" class="menu-item <?php echo $section === 'doctors' ? 'active' : ''; ?>"><i class="fas fa-user-md"></i> Doctor Management</a></li>
                <li><a href="?section=appointments" class="menu-item <?php echo $section === 'appointments' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> Appointment Management</a></li>
                <li><a href="?section=medicine" class="menu-item <?php echo $section === 'medicine' ? 'active' : ''; ?>"><i class="fas fa-pills"></i> Medicine Management</a></li>
                <li><a href="?section=profile" class="menu-item <?php echo $section === 'profile' ? 'active' : ''; ?>"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
                <li> <a href="../../controller/logoutAdmin.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

            </ul>
        </aside>

        <main class="content-display" id="contentDisplay">
            <!-- Home Section -->
            <section id="content-home" class="dashboard-section dashboard-home" style="<?php echo $section === 'home' ? '' : 'display: none'; ?>">
                <h2>Admin Dashboard Overview</h2>
                <p>Welcome to the MedCare Hospital Admin Panel. Here's a summary of key system data.</p>
                <div class="summary-cards">
                    <div class="card">
                        <h4>Total Patients</h4>
                        <p id="totalPatientsCount"><?php echo count($patients); ?></p>
                        <a class="button" href="?section=patients">View Patients</a>
                    </div>
                    <div class="card">
                        <h4>Total Doctors</h4>
                        <p id="totalDoctorsCount"><?php echo count($doctors); ?></p>
                        <a class="button" href="?section=doctors">View Doctors</a>
                    </div>
                    <div class="card">
                        <h4>Total Medicines</h4> <!-- NEW -->
                        <p id="totalMedicinesCount">0</p>
                        <!-- <button class="button" onclick="loadContent('medicines')">View Medicines</button> -->
                        <a class="button" href="?section=medicine">View Medicines</a>
                    </div>
                    <div class="card">
                        <h4>Today's Appointments</h4>
                        <p id="todayAppointmentsCount">0</p>
                        <a class="button" href="?section=appointments">View Appointments</a>
                    </div>
                </div>
                <div style="margin-top: 3.2rem; text-align: left; background-color: var(--light-bg); padding: 2.4rem; border-radius: var(--border-radius-base); border: 0.1rem solid var(--border-color-light);">
                    <h3>Quick Actions</h3>
                    <p>
                        <a href="?section=doctors&action=add" class="button button-secondary">Add New Doctor</a>
                        <a href="?section=appointments&action=create" class="button">Create Appointment</a>
                    </p>
                </div>
            </section>

            <!-- Patient List Section -->
            <section id="content-patients" class="dashboard-section" style="<?php echo $section === 'patients' ? '' : 'display: none'; ?>">
                <h2>Patient List</h2>
                <!-- <p>View patient records. Patient creation, editing, and deletion are handled via the backend system.</p> -->

                <div id="viewPatientsSection" style="<?php echo ($section === 'patients' && !$patientToEdit) ? '' : 'display: none'; ?>">
                    <h3>All Patients</h3>
                    <div class="table-container">
                        <table class="data-table" id="patientTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Address</th>
                                    <th>Gender</th>
                                    <th>DOB</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($patients as $patient) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($patient['pid']) . '</td>';
                                    echo '<td>' . htmlspecialchars($patient['fname']) . '</td>';
                                    echo '<td>' . htmlspecialchars($patient['phone']) . '</td>';
                                    echo '<td>' . htmlspecialchars($patient['email']) . '</td>';
                                    echo '<td>' . htmlspecialchars($patient['address']) . '</td>';
                                    echo '<td>' . htmlspecialchars($patient['gender']) . '</td>';
                                    echo '<td>' . htmlspecialchars($patient['dob']) . '</td>';
                                    echo '<td><div class="action-buttons">';
                                    echo '<a href="?section=patients&action=edit_patient&id=' . htmlspecialchars($patient['pid']) . '" class="button button-outline">Edit</a>';
                                    echo '<button class="button button-danger" onclick="deleteParient(\'' . htmlspecialchars($patient['pid']) . '\')">Delete</button>';
                                    echo '</div></td>';
                                    echo '</tr>';
                                }
                                if (empty($patients)) {
                                    echo '<tr><td colspan="8" style="text-align: center; padding: 2rem;">No patient records found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <form id="deletePatientsForm" method="POST" action="../../controller/delete_control.php" style="display: none;">
                        <input type="hidden" name="patient_id" id="deletePatientId" />
                    </form>
                </div>

                <div id="editPatientSection" class="form-section" style="<?php echo ($section === 'patients' && $patientToEdit) ? '' : 'display: none'; ?>">
                    <h3>Edit Patient Information</h3>
                    <?php if ($patientToEdit): ?>
                        <form id="editPatientForm" method="POST" action="../../controller/upddate_patients_con.php">
                            <input type="hidden" name="action" value="update_patient">
                            <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($patientToEdit['pid']); ?>">

                            <div class="profile-form-row">
                                <div class="form-group">
                                    <label for="editPatientName">Full Name:</label>
                                    <input type="text" id="editPatientName" name="fullname" value="<?php echo htmlspecialchars($patientToEdit['fname']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="editPatientPhone">Phone Number:</label>
                                    <input type="tel" id="editPatientPhone" name="phone" value="<?php echo htmlspecialchars($patientToEdit['phone']); ?>" pattern="[+]?[0-9]{10,15}" title="Phone number must be 10-15 digits, optionally starting with a plus sign." required>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="editPatientEmail">Email:</label>
                                <input type="email" id="editPatientEmail" name="email" value="<?php echo htmlspecialchars($patientToEdit['email']); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="editPatientAddress">Address:</label>
                                <textarea id="editPatientAddress" rows="2" name="address" required><?php echo htmlspecialchars($patientToEdit['address']); ?></textarea>
                            </div>

                            <div class="profile-form-row">
                                <div class="form-group">
                                    <label for="editPatientGender">Gender:</label>
                                    <select id="editPatientGender" name="gender" required>
                                        <option value="Male" <?php if ($patientToEdit['gender'] === "Male") echo "selected"; ?>>Male</option>
                                        <option value="Female" <?php if ($patientToEdit['gender'] === "Female") echo "selected"; ?>>Female</option>
                                        <option value="Other" <?php if ($patientToEdit['gender'] === "Other") echo "selected"; ?>>Other</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editPatientDob">Date of Birth:</label>
                                    <input type="date" id="editPatientDob" name="dob" value="<?php echo htmlspecialchars($patientToEdit['dob']); ?>" required>
                                </div>
                            </div>

                            <div class="form-actions">
                                <button type="submit" class="button button-secondary" name="save-change">Save Changes</button>
                                <a href="?section=patients" class="button button-outline">Cancel</a>
                            </div>
                        </form>

                    <?php else: ?>
                        <p class="message-box message-error" style="display:block;">Patient data not found. Please select a valid patient to edit.</p>
                        <div class="form-actions">
                            <a href="?section=patients" class="button button-outline">Back to Patients List</a>
                        </div>
                    <?php endif; ?>
                    <?php
                    if (isset($_SESSION['errorUpdate'])) {
                        echo '<div class="message-box message-error">';
                        foreach ($_SESSION['errorUpdate'] as $error) {
                            echo htmlspecialchars($error) . '<br>';
                        }
                        echo '</div>';
                        unset($_SESSION['errorUpdate']);
                    }
                    if (isset($_SESSION['successUpdate'])) {
                        // echo '<div class="message-box message-success">' . htmlspecialchars($_SESSION['successUpdate']) . '</div>';
                        unset($_SESSION['successUpdate']);
                    }
                    ?>
                </div>
            </section>

            <!-- Doctor Management Section -->
            <section id="content-doctors" class="dashboard-section" style="<?php echo $section === 'doctors' ? '' : 'display: none'; ?>">
                <h2>Doctor Management</h2>
                <p>Add new doctor records, view, or edit existing ones.</p>
                <div class="doctor-management-controls">
                    <a href="?section=doctors&action=add" class="button <?php echo (isset($_GET['action']) && $_GET['action'] == 'add') ? 'active' : 'button-outline'; ?>">Add Doctor</a>
                    <a href="?section=doctors&action=view" class="button <?php echo (!isset($_GET['action']) || $_GET['action'] == 'view') ? 'active' : 'button-outline'; ?>">View Doctors</a>
                </div>

                <div id="addDoctorSection" class="form-section" style="<?php echo ($section === 'doctors' && isset($_GET['action']) && $_GET['action'] == 'add') ? '' : 'display: none'; ?>">
                    <h3>Add New Doctor</h3>
                    <form id="doctorForm" method="POST" action="../../controller/add_doctor_con.php">
                        <input type="hidden" name="action" value="add">
                        <div class="form-group">
                            <label for="doctorName">Full Name</label>
                            <input type="text" id="doctorName" name="doctorName" placeholder="Doctor's Full Name">
                        </div>
                        <div class="form-group">
                            <label for="doctorSpecialty">Specialty</label>
                            <select id="doctorSpecialty" name="doctorSpecialty">
                                <option value="">Select Specialty</option>
                                <?php foreach ($specialties as $specialty): ?>
                                    <option value="<?php echo htmlspecialchars($specialty); ?>"><?php echo htmlspecialchars($specialty); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="doctorContact">Email</label>
                            <input type="email" id="doctorContact" name="doctorContact" placeholder="email@example.com">
                        </div>
                        <div class="form-group">
                            <label for="doctorPhone">Phone Number</label>
                            <input type="tel" id="doctorPhone" name="doctorPhone" placeholder="01756XXXXXX" title="Enter a valid phone number">
                        </div>
                        <div class="form-group">
                            <label for="doctorPassword">Password</label>
                            <input type="password" id="doctorPassword" name="doctorPassword" placeholder="Set password" minlength="8">

                        </div>
                        <div class="form-group">
                            <label for="doctorFees">Consultation Fees ($)</label>
                            <input type="number" id="doctorFees" name="doctorFees" placeholder="e.g., 150" min="0" step="any">
                        </div>
                        <div class="form-group">
                            <label for="doctorInfo">Doctor Info (Short Bio)</label>
                            <textarea id="doctorInfo" name="doctorInfo" rows="3" placeholder="e.g., Dedicated to children's health with 10 years experience..."></textarea>
                        </div>
                        <div class="doctor-availability-group grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="form-group">
                                <label for="doctorFromDay">Available From Day</label>
                                <select id="doctorFromDay" name="doctorFromDay">
                                    <option value="">Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="doctorToDay">Available To Day</label>
                                <select id="doctorToDay" name="doctorToDay">
                                    <option value="">Select Day</option>
                                    <option value="Monday">Monday</option>
                                    <option value="Tuesday">Tuesday</option>
                                    <option value="Wednesday">Wednesday</option>
                                    <option value="Thursday">Thursday</option>
                                    <option value="Friday">Friday</option>
                                    <option value="Saturday">Saturday</option>
                                    <option value="Sunday">Sunday</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="doctorStartTime">Start Time</label>
                                <input type="time" id="doctorStartTime" name="doctorStartTime">
                            </div>
                            <div class="form-group">
                                <label for="doctorEndTime">End Time</label>
                                <input type="time" id="doctorEndTime" name="doctorEndTime">
                            </div>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button" name="adddoctor">Add Doctor</button>
                        </div>
                    </form>
                    <?php
                    if (isset($_SESSION['errorAddDoctor'])) {
                        echo '<div class="message-box message-error">';
                        foreach ($_SESSION['errorAddDoctor'] as $error) {
                            echo htmlspecialchars($error) . '<br>';
                        }
                        echo '</div>';
                        unset($_SESSION['errorAddDoctor']);
                    } elseif (isset($_SESSION['successAddDoctor'])) {
                        echo '<div class="message-box message-success">' . htmlspecialchars($_SESSION['successAddDoctor']) . '</div>';
                        unset($_SESSION['successAddDoctor']);
                    }
                    ?>
                </div>

                <div id="editDoctorSection" class="form-section" style="<?php echo ($doctorToEdit) ? '' : 'display: none'; ?>">
                    <h3>Edit Doctor Information</h3>
                    <?php if ($doctorToEdit): ?>
                        <form id="editDoctorForm" method="POST" action="../../controller/add_doctor_con.php">
                            <input type="hidden" name="action" value="update_doctor">
                            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctorToEdit['did']); ?>">
                            <div class="form-group">
                                <label for="editDoctorName">Full Name</label>
                                <input type="text" id="editDoctorName" name="doctorName" value="<?php echo htmlspecialchars($doctorToEdit['fname']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editDoctorSpecialty">Specialty</label>
                                <select id="editDoctorSpecialty" name="doctorSpecialty" required>
                                    <option value="">Select Specialty</option>
                                    <?php foreach ($specialties as $specialty): ?>
                                        <option value="<?php echo htmlspecialchars($specialty); ?>" <?php if ($doctorToEdit['specialty'] === $specialty) echo "selected"; ?>>
                                            <?php echo htmlspecialchars($specialty); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editDoctorContact">Email</label>
                                <input type="email" id="editDoctorContact" name="doctorContact" value="<?php echo htmlspecialchars($doctorToEdit['email']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editDoctorPhone">Phone Number</label>
                                <input type="tel" id="editDoctorPhone" name="doctorPhone" value="<?php echo htmlspecialchars($doctorToEdit['phone']); ?>" title="Enter a valid phone number" required>
                            </div>
                            <div class="form-group">
                                <label for="editDoctorFees">Consultation Fees ($)</label>
                                <input type="number" id="editDoctorFees" name="doctorFees" value="<?php echo htmlspecialchars($doctorToEdit['fees']); ?>" required min="0" step="any">
                            </div>
                            <div class="form-group">
                                <label for="editDoctorInfo">Doctor Info (Short Bio)</label>
                                <textarea id="editDoctorInfo" name="doctorInfo" rows="3" required><?php echo htmlspecialchars($doctorToEdit['info']); ?></textarea>
                            </div>
                            <div class="doctor-availability-group grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="form-group">
                                    <label for="editDoctorFromDay">Available From Day</label>
                                    <select id="editDoctorFromDay" name="doctorFromDay" required>
                                        <option value="">Select Day</option>
                                        <option value="Monday" <?php if ($doctorToEdit['fromDay'] === "Monday") echo "selected"; ?>>Monday</option>
                                        <option value="Tuesday" <?php if ($doctorToEdit['fromDay'] === "Tuesday") echo "selected"; ?>>Tuesday</option>
                                        <option value="Wednesday" <?php if ($doctorToEdit['fromDay'] === "Wednesday") echo "selected"; ?>>Wednesday</option>
                                        <option value="Thursday" <?php if ($doctorToEdit['fromDay'] === "Thursday") echo "selected"; ?>>Thursday</option>
                                        <option value="Friday" <?php if ($doctorToEdit['fromDay'] === "Friday") echo "selected"; ?>>Friday</option>
                                        <option value="Saturday" <?php if ($doctorToEdit['fromDay'] === "Saturday") echo "selected"; ?>>Saturday</option>
                                        <option value="Sunday" <?php if ($doctorToEdit['toDay'] === "Sunday") echo "selected"; ?>>Sunday</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editDoctorToDay">Available To Day</label>
                                    <select id="editDoctorToDay" name="doctorToDay" required>
                                        <option value="">Select Day</option>
                                        <option value="Monday" <?php if ($doctorToEdit['toDay'] === "Monday") echo "selected"; ?>>Monday</option>
                                        <option value="Tuesday" <?php if ($doctorToEdit['toDay'] === "Tuesday") echo "selected"; ?>>Tuesday</option>
                                        <option value="Wednesday" <?php if ($doctorToEdit['toDay'] === "Wednesday") echo "selected"; ?>>Wednesday</option>
                                        <option value="Thursday" <?php if ($doctorToEdit['toDay'] === "Thursday") echo "selected"; ?>>Thursday</option>
                                        <option value="Friday" <?php if ($doctorToEdit['toDay'] === "Friday") echo "selected"; ?>>Friday</option>
                                        <option value="Saturday" <?php if ($doctorToEdit['toDay'] === "Saturday") echo "selected"; ?>>Saturday</option>
                                        <option value="Sunday" <?php if ($doctorToEdit['toDay'] === "Sunday") echo "selected"; ?>>Sunday</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="editDoctorStartTime">Start Time</label>
                                    <input type="time" id="editDoctorStartTime" name="doctorStartTime" value="<?php echo htmlspecialchars($doctorToEdit['startTime']); ?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="editDoctorEndTime">End Time</label>
                                    <input type="time" id="editDoctorEndTime" name="doctorEndTime" value="<?php echo htmlspecialchars($doctorToEdit['endTime']); ?>" required>
                                </div>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="button button-secondary" name="savechange">Save Changes</button>
                                <a href="?section=doctors&action=view" class="button button-outline">Cancel</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="message-box message-error" style="display:block;">Doctor data not found. Please select a valid doctor to edit.</p>
                        <div class="form-actions">
                            <a href="?section=doctors&action=view" class="button button-outline">Back to Doctors List</a>
                        </div>
                    <?php endif; ?>
                    <?php
                    if (isset($_SESSION['errorEditDoctor'])) {
                        echo '<div class="message-box message-error">';
                        foreach ($_SESSION['errorEditDoctor'] as $error) {
                            echo htmlspecialchars($error) . '<br>';
                        }
                        echo '</div>';
                        unset($_SESSION['errorEditDoctor']);
                    } elseif (isset($_SESSION['successEditDoctor'])) {
                        echo '<div class="message-box message-success">' . htmlspecialchars($_SESSION['successEditDoctor']) . '</div>';
                        unset($_SESSION['successEditDoctor']);
                    }
                    ?>
                </div>

                <div id="viewDoctorsSection" style="<?php echo ($section === 'doctors' && !isset($_GET['action'])) || ($section === 'doctors' && $_GET['action'] == 'view') ? '' : 'display: none'; ?>">
                    <h3>Doctor List</h3>
                    <div class="table-container">
                        <table class="data-table" id="doctorTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Specialty</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Fees ($)</th>
                                    <th>Info</th>
                                    <th>Availability</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (empty($doctors)) {
                                    echo '<tr><td colspan="9" style="text-align: center; padding: 2rem;">No doctor records found.</td></tr>';
                                } else {
                                    foreach ($doctors as $doctor) {
                                        echo '<tr>';
                                        echo '<td>' . htmlspecialchars($doctor['did']) . '</td>';
                                        echo '<td>' . htmlspecialchars($doctor['fname']) . '</td>';
                                        echo '<td>' . htmlspecialchars($doctor['specialty']) . '</td>';
                                        echo '<td>' . htmlspecialchars($doctor['email']) . '</td>';
                                        echo '<td>' . htmlspecialchars($doctor['phone']) . '</td>';
                                        echo '<td>$' . htmlspecialchars($doctor['fees']) . '</td>';
                                        echo '<td>' . htmlspecialchars($doctor['info']) . '</td>'; // Separated Info
                                        echo '<td>' . htmlspecialchars($doctor['availablity']) . '</td>'; // Separated Availability
                                        echo '<td><div class="action-buttons">';
                                        echo '<a href="?section=doctors&action=edit_doctor&id=' . htmlspecialchars($doctor['did']) . '" class="button button-outline">Edit</a>';
                                        // Note: confirmAction JavaScript function would need to be defined elsewhere
                                        echo '<button class="button button-danger" onclick="deleteDoctor(\'' . htmlspecialchars($doctor['did']) . '\')">Delete</button>';
                                        echo '</div></td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <form id="deleteDoctorForm" method="POST" action="../../controller/delete_control.php" style="display: none;">
                        <input type="hidden" name="doctor_id" id="deleteDoctorId" />
                    </form>
                </div>
            </section>


            <!-- Appointment Management Section -->
            <section id="content-appointments" class="dashboard-section" style="<?php echo $section === 'appointments' ? '' : 'display: none'; ?>">

                <div id="viewAppointmentsSection" style="<?php echo (!isset($_GET['action']) || $_GET['action'] == 'view' || ($section === 'appointments' && !($patientToEdit || (isset($_GET['action']) && $_GET['action'] == 'create')))) ? '' : 'display: none'; ?>">
                    <h3>All Appointments</h3>
                    <div class="table-container">
                        <table class="data-table" id="appointmentTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient Id</th>
                                    <th>Doctor ID</th>
                                    <th>Appointment Date</th>
                                    <th>AppointmentTime</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($appointments as $appt) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($appt['aid']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['patient_id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['doctor_id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['appointment_date']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['appointment_time']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['status']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['created_at']) . '</td>';
                                    echo '<td><div class="action-buttons">';
                                    echo '<button class="button" onclick="updateAppointmentStatus(\'' . htmlspecialchars($appt['aid'], ENT_QUOTES) . '\', \'Confirmed\')">Confirm</button>';
                                    echo '<button class="button button-danger" onclick="deleteAppointment(\'' . htmlspecialchars($appt['aid']) . '\')">Delete</button>';
                                    echo '</div></td>';
                                    echo '</tr>';
                                }
                                if (empty($appointments)) {
                                    echo '<tr><td colspan="8" style="text-align: center; padding: 2rem;">No appointments found.</td></tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <form id="deleteAppointmentForm" method="POST" action="../../controller/delete_control.php" style="display: none;">
                        <input type="hidden" name="appointment_id" id="deleteAppointmentId" />
                    </form>
                </div>
            </section>



            <!-- Admin Profile Section -->
            <section id="content-profile" class="dashboard-section" style="<?php echo $section === 'profile' ? '' : 'display: none'; ?>">
                <h2>Admin Profile Settings</h2>
                <p>Manage your account details and security.</p>

                <div class="form-section">
                    <h3>Admin Information</h3>
                    <form id="profileForm" method="POST" action="../../controller/admin_profile_con.php">
                        <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>" />
                        <div class="form-group">
                            <label for="adminName">Admin Name</label>
                            <input type="text" id="profileName" name="admin_name" value="<?php echo htmlspecialchars($admin_fname); ?>" <?php echo $editMode ? '' : 'disabled'; ?> required />

                        </div>
                        <div class="form-group">
                            <label for="adminEmail">Email</label>
                            <input type="email" id="profileEmail" name="admin_email" value="<?php echo htmlspecialchars($admin_email); ?>" <?php echo $editMode ? '' : 'disabled'; ?> required />

                        </div>
                        <div class="profile-action-buttons">
                            <?php if (!$editMode): ?>
                                <a href="?section=profile&edit=1" class="button" id="editProfileBtn">Edit Details</a>
                            <?php else: ?>
                                <button type="submit" class="button button-secondary" id="saveProfileBtn" name="submit-btn">Save Changes</button>
                                <a href="?section=profile" class="button button-outline" id="cancelProfileBtn">Cancel</a>
                            <?php endif; ?>
                        </div>
                    </form>
                    <div id="adminProfileMessage" class="message-box" style="display: none;"></div>
                </div>


                <div class="form-section">
                    <h3>Change Password</h3>
                    <form id="passwordChangeForm" method="post" action="../../controller/change_admin_pass_con.php">
                        <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>" />
                        <div class="form-group">
                            <label for="currentPassword">Current Password:</label>
                            <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter your current password" required autocomplete="off" />
                        </div>
                        <div class="form-group">
                            <label for="newPassword">New Password:</label>
                            <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password (min 8 chars)" required autocomplete="off" minlength="8" />
                        </div>
                        <div class="form-group">
                            <label for="confirmNewPassword">Confirm New Password:</label>
                            <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm new password" required autocomplete="off" minlength="8" />
                        </div>
                        <button type="submit" class="button">Change Password</button>
                    </form>
                    <?php
                    // Display password change messages
                    if (isset($_SESSION['password_change_message'])) {
                        echo '<div id="passwordMessage" class="message-box ' . htmlspecialchars($_SESSION['password_change_message_type']) . '">' . htmlspecialchars($_SESSION['password_change_message']) . '</div>';
                        unset($_SESSION['password_change_message']);
                        unset($_SESSION['password_change_message_type']);
                    } else {
                        echo '<div id="passwordMessage" class="message-box"></div>';
                    }
                    ?>
                </div>
            </section>

            <!-- Logout Section -->
            <section id="content-logout" class="dashboard-section" style="<?php echo $section === 'logout' ? '' : 'display: none'; ?>">
                <h2>Logging Out...</h2>
                <?php
                session_destroy(); // Destroy all session data
                // Clear the admin_id from cookie if you use it for persistence
                if (isset($_COOKIE['admin_id'])) {
                    setcookie('admin_id', '', time() - 3600, '/');
                }
                header("refresh:2;url=../../view/login/login.php"); // Redirect to a generic login page
                ?>
                <p>Redirecting to login...</p>
            </section>
        </main>
    </div>

    <div class="overlay" id="overlay"></div>

    <!-- <footer>
        <div class="container">
            <p>&copy; 2025 MedCare Hospital. Admin Panel. All rights reserved.</p>
        </div>
    </footer> -->


    <script src="../../assets/admin/adminDash.js"></script>
    <!-- <script src="../../assets/admin/statusChange.js"></script> -->
</body>

</html>