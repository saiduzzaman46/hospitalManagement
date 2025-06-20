function validateFormRegister() {
  const fullName = document.getElementById("fullName").value;
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  const confirmPassword = document.getElementById("confirmPassword").value;
  const phone = document.getElementById("phone").value;
  const dob = document.getElementById("dob").value;
  const gender = document.getElementById("gender").value;
  const address = document.getElementById("address").value;
  const userType = document.getElementById("userType").value;
  const messageDiv = document.getElementById("message");

  if (!fullName || !email || !password || !confirmPassword || !phone || !dob || !gender || !address) {
    messageDiv.textContent = "Please fill in all required fields.";
    return false;
  }
  if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
    messageDiv.textContent = "Please enter a valid email address.";
    return false;
  }

  if (password.length < 8) {
    messageDiv.textContent = "Password must be at least 8 characters long.";
    return false;
  }

  if (password !== confirmPassword) {
    messageDiv.textContent = "Passwords do not match.";
    return false;
  }

  const phoneInput = document.getElementById("phone");
  if (!phoneInput.checkValidity()) {
    messageDiv.textContent = "Please enter a valid phone number.";
    return false;
  }
  return true;
}
