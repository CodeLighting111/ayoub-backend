<?php

namespace App\Http\Requests\Admin;

use App\Models\Complaint;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComplaintStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Complaint::STATUSES)],
            'admin_response' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'حالة الشكوى مطلوبة.',
            'status.in' => 'حالة الشكوى غير صالحة.',
        ];
    }
}
