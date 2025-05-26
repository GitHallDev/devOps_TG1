<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
    <link rel="stylesheet" href="./styles/GestionCompte/login_style.css">
</head>
<body>
  <div class="wrapper">
    <form action="/login" method="POST">
      <h2>Login</h2>
      <?php if(isset($_SESSION['error'])): ?>
        <div class="error-message">
          <?php 
            echo $_SESSION['error'];
            unset($_SESSION['error']);
          ?>
        </div>
      <?php endif; ?>
      <div class="input-field">
      <input type="email" name ="email" required>
      <label>Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Enter your password</label>
      </div>
      <div class="forget">
        <label for="remember">
          <input type="checkbox" id="remember">
          <p>Remember me</p>
        </label>
        <!-- <a href="#">Forgot password?</a> -->
      </div>
      <button type="submit">Log In</button>
      <div class="register">
        <p>Don't have an account? <a href="/register">Register</a></p>
      </div>
    </form>
  </div>
</body>
</html>