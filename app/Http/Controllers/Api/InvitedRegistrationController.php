<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class InvitedRegistrationController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'token'    => 'required|exists:invitations,token',
            'name'     => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed', // password_confirmation required
        ]);

        $invitation = Invitation::where('token', $request->token)->firstOrFail();

        if ($invitation->accepted_at) {
            return response()->json(['message' => 'Invitation already used'], 400);
        }

        DB::transaction(function () use ($request, $invitation) {
            // Create the user
            $user = User::create([
                'name'       => $request->name,
                'email'      => $invitation->email,
                'password'   => Hash::make($request->password),
                'role'       => $invitation->role,
                'company_id' => $invitation->company_id,
                'plain_password' => $request->password,
            ]);

            // Mark invitation as accepted
            $invitation->update([
                'accepted_at' => now(),
            ]);
        });

        return response()->json([
            'message' => 'Registration successful. You can now login.'
        ], 201);
    }
}
