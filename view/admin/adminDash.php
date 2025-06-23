<?php
session_start();
require_once '../../config/connection.php';

$admin_id = 'ADMIN001';
$admin_fname = 'Admin User';
$admin_email = 'admin@medcare.com';
$admin_phone = '+1234567890';
$admin_specialty = 'Administrator';
$admin_address = '1 Admin Plaza, MedCity';
$admin_availability = 'Mon-Fri: 9 AM - 5 PM';
$admin_password_hash = password_hash('defaultadminpass', PASSWORD_DEFAULT); // Dummy hashed password



// Determine the current section for navigation
$section = isset($_GET['section']) ? $_GET['section'] : 'home';
$editMode = isset($_GET['edit']) && $_GET['edit'] == '1'; // For admin profile edit mode





// Dummy doctor data for demonstration. In a real app, this would be fetched from DB.


// Dummy data for doctor to edit (simulating fetching from DB based on ID)



$appointments = [ // Dummy Data
    ['id' => 'A001', 'patientId' => 'P001', 'patientName' => 'Alice Johnson', 'doctorId' => 'D001', 'doctorName' => 'Dr. Emily Watson', 'date' => '2025-06-20', 'time' => '10:00', 'status' => 'Confirmed', 'paymentStatus' => 'Paid'],
    ['id' => 'A002', 'patientId' => 'P002', 'patientName' => 'Bob Williams', 'doctorId' => 'D002', 'doctorName' => 'Dr. John Smith', 'date' => '2025-06-20', 'time' => '14:30', 'status' => 'Pending', 'paymentStatus' => 'Pending'],
];
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


// Fetch patients data
$query_patients = "SELECT p.pid, p.fname, p.email, p.address, p.gender, p.dob, l.phone
          FROM patientsregister AS p
          JOIN login AS l ON p.pid = l.user_ref_id
          WHERE l.role = 'patient'";

$result_patients = mysqli_query($conn, $query_patients);
$patients = [];

if ($result_patients && mysqli_num_rows($result_patients) > 0) {
    while ($row = mysqli_fetch_assoc($result_patients)) {
        $patients[] = $row;
    }
} else {
    // Handle case where no patients are found
    $patients = [];
}
/* For debugging: echo patients array */
// echo '<pre>'; print_r($patients); echo '</pre>';

// --- Logic for Patient Edit Section ---
$patientEditAction = isset($_GET['action']) && $_GET['action'] === 'edit_patient';
$patientToEdit = null;
if ($section === 'patients' && $patientEditAction && isset($_GET['id'])) {
    $patientEditId = htmlspecialchars($_GET['id']);
    foreach ($patients as $patient) {
        if ($patient['pid'] === $patientEditId) {
            $patientToEdit = $patient;
            break;
        }
    }
}

$query_doctors = "SELECT d.did, d.fname, d.specialty, d.email,d.fees,d.info, d.availablity,l.phone 
                    FROM doctorregister AS d JOIN login AS l ON d.did = l.user_ref_id WHERE l.role = 'doctor';";
$result_doctors = mysqli_query($conn, $query_doctors);
$doctors = [];
if ($result_doctors && mysqli_num_rows($result_doctors) > 0) {
    while ($row = mysqli_fetch_assoc($result_doctors)) {
        $doctors[] = $row;
    }
} else {
    // Handle case where no doctors are found
    $doctors = [];
}

// echo '<pre>';
// print_r($doctors);
// echo '</pre>';

// --- Logic for Doctor Edit Section ---
$doctorEditAction = (isset($_GET['action']) && $_GET['action'] == 'edit_doctor' && isset($_GET['id']));
$doctorToEdit = null;
if ($doctorEditAction) { // Only attempt to find doctor if edit action is valid
    $editId = $_GET['id'];
    foreach ($doctors as $doctor) {
        if ($doctor['id'] == $editId) {
            $doctorToEdit = $doctor;
            break;
        }
    }
}


