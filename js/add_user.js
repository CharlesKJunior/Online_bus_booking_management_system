document.addEventListener('DOMContentLoaded', function () {
    // Add any custom JS code for form validation or event handling
    const form = document.getElementById('addUserForm');
    form.addEventListener('submit', function (event) {
        const password = form.querySelector('input[name="password"]').value;
        const email = form.querySelector('input[name="email"]').value;

        if (!validateEmail(email)) {
            event.preventDefault();
            alert('Please enter a valid email.');
        } else if (password.length < 6) {
            event.preventDefault();
            alert('Password must be at least 6 characters.');
        }
    });
});

function validateEmail(email) {
    const regex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,6}$/;
    return regex.test(email);
}
