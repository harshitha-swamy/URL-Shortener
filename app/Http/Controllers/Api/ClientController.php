<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if ($user->role === 'member') {
            abort(403, 'Unauthorized');
        }

        if ($user->role === 'super_admin') {
            return Company::withCount([
                'users',
                'shortUrls as total_urls'
            ])
            ->get()
            ->map(fn ($c) => [
                'name'        => $c->name,
                'email'       => '-',
                'users_count' => $c->users_count,
                'total_urls'  => $c->total_urls,
            ]);
        }

        if ($user->role === 'admin') {
            $company = Company::withCount([
                'users',
                'shortUrls as total_urls'
            ])->findOrFail($user->company_id);

            return [[
                'name'        => $company->name,
                'email'       => '-',
                'users_count' => $company->users_count,
                'total_urls'  => $company->total_urls,
            ]];
        }

        abort(403, 'Unauthorized');
    }
}
