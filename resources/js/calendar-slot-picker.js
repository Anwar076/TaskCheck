/**
 * Calendar: koppel lijsten aan tijdslots in de weekweergave.
 */
export function initCalendarSlotPicker() {
    const grid = document.querySelector('[data-calendar-slot-grid]');
    if (!grid) {
        return;
    }

    const dayStartHour = parseInt(grid.dataset.dayStartHour || '6', 10);
    const dayEndHour = parseInt(grid.dataset.dayEndHour || '22', 10);
    const slotMinutes = parseInt(grid.dataset.slotMinutes || '30', 10);
    const gridStartMinutes = dayStartHour * 60;
    const gridEndMinutes = dayEndHour * 60;
    const totalMinutes = Math.max(1, gridEndMinutes - gridStartMinutes);

    const popup = document.getElementById('calendar-list-schedule');
    const backdrop = document.getElementById('calendar-list-schedule-backdrop');
    const form = document.getElementById('calendar-list-schedule-form');
    const titleEl = document.getElementById('calendar-list-schedule-title');
    const metaLabel = document.getElementById('calendar-list-schedule-meta');
    const listSelect = document.getElementById('calendar-list-select');
    const listSelectWrap = document.getElementById('calendar-list-select-wrap');
    const weekdaySelect = document.getElementById('calendar-list-weekday');
    const startInput = document.getElementById('calendar-list-start');
    const endInput = document.getElementById('calendar-list-end');
    const workingHoursWarning = document.getElementById('calendar-list-working-hours-warning');
    const errorBox = document.getElementById('calendar-list-schedule-error');
    const submitBtn = document.getElementById('calendar-list-schedule-submit');
    const deleteBtn = document.getElementById('calendar-list-schedule-delete');
    const manageLink = document.getElementById('calendar-list-schedule-manage');
    const csrf = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';

    let activeSelection = null;
    let popupState = null;

    const weekdayLabels = {
        monday: 'Maandag',
        tuesday: 'Dinsdag',
        wednesday: 'Woensdag',
        thursday: 'Donderdag',
        friday: 'Vrijdag',
        saturday: 'Zaterdag',
        sunday: 'Zondag',
    };

    if (popup && popup.parentElement !== document.body) {
        document.body.appendChild(popup);
    }
    if (backdrop && backdrop.parentElement !== document.body) {
        document.body.appendChild(backdrop);
    }

    const minutesToTime = (minutes) => {
        const clamped = Math.max(gridStartMinutes, Math.min(gridEndMinutes, minutes));
        const hours = Math.floor(clamped / 60);
        const mins = clamped % 60;
        return `${String(hours).padStart(2, '0')}:${String(mins).padStart(2, '0')}`;
    };

    const timeToMinutes = (time) => {
        const [hours, minutes] = String(time || '').split(':').map((part) => parseInt(part, 10));
        if (!Number.isFinite(hours) || !Number.isFinite(minutes)) {
            return null;
        }

        return hours * 60 + minutes;
    };

    const isOutsideWorkingHours = (weekday, startTime, endTime) => {
        const dayHours = popupState?.config?.workingHoursByDay?.[weekday];

        if (!dayHours) {
            return false;
        }
        if (dayHours.enabled === false || dayHours.enabled === '0') {
            return true;
        }

        const startMinutes = timeToMinutes(startTime);
        const endMinutes = timeToMinutes(endTime) ?? (startMinutes === null ? null : startMinutes + slotMinutes);
        const workStart = timeToMinutes(dayHours.start);
        const workEnd = timeToMinutes(dayHours.end);

        if ([startMinutes, endMinutes, workStart, workEnd].some((value) => value === null)) {
            return false;
        }

        return startMinutes < workStart || endMinutes > workEnd;
    };

    const updateWorkingHoursWarning = () => {
        if (!workingHoursWarning || !popupState) {
            return;
        }

        const weekday = weekdaySelect?.value || popupState.weekday;
        const startTime = startInput?.value || popupState.startTime;
        const endTime = endInput?.value || popupState.endTime || startTime;
        const showWarning = isOutsideWorkingHours(weekday, startTime, endTime);

        workingHoursWarning.classList.toggle('hidden', !showWarning);
    };

    const clearSelection = () => {
        document.querySelectorAll('[data-calendar-selection], [data-calendar-drag-preview]').forEach((el) => el.remove());
        activeSelection = null;
    };

    const buildSelectionElement = (startMin, endMin, startTime, endTime, isPreview = false) => {
        const top = ((startMin - gridStartMinutes) / totalMinutes) * 100;
        const height = Math.max(((endMin - startMin) / totalMinutes) * 100, (slotMinutes / totalMinutes) * 100);

        const el = document.createElement('div');
        el.setAttribute(isPreview ? 'data-calendar-drag-preview' : 'data-calendar-selection', '1');
        el.style.top = `${top}%`;
        el.style.height = `${height}%`;

        if (!isPreview && startTime && endTime) {
            const label = document.createElement('div');
            label.setAttribute('data-calendar-selection-label', '1');
            label.textContent = `${startTime} – ${endTime}`;
            el.appendChild(label);
        }

        return el;
    };

    const showSelection = (column, startMin, endMin, startTime, endTime) => {
        clearSelection();
        activeSelection = buildSelectionElement(startMin, endMin, startTime, endTime, false);
        column.appendChild(activeSelection);
    };

    const positionPopup = () => {
        if (!popup) {
            return;
        }

        const anchorRect = popupState?.anchorElement
            ? popupState.anchorElement.getBoundingClientRect()
            : activeSelection
                ? activeSelection.getBoundingClientRect()
                : {
                    left: window.innerWidth / 2 - 160,
                    width: 320,
                    top: window.innerHeight / 2 - 100,
                    bottom: window.innerHeight / 2,
                };

        const popupWidth = popup.offsetWidth || 320;
        const popupHeight = popup.offsetHeight || 200;

        let left = anchorRect.left + anchorRect.width / 2 - popupWidth / 2;
        let top = anchorRect.bottom + 8;

        if (top + popupHeight > window.innerHeight - 16) {
            top = anchorRect.top - popupHeight - 8;
        }
        if (top < 16) {
            top = 16;
        }

        left = Math.max(16, Math.min(left, window.innerWidth - popupWidth - 16));

        popup.style.left = `${left}px`;
        popup.style.top = `${top}px`;
    };

    const setPopupMode = (mode) => {
        if (titleEl) {
            titleEl.textContent = mode === 'edit' ? 'Tijdslot aanpassen' : 'Lijst plannen';
        }
        if (submitBtn) {
            submitBtn.textContent = mode === 'edit' ? 'Opslaan' : 'Koppelen';
        }
        deleteBtn?.classList.toggle('hidden', mode !== 'edit');
        manageLink?.classList.toggle('hidden', mode !== 'edit');
    };

    const populateListSelect = (lists, selectedId) => {
        if (!listSelect) {
            return;
        }

        if (lists.length > 1) {
            listSelectWrap?.classList.remove('hidden');
            listSelect.innerHTML = lists.map((item) => (
                `<option value="${item.id}"${String(item.id) === String(selectedId) ? ' selected' : ''}>${item.title}</option>`
            )).join('');
        } else if (lists.length === 1) {
            listSelectWrap?.classList.add('hidden');
            listSelect.innerHTML = `<option value="${lists[0].id}" selected>${lists[0].title}</option>`;
        }
    };

    const closePopup = () => {
        popup?.classList.add('hidden');
        backdrop?.classList.add('hidden');
        clearSelection();
        popupState = null;
        form?.reset();
        setPopupMode('create');
        if (errorBox) {
            errorBox.classList.add('hidden');
            errorBox.textContent = '';
        }
        workingHoursWarning?.classList.add('hidden');
        if (submitBtn) {
            submitBtn.disabled = false;
        }
        if (deleteBtn) {
            deleteBtn.disabled = false;
        }
    };

    const resolveTimeRange = (startMin, endMin) => {
        let start = Math.min(startMin, endMin);
        let end = Math.max(startMin, endMin);
        if (end <= start) {
            end = start + slotMinutes;
        }
        end = Math.min(end, gridEndMinutes);
        return {
            startMin: start,
            endMin: end,
            startTime: minutesToTime(start),
            endTime: minutesToTime(end),
        };
    };

    const openPopup = ({ mode, config, lists, weekday, startTime, endTime, anchorElement, slotId, sourceListId, updateUrl, deleteUrl, manageUrl }) => {
        if (lists.length === 0 || !popup || !form) {
            return;
        }

        popupState = {
            mode,
            config,
            lists,
            weekday,
            startTime,
            endTime,
            column: config?.column || null,
            anchorElement: anchorElement || null,
            slotId: slotId || null,
            sourceListId: sourceListId || null,
            updateUrl: updateUrl || null,
            deleteUrl: deleteUrl || null,
        };

        setPopupMode(mode);

        const dayLabel = weekdayLabels[weekday] || weekday || 'Deze dag';
        if (metaLabel) {
            metaLabel.textContent = `${dayLabel} · ${startTime}${endTime ? ` – ${endTime}` : ''}`;
        }
        if (weekdaySelect) {
            weekdaySelect.value = weekday;
        }
        if (startInput) {
            startInput.value = startTime;
        }
        if (endInput) {
            endInput.value = endTime || '';
        }
        updateWorkingHoursWarning();

        populateListSelect(lists, sourceListId || lists[0]?.id);

        if (manageLink && manageUrl) {
            manageLink.href = manageUrl;
        }

        popup.classList.remove('hidden');
        backdrop?.classList.remove('hidden');
        positionPopup();
        (lists.length > 1 ? listSelect : startInput)?.focus();
    };

    [weekdaySelect, startInput, endInput].forEach((input) => {
        input?.addEventListener('input', updateWorkingHoursWarning);
        input?.addEventListener('change', updateWorkingHoursWarning);
    });

    const openSchedulePopup = (column, config, startTime, endTime) => {
        openPopup({
            mode: 'create',
            config: { ...config, column },
            lists: config.lists || [],
            weekday: config.weekday,
            startTime,
            endTime,
            anchorElement: activeSelection,
        });
    };

    const openEditPopup = (button, column, config) => {
        const weekday = button.dataset.weekday || config.weekday;
        const startTime = button.dataset.startTime || '';
        const endTime = button.dataset.endTime || '';

        openPopup({
            mode: 'edit',
            config: { ...config, column },
            lists: config.lists || [],
            weekday,
            startTime,
            endTime,
            anchorElement: button,
            slotId: button.dataset.slotId,
            sourceListId: button.dataset.listId,
            updateUrl: button.dataset.updateUrl,
            deleteUrl: button.dataset.deleteUrl,
            manageUrl: button.dataset.manageUrl,
        });
    };

    const handleSlotSelection = (column, config, startMin, endMin) => {
        const range = resolveTimeRange(startMin, endMin);
        showSelection(column, range.startMin, range.endMin, range.startTime, range.endTime);
        openSchedulePopup(column, config, range.startTime, range.endTime);
    };

    popup?.querySelectorAll('[data-calendar-schedule-close]').forEach((btn) => {
        btn.addEventListener('click', closePopup);
    });

    backdrop?.addEventListener('click', closePopup);

    deleteBtn?.addEventListener('click', async () => {
        if (!popupState || popupState.mode !== 'edit' || !popupState.deleteUrl) {
            return;
        }

        if (!window.confirm('Weet je zeker dat je dit tijdslot wilt verwijderen?')) {
            return;
        }

        deleteBtn.disabled = true;
        errorBox?.classList.add('hidden');

        try {
            const response = await fetch(popupState.deleteUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
            });

            const data = await response.json();

            if (!response.ok || !data.success) {
                if (errorBox) {
                    errorBox.textContent = data.message || 'Verwijderen mislukt.';
                    errorBox.classList.remove('hidden');
                }
                deleteBtn.disabled = false;
                return;
            }

            window.location.reload();
        } catch {
            if (errorBox) {
                errorBox.textContent = 'Verwijderen mislukt. Probeer opnieuw.';
                errorBox.classList.remove('hidden');
            }
            deleteBtn.disabled = false;
        }
    });

    form?.addEventListener('submit', async (event) => {
        event.preventDefault();
        if (!popupState) {
            return;
        }

        const lists = popupState.lists;
        const selectedList = lists.length > 1
            ? lists.find((item) => String(item.id) === listSelect?.value)
            : lists[0];

        if (!selectedList) {
            return;
        }

        const isEdit = popupState.mode === 'edit';
        const requestUrl = isEdit ? popupState.updateUrl : selectedList.storeUrl;

        if (!requestUrl) {
            return;
        }

        if (submitBtn) {
            submitBtn.disabled = true;
        }
        errorBox?.classList.add('hidden');

        const payload = {
            weekday: weekdaySelect?.value || popupState.weekday,
            start_time: startInput?.value || popupState.startTime,
            end_time: endInput?.value || popupState.endTime || null,
        };

        if (isEdit && String(selectedList.id) !== String(popupState.sourceListId)) {
            payload.target_list_id = selectedList.id;
        }

        try {
            const response = await fetch(requestUrl, {
                method: isEdit ? 'PUT' : 'POST',
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
                const message = data.message || data.errors?.end_time?.[0] || (isEdit ? 'Opslaan mislukt.' : 'Koppelen mislukt.');
                if (errorBox) {
                    errorBox.textContent = message;
                    errorBox.classList.remove('hidden');
                }
                if (submitBtn) {
                    submitBtn.disabled = false;
                }
                return;
            }

            window.location.reload();
        } catch {
            if (errorBox) {
                errorBox.textContent = isEdit ? 'Opslaan mislukt. Probeer opnieuw.' : 'Koppelen mislukt. Probeer opnieuw.';
                errorBox.classList.remove('hidden');
            }
            if (submitBtn) {
                submitBtn.disabled = false;
            }
        }
    });

    window.addEventListener('resize', () => {
        if (!popup?.classList.contains('hidden')) {
            positionPopup();
        }
    });

    grid.querySelectorAll('[data-calendar-all-day-column]').forEach((cell) => {
        let config = {};
        try {
            config = JSON.parse(cell.dataset.dayConfig || '{}');
        } catch {
            return;
        }

        cell.addEventListener('click', (event) => {
            const allDayBtn = event.target.closest('[data-calendar-all-day-list]');
            if (!allDayBtn || !config.canCreate) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            if (popup && !popup.classList.contains('hidden')) {
                closePopup();
            }

            openPopup({
                mode: 'create',
                config,
                lists: config.lists || [],
                weekday: config.weekday,
                startTime: '09:00',
                endTime: '10:00',
                anchorElement: allDayBtn,
                sourceListId: allDayBtn.dataset.listId,
            });
        });
    });

    grid.querySelectorAll('[data-calendar-time-column]').forEach((column) => {
        let config = {};
        try {
            config = JSON.parse(column.dataset.dayConfig || '{}');
        } catch {
            return;
        }

        column.addEventListener('click', (event) => {
            const timedList = event.target.closest('[data-calendar-timed-list]');
            if (!timedList || !column.contains(timedList)) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();

            if (popup && !popup.classList.contains('hidden')) {
                closePopup();
            }

            openEditPopup(timedList, column, config);
        });

        if (!config.canCreate) {
            return;
        }

        let dragStartY = null;
        let dragEndY = null;
        let preview = null;

        const yToMinutes = (clientY) => {
            const rect = column.getBoundingClientRect();
            const pct = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height));
            const raw = gridStartMinutes + pct * totalMinutes;
            return Math.round(raw / slotMinutes) * slotMinutes;
        };

        const updatePreview = () => {
            if (!preview || dragStartY === null || dragEndY === null) {
                return;
            }

            const range = resolveTimeRange(yToMinutes(dragStartY), yToMinutes(dragEndY));
            preview.style.top = `${((range.startMin - gridStartMinutes) / totalMinutes) * 100}%`;
            preview.style.height = `${((range.endMin - range.startMin) / totalMinutes) * 100}%`;
        };

        const cleanupDrag = () => {
            preview?.remove();
            preview = null;
            dragStartY = null;
            dragEndY = null;
            document.removeEventListener('mousemove', onMove);
            document.removeEventListener('mouseup', onUp);
        };

        const onMove = (event) => {
            if (dragStartY === null) {
                return;
            }
            dragEndY = event.clientY;
            updatePreview();
        };

        const onUp = () => {
            if (dragStartY === null || dragEndY === null) {
                cleanupDrag();
                return;
            }

            handleSlotSelection(column, config, yToMinutes(dragStartY), yToMinutes(dragEndY));
            cleanupDrag();
        };

        column.addEventListener('mousedown', (event) => {
            if (event.button !== 0) {
                return;
            }
            if (event.target.closest('[data-calendar-timed-list]')) {
                return;
            }
            if (popup && !popup.classList.contains('hidden')) {
                closePopup();
            }

            event.preventDefault();

            dragStartY = event.clientY;
            dragEndY = event.clientY;

            const initialRange = resolveTimeRange(yToMinutes(dragStartY), yToMinutes(dragEndY));
            preview = buildSelectionElement(initialRange.startMin, initialRange.endMin, null, null, true);
            column.appendChild(preview);
            updatePreview();

            document.addEventListener('mousemove', onMove);
            document.addEventListener('mouseup', onUp);
        });
    });
}
