// Encapsulate in an IIFE to avoid polluting the global namespace
(function () {
    "use strict";
  
    // Wait until the DOM is fully loaded
    document.addEventListener("DOMContentLoaded", function () {
      // Bind the custom delete confirmation to all delete links
      // We’re targeting the ones with class "delete-link"
      const deleteLinks = document.querySelectorAll("a.delete-link");
      deleteLinks.forEach(function (link) {
        link.addEventListener("click", function (e) {
          // Show a custom confirmation box
          const confirmDeletion = confirm(
            "Are you sure you want to delete this booking?"
          );
          if (!confirmDeletion) {
            e.preventDefault();
          }
        });
      });
  
      // Future enhancements: you could add client-side form validation,
      // dynamic table filters, or AJAX-based interactions here.
    });
  })();
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
    