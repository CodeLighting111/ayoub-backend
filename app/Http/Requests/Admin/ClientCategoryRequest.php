<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $categoryId = $this->route('client_category')?->id;

        return [
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('client_categories', 'title')->ignore($categoryId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'عنوان الفئة مطلوب.',
            'title.unique' => 'عنوان الفئة موجود مسبقاً.',
            'title.max' => 'عنوان الفئة يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
