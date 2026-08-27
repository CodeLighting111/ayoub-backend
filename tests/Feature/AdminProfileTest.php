<?php

namespace Tests\Feature;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AdminProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_profile_page(): void
    {
        $this->get(route('admin.profile.edit'))->assertRedirect(route('admin.login'));
    }

    public function test_admin_can_view_profile_page(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->get(route('admin.profile.edit'))
            ->assertOk()
            ->assertSee('الملف الشخصي')
            ->assertSee($admin->email);
    }

    public function test_admin_can_update_profile_info(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => 'Ayoub Updated',
                'email' => 'updated@example.com',
                'phone' => '01012345678',
            ])
            ->assertRedirect(route('admin.profile.edit'))
            ->assertSessionHas('success');

        $admin->refresh();

        $this->assertSame('Ayoub Updated', $admin->name);
        $this->assertSame('updated@example.com', $admin->email);
        $this->assertSame('01012345678', $admin->phone);
    }

    public function test_admin_can_update_password(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'current_password' => 'admin123',
                'password' => 'NewPass1',
                'password_confirmation' => 'NewPass1',
            ])
            ->assertRedirect(route('admin.profile.edit'));

        $admin->refresh();

        $this->assertTrue(Hash::check('NewPass1', $admin->password));
    }

    public function test_admin_can_upload_avatar(): void
    {
        $admin = $this->createAdmin();

        $this->actingAs($admin, 'admin')
            ->put(route('admin.profile.update'), [
                'name' => $admin->name,
                'email' => $admin->email,
                'avatar' => UploadedFile::fake()->image('avatar.jpg'),
            ])
            ->assertRedirect(route('admin.profile.edit'));

        $admin->refresh();

        $this->assertNotNull($admin->avatar_url);
        $this->assertStringStartsWith('/images/admins/', $admin->avatar_url);
    }

    private function createAdmin(): Admin
    {
        return Admin::query()->create([
            'name' => 'Ayoub',
            'email' => 'ayoub@gmail.com',
            'password' => 'admin123',
            'role' => 'superadmin',
        ]);
    }
}
