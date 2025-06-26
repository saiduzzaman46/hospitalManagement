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

document.addEventListener("DOMContentLoaded", () => {
  // --- Sidebar Toggle Logic ---
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");

  function closeSidebar() {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  }

  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
  });

  overlay.addEventListener("click", closeSidebar);
  closeSidebar();
});
