import Sortable from 'sortablejs';

export function initListOrder() {
    const root = document.querySelector('[data-list-order-url]');
    const modal = document.getElementById('list-order-modal');
    const items = document.getElementById('list-order-items');
    const openButton = document.getElementById('open-list-order');
    const closeButtons = modal?.querySelectorAll('[data-close-list-order]') || [];
    const saveButton = document.getElementById('save-list-order');

    if (!root || !modal || !items || !openButton || !saveButton) return;
    if (root.dataset.listOrderInitialized === 'true') return;
    root.dataset.listOrderInitialized = 'true';

    const setOpen = (open) => {
        modal.classList.toggle('hidden', !open);
        document.body.classList.toggle('overflow-hidden', open);
    };

    openButton.addEventListener('click', () => setOpen(true));
    closeButtons.forEach(button => button.addEventListener('click', () => setOpen(false)));
    document.addEventListener('keydown', event => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) setOpen(false);
    });

    new Sortable(items, {
        animation: 180,
        handle: '[data-list-drag-handle]',
        ghostClass: 'opacity-40',
        chosenClass: 'ring-2',
        forceFallback: true,
    });

    saveButton.addEventListener('click', async () => {
        const originalText = saveButton.textContent;
        saveButton.disabled = true;
        saveButton.textContent = 'Opslaan...';

        try {
            const response = await fetch(root.dataset.listOrderUrl, {
                method: 'POST',
                credentials: 'same-origin',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    'X-Requested-With': 'XMLHttpRequest',
                },
                body: JSON.stringify({
                    list_ids: Array.from(items.children).map(item => Number(item.dataset.listId)),
                }),
            });

            const data = await response.json();
            if (!response.ok) throw new Error(data.message || 'Opslaan is mislukt.');

            setOpen(false);
            window.location.reload();
        } catch (error) {
            alert(error.message || 'De volgorde kon niet worden opgeslagen.');
        } finally {
            saveButton.disabled = false;
            saveButton.textContent = originalText;
        }
    });
}
