<script>
    (function () {
        document.querySelectorAll('input[type="search"][name="q"]').forEach(function (input) {
            const form = input.closest('form');
            if (!form) {
                return;
            }

            const showAllData = function () {
                if (input.value.trim() !== '') {
                    return;
                }

                const params = new URLSearchParams(window.location.search);
                const currentQuery = (params.get('q') ?? '').trim();

                if (currentQuery === '') {
                    return;
                }

                const url = new URL(form.action, window.location.origin);
                window.location.href = url.pathname;
            };

            input.addEventListener('input', showAllData);
            input.addEventListener('search', showAllData);
        });
    })();
</script>
