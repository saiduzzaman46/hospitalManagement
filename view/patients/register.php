<script>
  
  window.addEventListener('pageshow', function (event) {
    if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
      window.location.reload();
    }
  });
</script>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Patient Registration - MedCare Hospital</title>
  <link
    href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap"
    rel="stylesheet" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
  <link rel="stylesheet" href="../../assets/patients/register.css" />
</head>

<body>
  <div class="register-container">
    <h2 class="register-title">Patient Registration</h2>
    <p class="register-description">
      Create your account to book appointments and access your health records.
    </p>

    <form id="registrationForm" class="register-form" onsubmit="return validateFormRegister()" method="POST" action="../../controller/reg_con.php">
      <input type="hidden" id="userType" name="userType" value="patient" />

      <div class="form-row">
        <div class="form-group">
          <label for="fullName"><i class="fas fa-user"></i> Full Name</label>
          <input
            type="text"
            id="fullName"
            name="fullName"
            placeholder="Enter your full name" />
        </div>

        <div class="form-group">
          <label for="email"><i class="fas fa-envelope"></i> Email Address</label>
          <input
            type="email"
            id="email"
            name="email"
            placeholder="Enter your email address" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="password"><i class="fas fa-lock"></i> Password</label>
          <input
            type="password"
            id="password"
            name="password"
            placeholder="Create a password" />
        </div>

        <div class="form-group">
          <label for="confirmPassword"><i class="fas fa-check-circle"></i> Confirm Password</label>
          <input
            type="password"
            id="confirmPassword"
            name="confirmPassword"
            placeholder="Confirm your password" />
        </div>
      </div>

      <div class="form-row">
        <div class="form-group">
          <label for="phone"><i class="fas fa-phone"></i> Phone Number</label>
          <input
            type="tel"
            id="phone"
            name="phone"
            placeholder="e.g., +8801712345678" />
        </div>

        <div class="form-group">
          <label for="dob"><i class="fas fa-calendar-alt"></i> Date of Birth</label>
          <input type="date" id="dob" name="dob" />
        </div>
      </div>


      <div class="form-row">
        <div class="form-group">
          <label for="gender"><i class="fas fa-venus-mars"></i> Gender</label>
          <select id="gender" name="gender">
            <option value="">Select Gender</option>
            <option value="male">Male</option>
            <option value="female">Female</option>
            <option value="other">Other</option>
          </select>
        </div>

        <div class="form-group">
          <label for="address"><i class="fas fa-map-marker-alt"></i> Address</label>
          <textarea
            id="address"
            name="address"
            rows="3"
            placeholder="Enter your full address"></textarea>
        </div>
      </div>

      <?php
      session_start();
      if (isset($_SESSION['errorRegister']['email'])) {
        echo '<p id="message">' . htmlspecialchars($_SESSION['errorRegister']['email']) . '</p>';
      } else if (isset($_SESSION['errorRegister']['phone'])) {
        echo '<p id="message">' . htmlspecialchars($_SESSION['errorRegister']['phone']) . '</p>';
      } else {
        echo '<p id="message"></p>';
      }
      ?>
      <input type="submit" name="submit" value="Register" class="btn btn-primary">

    </form>

    <!-- <button type="submit">Register Account</button> -->
    <!-- <div id="message" class="message-box"></div> -->

    <div class="back-to-login">
      Already have an account? <a href="../../view/login/login.php">Login here</a>
    </div>
  </div>

  <script src="../../assets/patients/register.js"></script>
</body>

</html>