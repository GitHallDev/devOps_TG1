<?php 
session_start();
    use Modules\PropositionStagemodule\Controller\PropositionStageController;

    $controller= new PropositionStageController();
    $propositions=$controller->getAllPropositionsAccepted();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Propositions</title>
    <link rel="stylesheet" href="./styles/GestionPropositionStage/style.css">
</head>
<body>
<?php
    // Include the header
    include 'header.php';

    // Get filter duration from GET request
    $filter_duration = isset($_GET['duration']) ? (int)$_GET['duration'] : null;

    // Filter proposals if a duration is set
    $filtered_proposals = [];
    foreach ($propositions as $proposal) {
        // Extract the number from the 'duree' string
        preg_match('/(\d+)/', $proposal['Duree'], $matches);
        $proposal_duration = isset($matches[0]) ? (int)$matches[0] : 0;

        if ($filter_duration === null || $proposal_duration === $filter_duration) {
            $filtered_proposals[] = $proposal;
        }
    }

    ?>
    <main>
        <section class="featured">
        <div style="text-align: center; margin-top: 2rem;;">
                <a href="/Accueil" class="btn btn-primary"><- Retour</a>
            </div>
            <h1 class="section-title">Liste des Propositions de Stage</h1>

            <!-- Filter Form -->
            <form action="/proposition_stage" method="get" class="filter-form">
                <label for="duration">Filtrer par durée (mois) :</label>
                <input type="number" id="duration" name="duration" placeholder="Entrez la durée en mois" min="1" max="12" value="<?php echo $filter_duration; ?>">
                <button type="submit" class="btn btn-primary">Filtrer</button>
                <?php if ($filter_duration !== null): ?>
                    <a href="/proposition_stage" class="btn btn-secondary">Réinitialiser</a>
                <?php endif; ?>
            </form>

            <div class="card-container">
                <?php
                // Display filtered proposals
                if (count($filtered_proposals) > 0) {
                    foreach ($filtered_proposals as $proposal) {
                ?>
                <article class="card">
                    <p><strong>Sujet :</strong> <?php echo htmlspecialchars($proposal['sujet']); ?></p>
                    <p><strong>Durée :</strong> <?php echo htmlspecialchars($proposal['Duree']); ?> mois</p>
                    <p><strong>Rémunération :</strong> <?php echo htmlspecialchars($proposal['remuneration']); ?> FCFA/mois</p>

                    <p><strong>Auteur :</strong> <?php echo htmlspecialchars($proposal['create_by']); ?></p>
                                        <?php 
                    if(isset($_SESSION['user'])){
                        if($_SESSION['user']['role']=='individus'){

                    ?>
                    <div><a href="./candidater?id_prop=<?=$proposal['ID_prop']?>">Candidater</a></div>
                                        <?php }
                            }?>
                </article>
                <?php
                    }
                } else {
                    echo "<p>Aucune proposition trouvée pour cette durée.</p>";
                }
                ?>
            </div>
        </section>
    </main>
    <?php
    // Include the footer
    include 'footer.php';
    ?>
</body>
</html>