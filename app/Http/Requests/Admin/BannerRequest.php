<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $bannerId = $this->route('banner')?->id;
        $isUpdate = $bannerId !== null;

        return [
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'sort_order' => ['nullable', 'integer', 'min:1'],
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
            'title.required' => 'عنوان البانر مطلوب.',
            'title.max' => 'العنوان يجب ألا يتجاوز 255 حرفاً.',
            'description.max' => 'الوصف يجب ألا يتجاوز 2000 حرف.',
            'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقماً.',
            'sort_order.min' => 'ترتيب العرض يجب أن يكون 1 على الأقل.',
            'image.required' => 'صورة البانر مطلوبة.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'صيغ الصورة المسموحة: jpeg, jpg, png, gif, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
