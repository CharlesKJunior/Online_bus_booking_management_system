document.addEventListener("DOMContentLoaded", function() {
    const editButtons = document.querySelectorAll(".edit-user");
    const deleteButtons = document.querySelectorAll(".delete-user");

    // Edit User Event
    editButtons.forEach(button => {
        button.addEventListener("click", function() {
            const userId = this.dataset.id;
            window.location.href = `edit_user.php?id=${userId}`;
        });
    });

    // Delete User Event
    deleteButtons.forEach(button => {
        button.addEventListener("click", function() {
            const userId = this.dataset.id;
            if (confirm("Are you sure you want to delete this user?")) {
                fetch(`delete_user.php?id=${userId}`, { method: "POST" })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            document.getElementById(`user-row-${userId}`).remove();
                            alert("User deleted successfully!");
                        } else {
                            alert("Failed to delete user.");
                        }
                    });
            }
        });
    });
});
