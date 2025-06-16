function validateForm() {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const errorMessage = document.getElementsByClassName("error");

  if (username === "") {
    errorMessage[0].innerHTML = "Username cannot be empty";
    errorMessage[0].style.display = "block";
    return false;
  } else if (password === "") {
    errorMessage[1].innerHTML = "Password cannot be empty";
    errorMessage[1].style.display = "block";
    return false;
  }

  return true;
}
