<?php

namespace App\Http\Controllers;

use App\Models\ShortUrl;
use Illuminate\Http\Request;

class ShortUrlRedirectController extends Controller
{
    /**
     * Redirect short URL to original URL
     */
    public function redirect($short_code)
    {
        $shortUrl = ShortUrl::where('short_code', $short_code)->first();

        if (!$shortUrl) {
            return response()->json(['message' => 'URL not found'], 404);
        }
        $shortUrl->increment('clicks');

        return redirect()->away($shortUrl->original_url);
    }
}
