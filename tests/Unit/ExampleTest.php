<?php

namespace Tests\Unit;

use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    public function test_the_application_returns_a_successful_response(): void
    {
        Video::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
