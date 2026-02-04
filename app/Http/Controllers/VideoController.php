<?php

namespace App\Http\Controllers;

use App\Models\Video;
use App\Models\VideoSession;
use Illuminate\Http\Request;

class VideoController extends Controller
{
    public function index()
    {
        $videos = Video::where('is_active', true)->get();
        return view('videos.index', compact('videos'));
    }

    public function show(Video $video)
    {
        return view('videos.show', compact('video'));
    }

    public function create()
    {
        return view('videos.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'video' => 'required|file|mimes:mp4,avi,mov,wmv|max:102400', // 100MB max
            'title' => 'required|string|max:255',
        ]);

        $file = $request->file('video');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('videos', $filename, 'private');

        Video::create([
            'title' => $request->title,
            'filename' => $filename,
            'mime_type' => $file->getMimeType(),
            'file_size' => $file->getSize(),
            'is_active' => true,
        ]);

        return redirect()->route('videos.index')->with('success', 'Video uploaded successfully.');
    }

    public function createSession($videoId, Request $request)
    {
        $video = Video::findOrFail($videoId);

        $session = VideoSession::createSession($videoId);

        return response()->json([
            'session_token' => $session->session_token,
            'expires_at' => $session->expires_at,
            'stream_url' => route('video.stream', [
                'videoId' => $videoId,
                'token' => $session->session_token
            ])
        ]);
    }
}