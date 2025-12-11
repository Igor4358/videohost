<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class VideoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_create_video()
    {
        $response = $this->get(route('videos.create'));

        $response->assertRedirect(route('login'));
    }

    public function test_user_can_create_video()
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get(route('videos.create'));
        $response->assertOk();
        $response->assertViewIs('videos.create');
    }

    public function test_store_video_validation()
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $response = $this->post(route('videos.store'), [
            'url' => 'not-a-valid-url',
            'title' => ''
        ]);

        $response->assertSessionHasErrors(['url', 'title']);
    }

    public function test_show_video()
    {
        $video = Video::factory()->create(['is_approved' => true]);

        $response = $this->get(route('videos.show', $video));

        $response->assertOk();
        $response->assertViewIs('videos.show');
        $response->assertViewHas('video', $video);
    }
}
