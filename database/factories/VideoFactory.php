<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Eloquent\Factories\Factory;

class VideoFactory extends Factory
{
    protected $model = Video::class;

    public function definition()
    {
        return [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
            'url' => 'https://rutube.ru/video/' . $this->faker->uuid,
            'video_id' => $this->faker->uuid,
            'platform' => 'rutube',
            'user_id' => User::factory(),
            'is_approved' => true,
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
            'updated_at' => now(),
        ];
    }

    public function pending()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_approved' => false,
            ];
        });
    }
}
