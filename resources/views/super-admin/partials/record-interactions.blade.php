<script>
(() => {
    document.querySelectorAll('[data-record-url]').forEach((row) => {
        row.addEventListener('click', (event) => {
            if (event.target.closest('a, button, form, input, select, textarea, label') || window.getSelection()?.toString()) return;
            if (event.ctrlKey || event.metaKey) window.open(row.dataset.recordUrl, '_blank', 'noopener');
            else window.location.assign(row.dataset.recordUrl);
        });
    });
    document.querySelectorAll('[data-delete-user]').forEach((form) => {
        form.addEventListener('submit', (event) => {
            const name = form.dataset.userName;
            const confirmation = window.prompt(`Typ "${name}" om deze gebruiker definitief te verwijderen. Ook de toewijzingen en inzendingen van deze gebruiker worden verwijderd.`);
            if (confirmation !== name) {
                event.preventDefault();
                if (confirmation !== null) window.alert('De naam komt niet exact overeen. De gebruiker is niet verwijderd.');
                return;
            }
            form.querySelector('[name="confirmation_name"]').value = confirmation;
        });
    });
})();
</script>
