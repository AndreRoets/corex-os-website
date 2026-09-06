<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class MediaUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_uploading_requires_a_session(): void
    {
        Storage::fake('public');

        $this->post(route('admin.media.store'), [
            'file' => UploadedFile::fake()->image('cover.jpg'),
        ])->assertRedirect(route('login'));
    }

    public function test_an_image_is_stored_and_served_back_publicly(): void
    {
        Storage::fake('public');

        $response = $this->actingAs(User::factory()->create())->post(route('admin.media.store'), [
            'file' => UploadedFile::fake()->image('Cover Photo!.jpg'),
        ]);

        $response->assertOk()->assertJsonStructure(['url', 'name']);

        $name = $response->json('name');
        Storage::disk('public')->assertExists('seo/'.$name);

        // Retrieved without a session — social-media crawlers fetch these
        // unauthenticated.
        $this->get($response->json('url'))->assertOk();
    }

    public function test_a_non_image_file_is_refused(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())->post(route('admin.media.store'), [
            'file' => UploadedFile::fake()->create('script.php', 10),
        ])->assertSessionHasErrors('file');
    }
}
