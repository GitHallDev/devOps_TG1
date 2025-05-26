<?php
namespace Modules\PropositionStagemodule\View;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Modules\PropositionStagemodule\Controller\PropositionStageController;

$controller = new PropositionStageController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /proposition/board');
    exit;
}

$proposition = $controller::getPropositionById($id);

if (!$proposition) {
    header('Location: /proposition/board');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $updatedProposition = [
            'sujet' => $_POST['sujet'],
            'Duree' => $_POST['Duree'],
            'remuneration' => $_POST['remuneration'],
            'statuts' => $_POST['statuts'],
            'create_by' => $_POST['create_by']
        ];
        
        PropositionStageController::updateProposition();
        header('Location: /proposition/board');
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
    <title>Modifier une proposition</title>
    <link rel="stylesheet" href="/styles/GestionPropositionStage/editer.css"> 
</head>
<body>
    <div class="container">
        <h2>Modifier la proposition #<?= htmlspecialchars($id) ?></h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form action="/proposition/update" method="post">
            <div class="form-grid">
                <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
                <div class="form-group">
                    <label for="Duree">Durée (mois)</label>
                    <input type="number" id="Duree" name="Duree" min="1" max="12" value="<?= htmlspecialchars($proposition['Duree']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="remuneration">Rémunération (FCFA/mois)</label>
                    <input type="number" id="remuneration" min="0" name="remuneration" value="<?= htmlspecialchars($proposition['remuneration']) ?>" required>
                </div>

                <div class="form-group">
                    <label for="statuts">Statut</label>
                    <select id="statuts" name="statuts" required>
                        <option value="en_cours" <?= $proposition['statuts'] === 'en_cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="accepter" <?= $proposition['statuts'] === 'accepter' ? 'selected' : '' ?>>Accepté</option>
                        <option value="rejeter" <?= $proposition['statuts'] === 'rejeter' ? 'selected' : '' ?>>Rejeté</option>
                    </select>
                </div>

                <div class="form-group full-width">
                    <label for="sujet">Sujet</label>
                    <textarea id="sujet" name="sujet" required><?= htmlspecialchars($proposition['sujet']) ?></textarea>
                </div>

                <div class="form-group full-width">
                    <label for="create_by">Structure</label>
                    <input type="text" id="create_by" name="create_by" value="<?= htmlspecialchars($proposition['create_by']) ?>" required>
                </div>
            </div>

            <div class="buttons">
                <input type="submit" class="confirm" value="Enregistrer les modifications">
                <button type="button" onclick="window.location.href='/PropositionBoard'" class="cancel">Annuler</button>
            </div>
        </form>
    </div>
    <footer>
    </footer>
</body>
</html>
