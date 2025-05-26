<?php
namespace Modules\PropositionStagemodule\View;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Modules\PropositionStagemodule\Controller\PropositionStageController;

$controller = new PropositionStageController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /PropositionBoard');
    exit;
}

$proposition = $controller::getPropositionById($id);

if (!$proposition) {
    header('Location: /PropositionBoard');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        PropositionStageController::deleteProposition();
        header('Location: /PropositionBoard');
        exit;
    } catch (\Throwable $e) {
        $error = $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer une proposition</title>
    <link rel="stylesheet" href="/styles/GestionPropositionStage/delete.css">
</head>
<body>
    <div class="container">
        <h2>Supprimer la proposition #<?= htmlspecialchars($id) ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <div class="warning-message">
            <strong>Attention !</strong> Vous êtes sur le point de supprimer la proposition suivante :
            <ul>
                <li>Sujet : <?= htmlspecialchars($proposition['sujet']) ?></li>
                <li>Durée : <?= htmlspecialchars($proposition['Duree']) ?> mois</li>
                <li>Rémunération : <?= htmlspecialchars($proposition['remuneration']) ?> FCFA/mois</li>
                <li>Statut : <?= htmlspecialchars($proposition['statuts']) ?></li>
                <li>Structure : <?= htmlspecialchars($proposition['create_by']) ?></li>
            </ul>
            Cette action est irréversible.
        </div>

        <form action="/proposition/delete" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
            <div class="delete-buttons">
                <button type="submit" class="delete-button">Confirmer la suppression</button>
                <button type="button" onclick="window.location.href='/PropositionBoard'" class="cancel-button">Annuler</button>
            </div>
        </form>
    </div>
</body>
</html>
