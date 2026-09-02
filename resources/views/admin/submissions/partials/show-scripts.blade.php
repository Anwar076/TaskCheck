<script>
function openImageModal(imageSrc) {
    document.getElementById('modalImage').src = imageSrc;
    const modal = document.getElementById('imageModal');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeImageModal() {
    const modal = document.getElementById('imageModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// Close modal with Escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});

// Notification function
function showNotification(message, type = 'success') {
    // Remove existing notification if any
    const existingNotification = document.querySelector('.notification-toast');
    if (existingNotification) {
        existingNotification.remove();
    }
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `notification-toast fixed top-4 left-4 right-4 sm:left-auto sm:right-5 sm:max-w-sm z-50 p-4 rounded-lg shadow-lg transform transition-all duration-300 ease-out ${
        type === 'success' 
            ? 'bg-green-500 text-white' 
            : 'bg-red-500 text-white'
    }`;
    
    notification.innerHTML = `
        <div class="flex items-center">
            <div class="flex-shrink-0">
                ${type === 'success' 
                    ? '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>'
                    : '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>'
                }
            </div>
            <div class="ml-3">
                <p class="text-sm font-medium">${message}</p>
            </div>
            <div class="ml-auto pl-3">
                <div class="flex">
                    <button type="button" onclick="this.closest('.notification-toast').remove()" class="rounded-md p-1.5 hover:bg-black hover:bg-opacity-10 transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Animate in
    setTimeout(() => {
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';
    }, 10);
    
    // Auto remove after 5 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 5000);
}

// 🎯 Helpers om UI te updaten zonder refresh
function updateTaskUIAfterApprove(taskId, managerComment) {
    const container = document.querySelector(`.submission-task[data-submission-task-id="${taskId}"]`);
    if (!container) return;

    const badge = container.querySelector('.task-status-badge');
    if (badge) {
        badge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold border task-status-badge bg-emerald-100 text-emerald-800 border-emerald-200';
        badge.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>Goedgekeurd`;
    }

    const box = container.querySelector('.review-action-box');
    if (box) {
        box.className = 'bg-emerald-50 rounded-xl p-5 sm:p-6 border border-emerald-100 review-action-box';
        box.innerHTML = `
            <div class="text-center">
                <div class="w-14 h-14 bg-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h6 class="text-base font-bold text-emerald-900 mb-2">Taak goedgekeurd</h6>
                <p class="text-sm text-emerald-700">Deze taak is beoordeeld en goedgekeurd.</p>
            </div>
        `;
    }

    if (managerComment && managerComment.trim() !== '') {
        let reviewBlock = container.querySelector('.manager-review-block');
        if (!reviewBlock) {
            reviewBlock = document.createElement('div');
            reviewBlock.className = 'mt-6 manager-review-block';
            const proofSection = container.querySelector('.bg-violet-50')?.closest('.mt-6');
            if (proofSection && proofSection.parentElement) {
                proofSection.parentElement.insertAdjacentElement('afterend', reviewBlock);
            } else {
                container.querySelector('.flex-1')?.appendChild(reviewBlock);
            }
        }
        reviewBlock.innerHTML = `
            <div class="bg-slate-50 rounded-xl p-5 sm:p-6 border border-slate-200">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-9 h-9 bg-slate-500 rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                    </div>
                    <h5 class="text-base font-bold text-slate-900">Beoordeling beheerder</h5>
                </div>
                <div class="bg-white/80 rounded-xl p-4 manager-comment-block">
                    <p class="font-semibold text-slate-900 mb-2">Opmerking beheerder</p>
                    <p class="text-slate-700 leading-relaxed manager-comment-text"></p>
                </div>
            </div>
        `;
        reviewBlock.querySelector('.manager-comment-text').textContent = managerComment;
    }
}

function updateTaskUIAfterReject(taskId, rejectionReason) {
    const container = document.querySelector(`.submission-task[data-submission-task-id="${taskId}"]`);
    if (!container) return;

    const badge = container.querySelector('.task-status-badge');
    if (badge) {
        badge.className = 'inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-sm font-semibold border task-status-badge bg-red-100 text-red-800 border-red-200';
        badge.innerHTML = `<svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>Afgewezen`;
    }

    const box = container.querySelector('.review-action-box');
    if (box) {
        box.className = 'bg-red-50 rounded-xl p-5 sm:p-6 border border-red-100 review-action-box';
        box.innerHTML = `
            <div class="text-center">
                <div class="w-14 h-14 bg-red-500 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </div>
                <h6 class="text-base font-bold text-red-900 mb-2">Taak afgekeurd</h6>
                <p class="text-sm text-red-700">Medewerker moet deze taak opnieuw uitvoeren en de checklist opnieuw indienen.</p>
            </div>
        `;
    }

    let reviewBlock = container.querySelector('.manager-review-block');
    if (!reviewBlock) {
        reviewBlock = document.createElement('div');
        reviewBlock.className = 'mt-6 manager-review-block';
        const proofSection = container.querySelector('.bg-violet-50')?.closest('.mt-6');
        if (proofSection && proofSection.parentElement) {
            proofSection.parentElement.insertAdjacentElement('afterend', reviewBlock);
        } else {
            container.querySelector('.flex-1')?.appendChild(reviewBlock);
        }
    }
    reviewBlock.innerHTML = `
        <div class="bg-slate-50 rounded-xl p-5 sm:p-6 border border-slate-200">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-9 h-9 bg-slate-500 rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z"/></svg>
                </div>
                <h5 class="text-base font-bold text-slate-900">Beoordeling beheerder</h5>
            </div>
            <div class="bg-red-50 border border-red-200 rounded-xl p-4 rejection-reason-block">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 11-18 0 9 9 0 0118 0zm-9 3.75h.008v.008H12v-.008z"/></svg>
                    <div>
                        <p class="font-semibold text-red-900 mb-1">Afwijzingsreden</p>
                        <p class="text-red-800 leading-relaxed rejection-reason-text"></p>
                    </div>
                </div>
            </div>
        </div>
    `;
    reviewBlock.querySelector('.rejection-reason-text').textContent = rejectionReason;
}

async function parseJsonResponseOrThrow(response, fallbackMessage) {
    const contentType = response.headers.get('content-type') || '';

    if (!response.ok || !contentType.includes('application/json')) {
        throw new Error(fallbackMessage);
    }

    const payload = await response.json();
    if (!payload || payload.success !== true) {
        throw new Error(payload?.message || fallbackMessage);
    }

    return payload;
}

// Auto handling of forms (AJAX + direct UI update)
document.addEventListener('DOMContentLoaded', function() {
    // Approve
    document.querySelectorAll('[id^="approve-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('approve-form-', '');
            const submitBtn = document.getElementById('approve-btn-' + taskId);
            const managerComment = form.querySelector('textarea[name="manager_comment"]')?.value || '';

            submitBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Verwerken...`;
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => parseJsonResponseOrThrow(response, 'Goedkeuren mislukt. Controleer je sessie en probeer opnieuw.'))
            .then(() => {
                showNotification('Taak succesvol goedgekeurd!', 'success');
                updateTaskUIAfterApprove(taskId, managerComment);
            })
            .catch(error => {
                showNotification(error.message || 'Fout bij goedkeuren. Probeer het opnieuw.', 'error');
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Taak goedkeuren
                `;
                submitBtn.disabled = false;
            });
        });
    });
    
    // Reject
    document.querySelectorAll('[id^="reject-form-"]').forEach(function(form) {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(form);
            const taskId = form.id.replace('reject-form-', '');
            const submitBtn = document.getElementById('reject-btn-' + taskId);
            const rejectionReason = form.querySelector('textarea[name="rejection_reason"]').value.trim();
            
            if (!rejectionReason) {
                showNotification('Afwijzingsreden is verplicht', 'error');
                return;
            }
            
            submitBtn.innerHTML = `<svg class="w-5 h-5 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99"/></svg> Verwerken...`;
            submitBtn.disabled = true;
            
            fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || formData.get('_token'),
                    'Accept': 'application/json'
                }
            })
            .then(response => parseJsonResponseOrThrow(response, 'Afwijzen mislukt. Controleer je sessie en probeer opnieuw.'))
            .then((payload) => {
                showNotification(`Taak afgewezen. Notificatie #${payload.notification_id ?? '-'} voor user ${payload.notification_user_id ?? '-'}.`, 'success');
                updateTaskUIAfterReject(taskId, rejectionReason);
            })
            .catch(error => {
                showNotification(error.message || 'Afwijzen mislukt. Probeer het opnieuw.', 'error');
                submitBtn.innerHTML = `
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    Reject Task
                `;
                submitBtn.disabled = false;
            });
        });
    });
});
</script>
