<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>MedCare Login</title>
  <link rel="stylesheet" href="../../assets/login/style.css" />

</head>

<body>
  <div class="login-container">
    <h2 class="title">Login</h2>

    <form id="loginForm" onsubmit="return validateForm()" class="flex flex-col gap-4" method="POST" action="../../controller/login_con.php">
      <div class="form-group">
        <label for="Phone Number">Phone Number</label>
        <input
          type="text"
          id="username"
          name="phone"
          placeholder="Enter your phone number" />
        <?php
        if (isset($_SESSION['errorLogin']['phone'])) {
          echo '<p class="error">' . $_SESSION['errorLogin']['phone'] . '</p>';
          unset($_SESSION['errorLogin']['phone']);
        } else {
          echo '<p class="error"></p>';
        }
        ?>
      </div>

      <div class="form-group">
        <label for="password">Password</label>
        <input
          type="password"
          id="password"
          name="password"
          placeholder="Enter your password" />
        <p class="error"></p>

        <?php
        if (isset($_SESSION['errorLogin']['password'])) {
          echo '<p class="error">' . $_SESSION['errorLogin']['password'] . '</p>';
          unset($_SESSION['errorLogin']['password']);
        } else {
          echo '<p class="error"></p>';
        }
        ?>
      </div>

      <button type="submit">Login</button>
      <!-- <div class="back-to-register">
        Don't have an account? <a href="../../view/logil">Create Account</a>
      </div> -->

    </form>
  </div>
  <script src="../../assets/login/script.js"></script>
</body>

</html>