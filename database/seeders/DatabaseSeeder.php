<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Video;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        Video::create([
            'title' => 'Sample Video 1',
            'filename' => 'sample.mp4',
            'mime_type' => 'video/mp4',
            'file_size' => 1024000, // 1MB
            'is_active' => true,
        ]);

        Video::create([
            'title' => 'Sample Video 2',
            'filename' => 'sample2.mp4',
            'mime_type' => 'video/mp4',
            'file_size' => 2048000, // 2MB
            'is_active' => true,
        ]);
    }
}
