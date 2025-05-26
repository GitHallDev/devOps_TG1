<?php
    session_start();
    $user = $_SESSION['user'];

    if(!$user){
        header("Location: /Login");
        exit();
    }

    if($user['role'] != 'individus'){
        header("Location: /Accueil");
        exit();
    }

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="./styles/variable_style.css">
    <link rel="stylesheet" href="./styles/GestionCompte/profile.css">
</head>
<body>
    <div class="profile-container">
        <div class="profile-header">
            <img src="./ressources/logo.png" alt="Logo" class="profile-logo">
            <span class="profile-title">Profile</span>
        </div>
        <div class="profile-avatar-section">
            <img src="./ressources/profile_img.svg" alt="Profile" class="profile-avatar">
            <div class="profile-name"><?php echo $user['nom']." ".$user['prenom']?></div>
            <div class="profile-email"><?php echo $user['email']?></div>
        </div>
        <div class="profile-tabs">
            <button class="tab active">Stage</button>
            <span class="tab-icon"><img src="./ressources/profile_img.svg" alt="Profile Icon" height="24"></span>
        </div>
        <div class="profile-links">
            <a href="/EditAccount">Edit compte <span class="arrow">&gt;</span></a>
            <a href="/IntershipHistory">Internship history <span class="arrow">&gt;</span></a>
            <a href="/candidacy">Candidacy <span class="arrow">&gt;</span></a>
            <a href="/Accueil">Retourner à l'Accueil<span class="arrow">&gt;</span></a>
        </div>

        <form action= '/logout' method='GET' class="logout-form">
            <button type="submit" class="logout-btn">Log out</button>
        </form>
    </div>
</body>
</html> 