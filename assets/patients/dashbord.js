// const doctorsData = [
//   {
//     name: "Dr. Emily Watson",
//     specialty: "Cardiologist",
//     description: "Specializing in heart health, Dr. Watson provides expert care for various cardiac conditions.",
//     availableTimes: ["Mon: 9-12 PM", "Wed: 2-5 PM"],
//   },
//   {
//     name: "Dr. John Smith",
//     specialty: "Pediatrician",
//     description: "Dedicated to children's health, Dr. Smith ensures comprehensive and gentle care for your little ones.",
//     availableTimes: ["Tue: 10-1 PM", "Thu: 9-12 PM"],
//   },
//   {
//     name: "Dr. Sarah Chen",
//     specialty: "Dermatologist",
//     description: "Expert in skin conditions, Dr. Chen offers personalized treatment plans for healthy and radiant skin.",
//     availableTimes: ["Mon: 1-4 PM", "Fri: 9-12 PM"],
//   },
//   {
//     name: "Dr. Michael Lee",
//     specialty: "Orthopedic Surgeon",
//     description: "Specializing in bone and joint health, Dr. Lee provides advanced surgical and non-surgical solutions.",
//     availableTimes: ["Wed: 9-12 PM", "Fri: 1-4 PM"],
//   },
//   {
//     name: "Dr. Laura Green",
//     specialty: "General Practitioner",
//     description: "Your primary care physician for routine check-ups, preventive care, and common ailments.",
//     availableTimes: ["Mon: 9-1 PM", "Tue: 2-5 PM", "Thu: 9-1 PM"],
//   },
//   {
//     name: "Dr. David Kim",
//     specialty: "Neurologist",
//     description: "Specializing in disorders of the nervous system, including the brain, spinal cord, and nerves.",
//     availableTimes: ["Tue: 9-12 PM", "Thu: 2-5 PM"],
//   },
//   {
//     name: "Dr. Anya Sharma",
//     specialty: "Endocrinologist",
//     description: "Focuses on hormonal imbalances and metabolic disorders like diabetes and thyroid issues.",
//     availableTimes: ["Wed: 10-1 PM", "Fri: 2-5 PM"],
//   },
//   {
//     name: "Dr. Ben Carter",
//     specialty: "Gastroenterologist",
//     description: "Diagnoses and treats conditions of the digestive system, including stomach, intestine, and liver.",
//     availableTimes: ["Mon: 2-5 PM", "Thu: 10-1 PM"],
//   },
// ];

// function renderDoctors(doctorsToRender) {
//   const doctorGrid = document.getElementById("doctorGrid");
//   doctorGrid.innerHTML = "";

//   if (doctorsToRender.length === 0) {
//     doctorGrid.innerHTML = '<p style="text-align: center; color: #64748b; font-size: 1.8rem; margin-top: 3rem;">No doctors found matching your criteria.</p>';
//     return;
//   }

//   doctorsToRender.forEach((doctor) => {
//     const doctorCard = document.createElement("div");
//     doctorCard.className = "doctor-card";
//     doctorCard.innerHTML = `
//                     <div class="doctor-avatar"><i class="fas fa-user-md"></i></div>
//                     <h3>${doctor.name}</h3>
//                     <p class="doctor-specialty">${doctor.specialty}</p>
//                     <p>${doctor.description}</p>
//                     <div class="available-times">
//                         <strong>Available:</strong> ${doctor.availableTimes.join(", ")}
//                     </div>
//                     <div class="card-buttons">
//                         <a href="#" class="button button-secondary">Book Now</a>
//                         <a href="#" class="button button-outline">View Details</a>
//                     </div>
//                 `;
//     doctorGrid.appendChild(doctorCard);
//   });
// }

// function filterDoctors() {
//   const nameSearch = document.getElementById("doctorNameSearch").value.toLowerCase();
//   const categoryFilter = document.getElementById("doctorCategorySelect").value;

//   const filtered = doctorsData.filter((doctor) => {
//     const matchesName = doctor.name.toLowerCase().includes(nameSearch);
//     const matchesCategory = categoryFilter === "" || doctor.specialty === categoryFilter;
//     return matchesName && matchesCategory;
//   });

//   renderDoctors(filtered);
// }

// Ensure the DOM is fully loaded before running the script
document.addEventListener("DOMContentLoaded", () => {
  // --- Sidebar Toggle Logic ---
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");

  // Function to close the sidebar and overlay
  function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  }

  // Event listener for the menu toggle button
  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
  });

  overlay.addEventListener("click", closeSidebar);

  closeSidebar();
});
