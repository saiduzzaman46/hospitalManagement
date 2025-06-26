  // --- Doctor Filter/Search AJAX Logic ---
const nameInput = document.getElementById("doctorNameSearch");
const specialtySelect = document.getElementById("doctorCategorySelect");
const doctorGrid = document.getElementById("doctorGrid");

function fetchDoctors() {
  const name = nameInput.value.trim();
  const specialty = specialtySelect.value;

  const xhr = new XMLHttpRequest();
  xhr.open("GET", "../../controller/load_doctors.php?name=" + encodeURIComponent(name) + "&specialty=" + encodeURIComponent(specialty), true);
  xhr.onreadystatechange = function () {
    if (xhr.status === 200) {
      doctorGrid.innerHTML = xhr.responseText;
    }
  };
  xhr.send();
}

// Attach listeners
nameInput.addEventListener("input", fetchDoctors);
specialtySelect.addEventListener("change", fetchDoctors);

// Initial load
fetchDoctors();