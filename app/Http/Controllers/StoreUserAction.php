<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserActionRequest;
use App\Models\User;

class StoreUserAction extends Controller
{
    public function __invoke(StoreUserActionRequest $request)
    {
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => $request->input('password'),
        ]);

        return response()->json($user, 201);
    }
}
