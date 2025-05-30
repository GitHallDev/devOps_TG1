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

use Modules\GestionCandidatureModule\Controller\CandidatureManagerController;

$controller = new CandidatureManagerController();
$candidatures = $controller::getAllCandidatures();

function filterByStatut($tab, $status) {
    return array_filter($tab, function ($candidature) use ($status) {
        return $candidature['statuts'] === $status;
    });
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/GestionCandidature/style_pageCandidature.css">
    <title>Gestion Candidature</title>
</head>
<body>
    <!-- header -->
    <?php include 'header.php';?>
    <main>
        <!-- sidebare -->
    <?php  include 'sidebar.php';?>
        <!-- content -->
         <div class="div-content">
            <H2>Gestion Candidature</H2>

            <!-- hearder-content  -->
            <div class="header-content">
                <div class="header-infos">
                    <span>Total</span><br>
                    <b><?=count($candidatures)?></b>
                </div>
                
                <div class="header-content-separator"></div>
                <div class="header-infos">
                    <span>Validées</span><br>
                    <b><?=count(filterByStatut($candidatures, 'accepter'))?></b>
                </div>
                
                <div class="header-content-separator"></div>
                <div class="header-infos">
                    <span>Refusers</span><br>
                    <b><?=count(filterByStatut($candidatures, 'rejeter'))?></b>
                </div>

                <div class="header-content-separator"></div>
                <div class="header-infos">
                    <span>En_cours</span><br>
                    <b><?=count(filterByStatut($candidatures, 'en_cours'))?></b>
                </div>
            </div>

            <!-- body content -->
            <div class="body-content">
                <div>
                    <div class="search-bar">
                        <img src="./ressources/search.svg" alt="icon loupe">
                        <input type="search" name="Filtre" id="Filtre" placeholder="Search here..." class="input-search">
                    </div>
                    <div class="body-content-separator"></div>
                    <button class="filter-btn">
                        <img src="./ressources/filter.svg" alt="filtre">
                        <span>Filtre</span>
                    </button>
                </div>
                <div class="content-tab">
                    <table id="table-candidature">
                        <thead>
                            <tr>
                                <th>Candidature ID</th>
                                <th>CV</th>
                                <th>Lettre Motivation</th>
                                <th>Statut</th>
                                <th>Proposition stage ID</th>
                                <th>Options</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Exemple de ligne de tableau -->
                            <?php foreach ($candidatures as $candidature): ?>
                                <tr>
                                    <td>#<?= $candidature['ID_cand'] ?></td>
                                    <td>
                                        <a href="<?php echo "./candidatureCV?id=".$candidature['ID_cand']; ?>" >
                                            <img src="./ressources/eye-closed.svg" alt="icon oeil" class="icon-eye">
                                        </a>
                                    </td>
                                    <td>
                                        <a href="<?php echo "./candidatureLM?id=".$candidature['ID_cand']; ?>" >
                                            <img src="./ressources/eye-closed.svg" alt="icon oeil" class="icon-eye">
                                        </a>
                                    </td>
                                    <td class="statut <?= $candidature['statuts']?>" >
                                    <div class="point-statut"></div>
                                    <?= $candidature['statuts']?>
                                    </td>
                                    <td >
                                        <button data-id_prop="<?= $candidature['ID_prop'] ?>" class ="btn-infos-prop">
                                            <?= $candidature['ID_prop'] ?>
                                        </button>
                                    </td>
                                    <td><button class="btn-options" anchor-name="--btn-anchor-<?=$candidature['ID_cand']?>" data-id="<?=$candidature['ID_cand']?>" data-id_user="<?=$candidature['ID_user']?>" data-id_prop="<?=$candidature['ID_prop']?>">...</button></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <div class="pagination">
                        <span class="page"></span>
                        <div>
                            <button class="btn_prev"><ion-icon name="chevron-back-outline"></ion-icon>Previous</button>
                            <button class="btn_next">Next<ion-icon name="chevron-forward-outline"></ion-icon></button>
                        </div>
                    </div>
                </div>                                
            </div>
        </div>

    </main>
    <footer>           
        <div  popover="manual" id="pop-up-infosProposition">
        </div>
        <div popover id="pop-up-option">
            <button id ="btn-option-delete" popovertarget="delete-cand-dialog"><ion-icon name="trash"></ion-icon> Delete</button>
            <button id="btn-option-change-statuts" popovertarget="changeStatus-cand-dialog"><ion-icon name="repeat"></ion-icon> Change Statuts</button>
        </div>
        <div popover="manual" id="delete-cand-dialog">
            <ion-icon name="trash"></ion-icon><br>
            <span>Are you sure ?</span>
            <p>this action cannot be undone . <br> Proceed with deleton ?</p>
            <div>
                <button id="btn-delete-cand">Delete</button>
                <button id="btn-cancel-dialog-delete">Cancel</button>
            </div>
        </div>
        <div popover="manual" id="changeStatus-cand-dialog">
            <ion-icon name="trail-sign"></ion-icon><br>
            <span>Select one Statut</span>
            <form id="ChangeStatutForm" action="" method="post">
                <input type="radio" name="statuts" id="statut-accepter" value="accepter" required>
                <label for="statut-accepter">Accepter</label><br>
                <input type="radio" name="statuts" id="statut-refuser" value="rejeter" required>
                <label for="statut-refuser">Refuser</label><br>
                <div>
                    <button id="btn-change-statuts">Confirmer</button>
                    <button id="btn-cancel-dialog-change">Cancel</button>
                </div>
            </form>
        </div>
        <div popover id="pop-up-create-stage-effectif">
                <button popoverTarget="pop-up-create-stage-effectif"><ion-icon name="close"></ion-icon></button>

            <div class="pop-up-header">
                <h3>Créer Stage Effectif</h3>
            </div>
            <div class="pop-up-content">
            <form action="/createStageEffectif" method="post">
                <div>
                    <label for="duration">Durée Effective :</label>
                    <input type="number" name="duration" id="duration-stage-effectif" readonly min="1" max="12" required> mois
                    <label for="remunaration">Remuneration Effective :</label>
                    <input type="number" min="0" name="remuneration_effective" id="remuneration-stage-effectif" required> FCFA/mois
                    <input type="hidden" name="id_cand" id = "id_cand">
                    <input type="hidden" name="id_prop" id = "id_prop">
                    <input type="hidden" name="id_user" id = "id_user">
                </div><br>
                <label for="sujet">Sujet Effectif :</label><br>
                <textarea name="sujet_effectif" id="sujet-stage-effectif" rows="6" cols="40" required></textarea><br><br>
                <label for="encadreur">Encadreur :</label>
                <input type="text" name="encadreur" id="encadreur-stage-effectif" placeholder="nom-prenom-ID" required><br>
                <button type="submit">Créer</button>
            </form>
            </div>
        </div>
    </footer>
    <script src="./scripts/GestionCandidature/pagesCandidatures.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>