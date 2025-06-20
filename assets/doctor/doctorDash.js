// Dummy data for patients
const patientsData = [
  { id: "P001", name: "Jane Doe", lastVisit: "2025-06-15", notes: "Stable condition." },
  { id: "P002", name: "Robert Brown", lastVisit: "2025-06-10", notes: "Follow-up needed." },
  { id: "P003", name: "Alice Johnson", lastVisit: "2025-06-01", notes: "Routine check-up." },
  { id: "P004", name: "Michael Davis", lastVisit: "2025-06-01", notes: "Routine check-up." },
  { id: "P005", name: "Emily White", lastVisit: "2025-05-28", notes: "Prescription refilled." },
  { id: "P006", name: "Olivia Taylor", lastVisit: "2025-06-18", notes: "New patient." },
];

// Dummy data for the currently logged-in doctor
const doctorData = {
  fullName: "Dr. John Smith",
  email: "dr.john.smith@medcare.com",
  phone: "+1 (555) 234-5678",
  specialty: "Pediatrician", // Updated specialty for consistency
  clinicAddress: "789 Medical Lane, Health City, USA",
  availability: "Mon, Wed, Fri: 9 AM - 5 PM",
};

// Dummy data for appointments
// The current date based on your context is Friday, June 20, 2025.
// Appointments with '2025-06-20' should show as 'today'.
let appointmentsData = [
  // Made `let` to allow potential in-memory "deletions" or status changes
  { id: "A001", patientId: "P001", patientName: "Jane Doe", specialty: "Pediatrics", date: "2025-06-20", time: "10:00 AM", status: "Confirmed" },
  { id: "A002", patientId: "P002", patientName: "Robert Brown", specialty: "Pediatrics", date: "2025-06-20", time: "11:00 AM", status: "Confirmed" },
  { id: "A003", patientId: "P003", patientName: "Alice Johnson", specialty: "Pediatrics", date: "2025-06-22", time: "09:00 AM", status: "Confirmed" }, // Upcoming
  { id: "A004", patientId: "P004", patientName: "Michael Davis", specialty: "Pediatrics", date: "2025-06-22", time: "10:30 AM", status: "Confirmed" }, // Upcoming
  { id: "A005", patientId: "P005", patientName: "Emily White", specialty: "Pediatrics", date: "2025-06-18", time: "03:00 PM", status: "Completed" }, // Past
  { id: "A006", patientId: "P006", patientName: "Olivia Taylor", specialty: "Pediatrics", date: "2025-06-17", time: "02:00 PM", status: "Completed" }, // Past
];

// Dummy data for prescriptions
let prescriptionsData = [
  { id: "RX001", patientId: "P001", medication: "Ibuprofen", dosage: "400mg, twice daily", instructions: "Take with food. For pain management.", refills: 2, notes: "" },
  {
    id: "RX002",
    patientId: "P001",
    medication: "Amoxicillin",
    dosage: "250mg, three times daily",
    instructions: "Complete the full course of antibiotics.",
    refills: 0,
    notes: "Follow-up in 7 days.",
  },
  { id: "RX003", patientId: "P002", medication: "Lisinopril", dosage: "10mg, once daily", instructions: "Take in the morning.", refills: 3, notes: "Monitor blood pressure." },
];

let currentPatientForPrescriptions = null; // Stores patient object for prescriptions page

/**
 * Shows a message box with success or error style.
 * For doctor dashboard, actions like delete/edit are conceptual.
 */
function showMessage(elementId, message, type) {
  const msgBox = document.getElementById(elementId);
  msgBox.textContent = message;
  msgBox.className = `message-box message-${type}`;
  msgBox.style.display = "block";
  setTimeout(() => {
    msgBox.style.display = "none";
  }, 3000);
}

/**
 * Renders patient data in a table format.
 * @param {Array<Object>} patientsToRender - An array of patient objects to display.
 */
function renderPatients(patientsToRender) {
  const patientTableBody = document.querySelector("#patientTable tbody");
  patientTableBody.innerHTML = ""; // Clear existing patient rows

  if (!patientsToRender || patientsToRender.length === 0) {
    patientTableBody.innerHTML = '<tr><td colspan="4" style="text-align: center; color: #64748b; padding: 2rem;">No patients found matching your criteria.</td></tr>';
    return;
  }

  patientsToRender.forEach((patient) => {
    const tableRow = document.createElement("tr");
    tableRow.innerHTML = `
                    <td>${patient.id}</td>
                    <td>${patient.name}</td>
                    <td>${patient.lastVisit}</td>
                    <td>
                        <div class="action-buttons">
                            <a href="#" class="button button-secondary">View Details</a>
                        </div>
                    </td>
                `;
    patientTableBody.appendChild(tableRow);
  });
}

