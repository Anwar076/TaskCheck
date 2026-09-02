<script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

<!-- CRITICAL CAMERA & UPLOAD FUNCTIONS (single source of truth) -->
<script>
// === GLOBAL CAMERA / UPLOAD API ===

async function openCamera(taskId, mode) {
    const nativeCamera = window.TaskCheckNative?.plugin?.('Camera');
    if (mode === 'photo' && nativeCamera) {
        try {
            const image = await nativeCamera.getPhoto({
                quality: 85,
                allowEditing: false,
                correctOrientation: true,
                resultType: 'uri',
                source: 'camera',
            });
            const response = await fetch(image.webPath);
            const blob = await response.blob();
            const extension = image.format === 'png' ? 'png' : 'jpg';
            const file = new File([blob], `taskcheck-${Date.now()}.${extension}`, {
                type: blob.type || `image/${extension === 'jpg' ? 'jpeg' : extension}`,
                lastModified: Date.now(),
            });
            addProofFiles(taskId, [file]);
            return;
        } catch (error) {
            if (/cancel/i.test(error?.message || '')) return;
            console.warn('Native camera niet beschikbaar; browsercamera wordt gebruikt.', error);
        }
    }

    // mode = 'photo' of 'video'
    const inputId = mode === 'video' 
        ? 'camera-input-video-' + taskId 
        : 'camera-input-photo-' + taskId;

    const input = document.getElementById(inputId);
    if (input) {
        input.click(); // triggert Android camera (als ondersteund)
    } else {
        console.warn('Camera-input niet gevonden voor', inputId);
    }
}

function uploadFile(taskId) {
    const input = document.getElementById('file-input-' + taskId);
    if (input) {
        // Allow selecting the same file again on a next pick.
        input.value = '';
        input.click();
    } else {
        console.warn('File-input niet gevonden voor', taskId);
    }
}

window.taskProofFileStore = window.taskProofFileStore || {};
window.savedProofFiles = Object.assign({}, window.savedProofFiles || {}, @json($savedProofFilesByTask));

function proofFileKey(file) {
    return `${file.name}|${file.size}|${file.lastModified}`;
}

function getStoredProofFiles(taskId) {
    return window.taskProofFileStore[String(taskId)] || [];
}

function setStoredProofFiles(taskId, files) {
    window.taskProofFileStore[String(taskId)] = files;
    syncProofFilesToInput(taskId);
}

function getSavedProofFiles(taskId) {
    return window.savedProofFiles[String(taskId)] || [];
}

function setSavedProofFiles(taskId, files) {
    window.savedProofFiles[String(taskId)] = Array.isArray(files) ? files : [];
}

function syncProofFilesToInput(taskId) {
    const fileInput = document.getElementById('file-input-' + taskId);
    if (!fileInput) return;

    const dt = new DataTransfer();
    getStoredProofFiles(taskId).forEach(file => dt.items.add(file));
    fileInput.files = dt.files;
}

function addProofFiles(taskId, newFiles) {
    const existing = getStoredProofFiles(taskId);
    const seen = new Set(existing.map(proofFileKey));
    const merged = [...existing];

    Array.from(newFiles).forEach(file => {
        const key = proofFileKey(file);
        if (!seen.has(key)) {
            seen.add(key);
            merged.push(file);
        }
    });

    setStoredProofFiles(taskId, merged);
    renderProofFilePreviews(taskId);
}

function handleCameraCapture(cameraInput, taskId) {
    if (!cameraInput.files.length) return;

    addProofFiles(taskId, cameraInput.files);
    cameraInput.value = '';
    cameraInput.name = '';

    const photoInput = document.getElementById('camera-input-photo-' + taskId);
    const videoInput = document.getElementById('camera-input-video-' + taskId);
    if (photoInput && photoInput !== cameraInput) photoInput.name = '';
    if (videoInput && videoInput !== cameraInput) videoInput.name = '';
}

function handleFileSelect(input, taskId) {
    const newFiles = Array.from(input.files || []);
    if (newFiles.length === 0) return;

    addProofFiles(taskId, newFiles);
}

function proofFileKind(mime, name) {
    const type = (mime || '').toLowerCase();
    const fileName = (name || '').toLowerCase();
    if (type.startsWith('image/') || /\.(jpe?g|png|gif|webp|heic|heif)$/i.test(fileName)) return 'image';
    if (type.startsWith('video/') || /\.(mp4|webm|mov|m4v|avi)$/i.test(fileName)) return 'video';
    return 'file';
}

