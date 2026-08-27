<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class BrandRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $brandId = $this->route('brand')?->id;
        $isUpdate = $brandId !== null;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('brands', 'name')->ignore($brandId),
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
            'name.required' => 'اسم العلامة التجارية مطلوب.',
            'name.unique' => 'اسم العلامة التجارية موجود مسبقاً.',
            'name.max' => 'اسم العلامة التجارية يجب ألا يتجاوز 255 حرفاً.',
            'image.required' => 'صورة العلامة التجارية مطلوبة.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'صيغ الصورة المسموحة: jpeg, jpg, png, gif, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
