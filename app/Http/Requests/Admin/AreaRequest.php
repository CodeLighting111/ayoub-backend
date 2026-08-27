<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $areaId = $this->route('area')?->id;
        $cityId = $this->input('city_id');

        return [
            'city_id' => ['required', 'exists:cities,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('areas', 'name')
                    ->where(fn ($query) => $query->where('city_id', $cityId))
                    ->ignore($areaId),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'city_id.required' => 'المدينة مطلوبة.',
            'city_id.exists' => 'المدينة المختارة غير موجودة.',
            'name.required' => 'اسم المنطقة مطلوب.',
            'name.unique' => 'اسم المنطقة موجود مسبقاً في هذه المدينة.',
            'name.max' => 'اسم المنطقة يجب ألا يتجاوز 255 حرفاً.',
        ];
    }
}
