<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VideoController;
use App\Http\Controllers\StreamController;

Route::get('/', function () {
    return redirect()->route('videos.index');
});

Route::get('/videos', [VideoController::class, 'index'])->name('videos.index');
Route::get('/videos/create', [VideoController::class, 'create'])->name('videos.create');
Route::get('/videos/{video}', [VideoController::class, 'show'])->name('videos.show');
Route::post('/videos', [VideoController::class, 'store'])->name('videos.store');

Route::post('/api/videos/{videoId}/session', [VideoController::class, 'createSession'])->withoutMiddleware([\Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class]);
Route::get('/stream/{videoId}', [StreamController::class, 'stream'])->middleware('validate.video.session')->name('video.stream');
