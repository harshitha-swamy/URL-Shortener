<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
 public function index(Request $request)
{
    $user = $request->user();
    if ($user->role === 'superAdmin') {
        abort(403);
    }

    $urls = match($user->role) {
        'admin'  => ShortUrl::where('company_id', $user->company_id)->get(),
        'member' => ShortUrl::where('user_id', $user->id)->get(),
        default  => abort(403),
    };

    return response()->json($urls, 200);
}

    public function store(Request $request)
    {
        $user = $request->user();
        $this->authorize('create', ShortUrl::class);
        $request->validate([
            'original_url' => 'required|url',
        ]);

        $validated = $request->validate([
            'original_url' => 'required|url',
        ]);
        

        $shortUrl = ShortUrl::create([
            'original_url' => $validated['original_url'],
            'short_code'   => Str::random(6),
            'user_id'      => $user->id,
            'company_id'   => $user->company_id, // link to user's company
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
