<?php
namespace Modules\PropositionStagemodule\View;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Modules\PropositionStagemodule\Controller\PropositionStageController;
use Modules\PropositionStagemodule\Modele\Proposition;

$message = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $controller = new PropositionManagerController();
        
        // Créer une nouvelle instance de Proposition avec les données du formulaire
        $proposition = new Proposition(
            $_POST['sujet'],
            $_POST['Duree'],
            $_POST['remuneration'],
            'en_cours', // Statut par défaut
            $_POST['create_by']
        );

        // Enregistrer la proposition
        $service = new \Modules\PropositionStagemodule\Service\PropositionStageService(
            new \Modules\PropositionStagemodule\Repository\PropositionStageRepository()
        );
        
        if ($service->saveProposition($proposition)) {
            header('Location: /PropositionBoard');
            exit;
        } else {
            $message = 'Erreur lors de l\'enregistrement de la proposition';
        }
    } catch (\Exception $e) {
        $message = 'Erreur : ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter une Proposition de Stage</title>
    <link rel="stylesheet" href="./styles/GestionPropositionStage/editer.css">
</head>
<body>

<div class="container">
    <h2>Ajouter une Proposition de Stage</h2>
    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>
    
    <form action="/proposition/create" method="POST">
        <div class="form-grid">
            <div class="form-group">
                <label for="Duree">Durée (Mois)</label>
                <input type="number" id="Duree" name="Duree" min="1" max="12" required>
            </div>

            <div class="form-group">
                <label for="remuneration">Rémunération (FCFA/mois)</label>
                <input type="number" id="remuneration" name="remuneration" min="0" required>
            </div>

            <div class="form-group full-width">
                <label for="sujet">Sujet</label>
                <textarea id="sujet" name="sujet" required></textarea>
            </div>

            <div class="form-group full-width">
                <label for="create_by">Créé par (Nom de la Structure)</label>
                <input type="text" id="create_by" name="createBy" required>
            </div>
        </div>

        <div class="buttons">
            <input type="submit" class="confirm" value="Confirmer">
            <button type="button" onclick="window.location.href='/PropositionBoard'" class="cancel">Annuler</button>
        </div>
    </form>
</div>

</body>
</html>
