document.addEventListener('DOMContentLoaded', function() {
    const flashMessage = document.querySelector('.flash-message');

    if (flashMessage) {
        // Fade out after 3 seconds
        setTimeout(() => {
            flashMessage.style.transition = 'opacity 0.5s ease';
            flashMessage.style.opacity = '0';

            // Remove after fade completes
            setTimeout(() => flashMessage.remove(), 500);
        }, 5000);
    }
});
