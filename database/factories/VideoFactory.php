<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Video>
 */
class VideoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence,
            'filename' => $this->faker->unique()->slug . '.mp4',
            'mime_type' => 'video/mp4',
            'file_size' => $this->faker->numberBetween(1000000, 100000000),
            'is_active' => true,
        ];
    }
}
