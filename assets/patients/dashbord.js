const doctorsData = [
  {
    name: "Dr. Emily Watson",
    specialty: "Cardiologist",
    description: "Specializing in heart health, Dr. Watson provides expert care for various cardiac conditions.",
    availableTimes: ["Mon: 9-12 PM", "Wed: 2-5 PM"],
  },
  {
    name: "Dr. John Smith",
    specialty: "Pediatrician",
    description: "Dedicated to children's health, Dr. Smith ensures comprehensive and gentle care for your little ones.",
    availableTimes: ["Tue: 10-1 PM", "Thu: 9-12 PM"],
  },
  {
    name: "Dr. Sarah Chen",
    specialty: "Dermatologist",
    description: "Expert in skin conditions, Dr. Chen offers personalized treatment plans for healthy and radiant skin.",
    availableTimes: ["Mon: 1-4 PM", "Fri: 9-12 PM"],
  },
  {
    name: "Dr. Michael Lee",
    specialty: "Orthopedic Surgeon",
    description: "Specializing in bone and joint health, Dr. Lee provides advanced surgical and non-surgical solutions.",
    availableTimes: ["Wed: 9-12 PM", "Fri: 1-4 PM"],
  },
  {
    name: "Dr. Laura Green",
    specialty: "General Practitioner",
    description: "Your primary care physician for routine check-ups, preventive care, and common ailments.",
    availableTimes: ["Mon: 9-1 PM", "Tue: 2-5 PM", "Thu: 9-1 PM"],
  },
  {
    name: "Dr. David Kim",
    specialty: "Neurologist",
    description: "Specializing in disorders of the nervous system, including the brain, spinal cord, and nerves.",
    availableTimes: ["Tue: 9-12 PM", "Thu: 2-5 PM"],
  },
  {
    name: "Dr. Anya Sharma",
    specialty: "Endocrinologist",
    description: "Focuses on hormonal imbalances and metabolic disorders like diabetes and thyroid issues.",
    availableTimes: ["Wed: 10-1 PM", "Fri: 2-5 PM"],
  },
  {
    name: "Dr. Ben Carter",
    specialty: "Gastroenterologist",
    description: "Diagnoses and treats conditions of the digestive system, including stomach, intestine, and liver.",
    availableTimes: ["Mon: 2-5 PM", "Thu: 10-1 PM"],
  },
];

function renderDoctors(doctorsToRender) {
  const doctorGrid = document.getElementById("doctorGrid");
  doctorGrid.innerHTML = "";

  if (doctorsToRender.length === 0) {
    doctorGrid.innerHTML = '<p style="text-align: center; color: #64748b; font-size: 1.8rem; margin-top: 3rem;">No doctors found matching your criteria.</p>';
    return;
  }

  doctorsToRender.forEach((doctor) => {
    const doctorCard = document.createElement("div");
    doctorCard.className = "doctor-card";
    doctorCard.innerHTML = `
                    <div class="doctor-avatar"><i class="fas fa-user-md"></i></div>
                    <h3>${doctor.name}</h3>
                    <p class="doctor-specialty">${doctor.specialty}</p>
                    <p>${doctor.description}</p>
                    <div class="available-times">
                        <strong>Available:</strong> ${doctor.availableTimes.join(", ")}
                    </div>
                    <div class="card-buttons">
                        <a href="#" class="button button-secondary">Book Now</a>
                        <a href="#" class="button button-outline">View Details</a>
                    </div>
                `;
    doctorGrid.appendChild(doctorCard);
  });
}

function filterDoctors() {
  const nameSearch = document.getElementById("doctorNameSearch").value.toLowerCase();
  const categoryFilter = document.getElementById("doctorCategorySelect").value;

  const filtered = doctorsData.filter((doctor) => {
    const matchesName = doctor.name.toLowerCase().includes(nameSearch);
    const matchesCategory = categoryFilter === "" || doctor.specialty === categoryFilter;
    return matchesName && matchesCategory;
  });

  renderDoctors(filtered);
}

