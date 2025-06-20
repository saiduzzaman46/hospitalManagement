// --- In-memory Data Stores (Replace with Backend API Calls for real CRUD) ---
let patientsData = [
  { id: "P001", name: "Alice Johnson", phone: "+11234567890", email: "alice@example.com", address: "123 Oak Ave", gender: "Female", dob: "1990-05-15" },
  { id: "P002", name: "Bob Williams", phone: "+19876543210", email: "bob@example.com", address: "456 Pine St", gender: "Male", dob: "1985-11-20" },
  { id: "P003", name: "Charlie Brown", phone: "+15551234567", email: "charlie@example.com", address: "789 Maple Rd", gender: "Male", dob: "1992-02-28" },
];

let doctorsData = [
  {
    id: "D001",
    name: "Dr. Emily Watson",
    specialty: "Cardiologist",
    contact: "emily.w@medcare.com",
    info: "Specializing in heart health, Dr. Watson provides expert care for various cardiac conditions.",
    fromDay: "Monday",
    toDay: "Friday",
    startTime: "09:00",
    endTime: "17:00",
  },
  {
    id: "D002",
    name: "Dr. John Smith",
    specialty: "Pediatrician",
    contact: "john.s@medcare.com",
    info: "Dedicated to children's health, Dr. Smith ensures comprehensive and gentle care for your little ones.",
    fromDay: "Tuesday",
    toDay: "Thursday",
    startTime: "10:00",
    endTime: "18:00",
  },
  {
    id: "D003",
    name: "Dr. Sarah Chen",
    specialty: "Dermatologist",
    contact: "sarah.c@medcare.com",
    info: "Expert in skin conditions and cosmetic dermatology.",
    fromDay: "Monday",
    toDay: "Wednesday",
    startTime: "08:00",
    endTime: "16:00",
  },
];

let appointmentsData = [
  { id: "A001", patientId: "P001", doctorId: "D001", date: "2025-06-20", time: "10:00", status: "Confirmed", paymentStatus: "Paid" },
  { id: "A002", patientId: "P002", doctorId: "D002", date: "2025-06-20", time: "14:30", status: "Pending", paymentStatus: "Pending" },
  { id: "A003", patientId: "P003", doctorId: "D003", date: "2025-06-22", time: "09:00", status: "Confirmed", paymentStatus: "Partially Paid" },
];

const adminData = {
  name: "Hospital Admin",
  email: "admin@medcare.com",
  password: "adminpassword123", // Dummy password
};

// --- Global references to sub-sections for easier access ---
let addDoctorSection;
let viewDoctorsSection;
let createAppointmentSection;
let viewAppointmentsSection;
let paymentConfirmationSection; // New reference for payment section

let currentAppointmentId = null; // To hold the ID of the appointment being processed for payment/download

// --- Utility Functions ---

/** Generates a simple unique ID */
function generateId(prefix) {
  return prefix + Math.random().toString(36).substr(2, 9).toUpperCase();
}

/** Shows a message box with success or error style */
function showMessage(elementId, message, type) {
  const msgBox = document.getElementById(elementId);
  msgBox.textContent = message;
  msgBox.className = `message-box message-${type}`;
  msgBox.style.display = "block";
  setTimeout(() => {
    msgBox.style.display = "none";
  }, 3000);
}

/** Formats date for display */
function formatDateForDisplay(dateString) {
  if (!dateString) return "";
  const options = { year: "numeric", month: "short", day: "numeric" };
  return new Date(dateString).toLocaleDateString("en-US", options);
}

// --- Render Functions ---

/** Renders the patient list table */
function renderPatients() {
  const tableBody = document.querySelector("#patientTable tbody");
  tableBody.innerHTML = ""; // Clear existing rows

  if (patientsData.length === 0) {
    tableBody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 2rem;">No patient records found.</td></tr>';
    return;
  }

  patientsData.forEach((patient) => {
    const row = tableBody.insertRow();
    row.innerHTML = `
                    <td>${patient.id}</td>
                    <td>${patient.name}</td>
                    <td>${patient.phone}</td>
                    <td>${patient.email}</td>
                    <td>${patient.address}</td>
                    <td>${patient.gender}</td>
                    <td>${formatDateForDisplay(patient.dob)}</td>
                    <td>
                        <div class="action-buttons">
                            <button class="button button-outline" onclick="editPatient('${patient.id}')">Edit</button>
                            <button class="button button-danger" onclick="deletePatient('${patient.id}')">Delete</button>
                        </div>
                    </td>
                `;
  });
  updateDashboardStatistics();
}

