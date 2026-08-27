<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GovernorateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $governorateId = $this->route('governorate')?->id;

        return [
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('governorates', 'name')->ignore($governorateId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'اسم المحافظة مطلوب.',
            'name.unique' => 'اسم المحافظة موجود مسبقاً.',
            'name.max' => 'اسم المحافظة يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
