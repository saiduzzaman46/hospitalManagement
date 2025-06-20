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
      <div class="container">
        <a href="#" class="logo">MedCare Hospital</a>
        <div class="doctor-info">
          <i class="fas fa-user-md"></i>
          <span>Welcome, Dr. John Smith!</span>
        </div>
        <div class="menu-toggle" id="menuToggle">
          <i class="fas fa-bars"></i>
        </div>
      </div>
    </header>

    <div class="main-wrapper">
      <div class="main-content-area container">
        <aside class="sidebar" id="sidebar">
          <h3>Doctor Menu</h3>
          <ul class="sidebar-menu">
            <li>
              <a href="#" class="menu-item active" data-content="home"><i class="fas fa-home"></i> Home</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="appointments"><i class="fas fa-calendar-alt"></i> My Appointments</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="patients"><i class="fas fa-user-injured"></i> My Patients</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="records"><i class="fas fa-file-medical-alt"></i> Patient Records</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="profile"><i class="fas fa-user-cog"></i> Profile Settings</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
          </ul>
        </aside>

        <main class="content-display" id="contentDisplay">
          <section id="content-home" class="dashboard-section dashboard-home">
            <h2>Doctor Dashboard Overview</h2>
            <p>Welcome back, Dr. John Smith! Here's a quick summary of your daily activities.</p>

            <div class="summary-cards">
              <div class="card">
                <h4>Appointments Today</h4>
                <p id="appointmentsTodayCount">0</p>
                <button class="button" onclick="loadContent('appointments', 'today')">View Schedule</button>
              </div>
              <div class="card">
                <h4>Total Patients</h4>
                <p>78</p>
                <button class="button" onclick="loadContent('patients')">View Patients</button>
              </div>
              <div class="card">
                <h4>New Messages</h4>
                <p>3</p>
                <button class="button">Read Messages</button>
              </div>
              <div class="card">
                <h4>Pending Requests</h4>
                <p>2</p>
                <button class="button">Review Requests</button>
              </div>
            </div>

            <div
              style="margin-top: 3.2rem; text-align: left; background-color: var(--light-bg); padding: 2.4rem; border-radius: var(--border-radius-base); border: 0.1rem solid var(--border-color-light)"
            >
              <h3>Quick Actions</h3>
              <p>
                <a href="#" class="button button-secondary" onclick="loadContent('appointments')">Manage Appointments</a>
                <a href="#" class="button" onclick="loadContent('patients')">Browse Patient List</a>
              </p>
            </div>
          </section>

          <section id="content-appointments" class="dashboard-section" style="display: none">
            <h2 id="appointmentsHeading">My Appointments</h2>
            <p>Here you can view and manage your scheduled patient appointments.</p>

            <div id="todayAppointmentsContainer" class="table-container" style="margin-top: 2rem">
              <h3>Today's Appointments</h3>
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Specialty</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <!-- Added Actions column -->
                  </tr>
                </thead>
                <tbody id="todayAppointmentsTableBody">
                  <!-- Today's appointments will be rendered here -->
                </tbody>
              </table>
            </div>

            <div id="upcomingAppointmentsContainer" class="table-container" style="margin-top: 2rem">
              <h3>Upcoming Appointments</h3>
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Specialty</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="upcomingAppointmentsTableBody">
                  <!-- Upcoming appointments will be rendered here -->
                </tbody>
              </table>
            </div>

            <div id="pastAppointmentsContainer" class="table-container" style="margin-top: 2rem">
              <h3>Past Appointments</h3>
              <table class="data-table">
                <thead>
                  <tr>
                    <th>Patient Name</th>
                    <th>Specialty</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody id="pastAppointmentsTableBody">
                  <!-- Past appointments will be rendered here -->
                </tbody>
              </table>
            </div>
            <div id="appointmentMessage" class="message-box"></div>
            <!-- For delete/edit messages -->
          </section>

          <section id="content-patients" class="dashboard-section" style="display: none">
            <h2>My Patients</h2>
            <p>View your patient list, search for specific patients, or access their records.</p>

            <div class="patient-search-controls">
              <div class="form-group">
                <label for="patientNameSearch">Search Patient by Name:</label>
                <input type="text" id="patientNameSearch" placeholder="e.g., Jane Doe" />
              </div>
              <div class="form-group">
                <label for="patientFilter">Filter (e.g., Recent Visits):</label>
                <select id="patientFilter">
                  <option value="">All Patients</option>
                  <option value="Recent">Recently Visited (Last 30 Days)</option>
                  <option value="Active">Active Cases</option>
                  <option value="Follow-up">Needs Follow-up</option>
                </select>
              </div>
            </div>

            <div class="table-container">
              <!-- Uses generic table container now -->
              <table class="data-table" id="patientTable">
                <!-- Uses generic data-table now -->
                <thead>
                  <tr>
                    <th>Patient ID</th>
                    <th>Name</th>
                    <th>Last Visit</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </section>

          <section id="content-records" class="dashboard-section" style="display: none">
            <h2>Patient Medical Records</h2>
            <p>Access detailed medical records for your patients. Please select a patient from the "My Patients" section to view their specific records.</p>
            <div
              style="background-color: var(--light-bg); padding: 2rem; border-radius: var(--border-radius-base); margin-top: 2rem; text-align: center; border: 0.1rem solid var(--border-color-light)"
            >
              <p style="font-size: 1.8rem; font-weight: 500; color: #64748b">
                <i class="fas fa-info-circle" style="margin-right: 0.8rem; color: var(--primary-blue)"></i>
                Patient records are dynamically loaded. Please go to
                <a href="#" onclick="loadContent('patients')" style="color: var(--primary-blue); text-decoration: none; font-weight: 600">My Patients</a> to select a patient.
              </p>
            </div>
          </section>

          <section id="content-prescriptions" class="dashboard-section" style="display: none">
            <h2 id="prescriptionsHeading">Prescriptions for <span id="currentPatientName">this patient</span></h2>
            <p>Review and manage current prescriptions, or add new ones.</p>

            <div class="prescription-details" id="currentPrescriptionsList">
              <h3>Current Prescriptions</h3>
              <!-- Prescriptions will be dynamically loaded here -->
              <p style="text-align: center; color: #64748b; padding: 1rem">No prescriptions available for this patient.</p>
            </div>

            <div class="action-area">
              <button class="button button-secondary" onclick="loadContent('add-prescription')">Add New Prescription</button>
              <button class="button button-outline" onclick="loadContent('appointments')">Back to Appointments</button>
            </div>
            <div id="prescriptionMessage" class="message-box"></div>
          </section>

          <!-- New section for adding prescriptions -->
          <section id="content-add-prescription" class="dashboard-section" style="display: none">
            <h2 id="addPrescriptionHeading">Add New Prescription for <span id="addPrescriptionPatientName"></span></h2>
            <p>Fill in the details below to add a new prescription for this patient.</p>

            <div class="form-section">
              <form id="addPrescriptionForm">
                <input type="hidden" id="addPrescriptionPatientId" value="" />
                <div class="form-group">
                  <label for="medicationName">Medication Name</label>
                  <input type="text" id="medicationName" placeholder="e.g., Amoxicillin" required />
                </div>
                <div class="form-group">
                  <label for="dosage">Dosage</label>
                  <input type="text" id="dosage" placeholder="e.g., 250mg, three times daily" required />
                </div>
                <div class="form-group">
                  <label for="instructions">Instructions</label>
                  <textarea id="instructions" rows="3" placeholder="e.g., Take with food, complete full course." required></textarea>
                </div>
                <div class="form-group">
                  <label for="refills">Refills</label>
                  <input type="number" id="refills" min="0" value="0" required />
                </div>
                <div class="form-group">
                  <label for="prescriptionNotes">Notes</label>
                  <textarea id="prescriptionNotes" rows="2" placeholder="Any additional notes for the patient or pharmacist."></textarea>
                </div>
                <div class="form-actions">
                  <button type="submit" class="button button-secondary">Save Prescription</button>
                  <button type="button" class="button button-outline" onclick="loadContent('prescriptions')">Back to Prescriptions</button>
                </div>
              </form>
              <div id="addPrescriptionMessage" class="message-box"></div>
            </div>
          </section>

          <section id="content-profile" class="dashboard-section" style="display: none">
            <h2>Profile Settings</h2>
            <p>Update your personal and professional information, and manage your account security.</p>

            <div class="profile-section">
              <h3>Personal & Professional Information</h3>
              <form id="profileForm">
                <div class="profile-form-row">
                  <div class="form-group">
                    <label for="profileName">Full Name</label>
                    <input type="text" id="profileName" value="Dr. John Smith" disabled />
                  </div>
                  <div class="form-group">
                    <label for="profileSpecialty">Specialty</label>
                    <input type="text" id="profileSpecialty" value="Pediatrician" disabled />
                  </div>
                </div>
                <div class="form-group">
                  <label for="profileEmail">Email</label>
                  <input type="email" id="profileEmail" value="dr.john.smith@medcare.com" disabled />
                </div>
                <div class="form-group">
                  <label for="profilePhone">Phone Number</label>
                  <input type="tel" id="profilePhone" value="+1 (555) 234-5678" pattern="[+]?[0-9]{10,15}" title="Phone number must be 10-15 digits, optionally starting with a plus sign." disabled />
                </div>
                <div class="form-group">
                  <label for="profileAddress">Clinic Address</label>
                  <textarea id="profileAddress" rows="3" disabled>789 Medical Lane, Health City, USA</textarea>
                </div>
                <div class="form-group">
                  <label for="profileAvailability">Availability</label>
                  <textarea id="profileAvailability" rows="2" disabled>Mon, Wed, Fri: 9 AM - 5 PM</textarea>
                </div>
                <div class="profile-action-buttons">
                  <button type="button" class="button" id="editProfileBtn">Edit Details</button>
                  <button type="submit" class="button button-secondary" id="saveProfileBtn" style="display: none">Save Changes</button>
                  <button type="button" class="button button-outline" id="cancelProfileBtn" style="display: none">Cancel</button>
                </div>
              </form>
              <div id="profileMessage" class="message-box"></div>
            </div>

            <div class="password-change-section">
              <h3>Change Password</h3>
              <form id="passwordChangeForm">
                <div class="form-group">
                  <label for="currentPassword">Current Password</label>
                  <input type="password" id="currentPassword" placeholder="Enter your current password" />
                </div>
                <div class="form-group">
                  <label for="newPassword">New Password</label>
                  <input type="password" id="newPassword" placeholder="Enter new password (min 8 chars)" />
                </div>
                <div class="form-group">
                  <label for="confirmNewPassword">Confirm New Password</label>
                  <input type="password" id="confirmNewPassword" placeholder="Confirm new password" />
                </div>
                <button type="submit" class="button">Change Password</button>
              </form>
              <div id="passwordMessage" class="message-box"></div>
            </div>
          </section>

          <section id="content-logout" class="dashboard-section" style="display: none">
            <h2>Logging Out...</h2>
            <p>You will be redirected to the login page shortly.</p>
          </section>
        </main>
      </div>
    </div>

    <div class="overlay" id="overlay"></div>

    <footer>
      <div class="container">
        <p>&copy; 2025 MedCare Hospital. All rights reserved. Your trusted healthcare partner.</p>
      </div>
    </footer>
    <script src="../../assets/doctor/doctorDash.js"></script>
  </body>
</html>
