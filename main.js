// Sélectionner tous les liens avec des ancres commençant par '#'
document.querySelectorAll('a[href^="#"]').forEach((anchor) => {
  anchor.addEventListener("click", function (e) {
    e.preventDefault();

    // Cibler l'ancre (l'élément avec l'ID correspondant) et faire défiler jusqu'à lui
    document.querySelector(this.getAttribute("href")).scrollIntoView({
      behavior: "smooth",
    });
  });
});

// barre de progression
window.onscroll = function () {
  myFunction();
};

function myFunction() {
  // Obtenir la hauteur totale du document
  var body = document.body,
    html = document.documentElement;

  var documentHeight = Math.max(
    body.scrollHeight,
    body.offsetHeight,
    html.clientHeight,
    html.scrollHeight,
    html.offsetHeight
  );

  // Calculer le pourcentage de défilement
  var scrollPercent =
    (window.pageYOffset / (documentHeight - window.innerHeight)) * 100;

  // Mettre à jour la largeur de la barre de progression
  document.getElementById("progressBar").style.width = scrollPercent + "%";
}

// rond sur le pointer de la souris
const mousemove = document.querySelector(".mousemove");

function updateCursorPosition(e, scale = 1) {
  let offsetWidth = (scale * mousemove.offsetWidth) / 2;
  let offsetHeight = (scale * mousemove.offsetHeight) / 2;

  mousemove.style.left = e.clientX - offsetWidth + "px";
  mousemove.style.top = e.clientY - offsetHeight + "px";

  // Vérifier si l'élément survolé est un lien, une image cliquable, un bouton, un input de n'importe quel type, ou un textarea
  const targetElement = e.target;
  const isLink = targetElement.tagName.toLowerCase() === "a";
  const isImageClickable =
    targetElement.tagName.toLowerCase() === "img" &&
    targetElement.parentElement.tagName.toLowerCase() === "a";
  const isButton = targetElement.tagName.toLowerCase() === "button";
  const isInput = targetElement.tagName.toLowerCase() === "input";
  const isTextarea = targetElement.tagName.toLowerCase() === "textarea";

  // Masquer le curseur si c'est un lien, une image cliquable, un bouton, un input ou un textarea, sinon l'afficher
  if (isLink || isImageClickable || isButton || isInput || isTextarea) {
    mousemove.style.display = "none";
  } else {
    mousemove.style.display = "block";
  }
}

window.addEventListener("mousemove", (e) => {
  updateCursorPosition(e);
});

window.addEventListener("mousedown", (e) => {
  mousemove.style.transform = "scale(1.5)";
  updateCursorPosition(e, 1.5);
});

window.addEventListener("mouseup", (e) => {
  mousemove.style.transform = "scale(1)";
  updateCursorPosition(e);
});


// animation niveau de compétences (skills)

function isElementInViewport(el) {
  const rect = el.getBoundingClientRect();
  return (
    rect.top >= 0 &&
    rect.left >= 0 &&
    rect.bottom <=
      (window.innerHeight || document.documentElement.clientHeight) &&
    rect.right <= (window.innerWidth || document.documentElement.clientWidth)
  );
}


let hasStartedAnimation = false;

window.addEventListener("scroll", () => {
  if (
    !hasStartedAnimation &&
    isElementInViewport(document.querySelector(".skill-bar"))
  ) {
    hasStartedAnimation = true;

    const skillLevels = document.querySelectorAll(".skill-bar > div");
    skillLevels.forEach((skillLevel, index) => {
      let width = 0;
      skillLevel.style.width = "0%";
      const targetWidth = parseInt(skillLevel.getAttribute("data-level"));

      function frame() {
        if (width < targetWidth) {
          width += 80 / targetWidth;
          skillLevel.style.width = width + "%";
          requestAnimationFrame(frame);
        }
      }

      // Pour décaler le début de chaque animation
      setTimeout(frame, 550 * index);
    });
  }
});


// gestion section travaux

document.querySelectorAll(".projet").forEach((projet) => {
  projet.addEventListener("click", () => {
    projet.querySelector(".details").style.opacity = 1;
  });
});



// gestion du retournement des cartes
document.addEventListener("DOMContentLoaded", function () {
  document.querySelectorAll(".formation-item").forEach((item) => {
    item.addEventListener("click", (event) => {
      event.currentTarget.classList.toggle("flipped");
    });
  });
});


// gestion pour Expériences professionnelles
// Sélection de tous les titres de job
const jobTitles = document.querySelectorAll('.job-card h3');

// Pour chaque titre de job
jobTitles.forEach((title) => {
  // Ajout d'un écouteur d'événements pour le clic
  title.addEventListener('click', (event) => {
    // Sélection de la carte de job parente
    const jobCard = event.target.closest('.job-card');
    
    // Toggle de la classe "expanded"
    jobCard.classList.toggle('expanded');
  });
});



