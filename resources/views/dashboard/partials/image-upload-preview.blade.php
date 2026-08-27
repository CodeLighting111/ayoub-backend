<script>
    (function () {
        document.querySelectorAll('input[type="file"][accept*="image"]').forEach(function (input) {
            const uploadLabel = document.querySelector('label[for="' + input.id + '"]') || input.closest('label');

            if (! uploadLabel) {
                return;
            }

            let prompt = uploadLabel.querySelector('[data-image-upload-prompt]');

            if (! prompt) {
                prompt = uploadLabel.querySelector('.text-center');

                if (prompt) {
                    prompt.setAttribute('data-image-upload-prompt', '');
                }
            }

            let preview = uploadLabel.querySelector('[data-image-upload-preview]');

            if (! preview) {
                preview = document.createElement('div');
                preview.setAttribute('data-image-upload-preview', '');
                preview.className = 'hidden w-full px-2 py-2 text-center';
                preview.innerHTML =
                    '<img alt="معاينة الصورة المختارة" class="mx-auto max-h-44 w-auto max-w-full rounded-lg border border-outline-variant object-contain" data-image-upload-preview-img>' +
                    '<p class="mt-3 truncate text-sm font-semibold text-primary-container" data-image-upload-preview-name></p>' +
                    '<p class="mt-1 text-xs text-on-surface-variant">اضغط لتغيير الصورة</p>';
                uploadLabel.appendChild(preview);
            }

            const previewImage = preview.querySelector('[data-image-upload-preview-img]');
            const previewName = preview.querySelector('[data-image-upload-preview-name]');

            const hidePreview = function () {
                preview.classList.add('hidden');

                if (prompt) {
                    prompt.classList.remove('hidden');
                }

                if (input._previewObjectUrl) {
                    URL.revokeObjectURL(input._previewObjectUrl);
                    delete input._previewObjectUrl;
                }

                previewImage.removeAttribute('src');
                previewName.textContent = '';
            };

            const showPreview = function (file) {
                if (input._previewObjectUrl) {
                    URL.revokeObjectURL(input._previewObjectUrl);
                }

                input._previewObjectUrl = URL.createObjectURL(file);
                previewImage.src = input._previewObjectUrl;
                previewName.textContent = file.name;

                if (prompt) {
                    prompt.classList.add('hidden');
                }

                preview.classList.remove('hidden');
            };

            input.addEventListener('change', function () {
                const file = input.files && input.files[0];

                if (! file) {
                    hidePreview();

                    return;
                }

                showPreview(file);
            });
        });
    })();
</script>