/** Renders the doctor list table */
function renderDoctors(targetTableBodyId = "doctorTable") {
  // Default to main doctor table
  const tableBody = document.querySelector(`#${targetTableBodyId} tbody`);
  tableBody.innerHTML = ""; // Clear existing rows

  if (doctorsData.length === 0) {
    tableBody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 2rem;">No doctor records found.</td></tr>';
    return;
  }

  doctorsData.forEach((doctor) => {
    const row = tableBody.insertRow();
    const availabilityText = doctor.fromDay && doctor.toDay && doctor.startTime && doctor.endTime ? `${doctor.fromDay} to ${doctor.toDay}: ${doctor.startTime} - ${doctor.endTime}` : "N/A";

    row.innerHTML = `
                    <td>${doctor.id}</td>
                    <td>${doctor.name}</td>
                    <td>${doctor.specialty}</td>
                    <td>${doctor.contact}</td>
                    <td>
                        <strong>Info:</strong> ${doctor.info}<br>
                        <strong>Availability:</strong> ${availabilityText}
                    </td>
                    <td>
                        <div class="action-buttons">
                            <button class="button button-outline" onclick="editDoctor('${doctor.id}')">Edit</button>
                            <button class="button button-danger" onclick="deleteDoctor('${doctor.id}')">Delete</button>
                        </div>
                    </td>
                `;
  });
  updateDashboardStatistics();
}

/** Renders the appointment list table */
function renderAppointments() {
  const tableBody = document.querySelector("#appointmentTable tbody");
  tableBody.innerHTML = ""; // Clear existing rows

  if (appointmentsData.length === 0) {
    tableBody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 2rem;">No appointments found.</td></tr>';
    return;
  }

  appointmentsData.forEach((appt) => {
    const patient = patientsData.find((p) => p.id === appt.patientId);
    const doctor = doctorsData.find((d) => d.id === appt.doctorId);
    const patientName = patient ? patient.name : "Unknown Patient";
    const doctorName = doctor ? doctor.name : "Unknown Doctor";

    const row = tableBody.insertRow();
    row.innerHTML = `
                    <td>${appt.id}</td>
                    <td>${patientName}</td>
                    <td>${doctorName}</td>
                    <td>${formatDateForDisplay(appt.date)}</td>
                    <td>${appt.time}</td>
                    <td>${appt.status}</td>
                    <td>${appt.paymentStatus || "N/A"}</td> 
                    <td>
                        <div class="action-buttons">
                            <button class="button button-outline" onclick="editAppointment('${appt.id}')">Edit</button>
                            <button class="button button-danger" onclick="deleteAppointment('${appt.id}')">Delete</button>
                        </div>
                    </td>
                `;
  });
  updateDashboardStatistics();
}

/** Populates patient and doctor dropdowns for appointment form */
function populateAppointmentDropdowns() {
  const patientSelect = document.getElementById("appointmentPatient");
  const doctorSelect = document.getElementById("appointmentDoctor");

  // Clear previous options
  patientSelect.innerHTML = '<option value="">Select Patient</option>';
  doctorSelect.innerHTML = '<option value="">Select Doctor</option>';

  patientsData.forEach((patient) => {
    const option = document.createElement("option");
    option.value = patient.id;
    option.textContent = patient.name;
    patientSelect.appendChild(option);
  });

  doctorsData.forEach((doctor) => {
    const option = document.createElement("option");
    option.value = doctor.id;
    option.textContent = doctor.name;
    doctorSelect.appendChild(option);
  });
}

// --- Placeholder CRUD Action Handlers (for UI buttons only) ---
// These functions just show a message, actual CRUD would be handled by backend.

function editPatient(id) {
  showMessage("patientFormMessage", `Edit Patient (ID: ${id}) - This action would open a backend-managed form for editing.`, "success");
}

