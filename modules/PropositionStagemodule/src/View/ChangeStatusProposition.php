<?php
namespace Modules\GestionPropositionStage\View;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Modules\PropositionStagemodule\Controller\PropositionStageController;

$controller = new PropositionStageController();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: /proposition/board');
    exit;
}

$proposition = $controller::getPropositionById($id);
$statuts = ['accepter', 'rejeter'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nouveauStatut = $_POST['statuts'] ?? '';
    if (!empty($nouveauStatut)) {
        PropositionManagerController::updateStatutProposition();
        header('Location: /proposition/board');
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Changer le statut de la proposition</title>
  <link rel="stylesheet" href="/styles/GestionPropositionStage/statut.css">
</head>
<body>

  <div class="form-wrapper">
    <h2>Changer le statut de la proposition</h2>

    <?php if ($proposition): ?>
      <form action="/proposition/statuts" method="POST">
        <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">
        <label for="statuts">Statut actuel : <?= htmlspecialchars($proposition['statuts']) ?></label><br><br>

        <label for="statuts">Nouveau statut :</label><br>
        <select name="statuts" id="statuts" required>
          <?php foreach ($statuts as $statut): ?>
            <option value="<?= $statut ?>" <?= $statut === $proposition['statuts'] ? 'selected' : '' ?>>
              <?= $statut ?>
            </option>
          <?php endforeach; ?>
        </select><br><br>

        <button type="submit">Mettre à jour le statut</button>
      </form>
    <?php else: ?>
      <p>Proposition non trouvée.</p>
    <?php endif; ?>

    <div class="back-link">
      <a href="/PropositionBoard">Retour à la liste des propositions</a>
    </div>
  </div>


</body>
</html>
