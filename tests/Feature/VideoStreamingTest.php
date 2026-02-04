<?php

namespace Tests\Feature;

use App\Models\Video;
use App\Models\VideoSession;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class VideoStreamingTest extends TestCase
{
    use DatabaseTransactions;

    public function test_can_create_video_session()
    {
        $video = Video::factory()->create();

        $response = $this->postJson("/api/videos/{$video->id}/session");

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'session_token',
                     'expires_at',
                     'stream_url'
                 ]);
    }

    public function test_cannot_stream_without_valid_session()
    {
        $video = Video::factory()->create();

        $response = $this->get("/stream/{$video->id}");

        $response->assertStatus(403);
    }

    public function test_video_streaming_with_valid_session()
    {
        $video = Video::factory()->create();
        // Create a dummy file for testing
        $filePath = storage_path('app/videos/' . $video->filename);
        if (!file_exists(dirname($filePath))) {
            mkdir(dirname($filePath), 0755, true);
        }
        file_put_contents($filePath, 'dummy video content');
        $this->assertDatabaseHas('videos', ['id' => $video->id]);
        $session = VideoSession::createSession($video->id);
        $this->assertDatabaseHas('video_sessions', ['video_id' => $video->id]);

        $response = $this->get("/stream/{$video->id}?token={$session->session_token}");

        $response->assertStatus(200)
                 ->assertHeader('Content-Type', 'video/mp4');
    }
}