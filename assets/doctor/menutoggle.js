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
});

function filterTable(input) {
  const searchValue = input.value.toLowerCase();
  const tables = document.querySelectorAll(".appointments-table");
  tables.forEach((table) => {
    const rows = table.querySelectorAll("tr");
    rows.forEach((row) => {
      const name = row.children[0]?.textContent.toLowerCase() || "";
      const phone = row.children[1]?.textContent.toLowerCase() || "";
      if (name.includes(searchValue) || phone.includes(searchValue)) {
        row.style.display = "";
      } else {
        row.style.display = "none";
      }
    });
  });
}

function filterTablePatients(input) {
  const filter = input.value.toLowerCase();
  const table = document.getElementById("patientTable");
  const rows = table.getElementsByTagName("tr");

  for (let i = 1; i < rows.length; i++) {
    const nameCell = rows[i].getElementsByTagName("td")[1]; // name column
    const phoneCell = rows[i].getElementsByTagName("td")[2]; // phone column

    if (nameCell && phoneCell) {
      const name = nameCell.textContent.toLowerCase();
      const phone = phoneCell.textContent.toLowerCase();
      if (name.includes(filter) || phone.includes(filter)) {
        rows[i].style.display = "";
      } else {
        rows[i].style.display = "none";
      }
    }
  }
}

function updateAppointmentStatus(appointmentId, newStatus) {
  fetch("../../controller/update_appointment_status.php", {
    method: "POST",
    headers: {
      "Content-Type": "application/x-www-form-urlencoded",
    },
    body: `id=${appointmentId}&status=${newStatus}`,
  })
    .then((response) => response.text())
    .then((data) => {
      // console.log(data); // Optional: log success or failure
      location.reload(); // Refresh to reflect updated status
    })
    .catch((error) => {
      console.error("Error updating appointment status:", error);
    });
}

