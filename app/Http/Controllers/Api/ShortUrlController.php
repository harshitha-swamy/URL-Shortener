<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ShortUrl;
use Illuminate\Support\Str;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     */
 public function index(Request $request)
{
    $user = $request->user();
    \Log::info("User role: " . $user->role);
    // SuperAdmin cannot view URLs
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




    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        \Log::info("request------->");
        \Log::info($request->all());
        $user = $request->user();

        $request->validate([
    'url' => 'required|url', // change 'original_url' to 'url'
]);

\Log::info("Validated URL: " . $request->url);

        // 🔐 Authorize creation via URLPolicy
        $this->authorize('create', ShortUrl::class);

        // $data = $request->validate([
        //     'original_url' => 'required|url',
        // ]);

        $shortUrl = ShortUrl::create([
            'original_url' => $request->url,
            'short_code'   => Str::random(6),
            'user_id'      => $user->id,
            'company_id'   => $user->company_id, // link to user's company
        ]);

        return response()->json($shortUrl, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, ShortUrl $shortUrl)
    {
        $this->authorize('view', $shortUrl); // URLPolicy: role-based view
        return response()->json($shortUrl->load(['user', 'company']));
    }

    /**
     * Update the specified resource in storage.
     */
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShortUrl $shortUrl)
    {
        $this->authorize('delete', $shortUrl);
        $shortUrl->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