/**
 * Fetches and filters patients based on search input and filter selection.
 * This function simulates fetching from a backend.
 * You will need to implement the actual PHP backend to serve this data from your database.
 */
async function fetchAndFilterPatients() {
  const nameSearch = document.getElementById("patientNameSearch").value;
  const patientFilter = document.getElementById("patientFilter").value;

  // Simulate network request delay (remove in production)
  // await new Promise(resolve => setTimeout(resolve, 500));

  // For demonstration, filtering dummy data
  const filtered = patientsData.filter((patient) => {
    const matchesName = patient.name.toLowerCase().includes(nameSearch.toLowerCase());
    let matchesFilter = true; // Assume true if no filter selected

    if (patientFilter === "Recent") {
      const lastVisitDate = new Date(patient.lastVisit);
      const now = new Date();
      const diffTime = Math.abs(now - lastVisitDate);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      matchesFilter = diffDays <= 30; // Last 30 days
    } else if (patientFilter === "Active") {
      matchesFilter = patient.name.includes("Alice") || patient.name.includes("Robert");
    } else if (patientFilter === "Follow-up") {
      matchesFilter = patient.notes && patient.notes.includes("Follow-up");
    }

    return matchesName && matchesFilter;
  });
  renderPatients(filtered);
}

/**
 * Handles the delete action for an appointment.
 * This is a client-side simulation.
 */
function deleteAppointment(appointmentId) {
  showMessage("appointmentMessage", `Delete Appointment (ID: ${appointmentId}) - This action would send a request to your backend to delete the record.`, "danger");
  // In a real application, you would send a DELETE request to your backend:
  /*
            fetch(`/api/appointments.php/${appointmentId}`, { method: 'DELETE' })
                .then(response => {
                    if (response.ok) {
                        // Remove from in-memory array if successful
                        appointmentsData = appointmentsData.filter(appt => appt.id !== appointmentId);
                        renderAppointmentsList('today'); // Re-render relevant section
                        showMessage('appointmentMessage', `Appointment ${appointmentId} deleted successfully!`, 'success');
                    } else {
                        showMessage('appointmentMessage', `Failed to delete appointment ${appointmentId}.`, 'error');
                    }
                })
                .catch(error => {
                    console.error('Error deleting appointment:', error);
                    showMessage('appointmentMessage', `Error deleting appointment ${appointmentId}.`, 'error');
                });
            */
}

/**
 * Handles the prescriptions action for an appointment.
 * Navigates to the prescriptions page for the given patient.
 */
function viewPrescriptions(patientId, appointmentId) {
  const patient = patientsData.find((p) => p.id === patientId);
  if (patient) {
    currentPatientForPrescriptions = patient; // Store patient data
    loadContent("prescriptions"); // Load the prescriptions section
  } else {
    showMessage("appointmentMessage", "Patient details not found for prescriptions.", "error");
  }
}

/**
 * Renders prescriptions for the current patient.
 */
function renderPrescriptions() {
  const currentPrescriptionsList = document.getElementById("currentPrescriptionsList");
  currentPrescriptionsList.innerHTML = "<h3>Current Prescriptions</h3>"; // Reset the content

  if (!currentPatientForPrescriptions) {
    currentPrescriptionsList.innerHTML += '<p style="text-align: center; color: #64748b; padding: 1rem;">Please select a patient to view prescriptions.</p>';
    return;
  }

  const patientPrescriptions = prescriptionsData.filter((rx) => rx.patientId === currentPatientForPrescriptions.id);

  if (patientPrescriptions.length === 0) {
    currentPrescriptionsList.innerHTML += '<p style="text-align: center; color: #64748b; padding: 1rem;">No prescriptions available for this patient.</p>';
    return;
  }

  patientPrescriptions.forEach((rx) => {
    currentPrescriptionsList.innerHTML += `
                    <div style="border: 1px solid #e2e8f0; border-radius: var(--border-radius-base); padding: 1.5rem; margin-bottom: 1.5rem; background-color: #ffffff;">
                        <p><strong>Medication:</strong> ${rx.medication}</p>
                        <p><strong>Dosage:</strong> ${rx.dosage}</p>
                        <p><strong>Instructions:</strong> ${rx.instructions}</p>
                        <p><strong>Refills:</strong> ${rx.refills}</p>
                        <p><strong>Notes:</strong> ${rx.notes || "N/A"}</p>
                    </div>
                `;
  });
}

