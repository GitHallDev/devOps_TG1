<?php 
session_start();

if(!isset($_SESSION['user'])) {
    header('Location: /login');
    exit;
}
if($_SESSION['user']['role'] !== 'GC') {
    header('Location: /login');
    exit;
}

use Modules\GestionCandidatureModule\Controller\CandidatureManagerController;

$controller = new CandidatureManagerController();
$candidatures = $controller::getAllCandidatures();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style_pageCandidature.css">
    <title>Gestion Candidature</title>
</head>
<body>
    <!-- header -->
    <header>
        <div class="notification">
            <img src="./ressources/notification.svg" alt="">
            <div class="notification-badge">2</div>
        </div>
        <div class="separatorHeader"></div>
        <div class="profile"><img src="./ressources/profile_img.svg" alt="profile image"></div>

    </header>
    <main>
        <!-- sidebare -->
        <div class="sidebar">
            <div class="logo"><img src="./ressources/logo.png" alt=""></div>
            <div class="menu">
                <ul><h2>CANDIDATURES</h2>
                    <li><a href="#"><img src="./ressources/candidatures.svg" alt="icon candidatures">Candidatures</a></li>
                    <h2>STAGES</h2>
                    <li><a href="#"><img src="./ressources/stages.svg" alt="icon stages">Stages</a></li>
                    <li><a href="#"><img src="./ressources/soutenances.svg" alt="">Soutenances</a></li>
                </ul>
            </div>
        </div>
        <!-- content -->
         <div class="div-content">
            <H2>Gestion Candidature</H2>

            <!-- hearder-content  -->
            <div class="header-content">
                <div class="header-infos">
                    <span>Total</span><br>
                    <b>350</b>
                </div>
                
                <div class="header-content-separator"></div>
                <div class="header-infos">
                    <span>Validées</span><br>
                    <b>50</b>
                </div>
                
                <div class="header-content-separator"></div>
                <div class="header-infos">
                    <span>Refusers</span><br>
                    <b>150</b>
                </div>

                <div class="header-content-separator"></div>
                <div class="header-infos">
                    <span>En_cours</span><br>
                    <b>50</b>
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
                                    <td>#<?php echo $candidature['ID_cand']; ?></td>
                                    <td><a href="<?php echo "./candidatureCV?id=".$candidature['ID_cand']; ?>" ><img src="./ressources/eye-closed.svg" alt="icon oeil" class="icon-eye"></a></td>
                                    <td><a href="<?php echo "./candidatureLM?id=".$candidature['ID_cand']; ?>" ><img src="./ressources/eye-closed.svg" alt="icon oeil" class="icon-eye"></a></td>
                                    <td class="statut <?php echo $candidature['statuts'];?>" >
                                    <div class="point-statut"></div>
                                    <?php echo $candidature['statuts']; ?>
                                    </td>
                                    <td><?php echo $candidature['ID_prop']; ?></td>
                                    <td><button class="btn">...</button></td>
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
    <script src="./scripts/pagesCandidatures.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
<script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
</body>
</html>