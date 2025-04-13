// You can add additional interactivity for the footer if desired.
(function () {
    "use strict";
  
    document.addEventListener("DOMContentLoaded", function () {
      // Example: Smooth scroll to top functionality (if you add a back-to-top link with id 'back-to-top')
      var backToTop = document.getElementById("back-to-top");
      if (backToTop) {
        backToTop.addEventListener("click", function (e) {
          e.preventDefault();
          window.scrollTo({
            top: 0,
            behavior: "smooth"
          });
        });
      }
    });
  })();
  