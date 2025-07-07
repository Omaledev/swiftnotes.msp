document.addEventListener("DOMContentLoaded", function() {
    function togglePassword(inputId) {
        const input = document.getElementById(inputId);
        if (input.type === "password") {
            input.type = "text";
        } else {
            input.type = "password";
        }
    }

    // Attach event listeners to the eye icons
    document.querySelectorAll('.toggle-password').forEach(function(element) {
        element.addEventListener('click', function() {
            const inputId = this.getAttribute('data-input-id');
            togglePassword(inputId);
        });
    });
});
