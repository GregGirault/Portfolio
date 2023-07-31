document.addEventListener("DOMContentLoaded", function () {
  var submitButton = document.getElementById("submitButton");
  var form = document.getElementById("myForm");
  var label1 = document.getElementById("label1");
  var label2 = document.getElementById("label2");
  var label3 = document.getElementById("label3");
  var inputText = document.getElementById("inputText");
  var textarea = document.getElementById("textarea");
  var retour = document.getElementById("retour");

  submitButton.addEventListener("mouseover", function () {
    form.style.backgroundColor = "transparent"; // form en transparent
    this.style.backgroundColor = "#666666ed"; // bouton submit en gris foncé
    this.style.color = "#f2f2f2"; // texte du bouton submit en blanc
    label1.style.color = "#f2f2f2"; // texte du label en blanc
    label2.style.color = "#f2f2f2"; // texte du label en blanc
    label3.style.color = "#f2f2f2"; // texte du label en blanc
    retour.style.color = "#f2f2f2"; // texte du bouton retour en gris foncé
    inputText.style.borderColor = "#aaa"; // bordure de l'input en gris clair
    textarea.style.borderColor = "#aaa"; // bordure du textarea en gris clair
    retour.style.border = "1px solid #f2f2f2"; // ajout de la bordure au bouton retour
  });

  submitButton.addEventListener("mouseout", function () {
    form.style.backgroundColor = "#111111ee"; // form en gris foncé transparent
    this.style.backgroundColor = "#f2f2f2"; // bouton submit en gris clair
    this.style.color = "#333"; // texte du bouton submit en gris
    label1.style.color = "#f2f2f2"; // texte du label en blanc
    label2.style.color = "#f2f2f2"; // texte du label en blanc
    label3.style.color = "#f2f2f2"; // texte du label en blanc
    retour.style.color = "#f2f2f2"; // texte du bouton retour en blanc
    inputText.style.borderColor = "#ddd"; // bordure de l'input en gris
    textarea.style.borderColor = "#ddd"; // bordure du textarea en gris
    retour.style.border = "none"; // suppression de la bordure du bouton retour
  });
});

window.onload = function () {
  var toast = document.getElementById("toast");
  var toastMessage = document.getElementById("toast-message");
  var message = toast.getAttribute("data-message");
  if (message !== "") {
    toastMessage.textContent = message;
    toast.classList.remove("hidden");
    setTimeout(function () {
      toast.classList.add("hidden");
    }, 2000);
  }
};