function renderSavedProofFilePreview(taskId, file) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    const name = file.original_name || 'Bestand';
    const kind = proofFileKind(file.mime_type, name);
    const url = file.url || '';
    const sizeKb = file.size ? Math.round(Number(file.size) / 1024) : null;

    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-col sm:flex-row gap-3 sm:gap-0';
    row.dataset.savedPath = file.path || '';

    let mediaHtml = `
        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    `;
    if (kind === 'image' && url) {
        mediaHtml = `<img src="${url}" alt="Opgeslagen foto" class="w-16 h-16 object-cover rounded-lg">`;
    } else if (kind === 'video' && url) {
        mediaHtml = `<video src="${url}" class="w-16 h-16 object-cover rounded-lg" muted></video>`;
    }

    row.innerHTML = `
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            ${mediaHtml}
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate"></p>
                <p class="text-xs text-gray-500">${sizeKb !== null ? sizeKb + ' KB • ' : ''}${kind === 'image' ? 'Foto' : (kind === 'video' ? 'Video' : 'Bestand')} • Opgeslagen</p>
            </div>
        </div>
    `;
    const titleEl = row.querySelector('.text-sm.font-medium');
    if (titleEl) titleEl.textContent = name;

    previewArea.appendChild(row);
}

function renderProofFilePreviews(taskId) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    previewArea.innerHTML = '';

    const photoInput = document.getElementById('camera-input-photo-' + taskId);
    const videoInput = document.getElementById('camera-input-video-' + taskId);
    if (photoInput) photoInput.name = '';
    if (videoInput) videoInput.name = '';

    const saved = getSavedProofFiles(taskId);
    saved.forEach(file => renderSavedProofFilePreview(taskId, file));
    getStoredProofFiles(taskId).forEach(file => updateMediaPreview(taskId, file));

    if (saved.length > 0) {
        const hint = document.createElement('p');
        hint.className = 'text-xs text-slate-500';
        hint.textContent = 'Eerder opgeslagen bestanden blijven bewaard. Nieuwe foto’s of video’s worden toegevoegd.';
        previewArea.appendChild(hint);
    }
}

function updateMediaPreview(taskId, file) {
    const previewArea = document.getElementById('preview-area-' + taskId);
    if (!previewArea) return;

    const url = URL.createObjectURL(file);
    const kind = proofFileKind(file.type, file.name);
    const fileKey = proofFileKey(file);

    const row = document.createElement('div');
    row.className = 'flex items-center justify-between p-3 bg-gray-50 rounded-lg border flex-col sm:flex-row gap-3 sm:gap-0';
    row.dataset.fileKey = fileKey;

    let mediaHtml = `
        <div class="w-16 h-16 bg-gray-200 rounded-lg flex items-center justify-center">
            <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
            </svg>
        </div>
    `;

    if (kind === 'image') {
        mediaHtml = `<img src="${url}" alt="Preview" class="w-16 h-16 object-cover rounded-lg">`;
    } else if (kind === 'video') {
        mediaHtml = `<video src="${url}" class="w-16 h-16 object-cover rounded-lg" muted></video>`;
    }

    row.innerHTML = `
        <div class="flex items-center space-x-3 w-full sm:w-auto">
            ${mediaHtml}
            <div class="min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate"></p>
                <p class="text-xs text-gray-500">${Math.round(file.size / 1024)} KB • ${kind === 'image' ? 'Foto' : (kind === 'video' ? 'Video' : 'Bestand')}</p>
            </div>
        </div>
        <button type="button" class="text-red-600 hover:text-red-800 self-end sm:self-center" onclick="removePreviewItem(this, '${taskId}')">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    `;
    const titleEl = row.querySelector('.text-sm.font-medium');
    if (titleEl) titleEl.textContent = file.name;

    const hint = previewArea.querySelector('p.text-xs.text-slate-500');
    if (hint) {
        previewArea.insertBefore(row, hint);
    } else {
        previewArea.appendChild(row);
    }
}

function removePreviewItem(btn, taskId) {
    const row = btn.closest('[data-file-key]');
    if (!row) return;

    const fileKey = row.dataset.fileKey;
    setStoredProofFiles(
        taskId,
        getStoredProofFiles(taskId).filter(file => proofFileKey(file) !== fileKey)
    );
    renderProofFilePreviews(taskId);
}

Object.keys(window.savedProofFiles || {}).forEach(function(taskId) {
    if ((window.savedProofFiles[taskId] || []).length) {
        renderProofFilePreviews(taskId);
    }
});

</script>

<script>
// Custom error class
class ValidationError extends Error {
    constructor(message, errors = {}) {
        super(message);
        this.name = 'ValidationError';
        this.errors = errors;
    }
}

