// Path: assets/doctor/add_medicine.js
let entryIndex = 1;
document.getElementById("addMoreMedicine").addEventListener("click", function () {
  const container = document.getElementById("medicineEntries");
  const firstEntry = container.querySelector(".medicine-entry");
  const clone = firstEntry.cloneNode(true);

  // Clear input values
  clone.querySelectorAll("input, textarea, select").forEach((input) => {
    if (input.type === "checkbox" || input.type === "radio") {
      input.checked = false;
    } else {
      input.value = "";
    }
  });

  // Update name attributes for uniqueness
  clone.querySelectorAll("[name]").forEach((el) => {
    const name = el.getAttribute("name");
    const newName = name.replace(/\[\d+\]/, `[${entryIndex}]`);
    el.setAttribute("name", newName);
  });

  container.appendChild(clone);
  entryIndex++;
});

document.addEventListener("click", function (e) {
  if (e.target.classList.contains("remove-entry")) {
    const entry = e.target.closest(".medicine-entry");
    const allEntries = document.querySelectorAll(".medicine-entry");
    if (allEntries.length > 1) {
      entry.remove();
    }
  }
});