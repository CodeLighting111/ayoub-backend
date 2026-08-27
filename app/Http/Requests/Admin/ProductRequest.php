<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class ProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $productId = $this->route('product')?->id;
        $isUpdate = $productId !== null;
        $subCategoryId = $this->input('sub_product_category_id');

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('products', 'name')
                    ->where(fn ($query) => $query->where('sub_product_category_id', $subCategoryId))
                    ->ignore($productId),
            ],
            'brand_id' => ['required', 'exists:brands,id'],
            'sub_product_category_id' => ['required', 'exists:sub_product_categories,id'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'discount_price' => ['nullable', 'numeric', 'min:0', 'lt:price'],
            'pieces' => ['required', 'integer', 'min:1'],
            'stock' => ['required', 'integer', 'min:0'],
            'status' => ['required', Rule::in(['active', 'inactive'])],
            'image' => [
                $isUpdate ? 'nullable' : 'required',
                'image',
                'mimes:jpeg,jpg,png,gif,webp',
                'max:10240',
            ],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $mainCategoryId = $this->input('main_product_category_id');
            $subCategoryId = $this->input('sub_product_category_id');

            if (! $mainCategoryId || ! $subCategoryId) {
                return;
            }

            $subCategory = \App\Models\SubProductCategory::query()->find($subCategoryId);

            if ($subCategory && (int) $subCategory->main_product_category_id !== (int) $mainCategoryId) {
                $validator->errors()->add('sub_product_category_id', 'الفئة الفرعية لا تتبع الفئة الرئيسية المختارة.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المنتج مطلوب.',
            'name.unique' => 'اسم المنتج موجود مسبقاً في هذه الفئة الفرعية.',
            'name.max' => 'اسم المنتج يجب ألا يتجاوز 255 حرفاً.',
            'brand_id.required' => 'العلامة التجارية مطلوبة.',
            'brand_id.exists' => 'العلامة التجارية المختارة غير صالحة.',
            'sub_product_category_id.required' => 'الفئة الفرعية مطلوبة.',
            'sub_product_category_id.exists' => 'الفئة الفرعية المختارة غير صالحة.',
            'price.required' => 'السعر مطلوب.',
            'price.numeric' => 'السعر يجب أن يكون رقماً.',
            'price.min' => 'السعر يجب ألا يكون سالباً.',
            'discount_price.numeric' => 'سعر الخصم يجب أن يكون رقماً.',
            'discount_price.min' => 'سعر الخصم يجب ألا يكون سالباً.',
            'discount_price.lt' => 'سعر الخصم يجب أن يكون أقل من السعر الأصلي.',
            'pieces.required' => 'عدد القطع مطلوب.',
            'pieces.integer' => 'عدد القطع يجب أن يكون رقماً صحيحاً.',
            'pieces.min' => 'عدد القطع يجب أن يكون 1 على الأقل.',
            'stock.required' => 'المخزون مطلوب.',
            'stock.integer' => 'المخزون يجب أن يكون رقماً صحيحاً.',
            'stock.min' => 'المخزون يجب ألا يكون سالباً.',
            'status.required' => 'حالة المنتج مطلوبة.',
            'status.in' => 'حالة المنتج غير صالحة.',
            'image.required' => 'صورة المنتج مطلوبة.',
            'image.image' => 'يجب أن يكون الملف صورة.',
            'image.mimes' => 'صيغ الصورة المسموحة: jpeg, jpg, png, gif, webp.',
            'image.max' => 'حجم الصورة يجب ألا يتجاوز 10 ميجابايت.',
        ];
    }
}