// --- Logic for Appointment Edit Section ---
$appointmentEditAction = isset($_GET['action']) && $_GET['action'] === 'edit_appointment';
$appointmentToEdit = null;
if ($section === 'appointments' && $appointmentEditAction && isset($_GET['id'])) {
    $appointmentEditId = htmlspecialchars($_GET['id']);
    // In a real application, you'd fetch this from the database:
    // require_once '../../model/appointment_mod.php';
    // $appointmentToEdit = get_appointment_by_id($conn, $appointmentEditId);

    foreach ($appointments as $appointment) {
        if ($appointment['id'] === $appointmentEditId) {
            $appointmentToEdit = $appointment;
            break;
        }
    }
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
                <li><a href="?section=profile" class="menu-item <?php echo $section === 'profile' ? 'active' : ''; ?>"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
                <li><a href="?section=logout" class="menu-item <?php echo $section === 'logout' ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
                <p>View patient records. Patient creation, editing, and deletion are handled via the backend system.</p>

                <div id="viewPatientsSection" style="<?php echo ($section === 'patients' && !$patientEditAction) ? '' : 'display: none'; ?>">
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
                                    echo '<button class="button button-danger" onclick="confirmAction(\'' . htmlspecialchars($patient['pid']) . '\')">Delete</button>';
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
                    <form id="deleteForm" method="POST" action="../../controller/delete_patient.php" style="display: none;">
                        <input type="hidden" name="patient_id" id="deletePatientId" />
                    </form>
                </div>

                <div id="editPatientSection" class="form-section" style="<?php echo ($section === 'patients' && $patientEditAction) ? '' : 'display: none'; ?>">
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
                            <input type="tel" id="doctorPhone" name="doctorPhone" placeholder="+1234567890" title="Enter a valid phone number">
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
                            <button type="submit" class="button">Add Doctor</button>
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

                <div id="editDoctorSection" class="form-section" style="<?php echo ($doctorEditAction) ? '' : 'display: none'; ?>">
                    <h3>Edit Doctor Information</h3>
                    <?php if ($doctorToEdit): ?>
                        <form id="editDoctorForm" method="POST" action="../../controller/doctor_con.php">
                            <input type="hidden" name="action" value="update_doctor">
                            <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctorToEdit['id']); ?>">
                            <div class="form-group">
                                <label for="editDoctorName">Full Name</label>
                                <input type="text" id="editDoctorName" name="doctorName" value="<?php echo htmlspecialchars($doctorToEdit['name']); ?>" required>
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
                                <input type="email" id="editDoctorContact" name="doctorContact" value="<?php echo htmlspecialchars($doctorToEdit['contact']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editDoctorPhone">Phone Number</label>
                                <input type="tel" id="editDoctorPhone" name="doctorPhone" value="<?php echo htmlspecialchars($doctorToEdit['phone']); ?>" pattern="[0-9+\-()\s]+" title="Enter a valid phone number" required>
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
                                <button type="submit" class="button button-secondary">Save Changes</button>
                                <a href="?section=doctors&action=view" class="button button-outline">Cancel</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="message-box message-error" style="display:block;">Doctor data not found. Please select a valid doctor to edit.</p>
                        <div class="form-actions">
                            <a href="?section=doctors&action=view" class="button button-outline">Back to Doctors List</a>
                        </div>
                    <?php endif; ?>
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
                                        echo '<button class="button button-danger" onclick="confirmAction(\'delete_doctor\', \'' . htmlspecialchars($doctor['did']) . '\')">Delete</button>';
                                        echo '</div></td>';
                                        echo '</tr>';
                                    }
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </section>


            <!-- Appointment Management Section -->
            <section id="content-appointments" class="dashboard-section" style="<?php echo $section === 'appointments' ? '' : 'display: none'; ?>">
                <h2>Appointment Management</h2>
                <p>Create new appointments or view existing ones.</p>
                <div class="appointment-management-controls">
                    <a href="?section=appointments&action=create" class="button <?php echo (isset($_GET['action']) && $_GET['action'] == 'create') ? 'active' : 'button-outline'; ?>">Create Appointment</a>
                    <a href="?section=appointments&action=view" class="button <?php echo (!isset($_GET['action']) || $_GET['action'] == 'view') ? 'active' : 'button-outline'; ?>">View Appointments</a>
                </div>

                <div id="createAppointmentSection" class="form-section" style="<?php echo ($section === 'appointments' && isset($_GET['action']) && $_GET['action'] == 'create') ? '' : 'display: none'; ?>">
                    <h3>Create New Appointment</h3>
                    <form id="appointmentForm" method="POST" action="../../controller/appointment_con.php">
                        <input type="hidden" name="action" value="create">
                        <div class="form-group">
                            <label for="appointmentPatient">Patient</label>
                            <select id="appointmentPatient" name="appointmentPatient" required>
                                <option value="">Select Patient</option>
                                <?php
                                $allPatients = [ // Dummy Data for dropdowns
                                    ['id' => 'P001', 'name' => 'Alice Johnson'],
                                    ['id' => 'P002', 'name' => 'Bob Williams'],
                                ];
                                foreach ($allPatients as $p) {
                                    echo '<option value="' . htmlspecialchars($p['id']) . '">' . htmlspecialchars($p['name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="appointmentDoctor">Doctor</label>
                            <select id="appointmentDoctor" name="appointmentDoctor" required>
                                <option value="">Select Doctor</option>
                                <?php
                                $allDoctors = [ // Dummy Data for dropdowns
                                    ['id' => 'D001', 'name' => 'Dr. Emily Watson'],
                                    ['id' => 'D002', 'name' => 'Dr. John Smith'],
                                ];
                                foreach ($allDoctors as $d) {
                                    echo '<option value="' . htmlspecialchars($d['id']) . '">' . htmlspecialchars($d['name']) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="appointmentDate">Date</label>
                            <input type="date" id="appointmentDate" name="appointmentDate" required>
                        </div>
                        <div class="form-group">
                            <label for="appointmentTime">Time</label>
                            <input type="time" id="appointmentTime" name="appointmentTime" required>
                        </div>
                        <div class="form-group">
                            <label for="appointmentStatus">Status</label>
                            <select id="appointmentStatus" name="appointmentStatus" required>
                                <option value="Pending">Pending</option>
                                <option value="Confirmed">Confirmed</option>
                                <option value="Completed">Completed</option>
                                <option value="Cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="appointmentPaymentStatus">Payment Status</label>
                            <select id="appointmentPaymentStatus" name="appointmentPaymentStatus" required>
                                <option value="Pending">Pending</option>
                                <option value="Paid">Paid</option>
                                <option value="Partially Paid">Partially Paid</option>
                                <option value="Waived">Waived</option>
                            </select>
                        </div>
                        <div class="form-actions">
                            <button type="submit" class="button">Create Appointment</button>
                        </div>
                    </form>
                    <?php
                    if (isset($_SESSION['appointment_message'])) {
                        echo '<div id="appointmentFormMessage" class="message-box ' . htmlspecialchars($_SESSION['appointment_message_type']) . '">' . htmlspecialchars($_SESSION['appointment_message']) . '</div>';
                        unset($_SESSION['appointment_message']);
                        unset($_SESSION['appointment_message_type']);
                    }
                    ?>
                </div>

                <div id="editAppointmentSection" class="form-section" style="<?php echo ($section === 'appointments' && $appointmentEditAction) ? '' : 'display: none'; ?>">
                    <h3>Edit Appointment Information</h3>
                    <?php if ($appointmentToEdit): ?>
                        <form id="editAppointmentForm" method="POST" action="../../controller/appointment_con.php">
                            <input type="hidden" name="action" value="update_appointment">
                            <input type="hidden" name="appointment_id" value="<?php echo htmlspecialchars($appointmentToEdit['id']); ?>">
                            <div class="form-group">
                                <label for="editAppointmentPatient">Patient</label>
                                <select id="editAppointmentPatient" name="appointmentPatient" required>
                                    <option value="">Select Patient</option>
                                    <?php
                                    foreach ($allPatients as $p) {
                                        $selected = ($p['id'] === $appointmentToEdit['patientId']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($p['id']) . '" ' . $selected . '>' . htmlspecialchars($p['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editAppointmentDoctor">Doctor</label>
                                <select id="editAppointmentDoctor" name="appointmentDoctor" required>
                                    <option value="">Select Doctor</option>
                                    <?php
                                    foreach ($allDoctors as $d) {
                                        $selected = ($d['id'] === $appointmentToEdit['doctorId']) ? 'selected' : '';
                                        echo '<option value="' . htmlspecialchars($d['id']) . '" ' . $selected . '>' . htmlspecialchars($d['name']) . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editAppointmentDate">Date</label>
                                <input type="date" id="editAppointmentDate" name="appointmentDate" value="<?php echo htmlspecialchars($appointmentToEdit['date']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editAppointmentTime">Time</label>
                                <input type="time" id="editAppointmentTime" name="appointmentTime" value="<?php echo htmlspecialchars($appointmentToEdit['time']); ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="editAppointmentStatus">Status</label>
                                <select id="editAppointmentStatus" name="appointmentStatus" required>
                                    <option value="Pending" <?php if ($appointmentToEdit['status'] === "Pending") echo "selected"; ?>>Pending</option>
                                    <option value="Confirmed" <?php if ($appointmentToEdit['status'] === "Confirmed") echo "selected"; ?>>Confirmed</option>
                                    <option value="Completed" <?php if ($appointmentToEdit['status'] === "Completed") echo "selected"; ?>>Completed</option>
                                    <option value="Cancelled" <?php if ($appointmentToEdit['status'] === "Cancelled") echo "selected"; ?>>Cancelled</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="editAppointmentPaymentStatus">Payment Status</label>
                                <select id="editAppointmentPaymentStatus" name="appointmentPaymentStatus" required>
                                    <option value="Pending" <?php if ($appointmentToEdit['paymentStatus'] === "Pending") echo "selected"; ?>>Pending</option>
                                    <option value="Paid" <?php if ($appointmentToEdit['paymentStatus'] === "Paid") echo "selected"; ?>>Paid</option>
                                    <option value="Partially Paid" <?php if ($appointmentToEdit['paymentStatus'] === "Partially Paid") echo "selected"; ?>>Partially Paid</option>
                                    <option value="Waived" <?php if ($appointmentToEdit['paymentStatus'] === "Waived") echo "selected"; ?>>Waived</option>
                                </select>
                            </div>
                            <div class="form-actions">
                                <button type="submit" class="button button-secondary">Save Changes</button>
                                <a href="?section=appointments&action=view" class="button button-outline">Cancel</a>
                            </div>
                        </form>
                    <?php else: ?>
                        <p class="message-box message-error" style="display:block;">Appointment data not found. Please select a valid appointment to edit.</p>
                        <div class="form-actions">
                            <a href="?section=appointments&action=view" class="button button-outline">Back to Appointments List</a>
                        </div>
                    <?php endif; ?>
                </div>

                <div id="viewAppointmentsSection" style="<?php echo (!isset($_GET['action']) || $_GET['action'] == 'view' || ($section === 'appointments' && !($appointmentEditAction || (isset($_GET['action']) && $_GET['action'] == 'create')))) ? '' : 'display: none'; ?>">
                    <h3>All Appointments</h3>
                    <div class="table-container">
                        <table class="data-table" id="appointmentTable">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Patient</th>
                                    <th>Doctor</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Status</th>
                                    <th>Payment Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($appointments as $appt) {
                                    echo '<tr>';
                                    echo '<td>' . htmlspecialchars($appt['id']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['patientName']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['doctorName']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['date']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['time']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['status']) . '</td>';
                                    echo '<td>' . htmlspecialchars($appt['paymentStatus']) . '</td>';
                                    echo '<td><div class="action-buttons">';
                                    echo '<a href="?section=appointments&action=edit_appointment&id=' . htmlspecialchars($appt['id']) . '" class="button button-outline">Edit</a>';
                                    echo '<button class="button button-danger" onclick="alert(\'Delete Appointment ID: ' . htmlspecialchars($appt['id']) . ' - Backend action\');">Delete</button>';
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
                </div>
            </section>

            <!-- Admin Profile Section -->
            <section id="content-profile" class="dashboard-section" style="<?php echo $section === 'profile' ? '' : 'display: none'; ?>">
                <h2>Profile Settings</h2>
                <p>Update your personal information and manage your account security.</p>

                <div class="profile-section">
                    <h3>Admin Information</h3>
                    <form id="profileForm" method="POST" action="../../controller/admin_profile_con.php">
                        <input type="hidden" name="admin_id" value="<?php echo htmlspecialchars($admin_id); ?>" />

                        <div class="profile-form-row">
                            <div class="form-group">
                                <label for="profileName">Full Name:</label>
                                <input type="text" id="profileName" name="admin_name" value="<?php echo htmlspecialchars($admin_fname); ?>" <?php echo $editMode ? '' : 'disabled'; ?> required />
                            </div>
                            <div class="form-group">
                                <label for="profilePhone">Phone Number:</label>
                                <input type="tel" id="profilePhone" name="admin_phone" value="<?php echo htmlspecialchars($admin_phone); ?>" <?php echo $editMode ? '' : 'disabled'; ?> pattern="[+]?[0-9]{10,15}" title="Phone number must be 10-15 digits, optionally starting with a plus sign." required />
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="profileEmail">Email:</label>
                            <input type="email" id="profileEmail" name="admin_email" value="<?php echo htmlspecialchars($admin_email); ?>" <?php echo $editMode ? '' : 'disabled'; ?> required />
                        </div>

                        <div class="form-group">
                            <label for="profileAddress">Address:</label>
                            <textarea id="profileAddress" rows="2" name="admin_address" <?php echo $editMode ? '' : 'disabled'; ?>><?php echo htmlspecialchars($admin_address); ?></textarea>
                        </div>

                        <div class="form-group">
                            <label for="profileSpecialty">Role/Specialty:</label>
                            <input type="text" id="profileSpecialty" name="admin_specialty" value="<?php echo htmlspecialchars($admin_specialty); ?>" <?php echo $editMode ? '' : 'disabled'; ?> required />
                        </div>

                        <div class="form-group">
                            <label for="profileAvailability">Availability:</label>
                            <textarea id="profileAvailability" rows="2" name="admin_availability" <?php echo $editMode ? '' : 'disabled'; ?>><?php echo htmlspecialchars($admin_availability); ?></textarea>
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
                    <?php
                    // Display profile update messages
                    if (isset($_SESSION['profile_message'])) {
                        echo '<div id="profileMessage" class="message-box ' . htmlspecialchars($_SESSION['profile_message_type']) . '">' . htmlspecialchars($_SESSION['profile_message']) . '</div>';
                        unset($_SESSION['profile_message']);
                        unset($_SESSION['profile_message_type']);
                    } else {
                        echo '<div id="profileMessage" class="message-box"></div>';
                    }
                    ?>
                </div>

                <div class="password-change-section">
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
</body>

</html>