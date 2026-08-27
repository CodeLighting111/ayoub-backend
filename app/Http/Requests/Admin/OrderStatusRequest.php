<?php

namespace App\Http\Requests\Admin;

use App\Models\Order;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => ['required', Rule::in(Order::STATUSES)],
            'expected_delivery_at' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'حالة الطلب مطلوبة.',
            'status.in' => 'حالة الطلب غير صالحة.',
            'expected_delivery_at.date' => 'تاريخ التوصيل المتوقع غير صالح.',
        ];
    }
}
