<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class OnboardingScreenRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $isUpdate = $this->route('onboarding_screen') !== null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:1000'],
            'sort_order' => ['required', 'integer', 'min:1', 'max:20'],
            'status' => ['required', Rule::in(['active', 'draft'])],
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:10240',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'العنوان الرئيسي مطلوب.',
            'description.required' => 'الوصف الإيضاحي مطلوب.',
            'sort_order.required' => 'ترتيب الشاشة مطلوب.',
            'status.required' => 'حالة الشاشة مطلوبة.',
            'image.required' => 'صورة الشاشة مطلوبة.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
