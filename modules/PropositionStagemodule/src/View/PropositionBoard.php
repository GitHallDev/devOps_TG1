<?php
namespace Modules\PropositionStagemodule\View;

require_once __DIR__ . '/../../../../vendor/autoload.php';

use Modules\PropositionStagemodule\Controller\PropositionStageController;

$controller = new PropositionStageController();
$propositions = $controller::getAllPropositions();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Internship Proposal Manager</title>
  <link rel="stylesheet" href="./styles/GestionPropositionStage/proposition.css">

</head>
<body>

  <div class="header">
    <img src="./ressources/logo.png" alt="Logo" width="40">
    <div class="search-bar"><input type="text" placeholder="Search"></div>
    <div class="cell-name">Cell_name</div>
  </div>

  <h2 style="text-align:center;">Internship Proposal Manager</h2>

  <div class="filters">
    <button class="filter-button active" data-filter="all">All</button>
    <button class="filter-button" data-filter="en_cours">On hold</button>
    <button class="filter-button" data-filter="accepter">Accepted</button>
    <button class="filter-button" data-filter="rejeter">Denied</button>
    <a href="/propositionCreate" class="button-create">Create</a>
  </div>

  <div class="table-container">
    <table>
      <thead>
        <tr>
          <th>ID</th>
          <th>Sujet</th>
          <th>Durée</th>
          <th>Rémunération</th>
          <th>Statut</th>
          <th>Affecter</th>
          <th>Created_by</th>
          <th>Open</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($propositions as $prop): ?>
          <tr data-status="<?= htmlspecialchars($prop['statuts']) ?>">
            <td><?= htmlspecialchars($prop['ID_prop']) ?></td>
            <td><?= htmlspecialchars($prop['sujet']) ?></td>
            <td><?= htmlspecialchars($prop['Duree']) ?></td>
            <td><?= htmlspecialchars($prop['remuneration']) ?></td>
            <td><?= htmlspecialchars($prop['statuts']) ?></td>
            <td><?php echo $prop['affecter']?"oui":"non"?></td>
            <td>
              <?php if (!empty($prop['create_by'])): ?>
                <?= htmlspecialchars($prop['create_by']) ?>
              <?php else: ?>
                N/A
              <?php endif; ?>
            </td>
            <td>
              <a href="#" class="popup-trigger" data-id="<?= $prop['ID_prop'] ?>">...</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  </div>

  <div class="pagination">
    <span>1–<?= count($propositions) ?> of <?= count($propositions) ?></span>
    <button>Previous</button>
    <button class="active">1</button>
    <button>2</button>
    <button>3</button>
    <button>4</button>
    <button>Next</button>
  </div>

  <!-- Popup -->
  <div id="popupMenu" style="display: none; position: absolute; background: white; border: 1px solid #ccc; padding: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.2); z-index: 1000;">
    <ul style="list-style: none; margin: 0; padding: 0;">
      <li><a href="./PropositionEditForm" id="editLink">✏️ Éditer</a></li>
      <li><a href="./PropositionChangeStatusForm" id="statusLink">🔁 Changer le statut</a></li>
      <li><a href="./DeletePropositionForm" id="deleteLink">🗑️ Supprimer</a></li>
    </ul>
  </div>

  <script>
    // Gérer le menu contextuel "..."
    const popup = document.getElementById('popupMenu');

    document.querySelectorAll('.popup-trigger').forEach(link => {
      link.addEventListener('click', function(e) {
        e.preventDefault();

        const id = this.dataset.id;
        popup.style.display = "block";
        popup.style.visibility = "hidden";

        const popupWidth = popup.offsetWidth;
        const popupHeight = popup.offsetHeight;
        let x = e.pageX + 10;
        let y = e.pageY + 10;
        const windowWidth = window.innerWidth;
        const windowHeight = window.innerHeight;

        if (x + popupWidth > window.scrollX + windowWidth) {
          x = window.scrollX + windowWidth - popupWidth - 10;
        }
        if (y + popupHeight > window.scrollY + windowHeight) {
          y = window.scrollY + windowHeight - popupHeight - 10;
        }

        popup.style.top = y + "px";
        popup.style.left = x + "px";
        popup.style.visibility = "visible";
        popup.style.display = "block";

        document.getElementById('editLink').href = "./PropositionEditForm?id=" + id;
        document.getElementById('statusLink').href = "/PropositionChangeStatusForm?id=" + id;
        document.getElementById('deleteLink').href = "/DeletePropositionForm?id=" + id;

        document.addEventListener('click', function handler(event) {
          if (!popup.contains(event.target) && !event.target.classList.contains('popup-trigger')) {
            popup.style.display = "none";
            document.removeEventListener('click', handler);
          }
        });
      });
    });

    // Filtres dynamiques
    document.querySelectorAll('.filter-button').forEach(button => {
      button.addEventListener('click', () => {
        const filter = button.dataset.filter;
        document.querySelectorAll('tbody tr').forEach(row => {
          const status = row.dataset.status;
          if (filter === 'all' || status === filter) {
            row.style.display = '';
          } else {
            row.style.display = 'none';
          }
        });

        // Marquer le bouton actif
        document.querySelectorAll('.filter-button').forEach(btn => btn.classList.remove('active'));
        button.classList.add('active');
      });
    });
  </script>

</body>
</html>
