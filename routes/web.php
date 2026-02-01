<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlRedirectController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ShortUrlController;


// Frontend pages
Route::view('/login', 'auth.login');
Route::view('/dashboard', 'dashboard');
Route::view('/invite', 'invite');

Route::get('/short/{code}', function ($code) {
    $short = \App\Models\ShortUrl::where('short_code', $code)->firstOrFail();
    $short->increment('clicks');
    return redirect($short->original_url);
});

Route::get('/short-urls', function() {
    return view('url_shortener');
});




