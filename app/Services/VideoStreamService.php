<?php

namespace App\Services;

use App\Models\Video;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class VideoStreamService
{
    public function streamVideo($videoId, $sessionId)
    {
        $video = Video::findOrFail($videoId);

        $filePath = storage_path('app/videos/' . $video->filename);

        if (!file_exists($filePath)) {
            abort(404, 'Video file not found');
        }

        return response()->file($filePath, [
            'Content-Type' => $video->mime_type,
            'Accept-Ranges' => 'bytes',
            'Cache-Control' => 'no-cache, no-store, must-revalidate',
            'Pragma' => 'no-cache',
            'Expires' => '0',
            'X-Content-Type-Options' => 'nosniff',
            'X-Frame-Options' => 'DENY',
            'X-XSS-Protection' => '1; mode=block',
        ]);
    }
}