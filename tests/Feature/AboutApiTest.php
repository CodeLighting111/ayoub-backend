<?php

namespace Tests\Feature;

use App\Models\About;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AboutApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_shows_about_page_content(): void
    {
        About::query()->create([
            'title' => 'من نحن',
            'description' => 'وصف تجريبي عن سوقنا.',
            'image_url' => '/images/about/test.png',
        ]);

        $this->getJson('/api/about')
            ->assertOk()
            ->assertJsonPath('data.title', 'من نحن')
            ->assertJsonPath('data.description', 'وصف تجريبي عن سوقنا.')
            ->assertJsonPath('data.image_url', '/images/about/test.png');
    }

    public function test_it_returns_not_found_when_about_is_missing(): void
    {
        $this->getJson('/api/about')->assertNotFound();
    }
}