document.addEventListener('DOMContentLoaded', function() {
    console.log('checklist-js v4 loaded');
    const completionStorageKey = 'completed_list_{{ $submission->taskList->id }}:' + new Date().toISOString().slice(0, 10);

    if (!window.signaturePads) window.signaturePads = {};

    // Init per-task signature pads (only expanded cards; hidden canvases get a broken size)
    document.querySelectorAll('.task-card.is-expanded canvas[id^="signature-pad-task-"]').forEach(canvas => {
        ensureTaskSignaturePad(canvas);
    });

    initializeTaskAccordions();

    // Final signature pad (indien aanwezig)
    setupFinalSignaturePad();

    // Checklist persistence & forms
    initializeChecklists();
    syncFinalSubmissionForm(false);

    // CSRF meta fallback
    if (!document.querySelector('meta[name="csrf-token"]')) {
        const meta = document.createElement('meta');
        meta.name = 'csrf-token';
        meta.content = '{{ csrf_token() }}';
        document.head.appendChild(meta);
    }

    // Animatie op cards
    const cards = document.querySelectorAll('.task-card');
    cards.forEach((card, index) => {
        card.style.opacity = '0';
        card.style.transform = 'translateY(20px)';
        card.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        setTimeout(() => {
            card.style.opacity = '1';
            card.style.transform = 'translateY(0)';
        }, index * 40 + 80);
    });

    // Progress circle init
    const progressCircle = document.getElementById('submission-progress-circle');
    if (progressCircle) {
        const circumference = 2 * Math.PI * 40;
        const progressPercent = {{ $progressPercent }};
        const offset = circumference - (progressPercent / 100) * circumference;
        progressCircle.style.strokeDasharray = String(circumference);
        progressCircle.style.strokeDashoffset = String(circumference);
        setTimeout(() => {
            progressCircle.style.strokeDashoffset = String(offset);
        }, 500);
    }

    initializeListSwipe();
    initializeListJump();

    // Ripple-effect op buttons
    function createRipple(event) {
        const button = event.currentTarget;
        const ripple = document.createElement('span');
        const rect = button.getBoundingClientRect();
        const size = Math.max(rect.width, rect.height);
        const x = event.clientX - rect.left - size / 2;
        const y = event.clientY - rect.top - size / 2;
        ripple.style.width = ripple.style.height = size + 'px';
        ripple.style.left = x + 'px';
        ripple.style.top = y + 'px';
        ripple.classList.add('ripple');
        button.appendChild(ripple);
        setTimeout(() => ripple.remove(), 600);
    }
    document.querySelectorAll('button[type="submit"], a[href*="dashboard"]').forEach(btn => {
        btn.addEventListener('click', createRipple);
    });

    // Klein touch-effect op mobile
    document.addEventListener('touchstart', function(e) {
        const t = e.target.closest('button, a');
        if (t) t.style.transform = 'scale(0.98)';
    });
    document.addEventListener('touchend', function(e) {
        const t = e.target.closest('button, a');
        if (t) setTimeout(() => { t.style.transform = ''; }, 150);
    });

    // AJAX final form (indien al actief)
    initializeFinalSubmissionAjax();

    console.log('Helpers ready. Cards:', document.querySelectorAll('.task-card').length);
});

// ---- Signature helpers ----
function clearSignaturePad(key) {
    if (window.signaturePads && window.signaturePads[key]) {
        window.signaturePads[key].clear();
    }
}

function setupFinalSignaturePad() {
    const canvasFinal = document.getElementById('signature-pad-final');
    if (!canvasFinal || typeof SignaturePad === 'undefined') return;
    if (window.signaturePadFinal) return;

    window.signaturePadFinal = new SignaturePad(canvasFinal, {
        backgroundColor: 'rgba(255,255,255,0)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 1,
        maxWidth: 3
    });
}

function clearSignaturePadFinal() {
    if (window.signaturePadFinal) {
        window.signaturePadFinal.clear();
    }
}

function buildTaskFormData(form, taskId) {
    syncProofFilesToInput(taskId);

    const storedProofFiles = getStoredProofFiles(taskId);
    const formData = new FormData(form);

    if (storedProofFiles.length === 0) {
        return formData;
    }

    const fresh = new FormData();
    for (const [key, value] of formData.entries()) {
        if (key !== 'proof_files[]') {
            fresh.append(key, value);
        }
    }

    storedProofFiles.forEach(file => {
        fresh.append('proof_files[]', file, file.name);
    });

    return fresh;
}

// ===== Helpers & AJAX for forms =====

