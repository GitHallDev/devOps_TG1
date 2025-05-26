<?php
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['user'])){
    header('Location: /login');
    exit();
}
// Vérifier si l'utilisateur a le rôle d'individu
    if($_SESSION['user']['role'] !== 'individus'){
        echo '<script> alert(" Vous n\'avez pas accès à cette page 😊 !") <\script>';
        header('Location: /login');
        exit();
    }

    // get id in the url
    $id_prop = filter_input(INPUT_GET, 'id_prop', FILTER_VALIDATE_INT);
    if ($id_prop === false) {
        // Gérer l'erreur, par exemple, rediriger ou afficher un message d'erreur
            $id_prop = intval($id_prop);
        if($id_prop <= 0){
        echo '<script> alert(" Vous n\'avez pas accès à cette page 😊 !") <\script>';
        header('Location: /');
        exit();}
    }  
    $id_prop = intval($id_prop);

?>
<link rel="stylesheet" href="./styles/GestionCandidature/style_candidatureForm.css">
<form action="/candidater" id = "candidature-form" method="post" enctype= "multipart/form-data">
    <h2>Candidater</h2>
Selectionner les fichiers à uploader 
<b>CV * :</b>
<input type="file" name="cv" id="cv_upload" accept=".pdf" required> 
<label  class= "dropzone"  for="cv_upload">
    <ion-icon   name="cloud-upload"></ion-icon>
    <b>Cliquez pour télécharger</b>or Drag and drop your file here 
    PDF
</label>
<b>Lettre de motivation * :</b>
<input type="file" name="cover_letter" id="cover_letter_upload" accept=".pdf" required>
<label  class= "dropzone" for="cover_letter_upload">
    <ion-icon name="cloud-upload"></ion-icon>
    <b>Cliquez pour télécharger</b>or Drag and drop your file here 
    PDF
</label>
<input type="hidden" name="statuts" value="en_cours">
<input type="hidden" name="id_user" value="<?=$_SESSION['user']['ID_user']?>"> <!-- id de l'utilisateur recuparable dans les variables de session -->
<input type="hidden" name="id_prop" value="<?= $id_prop?>"><!-- Id de la proposition -->
<span>NB: Format autorisé .Pdf </span>
<input type="submit" id = "submit-btn"value="Candidater">
</form>

<script src="./scripts/GestionCandidature/candidatureForm.js"></script>
<script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
