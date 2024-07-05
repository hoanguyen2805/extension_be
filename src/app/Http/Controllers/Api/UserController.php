<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateInfoRequest;
use App\Models\User;  // add the User model

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function info()
    {
        // use auth()->user() to get authenticated user data

        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => auth()->user(),
            ],
        ]);
    }

    public function updateInfo(UserUpdateInfoRequest $request)
    {
        $user = auth()->user();
        $user->name = $request->name;
        $user->save();
        return response()->json(['message' => 'User information successfully updated', 'user' => $user]);
    }
}
