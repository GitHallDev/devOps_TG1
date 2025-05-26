<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
    <link rel="stylesheet" href="./styles/GestionCompte/login_style.css">
</head>
<body>
  <div class="wrapper">
    <form action="/register" method="POST">
      <h2>Sign Up</h2>
      <div class="input-field">
        <input type="text" name ="nom" required>
        <label>Enter your name</label>
      </div>
      <div class="input-field">
        <input type="text" name ="prenom" required>
        <label>Enter your first name</label>
      </div>
        <div class="input-field">
        <input type="email" name ="email" required>
        <label>Enter your email</label>
      </div>
      <div class="input-field">
        <input type="password" name="password" required>
        <label>Enter your password</label>
      </div>
      <button type="submit">Sign Up</button>
      <div class="register">
        <p>Already have an account? <a href="/login">Login</a></p>
      </div>
    </form>
  </div>
</body>
</html>














<!-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/GestionCompte/style_auth.css">
    <title>Document</title>
</head>
<body>
    <div class="container">
        <h1>Inscription</h1>
        <form action="/register" method="POST">
            <input type="text" name="nom" id="" placeholder="Nom" required> <br>
            <input type="text" name="prenom" id="" placeholder="Prenom" required><br>
            <input type="email" name="email" id="" placeholder="Email" required><br>
            <input type="password" name="password" id="" placeholder = "Mot de passe" required> <br>
            <button type="submit">S'inscrire</button>
        </form>
    </div>
</body>
</html> -->

