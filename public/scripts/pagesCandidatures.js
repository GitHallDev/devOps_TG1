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
var l = document.querySelector("#table-candidature").rows.length

var btn_next = document.querySelector(".btn_next");
var btn_prev = document.querySelector(".btn_prev");
btn_next.addEventListener("click", function() {
    nextPage();
});
btn_prev.addEventListener("click", function() {
    prevPage();
});

function prevPage()
{

    if (current_page > 1) {
        current_page--;
        changePage(current_page);
    }
}

function nextPage()
{
    if (current_page < numPages()) {
        current_page++;
        changePage(current_page);
    }
}
    
function changePage(page)
{
    var btn_next = document.querySelector(".btn_next");
    var btn_prev = document.querySelector(".btn_prev");
    var listing_table = document.querySelector("#table-candidature");
    var page_span = document.querySelector(".page");
 
    // Validate page
    if (page < 1) page = 1;
    if (page > numPages()) page = numPages();

    [...listing_table.getElementsByTagName('tr')].forEach((tr)=>{
        tr.style.display='none'; // reset all to not display
    });
    listing_table.rows[0].style.display = ""; // display the title row

    for (var i = (page-1) * records_per_page + 1; i < (page * records_per_page) + 1; i++) {
        if (listing_table.rows[i]) {
            listing_table.rows[i].style.display = ""
        } else {
            continue;
        }
    }
    
    page_span.innerHTML = "Page: "+ page + "/" + numPages();
    // Disable/enable buttons
    if (page == 1) {
        btn_prev.disable = true;
    } else {
        btn_prev.disable = false;
    }

    if (page == numPages()) {
        btn_next.disable =true;
    } else {
        btn_next.disable = false;
    }
}

function numPages()
{
    return Math.ceil((l - 1) / records_per_page);
}

window.onload = function() {
    changePage(current_page);
};

