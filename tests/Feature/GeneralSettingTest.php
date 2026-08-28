<?php

namespace Tests\Feature;

use App\Models\Admin;
use App\Models\GeneralSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GeneralSettingTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_settings_page(): void
    {
        $this->get(route('admin.settings.edit'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_and_update_settings(): void
    {
        $admin = Admin::query()->create([
            'name' => 'Ayoub',
            'email' => 'ayoub@gmail.com',
            'password' => 'admin123',
            'role' => 'superadmin',
        ]);

        GeneralSetting::query()->create([
            'app_title' => 'سوقنا',
            'app_description' => 'وصف تجريبي',
            'delivery_fee' => 30,
            'min_order_amount' => 0,
        ]);

        $this->actingAs($admin, 'admin')
            ->get(route('admin.settings.edit'))
            ->assertOk()
            ->assertSee('الإعدادات العامة')
            ->assertSee('سوقنا');

        $this->actingAs($admin, 'admin')
            ->put(route('admin.settings.update'), [
                'app_title' => 'سوقنا المحدّث',
                'app_description' => 'وصف جديد',
                'hotline_phone' => '01012345678',
                'delivery_fee' => 45,
                'min_order_amount' => 100,
            ])
            ->assertRedirect(route('admin.settings.edit'))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('general_settings', [
            'app_title' => 'سوقنا المحدّث',
            'hotline_phone' => '01012345678',
            'delivery_fee' => 45,
            'min_order_amount' => 100,
        ]);

        $this->assertSame('سوقنا المحدّث', GeneralSetting::platformName());
    }
}
