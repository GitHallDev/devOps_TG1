let btn_options = document.querySelectorAll(".option-btn");
let pop_up_option = document.querySelector("#dropdown-content");

// Fonction pour vérifier si la date de fin est dépassée
function isDateDepasse(dateFin) {
  const dateFinStage = new Date(dateFin);
  const dateActuelle = new Date();
  return dateFinStage < dateActuelle;
}

// Fonction pour mettre à jour la visibilité de l'option Supprimer
function updateDeleteOptionVisibility() {
  const rows = document.querySelectorAll("tr[data-fin-stage]");
  rows.forEach((row) => {
    const dateFin = row.getAttribute("data-fin-stage");
    const deleteOption = row.querySelector(".delete-option");
    if (deleteOption) {
      deleteOption.style.display = isDateDepasse(dateFin) ? "block" : "none";
    }
  });
}

document.addEventListener("DOMContentLoaded", function () {
  // Mettre à jour la visibilité des options de suppression
  updateDeleteOptionVisibility();

  // Gestion des boutons d'options
  const btnOptions = document.querySelectorAll(".option-btn");
  btnOptions.forEach((btn) => {
    btn.addEventListener("click", function () {
      const dropdownContent = this.nextElementSibling;
      const rect = this.getBoundingClientRect();
      const scrollTop =
        window.pageYOffset || document.documentElement.scrollTop;
      const scrollLeft =
        window.pageXOffset || document.documentElement.scrollLeft;

      dropdownContent.style.top = `${rect.bottom + scrollTop - 10}px`;
      dropdownContent.style.left = `${rect.left + scrollLeft - 70}px`;
      dropdownContent.showPopover();
    });
  });

  // Fermer les popups quand on clique ailleurs
  document.addEventListener("click", function (event) {
    if (!event.target.closest(".dropdown")) {
      document.querySelectorAll(".dropdown-content").forEach((popup) => {
        popup.hidePopover();
      });
    }
  });

  const table = document.querySelector("table");
  const tbody = table.querySelector("tbody");
  const rows = Array.from(tbody.querySelectorAll("tr"));
  const itemsPerPage = 5;
  let currentPage = 1;

  // Créer les éléments de pagination
  const paginationContainer = document.createElement("div");
  paginationContainer.className = "pagination";
  table.parentNode.insertBefore(paginationContainer, table.nextSibling);

  // Fonction pour afficher les lignes de la page courante
  function displayPage(page) {
    const start = (page - 1) * itemsPerPage;
    const end = start + itemsPerPage;

    // Cacher toutes les lignes
    rows.forEach((row) => (row.style.display = "none"));

    // Afficher les lignes de la page courante
    rows.slice(start, end).forEach((row) => (row.style.display = ""));

    // Mettre à jour les boutons de pagination
    updatePaginationButtons();
  }

  // Fonction pour mettre à jour les boutons de pagination
  function updatePaginationButtons() {
    const totalPages = Math.ceil(rows.length / itemsPerPage);
    paginationContainer.innerHTML = "";

    // Bouton précédent
    const prevButton = document.createElement("button");
    prevButton.textContent = "Précédent";
    prevButton.disabled = currentPage === 1;
    prevButton.addEventListener("click", () => {
      if (currentPage > 1) {
        currentPage--;
        displayPage(currentPage);
      }
    });
    paginationContainer.appendChild(prevButton);

    // Numéros de page
    for (let i = 1; i <= totalPages; i++) {
      const pageButton = document.createElement("button");
      pageButton.textContent = i;
      pageButton.className = i === currentPage ? "active" : "";
      pageButton.addEventListener("click", () => {
        currentPage = i;
        displayPage(currentPage);
      });
      paginationContainer.appendChild(pageButton);
    }

    // Bouton suivant
    const nextButton = document.createElement("button");
    nextButton.textContent = "Suivant";
    nextButton.disabled = currentPage === totalPages;
    nextButton.addEventListener("click", () => {
      if (currentPage < totalPages) {
        currentPage++;
        displayPage(currentPage);
      }
    });
    paginationContainer.appendChild(nextButton);
  }

  // Ajouter le style CSS pour la pagination et les dropdowns
  const style = document.createElement("style");
  style.textContent = `
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin: 20px 0;
        }
        .pagination button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background-color: #fff;
            cursor: pointer;
            border-radius: 4px;
        }
        .pagination button:hover:not(:disabled) {
            background-color: #f0f0f0;
        }
        .pagination button.active {
            background-color: #007bff;
            color: white;
            border-color: #007bff;
        }
        .pagination button:disabled {
            cursor: not-allowed;
            opacity: 0.5;
        }
        .delete-option {
            color: #dc3545;
        }
        .delete-option:hover {
            color: #c82333;
            text-decoration: underline;
        }
        .dropdown-content {
            background: white;
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .dropdown-content a {
            display: block;
            padding: 4px 8px;
            text-decoration: none;
            color: #333;
        }
        .dropdown-content a:hover {
            background-color: #f5f5f5;
        }
    `;
  document.head.appendChild(style);

  // Afficher la première page
  displayPage(1);
});
