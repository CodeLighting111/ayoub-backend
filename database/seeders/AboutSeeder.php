<?php

namespace Database\Seeders;

use App\Models\About;
use Illuminate\Database\Seeder;

class AboutSeeder extends Seeder
{
    public function run(): void
    {
        About::query()->updateOrCreate(
            ['id' => 1],
            [
                'title' => 'من نحن',
                'description' => 'منصة تجارة إلكترونية متخصصة في توفير منتجات البقالة والجملة '
                    .'لأصحاب المحلات والتجار. نسعى لتقديم أفضل الأسعار وأعلى جودة '
                    .'مع خدمة توصيل سريعة وموثوقة.',
            ],
        );

        $this->command?->info('About page content seeded.');
    }
}
