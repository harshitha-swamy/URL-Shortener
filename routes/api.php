<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\ShortUrlController;
use App\Http\Controllers\Api\InvitedRegistrationController;

// Auth
Route::post('/login', [AuthController::class, 'login']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

// URLs
Route::middleware('auth:sanctum')->get('/urls', [ShortUrlController::class, 'index']);
Route::middleware('auth:sanctum')->post('/urls', [ShortUrlController::class, 'store']);
Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('short-urls', ShortUrlController::class);
});

Route::middleware('auth:sanctum')->post('/short-urls', [ShortUrlController::class, 'store']);


Route::middleware('auth:sanctum')->get('/me', function (Request $request) {
    return response()->json($request->user());
});

Route::middleware('auth:sanctum')->get('/urls', [ShortUrlController::class, 'index']);


// routes/api.php
Route::middleware('auth:api')->get('/me', function () {
    return auth()->user();
});
// Route::post('/short-urls', [ShortUrlController::class, 'store']);

Route::middleware('auth:sanctum')->post('/short-urls', [ShortUrlController::class, 'store']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
});

Route::middleware('auth:sanctum')->post(
    '/invitations',
    [InvitationController::class, 'store']
);

Route::post('/register-invited', [InvitedRegistrationController::class, 'register']);

Route::middleware('auth:sanctum')->get('/clients', function () {
    return \App\Models\Company::withCount('users')
        ->get()
        ->map(fn ($c) => [
            'name' => $c->name,
            'email' => '-', // optional
            'users_count' => $c->users_count,
            'total_urls' => 0,
            'total_hits' => 0,
        ]);
});




