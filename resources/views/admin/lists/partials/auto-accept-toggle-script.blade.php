<script>
document.addEventListener('DOMContentLoaded', () => {
    const reviewInput = document.getElementById(@json($reviewInputId));
    const wrapper = document.querySelector('[data-auto-accept-wrapper]');
    const autoAcceptInput = wrapper?.querySelector('[data-auto-accept-input]');

    if (!reviewInput || !wrapper || !autoAcceptInput) return;

    const syncAutoAcceptVisibility = () => {
        const requiresReview = reviewInput.checked;
        wrapper.classList.toggle('hidden', requiresReview);

        if (requiresReview) {
            autoAcceptInput.checked = false;
        }
    };

    reviewInput.addEventListener('change', syncAutoAcceptVisibility);
    syncAutoAcceptVisibility();
});
</script>
