<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_page_is_displayed(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        $response = $this
            ->actingAs($user)
            ->get('/profile');

        $response->assertOk();
        // Check if the role is visible on the page
        $response->assertSee('user');
    }

    public function test_profile_information_can_be_updated_with_picture(): void
    {
        Storage::fake('public'); // Simulate the storage disk
        $file = UploadedFile::fake()->image('avatar.jpg');
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->patch('/profile', [
                'name' => 'New Name',
                'profile_picture' => $file,
            ]);

        $response
            ->assertSessionHasNoErrors()
            ->assertRedirect('/profile');

        $user->refresh();

        $this->assertSame('New Name', $user->name);
        // Verify the file was stored and the database was updated
        $this->assertNotNull($user->profile_picture);
        Storage::disk('public')->assertExists($user->profile_picture);
    }

    public function test_user_cannot_change_their_own_role(): void
    {
        $user = User::factory()->create(['role' => 'user']);

        // Attempting to "hack" the role to admin
        $this->actingAs($user)
            ->patch('/profile', [
                'name' => 'Test User',
                'role' => 'admin', 
            ]);

        $this->assertSame('user', $user->refresh()->role);
    }
}