<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ClientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $clientId = $this->route('client')?->id;

        return [
            'client_category_id' => ['required', 'exists:client_categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'phone' => [
                'required',
                'string',
                'max:20',
                Rule::unique('clients', 'phone')->ignore($clientId),
            ],
            'password' => [$clientId ? 'nullable' : 'required', 'string', 'min:6', 'max:255'],
            'branch_name' => ['required', 'string', 'max:255'],
            'responsible_person' => ['nullable', 'string', 'max:255'],
            'governorate_id' => ['required', 'exists:governorates,id'],
            'city_id' => [
                'required',
                'exists:cities,id',
                Rule::exists('cities', 'id')->where(fn ($query) => $query->where('governorate_id', $this->input('governorate_id'))),
            ],
            'area_id' => [
                'required',
                'exists:areas,id',
                Rule::exists('areas', 'id')->where(fn ($query) => $query->where('city_id', $this->input('city_id'))),
            ],
            'address' => ['nullable', 'string', 'max:1000'],
            'latitude' => ['nullable', 'numeric', 'between:-90,90'],
            'longitude' => ['nullable', 'numeric', 'between:-180,180'],
            'status' => ['required', Rule::in(['active', 'suspended'])],
        ];
    }

    public function messages(): array
    {
        return [
            'client_category_id.required' => 'فئة العميل مطلوبة.',
            'client_category_id.exists' => 'فئة العميل المختارة غير موجودة.',
            'name.required' => 'اسم العميل مطلوب.',
            'phone.required' => 'رقم الهاتف مطلوب.',
            'phone.unique' => 'رقم الهاتف مستخدم مسبقاً.',
            'password.required' => 'كلمة المرور مطلوبة.',
            'password.min' => 'كلمة المرور يجب أن تكون 6 أحرف على الأقل.',
            'branch_name.required' => 'اسم الفرع مطلوب.',
            'governorate_id.required' => 'المحافظة مطلوبة.',
            'city_id.required' => 'المدينة مطلوبة.',
            'city_id.exists' => 'المدينة المختارة لا تتبع المحافظة المحددة.',
            'area_id.required' => 'المنطقة مطلوبة.',
            'area_id.exists' => 'المنطقة المختارة لا تتبع المدينة المحددة.',
            'status.required' => 'حالة العميل مطلوبة.',
            'status.in' => 'حالة العميل غير صالحة.',
        ];
    }
}
