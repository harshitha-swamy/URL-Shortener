<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;
use App\Models\Company;

class ShortUrlController extends Controller
{
 public function index(Request $request)
{
    $user = $request->user();
    if ($user->role === 'superAdmin') {
        abort(403);
    }

    $urls = match($user->role) {
        'admin'  => ShortUrl::with('user')->where('company_id', $user->company_id)->get(),
        'member' => ShortUrl::with('user')->where('user_id', $user->id)->get(),
        default  => abort(403),
    };

    
    $response = $urls->map(function($url) {
        return [
            'id' => $url->id,
            'original_url' => $url->original_url,
            'short_code' => $url->short_code,
            'user_id' => $url->user_id,
            'user_name' => $url->user->name ?? null, 
            'company_id' => $url->company_id,
            'clicks' => $url->clicks,
            'created_at' => $url->created_at,
            'updated_at' => $url->updated_at,
        ];
    });

    return response()->json($response, 200);
}


    public function store(Request $request)
    {
        \Log::info('Creating short URL', ['request' => $request->all()]);
        $user = $request->user();
        \Log::info('Authenticated user', ['user_id' => $user->id, 'role' => $user->role]);
        $this->authorize('create', ShortUrl::class);
       
        \Log::info('Validation passed for original_url', ['original_url' => $request->url]);

        $validated = $request->validate([
            'url' => 'required|url',
        ]);
        

        $shortUrl = ShortUrl::create([
            'original_url' => $validated['url'],
            'short_code'   => Str::random(6),
            'user_id'      => $user->id,
            'company_id'   => $user->company_id, 
        ]);

        return response()->json($shortUrl, 201);
    }

 


   
    public function show(Request $request, ShortUrl $shortUrl)
    {
        $this->authorize('view', $shortUrl); // URLPolicy: role-based view
        return response()->json($shortUrl->load(['user', 'company']));
    }

    public function update(Request $request, ShortUrl $shortUrl)
    {
        $this->authorize('update', $shortUrl);

        $data = $request->validate([
            'original_url' => 'required|url',
        ]);

        $shortUrl->update([
            'original_url' => $data['original_url'],
        ]);

        return response()->json($shortUrl);
    }

    public function destroy(ShortUrl $shortUrl)
    {
        $this->authorize('delete', $shortUrl);
        $shortUrl->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
