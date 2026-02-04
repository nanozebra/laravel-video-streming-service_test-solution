<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $video->title }} - Video Streaming</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <div class="max-w-4xl mx-auto">
            <h1 class="text-3xl font-bold mb-6">{{ $video->title }}</h1>
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                <video id="videoPlayer" controls class="w-full rounded-lg mb-4" poster="">
                    Your browser does not support the video tag.
                </video>
                <div class="flex gap-4">
                    <button id="loadVideoBtn" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded">Load Video</button>
                    <button id="shareUrlBtn" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">Share Stream URL</button>
                    <a href="{{ route('videos.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded">Back to Videos</a>
                </div>
                <div id="shareUrlDiv" class="mt-4 hidden">
                    <input type="text" id="streamUrlInput" readonly class="w-full px-3 py-2 border border-gray-300 rounded-md bg-gray-100 dark:bg-gray-700 dark:border-gray-600 dark:text-gray-100">
                    <button id="copyUrlBtn" class="mt-2 bg-gray-500 hover:bg-gray-600 text-white font-medium py-1 px-3 rounded">Copy URL</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('loadVideoBtn').addEventListener('click', function() {
                // Create session via fetch
                fetch('/api/videos/{{ $video->id }}/session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.session_token) {
                        // Set video source
                        document.getElementById('videoPlayer').src = data.stream_url;
                        document.getElementById('loadVideoBtn').disabled = true;
                        document.getElementById('loadVideoBtn').textContent = 'Video Loaded';
                    } else {
                        alert('Failed to create session');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error creating session');
                });
            });

            document.getElementById('shareUrlBtn').addEventListener('click', function() {
                // Create session and show URL
                fetch('/api/videos/{{ $video->id }}/session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.stream_url) {
                        document.getElementById('streamUrlInput').value = data.stream_url;
                        document.getElementById('shareUrlDiv').classList.remove('hidden');
                    } else {
                        alert('Failed to generate stream URL');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Error generating stream URL');
                });
            });

            document.getElementById('copyUrlBtn').addEventListener('click', function() {
                const url = document.getElementById('streamUrlInput').value;
                navigator.clipboard.writeText(url).then(() => {
                    alert('URL copied to clipboard!');
                }).catch(err => {
                    console.error('Failed to copy: ', err);
                    alert('Failed to copy URL');
                });
            });
        });
    </script>
</body>
</html>