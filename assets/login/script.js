function validateForm() {
  const username = document.getElementById("username").value;
  const password = document.getElementById("password").value;
  const errorMessage = document.getElementsByClassName("error");

  if (username === "") {
    errorMessage[0].innerHTML = "Please enter your username";
    errorMessage[0].style.display = "block";
    return false;
  } else if (password === "") {
    errorMessage[1].innerHTML = "Please enter your password";
    errorMessage[1].style.display = "block";
    return false;
  }

  return true;
}
