<style>
    #taskcheck-page-skeleton {
        display: none;
        opacity: 0;
        pointer-events: none;
        transition: opacity 90ms ease;
        will-change: opacity;
    }

    body.taskcheck-page-loading #taskcheck-page-skeleton {
        display: block;
        opacity: 1;
        pointer-events: auto;
    }

    .taskcheck-skeleton-shimmer {
        background: linear-gradient(90deg, #e5e7eb 0%, #f8fafc 50%, #e5e7eb 100%);
        background-size: 220% 100%;
        animation: taskcheck-skeleton-shimmer 1.8s ease-in-out infinite;
    }

    @keyframes taskcheck-skeleton-shimmer {
        0% {
            background-position: 100% 0;
        }
        100% {
            background-position: 0 0;
        }
    }
</style>

<div id="taskcheck-page-skeleton" class="fixed inset-0 z-[9999] bg-slate-50" aria-hidden="true">
    <div class="h-full overflow-hidden">
        <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
            <div class="taskcheck-skeleton-shimmer mb-6 h-28 rounded-2xl"></div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
                <div class="taskcheck-skeleton-shimmer h-24 rounded-xl"></div>
                <div class="taskcheck-skeleton-shimmer h-24 rounded-xl"></div>
                <div class="taskcheck-skeleton-shimmer h-24 rounded-xl"></div>
                <div class="taskcheck-skeleton-shimmer h-24 rounded-xl"></div>
            </div>
            <div class="mt-6 rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                <div class="taskcheck-skeleton-shimmer h-5 w-48 rounded"></div>
                <div class="mt-5 space-y-3">
                    <div class="taskcheck-skeleton-shimmer h-12 rounded-xl"></div>
                    <div class="taskcheck-skeleton-shimmer h-12 rounded-xl"></div>
                    <div class="taskcheck-skeleton-shimmer h-12 rounded-xl"></div>
                    <div class="taskcheck-skeleton-shimmer h-12 rounded-xl"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    (() => {
        const loadingClass = 'taskcheck-page-loading';
        let loadingTimer = null;

        function showPageSkeleton() {
            window.clearTimeout(loadingTimer);
            loadingTimer = window.setTimeout(() => {
                document.body.classList.add(loadingClass);
            }, 260);
        }

        function hidePageSkeleton() {
            window.clearTimeout(loadingTimer);
            document.body.classList.remove(loadingClass);
        }

        function isModifiedClick(event) {
            return event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0;
        }

        function shouldHandleLink(link) {
            if (!link || link.dataset.noPageTransition !== undefined) return false;
            if (link.target && link.target !== '_self') return false;
            if (link.hasAttribute('download')) return false;
            if (!link.href || link.href.startsWith('javascript:') || link.href.startsWith('mailto:') || link.href.startsWith('tel:')) return false;

            const targetUrl = new URL(link.href, window.location.href);
            if (targetUrl.origin !== window.location.origin) return false;
            if (targetUrl.pathname === window.location.pathname && targetUrl.search === window.location.search && targetUrl.hash) return false;

            return true;
        }

        document.addEventListener('click', (event) => {
            if (event.defaultPrevented || isModifiedClick(event)) return;

            const link = event.target.closest('a[href]');
            if (shouldHandleLink(link)) {
                showPageSkeleton();
            }
        });

        document.addEventListener('submit', (event) => {
            if (event.defaultPrevented) return;

            const form = event.target;
            if (!(form instanceof HTMLFormElement)) return;
            if (form.dataset.noPageTransition !== undefined) return;
            if (form.target && form.target !== '_self') return;

            showPageSkeleton();
        });

        window.addEventListener('pageshow', hidePageSkeleton);
        window.addEventListener('pagehide', hidePageSkeleton);
    })();
</script>
