@php
    $isEdit = $client->exists;
    $cancelUrl = $isEdit ? route('admin.clients.show', $client) : route('admin.clients.index');
@endphp

<div class="dashboard-card w-full rounded-xl border border-outline-variant bg-surface-container-lowest p-6">
    <form action="{{ $action }}" class="space-y-6" method="POST">
        @csrf
        @if ($method ?? false)
            @method($method)
        @endif

        <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="name">الاسم بالكامل <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('name'),
                    ])
                    id="name"
                    name="name"
                    placeholder="أدخل اسم العميل"
                    required
                    type="text"
                    value="{{ old('name', $client->name) }}"
                >
                @error('name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="phone">رقم الموبيل <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('phone'),
                    ])
                    dir="ltr"
                    id="phone"
                    name="phone"
                    placeholder="01xxxxxxxxx"
                    required
                    style="text-align: right;"
                    type="tel"
                    value="{{ old('phone', $client->phone) }}"
                >
                @error('phone')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="password">
                    كلمة المرور @if (! $isEdit)<span class="text-error">*</span>@endif
                </label>
                @if ($isEdit)
                    <p class="text-xs text-on-surface-variant">اترك الحقل فارغاً للاحتفاظ بكلمة المرور الحالية.</p>
                @endif
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('password'),
                    ])
                    id="password"
                    name="password"
                    placeholder="••••••••"
                    @if (! $isEdit) required @endif
                    type="password"
                >
                @error('password')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="branch_name">اسم الفرع <span class="text-error">*</span></label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('branch_name'),
                    ])
                    id="branch_name"
                    name="branch_name"
                    placeholder="اسم فرع المتجر/الشركة"
                    required
                    type="text"
                    value="{{ old('branch_name', $client->branch_name) }}"
                >
                @error('branch_name')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="client_category_id">فئة العميل <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('client_category_id'),
                    ])
                    id="client_category_id"
                    name="client_category_id"
                    required
                >
                    <option disabled @selected(! old('client_category_id', $client->client_category_id)) value="">اختر الفئة</option>
                    @foreach ($categories as $category)
                        <option @selected((int) old('client_category_id', $client->client_category_id) === $category->id) value="{{ $category->id }}">
                            {{ $category->title }}
                        </option>
                    @endforeach
                </select>
                @error('client_category_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="responsible_person">الشخص المسؤول</label>
                <input
                    @class([
                        'block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('responsible_person'),
                    ])
                    id="responsible_person"
                    name="responsible_person"
                    placeholder="اسم الشخص المسؤول"
                    type="text"
                    value="{{ old('responsible_person', $client->responsible_person) }}"
                >
                @error('responsible_person')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="status">حالة العميل <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('status'),
                    ])
                    id="status"
                    name="status"
                    required
                >
                    <option @selected(old('status', $client->status) === 'active') value="active">نشط</option>
                    <option @selected(old('status', $client->status) === 'suspended') value="suspended">غير نشط</option>
                </select>
                @error('status')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <hr class="border-outline-variant">

        <h3 class="text-lg font-semibold text-primary-container">معلومات العنوان</h3>

        <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="governorate_id">المحافظة <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('governorate_id'),
                    ])
                    id="governorate_id"
                    name="governorate_id"
                    required
                >
                    <option disabled @selected(! old('governorate_id', $client->governorate_id)) value="">اختر المحافظة</option>
                    @foreach ($governorates as $governorate)
                        <option @selected((int) old('governorate_id', $client->governorate_id) === $governorate->id) value="{{ $governorate->id }}">
                            {{ $governorate->name }}
                        </option>
                    @endforeach
                </select>
                @error('governorate_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="city_id">المدينة <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('city_id'),
                    ])
                    id="city_id"
                    name="city_id"
                    required
                >
                    <option disabled value="">اختر المدينة</option>
                </select>
                @error('city_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="block text-sm font-semibold text-on-surface" for="area_id">المنطقة <span class="text-error">*</span></label>
                <select
                    @class([
                        'dashboard-select block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                        'border-red-500' => $errors->has('area_id'),
                    ])
                    id="area_id"
                    name="area_id"
                    required
                >
                    <option disabled value="">اختر المنطقة</option>
                </select>
                @error('area_id')
                    <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface" for="address">العنوان بالتفصيل</label>
            <textarea
                @class([
                    'block w-full resize-y rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container',
                    'border-red-500' => $errors->has('address'),
                ])
                id="address"
                name="address"
                placeholder="رقم العمارة، الشارع، علامة مميزة..."
                rows="3"
            >{{ old('address', $client->address) }}</textarea>
            @error('address')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <div class="space-y-2">
            <label class="block text-sm font-semibold text-on-surface">اللوكيشن على الخريطة</label>
            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                <div class="space-y-2">
                    <label class="text-xs text-on-surface-variant" for="latitude">خط العرض</label>
                    <input
                        class="block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container"
                        id="latitude"
                        name="latitude"
                        placeholder="31.9539"
                        step="any"
                        type="number"
                        value="{{ old('latitude', $client->latitude) }}"
                    >
                </div>
                <div class="space-y-2">
                    <label class="text-xs text-on-surface-variant" for="longitude">خط الطول</label>
                    <input
                        class="block w-full rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-3 text-sm text-on-surface shadow-sm focus:border-primary-container focus:outline-none focus:ring-1 focus:ring-primary-container"
                        id="longitude"
                        name="longitude"
                        placeholder="35.9106"
                        step="any"
                        type="number"
                        value="{{ old('longitude', $client->longitude) }}"
                    >
                </div>
            </div>
            <button class="mt-2 inline-flex items-center gap-2 rounded-lg border border-outline-variant bg-surface-container-lowest px-4 py-2 text-sm font-medium text-primary-container transition-colors hover:bg-surface-container" id="locate-me" type="button">
                <span class="material-symbols-outlined text-[18px]">my_location</span>
                تحديد موقعي الحالي
            </button>
            @error('latitude')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
            @error('longitude')
                <p class="text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>

        <hr class="border-outline-variant">

        <div class="flex items-center justify-end gap-4 pt-2">
            <a class="rounded-lg px-6 py-2.5 text-sm font-semibold text-on-surface-variant transition-colors hover:bg-surface-container" href="{{ $cancelUrl }}">
                إلغاء
            </a>
            <button class="flex items-center gap-2 rounded-lg bg-primary-container px-8 py-2.5 text-sm font-semibold text-on-primary shadow-sm transition-colors hover:bg-primary" type="submit">
                <span class="material-symbols-outlined text-[18px]">save</span>
                {{ $submitLabel }}
            </button>
        </div>
    </form>
</div>

@push('scripts')
<script>
    (function () {
        const cities = @json($cities);
        const areas = @json($areas);
        const selectedCityId = @json((int) old('city_id', $client->city_id));
        const selectedAreaId = @json((int) old('area_id', $client->area_id));
        const selectedGovernorateId = @json((int) old('governorate_id', $client->governorate_id));

        const governorateSelect = document.getElementById('governorate_id');
        const citySelect = document.getElementById('city_id');
        const areaSelect = document.getElementById('area_id');
        const locateButton = document.getElementById('locate-me');
        const latitudeInput = document.getElementById('latitude');
        const longitudeInput = document.getElementById('longitude');

        function fillSelect(select, items, selectedId, placeholder) {
            select.innerHTML = '';
            const placeholderOption = document.createElement('option');
            placeholderOption.value = '';
            placeholderOption.textContent = placeholder;
            placeholderOption.disabled = true;
            placeholderOption.selected = !selectedId;
            select.appendChild(placeholderOption);

            items.forEach(function (item) {
                const option = document.createElement('option');
                option.value = item.id;
                option.textContent = item.name;
                option.selected = Number(selectedId) === Number(item.id);
                select.appendChild(option);
            });
        }

        function refreshCities() {
            const governorateId = Number(governorateSelect.value);
            const filtered = cities.filter(function (city) {
                return Number(city.governorate_id) === governorateId;
            });
            fillSelect(citySelect, filtered, selectedCityId && Number(governorateSelect.value) === selectedGovernorateId ? selectedCityId : '', 'اختر المدينة');
            refreshAreas();
        }

        function refreshAreas() {
            const cityId = Number(citySelect.value);
            const filtered = areas.filter(function (area) {
                return Number(area.city_id) === cityId;
            });
            fillSelect(areaSelect, filtered, selectedAreaId && Number(citySelect.value) === selectedCityId ? selectedAreaId : '', 'اختر المنطقة');
        }

        if (governorateSelect && citySelect && areaSelect) {
            governorateSelect.addEventListener('change', function () {
                fillSelect(citySelect, cities.filter(function (city) {
                    return Number(city.governorate_id) === Number(governorateSelect.value);
                }), '', 'اختر المدينة');
                fillSelect(areaSelect, [], '', 'اختر المنطقة');
            });

            citySelect.addEventListener('change', function () {
                fillSelect(areaSelect, areas.filter(function (area) {
                    return Number(area.city_id) === Number(citySelect.value);
                }), '', 'اختر المنطقة');
            });

            refreshCities();
        }

        if (locateButton && latitudeInput && longitudeInput && navigator.geolocation) {
            locateButton.addEventListener('click', function () {
                locateButton.disabled = true;
                navigator.geolocation.getCurrentPosition(function (position) {
                    latitudeInput.value = position.coords.latitude.toFixed(7);
                    longitudeInput.value = position.coords.longitude.toFixed(7);
                    locateButton.disabled = false;
                }, function () {
                    locateButton.disabled = false;
                });
            });
        }
    })();
</script>
@endpush