function deletePatient(id) {
  showMessage("patientFormMessage", `Delete Patient (ID: ${id}) - This action would send a request to your backend to delete the record.`, "danger");
}

function editDoctor(id) {
  showMessage("doctorFormMessage", `Edit Doctor (ID: ${id}) - This action would open the form with pre-filled data for editing.`, "success");
  // In a real app, this would fetch doctor data and populate the form for editing
  const doctor = doctorsData.find((d) => d.id === id);
  if (doctor) {
    document.getElementById("doctorId").value = doctor.id;
    document.getElementById("doctorName").value = doctor.name;
    document.getElementById("doctorSpecialty").value = doctor.specialty;
    document.getElementById("doctorContact").value = doctor.contact;
    document.getElementById("doctorInfo").value = doctor.info;
    document.getElementById("doctorFromDay").value = doctor.fromDay;
    document.getElementById("doctorToDay").value = doctor.toDay;
    document.getElementById("doctorStartTime").value = doctor.startTime;
    document.getElementById("doctorEndTime").value = doctor.endTime;

    document.getElementById("doctorFormHeading").textContent = "Edit Doctor";
    document.getElementById("doctorFormSubmit").textContent = "Update Doctor";
    document.getElementById("doctorFormCancel").style.display = "inline-block";
    // Ensure the add form is visible and scroll to it
    showDoctorSubSection("add");
    document.getElementById("contentDisplay").scrollTop = 0;
  }
}

function deleteDoctor(id) {
  showMessage("doctorFormMessage", `Delete Doctor (ID: ${id}) - This action would send a request to your backend to delete the record.`, "danger");
  // Simulate deletion in-memory for immediate UI feedback (remove in real app with backend)
  doctorsData = doctorsData.filter((d) => d.id !== id);
  renderDoctors();
  updateDashboardStatistics();
}

function editAppointment(id) {
  showMessage("appointmentFormMessage", `Edit Appointment (ID: ${id}) - This action would open a form with pre-filled data for editing.`, "success");
}

function deleteAppointment(id) {
  showMessage("appointmentFormMessage", `Delete Appointment (ID: ${id}) - This action would send a request to your backend to delete the record.`, "danger");
}

// --- Doctor Add/Update Form Submission (In-memory simulation) ---
document.getElementById("doctorForm").addEventListener("submit", function (event) {
  event.preventDefault();
  const id = document.getElementById("doctorId").value;
  const newDoctor = {
    id: id || generateId("D"),
    name: document.getElementById("doctorName").value,
    specialty: document.getElementById("doctorSpecialty").value,
    contact: document.getElementById("doctorContact").value,
    info: document.getElementById("doctorInfo").value,
    fromDay: document.getElementById("doctorFromDay").value,
    toDay: document.getElementById("doctorToDay").value,
    startTime: document.getElementById("doctorStartTime").value,
    endTime: document.getElementById("doctorEndTime").value,
  };

  if (id) {
    // Simulate Update existing doctor (in-memory)
    const index = doctorsData.findIndex((d) => d.id === id);
    if (index !== -1) {
      doctorsData[index] = newDoctor;
      showMessage("doctorFormMessage", "Doctor updated (in-memory) successfully!", "success");
    } else {
      showMessage("doctorFormMessage", "Doctor not found for update.", "error");
    }
  } else {
    // Simulate Add new doctor (in-memory)
    doctorsData.push(newDoctor);
    showMessage("doctorFormMessage", "Doctor added (in-memory) successfully!", "success");
  }
  resetDoctorForm(); // Clear the form
  updateDashboardStatistics();

  // --- Backend Integration Placeholder for Doctors ---
  /*
            const apiUrl = id ? `/api/doctors.php/${id}` : '/api/doctors.php';
            const method = id ? 'PUT' : 'POST';
            fetch(apiUrl, {
                method: method,
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newDoctor)
            })
            .then(response => response.json())
            .then(data => {
                showMessage('doctorFormMessage', `Doctor ${id ? 'updated' : 'added'} successfully!`, 'success');
                resetDoctorForm();
                updateDashboardStatistics();
            })
            .catch(error => {
                console.error('Error with doctor operation:', error);
                showMessage('doctorFormMessage', `Error ${id ? 'updating' : 'adding'} doctor.`, 'error');
            });
            */
});

