<?php
    session_start();
    $user = $_SESSION['user'] ?? null;
    // echo "<pre>";
    // print_r($user);
    // echo "</pre>"
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link rel="stylesheet" href="./styles/variable_style.css">
    <link rel="stylesheet" href="./styles/GestionCompte/Edit_account.css">
    <style>

    </style>
</head>
<body>
    <div class="wrapper">
        <form action="/EditAccount" method="POST" class="edit-account-form">
            <h2>Edit Account</h2>
            <div class="input-field">
                <input type="text" id="prenom" name="prenom" value=<?php echo $user['prenom'] ?> required>
                <label for="name"><b>Prenom</b></label>
            </div>
            <div class="input-field">
                <input type="text" id="name" name="nom" value=<?php echo $user['nom'] ?> required>
                <label for="name"><b>Nom</b></label>
            </div>
            <div class="input-field">
                <input type="email" id="email" name="email" value=<?php echo $user['email'] ?> required>
                <label for="email"><b>Email</b></label>
            </div>
            <div class="input-field">
                <input type="password" id="password" name="password" placeholder="Laissez vide pour ne pas changer">
                <!-- <label for="password"><b>Nouveau mot de passe</b></label> -->
            </div>
            <button type="submit" class="save-btn">Save Changes</button>
            <a href="/profile" class="back-link">&#8592; Back to Profile</a>
        </form>
    </div>
</body>
</html> 