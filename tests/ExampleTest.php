<?php

namespace Tests;

// Убедитесь, что здесь только один класс
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        \App\Models\Video::factory()->count(3)->create();

        $response = $this->get('/');

        $response->assertStatus(200);
    }
}