document.addEventListener("DOMContentLoaded", () => {
  const menuItems = document.querySelectorAll(".sidebar-menu .menu-item");
  const sections = document.querySelectorAll(".dashboard-section");
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");

  const profileForm = document.getElementById("profileForm");
  const profileNameInput = document.getElementById("profileName");
  const profileEmailInput = document.getElementById("profileEmail");
  const profilePhoneInput = document.getElementById("profilePhone");
  const profileAddressInput = document.getElementById("profileAddress");
  const profileGenderSelect = document.getElementById("profileGender");
  const profileDobInput = document.getElementById("profileDob");

  const editProfileBtn = document.getElementById("editProfileBtn");
  const saveProfileBtn = document.getElementById("saveProfileBtn");
  const cancelProfileBtn = document.getElementById("cancelProfileBtn");
  const profileMessageDiv = document.getElementById("profileMessage");

  const passwordChangeForm = document.getElementById("passwordChangeForm");
  const currentPasswordInput = document.getElementById("currentPassword");
  const newPasswordInput = document.getElementById("newPassword");
  const confirmNewPasswordInput = document.getElementById("confirmNewPassword");
  const passwordMessageDiv = document.getElementById("passwordMessage");

  function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  }

  function loadContent(contentId) {
    sections.forEach((section) => {
      section.style.display = "none";
    });
    const targetSection = document.getElementById(`content-${contentId}`);
    if (targetSection) {
      targetSection.style.display = "block";
    }

    if (contentId === "doctors") {
      filterDoctors();
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
    closeSidebar(); // Close sidebar after selecting an item

    if (contentId === "logout") {
      setTimeout(() => {
        window.location.href = "./login.html";
      }, 1500);
    }
  }

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

  document.getElementById("doctorNameSearch").addEventListener("input", filterDoctors);
  document.getElementById("doctorCategorySelect").addEventListener("change", filterDoctors);

  function setProfileInputsDisabled(disabled) {
    profileNameInput.disabled = disabled;
    profileEmailInput.disabled = disabled;
    profilePhoneInput.disabled = disabled;
    profileAddressInput.disabled = disabled;
    profileGenderSelect.disabled = disabled;
    profileDobInput.disabled = disabled;
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
    populateProfileForm();
    setProfileInputsDisabled(true);
    editProfileBtn.style.display = "inline-block";
    saveProfileBtn.style.display = "none";
    cancelProfileBtn.style.display = "none";
    profileMessageDiv.style.display = "none";
  });

  profileForm.addEventListener("submit", function (event) {
    event.preventDefault();
    // Client-side validation for profile fields
    if (!profileNameInput.value || !profileEmailInput.value || !profilePhoneInput.value || !profileAddressInput.value || !profileGenderSelect.value || !profileDobInput.value) {
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
    const today = new Date();
    const birthDate = new Date(profileDobInput.value);
    if (birthDate >= today) {
      profileMessageDiv.textContent = "Date of Birth cannot be in the future.";
      profileMessageDiv.className = "message-box message-error";
      profileMessageDiv.style.display = "block";
      return;
    }

    profileMessageDiv.textContent = "Profile updated successfully!";
    profileMessageDiv.className = "message-box message-success";
    profileMessageDiv.style.display = "block";

    setTimeout(() => {
      setProfileInputsDisabled(true);
      editProfileBtn.style.display = "inline-block";
      saveProfileBtn.style.display = "none";
      cancelProfileBtn.style.display = "none";
      patientNamePlaceholder.textContent = `Welcome, ${patientData.fullName}!`;
    }, 1000);
  });

  passwordChangeForm.addEventListener("submit", function (event) {
    event.preventDefault();

    const currentPass = currentPasswordInput.value;
    const newPass = newPasswordInput.value;
    const confirmNewPass = confirmNewPasswordInput.value;

    passwordMessageDiv.style.display = "none";

    if (currentPass !== "password123") {
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

  loadContent("home");
});