function validateTaskForm(form) {
    let isValid = true;
    const requiredProofType = form.dataset.requiredProofType || 'none';
    const taskId = form.id.replace('task-form-', '');
    const mainFileInput = form.querySelector('input[type="file"][name="proof_files[]"]');
    syncProofFilesToInput(taskId);
    const hasProofFiles = getStoredProofFiles(taskId).length > 0
        || getSavedProofFiles(taskId).length > 0
        || !!(mainFileInput && mainFileInput.files && mainFileInput.files.length > 0)
        || form.dataset.hasExistingProof === '1';

    const signatureCanvas = form.querySelector('canvas[id^="signature-pad-task-"]');
    if (signatureCanvas) {
        const key = 'task-' + signatureCanvas.id.replace('signature-pad-task-', '');
        const hidden = form.querySelector('input[name="digital_signature"]');

        if (window.signaturePads && window.signaturePads[key]) {
            if (window.signaturePads[key].isEmpty()) {
                if (form.dataset.hasSignature !== '1') {
                    showNotification('Een digitale handtekening is vereist voor deze taak.', 'error');
                    isValid = false;
                }
            } else if (hidden) {
                hidden.value = window.signaturePads[key].toDataURL();
            }
        }
    }

    const requiredFields = form.querySelectorAll('[required]');

    if (['photo', 'video', 'file'].includes(requiredProofType) && !hasProofFiles) {
        if (mainFileInput) {
            mainFileInput.classList.add('border-red-500');
        }
        if (requiredProofType === 'photo') {
            showNotification('Je hebt geen afbeelding toegevoegd aan de taak.', 'error');
        } else {
            showNotification('Bewijs is vereist voor deze taak.', 'error');
        }
        return false;
    }

    requiredFields.forEach(field => {
        field.classList.remove('border-red-500', 'border-red-300');

        if (field.type === 'file') {
            if (field.name === 'proof_files[]') {
                return;
            }
            if (field.files.length === 0) {
                field.classList.add('border-red-500');
                if (requiredProofType === 'photo') {
                    showNotification('Je hebt geen afbeelding toegevoegd aan de taak.', 'error');
                } else {
                    showNotification('Bewijs is vereist voor deze taak.', 'error');
                }
                isValid = false;
            }
        } else if ((field.type === 'checkbox' || field.type === 'radio') &&
                   !form.querySelector(`input[name="${field.name}"]:checked`)) {
            showNotification('Dit veld is verplicht.', 'error');
            isValid = false;
        } else if (!field.value.trim()) {
            field.classList.add('border-red-500');
            isValid = false;
        }
    });

    if (!isValid) {
        showNotification('Alle verplichte velden moeten ingevuld worden.', 'error');
    }

    return isValid;
}

function showLoadingOverlay() {
    hideLoadingOverlay();
    const overlay = document.createElement('div');
    overlay.className = 'loading-overlay fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4';
    overlay.innerHTML = `
        <div class="bg-white rounded-lg p-6 sm:p-8 flex flex-col items-center space-y-4 max-w-sm w-full">
            <svg class="animate-spin h-10 w-10 sm:h-12 sm:w-12 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <div class="text-center">
                <p class="text-base sm:text-lg font-semibold text-gray-900">Bezig met verwerken...</p>
                <p class="text-xs sm:text-sm text-gray-600 mt-1">Even geduld alsjeblieft</p>
            </div>
        </div>
    `;
    document.body.appendChild(overlay);
}

function hideLoadingOverlay() {
    const overlay = document.querySelector('.loading-overlay');
    if (overlay) overlay.remove();
}

function elementContainsText(element, selector, text) {
    const els = element.querySelectorAll(selector);
    return Array.from(els).some(el => el.textContent.includes(text));
}

function countCompletedRequiredTasks() {
    let completedRequired = 0;
    document.querySelectorAll('.task-card').forEach(card => {
        const isRequired = card.dataset.required === '1';
        const status = card.dataset.status;
        if (isRequired && (status === 'completed' || status === 'approved')) completedRequired++;
    });
    return completedRequired;
}

function countTotalRequiredTasks() {
    let totalRequired = 0;
    document.querySelectorAll('.task-card').forEach(card => {
        if (card.dataset.required === '1') totalRequired++;
    });
    return totalRequired;
}

// ✅ Sync final submission block (single submit button)
let finalSubmissionWasReady = null;

function syncFinalSubmissionForm(showCelebration = false) {
    try {
        const completedRequiredTasks = countCompletedRequiredTasks();
        const totalRequiredTasks = countTotalRequiredTasks();
        // Model B: alleen verplichte taken blokkeren indiening. Zonder verplichte
        // taken is de checklist dus direct indienbaar; optionele taken mogen openblijven.
        const ready = totalRequiredTasks === 0 || completedRequiredTasks >= totalRequiredTasks;

        setFinalSubmissionReady(ready);

        if (showCelebration && ready && finalSubmissionWasReady === false) {
            showNotification('Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen.', 'success', 5000);
        }

        finalSubmissionWasReady = ready;
    } catch (e) {
        console.error('syncFinalSubmissionForm error:', e);
    }
}

