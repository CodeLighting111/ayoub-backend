<?php

namespace Database\Seeders;

use App\Models\GeneralSetting;
use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    public function run(): void
    {
        GeneralSetting::query()->updateOrCreate(
            ['id' => 1],
            [
                'app_title' => 'سوقنا',
                'app_description' => 'منصة متكاملة لتجارة الجملة وتوريد المواد الغذائية بجودة عالية وأسعار تنافسية.',
                'delivery_fee' => 30,
                'min_order_amount' => 0,
            ],
        );

        $this->command?->info('General settings seeded.');
    }
}
