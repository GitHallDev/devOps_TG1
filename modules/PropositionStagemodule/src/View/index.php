<?php
    session_start();
    use Modules\PropositionStagemodule\Controller\PropositionStageController;
    
    $controller = new PropositionStageController();
    $latestPropositions = $controller->getLatestPropositions(10);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page d'accueil</title>
    
    <link rel="stylesheet" href="./styles\GestionPropositionStage\style.css">
</head>
<body>
    <?php
    // Include the header
    include 'header.php';
    ?>
    <main>
        <section class="featured">
            <h1 class="section-title">Propositions de Stage en Vedette</h1>
            <div class="card-container">
                <?php foreach ($latestPropositions as $proposition): ?>
                <article class="card">
                    <p><strong>Sujet :</strong> <?= htmlspecialchars($proposition['sujet']) ?></p>
                    <p><strong>Durée :</strong> <?= htmlspecialchars($proposition['Duree']) ?> mois</p>
                <p><strong>Rémunération :</strong> <?= $proposition['remuneration'] > 0 ? htmlspecialchars($proposition['remuneration']) . ' FCFA/mois' : 'Non rémunéré' ?></p>
                    <p><strong>Créé par :</strong> <?= htmlspecialchars($proposition['create_by']) ?></p>
                    <?php 
                    if(isset($_SESSION['user'])){
                        if($_SESSION['user']['role']=='individus'){

                    ?>
                    <div><a href="./candidater?id_prop=<?=$proposition['ID_prop']?>">Candidater</a></div>
                    <?php }
                            }?>
                </article>
                <?php endforeach; ?>
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <a href="/proposition_stage" class="btn btn-primary">Voir toutes les propositions</a>
            </div>
        </section>
    </main>
    <?php
    // Include the footer
    include 'footer.php';
    ?>
</body>
</html>