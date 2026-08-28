<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SendClientNotificationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'target' => ['required', Rule::in(['all', 'client'])],
            'client_id' => [
                Rule::requiredIf($this->input('target') === 'client'),
                'nullable',
                'exists:clients,id',
            ],
            'title' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string', 'max:2000'],
            'return_to' => ['nullable', 'string', 'in:clients,notifications'],
        ];
    }

    public function messages(): array
    {
        return [
            'target.required' => 'يجب تحديد نوع الإرسال.',
            'target.in' => 'نوع الإرسال غير صالح.',
            'client_id.required' => 'يجب اختيار العميل.',
            'client_id.exists' => 'العميل المحدد غير موجود.',
            'title.required' => 'عنوان الإشعار مطلوب.',
            'message.required' => 'نص الإشعار مطلوب.',
            'message.max' => 'نص الإشعار طويل جداً.',
        ];
    }
}