/**
 * Handles adding a new prescription (in-memory simulation).
 */
document.getElementById("addPrescriptionForm").addEventListener("submit", function (event) {
  event.preventDefault();

  const patientId = document.getElementById("addPrescriptionPatientId").value;
  const medicationName = document.getElementById("medicationName").value;
  const dosage = document.getElementById("dosage").value;
  const instructions = document.getElementById("instructions").value;
  const refills = document.getElementById("refills").value;
  const notes = document.getElementById("prescriptionNotes").value;

  if (!patientId || !medicationName || !dosage || !instructions) {
    showMessage("addPrescriptionMessage", "Please fill in all required fields.", "error");
    return;
  }

  const newPrescription = {
    id: "RX" + generateId(""), // Simple ID for new prescription
    patientId: patientId,
    medication: medicationName,
    dosage: dosage,
    instructions: instructions,
    refills: parseInt(refills, 10),
    notes: notes,
  };

  prescriptionsData.push(newPrescription);
  showMessage("addPrescriptionMessage", "Prescription added successfully!", "success");
  document.getElementById("addPrescriptionForm").reset(); // Clear form

  // Go back to prescriptions view for the same patient after adding
  loadContent("prescriptions");

  // In a real app, you would send this to your PHP backend:
  /*
            fetch('/api/prescriptions.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify(newPrescription)
            })
            .then(response => response.json())
            .then(data => {
                showMessage('addPrescriptionMessage', 'Prescription added successfully!', 'success');
                prescriptionsData.push(data); // If backend returns the saved object
                loadContent('prescriptions'); // Load view page
            })
            .catch(error => {
                console.error('Error adding prescription:', error);
                showMessage('addPrescriptionMessage', 'Error adding prescription.', 'error');
            });
            */
});

/**
 * Renders appointments based on the provided filter into tables.
 * @param {string} filter - 'today', 'upcoming', 'past', or 'all'.
 */
