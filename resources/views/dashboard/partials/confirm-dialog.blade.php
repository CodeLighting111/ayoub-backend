<div id="dashboard-confirm" class="fixed inset-0 z-[80] hidden items-center justify-center p-4" role="dialog" aria-modal="true" aria-labelledby="dashboard-confirm-title">
    <div class="absolute inset-0 bg-[#1a1c1c]/45 backdrop-blur-[2px]" data-confirm-dismiss></div>

    <div class="relative w-full max-w-md rounded-xl border border-outline-variant bg-surface-container-lowest p-6 text-center shadow-[0_16px_40px_rgba(27,94,32,0.16)]">
        <div class="mx-auto mb-4 flex h-14 w-14 items-center justify-center rounded-full bg-error-container">
            <span class="material-symbols-outlined text-[28px] text-error">delete</span>
        </div>
        <h2 class="mb-2 text-lg font-bold text-on-surface" id="dashboard-confirm-title">تأكيد الحذف</h2>
        <p class="mb-6 text-sm leading-6 text-on-surface-variant" id="dashboard-confirm-message">هل أنت متأكد من تنفيذ هذا الإجراء؟</p>
        <div class="flex items-center justify-center gap-3">
            <button class="rounded-lg border border-outline-variant px-6 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" data-confirm-dismiss type="button">
                إلغاء
            </button>
            <button class="rounded-lg bg-error px-6 py-2.5 text-sm font-semibold text-on-error shadow-sm transition-colors hover:bg-[#93000a]" id="dashboard-confirm-accept" type="button">
                حذف
            </button>
        </div>
    </div>
</div>

<script>
    window.DashboardConfirm = (function () {
        const dialog = document.getElementById('dashboard-confirm');
        const titleEl = document.getElementById('dashboard-confirm-title');
        const messageEl = document.getElementById('dashboard-confirm-message');
        const acceptEl = document.getElementById('dashboard-confirm-accept');
        let pendingForm = null;

        function open(options) {
            const settings = options || {};
            titleEl.textContent = settings.title || 'تأكيد الحذف';
            messageEl.textContent = settings.message || 'هل أنت متأكد من تنفيذ هذا الإجراء؟';
            acceptEl.textContent = settings.confirmText || 'حذف';
            pendingForm = settings.form || null;
            dialog.classList.remove('hidden');
            dialog.classList.add('flex');
            document.body.classList.add('overflow-hidden');
        }

        function close() {
            dialog.classList.add('hidden');
            dialog.classList.remove('flex');
            document.body.classList.remove('overflow-hidden');
            pendingForm = null;
        }

        dialog.querySelectorAll('[data-confirm-dismiss]').forEach(function (el) {
            el.addEventListener('click', close);
        });

        document.addEventListener('keydown', function (event) {
            if (event.key === 'Escape' && !dialog.classList.contains('hidden')) {
                close();
            }
        });

        acceptEl.addEventListener('click', function () {
            if (pendingForm) {
                pendingForm.dataset.confirmAccepted = '1';
                pendingForm.submit();
            }
            close();
        });

        document.addEventListener('submit', function (event) {
            const form = event.target;
            if (!(form instanceof HTMLFormElement) || !form.hasAttribute('data-confirm')) {
                return;
            }
            if (form.dataset.confirmAccepted === '1') {
                return;
            }
            event.preventDefault();
            open({
                form: form,
                title: form.getAttribute('data-confirm-title') || 'تأكيد الحذف',
                message: form.getAttribute('data-confirm') || 'هل أنت متأكد من الحذف؟',
                confirmText: form.getAttribute('data-confirm-action') || 'حذف',
            });
        });

        return { open: open, close: close };
    })();
</script>
