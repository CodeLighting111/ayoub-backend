<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubProductCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $subCategoryId = $this->route('sub_product_category')?->id;
        $mainCategoryId = $this->input('main_product_category_id');

        return [
            'main_product_category_id' => ['required', 'exists:main_product_categories,id'],
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('sub_product_categories', 'title')
                    ->where(fn ($query) => $query->where('main_product_category_id', $mainCategoryId))
                    ->ignore($subCategoryId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'main_product_category_id.required' => 'الفئة الرئيسية مطلوبة.',
            'main_product_category_id.exists' => 'الفئة الرئيسية المختارة غير موجودة.',
            'title.required' => 'عنوان الفئة الفرعية مطلوب.',
            'title.unique' => 'عنوان الفئة الفرعية موجود مسبقاً في هذه الفئة الرئيسية.',
            'title.max' => 'عنوان الفئة الفرعية يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
