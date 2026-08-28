<?php

namespace Database\Seeders;

use App\Models\SocialMediaAccount;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class SocialMediaAccountSeeder extends Seeder
{
    public function run(): void
    {
        $directory = public_path('images/social-media-accounts');

        if (! File::isDirectory($directory)) {
            File::makeDirectory($directory, 0755, true);
        }

        $accounts = [
            [
                'name' => 'فيسبوك',
                'url' => 'https://facebook.com/kashkoolgomla',
                'file' => 'demo_facebook.svg',
                'sort_order' => 1,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#1877F2"/><path fill="#fff" d="M36.5 33.5h3.2l1.4-4.6h-4.6v-3c0-1.3.4-2.2 2.3-2.2h2.4V20.2c-.4 0-2-.3-3.9-.3-3.9 0-6.6 2.4-6.6 6.7v3.3h-4.4v4.6h4.4V44h5.3v-10.5z"/></svg>',
            ],
            [
                'name' => 'انستغرام',
                'url' => 'https://instagram.com/kashkoolgomla',
                'file' => 'demo_instagram.svg',
                'sort_order' => 2,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><defs><linearGradient id="g" x1="0%" y1="100%" x2="100%" y2="0%"><stop offset="0%" stop-color="#FD5949"/><stop offset="50%" stop-color="#D6249F"/><stop offset="100%" stop-color="#285AEB"/></linearGradient></defs><rect width="64" height="64" rx="14" fill="url(#g)"/><rect x="17" y="17" width="30" height="30" rx="8" fill="none" stroke="#fff" stroke-width="3"/><circle cx="32" cy="32" r="7" fill="none" stroke="#fff" stroke-width="3"/><circle cx="43" cy="21" r="2.5" fill="#fff"/></svg>',
            ],
            [
                'name' => 'واتساب',
                'url' => 'https://wa.me/201206027127',
                'file' => 'demo_whatsapp.svg',
                'sort_order' => 3,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#25D366"/><path fill="#fff" d="M32 18c-7.7 0-14 6.1-14 13.6 0 2.4.6 4.7 1.8 6.8L18 46l8-1.7c2 1.1 4.2 1.7 6.5 1.7 7.7 0 14-6.1 14-13.6S39.7 18 32 18zm0 24.8c-2 0-3.9-.5-5.6-1.5l-.4-.2-4.8 1 1-4.7-.3-.5a11.2 11.2 0 0 1-1.7-6c0-6.2 5.3-11.2 11.8-11.2 6.5 0 11.8 5 11.8 11.2S38.5 42.8 32 42.8z"/></svg>',
            ],
            [
                'name' => 'تيك توك',
                'url' => 'https://tiktok.com/@kashkoolgomla',
                'file' => 'demo_tiktok.svg',
                'sort_order' => 4,
                'svg' => '<svg xmlns="http://www.w3.org/2000/svg" width="64" height="64" viewBox="0 0 64 64"><rect width="64" height="64" rx="14" fill="#010101"/><path fill="#25F4EE" d="M38 22v11.5a7.5 7.5 0 1 1-5.3-7.2v3.4a3.8 3.8 0 1 0 2.7 3.6V18h3.6a6.5 6.5 0 0 0 6.5 6.5V22a10 10 0 0 1-7.5-3.2z"/><path fill="#FE2C55" d="M40 20.8a6.5 6.5 0 0 0 4.6-1.9V22a10 10 0 0 1-7.5-3.2V31a7.5 7.5 0 1 1-5.3-7.2v3.4a3.8 3.8 0 1 0 2.7 3.6V18h3.6v2.8z"/></svg>',
            ],
        ];

        foreach ($accounts as $account) {
            $path = $directory.'/'.$account['file'];
            File::put($path, $account['svg']);

            SocialMediaAccount::query()->updateOrCreate(
                ['name' => $account['name']],
                [
                    'url' => $account['url'],
                    'image_url' => '/images/social-media-accounts/'.$account['file'],
                    'sort_order' => $account['sort_order'],
                ],
            );
        }

        $this->command?->info('Seeded '.count($accounts).' demo social media accounts.');
    }
}