// Function to reset Doctor form (for Cancel button or after submission)
function resetDoctorForm() {
  document.getElementById("doctorForm").reset();
  document.getElementById("doctorId").value = "";
  document.getElementById("doctorFormHeading").textContent = "Add New Doctor";
  document.getElementById("doctorFormSubmit").textContent = "Add Doctor";
  document.getElementById("doctorFormCancel").style.display = "none";
  document.getElementById("doctorFormMessage").style.display = "none";
}
document.getElementById("doctorFormCancel").addEventListener("click", resetDoctorForm);

// --- Appointment Creation (In-memory simulation) ---
document.getElementById("appointmentForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const newAppointment = {
    id: generateId("A"), // Generate a new ID for the appointment
    patientId: document.getElementById("appointmentPatient").value,
    doctorId: document.getElementById("appointmentDoctor").value,
    date: document.getElementById("appointmentDate").value,
    time: document.getElementById("appointmentTime").value,
    status: document.getElementById("appointmentStatus").value,
    paymentStatus: document.getElementById("appointmentPaymentStatus").value,
  };

  // Basic validation
  if (!newAppointment.patientId || !newAppointment.doctorId || !newAppointment.date || !newAppointment.time || !newAppointment.paymentStatus) {
    showMessage("appointmentFormMessage", "Please fill all required fields.", "error");
    return;
  }

  // Simulate adding to backend (in-memory only for this client-side demo)
  appointmentsData.push(newAppointment);
  currentAppointmentId = newAppointment.id; // Store ID for payment/download

  // Populate payment confirmation details
  const patient = patientsData.find((p) => p.id === newAppointment.patientId);
  const doctor = doctorsData.find((d) => d.id === newAppointment.doctorId);

  document.getElementById("confirmedApptId").textContent = newAppointment.id;
  document.getElementById("confirmedApptPatient").textContent = patient ? patient.name : "N/A";
  document.getElementById("confirmedApptDoctor").textContent = doctor ? doctor.name : "N/A";
  document.getElementById("confirmedApptDateTime").textContent = `${formatDateForDisplay(newAppointment.date)} at ${newAppointment.time}`;
  document.getElementById("paymentAmount").value = "50.00"; // Default payment amount
  document.getElementById("paymentMessage").style.display = "none"; // Clear previous payment messages
  document.getElementById("downloadConfirmBtn").style.display = "none"; // Hide download button initially

  // Hide create form, show payment confirmation
  createAppointmentSection.style.display = "none";
  paymentConfirmationSection.style.display = "block";
  document.getElementById("contentDisplay").scrollTop = 0; // Scroll to top of section

  showMessage("appointmentFormMessage", "Appointment created successfully! Proceed to payment.", "success");
  updateDashboardStatistics(); // Update counts

  // --- Backend Integration Placeholder for Appointments ---
  /*
            fetch('/api/appointments.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newAppointment)
            })
            .then(response => response.json())
            .then(data => {
                showMessage('appointmentFormMessage', 'Appointment created successfully!', 'success');
                currentAppointmentId = data.id; // Use ID from backend
                // Populate UI as above
                createAppointmentSection.style.display = 'none';
                paymentConfirmationSection.style.display = 'block';
                document.getElementById('contentDisplay').scrollTop = 0;
                updateDashboardStatistics();
            })
            .catch(error => {
                console.error('Error creating appointment:', error);
                showMessage('appointmentFormMessage', 'Error creating appointment.', 'error');
            });
            */
});

