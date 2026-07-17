/**
 * Pop-up voor taak aanmaken en bewerken (vervangt /tasks/create en /tasks/edit pagina's).
 */
export function initTaskCreateModal() {
    const modal = document.querySelector('[data-task-create-modal]');
    if (!modal) {
        return;
    }

    const form = document.getElementById('task-create-form');
    const modalTitle = document.getElementById('task-create-modal-title');
    const titleInput = document.getElementById('task-create-title');
    const proofSelect = document.getElementById('task-create-proof');
    const requiredCheckbox = document.getElementById('task-create-required');
    const signatureCheckbox = document.getElementById('task-create-signature');
    const descriptionInput = document.getElementById('task-create-description');
    const instructionsInput = document.getElementById('task-create-instructions');
    const normReferenceInput = document.getElementById('task-create-norm-reference');
    const acceptanceCriteriaInput = document.getElementById('task-create-acceptance-criteria');
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
    const formDataUrlBase = modal.dataset.formDataUrl;
    const showDayPicker = modal.dataset.showDayPicker === '1';
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let expandedOpen = false;
    let editMode = false;
    let updateUrl = null;

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

    const setExpanded = (open) => {
        expandedOpen = open;
        expanded?.classList.toggle('hidden', !expandedOpen);
        expandIcon?.classList.toggle('rotate-180', expandedOpen);
    };

    const setModalMode = (mode) => {
        editMode = mode === 'edit';
        updateUrl = null;

        if (modalTitle) {
            modalTitle.textContent = editMode ? 'Taak bewerken' : 'Nieuwe taak';
        }
        if (submitBtn) {
            submitBtn.textContent = editMode ? 'Wijzigingen opslaan' : 'Taak toevoegen';
        }
    };

    const resetForm = () => {
        form?.reset();
        if (requiredCheckbox) {
            requiredCheckbox.checked = true;
        }
        if (checklistContainer) {
            checklistContainer.innerHTML = '';
        }
        setExpanded(false);
        setModalMode('create');
        hideError();
    };

    const populateForm = (task) => {
        if (titleInput) {
            titleInput.value = task.title || '';
        }
        if (proofSelect) {
            proofSelect.value = task.required_proof_type || 'none';
        }
        if (requiredCheckbox) {
            requiredCheckbox.checked = task.is_required ?? true;
        }
        if (signatureCheckbox) {
            signatureCheckbox.checked = task.requires_signature ?? false;
        }
        if (descriptionInput) {
            descriptionInput.value = task.description || '';
        }
        if (instructionsInput) {
            instructionsInput.value = task.instructions || '';
        }
        if (normReferenceInput) {
            normReferenceInput.value = task.control_reference || '';
        }
        if (acceptanceCriteriaInput) {
            acceptanceCriteriaInput.value = task.acceptance_criteria || '';
        }
        if (metricType) {
            metricType.value = task.metric_type || '';
        }
        if (metricUnit) {
            metricUnit.value = task.metric_unit || '';
        }
        if (metricMin) {
            metricMin.value = task.metric_min ?? '';
        }
        if (metricMax) {
            metricMax.value = task.metric_max ?? '';
        }

        if (checklistContainer) {
            checklistContainer.innerHTML = '';
            (task.checklist_items || []).forEach((item) => addChecklistRow(item));
        }

        if (showDayPicker) {
            document.querySelectorAll('.task-create-weekday').forEach((input) => {
                input.checked = (task.weekdays || []).includes(input.value);
            });
        }

        const hasExpandedContent = Boolean(
            task.description
            || task.instructions
            || task.control_reference
            || task.acceptance_criteria
            || (task.checklist_items && task.checklist_items.length > 0)
            || task.requires_signature
            || task.metric_type
            || (showDayPicker && task.weekdays && task.weekdays.length > 0)
        );
        setExpanded(hasExpandedContent);
    };

    const openCreateModal = (options = {}) => {
        resetForm();

        if (options.weekday && showDayPicker) {
            setExpanded(true);
            document.querySelectorAll('.task-create-weekday').forEach((input) => {
                input.checked = input.value === options.weekday;
            });
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');
        setTimeout(() => titleInput?.focus(), 50);
    };

    const openEditModal = async (taskId) => {
        if (!taskId || !formDataUrlBase) {
            return;
        }

        resetForm();
        setModalMode('edit');
        hideError();

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.textContent = 'Laden…';
        }

        modal.classList.remove('hidden');
        document.body.classList.add('overflow-hidden');

        try {
            const response = await fetch(`${formDataUrlBase}/${taskId}/form-data`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success || !data.task) {
                showError(data.message || 'Taak laden mislukt.');
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Wijzigingen opslaan';
                }
                return;
            }

            updateUrl = data.task.update_url;
            populateForm(data.task);

            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Wijzigingen opslaan';
            }

            setTimeout(() => titleInput?.focus(), 50);
        } catch {
            showError('Taak laden mislukt. Probeer opnieuw.');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.textContent = 'Wijzigingen opslaan';
            }
        }
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
        setExpanded(!expandedOpen);
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
            openCreateModal({
                weekday: trigger.dataset.weekday || null,
            });
        });
    });

    document.querySelectorAll('[data-open-task-edit]').forEach((trigger) => {
        trigger.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
            openEditModal(trigger.dataset.taskId);
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
            control_reference: normReferenceInput?.value?.trim() || null,
            acceptance_criteria: acceptanceCriteriaInput?.value?.trim() || null,
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

        const requestUrl = editMode && updateUrl ? updateUrl : storeUrl;
        const requestMethod = editMode && updateUrl ? 'PUT' : 'POST';
        const failureMessage = editMode ? 'Taak bijwerken mislukt.' : 'Taak toevoegen mislukt.';

        try {
            const response = await fetch(requestUrl, {
                method: requestMethod,
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
                showError(data.message || firstError || failureMessage);
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
                return;
            }

            window.location.href = data.redirect || window.location.pathname;
        } catch {
            showError(`${failureMessage} Probeer opnieuw.`);
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        }
    });

    if (modal.dataset.autoOpen === '1') {
        openCreateModal({ weekday: modal.dataset.presetWeekday || null });

        const url = new URL(window.location.href);
        url.searchParams.delete('addTask');
        url.searchParams.delete('weekday');
        window.history.replaceState({}, '', url);
    }

    if (modal.dataset.autoEditTask) {
        openEditModal(modal.dataset.autoEditTask);

        const url = new URL(window.location.href);
        url.searchParams.delete('editTask');
        window.history.replaceState({}, '', url);
    }
}
