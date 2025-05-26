<?php
session_start();

if(!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
if($_SESSION['user']['role'] !== 'GC') {
    echo '<script> alert(" Vous n\'avez pas accès à cette page 😊 !") </script>';
    header('Location: /Accueil');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Soutenances</title>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.css' rel='stylesheet' />
    <link href='styles/GestionCandidature/calendar_style.css' rel='stylesheet' />
</head>
<body>
    <?php include 'header.php'?>
    <?php include 'sidebar.php'?>
    <div class="container">
        <h1>Gestion des Soutenances</h1>
        <div id="calendar"></div>

        <!-- Modal pour les détails de la soutenance -->
        <div id="soutenanceModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2>Détails de la Soutenance</h2>
                <div id="soutenanceDetails">
                    <p><strong>Date:</strong> <span id="modalDate"></span></p>
                    <p><strong>Étudiant:</strong> <span id="modalEtudiant"></span></p>
                    <h3>Jury</h3>
                    <div id="modalJury"></div>
                    <p><strong>Statut:</strong> <span id="modalStatut"></span></p>
                    
                    <div class="actions">
                        <button id="btnValider" class="btn-success">Valider</button>
                        <button id="btnRefuser" class="btn-danger">Refuser</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.js'></script>
    <script src="scripts/GestionCandidature/calendar.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>