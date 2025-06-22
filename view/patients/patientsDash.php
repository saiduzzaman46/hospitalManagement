<?php
session_start();
require_once '../../model/get_user_info.php';
require_once '../../config/connection.php';

$section = isset($_GET['section']) ? $_GET['section'] : 'home';
$editMode = isset($_GET['edit']) && $_GET['edit'] == '1';

if (isset($_COOKIE['user_id'])) {
  $user_id = $_COOKIE['user_id'];
  // Fetch user info using the get_user_info function
  $user_info = get_user_info($conn, $user_id);

  if ($user_info) {
    $id = $user_info['id'];
    $fname = $user_info['fname'];
    $phone = get_user_phone($conn, $user_info['id']);
    $email = $user_info['email'];
    $dob = $user_info['dob'];
    $gender = $user_info['gender'];
    $address = $user_info['address'];
  } else {
    // Handle user not found
    $id = $fname = $phone = $email = $dob = $gender = $address = '';
  }
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
        <span><?php echo htmlspecialchars($fname); ?></span>
      </div>
    </div>
  </header>

  <div class="main-content-area">
    <aside class="sidebar scroll-container" id="sidebar">
      <h3>Patient Menu</h3>
      <ul class="sidebar-menu">
        <li><a href="?section=home" class="menu-item <?php echo $section === 'home' ? 'active' : ''; ?>"><i class="fas fa-home"></i> Home</a></li>
        <li><a href="?section=appointments" class="menu-item <?php echo $section === 'appointments' ? 'active' : ''; ?>"><i class="fas fa-calendar-alt"></i> My Appointments</a></li>
        <li><a href="?section=doctors" class="menu-item <?php echo $section === 'doctors' ? 'active' : ''; ?>"><i class="fas fa-user-md"></i> View & Book Doctors</a></li>
        <li><a href="?section=records" class="menu-item <?php echo $section === 'records' ? 'active' : ''; ?>"><i class="fas fa-file-medical-alt"></i> Medical Records</a></li>
        <li><a href="?section=notifications" class="menu-item <?php echo $section === 'notifications' ? 'active' : ''; ?>"><i class="fas fa-bell"></i> Notifications</a></li>
        <li><a href="?section=profile" class="menu-item <?php echo $section === 'profile' ? 'active' : ''; ?>"><i class="fas fa-user-cog"></i> Profile Settings</a></li>
        <li><a href="?section=logout" class="menu-item <?php echo $section === 'logout' ? 'active' : ''; ?>"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
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
            <p>3</p>
            <a class="button" href="?section=appointments">View Details</a>
          </div>
          <div class="card">
            <h4>Past Appointments</h4>
            <p>12</p>
            <a class="button" href="?section=appointments">View Details</a>
          </div>
          <div class="card">
            <h4>New Notifications</h4>
            <p>2</p>
            <a class="button" href="?section=notifications">View Details</a>
          </div>
          <div class="card">
            <h4>Total Doctors</h4>
            <p>8</p>
            <a class="button" href="?section=doctors">View Details</a>
          </div>
        </div>
        <div style="margin-top: 3.2rem; text-align: left; background-color: #f0f4f8; padding: 2.4rem; border-radius: 1rem">
          <h3>Quick Actions</h3>
          <p>
            <a href="?section=doctors" class="button button-secondary">Book New Appointment</a>
            <a href="?section=records" class="button">View My Records</a>
          </p>
        </div>
      </section>

      <!-- Appointments -->
      <section id="content-appointments" class="dashboard-section" style="<?php echo $section === 'appointments' ? '' : 'display: none'; ?>">
        <h2>My Appointments</h2>
        <p>Here you can view your scheduled and past appointments.</p>
        <div style="background-color: #f0f4f8; padding: 2rem; border-radius: 0.8rem; margin-top: 2rem">
          <p><strong>Upcoming:</strong></p>
          <ul>
            <li>Dr. John Smith - Cardiology - June 20, 2025, 10:00 AM</li>
            <li>Dr. Emily Watson - Internal Medicine - June 25, 2025, 02:00 PM</li>
          </ul>
          <p style="margin-top: 1.5rem"><strong>Past:</strong></p>
          <ul>
            <li>Dr. Sarah Chen - Dermatology - May 15, 2025, 2:00 PM</li>
            <li>Dr. Michael Lee - Orthopedics - April 10, 2025, 09:00 AM</li>
          </ul>
        </div>
      </section>

      <!-- Doctors -->
      <section id="content-doctors" class="dashboard-section" style="<?php echo $section === 'doctors' ? '' : 'display: none'; ?>">
        <h2>Our Expert Doctors</h2>
        <p>Find the right specialist for your needs. Click 'Book Now' to schedule an appointment.</p>
        <div class="doctor-grid" id="doctorGrid">
          <p>Doctor list will be loaded dynamically...</p>
        </div>
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
            <input type="hidden" name="id" value="<?php echo htmlspecialchars($id); ?>" />

            <div class="profile-form-row">
              <div class="form-group">
                <label for="profileName">Full Name:</label>
                <input type="text" id="profileName" name="fullname" value="<?php echo htmlspecialchars($fname); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
              </div>
              <div class="form-group">
                <label for="profilePhone">Phone Number:</label>
                <input type="tel" id="profilePhone" name="phone" value="<?php echo htmlspecialchars($phone); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
              </div>
            </div>

            <div class="form-group">
              <label for="profileEmail">Email:</label>
              <input type="email" id="profileEmail" name="email" value="<?php echo htmlspecialchars($email); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
            </div>

            <div class="form-group">
              <label for="profileAddress">Address:</label>
              <textarea id="profileAddress" rows="2" name="address" <?php echo $editMode ? '' : 'disabled'; ?>><?php echo htmlspecialchars($address); ?></textarea>
            </div>

            <div class="profile-form-row">
              <div class="form-group">
                <label for="profileGender">Gender:</label>
                <select id="profileGender" name="gender" <?php echo $editMode ? '' : 'disabled'; ?>>
                  <option value="male" <?php if ($gender === "male") echo "selected"; ?>>Male</option>
                  <option value="female" <?php if ($gender === "female") echo "selected"; ?>>Female</option>
                  <option value="other" <?php if ($gender === "other") echo "selected"; ?>>Other</option>
                </select>
              </div>
            </div>

            <div class="form-group">
              <label for="profileDob">Date of Birth:</label>
              <input type="date" id="profileDob" name="dob" value="<?php echo htmlspecialchars($dob); ?>" <?php echo $editMode ? '' : 'disabled'; ?> />
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
          if (isset($_SESSION['errorUpdate']['fullname'])) {
            echo '<div id="profileMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorUpdate']['fullname']) . '</div>';
            unset($_SESSION['errorUpdate']);
          } elseif (isset($_SESSION['errorUpdate']['email'])) {
            echo '<div id="profileMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorUpdate']['email']) . '</div>';
            unset($_SESSION['errorUpdate']);
          } elseif (isset($_SESSION['errorUpdate']['dob'])) {
            echo '<div id="profileMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorUpdate']['dob']) . '</div>';
            unset($_SESSION['errorUpdate']);
          } elseif (isset($_SESSION['errorUpdate']['address'])) {
            echo '<div id="profileMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorUpdate']['address']) . '</div>';
            unset($_SESSION['errorUpdate']);
          } elseif (isset($_SESSION['errorUpdate']['phone'])) {
            echo '<div id="profileMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorUpdate']['phone']) . '</div>';
            unset($_SESSION['errorUpdate']);
          } elseif (isset($_SESSION['errorUpdate']['gender'])) {
            echo '<div id="profileMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorUpdate']['gender']) . '</div>';
            unset($_SESSION['errorUpdate']);
          } elseif (isset($_SESSION['successUpdate'])) { 
            echo '<div id="profileMessage" class="message-box message-success">' . htmlspecialchars($_SESSION['successUpdate']) . '</div>';
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
            if (isset($_SESSION['errorPassChange']['currentPassword'])) {
              echo '<div id="passwordMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorPassChange']['currentPassword']) . '</div>';
              unset($_SESSION['errorPassChange']);
            } elseif (isset($_SESSION['errorPassChange']['newPassword'])) {
              echo '<div id="passwordMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorPassChange']['newPassword']) . '</div>';
              unset($_SESSION['errorPassChange']);
            } elseif (isset($_SESSION['errorPassChange']['confirmNewPassword'])) {
              echo '<div id="passwordMessage" class="message-box message-error">' . htmlspecialchars($_SESSION['errorPassChange']['confirmNewPassword']) . '</div>';
              unset($_SESSION['errorPassChange']);
            } elseif (isset($_SESSION['successPassChange'])) {
              echo '<div id="passwordMessage" class="message-box message-success">' . htmlspecialchars($_SESSION['successPassChange']) . '</div>';
              unset($_SESSION['successPassChange']);
            } else {
              echo '<div id="passwordMessage" class="message-box"></div>';
            }
            ?>
            <!-- <div id="passwordMessage" class="message-box"></div> -->
          </div>
        </div>
      </section>

      <!-- Logout -->
      <section id="content-logout" class="dashboard-section" style="<?php echo $section === 'logout' ? '' : 'display: none'; ?>">
        <h2>Logging Out...</h2>
        <?php
        session_destroy();
        header("refresh:2;url=../../login.php");
        ?>
        <p>Redirecting to login...</p>
      </section>
    </main>
  </div>

  <footer>
    <div class="container">
      <p>&copy; 2025 MedCare Hospital. All rights reserved. Your health, our priority.</p>
    </div>
  </footer>
  <script src="../../assets/patients/dashbord.js"></script>
</body>

</html>