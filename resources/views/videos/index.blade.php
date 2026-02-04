<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Video Streaming Service</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100">
    <div class="container mx-auto mt-10 px-4">
        <h1 class="text-3xl font-bold mb-6">Available Videos</h1>
        <a href="{{ route('videos.create') }}" class="bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded mb-6">Upload Video</a>
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($videos as $video)
                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h5 class="text-xl font-semibold mb-2">{{ $video->title }}</h5>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">
                        Size: {{ number_format($video->file_size / 1024 / 1024, 2) }} MB<br>
                        Type: {{ $video->mime_type }}
                    </p>
                    <a href="{{ route('videos.show', $video) }}" class="bg-blue-500 hover:bg-blue-600 text-white font-medium py-2 px-4 rounded">Watch Video</a>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>