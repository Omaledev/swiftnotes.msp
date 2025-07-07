document.addEventListener('DOMContentLoaded', function() {
    const emailInput = document.getElementById('email');
    const emailError = document.querySelector('[name="email"] + .text-red-600');

    if (emailInput && emailError) {
        emailInput.addEventListener('input', function() {
            // Clear error when user starts typing
            if (emailError.textContent.includes('already been taken')) {
                emailError.textContent = '';
            }
        });
    }
});
