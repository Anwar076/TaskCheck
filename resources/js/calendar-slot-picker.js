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
    let draggedUnscheduledList = null;
    let suppressTimedClick = false;
    const scrollStorageKey = 'taskcheck.calendar.scroll';

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

    const restoreScrollPosition = () => {
        const raw = window.sessionStorage.getItem(scrollStorageKey);
        if (!raw) {
            return;
        }

        window.sessionStorage.removeItem(scrollStorageKey);

        try {
            const position = JSON.parse(raw);
            window.requestAnimationFrame(() => {
                window.scrollTo({
                    top: Number(position.top) || 0,
                    left: Number(position.left) || 0,
                });
            });
        } catch {
            // Ignore malformed storage; the next interaction will write a fresh value.
        }
    };

    const reloadCalendar = () => {
        window.sessionStorage.setItem(scrollStorageKey, JSON.stringify({
            top: window.scrollY,
            left: window.scrollX,
        }));
        window.location.reload();
    };

    const refreshCalendar = async () => {
        const calendarRoot = grid.closest('[data-onboarding-target="calendar-main"]');
        if (!calendarRoot) {
            reloadCalendar();
            return;
        }

        const scrollPosition = {
            top: window.scrollY,
            left: window.scrollX,
        };

        calendarRoot.classList.add('pointer-events-none', 'opacity-70');

        try {
            const response = await fetch(window.location.href, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'text/html',
                },
            });

            if (!response.ok) {
                reloadCalendar();
                return;
            }

            const html = await response.text();
            const documentFragment = new DOMParser().parseFromString(html, 'text/html');
            const freshCalendarRoot = documentFragment.querySelector('[data-onboarding-target="calendar-main"]');

            if (!freshCalendarRoot) {
                reloadCalendar();
                return;
            }

            popup?.remove();
            backdrop?.remove();
            calendarRoot.replaceWith(freshCalendarRoot);
            window.scrollTo(scrollPosition.left, scrollPosition.top);
            initCalendarSlotPicker();
        } catch {
            reloadCalendar();
        }
    };

    restoreScrollPosition();

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

    const markPreviewInvalid = (target) => {
        if (!target) {
            return;
        }

        target.style.background = 'rgba(239, 68, 68, 0.18)';
        target.style.borderTopColor = '#ef4444';
        target.style.borderBottomColor = '#ef4444';
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
        if (mode === 'edit') {
            listSelectWrap?.classList.add('hidden');
        }
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

        if (popupState?.mode === 'edit') {
            listSelectWrap?.classList.add('hidden');
        }
    };

    const closePopup = () => {
        popup?.classList.add('hidden');
        backdrop?.classList.add('hidden');
        clearSelection();
        popupState = null;
        form?.reset();
        weekdaySelect?.closest('div')?.classList.remove('hidden');
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

    const resolveForwardTimeRange = (startMin, endMin) => {
        const start = Math.max(gridStartMinutes, Math.min(gridEndMinutes - slotMinutes, startMin));
        const end = Math.max(start + slotMinutes, Math.min(gridEndMinutes, endMin));

        return {
            startMin: start,
            endMin: end,
            startTime: minutesToTime(start),
            endTime: minutesToTime(end),
        };
    };

    const resolveResizeStartRange = (startMin, fixedEndMin) => {
        const end = Math.max(gridStartMinutes + slotMinutes, Math.min(gridEndMinutes, fixedEndMin));
        const start = Math.max(gridStartMinutes, Math.min(end - slotMinutes, startMin));

        return {
            startMin: start,
            endMin: end,
            startTime: minutesToTime(start),
            endTime: minutesToTime(end),
        };
    };

    const resolveResizeEndRange = (fixedStartMin, endMin) => {
        const start = Math.max(gridStartMinutes, Math.min(gridEndMinutes - slotMinutes, fixedStartMin));
        const end = Math.max(start + slotMinutes, Math.min(gridEndMinutes, endMin));

        return {
            startMin: start,
            endMin: end,
            startTime: minutesToTime(start),
            endTime: minutesToTime(end),
        };
    };

    const formatDateLabel = (isoDate) => {
        if (!isoDate) {
            return '';
        }
        const parts = isoDate.split('-');
        if (parts.length !== 3) {
            return isoDate;
        }
        return `${parts[2]}-${parts[1]}-${parts[0]}`;
    };

    const openPopup = ({ mode, config, lists, weekday, date, startTime, endTime, anchorElement, slotId, sourceListId, updateUrl, deleteUrl, manageUrl }) => {
        if (lists.length === 0 || !popup || !form) {
            return;
        }

        popupState = {
            mode,
            config,
            lists,
            weekday,
            date: date || config?.date || null,
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
        const dateLabel = formatDateLabel(popupState.date);
        if (metaLabel) {
            metaLabel.textContent = dateLabel
                ? `${dateLabel} · ${dayLabel} · ${startTime}${endTime ? ` – ${endTime}` : ''}`
                : `${dayLabel} · ${startTime}${endTime ? ` – ${endTime}` : ''}`;
        }
        if (weekdaySelect) {
            weekdaySelect.value = weekday;
            weekdaySelect.closest('div')?.classList.toggle('hidden', Boolean(popupState.date));
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
            date: config.date,
            startTime,
            endTime,
            anchorElement: activeSelection,
        });
    };

    const openEditPopup = (button, column, config) => {
        const weekday = button.dataset.weekday || config.weekday;
        const date = button.dataset.date || config.date || null;
        const startTime = button.dataset.startTime || '';
        const endTime = button.dataset.endTime || '';
        const sourceListId = button.dataset.listId;
        const sourceListTitle = button.dataset.listTitle || 'Lijst';
        const allLists = config.lists || [];
        const lists = allLists.filter((item) => String(item.id) === String(sourceListId));

        openPopup({
            mode: 'edit',
            config: { ...config, column },
            lists: lists.length > 0
                ? lists
                : [{ id: sourceListId, title: sourceListTitle, storeUrl: button.dataset.storeUrl || '' }],
            weekday,
            date,
            startTime,
            endTime,
            anchorElement: button,
            slotId: button.dataset.slotId,
            sourceListId,
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

    const readDroppedList = (event) => {
        const raw = event.dataTransfer?.getData('application/x-taskcheck-list');

        if (raw) {
            try {
                return JSON.parse(raw);
            } catch {
                return draggedUnscheduledList;
            }
        }

        return draggedUnscheduledList;
    };

    const setDropActive = (target, active) => {
        if (!target) {
            return;
        }

        if (active) {
            target.setAttribute('data-calendar-drop-active', '1');
        } else {
            target.removeAttribute('data-calendar-drop-active');
        }
    };

    const submitDroppedList = async (url, payload) => {
        if (!url) {
            return;
        }

        try {
            const response = await fetch(url, {
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
                window.alert(data.message || data.errors?.end_time?.[0] || 'Plannen mislukt.');
                return;
            }

            await refreshCalendar();
        } catch {
            window.alert('Plannen mislukt. Probeer opnieuw.');
        }
    };

    const submitUpdatedSlot = async (button, payload) => {
        const url = button.dataset.updateUrl;
        if (!url) {
            return;
        }

        try {
            const response = await fetch(url, {
                method: 'PUT',
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
                window.alert(data.message || data.errors?.end_time?.[0] || 'Tijdslot aanpassen mislukt.');
                return;
            }

            await refreshCalendar();
        } catch {
            window.alert('Tijdslot aanpassen mislukt. Probeer opnieuw.');
        }
    };

    document.querySelectorAll('[data-unscheduled-list]').forEach((source) => {
        source.addEventListener('dragstart', (event) => {
            draggedUnscheduledList = {
                id: source.dataset.listId,
                title: source.dataset.listTitle,
                storeUrl: source.dataset.storeUrl,
                dayStoreUrl: source.dataset.dayStoreUrl,
            };

            event.dataTransfer.effectAllowed = 'copy';
            event.dataTransfer.setData('application/x-taskcheck-list', JSON.stringify(draggedUnscheduledList));
            event.dataTransfer.setData('text/plain', draggedUnscheduledList.title || '');
            source.classList.add('opacity-60');
        });

        source.addEventListener('dragend', () => {
            draggedUnscheduledList = null;
            source.classList.remove('opacity-60');
            document.querySelectorAll('[data-calendar-drop-active]').forEach((target) => setDropActive(target, false));
        });
    });

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
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrf,
                    'X-Requested-With': 'XMLHttpRequest',
                    Accept: 'application/json',
                },
                body: JSON.stringify({
                    date: popupState.date || null,
                }),
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

            await refreshCalendar();
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

        if (popupState.date) {
            payload.date = popupState.date;
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

            await refreshCalendar();
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

        cell.addEventListener('dragover', (event) => {
            const droppedList = readDroppedList(event);
            if (!droppedList || !config.canCreate) {
                return;
            }

            event.preventDefault();
            event.dataTransfer.dropEffect = 'copy';
            setDropActive(cell, true);
        });

        cell.addEventListener('dragleave', (event) => {
            if (!cell.contains(event.relatedTarget)) {
                setDropActive(cell, false);
            }
        });

        cell.addEventListener('drop', (event) => {
            const droppedList = readDroppedList(event);
            if (!droppedList || !config.canCreate) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            setDropActive(cell, false);

            if (droppedList.dayStoreUrl) {
                submitDroppedList(droppedList.dayStoreUrl, {
                    weekday: config.weekday,
                });
                return;
            }

            submitDroppedList(droppedList.storeUrl, {
                weekday: config.weekday,
                start_time: '09:00',
                end_time: '10:00',
            });
        });

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
                date: config.date,
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

            if (suppressTimedClick) {
                event.preventDefault();
                event.stopPropagation();
                suppressTimedClick = false;
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
        let blockDrag = null;

        const yToMinutes = (clientY) => {
            const rect = column.getBoundingClientRect();
            const pct = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height));
            const raw = gridStartMinutes + pct * totalMinutes;
            return Math.round(raw / slotMinutes) * slotMinutes;
        };

        const timeColumnAt = (clientX, clientY) => {
            const target = document.elementsFromPoint(clientX, clientY)
                .map((element) => element.closest?.('[data-calendar-time-column]'))
                .find(Boolean);

            if (!target) {
                return null;
            }

            let targetConfig = {};
            try {
                targetConfig = JSON.parse(target.dataset.dayConfig || '{}');
            } catch {
                return null;
            }

            if (!targetConfig.canCreate) {
                return null;
            }

            return {
                column: target,
                config: targetConfig,
            };
        };

        const yToMinutesForColumn = (targetColumn, clientY) => {
            const rect = targetColumn.getBoundingClientRect();
            const pct = Math.max(0, Math.min(1, (clientY - rect.top) / rect.height));
            const raw = gridStartMinutes + pct * totalMinutes;

            return Math.round(raw / slotMinutes) * slotMinutes;
        };

        const showBlockPreview = (targetColumn, range, label) => {
            if (!preview || preview.parentElement !== targetColumn) {
                preview?.remove();
                preview = buildSelectionElement(range.startMin, range.endMin, null, null, true);
                targetColumn.appendChild(preview);
            }

            preview.style.top = `${((range.startMin - gridStartMinutes) / totalMinutes) * 100}%`;
            preview.style.height = `${((range.endMin - range.startMin) / totalMinutes) * 100}%`;
            preview.style.background = '';
            preview.style.borderTopColor = '';
            preview.style.borderBottomColor = '';
            preview.innerHTML = '';

            const labelEl = document.createElement('div');
            labelEl.setAttribute('data-calendar-selection-label', '1');
            labelEl.textContent = label || `${range.startTime} – ${range.endTime}`;
            preview.appendChild(labelEl);
        };

        const cleanupBlockDrag = () => {
            preview?.remove();
            preview = null;
            blockDrag = null;
            document.body.classList.remove('select-none');
            document.removeEventListener('mousemove', onBlockMove);
            document.removeEventListener('mouseup', onBlockUp);
        };

        const currentBlockRange = (event) => {
            if (!blockDrag) {
                return null;
            }

            const target = timeColumnAt(event.clientX, event.clientY);
            if (!target) {
                return null;
            }

            if (blockDrag.mode === 'resize') {
                const pointerMin = yToMinutesForColumn(target.column, event.clientY);

                if (blockDrag.edge === 'start') {
                    return {
                        target,
                        range: resolveResizeStartRange(pointerMin, blockDrag.endMin),
                    };
                }

                return {
                    target,
                    range: resolveResizeEndRange(blockDrag.startMin, pointerMin),
                };
            }

            const pointerMin = yToMinutesForColumn(target.column, event.clientY);
            let startMin = pointerMin - blockDrag.pointerOffsetMin;
            startMin = Math.round(startMin / slotMinutes) * slotMinutes;
            startMin = Math.max(gridStartMinutes, Math.min(gridEndMinutes - blockDrag.durationMin, startMin));

            return {
                target,
                range: resolveForwardTimeRange(startMin, startMin + blockDrag.durationMin),
            };
        };

        const onBlockMove = (event) => {
            if (!blockDrag) {
                return;
            }

            const movedEnough = Math.abs(event.clientX - blockDrag.startX) > 3 || Math.abs(event.clientY - blockDrag.startY) > 3;
            if (movedEnough) {
                blockDrag.hasMoved = true;
            }

            const next = currentBlockRange(event);
            if (!next) {
                showBlockPreview(blockDrag.column, {
                    startMin: blockDrag.startMin,
                    endMin: blockDrag.endMin,
                    startTime: minutesToTime(blockDrag.startMin),
                    endTime: minutesToTime(blockDrag.endMin),
                }, 'Laat los op een tijdkolom');
                markPreviewInvalid(preview);
                return;
            }

            showBlockPreview(next.target.column, next.range, `${next.range.startTime} – ${next.range.endTime}`);
        };

        const onBlockUp = (event) => {
            if (!blockDrag) {
                cleanupBlockDrag();
                return;
            }

            const didMove = blockDrag.hasMoved;
            const source = blockDrag.button;
            const next = currentBlockRange(event);
            cleanupBlockDrag();

            if (!didMove || !next) {
                return;
            }

            suppressTimedClick = true;
            window.setTimeout(() => {
                suppressTimedClick = false;
            }, 80);

            submitUpdatedSlot(source, {
                weekday: next.target.config.weekday,
                start_time: next.range.startTime,
                end_time: next.range.endTime,
            });
        };

        column.addEventListener('dragover', (event) => {
            const droppedList = readDroppedList(event);
            if (!droppedList || !config.canCreate) {
                return;
            }

            event.preventDefault();
            event.dataTransfer.dropEffect = 'copy';
            setDropActive(column, true);
        });

        column.addEventListener('dragleave', (event) => {
            if (!column.contains(event.relatedTarget)) {
                setDropActive(column, false);
            }
        });

        column.addEventListener('drop', (event) => {
            const droppedList = readDroppedList(event);
            if (!droppedList || !config.canCreate || !droppedList.storeUrl) {
                return;
            }

            event.preventDefault();
            event.stopPropagation();
            setDropActive(column, false);

            const startMin = yToMinutes(event.clientY);
            const range = resolveTimeRange(startMin, startMin + slotMinutes);

            submitDroppedList(droppedList.storeUrl, {
                weekday: config.weekday,
                start_time: range.startTime,
                end_time: range.endTime,
            });
        });

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
            const timedList = event.target.closest('[data-calendar-timed-list]');
            if (timedList) {
                if (!column.contains(timedList) || timedList.dataset.isDefault === '1') {
                    return;
                }

                event.preventDefault();
                event.stopPropagation();

                if (popup && !popup.classList.contains('hidden')) {
                    closePopup();
                }

                const startMin = timeToMinutes(timedList.dataset.startTime) ?? gridStartMinutes;
                const parsedEndMin = timeToMinutes(timedList.dataset.endTime);
                const endMin = parsedEndMin !== null && parsedEndMin > startMin ? parsedEndMin : startMin + slotMinutes;
                const pointerMin = yToMinutes(event.clientY);

                const resizeHandle = event.target.closest('[data-calendar-resize-handle]');

                blockDrag = {
                    button: timedList,
                    column,
                    config,
                    mode: resizeHandle ? 'resize' : 'move',
                    edge: resizeHandle?.dataset.calendarResizeHandle || 'end',
                    startMin,
                    endMin,
                    durationMin: Math.max(slotMinutes, endMin - startMin),
                    pointerOffsetMin: Math.max(0, pointerMin - startMin),
                    startX: event.clientX,
                    startY: event.clientY,
                    hasMoved: false,
                };

                const initialRange = resolveForwardTimeRange(startMin, endMin);
                showBlockPreview(column, initialRange, `${initialRange.startTime} – ${initialRange.endTime}`);
                document.body.classList.add('select-none');
                document.addEventListener('mousemove', onBlockMove);
                document.addEventListener('mouseup', onBlockUp);
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
