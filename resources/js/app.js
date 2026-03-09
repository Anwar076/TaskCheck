import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

// Task list drag-and-drop (only on lists show page)
document.addEventListener('DOMContentLoaded', () => {
    if (document.querySelector('[data-sortable-reorder-url]')) {
        import('./list-sortable.js').then(({ initListSortable }) => initListSortable());
    }
});
