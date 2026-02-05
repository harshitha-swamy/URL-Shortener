<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShortUrlRedirectController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\ShortUrlController;
use App\Models\ShortUrl;


// Frontend pages
Route::view('/login', 'auth.login');
Route::view('/dashboard', 'dashboard');
Route::view('/invite', 'invite');

// Route::get('/short/{code}', function ($code) {
//     $short = ShortUrl::where('short_code', $code)->firstOrFail();
//     $short->increment('clicks');
//     return redirect($short->original_url);
// });

Route::get('/short/{code}', [ShortUrlRedirectController::class, 'redirect']);


Route::get('/short-urls', function() {
    return view('url_shortener');
});




