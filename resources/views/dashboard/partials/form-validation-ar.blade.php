<script>
    (function () {
        function getArabicValidationMessage(field) {
            const validity = field.validity;

            if (validity.valueMissing) {
                if (field.tagName === 'SELECT') {
                    return 'يرجى اختيار عنصر من القائمة.';
                }

                if (field.type === 'checkbox') {
                    return 'يرجى تحديد هذا الخيار.';
                }

                if (field.type === 'radio') {
                    return 'يرجى اختيار أحد الخيارات.';
                }

                if (field.type === 'file') {
                    return 'يرجى اختيار ملف.';
                }

                return 'يرجى ملء هذا الحقل.';
            }

            if (validity.typeMismatch) {
                if (field.type === 'email') {
                    return 'يرجى إدخال بريد إلكتروني صالح.';
                }

                if (field.type === 'url') {
                    return 'يرجى إدخال رابط صالح.';
                }

                return 'القيمة المدخلة غير صالحة.';
            }

            if (validity.patternMismatch) {
                return 'القيمة المدخلة لا تطابق الصيغة المطلوبة.';
            }

            if (validity.tooShort) {
                return `يجب أن يحتوي الحقل على ${field.minLength} أحرف على الأقل.`;
            }

            if (validity.tooLong) {
                return `يجب ألا يتجاوز الحقل ${field.maxLength} حرفاً.`;
            }

            if (validity.rangeUnderflow) {
                return `يجب أن تكون القيمة ${field.min} أو أكثر.`;
            }

            if (validity.rangeOverflow) {
                return `يجب أن تكون القيمة ${field.max} أو أقل.`;
            }

            if (validity.stepMismatch) {
                return 'القيمة المدخلة غير صالحة.';
            }

            if (validity.badInput) {
                return 'القيمة المدخلة غير صالحة.';
            }

            return 'يرجى التحقق من هذا الحقل.';
        }

        function resetCustomValidity(field) {
            if (field instanceof HTMLInputElement || field instanceof HTMLSelectElement || field instanceof HTMLTextAreaElement) {
                field.setCustomValidity('');
            }
        }

        document.addEventListener('invalid', function (event) {
            const field = event.target;

            if (!(field instanceof HTMLInputElement || field instanceof HTMLSelectElement || field instanceof HTMLTextAreaElement)) {
                return;
            }

            field.setCustomValidity(getArabicValidationMessage(field));
        }, true);

        document.addEventListener('input', function (event) {
            resetCustomValidity(event.target);
        }, true);

        document.addEventListener('change', function (event) {
            resetCustomValidity(event.target);
        }, true);
    })();
</script>
