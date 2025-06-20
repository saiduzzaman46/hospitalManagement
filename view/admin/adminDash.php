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
        <a href="#" class="logo">MedCare Admin</a>
        <div class="admin-info">
          <i class="fas fa-user-shield"></i>
          <span>Welcome, Admin!</span>
        </div>
        <div class="menu-toggle" id="menuToggle">
          <i class="fas fa-bars"></i>
        </div>
      </div>
    </header>

    <div class="main-wrapper">
      <div class="main-content-area container">
        <aside class="sidebar" id="sidebar">
          <h3>Admin Menu</h3>
          <ul class="sidebar-menu">
            <li>
              <a href="#" class="menu-item active" data-content="home"><i class="fas fa-tachometer-alt"></i> Dashboard Home</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="patients"><i class="fas fa-user-injured"></i> Patient List</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="doctors"><i class="fas fa-user-md"></i> Doctor Management</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="appointments"><i class="fas fa-calendar-alt"></i> Appointment Management</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="profile"><i class="fas fa-user-cog"></i> Admin Profile</a>
            </li>
            <li>
              <a href="#" class="menu-item" data-content="logout"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </li>
          </ul>
        </aside>

        <main class="content-display" id="contentDisplay">
          <!-- Dashboard Home Section -->
          <section id="content-home" class="dashboard-section">
            <h2>Admin Dashboard Overview</h2>
            <p>Welcome to the MedCare Hospital Admin Panel. Here's a summary of key system data.</p>

            <div class="summary-cards">
              <div class="card">
                <h4>Total Patients</h4>
                <p id="totalPatientsCount">0</p>
                <button class="button" onclick="loadContent('patients')">View Patients</button>
              </div>
              <div class="card">
                <h4>Total Doctors</h4>
                <p id="totalDoctorsCount">0</p>
                <button class="button" onclick="loadContent('doctors')">View Doctors</button>
              </div>
              <div class="card">
                <h4>Today's Appointments</h4>
                <p id="todayAppointmentsCount">0</p>
                <button class="button" onclick="loadContent('appointments')">View Appointments</button>
              </div>
            </div>

            <div
              style="margin-top: 3.2rem; text-align: left; background-color: var(--light-bg); padding: 2.4rem; border-radius: var(--border-radius-base); border: 0.1rem solid var(--border-color-light)"
            >
              <h3>Quick Actions</h3>
              <p>
                <a href="#" class="button button-secondary" onclick="loadContent('doctors'); showDoctorSubSection('add');">Add New Doctor</a>
                <a href="#" class="button" onclick="loadContent('appointments'); showAppointmentSubSection('create');">Create Appointment</a>
              </p>
            </div>
          </section>

          <!-- Patient List Section (No Add/Edit/Delete Forms) -->
          <section id="content-patients" class="dashboard-section" style="display: none">
            <h2>Patient List</h2>
            <p>View patient records. Patient creation, editing, and deletion are handled via the backend system.</p>

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
                  <!-- Patient data will be rendered here by JavaScript -->
                </tbody>
              </table>
            </div>
            <div id="patientFormMessage" class="message-box"></div>
          </section>

          <!-- Doctor Management Section -->
          <section id="content-doctors" class="dashboard-section" style="display: none">
            <h2>Doctor Management</h2>
            <p>Add new doctor records or view existing ones.</p>

            <div class="doctor-management-controls" style="margin-bottom: 2rem; display: flex; gap: 1rem">
              <button class="button" onclick="showDoctorSubSection('add')">Add Doctor</button>
              <button class="button button-outline" onclick="showDoctorSubSection('view')">View Doctors</button>
            </div>

            <div id="addDoctorSection" class="form-section" style="display: none">
              <h3 id="doctorFormHeading">Add New Doctor</h3>
              <form id="doctorForm">
                <input type="hidden" id="doctorId" value="" />
                <div class="form-group">
                  <label for="doctorName">Full Name</label>
                  <input type="text" id="doctorName" placeholder="Doctor's Full Name" required />
                </div>
                <div class="form-group">
                  <label for="doctorSpecialty">Specialty</label>
                  <input type="text" id="doctorSpecialty" placeholder="e.g., Cardiologist" required />
                </div>
                <div class="form-group">
                  <label for="doctorContact">Contact Info (Email/Phone)</label>
                  <input type="text" id="doctorContact" placeholder="email@example.com or +123..." required />
                </div>
                <div class="form-group">
                  <label for="doctorInfo">Doctor Info (Text Area)</label>
                  <textarea id="doctorInfo" rows="2" placeholder="e.g., Dedicated to children's health..." required></textarea>
                </div>
                <div class="doctor-availability-group">
                  <div class="form-group">
                    <label for="doctorFromDay">Available From Day</label>
                    <select id="doctorFromDay" required>
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
                    <select id="doctorToDay" required>
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
                    <input type="time" id="doctorStartTime" required />
                  </div>
                  <div class="form-group">
                    <label for="doctorEndTime">End Time</label>
                    <input type="time" id="doctorEndTime" required />
                  </div>
                </div>
                <div class="form-actions">
                  <button type="submit" class="button" id="doctorFormSubmit">Add Doctor</button>
                  <button type="button" class="button button-outline" id="doctorFormCancel" style="display: none">Cancel</button>
                </div>
              </form>
              <div id="doctorFormMessage" class="message-box"></div>
            </div>

            <div id="viewDoctorsSection" style="display: none">
              <h3>Doctor List</h3>
              <div class="table-container">
                <table class="data-table" id="doctorTable">
                  <thead>
                    <tr>
                      <th>ID</th>
                      <th>Name</th>
                      <th>Specialty</th>
                      <th>Contact</th>
                      <th>Info & Availability</th>
                      <th>Actions</th>
                    </tr>
                  </thead>
                  <tbody>
                    <!-- Doctor data will be rendered here by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- Appointment Management Section (Create Form + View Table) -->
          <section id="content-appointments" class="dashboard-section" style="display: none">
            <h2>Appointment Management</h2>
            <p>Create new appointments or view existing ones.</p>

            <div class="appointment-management-controls" style="margin-bottom: 2rem; display: flex; gap: 1rem">
              <button class="button" onclick="showAppointmentSubSection('create')">Create Appointment</button>
              <button class="button button-outline" onclick="showAppointmentSubSection('view')">View Appointments</button>
            </div>

            <div id="createAppointmentSection" class="form-section" style="display: none">
              <h3 id="appointmentFormHeading">Create New Appointment</h3>
              <form id="appointmentForm">
                <div class="form-group">
                  <label for="appointmentPatient">Patient</label>
                  <select id="appointmentPatient" required>
                    <option value="">Select Patient</option>
                    <!-- Patients will be dynamically loaded here -->
                  </select>
                </div>
                <div class="form-group">
                  <label for="appointmentDoctor">Doctor</label>
                  <select id="appointmentDoctor" required>
                    <option value="">Select Doctor</option>
                    <!-- Doctors will be dynamically loaded here -->
                  </select>
                </div>
                <div class="form-group">
                  <label for="appointmentDate">Date</label>
                  <input type="date" id="appointmentDate" required />
                </div>
                <div class="form-group">
                  <label for="appointmentTime">Time</label>
                  <input type="time" id="appointmentTime" required />
                </div>
                <div class="form-group">
                  <label for="appointmentStatus">Status</label>
                  <select id="appointmentStatus" required>
                    <option value="Pending">Pending</option>
                    <option value="Confirmed">Confirmed</option>
                    <option value="Completed">Completed</option>
                    <option value="Cancelled">Cancelled</option>
                  </select>
                </div>
                <div class="form-group">
                  <label for="appointmentPaymentStatus">Payment Status</label>
                  <select id="appointmentPaymentStatus" required>
                    <option value="Pending">Pending</option>
                    <option value="Paid">Paid</option>
                    <option value="Partially Paid">Partially Paid</option>
                    <option value="Waived">Waived</option>
                  </select>
                </div>
                <div class="form-actions">
                  <button type="submit" class="button" id="appointmentFormSubmit">Create Appointment</button>
                </div>
              </form>
              <div id="appointmentFormMessage" class="message-box"></div>
            </div>

            <div id="paymentConfirmationSection" class="form-section" style="display: none">
              <h3>Appointment Confirmation & Payment</h3>
              <p>Your appointment has been successfully created!</p>
              <div class="form-group">
                <label>Appointment ID:</label>
                <span id="confirmedApptId"></span>
              </div>
              <div class="form-group">
                <label>Patient:</label>
                <span id="confirmedApptPatient"></span>
              </div>
              <div class="form-group">
                <label>Doctor:</label>
                <span id="confirmedApptDoctor"></span>
              </div>
              <div class="form-group">
                <label>Date & Time:</label>
                <span id="confirmedApptDateTime"></span>
              </div>
              <div class="form-group">
                <label for="paymentAmount">Payment Amount ($)</label>
                <input type="number" id="paymentAmount" value="50.00" min="0" step="0.01" />
              </div>
              <div class="form-actions">
                <button type="button" class="button" onclick="makePayment()">Make Payment</button>
              </div>
              <div id="paymentMessage" class="message-box"></div>
              <div class="form-actions" style="margin-top: 2rem">
                <button type="button" class="button button-secondary" id="downloadConfirmBtn" style="display: none" onclick="downloadAppointmentConfirmation()">Download Confirmation</button>
                <button type="button" class="button button-outline" onclick="showAppointmentSubSection('view')">Back to Appointments List</button>
              </div>
            </div>

            <div id="viewAppointmentsSection" style="display: none">
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
                    <!-- Appointment data will be rendered here by JavaScript -->
                  </tbody>
                </table>
              </div>
            </div>
          </section>

          <!-- Admin Profile Section -->
          <section id="content-profile" class="dashboard-section" style="display: none">
            <h2>Admin Profile Settings</h2>
            <p>Manage your account details and security.</p>

            <div class="form-section">
              <h3>Admin Information</h3>
              <form id="adminProfileForm">
                <div class="form-group">
                  <label for="adminName">Admin Name</label>
                  <input type="text" id="adminName" value="Hospital Admin" disabled />
                </div>
                <div class="form-group">
                  <label for="adminEmail">Email</label>
                  <input type="email" id="adminEmail" value="admin@medcare.com" disabled />
                </div>
                <div class="form-actions">
                  <button type="button" class="button" id="editAdminProfileBtn">Edit Details</button>
                  <button type="submit" class="button button-secondary" id="saveAdminProfileBtn" style="display: none">Save Changes</button>
                  <button type="button" class="button button-outline" id="cancelAdminProfileBtn" style="display: none">Cancel</button>
                </div>
              </form>
              <div id="adminProfileMessage" class="message-box"></div>
            </div>

            <div class="form-section">
              <h3>Change Password</h3>
              <form id="adminPasswordChangeForm">
                <div class="form-group">
                  <label for="adminCurrentPassword">Current Password</label>
                  <input type="password" id="adminCurrentPassword" placeholder="Enter your current password" />
                </div>
                <div class="form-group">
                  <label for="adminNewPassword">New Password</label>
                  <input type="password" id="adminNewPassword" placeholder="Enter new password (min 8 chars)" />
                </div>
                <div class="form-group">
                  <label for="adminConfirmNewPassword">Confirm New Password</label>
                  <input type="password" id="adminConfirmNewPassword" placeholder="Confirm new password" />
                </div>
                <div class="form-actions">
                  <button type="submit" class="button">Change Password</button>
                </div>
              </form>
              <div id="adminPasswordMessage" class="message-box"></div>
            </div>
          </section>

          <!-- Logout Section -->
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
        <p>&copy; 2025 MedCare Hospital. Admin Panel. All rights reserved.</p>
      </div>
    </footer>
    <script src="../../assets/admin/adminDash.js"></script>
  </body>
</html>
