// --- Client-Side JS for interactivity (not for data storage) ---

// Helper function to show messages (similar to your patient dashboard)
function showMessage(elementId, message, type) {
  const msgBox = document.getElementById(elementId);
  msgBox.textContent = message;
  msgBox.className = `message-box message-${type}`;
  msgBox.style.display = "block";
  setTimeout(() => {
    msgBox.style.display = "none";
  }, 3000);
}

// Sidebar and Overlay functionality
document.addEventListener("DOMContentLoaded", () => {
  const menuToggle = document.getElementById("menuToggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");
  const menuItems = document.querySelectorAll(".sidebar-menu .menu-item");


  menuToggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
  });

  overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
  });
  menuItems.forEach((item) => {
    item.addEventListener("click", (event) => {
      
      sidebar.classList.remove("active"); 
      overlay.classList.remove("active");
    });
  });

  // For today's appointments, you'd need a PHP function to get today's count from DB
  // document.getElementById('todayAppointmentsCount').textContent = '<?php
  //                                                                     $today_date = date('Y-m-d'); // Current date in YYYY-MM-DD format
  //                                                                     $count = 0;
  //                                                                     foreach ($appointments as $appt) {
  //                                                                         if ($appt['date'] === $today_date) {
  //                                                                             $count++;
  //                                                                         }
  //                                                                     }
  //                                                                     echo $count;
  //                                                                     ?>';
});

function confirmAction(id) {
  if (confirm("Are you sure you want to delete this patient?")) {
    document.getElementById("deletePatientId").value = id;
    document.getElementById("deleteForm").submit();
  }
}
