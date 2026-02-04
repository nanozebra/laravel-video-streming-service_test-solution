<?php

namespace App\Http\Controllers;

use App\Services\VideoStreamService;
use Illuminate\Http\Request;

class StreamController extends Controller
{
    protected $streamService;

    public function __construct(VideoStreamService $streamService)
    {
        $this->streamService = $streamService;
    }

    public function stream($videoId, Request $request)
    {
        $token = $request->query('token');

        return $this->streamService->streamVideo($videoId, $token);
    }
}