// --- Payment and Download Functions ---
function makePayment() {
  if (!currentAppointmentId) {
    showMessage("paymentMessage", "No appointment selected for payment.", "error");
    return;
  }

  const paymentAmount = parseFloat(document.getElementById("paymentAmount").value);
  if (isNaN(paymentAmount) || paymentAmount <= 0) {
    showMessage("paymentMessage", "Please enter a valid payment amount.", "error");
    return;
  }

  // Find the appointment and update its payment status
  const apptIndex = appointmentsData.findIndex((appt) => appt.id === currentAppointmentId);
  if (apptIndex !== -1) {
    appointmentsData[apptIndex].paymentStatus = "Paid"; // Simulate full payment
    showMessage("paymentMessage", `Payment of $${paymentAmount.toFixed(2)} recorded successfully!`, "success");
    document.getElementById("downloadConfirmBtn").style.display = "inline-block"; // Show download button
    renderAppointments(); // Re-render table to show updated status
    updateDashboardStatistics(); // Update counts if necessary
  } else {
    showMessage("paymentMessage", "Appointment not found for payment.", "error");
  }

  // --- Backend Payment Integration Placeholder ---
  /*
            fetch(`/api/payments.php`, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    appointmentId: currentAppointmentId,
                    amount: paymentAmount,
                    status: 'Paid'
                })
            })
            .then(response => response.json())
            .then(data => {
                showMessage('paymentMessage', `Payment of $${paymentAmount.toFixed(2)} recorded successfully!`, 'success');
                document.getElementById('downloadConfirmBtn').style.display = 'inline-block';
                // Update local data with confirmation from backend
                const apptIndex = appointmentsData.findIndex(appt => appt.id === currentAppointmentId);
                if (apptIndex !== -1) {
                    appointmentsData[apptIndex].paymentStatus = 'Paid';
                    renderAppointments();
                    updateDashboardStatistics();
                }
            })
            .catch(error => {
                console.error('Error processing payment:', error);
                showMessage('paymentMessage', 'Error processing payment.', 'error');
            });
            */
}

function downloadAppointmentConfirmation() {
  if (!currentAppointmentId) {
    showMessage("paymentMessage", "No appointment data to download.", "error");
    return;
  }

  const appt = appointmentsData.find((a) => a.id === currentAppointmentId);
  if (!appt) {
    showMessage("paymentMessage", "Appointment details not found for download.", "error");
    return;
  }

  const patient = patientsData.find((p) => p.id === appt.patientId);
  const doctor = doctorsData.find((d) => d.id === appt.doctorId);

  const fileContent = `
MedCare Hospital Appointment Confirmation
--------------------------------------

Appointment ID: ${appt.id}
Patient Name: ${patient ? patient.name : "N/A"}
Doctor Name: ${doctor ? doctor.name : "N/A"}
Specialty: ${doctor ? doctor.specialty : "N/A"}
Date: ${formatDateForDisplay(appt.date)}
Time: ${appt.time}
Status: ${appt.status}
Payment Status: ${appt.paymentStatus || "N/A"}
Payment Amount: $${document.getElementById("paymentAmount").value || "0.00"}

Thank you for choosing MedCare Hospital.
`;

  const blob = new Blob([fileContent], { type: "text/plain;charset=utf-8" });
  const link = document.createElement("a");
  link.href = URL.createObjectURL(blob);
  link.download = `Appointment_Confirmation_${appt.id}.txt`; // Filename
  document.body.appendChild(link); // Required for Firefox
  link.click();
  document.body.removeChild(link); // Clean up the DOM

  showMessage("paymentMessage", "Confirmation document downloaded!", "success");
}

// --- Admin Profile Management ---
const adminProfileForm = document.getElementById("adminProfileForm");
const adminNameInput = document.getElementById("adminName");
const adminEmailInput = document.getElementById("adminEmail");
const editAdminProfileBtn = document.getElementById("editAdminProfileBtn");
const saveAdminProfileBtn = document.getElementById("saveAdminProfileBtn");
const cancelAdminProfileBtn = document.getElementById("cancelAdminProfileBtn");
const adminProfileMessageDiv = document.getElementById("adminProfileMessage");

function populateAdminProfileForm() {
  adminNameInput.value = adminData.name;
  adminEmailInput.value = adminData.email;
}

function setAdminProfileInputsDisabled(disabled) {
  adminNameInput.disabled = disabled;
  adminEmailInput.disabled = disabled;
}

function enableAdminProfileEditing() {
  setAdminProfileInputsDisabled(false);
  editAdminProfileBtn.style.display = "none";
  saveAdminProfileBtn.style.display = "inline-block";
  cancelAdminProfileBtn.style.display = "inline-block";
  adminProfileMessageDiv.style.display = "none";
}

