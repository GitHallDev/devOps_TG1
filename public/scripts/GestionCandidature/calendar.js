document.addEventListener("DOMContentLoaded", function () {
  const calendarEl = document.getElementById("calendar");
  const modal = document.getElementById("soutenanceModal");
  const closeBtn = document.querySelector(".close");
  const btnValider = document.getElementById("btnValider");
  const btnRefuser = document.getElementById("btnRefuser");
  btnValider.disabled = true;
  btnValider.style.opacity = 0.5;
  btnRefuser.disabled = true;
  btnRefuser.style.opacity = 0.5;
  let currentSoutenanceId = null;

  // Tableau de couleurs pour les événements
  const colors = [
    "#FF6B6B", // Rouge corail
    "#4ECDC4", // Turquoise
    "#45B7D1", // Bleu ciel
    "#96CEB4", // Vert menthe
    "#FFEEAD", // Jaune pâle
    "#D4A5A5", // Rose poudré
    "#9B59B6", // Violet
    "#3498DB", // Bleu
    "#E67E22", // Orange
    "#2ECC71", // Vert
  ];

  const calendar = new FullCalendar.Calendar(calendarEl, {
    initialView: "dayGridMonth",
    locale: "fr",
    headerToolbar: {
      left: "prev,next today",
      center: "title",
      right: "dayGridMonth,timeGridWeek,timeGridDay",
    },
    events: function (info, successCallback, failureCallback) {
      fetch("/api/soutenances")
        .then((response) => response.json())
        .then((events) => {
          // Ajouter une couleur aléatoire à chaque événement
          const coloredEvents = events.map((event) => ({
            ...event,
            backgroundColor: colors[parseInt(event.id) % colors.length],
            borderColor: "transparent",
            display: "block", // Force l'affichage en bloc
          }));
          successCallback(coloredEvents);
        })
        .catch((error) => {
          console.error("Erreur:", error);
          failureCallback(error);
        });
    },
    eventClick: function (info) {
      currentSoutenanceId = info.event.id;
      showSoutenanceDetails(currentSoutenanceId);
    },
  });

  calendar.render();

  function showSoutenanceDetails(soutenanceId) {
    fetch(`/api/soutenances/${soutenanceId}`)
      .then((response) => response.json())
      .then((data) => {
        const dateActuelle = new Date();
        const dateSoutenance = new Date(data.date);
        const peutModifier =
          data.statut === "en_cours" && dateSoutenance > dateActuelle;

        document.getElementById("modalDate").textContent = new Date(
          data.date
        ).toLocaleDateString("fr-FR");
        document.getElementById("modalEtudiant").textContent = data.etudiant;
        document.getElementById("modalJury").innerHTML = data.jury
          .map((membre) => `<p>${membre}</p>`)
          .join("");

        document.getElementById("modalStatut").textContent = data.statut;

        // Gestion des boutons de modification et suppression
        const actionsDiv = document.querySelector(".actions");
        // Supprimer les anciens boutons de modification/suppression s'ils existent
        const btnModifier = document.getElementById("btnModifier");
        const btnSupprimer = document.getElementById("btnSupprimer");
        if (btnModifier) btnModifier.remove();
        if (btnSupprimer) btnSupprimer.remove();

        if (peutModifier) {
          btnValider.disabled = false;
          btnValider.style.opacity = 1;
          btnRefuser.disabled = false;
          btnRefuser.style.opacity = 1;
          const modifierBtn = document.createElement("button");
          modifierBtn.id = "btnModifier";
          modifierBtn.className = "btn-primary";
          modifierBtn.textContent = "Modifier";
          modifierBtn.onclick = () =>
            (window.location.href = `/planifierSoutenance?id=${soutenanceId}`);

          const supprimerBtn = document.createElement("button");
          supprimerBtn.id = "btnSupprimer";
          supprimerBtn.className = "btn-danger";
          supprimerBtn.textContent = "Supprimer";
          supprimerBtn.onclick = () => deleteSoutenance(soutenanceId);

          actionsDiv.appendChild(modifierBtn);
          actionsDiv.appendChild(supprimerBtn);
        }

        modal.style.display = "block";
      })
      .catch((error) => console.error("Erreur:", error));
  }

  function deleteSoutenance(soutenanceId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cette soutenance ?")) {
      console.log(`/api/soutenances/${soutenanceId}`);
      fetch(`/api/soutenances/${soutenanceId}`, {
        method: "DELETE",
        headers: {
          "Content-Type": "application/json",
        },
      })
        .then((response) => response.json())
        .then((data) => {
          if (data.success) {
            calendar.refetchEvents();
            modal.style.display = "none";
          } else {
            alert("Erreur lors de la suppression de la soutenance");
          }
        })
        .catch((error) => console.error("Erreur:", error));
    }
  }

  function updateSoutenanceStatus(soutenanceId, status) {
    fetch(`/api/soutenances/${soutenanceId}/status`, {
      method: "PUT",
      headers: {
        "Content-Type": "application/json",
      },
      body: JSON.stringify({ status: status }),
    })
      .then((response) => response.json())
      .then((data) => {
        calendar.refetchEvents();
        modal.style.display = "none";
      })
      .catch((error) => console.error("Erreur:", error));
  }

  closeBtn.onclick = function () {
    modal.style.display = "none";
  };

  window.onclick = function (event) {
    if (event.target == modal) {
      modal.style.display = "none";
    }
  };

  btnValider.onclick = function () {
    if (currentSoutenanceId) {
      if (confirm("Vous ête sure de vouloir valider cette soutenance ?"))
        updateSoutenanceStatus(currentSoutenanceId, "valide");
    }
  };

  btnRefuser.onclick = function () {
    if (currentSoutenanceId) {
      if (confirm("Vous ête sure de vouloir invalider cette soutenance ?"))
        updateSoutenanceStatus(currentSoutenanceId, "invalide");
    }
  };
});