function renderAppointmentsList(filter = "all") {
  const todayTableBody = document.getElementById("todayAppointmentsTableBody");
  const upcomingTableBody = document.getElementById("upcomingAppointmentsTableBody");
  const pastTableBody = document.getElementById("pastAppointmentsTableBody");
  const appointmentsHeading = document.getElementById("appointmentsHeading");

  // Clear previous table bodies
  todayTableBody.innerHTML = "";
  upcomingTableBody.innerHTML = "";
  pastTableBody.innerHTML = "";

  const today = new Date();
  today.setHours(0, 0, 0, 0); // Normalize to start of today

  let appointmentsTodayCount = 0;

  // Update the Appointments Today count on the home card
  const appointmentsTodayCardCount = document.getElementById("appointmentsTodayCount");

  // Hide all appointment sections initially, then show based on filter
  document.getElementById("todayAppointmentsContainer").style.display = "none";
  document.getElementById("upcomingAppointmentsContainer").style.display = "none";
  document.getElementById("pastAppointmentsContainer").style.display = "none";

  if (filter === "today") {
    appointmentsHeading.textContent = "My Appointments - Today";
    document.getElementById("todayAppointmentsContainer").style.display = "block";

    const filteredTodayAppts = appointmentsData.filter((appt) => {
      const apptDate = new Date(appt.date);
      apptDate.setHours(0, 0, 0, 0);
      return apptDate.getTime() === today.getTime();
    });
    appointmentsTodayCount = filteredTodayAppts.length;

    if (filteredTodayAppts.length === 0) {
      todayTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No appointments scheduled for today.</td></tr>';
    } else {
      filteredTodayAppts.forEach((appt) => {
        todayTableBody.innerHTML += `
                            <tr>
                                <td>${appt.patientName}</td>
                                <td>${appt.specialty}</td>
                                <td>${appt.time}</td>
                                <td>${appt.status}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="button button-danger" onclick="deleteAppointment('${appt.id}')">Delete</button>
                                        <button class="button button-secondary" onclick="viewPrescriptions('${appt.patientId}', '${appt.id}')">Prescriptions</button>
                                    </div>
                                </td>
                            </tr>
                        `;
      });
    }
  } else {
    // 'all' (default), or any other general view
    appointmentsHeading.textContent = "My Appointments"; // Default heading
    document.getElementById("todayAppointmentsContainer").style.display = "block";
    document.getElementById("upcomingAppointmentsContainer").style.display = "block";
    document.getElementById("pastAppointmentsContainer").style.display = "block";

    const todayAppts = appointmentsData.filter((appt) => {
      const apptDate = new Date(appt.date);
      apptDate.setHours(0, 0, 0, 0);
      return apptDate.getTime() === today.getTime();
    });
    appointmentsTodayCount = todayAppts.length; // Count for home card

    if (todayAppts.length === 0) {
      todayTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No appointments scheduled for today.</td></tr>';
    } else {
      todayAppts.forEach((appt) => {
        todayTableBody.innerHTML += `
                            <tr>
                                <td>${appt.patientName}</td>
                                <td>${appt.specialty}</td>
                                <td>${appt.time}</td>
                                <td>${appt.status}</td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="button button-danger" onclick="deleteAppointment('${appt.id}')">Delete</button>
                                        <button class="button button-secondary" onclick="viewPrescriptions('${appt.patientId}', '${appt.id}')">Prescriptions</button>
                                    </div>
                                </td>
                            </tr>
                        `;
      });
    }

    const upcomingAppts = appointmentsData
      .filter((appt) => {
        const apptDate = new Date(appt.date);
        apptDate.setHours(0, 0, 0, 0);
        return apptDate.getTime() > today.getTime();
      })
      .sort((a, b) => new Date(a.date).getTime() - new Date(b.date).getTime()); // Sort upcoming by date

    if (upcomingAppts.length === 0) {
      upcomingTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No upcoming appointments.</td></tr>';
    } else {
      upcomingAppts.forEach((appt) => {
        upcomingTableBody.innerHTML += `
                            <tr>
                                <td>${appt.patientName}</td>
                                <td>${appt.specialty}</td>
                                <td>${appt.date}</td>
                                <td>${appt.time}</td>
                                <td>${appt.status}</td>
                            </tr>
                        `;
      });
    }

    const pastAppts = appointmentsData
      .filter((appt) => {
        const apptDate = new Date(appt.date);
        apptDate.setHours(0, 0, 0, 0);
        return apptDate.getTime() < today.getTime();
      })
      .sort((a, b) => new Date(b.date).getTime() - new Date(a.date).getTime()); // Sort past by date (most recent first)

    if (pastAppts.length === 0) {
      pastTableBody.innerHTML = '<tr><td colspan="5" style="text-align: center; padding: 2rem;">No past appointments.</td></tr>';
    } else {
      pastAppts.forEach((appt) => {
        pastTableBody.innerHTML += `
                            <tr>
                                <td>${appt.patientName}</td>
                                <td>${appt.specialty}</td>
                                <td>${appt.date}</td>
                                <td>${appt.time}</td>
                                <td>${appt.status}</td>
                            </tr>
                        `;
      });
    }
  }

  // Update the count on the home dashboard card
  if (appointmentsTodayCardCount) {
    appointmentsTodayCardCount.textContent = appointmentsTodayCount;
  }
}