editAdminProfileBtn.addEventListener("click", enableAdminProfileEditing);

cancelAdminProfileBtn.addEventListener("click", () => {
  populateAdminProfileForm();
  setAdminProfileInputsDisabled(true);
  editAdminProfileBtn.style.display = "inline-block";
  saveAdminProfileBtn.style.display = "none";
  cancelAdminProfileBtn.style.display = "none";
  adminProfileMessageDiv.style.display = "none";
});

adminProfileForm.addEventListener("submit", function (event) {
  event.preventDefault();
  // Client-side validation for profile fields
  if (!adminNameInput.value || !adminEmailInput.value) {
    showMessage("adminProfileMessage", "Please fill in all profile fields.", "error");
    return;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(adminEmailInput.value)) {
    showMessage("adminProfileMessage", "Please enter a valid email address.", "error");
    return;
  }

  // Update dummy data (replace with actual backend update logic)
  adminData.name = adminNameInput.value;
  adminData.email = adminEmailInput.value;

  showMessage("adminProfileMessage", "Profile updated successfully!", "success");

  setTimeout(() => {
    setAdminProfileInputsDisabled(true);
    editAdminProfileBtn.style.display = "inline-block";
    saveAdminProfileBtn.style.display = "none";
    cancelAdminProfileBtn.style.display = "none";
  }, 1000);
});

// Admin Password Change
const adminPasswordChangeForm = document.getElementById("adminPasswordChangeForm");
const adminCurrentPasswordInput = document.getElementById("adminCurrentPassword");
const adminNewPasswordInput = document.getElementById("adminNewPassword");
const adminConfirmNewPasswordInput = document.getElementById("adminConfirmNewPassword");
const adminPasswordMessageDiv = document.getElementById("adminPasswordMessage");

adminPasswordChangeForm.addEventListener("submit", function (event) {
  event.preventDefault();

  const currentPass = adminCurrentPasswordInput.value;
  const newPass = adminNewPasswordInput.value;
  const confirmNewPass = adminConfirmNewPasswordInput.value;

  adminPasswordMessageDiv.style.display = "none";

  if (currentPass !== adminData.password) {
    // Dummy check against in-memory data
    showMessage("adminPasswordMessage", "Incorrect current password.", "error");
    return;
  }

  if (newPass.length < 8) {
    showMessage("adminPasswordMessage", "New password must be at least 8 characters long.", "error");
    return;
  }

  if (newPass !== confirmNewPass) {
    showMessage("adminPasswordMessage", "New passwords do not match.", "error");
    return;
  }

  if (newPass === currentPass) {
    showMessage("adminPasswordMessage", "New password cannot be the same as current password.", "error");
    return;
  }

  adminData.password = newPass; // Update dummy password
  showMessage("adminPasswordMessage", "Password changed successfully!", "success");

  setTimeout(() => {
    adminPasswordChangeForm.reset();
    adminPasswordMessageDiv.style.display = "none";
  }, 1500);
});

// --- Dashboard Statistics Update ---
function updateDashboardStatistics() {
  document.getElementById("totalPatientsCount").textContent = patientsData.length;
  document.getElementById("totalDoctorsCount").textContent = doctorsData.length;

  const today = new Date();
  today.setHours(0, 0, 0, 0);
  const todayAppointments = appointmentsData.filter((appt) => {
    const apptDate = new Date(appt.date);
    apptDate.setHours(0, 0, 0, 0);
    return apptDate.getTime() === today.getTime();
  });
  document.getElementById("todayAppointmentsCount").textContent = todayAppointments.length;
}

// --- Navigation and Content Loading ---
const menuItems = document.querySelectorAll(".sidebar-menu .menu-item");
const sections = document.querySelectorAll(".dashboard-section");
const menuToggle = document.getElementById("menuToggle");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");

function closeSidebar() {
  sidebar.classList.remove("active");
  overlay.classList.remove("active");
}

/**
 * Shows a specific sub-section within the Doctor Management tab.
 * @param {string} mode - 'add' to show the add form, 'view' to show the list.
 */