function setFinalSubmissionReady(ready) {
    const finalSection = document.getElementById('final-submission-section');
    if (!finalSection) return;

    finalSection.dataset.ready = ready ? '1' : '0';

    const header = finalSection.querySelector('.bg-gradient-to-r');
    if (header) {
        header.className = ready
            ? 'bg-gradient-to-r from-green-50 to-emerald-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6'
            : 'bg-gradient-to-r from-amber-50 to-orange-50 border-b border-gray-100 px-4 sm:px-6 py-4 sm:py-6';

        const icon = header.querySelector('.w-9.h-9, .w-10.h-10');
        if (icon) {
            icon.className = ready
                ? 'w-9 h-9 sm:w-10 sm:h-10 bg-green-600 rounded-xl flex items-center justify-center flex-shrink-0'
                : 'w-9 h-9 sm:w-10 sm:h-10 bg-amber-500 rounded-xl flex items-center justify-center flex-shrink-0';
            icon.innerHTML = ready
                ? '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
                : '<svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>';
        }

        const title = header.querySelector('h3');
        if (title) {
            title.textContent = ready ? 'Klaar om in te dienen!' : 'Checklist Indienen';
        }

        const description = header.querySelector('p');
        if (description) {
            description.textContent = ready
                ? 'Alle verplichte taken zijn voltooid. Je kunt nu de checklist indienen voor review.'
                : 'Voltooi eerst alle verplichte taken om de checklist in te kunnen dienen.';
            description.className = ready
                ? 'mt-1 text-sm sm:text-base text-gray-600'
                : 'mt-1 text-sm sm:text-base text-amber-700 font-medium';
        }
    }

    const sigPad = finalSection.querySelector('#signature-pad-final');
    const sigInput = finalSection.querySelector('#signature-input-final');
    const clearBtn = finalSection.querySelector('button[onclick="clearSignaturePadFinal()"]');
    const notes = finalSection.querySelector('textarea[name="notes"]');
    const submitBtn = finalSection.querySelector('#submit-checklist-btn');
    const submitHint = finalSection.querySelector('#submit-checklist-hint');

    if (sigPad) {
        sigPad.classList.toggle('opacity-50', !ready);
        sigPad.style.pointerEvents = ready ? 'auto' : 'none';
    }
    if (sigInput) {
        sigInput.required = !!ready && !!sigPad;
        if (!ready) {
            sigInput.value = '';
        }
    }
    if (clearBtn) {
        clearBtn.disabled = !ready;
        clearBtn.classList.toggle('opacity-50', !ready);
        clearBtn.classList.toggle('cursor-not-allowed', !ready);
    }
    if (notes) {
        notes.disabled = !ready;
        notes.classList.toggle('opacity-50', !ready);
        notes.classList.toggle('bg-gray-50', !ready);
        notes.placeholder = ready
            ? 'Eventuele aanvullende opmerkingen over deze checklist...'
            : 'Dit veld wordt beschikbaar nadat alle verplichte taken zijn voltooid.';
    }
    if (submitBtn) {
        submitBtn.disabled = !ready;
        submitBtn.className = ready
            ? 'w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 transition-all duration-200 shadow-lg hover:shadow-xl transform hover:scale-105'
            : 'w-full sm:w-auto inline-flex items-center justify-center px-7 sm:px-8 py-3 border border-transparent text-sm font-semibold rounded-xl text-white bg-gray-300 cursor-not-allowed opacity-70';
    }
    if (submitHint) {
        submitHint.classList.toggle('hidden', ready);
    }

    if (ready) {
        setupFinalSignaturePad();
    }
}

function updateFinalSubmissionForm() {
    syncFinalSubmissionForm(true);
}

