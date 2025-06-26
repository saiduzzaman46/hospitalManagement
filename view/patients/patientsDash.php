<script>
  // Check if the page was loaded from the browser cache
  window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>


<?php
if (!isset($_COOKIE['user_id'])) {
  header("Location: ../login/login.php");
  exit();
}

session_start();

$section = isset($_GET['section']) ? $_GET['section'] : 'home';
$editMode = isset($_GET['edit']) && $_GET['edit'] == '1';

// Check if user is logged in and has a valid user ID cookie and retrieve user information
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

require_once '../../model/patients_model.php';
// require_once '../../config/connection.php';



if (isset($_COOKIE['user_id'])) {
  $user_id = $_COOKIE['user_id'];
  $dashboardData = patientsDashboard($user_id);

  $user_info = $dashboardData['user_info'];
  $phone = $dashboardData['phone'];
  $appointmentDoctor = $dashboardData['appointmentDoctor'];
  $availablityDoctor = $dashboardData['availablityDoctor'];
  $upcomingAppointments = $dashboardData['upcomingAppointments'];
  $pastAppointments = $dashboardData['pastAppointments'];
  $upcomingAppointmentsCount = $dashboardData['upcomingAppointmentsCount'];
  $pastAppointmentsCount = $dashboardData['pastAppointmentsCount'];
  $totalDoctors = $dashboardData['totalDoctors'];
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Patient Dashboard - MedCare Hospital</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/patients/dashbord.css" />
</head>

<body>
  <header>
    <div class="container">
      <div class="header-left">
        <div class="menu-toggle" id="menuToggle">
          <i class="fas fa-bars"></i>
        </div>
        <a href="#" class="logo">MedCare Hospital</a>
      </div>
      <div class="patient-info">
        <i class="fas fa-user-circle"></i>
        <span><?php echo htmlspecialchars($user_info['fname']); ?></span>
      </div>
    </div>
  </header>

  <div class="main-content-area">
    <aside class="sidebar scroll-container" id="sidebar">
      <h3>Patient Menu</h3>
      <ul class="sidebar-menu">
        <li><a href="?section=home" class="menu-item <?php echo $section === 'home' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="?section=appointments" class="menu-item <?php echo $section === 'appointments' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> My Appointments</a></li>
        <li><a href="?section=doctors" class="menu-item <?php echo $section === 'doctors' ? 'active' : ''; ?>"><i class="fas fa-user-md"></i> View & Appointments</a></li>
        <li><a href="?section=records" class="menu-item <?php echo $section === 'records' ? 'active' : ''; ?>"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
        <li><a href="?section=notifications" class="menu-item <?php echo $section === 'notifications' ? 'active' : ''; ?>"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="?section=profile" class="menu-item <?php echo $section === 'profile' ? 'active' : ''; ?>"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
        <li> <a href="../../controller/logoutPatients.php" class="menu-item"><i class="fas fa-sign-out-alt"></i> Logout</a></li>

        <!-- <li><a href="?section=logout" class="menu-item <?php echo $section === 'logout' ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li> -->
      </ul>
    </aside>

    <main class="content-display" id="contentDisplay">
      <!-- Home Section -->
      <section id="content-home" class="dashboard-section dashboard-home" style="<?php echo $section === 'home' ? '' : 'display: none'; ?>">
        <h2>Patient Dashboard Overview</h2>
        <p>Welcome back to MedCare Hospital's patient portal. Here's a quick summary of your health activities.</p>
        <div class="summary-cards">
          <div class="card">
            <h4>Upcoming Appointments</h4>
            <p><?php echo $upcomingAppointmentsCount; ?></p>
            <a class="button" href="?section=appointments">View Details</a>
          </div>
          <div class="card">
            <h4>Past Appointments</h4>
            <p><?php echo $pastAppointmentsCount; ?></p>
            <a class="button" href="?section=appointments">View Details</a>
          </div>
          <div class="card">
            <h4>Total Doctors</h4>
            <p><?php echo htmlspecialchars($totalDoctors); ?></p>
            <a class="button" href="?section=doctors">View Details</a>
          </div>
          <div class="card">
            <h4>New Notifications</h4>
            <p>2</p>
            <a class="button" href="?section=notifications">View Details</a>
          </div>

        </div>
        <div class="quick-actions">
          <!-- <h3>Quick Actions</h3> -->

          <a href="?section=doctors" class="button button-secondary">Book New Appointment</a>
          <a href="?section=records" class="button">View My Records</a>

        </div>
      </section>

      <!-- Appointments -->
      <section id="content-appointments" class="dashboard-section" style="<?php echo $section === 'appointments' ? '' : 'display: none'; ?>">
        <h2>My Appointments</h2>
        <p>Here you can view your scheduled and past appointments.</p>

        <div class="appointments-section">
          <h3>Upcoming Appointment:</h3>
          <ul class="upcoming-appointments-list">
            <?php if (!empty($upcomingAppointments)): ?>
              <?php foreach ($upcomingAppointments as $appt): ?>
                <li>
                  <?php echo htmlspecialchars($appt['doctor_name']); ?> -
                  <?php echo htmlspecialchars($appt['specialty']); ?> -
                  <?php echo date('F j, Y', strtotime($appt['appointment_date'])); ?>,
                  <?php echo date('g:i A', strtotime($appt['appointment_time'])); ?>
                  (<span style="<?php echo $appt['status'] === 'Pending' ? 'color:red' : 'color:green' ?>"><?php echo htmlspecialchars($appt['status']); ?></span>)
                </li>
              <?php endforeach; ?>
            <?php else: ?>
              <li>No upcoming appointments.</li>
            <?php endif; ?>
          </ul>

          <h3 class="past-app-header">Past Appointment:</h3>
          <?php if (!empty($pastAppointments)): ?>
            <div class="table-container">
              <table class="table data-table ">
                <thead>
                  <tr>
                    <th>Doctor</th>
                    <th>Specialty</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  <?php foreach ($pastAppointments as $appt): ?>
                    <tr>
                      <td><?php echo htmlspecialchars($appt['doctor_name']); ?></td>
                      <td><?php echo htmlspecialchars($appt['specialty']); ?></td>
                      <td><?php echo date('F j, Y', strtotime($appt['appointment_date'])); ?></td>
                      <td><?php echo date('g:i A', strtotime($appt['appointment_time'])); ?></td>
                      <td style="<?php echo $appt['status'] === 'Pending' ? 'color:red' : 'color:green' ?>">
                        <?php echo htmlspecialchars($appt['status']); ?>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          <?php else: ?>
            <p>No past appointments.</p>
          <?php endif; ?>
        </div>
      </section>


      <!-- Doctors -->
      <section id="content-doctors" class="dashboard-section" style="<?php echo $section === 'doctors' ? '' : 'display: none'; ?>">
        <h2>Our Expert Doctors</h2>
        <p>Find the right specialist for your needs. Click 'Book Now' to schedule an appointment.</p>

        <div class="doctor-search-controls">
          <div class="form-group">
            <label for="doctorNameSearch">Search by Name:</label>
            <input type="text" id="doctorNameSearch" placeholder="e.g., Dr. Emily Watson" />
          </div>
          <div class="form-group">
            <label for="doctorCategorySelect">Filter by Specialty:</label>
            <select id="doctorCategorySelect">
              <option value="">All Specialties</option>
              <?php foreach ($specialties as $specialty): ?>
                <option value="<?php echo htmlspecialchars($specialty); ?>"><?php echo htmlspecialchars($specialty); ?></option>
              <?php endforeach; ?>
            </select>
          </div>
        </div>
        <div class="doctor-grid" id="doctorGrid">
          <!-- add doctor data here -->
        </div>
      </section>

      <section id="content-appointment-form" class="dashboard-section" style="<?php echo $section === 'appointment_form' ? '' : 'display: none'; ?>">
        <div class="appointment-section">
          <h2>Book Appointment</h2>
          <?php if ($appointmentDoctor): ?>
            <form method="POST" action="../../controller/submit_appointment.php">
              <input type="hidden" name="doctor_id" value="<?php echo htmlspecialchars($appointmentDoctor['did']); ?>">
              <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($user_info['pid']); ?>">

              <div class="profile-form-row">
                <div class="form-group">
                  <label>Doctor:</label>
                  <input type="text" value="<?php echo htmlspecialchars($appointmentDoctor['fname']); ?>" disabled>
                </div>
                <div class="form-group">
                  <label>Specialty:</label>
                  <input type="text" value="<?php echo htmlspecialchars($appointmentDoctor['specialty']); ?>" disabled>
                </div>
              </div>
              <div class="available-time-container form-group profile-form-row">
                <label>Doctor Available Time:</label>
                <input type="text" class="available-time" value="<?php echo htmlspecialchars($availablityDoctor); ?>" disabled>
              </div>
              <div class="form-group">
                <label>Doctor Fees:</label>
                <input type="text" value="<?php echo htmlspecialchars($appointmentDoctor['fees'] ?? 'Not specified'); ?>" disabled>
              </div>
              <div class="profile-form-row">
                <div class="form-group">
                  <label>Patient Name:</label>
                  <input class="appointmentInput" type="text" name="patient_name" value="<?php echo htmlspecialchars($user_info['fname']); ?>">
                </div>

                <div class="form-group">
                  <label>Your Phone:</label>
                  <input class="appointmentInput" type="text" name="patient_phone" value="<?php echo htmlspecialchars($phone); ?>">
                </div>
              </div>
              <!-- <div class="profile-form-row"> -->
                <div class="form-group">
                  <label for="gender">Gender:</label>
                  <select class="appointmentInput" id="gender" name="gender">
                    <option value="">Select Gender</option>
                    <option value="male">Male</option>
                    <option value="female">Female</option>
                    <option value="other">Other</option>
                  </select>
                </div>
              <!-- </div> -->

              <div class="form-group">
                <label>Appointment Date:</label>
                <input type="date" name="appointment_date" required>
              </div>
              <div class="form-group">
                <label>Preferred Time:</label>
                <input type="time" name="appointment_time" required>
              </div>

              <input type="submit" class="button button-secondary" value="Appointment">
            </form>
          <?php else: ?>
            <p>Doctor not found.</p>
          <?php endif; ?>
        </div>
        <?php
        if (isset($_SESSION['errorSubmitAppointment']) && is_array($_SESSION['errorSubmitAppointment'])) {
          foreach ($_SESSION['errorSubmitAppointment'] as $error) {
            echo '<div class="message-box message-error">' . htmlspecialchars($error) . '</div>';
          }
          unset($_SESSION['errorSubmitAppointment']);
        }
        if (isset($_SESSION['successSubmitAppointment'])) {
          echo '<div class="message-box message-success">' . htmlspecialchars($_SESSION['successSubmitAppointment']) . '</div>';
          unset($_SESSION['successSubmitAppointment']);
        }
        ?>
      </section>

      <!-- Records -->
      <section id="content-records" class="dashboard-section" style="<?php echo $section === 'records' ? '' : 'display: none'; ?>">
        <h2>My Medical Records</h2>
        <p>Access your health history, prescriptions, and test results.</p>
        <div style="background-color: #f0f4f8; padding: 2rem; border-radius: 0.8rem; margin-top: 2rem">
          <p><strong>Recent Prescriptions:</strong></p>
          <ul>
            <li>Medication A - Prescribed by Dr. Smith</li>
          </ul>
        </div>
      </section>

      <!-- Notifications -->
      <section id="content-notifications" class="dashboard-section" style="<?php echo $section === 'notifications' ? '' : 'display: none'; ?>">
        <h2>My Notifications</h2>
        <ul>
          <li>New lab result available</li>
          <li>Appointment reminder</li>
        </ul>
      </section>

      <!-- Profile -->
      <section id="content-profile" class="dashboard-section" style="<?php echo $section === 'profile' ? '' : 'display: none'; ?>">
        <h2>Profile Settings</h2>
        <p>Update your personal information and manage your account security.</p>

        <div class="profile-section">
          <h3>Personal Information</h3>
          <!-- <form id="profileForm" method="POST" action="../../controller/upddate_patients_con.php"> -->

          <form id="profileForm" onsubmit="return validateProfileForm()" method="POST" action="../../controller/upddate_patients_con.php">
            <input type="hidden" name="patient_id" value="<?php echo htmlspecialchars($user_info['pid']); ?>" />

            <div class="profile-form-row">
              <div class="form-group">
                <label for="profileName">Full Name:</label>
                <input type="text" id="profileName" name="fullname" value="<?php echo htmlspecialchars($user_info['fname']); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
              </div>
              <div class="form-group">
                <label for="profilePhone">Phone Number:</label>
                <input type="tel" id="profilePhone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
              </div>
            </div>

            <div class="form-group">
              <label for="profileEmail">Email:</label>
              <input type="email" id="profileEmail" name="email" value="<?php echo htmlspecialchars($user_info['email']); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
            </div>

            <div class="form-group">
              <label for="profileAddress">Address:</label>
              <textarea id="profileAddress" rows="2" name="address" <?php echo $editMode ? '' : 'disabled'; ?>><?php echo htmlspecialchars($user_info['address']); ?></textarea>
            </div>

            <div class="profile-form-row">
              <div class="form-group">
                <label for="profileGender">Gender:</label>
                <select id="profileGender" name="gender" <?php echo $editMode ? '' : 'disabled'; ?>>
                  <option value="male" <?php if ($user_info['gender'] === "male") echo "selected"; ?>>Male</option>
                  <option value="female" <?php if ($user_info['gender'] === "female") echo "selected"; ?>>Female</option>
                  <option value="other" <?php if ($user_info['gender'] === "other") echo "selected"; ?>>Other</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="profileDob">Date of Birth:</label>
              <input type="date" id="profileDob" name="dob" value="<?php echo htmlspecialchars($user_info['dob']); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
            </div>

            <div class="profile-action-buttons">
              <?php if (!$editMode): ?>
                <a href="?section=profile&edit=1" class="button" id="editProfileBtn">Edit Details</a>
              <?php else: ?>
                <input type="submit" class="button button-secondary" id="saveProfileBtn" name="submit-btn" value="Save Changes" />
                <a href="?section=profile" class="button button-outline" id="cancelProfileBtn">Cancel</a>
              <?php endif; ?>
            </div>
          </form>

          <?php
          if (isset($_SESSION['errorUpdate']) && is_array($_SESSION['errorUpdate'])) {
            foreach ($_SESSION['errorUpdate'] as $error) {
              echo '<div class="message-box message-error">' . htmlspecialchars($error) . '</div>';
            }
            unset($_SESSION['errorUpdate']);
          }

          if (isset($_SESSION['successUpdate'])) {
            echo '<div class="message-box message-success">' . htmlspecialchars($_SESSION['successUpdate']) . '</div>';
            unset($_SESSION['successUpdate']);
          }
          ?>


          <!-- <div id="profileMessage" class="message-box"></div> -->

          <div class="password-change-section">
            <h3>Change Password</h3>
            <form id="passwordChangeForm" method="post" action="../../controller/change_patients_pass_con.php">
              <div class="form-group">
                <label for="currentPassword">Current Password:</label>
                <input type="password" id="currentPassword" name="currentPassword" placeholder="Enter your current password" autocomplete="off" />
              </div>
              <div class="form-group">
                <label for="newPassword">New Password:</label>
                <input type="password" id="newPassword" name="newPassword" placeholder="Enter new password (min 8 chars)" autocomplete="off" minlength="8" />
              </div>
              <div class="form-group">
                <label for="confirmNewPassword">Confirm New Password:</label>
                <input type="password" id="confirmNewPassword" name="confirmNewPassword" placeholder="Confirm new password" autocomplete="off" minlength="8" />
              </div>
              <button type="submit" class="button">Change Password</button>
            </form>
            <?php
            if (isset($_SESSION['errorPassChange'])) {
              foreach ($_SESSION['errorPassChange'] as $error) {
                echo '<div class="message-box message-error">' . htmlspecialchars($error) . '</div>';
              }
              unset($_SESSION['errorPassChange']); // <-- Add this line to clear old errors
            }

            if (isset($_SESSION['successPassChange'])) {
              echo '<div class="message-box message-success">' . htmlspecialchars($_SESSION['successPassChange']) . '</div>';
              unset($_SESSION['successPassChange']);
            }
            ?>

            <!-- <div id="passwordMessage" class="message-box"></div> -->
          </div>
        </div>
      </section>
    </main>
  </div>

  <footer>
    <div class="container">
      <p>&copy; 2025 MedCare Hospital. All rights reserved. Your health, our priority.</p>
    </div>
  </footer>
  <script src="../../assets/patients/dashbord.js"></script>
  <script src="../../assets/patients/adddoctor.js"></script>
</body>

</html>