function showDoctorSubSection(mode) {
  // Hide all doctor sub-sections first
  addDoctorSection.style.display = "none";
  viewDoctorsSection.style.display = "none";
  document.getElementById("doctorFormMessage").style.display = "none"; // Hide messages too

  if (mode === "add") {
    addDoctorSection.style.display = "block";
    resetDoctorForm(); // Ensure form is reset for new entry
  } else if (mode === "view") {
    viewDoctorsSection.style.display = "block";
    renderDoctors("doctorTable"); // Render into the main doctor table
  }
}

/**
 * Shows a specific sub-section within the Appointment Management tab.
 * @param {string} mode - 'create' to show the create form, 'view' to show the list, 'payment' for payment section.
 */
function showAppointmentSubSection(mode) {
  // Hide all appointment sub-sections first
  createAppointmentSection.style.display = "none";
  viewAppointmentsSection.style.display = "none";
  paymentConfirmationSection.style.display = "none"; // Hide payment section
  document.getElementById("appointmentFormMessage").style.display = "none"; // Hide messages too

  if (mode === "create") {
    createAppointmentSection.style.display = "block";
    document.getElementById("appointmentForm").reset(); // Reset form
    populateAppointmentDropdowns(); // Refresh dropdowns
    currentAppointmentId = null; // Clear current appointment context
  } else if (mode === "view") {
    viewAppointmentsSection.style.display = "block";
    renderAppointments(); // Render into the main appointment table
  } else if (mode === "payment") {
    paymentConfirmationSection.style.display = "block";
  }
}

/**
 * Loads content into the main display area.
 * @param {string} contentId - The ID of the content section to display (e.g., 'patients', 'doctors').
 */
function loadContent(contentId) {
  sections.forEach((section) => {
    section.style.display = "none";
  });
  const targetSection = document.getElementById(`content-${contentId}`);
  if (targetSection) {
    targetSection.style.display = "block";
  }

  // Logic for specific sections
  switch (contentId) {
    case "patients":
      renderPatients();
      break;
    case "doctors":
      // When doctors section is loaded from sidebar, default to viewing the list
      showDoctorSubSection("view");
      break;
    case "appointments":
      // When appointments section is loaded from sidebar, default to viewing the list
      showAppointmentSubSection("view");
      break;
    case "profile":
      populateAdminProfileForm();
      setAdminProfileInputsDisabled(true);
      editAdminProfileBtn.style.display = "inline-block";
      saveAdminProfileBtn.style.display = "none";
      cancelAdminProfileBtn.style.display = "none";
      document.getElementById("adminPasswordChangeForm").reset();
      document.getElementById("adminProfileMessage").style.display = "none";
      document.getElementById("adminPasswordMessage").style.display = "none";
      break;
    case "logout":
      setTimeout(() => {
        window.location.href = "./login.html"; // Redirect to login page
      }, 1500);
      break;
  }

  // Highlight active menu item
  menuItems.forEach((item) => {
    item.classList.remove("active");
  });
  const activeItem = document.querySelector(`.menu-item[data-content="${contentId}"]`);
  if (activeItem) {
    activeItem.classList.add("active");
  }
  closeSidebar(); // Close sidebar after selecting an item (important for mobile)
}

// Event Listeners for Sidebar Menu
menuItems.forEach((item) => {
  item.addEventListener("click", (event) => {
    event.preventDefault();
    const contentId = item.dataset.content;
    loadContent(contentId);
  });
});

// Mobile menu toggle
menuToggle.addEventListener("click", () => {
  sidebar.classList.toggle("active");
  overlay.classList.toggle("active");
});

overlay.addEventListener("click", closeSidebar); // Close sidebar when clicking outside

// Initial load
document.addEventListener("DOMContentLoaded", () => {
  // Get references to sub-sections after DOM is loaded
  addDoctorSection = document.getElementById("addDoctorSection");
  viewDoctorsSection = document.getElementById("viewDoctorsSection");
  createAppointmentSection = document.getElementById("createAppointmentSection");
  viewAppointmentsSection = document.getElementById("viewAppointmentsSection");
  paymentConfirmationSection = document.getElementById("paymentConfirmationSection"); // Initialize new section reference

  loadContent("home"); // Load dashboard home by default
});
