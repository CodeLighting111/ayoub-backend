<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class OrderUpdateItemsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantities' => ['required', 'array'],
            'quantities.*' => ['required', 'integer', 'min:1'],
        ];
    }

    public function messages(): array
    {
        return [
            'quantities.required' => 'يجب تحديد كميات المنتجات.',
            'quantities.*.required' => 'كمية المنتج مطلوبة.',
            'quantities.*.integer' => 'كمية المنتج يجب أن تكون رقماً صحيحاً.',
            'quantities.*.min' => 'كمية المنتج يجب أن تكون 1 على الأقل.',
        ];
    }
}
