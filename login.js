// Example: Validate form inputs before submission
document.getElementById('login-form').addEventListener('submit', function (e) {
    let email = document.getElementById('email').value.trim();
    let password = document.getElementById('password').value.trim();
    let errorMessage = '';

    // Simple validation
    if (!email || !password) {
        errorMessage = 'Both fields are required.';
    }

    // Prevent form submission if there is an error
    if (errorMessage) {
        e.preventDefault();
        alert(errorMessage);
    }
});

document.addEventListener("DOMContentLoaded", function () {
    const togglePassword = document.getElementById("togglePassword");
    const passwordField = document.getElementById("login-password");

    togglePassword.addEventListener("click", function () {
        // Toggle the password field type
        if (passwordField.type === "password") {
            passwordField.type = "text";
            // Change icon to hide state (for example, "🙈")
            togglePassword.textContent = "🙈";
        } else {
            passwordField.type = "password";
            // Revert icon back to show state
            togglePassword.textContent = "👁";
        }
    });
});
