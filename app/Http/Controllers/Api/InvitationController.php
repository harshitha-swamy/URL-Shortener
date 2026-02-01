<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\User;
use App\Models\Company;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class InvitationController extends Controller
{
    
    public function store(Request $request)
    {        
        $request->validate([
            'email'        => 'required|email|unique:users,email',
            'role'         => 'required|in:admin,member',
            'company_name' => 'required_if:role,admin',
        ]);

        $this->authorize('create', [Invitation::class, $request->role]);

        DB::transaction(function () use ($request) {

            // SuperAdmin creates company
            if (auth()->user()->role === 'super_admin') {
                $company = Company::create([
                    'name' => $request->company_name,
                ]);
            } else {
                $company = auth()->user()->company;
            }

            // OPTIONAL: store invitation record (can remove if not needed)
            
            Invitation::create([
                'email'      => $request->email,
                'role'       => $request->role,
                'company_id' => $company->id,
                'invited_by' => auth()->id(),
                'token'      => \Str::random(32),
            ]);

            
            $role=$request->role;
            
            User::create([
                'name'       => $request->name, // can be updated later
                'email'      => $request->email,
                'role'       => $role,
                'company_id' => $company->id,
                'password'   => Hash::make($request->password),
                'plain_password' => $request->password,
            ]);
        });

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }

    
}
