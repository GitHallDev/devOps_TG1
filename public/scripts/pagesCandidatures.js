let icon_eye = document.querySelectorAll(".icon-eye");

icon_eye.forEach((eye) => {
  eye.addEventListener("mouseover", function () {
    this.removeAttribute("src");
    this.setAttribute("src", "./ressources/eye-opened.svg");
  });
});

icon_eye.forEach((eye) => {
  eye.addEventListener("mouseout", function () {
    this.removeAttribute("src");
    this.setAttribute("src", "./ressources/eye-closed.svg");
  });
});

// Gestion de la pagination du tableau

var current_page = 1;
var records_per_page = 5;
var l = document.querySelector("#table-candidature").rows.length;

var btn_next = document.querySelector(".btn_next");
var btn_prev = document.querySelector(".btn_prev");
btn_next.addEventListener("click", function () {
  nextPage();
});
btn_prev.addEventListener("click", function () {
  prevPage();
});

function prevPage() {
  if (current_page > 1) {
    current_page--;
    changePage(current_page);
  }
}

function nextPage() {
  if (current_page < numPages()) {
    current_page++;
    changePage(current_page);
  }
}

function changePage(page) {
  var btn_next = document.querySelector(".btn_next");
  var btn_prev = document.querySelector(".btn_prev");
  var listing_table = document.querySelector("#table-candidature");
  var page_span = document.querySelector(".page");

  // Validate page
  if (page < 1) page = 1;
  if (page > numPages()) page = numPages();

  [...listing_table.getElementsByTagName("tr")].forEach((tr) => {
    tr.style.display = "none"; // reset all to not display
  });
  listing_table.rows[0].style.display = ""; // display the title row

  for (
    var i = (page - 1) * records_per_page + 1;
    i < page * records_per_page + 1;
    i++
  ) {
    if (listing_table.rows[i]) {
      listing_table.rows[i].style.display = "";
    } else {
      continue;
    }
  }

  page_span.innerHTML = "Page: " + page + "/" + numPages();
  // Disable/enable buttons
  if (page == 1) {
    btn_prev.disable = true;
  } else {
    btn_prev.disable = false;
  }

  if (page == numPages()) {
    btn_next.disable = true;
  } else {
    btn_next.disable = false;
  }
}

function numPages() {
  return Math.ceil((l - 1) / records_per_page);
}

window.onload = function () {
  changePage(current_page);
};

// Gestion de l'affichage des infos des propositions de stages

let btn_infos_stages = document.querySelectorAll(".btn-infos-prop");

btn_infos_stages.forEach((e) => {
  e.addEventListener("click", async () => {
    let url = `/propositionByCand?id=${e.dataset.id_prop}`;
    fetch(url)
      .then((response) => {
        if (response.ok) return response.json();
        else
          throw new Error(
            `Erreur lors du chargement des données: ${response.json()}`
          );
      })
      .then((data) => showInfosProp(data))
      .catch((error) => console.error("Error:", error));
  });
});

// fonction daffichages des infos des propositions de stages
let pop_up_infos_prop = document.querySelector("#pop-up-infosProposition");

function showInfosProp(prop) {
  if (HTMLElement.prototype.hasOwnProperty("popover")) {
    pop_up_infos_prop.innerHTML = "";
    pop_up_infos_prop.innerHTML = `
      <h3>Informations sur la proposition de stage</h3>
       <p><span>Durée: </span>${prop.Duree} mois </p>
       <p><span>Remunération: </span>${prop.remuneration} FCFA </p>
       <p><span>Sujet: </span><br>${prop.sujet}</p>
       <p><span>Créer par: </span> ${prop.create_by}</p>
      <button class= "btn-close">Close</button>
    `;

    let btn_close = document.querySelector(".btn-close");
    btn_close.addEventListener("click", function () {
      pop_up_infos_prop.innerHTML = "";
      pop_up_infos_prop.togglePopover();
    });
    pop_up_infos_prop.appendChild(btn_close);
    pop_up_infos_prop.togglePopover();
  } else {
    alert(
      "T'as un navigateur de merde qui ne prend as encore en charge PopOver remet toi en question !"
    );
  }
}

// Gestion de l'affichage des options
let btn_options = document.querySelectorAll(".btn-options");
let pop_up_option = document.querySelector("#pop-up-option");

btn_options.forEach((element) => {
  element.addEventListener("click", function () {
    const btn = this;
    const rect = btn.getBoundingClientRect();
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    const scrollLeft =
      window.pageXOffset || document.documentElement.scrollLeft;

    pop_up_option.style.top = `${rect.bottom + scrollTop}px`;
    pop_up_option.style.left = `${rect.left + scrollLeft - 40}px `;
    pop_up_option.showPopover();
  });
});

document.querySelector("#btn-option-delete").addEventListener("click", () => {
  pop_up_option.hidePopover();
});

document
  .querySelector("#btn-option-change-statuts")
  .addEventListener("click", () => {
    pop_up_option.hidePopover();
  });

// Gestion pop-up delete
let pop_up_delete = document.querySelector("#delete-cand-dialog");
document
  .querySelector("#btn-cancel-dialog-delete")
  .addEventListener("click", () => {
    pop_up_delete.hidePopover();
  });

// Gestion pop-up change statuts
let pop_up_changeStatut = document.querySelector("#changeStatus-cand-dialog");
let pop_up_create_stage_effectif = document.querySelector(
  "#pop-up-create-stage-effectif"
);
document
  .querySelector("#btn-cancel-dialog-change")
  .addEventListener("click", (e) => {
    e.preventDefault();
    pop_up_changeStatut.hidePopover();
    pop_up_create_stage_effectif.showPopover();
  });
