<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CityRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $cityId = $this->route('city')?->id;
        $governorateId = $this->input('governorate_id');

        return [
            'governorate_id' => ['required', 'exists:governorates,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('cities', 'name')
                    ->where(fn ($query) => $query->where('governorate_id', $governorateId))
                    ->ignore($cityId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'governorate_id.required' => 'المحافظة مطلوبة.',
            'governorate_id.exists' => 'المحافظة المختارة غير موجودة.',
            'name.required' => 'اسم المدينة مطلوب.',
            'name.unique' => 'اسم المدينة موجود مسبقاً في هذه المحافظة.',
            'name.max' => 'اسم المدينة يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
