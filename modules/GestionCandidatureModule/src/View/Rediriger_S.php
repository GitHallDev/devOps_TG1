<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Changement de Sujet de Stage</title>
  <link rel="stylesheet" href="./styles/GestionCandidature/style_2.css">
</head>
<body>

  <div class="container">
    <h2>Changement de Sujet de Stage</h2>

    <?php
    // Affichage de messages optionnels
    if (isset($_GET['success'])) {
        echo "<p style='color:green;'>Changement enregistré avec succès !</p>";
    } elseif (isset($_GET['error'])) {
        echo "<p style='color:red;'>Erreur lors de l'enregistrement du changement.</p>";
    }

    // Débogage
    echo "<!-- ID reçu : " . (isset($_GET['id_stage']) ? $_GET['id_stage'] : 'non défini') . " -->";

    // Récupération des informations du stage
    if (isset($_GET['id_stage'])) {
        $stageController = new \Modules\GestionCandidatureModule\Controller\StageManagerController();
        $stage = $stageController->get_stage_effectif_by_id((int)$_GET['id_stage']);
        
        // Débogage
        echo "<!-- Stage récupéré : ";
        var_export($stage);
        echo " -->";
    }
    ?>

    <form action="/traitement-redirection-stage" method="post">
      <div class="form-group">
        <label>Stagiaire :</label>
        <?php if (isset($stage) && $stage): ?>
            <div class="stagiaire-info">
                <strong><?= htmlspecialchars($stage['user_prenom'] . ' ' . $stage['user_nom']) ?></strong>
                <input type="hidden" name="id_stage" value="<?= $stage['ID_stage'] ?>">
            </div>
        <?php else: ?>
            <p class="error">Stage non trouvé (ID: <?= isset($_GET['id_stage']) ? htmlspecialchars($_GET['id_stage']) : 'non défini' ?>)</p>
        <?php endif; ?>
      </div>

      <div class="form-group">
        <label for="ancien_sujet">Ancien sujet :</label>
        <textarea id="ancien_sujet" name="ancien_sujet" rows="3" placeholder="Décrire brièvement l'ancien sujet..." required><?= isset($stage) ? htmlspecialchars($stage['Sujet_effectif']) : '' ?></textarea>
      </div>

      <div class="form-group">
        <label for="raison">Raison du changement :</label>
        <textarea id="raison" name="raison" rows="3" placeholder="Expliquer pourquoi le changement est demandé..." required></textarea>
      </div>

      <div class="form-group">
        <label for="nouveau_sujet">Nouveau sujet :</label>
        <textarea id="nouveau_sujet" name="nouveau_sujet" rows="3" placeholder="Décrire le nouveau sujet proposé..." required></textarea>
      </div>

      <div class="form-group">
        <label for="nouvel_encadrant">Nouvel encadrant :</label>
        <select id="nouvel_encadrant" name="nouvel_encadrant" required>
          <option value="">-- Choisir --</option>
          <option value="coulibaly">M. Coulibaly</option>
          <option value="fole">Mme Folé</option>
          <option value="traore">M. Traoré</option>
          <option value="autre">Autre...</option>
        </select>
      </div>

      <button type="submit">Valider le changement</button>
    </form>
  </div>

  <style>
    .stagiaire-info {
      padding: 10px;
      background-color: #f5f5f5;
      border-radius: 4px;
      margin: 5px 0;
    }
    .form-group {
      margin-bottom: 20px;
    }
    .error {
      color: red;
      font-style: italic;
    }
  </style>

</body>
</html>
