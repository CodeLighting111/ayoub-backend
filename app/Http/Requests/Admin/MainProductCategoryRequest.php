<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class MainProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('main_product_category')?->id;
        $isUpdate = $categoryId !== null;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('main_product_categories', 'title')->ignore($categoryId),
            ],
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
            'title.required' => 'عنوان الفئة مطلوب.',
            'title.unique' => 'عنوان الفئة موجود مسبقاً.',
            'title.max' => 'عنوان الفئة يجب ألا يتجاوز 255 حرفاً.',
            'image.required' => 'صورة الفئة مطلوبة.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'صيغ الصورة المسموحة: jpeg, jpg, png, gif, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
