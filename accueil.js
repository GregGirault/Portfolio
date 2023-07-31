const title = document.querySelector(".title");
const cta = document.querySelector(".cta");

window.addEventListener("DOMContentLoaded", () => {
  setTimeout(() => {
    title.style.transform = "translateY(0)";
    title.style.opacity = "1";
  }, 500);

  setTimeout(() => {
    cta.style.transform = "translateY(0)";
    cta.style.opacity = "1";
  }, 1000);
});


window.addEventListener("scroll", () => {
  let timelineItems = document.querySelectorAll(".timeline-item");

  timelineItems.forEach((item) => {
    let itemPos = item.getBoundingClientRect().top;
    let screenPos = window.innerHeight / 1.3;

    if (itemPos < screenPos) {
      item.style.transform = "translateX(0)";
      item.style.opacity = "1";
    } else {
      item.style.transform = "translateX(-100px)";
      item.style.opacity = "0";
    }
  });
});




