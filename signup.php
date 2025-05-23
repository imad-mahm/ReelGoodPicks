<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sign Up</title>
    <link rel="stylesheet" href="css/style.css" />
  </head>
  <body
    style="
      display: flex;
      justify-content: center;
      align-items: center;
      height: 100vh;
    "
  >
    <div class="signup-container">
      <form id="signupForm" action = "signuplogic.php" method="POST">
        <div class="input-group">
          <label for="fullname">Full Name</label>
          <input type="text" id="fullname" name="fullname" required />
        </div>
        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" required />
        </div>
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <div class="input-group">
          <label for="confirm-password">Confirm Password</label>
          <input
            type="password"
            id="confirm-password"
            name="confirm-password"
            required
          />
        </div>
        <div class="input-group">
          <label for="dob">Date of Birth</label>
          <input type="date" id="dob" name="dob" required />
        </div>
        <button type="submit">
          <a id="signup" href="index.html">Sign Up</a>
        </button>
      </form>
      <?php if (!empty($_SESSION['error'])){ 
        echo "<p id = 'error-message' style='color: red'>". $_SESSION['error']  . "</p>";
        unset($_SESSION['error']); // Clear the error message after displaying it
      } ?> 
      <p id="loginLink">
        Already have an account? <a href="login.php">Login</a>
      </p>
    </div>

    <script src="login.js"></script>
  </body>
</html>
