<?php

namespace App\Http\Controllers;

use App\Exceptions\InvalidCredentialsException;
use App\Http\Requests\AuthUserRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AuthUserAction extends Controller
{
    public function __invoke(AuthUserRequest $request)
    {
        /** @var User $user */
        $user = User::where('email', $request->input('email'))->first();

        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            throw new InvalidCredentialsException();
        }

        $token = $user->createToken('auth_token');

        return response()->json([
            'access_token' => $token->plainTextToken,
        ]);
    }
}
