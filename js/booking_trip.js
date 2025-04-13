// script.js

// Example: Simple validation to ensure phone number is entered in a valid format
document.addEventListener('DOMContentLoaded', function() {
    const bookingForm = document.querySelector('form');
    
    bookingForm.addEventListener('submit', function(event) {
        const phoneInput = document.getElementById('phone');
        const phoneValue = phoneInput.value.trim();
        
        // Basic phone number validation (check if the phone number contains only numbers and is not empty)
        const phoneRegex = /^[0-9]{10,15}$/;
        if (!phoneRegex.test(phoneValue)) {
            alert('Please enter a valid phone number with 10 to 15 digits.');
            event.preventDefault();
        }
    });
});
