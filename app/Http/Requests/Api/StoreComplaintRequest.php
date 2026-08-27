<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class StoreComplaintRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'subject' => ['required', 'string', 'max:255'],
            'message' => ['required', 'string'],
            'order_id' => ['nullable', 'exists:orders,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'معرف العميل مطلوب.',
            'client_id.exists' => 'العميل غير موجود.',
            'subject.required' => 'موضوع الشكوى مطلوب.',
            'message.required' => 'نص الشكوى مطلوب.',
            'order_id.exists' => 'الطلب غير موجود.',
        ];
    }
}