function initializeFinalSubmissionAjax() {
    const form = document.querySelector('#final-submission-form');
    if (!form || form.dataset.ajaxBound === '1') return;

    form.dataset.ajaxBound = '1';

    form.addEventListener('submit', function(e) {
        e.preventDefault();

        const sigInput = form.querySelector('#signature-input-final');
        const sigCanvas = document.getElementById('signature-pad-final');
        if (sigInput && sigCanvas) {
            if (!window.signaturePadFinal || window.signaturePadFinal.isEmpty()) {
                showNotification('Een digitale handtekening is vereist om de checklist in te dienen.', 'error');
                return;
            }
            sigInput.value = window.signaturePadFinal.toDataURL();
        }

        const requiredFields = form.querySelectorAll('[required]');
        let ok = true;
        requiredFields.forEach(field => {
            field.classList.remove('border-red-500', 'border-red-300');
            if (!field.value.trim()) {
                field.classList.add('border-red-500');
                ok = false;
            }
        });
        if (!ok) {
            showNotification('Alle verplichte velden moeten ingevuld worden.', 'error');
            return;
        }

        const submitButton = form.querySelector('#submit-checklist-btn');
        if (!submitButton || submitButton.disabled) {
            showNotification('Voltooi eerst alle verplichte taken om de checklist in te dienen.', 'warning');
            return;
        }
        const original = submitButton.innerHTML;
        submitButton.disabled = true;
        submitButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Indienen...
        `;
        showLoadingOverlay();

        const formData = new FormData(form);

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
            }
        })
        .then(r => {
            if (r.ok) return r.json();
            if (r.status === 422) return r.json().then(d => { throw new ValidationError(d.message || 'Validation failed', d.errors); });
            if (r.status === 403) throw new Error('Toegang geweigerd. Ververs de pagina en probeer opnieuw.');
            if (r.status === 500) throw new Error('Server fout opgetreden. Probeer het over een moment opnieuw.');
            throw new Error(`Verzoek gefaald met status ${r.status}`);
        })
        .then(data => {
            if (!data.success) throw new Error(data.message || 'Onbekende fout opgetreden');

            try {
                localStorage.setItem('completed_list_{{ $submission->taskList->id }}:' + new Date().toISOString().slice(0, 10), Date.now().toString());
            } catch (e) {
                console.warn('Kon localStorage niet schrijven:', e);
            }
            
            showNotification(data.message || 'Checklist succesvol ingediend!', 'success');
            setTimeout(() => {
                window.location.href = data.redirect_url || '/employee/dashboard';
            }, data.next_list ? 700 : 1200);
        })
        .catch(err => {
            let msg = 'Fout bij het indienen van checklist. Probeer opnieuw.';
            if (err instanceof ValidationError) {
                msg = err.message;
                if (err.errors && Object.keys(err.errors).length > 0) {
                    const first = Object.values(err.errors)[0];
                    if (Array.isArray(first) && first.length > 0) msg = first[0];
                }
            } else if (err.message) msg = err.message;
            showNotification(msg, 'error');
            hideLoadingOverlay();
            setFinalSubmissionReady(true);
            if (submitButton) {
                submitButton.innerHTML = original;
            }
        });
    });
}

function updateTaskToCompleted(taskId, completedAt, proofFiles) {
    const form = document.querySelector(`#task-form-${taskId}`);
    const taskCard = form ? form.closest('.task-card') : null;

    if (Array.isArray(proofFiles)) {
        setSavedProofFiles(taskId, proofFiles);
    }

    if (taskCard) {
        taskCard.dataset.status = 'completed';
        taskCard.classList.add('task-completed');
    }

    if (taskCard) {
        const taskHeader = taskCard.querySelector('.task-header');
        if (taskHeader) {
            const indexEl = taskHeader.querySelector('.task-index');
            if (indexEl) {
                indexEl.classList.add('is-done');
                indexEl.innerHTML = `
                    <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                    </svg>
                `;
            }

            const titleEl = taskHeader.querySelector('.task-title');
            if (titleEl) titleEl.classList.add('is-done');

            const descEl = taskHeader.querySelector('p.task-detail');
            if (descEl) {
                descEl.classList.remove('text-gray-600');
                descEl.classList.add('text-green-700');
            }
        }
    }

    if (form) {
        const savedCount = getSavedProofFiles(taskId).length;
        const hadNewProof = getStoredProofFiles(taskId).length > 0;
        if (form.dataset.hasExistingProof === '1' || hadNewProof || savedCount > 0) {
            form.dataset.hasExistingProof = '1';
        }
        const sigHidden = form.querySelector('input[name="digital_signature"]');
        if (sigHidden && sigHidden.value) {
            form.dataset.hasSignature = '1';
            sigHidden.removeAttribute('required');
        }
        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.textContent = 'Wijzigingen opslaan';

        const formWrap = form.parentElement;
        if (formWrap && !formWrap.querySelector('.task-edit-hint')) {
            const hint = document.createElement('p');
            hint.className = 'task-edit-hint text-sm text-slate-600 mb-4';
            hint.textContent = 'Deze taak is afgerond. Je kunt de gegevens nog aanpassen tot je de lijst indient.';
            formWrap.insertBefore(hint, form);
        }
    }

    if (typeof setStoredProofFiles === 'function') {
        setStoredProofFiles(taskId, []);
    }
    renderProofFilePreviews(taskId);

    if (taskCard) {
        const quickBtn = taskCard.querySelector('.task-quick-complete');
        if (quickBtn) quickBtn.remove();
        setTaskExpanded(taskCard, false);
        taskCard.style.opacity = '0';
        setTimeout(() => {
            taskCard.style.opacity = '1';
            taskCard.style.transition = 'opacity 0.5s ease-in-out';
        }, 100);
    }
}

function updateProgressIndicator() {
    try {
        const cards = document.querySelectorAll('.task-card');
        let completed = 0;
        cards.forEach(card => {
            if (card.dataset.status === 'completed' || card.dataset.status === 'approved') {
                completed++;
            }
        });
        const total = cards.length;
        const percent = total > 0 ? Math.round((completed / total) * 100) : 0;

        const progressCircle = document.getElementById('submission-progress-circle');
        const progressCount = document.getElementById('submission-progress-count');

        if (progressCircle) {
            const circumference = 2 * Math.PI * 40;
            const offset = circumference * (1 - (percent / 100));
            progressCircle.style.strokeDasharray = String(circumference);
            progressCircle.style.strokeDashoffset = String(offset);

            const color = percent >= 100 ? '#22c55e' : (percent > 0 ? '#3b82f6' : '#ef4444');
            progressCircle.setAttribute('stroke', color);
        }
        if (progressCount) {
            progressCount.textContent = `${completed}/${total} taken`;
        }
    } catch (e) {
        console.error('updateProgressIndicator error:', e);
    }
}

function initializeChecklists() {
    const checklistCheckboxes = document.querySelectorAll('.checklist-checkbox');
    const submissionId = '{{ $submission->id }}';

    checklistCheckboxes.forEach((checkbox) => {
        const taskId = checkbox.dataset.taskId;
        const key = `checklist_${submissionId}_${taskId}`;
        const idx = parseInt(checkbox.dataset.itemIndex);

        const saved = localStorage.getItem(key);
        if (saved) {
            const state = JSON.parse(saved);
            if (state[idx]) checkbox.checked = true;
        }
        checkbox.addEventListener('change', function() {
            const all = document.querySelectorAll(`.checklist-checkbox[data-task-id="${taskId}"]`);
            const state = {};
            all.forEach(cb => state[parseInt(cb.dataset.itemIndex)] = cb.checked);
            localStorage.setItem(key, JSON.stringify(state));
        });
    });

    document.querySelectorAll('form[id^="task-form-"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            if (form.dataset.submitting === '1') {
                return;
            }
            if (!validateTaskForm(form)) return;

            const taskId = form.id.replace('task-form-', '');
            const key = `checklist_${submissionId}_${taskId}`;
            const saved = localStorage.getItem(key);
            if (saved) {
                const progressInput = document.getElementById(`checklist-progress-${taskId}`);
                if (progressInput) progressInput.value = saved;
            }

            const submitBtn = form.querySelector('button[type="submit"]');
            const original = submitBtn.innerHTML;
            form.dataset.submitting = '1';
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Bezig...
            `;
            showLoadingOverlay();

            const formData = buildTaskFormData(form, taskId);
            const submitTaskForm = async (allowRetry = true) => {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    credentials: 'same-origin',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });

                if (response.ok) {
                    return response.json();
                }

                if (response.status === 422) {
                    const data = await response.json();
                    throw new ValidationError(data.message || 'Validation failed', data.errors);
                }

                if (response.status === 419) {
                    // Refresh token once and retry transparently.
                    const refreshResponse = await fetch('/refresh-csrf', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: { 'Accept': 'application/json' }
                    });
                    if (refreshResponse.ok) {
                        const refreshData = await refreshResponse.json();
                        if (refreshData && refreshData.token) {
                            const meta = document.querySelector('meta[name="csrf-token"]');
                            if (meta) meta.setAttribute('content', refreshData.token);
                        }
                    }

                    if (allowRetry) {
                        return submitTaskForm(false);
                    }
                }

                // Temporary/transient errors: retry once.
                if (allowRetry && (response.status >= 500 || response.status === 0)) {
                    await new Promise(resolve => setTimeout(resolve, 350));
                    return submitTaskForm(false);
                }

                if (response.status === 403) throw new Error('Toegang geweigerd. Ververs de pagina en probeer opnieuw.');
                if (response.status >= 500) throw new Error('Serverfout opgetreden. Probeer het opnieuw.');
                throw new Error(`Verzoek mislukt met status ${response.status}`);
            };

            submitTaskForm()
            .then(data => {
                if (!data.success) throw new Error(data.message || 'Onbekende fout opgetreden');

                updateTaskToCompleted(taskId, data.completed_at, data.proof_files);
                localStorage.removeItem(key);
                updateProgressIndicator();
                showNotification('Opgeslagen.', 'success');
                updateFinalSubmissionForm();
                hideLoadingOverlay();

                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Wijzigingen opslaan';
                }
                form.dataset.submitting = '0';
            })
            .catch(err => {
                let msg = 'Fout bij het afronden van taak. Probeer opnieuw.';
                if (err instanceof ValidationError) {
                    msg = err.message;
                    if (err.errors && Object.keys(err.errors).length > 0) {
                        const first = Object.values(err.errors)[0];
                        if (Array.isArray(first) && first.length > 0) msg = first[0];
                    }
                } else if (err.message) msg = err.message;
                showNotification(msg, 'error');
                hideLoadingOverlay();
                submitBtn.disabled = false;
                submitBtn.innerHTML = original;
                form.dataset.submitting = '0';
            });
        });
    });
}

function showNotification(message, type = 'success', duration = 3000) {
    const notification = document.createElement('div');
    const typeClasses = {
        success: 'bg-green-500 text-white border-green-600',
        error: 'bg-red-500 text-white border-red-600',
        warning: 'bg-amber-500 text-white border-amber-600',
        info: 'bg-blue-500 text-white border-blue-600'
    };
    const icons = {
        success: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>',
        error: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>',
        warning: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>',
        info: '<svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>'
    };

    notification.className = `fixed top-4 right-4 z-50 p-4 rounded-lg shadow-lg border-2 ${typeClasses[type] || typeClasses.info} transform translate-x-full transition-transform duration-300 max-w-md w-[calc(100%-2rem)] sm:w-auto`;
    notification.innerHTML = `
        <div class="flex items-center">
            ${icons[type] || icons.info}
            <span class="flex-1 text-sm sm:text-base">${message}</span>
            <button onclick="this.parentElement.parentElement.remove()" class="ml-2 text-white hover:text-gray-200 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
    `;
    document.body.appendChild(notification);
    setTimeout(() => { notification.style.transform = 'translateX(0)'; }, 50);
    setTimeout(() => {
        notification.style.transform = 'translateX(100%)';
        setTimeout(() => notification.remove(), 320);
    }, duration);
}

function ensureTaskSignaturePad(canvas) {
    if (!canvas || typeof SignaturePad === 'undefined') return;
    if (!window.signaturePads) window.signaturePads = {};
    const key = 'task-' + canvas.id.replace('signature-pad-task-', '');
    if (window.signaturePads[key]) return;
    window.signaturePads[key] = new SignaturePad(canvas, {
        backgroundColor: 'rgba(255,255,255,0)',
        penColor: 'rgb(0, 0, 0)',
        minWidth: 1,
        maxWidth: 3
    });
}

function setTaskExpanded(card, expanded) {
    if (!card) return;
    const body = card.querySelector('.task-body');
    const toggle = card.querySelector('.task-toggle');
    const toggleIcon = card.querySelector('.task-toggle-icon');

    card.dataset.expanded = expanded ? '1' : '0';
    card.classList.toggle('is-expanded', expanded);

    if (body) body.classList.toggle('hidden', !expanded);

    card.querySelectorAll('.task-detail').forEach(el => {
        el.classList.toggle('hidden', !expanded);
    });

    if (toggle) {
        toggle.setAttribute('aria-expanded', expanded ? 'true' : 'false');
        toggle.title = expanded ? 'Taak inklappen' : 'Taak uitklappen';
    }
    if (toggleIcon) {
        toggleIcon.classList.toggle('rotate-180', expanded);
    }

    if (expanded) {
        requestAnimationFrame(() => {
            card.querySelectorAll('canvas[id^="signature-pad-task-"]').forEach(ensureTaskSignaturePad);
        });
    }
}

function toggleTaskCard(card) {
    if (!card) return;
    setTaskExpanded(card, card.dataset.expanded !== '1');
}

function initializeTaskAccordions() {
    document.querySelectorAll('.task-header').forEach(header => {
        header.addEventListener('click', function(e) {
            if (e.target.closest('button, a, input, textarea, label, canvas')) return;
            toggleTaskCard(header.closest('.task-card'));
        });
    });
}

function quickCompleteTask(taskId) {
    const form = document.getElementById('task-form-' + taskId);
    if (!form) return;
    if (typeof form.requestSubmit === 'function') {
        form.requestSubmit();
        return;
    }
    form.dispatchEvent(new Event('submit', { cancelable: true, bubbles: true }));
}

function goToNeighborList(url) {
    if (!url) return;
    window.location.href = url;
}

function initializeListJump() {
    const input = document.getElementById('list-position-input');
    if (!input || input.disabled) return;

    const current = parseInt(input.dataset.current, 10);
    const total = parseInt(input.dataset.total, 10);
    let urls = [];
    try {
        urls = JSON.parse(input.dataset.urls || '[]');
    } catch (e) {
        urls = [];
    }

    const reset = () => {
        input.value = String(current);
    };

    const go = () => {
        const value = parseInt(String(input.value).replace(/[^\d]/g, ''), 10);
        if (!Number.isInteger(value) || value < 1 || value > total || !urls[value - 1]) {
            reset();
            return;
        }
        if (value === current) {
            reset();
            return;
        }
        goToNeighborList(urls[value - 1]);
    };

    input.addEventListener('focus', function() {
        input.select();
    });
    input.addEventListener('keydown', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            go();
        }
        if (e.key === 'Escape') {
            reset();
            input.blur();
        }
    });
    input.addEventListener('change', go);
}

function initializeListSwipe() {
    const prevBtn = document.getElementById('hero-nav-prev');
    const nextBtn = document.getElementById('hero-nav-next');
    const prevUrl = prevBtn && !prevBtn.disabled ? prevBtn.dataset.url : '';
    const nextUrl = nextBtn && !nextBtn.disabled ? nextBtn.dataset.url : '';

    if (prevBtn) {
        prevBtn.addEventListener('click', function() {
            goToNeighborList(prevUrl);
        });
    }
    if (nextBtn) {
        nextBtn.addEventListener('click', function() {
            goToNeighborList(nextUrl);
        });
    }

    if (!prevUrl && !nextUrl) return;

    let startX = 0;
    let startY = 0;
    let tracking = false;

    document.addEventListener('touchstart', function(e) {
        if (e.touches.length !== 1) return;
        if (e.target.closest('input, textarea, select, canvas, button, a, label')) return;
        tracking = true;
        startX = e.touches[0].clientX;
        startY = e.touches[0].clientY;
    }, { passive: true });

    document.addEventListener('touchend', function(e) {
        if (!tracking) return;
        tracking = false;
        const touch = e.changedTouches[0];
        const dx = touch.clientX - startX;
        const dy = touch.clientY - startY;
        if (Math.abs(dx) < 70 || Math.abs(dx) < Math.abs(dy) * 1.2) return;
        if (dx > 0) {
            goToNeighborList(prevUrl);
            return;
        }
        goToNeighborList(nextUrl);
    }, { passive: true });
}
</script>
