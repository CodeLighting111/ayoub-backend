<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class GeneralSettingRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'app_title' => ['required', 'string', 'max:255'],
            'app_description' => ['nullable', 'string'],
            'logo' => ['nullable', 'image', 'max:10240'],
            'delivery_fee' => ['required', 'numeric', 'min:0'],
            'min_order_amount' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'app_title.required' => 'اسم المنصة مطلوب.',
            'delivery_fee.required' => 'رسوم التوصيل مطلوبة.',
            'delivery_fee.min' => 'رسوم التوصيل يجب أن تكون 0 أو أكثر.',
            'min_order_amount.required' => 'الحد الأدنى لقيمة الطلب مطلوب.',
            'min_order_amount.min' => 'الحد الأدنى لقيمة الطلب يجب أن يكون 0 أو أكثر.',
            'logo.image' => 'يجب أن يكون الشعار من نوع صورة.',
            'logo.max' => 'حجم الشعار يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