// Script pour gérer l'ouverture et la fermeture du menu burger
document.querySelector(".burger-icon").addEventListener("click", function () {
  document.querySelector(".burger-nav").classList.toggle("show");
  console.log('test');
});



// toast formulaire
document.querySelector("form").addEventListener("submit", function (e) {
  e.preventDefault();

  let form = this;
  let formData = new FormData(form);

  fetch("PHPMailer.php", {
    method: "POST",
    body: formData,
  })
    .then((response) => {
      console.log(`Response status: ${response.status}`); // Ajout d'un journal de débogage pour le statut de la réponse
      return response.text();
    })
    .then((data) => {
      console.log(`Server response: "${data}"`); // Ajout d'un journal de débogage pour les données
      if (data.includes("success")) {
        Toastify({
          text: "Message envoyé avec succès",
          duration: 1500,
          close: true,
          gravity: "top", // `top` or `bottom`
          position: "right", // `left`, `center` or `right`
          style: {
            background: "linear-gradient(to right, #00b09b, #96c93d)",
          },
          stopOnFocus: true, // Prevents dismissing of toast on hover
        }).showToast();

        // Delay form reset
        setTimeout(function () {
          form.reset(); // Réinitialiser le formulaire
        }, 1500);
      } else {
        Toastify({
          text: "L'envoi du message a échoué",
          duration: 1500,
          close: true,
          gravity: "top", // `top` or `bottom`
          position: "right", // `left`, `center` or `right`
          backgroundColor: "linear-gradient(to right, #FF5733, #FF0000)",
          stopOnFocus: true, // Prevents dismissing of toast on hover
        }).showToast();
      }
    })
    .catch((error) => {
      console.error("Error:", error);
      Toastify({
        text: "Une erreur est survenue",
        duration: 3000,
        close: true,
        gravity: "top", // `top` or `bottom`
        position: "right", // `left`, `center` or `right`
        backgroundColor: "linear-gradient(to right, #FF5733, #FF0000)",
        stopOnFocus: true, // Prevents dismissing of toast on hover
      }).showToast();
    });
});







// Check if an element is in viewport
document.addEventListener("DOMContentLoaded", function () {
  const timelineItems = Array.from(document.querySelectorAll(".timeline-item"));

  function isInViewPort(el) {
    const rect = el.getBoundingClientRect();
    return (
      rect.top >= 0 &&
      rect.left >= 0 &&
      rect.bottom <=
        (window.innerHeight || document.documentElement.clientHeight) &&
      rect.right <= (window.innerWidth || document.documentElement.clientWidth)
    );
  }

  function checkIfInView() {
    timelineItems.forEach((item) => {
      if (isInViewPort(item)) {
        item.classList.add("in-view");
      }
    });
  }

  window.addEventListener("load", checkIfInView);
  window.addEventListener("scroll", checkIfInView);
});




// Récupérer les éléments du DOM
const modalTriggers = document.querySelectorAll(".modal-trigger");
const modals = document.querySelectorAll(".modal");
const modalCloseButtons = document.querySelectorAll(".modal-close");

function openModal(event) {
  event.preventDefault();
  const targetModal = event.currentTarget.getAttribute("data-modal");
  const modal = document.querySelector(targetModal);
  modal.style.visibility = "visible";
  modal.style.opacity = "1";
  modal.querySelector(".modal-content").classList.add("in");
  document.body.style.overflow = "hidden"; // Empêche le défilement de l'arrière-plan
}

function closeModal(event) {
  event.preventDefault();
  const modal = event.currentTarget.closest(".modal");
  modal.querySelector(".modal-content").classList.add("out");
  modal
    .querySelector(".modal-content")
    .addEventListener("transitionend", function handler() {
      modal.style.visibility = "hidden";
      modal.style.opacity = "0";
      modal.querySelector(".modal-content").classList.remove("in");
      modal.querySelector(".modal-content").classList.remove("out");
      document.body.style.overflow = ""; // Réactive le défilement de l'arrière-plan
      modal
        .querySelector(".modal-content")
        .removeEventListener("transitionend", handler);
    });
}

modals.forEach((modal) => {
  modal.addEventListener("click", (event) => {
    if (event.target === modal) {
      closeModal(event);
    }
  });
});

document.addEventListener("keydown", (event) => {
  if (event.key === "Escape") {
    modals.forEach((modal) => {
      closeModal({ preventDefault: () => {}, currentTarget: modal });
    });
  }
});

modalTriggers.forEach((trigger) => {
  trigger.addEventListener("click", openModal);
});

modalCloseButtons.forEach((button) => {
  button.addEventListener("click", closeModal);
});

