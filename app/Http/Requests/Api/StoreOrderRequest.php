<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'client_id' => ['required', 'exists:clients,id'],
            'payment_method' => ['required', Rule::in(['cash', 'wallet', 'bank_transfer'])],
            'notes' => ['nullable', 'string'],
            'preferred_delivery_at' => ['nullable', 'date'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'client_id.required' => 'معرف العميل مطلوب.',
            'client_id.exists' => 'العميل غير موجود.',
            'payment_method.required' => 'طريقة الدفع مطلوبة.',
            'payment_method.in' => 'طريقة الدفع غير صالحة.',
            'items.required' => 'يجب إضافة منتج واحد على الأقل.',
            'items.min' => 'يجب إضافة منتج واحد على الأقل.',
            'items.*.product_id.required' => 'معرف المنتج مطلوب.',
            'items.*.product_id.exists' => 'أحد المنتجات غير موجود.',
            'items.*.quantity.required' => 'كمية المنتج مطلوبة.',
            'items.*.quantity.min' => 'كمية المنتج يجب أن تكون 1 على الأقل.',
            'preferred_delivery_at.date' => 'ميعاد التوصيل المناسب غير صالح.',
        ];
    }
}
