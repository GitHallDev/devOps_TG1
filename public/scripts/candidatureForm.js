const dropzoneContainer = document.querySelectorAll(".dropzone");

dropzoneContainer.forEach((element) => {
  element.addEventListener("drop", (e) => {
    e.preventDefault();
    e.stopPropagation();

    element.classList.remove("dragging");
  });
});

dropzoneContainer.forEach((element) => {
  element.addEventListener("dragover", (e) => {
    e.preventDefault();
    e.stopPropagation();
    element.classList.add("dragging");
  });
});

dropzoneContainer.forEach((element) => {
  element.addEventListener("dragleave", (e) => {
    e.preventDefault();
    e.stopPropagation();
    element.classList.remove("dragging");
  });
});

dropzoneContainer.forEach((element) => {
  element.addEventListener("change", (e) => {
    alert("File uploaded");
    const file = e.dataTransfer.items[i].getAsFile();
    // alert(file);
    if (file) {
      element.firstChild.setAttribute("name", "cloud-done");
    }
    if (file.type != "application/pdf") {
      alert("Please upload a PDF file");
      return;
    }
  });
});

const fileInputCV = document.querySelector("#cv_upload");
const fileInputLM = document.querySelector("#cover_letter_upload");
var fileCV;
var fileLM;

fileInputCV.style.display = "none";
fileInputLM.style.display = "none";

fileInputCV.addEventListener("change", (e) => {
  const file = e.target.files[0];
  if (file) {
    const fileName = file.name;
    const fileSize = file.size;
    const fileType = file.type;
    dropzoneContainer[0].children[0].setAttribute("name", "cloud-done");

    if (fileType != "application/pdf") {
      alert("Please upload a PDF file");
      return;
    }
    // if (fileSize > 1048576) {
    //   alert("File size exceeds 1MB");
    //   return;
    // }
    fileCV = file;
  } else {
    dropzoneContainer[0].children[0].setAttribute("name", "cloud-upload");
  }
});

fileInputLM.addEventListener("change", (e) => {
  const file = e.target.files[0];
  if (file) {
    const fileName = file.name;
    const fileSize = file.size;
    const fileType = file.type;
    dropzoneContainer[1].children[0].setAttribute("name", "cloud-done");

    if (fileType != "application/pdf") {
      alert("Please upload a PDF file");
      return;
    }
    // if (fileSize > 1048576) {
    //   alert("File size exceeds 1MB");
    //   return;
    // }
    fileLM = file;
  } else {
    dropzoneContainer[1].children[0].setAttribute("name", "cloud-upload");
  }
});

const submitButton = document.querySelector("#submit-btn");
submitButton.addEventListener("click", (e) => {

  if (!fileCV || !fileLM) {
    e.preventDefault();
    alert("Veuillez remplir tous les champs obligatoires");
    return;
  }  
})