document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".sidebar-menu .menu-item");
  const sections = document.querySelectorAll(".dashboard-section");
  const doctorNamePlaceholder = document.querySelector(".doctor-info span");
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");

  const profileForm = document.getElementById("profileForm");
  const profileNameInput = document.getElementById("profileName");
  const profileEmailInput = document.getElementById("profileEmail");
  const profilePhoneInput = document.getElementById("profilePhone");
  const profileSpecialtyInput = document.getElementById("profileSpecialty");
  const profileAddressInput = document.getElementById("profileAddress");
  const profileAvailabilityInput = document.getElementById("profileAvailability");

  const editProfileBtn = document.getElementById("editProfileBtn");
  const saveProfileBtn = document.getElementById("saveProfileBtn");
  const cancelProfileBtn = document.getElementById("cancelProfileBtn");
  const profileMessageDiv = document.getElementById("profileMessage");

  const passwordChangeForm = document.getElementById("passwordChangeForm");
  const currentPasswordInput = document.getElementById("currentPassword");
  const newPasswordInput = document.getElementById("newPassword");
  const confirmNewPasswordInput = document.getElementById("confirmNewPassword");
  const passwordMessageDiv = document.getElementById("passwordMessage");

  // Set initial doctor's name in header
  doctorNamePlaceholder.textContent = `Welcome, ${doctorData.fullName}!`;

  function populateProfileForm() {
    profileNameInput.value = doctorData.fullName;
    profileEmailInput.value = doctorData.email;
    profilePhoneInput.value = doctorData.phone;
    profileSpecialtyInput.value = doctorData.specialty;
    profileAddressInput.value = doctorData.clinicAddress;
    profileAvailabilityInput.value = doctorData.availability;
  }

  function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  }

  /**
   * Loads content into the main display area.
   * @param {string} contentId - The ID of the content section to display (e.g., 'home', 'appointments').
   * @param {string} filter - Optional filter for appointments ('today', 'all').
   */
  function loadContent(contentId, filter = "all") {
    // Added filter parameter
    console.log(`Loading content: ${contentId} with filter: ${filter}`); // Debugging log
    sections.forEach((section) => {
      section.style.display = "none";
    });
    const targetSection = document.getElementById(`content-${contentId}`);
    if (targetSection) {
      targetSection.style.display = "block";
    }

    if (contentId === "patients") {
      fetchAndFilterPatients(); // Fetch and render patients when patients section is active
    } else if (contentId === "appointments") {
      renderAppointmentsList(filter); // Render appointments with specified filter
      document.getElementById("appointmentMessage").style.display = "none"; // Clear any previous messages
    } else if (contentId === "prescriptions") {
      if (currentPatientForPrescriptions) {
        document.getElementById("prescriptionsHeading").textContent = `Prescriptions for ${currentPatientForPrescriptions.name}`;
        document.getElementById("currentPatientName").textContent = currentPatientForPrescriptions.name;
        renderPrescriptions(); // Render prescriptions for the selected patient
      } else {
        document.getElementById("prescriptionsHeading").textContent = "Prescriptions";
        document.getElementById("currentPatientName").textContent = "a patient";
        document.getElementById("currentPrescriptionsList").innerHTML =
          '<h3>Current Prescriptions</h3><p style="text-align: center; color: #64748b; padding: 1rem;">No patient selected to view prescriptions.</p>';
      }
    } else if (contentId === "add-prescription") {
      if (currentPatientForPrescriptions) {
        document.getElementById("addPrescriptionPatientName").textContent = currentPatientForPrescriptions.name;
        document.getElementById("addPrescriptionPatientId").value = currentPatientForPrescriptions.id;
        document.getElementById("addPrescriptionForm").reset(); // Clear form on entry
        document.getElementById("addPrescriptionMessage").style.display = "none";
      } else {
        // Should ideally not happen if flow is correct, but for safety:
        showMessage("addPrescriptionMessage", "Error: No patient selected to add prescription.", "error");
        loadContent("appointments"); // Redirect to appointments if no patient context
      }
    }

    if (contentId === "profile") {
      populateProfileForm();
      setProfileInputsDisabled(true); // Always disable initially on load
      editProfileBtn.style.display = "inline-block";
      saveProfileBtn.style.display = "none";
      cancelProfileBtn.style.display = "none";
    } else {
      passwordChangeForm.reset();
      profileMessageDiv.style.display = "none";
      passwordMessageDiv.style.display = "none";
    }

    menuItems.forEach((item) => {
      item.classList.remove("active");
    });
    const activeItem = document.querySelector(`.menu-item[data-content="${contentId}"]`);
    if (activeItem) {
      activeItem.classList.add("active");
    }
    closeSidebar(); // Close sidebar after selecting an item (important for mobile)

    if (contentId === "logout") {
      setTimeout(() => {
        window.location.href = "./login.html"; // Redirect to login page on logout
      }, 1500);
    }
  }

  // Event Listeners for Sidebar Menu
  menuItems.forEach((item) => {
    item.addEventListener("click", (event) => {
      event.preventDefault();
      const contentId = item.dataset.content;
      // When clicking a sidebar menu item, we generally want to see ALL appointments (default filter)
      loadContent(contentId, contentId === "appointments" ? "all" : "all");
    });
  });

  // Mobile menu toggle
  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
  });

  overlay.addEventListener("click", closeSidebar); // Close sidebar when clicking outside

  // Event listeners for patient search/filter controls
  document.getElementById("patientNameSearch").addEventListener("input", fetchAndFilterPatients);
  document.getElementById("patientFilter").addEventListener("change", fetchAndFilterPatients);

  // Profile Editing Logic
  function setProfileInputsDisabled(disabled) {
    profileNameInput.disabled = disabled;
    profileEmailInput.disabled = disabled;
    profilePhoneInput.disabled = disabled;
    profileSpecialtyInput.disabled = disabled;
    profileAddressInput.disabled = disabled;
    profileAvailabilityInput.disabled = disabled;
  }

  function enableProfileEditing() {
    setProfileInputsDisabled(false);
    editProfileBtn.style.display = "none";
    saveProfileBtn.style.display = "inline-block";
    cancelProfileBtn.style.display = "inline-block";
    profileMessageDiv.style.display = "none";
  }

  editProfileBtn.addEventListener("click", enableProfileEditing);

  cancelProfileBtn.addEventListener("click", () => {
    populateProfileForm(); // Revert to initial loaded data
    setProfileInputsDisabled(true);
    editProfileBtn.style.display = "inline-block";
    saveProfileBtn.style.display = "none";
    cancelProfileBtn.style.display = "none";
    profileMessageDiv.style.display = "none";
  });

  profileForm.addEventListener("submit", function (event) {
    event.preventDefault();
    // Client-side validation for profile fields
    if (!profileNameInput.value || !profileEmailInput.value || !profilePhoneInput.value || !profileSpecialtyInput.value || !profileAddressInput.value || !profileAvailabilityInput.value) {
      profileMessageDiv.textContent = "Please fill in all profile fields.";
      profileMessageDiv.className = "message-box message-error";
      profileMessageDiv.style.display = "block";
      return;
    }
    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(profileEmailInput.value)) {
      profileMessageDiv.textContent = "Please enter a valid email address.";
      profileMessageDiv.className = "message-box message-error";
      profileMessageDiv.style.display = "block";
      return;
    }
    const phonePattern = new RegExp(profilePhoneInput.pattern);
    if (!phonePattern.test(profilePhoneInput.value)) {
      profileMessageDiv.textContent = profilePhoneInput.title;
      profileMessageDiv.className = "message-box message-error";
      profileMessageDiv.style.display = "block";
      return;
    }

    // Update dummy data (replace with actual backend update logic)
    doctorData.fullName = profileNameInput.value;
    doctorData.email = profileEmailInput.value;
    doctorData.phone = profilePhoneInput.value;
    doctorData.specialty = profileSpecialtyInput.value;
    doctorData.clinicAddress = profileAddressInput.value;
    doctorData.availability = profileAvailabilityInput.value;

    profileMessageDiv.textContent = "Profile updated successfully!";
    profileMessageDiv.className = "message-box message-success";
    profileMessageDiv.style.display = "block";

    setTimeout(() => {
      setProfileInputsDisabled(true);
      editProfileBtn.style.display = "inline-block";
      saveProfileBtn.style.display = "none";
      cancelProfileBtn.style.display = "none";
      doctorNamePlaceholder.textContent = `Welcome, ${doctorData.fullName}!`;
    }, 1000);
  });

  // Password Change Logic
  passwordChangeForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const currentPass = currentPasswordInput.value;
    const newPass = newPasswordInput.value;
    const confirmNewPass = confirmNewPasswordInput.value;

    passwordMessageDiv.style.display = "none";

    if (currentPass !== "password123") {
      // Dummy check: replace with actual backend validation
      passwordMessageDiv.textContent = "Incorrect current password.";
      passwordMessageDiv.className = "message-box message-error";
      passwordMessageDiv.style.display = "block";
      return;
    }

    if (newPass.length < 8) {
      passwordMessageDiv.textContent = "New password must be at least 8 characters long.";
      passwordMessageDiv.className = "message-box message-error";
      passwordMessageDiv.style.display = "block";
      return;
    }

    if (newPass !== confirmNewPass) {
      passwordMessageDiv.textContent = "New passwords do not match.";
      passwordMessageDiv.className = "message-box message-error";
      passwordMessageDiv.style.display = "block";
      return;
    }

    if (newPass === currentPass) {
      passwordMessageDiv.textContent = "New password cannot be the same as current password.";
      passwordMessageDiv.className = "message-box message-error";
      passwordMessageDiv.style.display = "block";
      return;
    }

    passwordMessageDiv.textContent = "Password changed successfully!";
    passwordMessageDiv.className = "message-box message-success";
    passwordMessageDiv.style.display = "block";

    setTimeout(() => {
      passwordChangeForm.reset();
      passwordMessageDiv.style.display = "none";
    }, 1500);
  });

  // Initial load of home section and update appointments count
  loadContent("home");
  // Initial call to render all appointments for the overview count on home page.
  // This function also updates the 'appointmentsTodayCount' element.
  renderAppointmentsList("all");
});
