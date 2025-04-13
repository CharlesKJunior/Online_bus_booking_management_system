document.addEventListener("DOMContentLoaded", function () {
    console.log("Dapo Travels Loaded!");
  
    // Smooth scrolling for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener("click", function (event) {
        event.preventDefault();
        const target = document.querySelector(this.getAttribute("href"));
        if (target) {
          target.scrollIntoView({ behavior: "smooth" });
        }
      });
    });
  
    // Book Now Button Animation
    document.querySelectorAll(".btn-primary").forEach(button => {
      button.addEventListener("mouseenter", function () {
        this.classList.add("shadow-lg");
      });
      button.addEventListener("mouseleave", function () {
        this.classList.remove("shadow-lg");
      });
    });
  });
  