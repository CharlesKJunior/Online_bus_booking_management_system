// Validate the form before submission
document.getElementById('register-form').addEventListener('submit', function (e) {
    let name = document.getElementById('name').value.trim();
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();
    let role = document.getElementById('role').value;
    let errorMessage = '';

    // Simple validation
    if (!name || !email || !password) {
        errorMessage = 'All fields are required.';
    }

    if (errorMessage) {
        e.preventDefault();
        alert(errorMessage);
    }
});
