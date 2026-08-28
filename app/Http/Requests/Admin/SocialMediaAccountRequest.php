<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class SocialMediaAccountRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $accountId = $this->route('social_media_account')?->id;
        $isUpdate = $accountId !== null;

        return [
            'name' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'url', 'max:2048'],
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
            'name.required' => 'اسم الحساب مطلوب.',
            'name.max' => 'اسم الحساب يجب ألا يتجاوز 255 حرفاً.',
            'url.required' => 'رابط الحساب مطلوب.',
            'url.url' => 'رابط الحساب غير صالح.',
            'url.max' => 'رابط الحساب طويل جداً.',
            'sort_order.integer' => 'ترتيب العرض يجب أن يكون رقماً.',
            'sort_order.min' => 'ترتيب العرض يجب أن يكون 1 على الأقل.',
            'image.required' => 'صورة الحساب مطلوبة.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'صيغ الصورة المسموحة: jpeg, jpg, png, gif, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
