<script>
  // Check if the page was loaded from the browser cache
  window.addEventListener('pageshow', function(event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>

<?php
session_start();

if (!isset($_COOKIE['doctor_id'])) {
  header("Location: ../login/login.php");
  exit();
}

require_once '../../model/doctor_model.php';
require_once '../../model/time_formate.php';

if (isset($_COOKIE['doctor_id'])) {
  $doctor_info = get_doctor_data($_COOKIE['doctor_id']);

  if ($doctor_info) {
    $doctor_did = $doctor_info['did'];
    $doctor_fname = $doctor_info['fname'];
    $doctor_specialty = $doctor_info['specialty'];
    $doctor_email = $doctor_info['email'];
    $doctor_phone = $doctor_info['phone'] ?? 'N/A';
    $doctor_info_bio = $doctor_info['info'] ?? 'No bio available.';
    $doctor_availability = format_availability($doctor_info['availablity']) ?? 'Not specified';
  }
}


$currentDoctorId = $_COOKIE['doctor_id'];


// Determine the current section for navigation
$section = isset($_GET['section']) ? $_GET['section'] : 'home';
$sub_section = isset($_GET['sub_section']) ? $_GET['sub_section'] : ''; // For appointments/patients/records/medicines sub-views
$patient_id_for_records = isset($_GET['patient_id']) ? htmlspecialchars($_GET['patient_id']) : null; // For viewing specific patient records/prescriptions
$medicine_id_for_prescription = isset($_GET['medicine_id']) ? htmlspecialchars($_GET['medicine_id']) : null; // For directly prescribing a medicine
$editMode = isset($_GET['edit']) && $_GET['edit'] == 'profile'; // For editing patient records or prescriptions



$appointments = doctor_appointments($currentDoctorId);
$today_doctor_appointments = $appointments['todayAppointments'];
$upcoming_doctor_appointments = $appointments['upcomingAppointments'];
$past_doctor_appointments = $appointments['pastAppointments'];

$today_appointments_count = !empty($today_doctor_appointments) ? count($today_doctor_appointments) : 0;
$upcoming_appointments_count = !empty($upcoming_doctor_appointments) ? count($upcoming_doctor_appointments) : 0;
$past_appointments_count = !empty($past_doctor_appointments) ? count($past_doctor_appointments) : 0;

$all_patients_data = get_all_patients_data($currentDoctorId);

if (!$all_patients_data) {
  $all_patients_data = []; // Ensure it's an empty array if no data found
}
// echo '<pre>';
// print_r($appointments);
// echo '</pre>';

$appointment_id = isset($_GET['appoint_id']) ? $_GET['appoint_id'] : null;
$current_patient_info_for_records = get_current_patients_data($appointment_id);

// echo '<pre>';
// print_r($current_patient_info_for_records);
// echo '</pre>';

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Doctor Dashboard - MedCare Hospital</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/doctor/doctorDash.css" />
</head>

<body>
  <header>
    <div class="container header-container">
      <div class="menu-toggle" id="menuToggle">
        <i class="fas fa-bars"></i>
      </div>
      <a href="#" class="logo">MedCare Hospital</a>
      <div class="doctor-info">
        <i class="fas fa-user-md"></i>
        <span>Welcome,<?php echo htmlspecialchars($doctor_fname); ?>!</span>
      </div>

    </div>
  </header>

  <div class="main-wrapper">
    <div class="main-content-area container">
      <aside class="sidebar" id="sidebar">
        <h3>Doctor Menu</h3>
        <ul class="sidebar-menu">
          <li><a href="?section=home" class="menu-item <?php echo $section === 'home' ? 'active' : ''; ?>" data-content="home"><i class="fas fa-home"></i> Home</a></li>
          <li><a href="?section=appointments" class="menu-item <?php echo $section === 'appointments' ? 'active' : ''; ?>" data-content="appointments"><i class="fas fa-calendar-alt"></i> My Appointments</a></li>
          <li><a href="?section=patients" class="menu-item <?php echo $section === 'patients' ? 'active' : ''; ?>" data-content="patients"><i class="fas fa-user-injured"></i> My Patients</a></li>
          <li><a href="?section=records" class="menu-item <?php echo $section === 'records' || $section === 'prescriptions' || $section === 'add-prescription' ? 'active' : ''; ?>" data-content="records"><i class="fas fa-file-medical-alt"></i> Patient Records</a></li>
          <li><a href="?section=prescribe_medicines" class="menu-item <?php echo $section === 'prescribe_medicines' ? 'active' : ''; ?>" data-content="prescribe_medicines"><i class="fas fa-pills"></i> Prescribe Medicines</a></li>
          <li><a href="?section=profile" class="menu-item <?php echo $section === 'profile' ? 'active' : ''; ?>" data-content="profile"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
          <li> <a href="../../controller/logoutDoctor.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
        </ul>
      </aside>

      <main class="content-display" id="contentDisplay">
        <!-- Dashboard Home Section -->
        <section id="content-home" class="dashboard-section dashboard-home" style="<?php echo $section === 'home' ? '' : 'display: none'; ?>">
          <h2>Doctor Dashboard Overview</h2>
          <p>Welcome back, <?php echo htmlspecialchars($doctor_fname); ?>! Here's a quick summary of your daily activities.</p>

          <div class="summary-cards">
            <div class="card">
              <h4>Appointments Today</h4>
              <p id="appointmentsTodayCount">
                <?php echo $today_appointments_count; ?>
              </p>
              <a class="button" href="?section=appointments&sub_section=today">View Schedule</a>
            </div>
            <div class="card">
              <h4>Total Patients</h4>
              <p><?php echo $today_appointments_count + $upcoming_appointments_count + $past_appointments_count; ?> </p>
              <a class="button" href="?section=patients">View Patients</a>
            </div>
            <!-- <div class="card">
              <h4>Total Medicines</h4>
              <p></p>
              <a class="button" href="?section=prescribe_medicines">Prescribe Medicines</a>
            </div> -->
            <div class="card">
              <h4>Total Appointments</h4>
              <p>
                <?php echo $today_appointments_count + $upcoming_appointments_count + $past_appointments_count; ?>
              </p>
              <a class="button" href="?section=appointments">View All</a>
            </div>
          </div>

          <div style="margin-top: 3.2rem; text-align: left; background-color: var(--light-bg); padding: 2.4rem; border-radius: var(--border-radius-base); border: 0.1rem solid var(--border-color-light)">
            <h3>Quick Actions</h3>
            <p>
              <a href="?section=appointments" class="button button-secondary">Manage Appointments</a>
              <a href="?section=patients" class="button">Browse Patient List</a>
              <a href="?section=prescribe_medicines" class="button button-outline">Prescribe Medicines</a>
            </p>
          </div>
        </section>

        <!-- Appointment Management Section -->
        <section id="content-appointments" class="dashboard-section" style="<?php echo $section === 'appointments' ? '' : 'display: none'; ?>">
          <h2 id="appointmentsHeading">My Appointments</h2>
          <p>Here you can view and manage your scheduled patient appointments.</p>
          <div class="appointment-management-controls">
            <a href="?section=appointments&action=today" class="button <?php echo (!isset($_GET['action']) || $_GET['action'] == 'today') ? 'active' : 'button-outline'; ?>">Today</a>
            <a href="?section=appointments&action=upcoming" class="button <?php echo (isset($_GET['action']) && $_GET['action'] == 'upcoming') ? 'active' : 'button-outline'; ?>">Upcoming</a>
            <a href="?section=appointments&action=past" class="button <?php echo (isset($_GET['action']) && $_GET['action'] == 'past') ? 'active' : 'button-outline'; ?>">Past</a>
          </div>
          <!-- <input type="text" id="searchBar" onkeyup="filterTable(this)" placeholder="Search by name or phone..." class="aptient-search-controls"> -->
          <div style="<?php echo ($section === 'appointments' && !isset($_GET['action'])) || ($section === 'appointments' && $_GET['action'] == 'today') ? '' : 'display: none'; ?>">
            <div class="appointments-header-controls">
              <div class="appointments-today-summary">
                <h3>Today's Appointments</h3>
                <p id="todayAppointmentsCountDisplay">(<?php echo $today_appointments_count ?>)</p>
              </div>
              <input type="text" id="searchBar" onkeyup="filterTable(this)" placeholder="Search by patient name or ID..." class="patient-search-controls">
            </div>
            <div id="todayAppointmentsContainer" class="table-container">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Patient Phone</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="todayAppointmentsTableBody" class="appointments-table">
                  <!-- Today's appointments will be rendered here by JavaScript -->
                  <?php

                  if (!empty($today_doctor_appointments)) {
                    foreach ($today_doctor_appointments as $appt) {
                      echo '<tr>';
                      echo '<td>' . htmlspecialchars($appt['patientsName']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['patientsPhone']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['appointment_date']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['status']) . '</td>';
                      echo '<td>';
                      echo '<div class="action-buttons">';
                      echo '<a href="?section=add-prescription&patient_id=' . htmlspecialchars($appt['patient_id']) . '&appoint_id=' . htmlspecialchars($appt['aid']) . '" class="button button-outline">Add Prescription</a>';

                      echo '<button class="button button-secondary" onclick="updateAppointmentStatus(\'' . htmlspecialchars($appt['aid']) . '\', \'Completed\')">Complete</button>';

                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No appointments for today.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <div style="<?php echo ($section === 'appointments' && isset($_GET['action']) && $_GET['action'] == 'upcoming') ? '' : 'display: none'; ?>">

            <div class="appointments-header-controls">
              <div class="appointments-today-summary">
                <h3>Upcoming Appointments </h3>
                <p id="todayAppointmentsCountDisplay">(<?php echo $upcoming_appointments_count ?>)</p>
              </div>
              <input type="text" id="searchBar" onkeyup="filterTable(this)" placeholder="Search by patient name or ID..." class="patient-search-controls">
            </div>

            <div id="upcomingAppointmentsContainer" class="table-container" style="margin-top: 2rem">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Patient Phone</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody id="upcomingAppointmentsTableBody" class="appointments-table">
                  <!-- Upcoming appointments will be rendered here by JavaScript -->
                  <?php

                  if (!empty($upcoming_doctor_appointments)) {
                    foreach ($upcoming_doctor_appointments as $appt) {

                      echo '<tr>';
                      echo '<td>' . htmlspecialchars($appt['patientsName']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['patientsPhone']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['appointment_date']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['status']) . '</td>';
                      echo '<td>';
                      echo '<div class="action-buttons">';
                      echo '<button class="button button-outline" onclick="updateAppointmentStatus(\'' . htmlspecialchars($appt['aid']) . '\', \'Cancelled\')">Cancel</button>';
                      echo '</div>';
                      echo '</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No upcoming appointments.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>

          <div style="<?php echo ($section === 'appointments' && isset($_GET['action']) && $_GET['action'] == 'past') ? '' : 'display: none'; ?>">

            <div class="appointments-header-controls">
              <div class="appointments-today-summary">
                <h3>Past Appointments </h3>
                <p id="todayAppointmentsCountDisplay">(<?php echo $past_appointments_count ?>)</p>
              </div>
              <input type="text" id="searchBar" onkeyup="filterTable(this)" placeholder="Search by patient name or ID..." class="patient-search-controls">
            </div>
            <div id="pastAppointmentsContainer" class="table-container" style="margin-top: 2rem">
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Patient Phone</th>
                    <th>Visit Date</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="pastAppointmentsTableBody" class="appointments-table">
                  <!-- Past appointments will be rendered here by JavaScript -->
                  <?php
                  if (!empty($past_doctor_appointments)) {
                    foreach ($past_doctor_appointments as $appt) {
                      echo '<tr>';
                      echo '<td>' . htmlspecialchars($appt['patientsName']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['patientsPhone']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['visit_date']) . '</td>';
                      echo '<td>' . htmlspecialchars($appt['status']) . '</td>';
                      echo '</tr>';
                    }
                  } else {
                    echo '<tr><td colspan="4" style="text-align: center; padding: 2rem;">No past appointments.</td></tr>';
                  }
                  ?>
                </tbody>
              </table>
            </div>
          </div>
          <div id="appointmentMessage" class="message-box"></div>
        </section>

        <!-- My Patients Section -->
        <section id="content-patients" class="dashboard-section" style="<?php echo $section === 'patients' ? '' : 'display: none'; ?>">
          <h2>My Patients</h2>
          <p>View your patient list, search for specific patients, or access their records.</p>

          <div class="patient-search-controls">
            <div class="form-group">
              <label for="patientNameSearch">Search Patient by Name:</label>
              <input type="text" id="searchBar" onkeyup="filterTablePatients(this)" placeholder="Search by name or phone..." class="aptient-search-controls" />
            </div>
            <!-- <div class="form-group">
              <label for="patientFilter">Filter (e.g., Recent Visits):</label>
              <select id="patientFilter">
                <option value="">All Patients</option>
                <option value="Recent">Recently Visited (Last 30 Days)</option>
                <option value="Active">Active Cases</option>
                <option value="Follow-up">Needs Follow-up</option>
              </select>
            </div> -->

          </div>

          <div class="table-container">
            <table class="data-table" id="patientTable">
              <thead>
                <tr>
                  <th>Patient ID</th>
                  <th>Patient Name</th>
                  <th>Patient Phone</th>
                  <th>Last Visit</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody id="patientTableBody">
                <!-- Patient data will be rendered here by JavaScript -->
                <?php
                if (!empty($all_patients_data)) {
                  foreach ($all_patients_data as $patient) {
                    echo '<tr>';
                    echo '<td>' . htmlspecialchars($patient['patient_id']) . '</td>';
                    echo '<td>' . htmlspecialchars($patient['patientsName']) . '</td>';
                    echo '<td>' . htmlspecialchars($patient['patientsPhone']) . '</td>';
                    echo '<td>' . htmlspecialchars($patient['visit_date']) . '</td>';
                    echo '<td>';
                    echo '<div class="action-buttons">';
                    echo '<a href="?section=records&appoint_id=' . htmlspecialchars($patient['aid']) . '" class="button button-secondary">View Records<br>Add Prescription</a>';

                    echo '</div>';
                    echo '</td>';
                    echo '</tr>';
                  }
                } else {
                  echo '<tr><td colspan="4" style="text-align: center; padding: 2rem;">No patients assigned or found.</td></tr>';
                }
                ?>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Patient Medical Records Section -->
        <section id="content-records" class="dashboard-section" style="<?php echo $section === 'records' ? '' : 'display: none'; ?>">
          <h2>Patient Medical Records</h2>
          <?php if ($appointment_id && $current_patient_info_for_records): ?>
            <p>Access detailed medical records for patient: <strong><?php echo htmlspecialchars($current_patient_info_for_records['patientsName']); ?></strong></p>
            <div class="patient-records-details">
              <h3>Patient Details</h3>
              <p><strong>Name:</strong> <?php echo htmlspecialchars($current_patient_info_for_records['patientsName'] ?? 'N/A'); ?></p>
              <p><strong>Phone:</strong> <?php echo htmlspecialchars($current_patient_info_for_records['patientsPhone'] ?? 'N/A'); ?></p>
              <p><strong>Email:</strong> <?php echo htmlspecialchars($current_patient_info_for_records['email'] ?? 'N/A'); ?></p>
              <p><strong>Address:</strong> <?php echo htmlspecialchars($current_patient_info_for_records['address'] ?? 'N/A'); ?></p>
              <p><strong>Gender:</strong> <?php echo htmlspecialchars($current_patient_info_for_records['patients_gender'] ?? 'N/A'); ?></p>
            </div>
            <div class="action-area" style="justify-content: flex-start; margin-top: 2rem;">
              <a href="?section=prescriptions&patient_id=<?php echo htmlspecialchars($patient_id_for_records); ?>" class="button button-secondary">View Prescriptions</a>
              <a href="?section=add-prescription&patient_id=<?php echo htmlspecialchars($patient_id_for_records); ?>" class="button button-outline">Add New Prescription</a>
              <a href="?section=patients" class="button">Back to Patients</a>
            </div>
          <?php else: ?>
            <p>Access detailed medical records for your patients. Please select a patient from the "My Patients" section to view their specific records.</p>
            <div style="background-color: var(--light-bg); padding: 2rem; border-radius: var(--border-radius-base); margin-top: 2rem; text-align: center; border: 0.1rem solid var(--border-color-light)">
              <p style="font-size: 1.8rem; font-weight: 500; color: #64748b">
                <i class="fas fa-info-circle" style="margin-right: 0.8rem; color: var(--primary-blue)"></i>
                Patient records are dynamically loaded. Please go to
                <a href="?section=patients" style="color: var(--primary-blue); text-decoration: none; font-weight: 600">My Patients</a> to select a patient.
              </p>
            </div>
          <?php endif; ?>
        </section>

        <!-- Prescriptions Section -->
        <section id="content-prescriptions" class="dashboard-section" style="<?php echo $section === 'prescriptions' ? '' : 'display: none'; ?>">
          <h2 id="prescriptionsHeading">Prescriptions for <?php echo htmlspecialchars($current_patient_name_for_display); ?></h2>
          <p>Review and manage current prescriptions, or add new ones.</p>

          <div class="prescription-details" id="currentPrescriptionsList">
            <h3>Current Prescriptions</h3>
            <?php
            $current_patient_prescriptions = $patient_prescriptions_data[$patient_id_for_records] ?? [];
            if (!empty($current_patient_prescriptions)): ?>
              <ul>
                <?php foreach ($current_patient_prescriptions as $rx): ?>
                  <li>
                    <strong>Medication:</strong> <?php echo htmlspecialchars($rx['medicationName']); ?><br>
                    <strong>Dosage:</strong> <?php echo htmlspecialchars($rx['dosage']); ?><br>
                    <strong>Instructions:</strong> <?php echo htmlspecialchars($rx['instructions']); ?><br>
                    <strong>Refills:</strong> <?php echo htmlspecialchars($rx['refills']); ?><br>
                    <strong>Notes:</strong> <?php echo htmlspecialchars($rx['notes']); ?><br>
                    <em>Prescription Date: <?php echo htmlspecialchars($rx['prescriptionDate']); ?></em>
                  </li>
                <?php endforeach; ?>
              </ul>
            <?php else: ?>
              <p style="text-align: center; color: #64748b; padding: 1rem">No prescriptions available for this patient.</p>
            <?php endif; ?>
          </div>

          <div class="action-area">
            <a href="?section=add-prescription&patient_id=<?php echo htmlspecialchars($patient_id_for_records); ?>" class="button button-secondary">Add New Prescription</a>
            <a href="?section=records&patient_id=<?php echo htmlspecialchars($patient_id_for_records); ?>" class="button button-outline">Back to Patient Records</a>
          </div>
          <div id="prescriptionMessage" class="message-box"></div>
        </section>

        <!-- New section for adding prescriptions -->
        <section id="content-add-prescription" class="dashboard-section" style="<?php echo $section === 'add-prescription' ? '' : 'display: none'; ?>">
          <h2 id="addPrescriptionHeading">Add New Prescription for <span id="addPrescriptionPatientName"><?php echo htmlspecialchars($current_patient_info_for_records['patientsName'])  ?></span></h2>
          <p>Fill in the details below to add a new prescription for this patient.</p>

          <div class="form-section">
            <form id="addPrescriptionForm">
              <input type="hidden" id="addPrescriptionPatientId" name="patient_id" value="<?php echo htmlspecialchars($patient_id_for_records); ?>" />

              <!-- Conditional Patient Selection if coming from Prescribe Medicines -->
              <div id="patientSelectionGroup" class="form-group">
                <label for="selectPatientForPrescription">Patient Name</label>
                <input type="text" id="selectPatientForPrescription" name="patient_name" list="patientOptions" placeholder="Patients Name" value="<?php echo htmlspecialchars($current_patient_info_for_records['patientsName'])  ?>" required />
              </div>

              <div id="medicineEntries">
                <div class="medicine-entry">
                  <div class="form-group">
                    <label>Medication Name</label>
                    <input type="text" name="medications[0][name]" list="medicineOptions" required />
                  </div>
                  <div class="form-group">
                    <label>Dosage</label>
                    <input type="text" name="medications[0][dosage]" placeholder="e.g., 250mg" required />
                  </div>
                  <div class="form-group">
                    <label>Timing</label>
                    <div class="timing-options">
                      <label><input type="checkbox" name="medications[0][timing][]" value="Morning" /> Morning</label>
                      <label><input type="checkbox" name="medications[0][timing][]" value="Afternoon" /> Afternoon</label>
                      <label><input type="checkbox" name="medications[0][timing][]" value="Evening" /> Evening</label>
                      <label><input type="checkbox" name="medications[0][timing][]" value="Night" /> Night</label>
                    </div>
                  </div>
                  <div class="form-group">
                    <label>With Food</label>
                    <select name="medications[0][meal_time]" required>
                      <option value="Before Meal">Before Meal</option>
                      <option value="After Meal">After Meal</option>
                    </select>
                  </div>
                  <div class="form-group">
                    <label>Duration (days)</label>
                    <input type="number" name="medications[0][duration]" min="1" required />
                  </div>
                  <div class="form-group">
                    <label>Notes</label>
                    <textarea name="medications[0][notes]" rows="2"></textarea>
                  </div>
                  <button type="button" class="button button-danger remove-entry">Remove</button>
                  <hr />
                </div>
              </div>
              <button type="button" class="button button-outline" id="addMoreMedicine">+ Add Another Medicine</button>
              <div class="form-actions">
                <button type="submit" class="button button-secondary">Save Prescription</button>
                <a href="?section=prescriptions&patient_id=<?php echo htmlspecialchars($patient_id_for_records); ?>" class="button button-outline">Back to Prescriptions</a>
              </div>
            </form>
            <div id="addPrescriptionMessage" class="message-box"></div>
          </div>
        </section>

        <!-- Prescribe Medicines Section -->
        <section id="content-prescribe_medicines" class="dashboard-section" style="<?php echo $section === 'prescribe_medicines' ? '' : 'display: none'; ?>">
          <h2>Prescribe Medicines</h2>
          <p>Browse the list of available medicines and initiate a prescription.</p>

          <div class="table-container">
            <table class="data-table" id="prescribeMedicineTable">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Name</th>
                  <th>Generic Name</th>
                  <th>Strength</th>
                  <th>Form</th>
                  <th>Price</th>
                  <th>Stock</th>
                  <th>Manufacturer</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php
                if (!empty($all_medicines_data)): ?>
                  <?php foreach ($all_medicines_data as $med): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($med['id']); ?></td>
                      <td><?php echo htmlspecialchars($med['name']); ?></td>
                      <td><?php echo htmlspecialchars($med['genericName']); ?></td>
                      <td><?php echo htmlspecialchars($med['strength']); ?></td>
                      <td><?php echo htmlspecialchars($med['dosageForm']); ?></td>
                      <td>$<?php echo htmlspecialchars(number_format($med['price'], 2)); ?></td>
                      <td><?php echo htmlspecialchars($med['stock']); ?></td>
                      <td><?php echo htmlspecialchars($med['manufacturer']); ?></td>
                      <td>
                        <div class="action-buttons">
                          <a href="?section=add-prescription&medicine_id=<?php echo htmlspecialchars($med['id']); ?>" class="button button-secondary">Prescribe</a>
                        </div>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                <?php else: ?>
                  <tr>
                    <td colspan="9" style="text-align: center; padding: 2rem;">No medicines found.</td>
                  </tr>
                <?php endif; ?>
              </tbody>
            </table>
          </div>
        </section>

        <!-- Profile Settings Section -->
        <section id="content-profile" class="dashboard-section" style="<?php echo $section === 'profile' ? '' : 'display: none'; ?>">
          <h2>Profile Settings</h2>
          <p>Update your personal and professional information, and manage your account security.</p>

          <div class="profile-section">
            <h3>Personal & Professional Information</h3>
            <form id="profileForm" method="POST" action="../../controller/doctor_profile_con.php">
              <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_did); ?>" />
              <div class="profile-form-row">
                <div class="form-group">
                  <label for="profileName">Full Name</label>
                  <input type="text" id="profileName" name="full_name" value="<?php echo htmlspecialchars($doctor_fname); ?> " <?php echo $editMode ? '' : 'disabled'; ?> />
                </div>
                <div class="form-group">
                  <label for="profileSpecialty">Specialty</label>
                  <input type="text" id="profileSpecialty" name="specialty" value="<?php echo htmlspecialchars($doctor_specialty); ?>" disabled />
                </div>
              </div>
              <div class="form-group">
                <label for="profileEmail">Email</label>
                <input type="email" id="profileEmail" name="email" value="<?php echo htmlspecialchars($doctor_email); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
              </div>
              <div class="form-group">
                <label for="profilePhone">Phone Number</label>
                <input type="tel" id="profilePhone" name="phone" value="<?php echo htmlspecialchars($doctor_phone); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
              </div>
              <div class="form-group">
                <label for="profileInfo">Bio/Info</label>
                <textarea id="profileInfo" name="info" rows="3" <?php echo $editMode ? '' : 'disabled'; ?>><?php echo htmlspecialchars($doctor_info_bio); ?></textarea>
              </div>
              <div class="form-group">
                <label for="profileAvailability">Availability</label>
                <textarea id="profileAvailability" name="availability" rows="2" disabled><?php echo htmlspecialchars($doctor_availability); ?></textarea>
              </div>
              <div class="profile-action-buttons">
                <?php if (!$editMode): ?>
                  <a href="?section=profile&edit=profile" class="button" id="editProfileBtn">Edit Details</a>
                <?php else: ?>
                  <input type="submit" class="button button-secondary" id="saveProfileBtn" name="submit-btn" value="Save Changes" />
                  <a href="?section=profile" class="button button-outline" id="cancelProfileBtn">Cancel</a>
                <?php endif; ?>
              </div>
            </form>
            <?php
            // Display profile update messages from session
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
            <form id="passwordChangeForm" method="POST" action="../../controller/doctor_password_con.php">
              <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($doctor_did); ?>" />
              <div class="form-group">
                <label for="currentPassword">Current Password</label>
                <input type="password" id="currentPassword" name="current_password" placeholder="Enter your current password" required autocomplete="off" />
              </div>
              <div class="form-group">
                <label for="newPassword">New Password</label>
                <input type="password" id="newPassword" name="new_password" placeholder="Enter new password (min 8 chars)" required autocomplete="off" minlength="8" />
              </div>
              <div class="form-group">
                <label for="confirmNewPassword">Confirm New Password</label>
                <input type="password" id="confirmNewPassword" name="confirm_new_password" placeholder="Confirm new password" required autocomplete="off" minlength="8" />
              </div>
              <button type="submit" class="button">Change Password</button>
            </form>
            <?php
            // Display password change messages from session
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

      </main>
    </div>
  </div>

  <div class="overlay" id="overlay"></div>

  <footer>
    <div class="container">
      <p>&copy; 2025 MedCare Hospital. All rights reserved. Your trusted healthcare partner.</p>
    </div>
  </footer>


  <script src="../../assets/doctor/menutoggle.js"></script>
  <script src="../../assets/doctor/loadNewmedicine.js"></script>
</body>

</html>