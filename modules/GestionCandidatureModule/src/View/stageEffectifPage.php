<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Gestion des Stages Effectifs</title>
  <link rel="stylesheet" href="./styles/GestionCandidature/style.css">
  <link rel="stylesheet" href="./styles/GestionCandidature/notifications.css">
</head>
<body>
    <?php include 'header.php'?>
  <!-- <div class="sidebar">
   
  <img src="./ressources/logo.png" alt="IA4D" width="100">
    <ul>
      <li><strong>STAGES</strong></li>
      <li>Stages effectifs</li>
      <li>Utilisateurs</li>
      <li>Paramètres</li>
    </ul>
  </div> -->
  <?php  include 'sidebar.php'?>
    <main>
  <div class="main">
    <div class="header">
      <h1>Gestion des Stages Effectifs</h1>
      <div class="container">
        <h2>Gestion des Stages Effectifs</h2>

        <div class="stats">
          <?php
            // Récupération des statistiques depuis la base de données
            $stageService = new \Modules\GestionCandidatureModule\Service\StageManagerService(
                new \Modules\GestionCandidatureModule\Repository\StageManagerRepository()
            );
            $stages = $stageService->get_all_stages_effectifs();
            
            // Calcul des statistiques
            $total = count($stages);
            $termines = 0;
            $encours = 0;
            
            foreach ($stages as $stage) {
                if ($stage['statuts'] === 'Fini') {
                    $termines++;
                } elseif ($stage['statuts'] === 'en_cours') {
                    $encours++;
                }
            }
          ?>
          <div class="stat">Total : <?= $total ?></div>
          <div class="stat">Terminés : <?= $termines ?></div>
          <div class="stat">En cours : <?= $encours ?></div>
        </div>
      </div>
    </div>

    <?php
      /* Liste de stages simulée
      $stages = [
        ['id' => '#001', 'stagiaire' => 'Ousmane Kone', 'encadrant' => 'M. Coulibaly', 'duree' => '3 mois', 'statut' => 'Terminé'],
        ['id' => '#002', 'stagiaire' => 'Halleg B Doumbia', 'encadrant' => 'Mme Folé', 'duree' => '2 mois', 'statut' => 'En cours'],
        ['id' => '#003', 'stagiaire' => 'Karim Sidibé', 'encadrant' => 'M. Traoré', 'duree' => '4 mois', 'statut' => 'Terminé']
      ]; */
      
      // Récupération des données depuis la base de données
      $stageService = new \Modules\GestionCandidatureModule\Service\StageManagerService(
          new \Modules\GestionCandidatureModule\Repository\StageManagerRepository()
      );
      $stages = $stageService->get_all_stages_effectifs();
    ?>

    <table>
      <thead>
        <tr>
          <th>Stage ID</th>
          <th>Stagiaire</th>
          <th>Encadrant</th>
          <th>Debut</th>
          <th>Fin</th>
          <th>Statut</th>
          <th>Rapport</th>
          <th>Options</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($stages as $stage): ?>
          <tr data-fin-stage="<?= $stage['Fin_stage'] ?>">
            <td><?= $stage['ID_stage'] ?? 'N/A' ?></td>
            <td><?= $stage['user_prenom'] . ' ' . $stage['user_nom'] ?></td>
            <td><?= $stage['Encadreur'] ?? 'Non assigné' ?></td>
            <td><?=$stage['Debut_stage']?></td>
            <td><?=$stage['Fin_stage']?></td>
            <td>
              <?php
                $badge_class = strtolower(str_replace(' ', '_', $stage['statuts']));
              ?>
              <span class="badge <?= $badge_class ?>"><?= $stage['statuts'] ?></span>
            </td>
            <td><a href="#">Voir</a></td>
            <td>
              <div class="dropdown">
                <button class="option-btn">Option</button>
                <div popover class="dropdown-content">
                  <a href="/planifierSoutenance">Planifier une soutenance</a><br>
                  <?php if ($stage['statuts'] === 'Fini'): ?>
                    <a href="/redirectionStage?id_stage=<?= $stage['ID_stage'] ?>">Rediriger stagiaire</a><br>
                  <?php endif; ?>
                  <a href="#" class="delete-option" style="display: none;">Supprimer</a>
                </div>
              </div>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>
</main>
  <footer>
    <script src="./scripts/GestionCandidature/stageEffectif.js"></script>
    <script src="./scripts/GestionCandidature/notifications.js"></script>
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
  </footer>
</body>
</html>
