/**
 * Pop-up voor nieuwe taak aanmaken (vervangt /tasks/create pagina).
 */
export function initTaskCreateModal() {
    const modal = document.querySelector('[data-task-create-modal]');
    if (!modal) {
        return;
    }

    const form = document.getElementById('task-create-form');
    const titleInput = document.getElementById('task-create-title');
    const proofSelect = document.getElementById('task-create-proof');
    const requiredCheckbox = document.getElementById('task-create-required');
    const signatureCheckbox = document.getElementById('task-create-signature');
    const descriptionInput = document.getElementById('task-create-description');
    const instructionsInput = document.getElementById('task-create-instructions');
    const expandToggle = document.getElementById('task-create-expand-toggle');
    const expanded = document.getElementById('task-create-expanded');
    const expandIcon = document.getElementById('task-create-expand-icon');
    const errorBox = document.getElementById('task-create-error');
    const submitBtn = document.getElementById('task-create-submit');
    const checklistContainer = document.getElementById('task-create-checklist');
    const addChecklistBtn = document.getElementById('task-create-add-checklist');
    const metricType = document.getElementById('task-create-metric-type');
    const metricUnit = document.getElementById('task-create-metric-unit');
    const metricMin = document.getElementById('task-create-metric-min');
    const metricMax = document.getElementById('task-create-metric-max');

    const storeUrl = modal.dataset.storeUrl;
    const showDayPicker = modal.dataset.showDayPicker === '1';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let expandedOpen = false;

    const showError = (message) => {
        if (!errorBox) {
            return;
        }
        errorBox.textContent = message;
        errorBox.classList.remove('hidden');
    };

    const hideError = () => {
        if (!errorBox) {
            return;
        }
        errorBox.classList.add('hidden');
        errorBox.textContent = '';
    };

    const resetForm = () => {
        form?.reset();
        if (requiredCheckbox) {
            requiredCheckbox.checked = true;
        }
        if (checklistContainer) {
            checklistContainer.innerHTML = '';
        }
        expandedOpen = false;
        expanded?.classList.add('hidden');
        expandIcon?.classList.remove('rotate-180');
        hideError();
    };

    const openModal = (options = {}) => {
        resetForm();

        if (options.weekday && showDayPicker) {
            expandedOpen = true;
            expanded?.classList.remove('hidden');
            expandIcon?.classList.add('rotate-180');

            document.querySelectorAll('.task-create-weekday').forEach((input) => {
                input.checked = input.value === options.weekday;
            });
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => titleInput?.focus(), 50);
    };

    const closeModal = () => {
        modal.classList.add('hidden');
        document.body.classList.remove('overflow-hidden');
        resetForm();
    };

    const addChecklistRow = (value = '') => {
        if (!checklistContainer) {
            return;
        }

        const row = document.createElement('div');
        row.className = 'flex gap-2';
        row.innerHTML = `
            <input type="text" value="${value.replace(/"/g, '&quot;')}" maxlength="500"
                   class="task-create-checklist-item flex-1 rounded-lg border border-slate-200 px-2 py-2 text-sm"
                   placeholder="Checklist item">
            <button type="button" class="task-create-remove-checklist shrink-0 rounded-lg px-2 text-slate-400 hover:bg-red-50 hover:text-red-600" aria-label="Verwijderen">×</button>
        `;
        checklistContainer.appendChild(row);
        row.querySelector('.task-create-remove-checklist')?.addEventListener('click', () => row.remove());
    };

    expandToggle?.addEventListener('click', () => {
        expandedOpen = !expandedOpen;
        expanded?.classList.toggle('hidden', !expandedOpen);
        expandIcon?.classList.toggle('rotate-180', expandedOpen);
    });

    addChecklistBtn?.addEventListener('click', () => addChecklistRow());

    modal.querySelectorAll('[data-task-create-close]').forEach((el) => {
        el.addEventListener('click', closeModal);
    });

    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });

    document.querySelectorAll('[data-open-task-create]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            openModal({
                weekday: trigger.dataset.weekday || null,
            });
        });
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        hideError();

        const title = titleInput?.value?.trim();
        if (!title) {
            showError('Vul een taaktitel in.');
            titleInput?.focus();
            return;
        }

        const checklistItems = [...document.querySelectorAll('.task-create-checklist-item')]
            .map((input) => input.value.trim())
            .filter(Boolean);

        const weekdays = showDayPicker
            ? [...document.querySelectorAll('.task-create-weekday:checked')].map((input) => input.value)
            : [];

        const payload = {
            title,
            description: descriptionInput?.value?.trim() || null,
            instructions: instructionsInput?.value?.trim() || null,
            required_proof_type: proofSelect?.value || 'none',
            is_required: requiredCheckbox?.checked ?? true,
            requires_signature: signatureCheckbox?.checked ?? false,
            weekdays: weekdays.length > 0 ? weekdays : undefined,
            checklist_items: checklistItems.length > 0 ? checklistItems : undefined,
            metric_type: metricType?.value || null,
            metric_unit: metricUnit?.value?.trim() || null,
            metric_min: metricMin?.value !== '' ? metricMin?.value : null,
            metric_max: metricMax?.value !== '' ? metricMax?.value : null,
            metric_comparison: 'lte',
        };

        if (submitBtn) {
            submitBtn.disabled = true;
        }

        try {
            const response = await fetch(storeUrl, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: JSON.stringify(payload),
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                const firstError = data.errors
                    ? Object.values(data.errors).flat()[0]
                    : null;
                showError(data.message || firstError || 'Taak toevoegen mislukt.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
                return;
            }

            window.location.href = data.redirect || window.location.pathname;
        } catch {
            showError('Taak toevoegen mislukt. Probeer opnieuw.');
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        }
    });

    if (modal.dataset.autoOpen === '1') {
        openModal({ weekday: modal.dataset.presetWeekday || null });

        const url = new URL(window.location.href);
        url.searchParams.delete('addTask');
        url.searchParams.delete('weekday');
        window.history.replaceState({}, '', url);
    }
}
