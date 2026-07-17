/**
 * Task list drag-and-drop with Sortable.js (bundled via Vite)
 */
import Sortable from 'sortablejs';

function showReorderToast(msg) {
    const toast = document.createElement('div');
    toast.className = 'fixed bottom-4 right-4 px-4 py-2 bg-emerald-600 text-white text-sm font-medium rounded-xl shadow-lg z-50';
    toast.textContent = msg;
    document.body.appendChild(toast);
    setTimeout(() => toast.remove(), 2000);
}

export function initListSortable() {
    const configEl = document.querySelector('[data-sortable-reorder-url]');
    if (!configEl) return;

    const reorderUrl = configEl.dataset.sortableReorderUrl;
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    if (!reorderUrl || !csrf) return;

    function sendReorder(container, items) {
        const tasks = items.map((el, i) => ({
            id: parseInt(el.dataset.taskId, 10),
            order_index: i
        }));
        if (tasks.length === 0) return;
        fetch(reorderUrl, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrf,
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ tasks })
        })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const badges = container.querySelectorAll('.task-item .task-order-badge');
                    badges.forEach((b, i) => { if (b) b.textContent = (i + 1).toString(); });
                }
            })
            .catch(() => alert('Volgorde opslaan mislukt.'));
    }

    function createSortable(el) {
        if (!el || el.children.length === 0) return;
        new Sortable(el, {
            animation: 200,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-dragging',
            filter: 'a, button, input',
            preventOnFilter: false,
            swapThreshold: 0.65,
            onEnd() {
                const items = el.querySelectorAll('.task-item');
                sendReorder(el, Array.from(items));
            }
        });
    }

    createSortable(document.getElementById('sortable-tasks'));
    createSortable(document.getElementById('sortable-general-tasks'));
    document.querySelectorAll('.task-day-list').forEach(dayList => {
        if (dayList.children.length > 0) createSortable(dayList);
    });
}
