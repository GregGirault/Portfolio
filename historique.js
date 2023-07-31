window.onload = function () {
  var toast = document.getElementById("toast");
  var toastMessage = document.getElementById("toast-message");
  var message = toast.getAttribute("data-message");
  var error = toast.getAttribute("data-error");
  if (message !== "") {
    toastMessage.textContent = message;
    toast.classList.remove("hidden");
    toast.style.backgroundColor = "#000000"; // Noir correspondant au CSS
    setTimeout(function () {
      toast.classList.add("hidden");
    }, 2000);
  } else if (error !== "") {
    toastMessage.textContent = error;
    toast.classList.remove("hidden");
    toast.style.backgroundColor = "#A43232"; // Rouge correspondant au CSS
    setTimeout(function () {
      toast.classList.add("hidden");
    }, 2000);
  }
};

function confirmModif(event) {
  event.preventDefault();
  var url = event.target.getAttribute("href");
  if (url) {
    showConfirmationToast("Êtes-vous sûr de vouloir modifier ce projet ?", url, false);
    resetConfirmButtonColor(); // Réinitialiser la couleur du bouton "Confirmer"
  }
}

function confirmDelete(event) {
  event.preventDefault();
  var url = event.target.getAttribute("href");
  if (url) {
    showConfirmationToast(
      "Êtes-vous sûr de vouloir supprimer ce projet ?",
      url,
      true
    );
  }
}

function resetConfirmButtonColor() {
  var confirmButton = document.querySelector(".confirm-delete-button");
  if (confirmButton) {
    confirmButton.classList.remove("confirm-delete-button");
  }
}

function showConfirmationToast(message, url, isDelete) {
  var confirmationToast = document.getElementById("confirmation-toast");
  var toastMessage = document.getElementById("confirmation-toast-message");
  var toastConfirm = document.getElementById("confirmation-toast-confirm");
  var toastCancel = document.getElementById("confirmation-toast-cancel");

  function confirmAction() {
    window.location = url;
    toastConfirm.removeEventListener("click", confirmAction);
    toastCancel.removeEventListener("click", cancelAction);
  }

  function cancelAction() {
    confirmationToast.classList.add("hidden");
    toastConfirm.removeEventListener("click", confirmAction);
    toastCancel.removeEventListener("click", cancelAction);
  }

  toastMessage.textContent = message;

  // Vérifier si c'est une suppression pour appliquer la couleur appropriée
  if (isDelete) {
    toastConfirm.classList.add("confirm-delete-button");
  }

  toastConfirm.addEventListener("click", confirmAction);
  toastCancel.addEventListener("click", cancelAction);

  confirmationToast.classList.remove("hidden");
}

