<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserUpdateInfoRequest;
use App\Models\User;  // add the User model
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserController extends Controller
{
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function info()
    {
        // use auth()->user() to get authenticated user data
        $user = auth()->user();
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User fetched successfully!',
            ],
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'name' => $user->name,
                    'avatar' => $user->avatar ? $user->avatar : null
                ],
            ],
        ]);
    }

    public function updateInfo(UserUpdateInfoRequest $request)
    {
        $user = auth()->user();
        if ($request->hasFile('file')) {
            $fileUpload = $request->file('file');
            $type = $fileUpload->getClientOriginalExtension();
            $folder = 'avatars/';

            $fileName =  time() . Str::random(5) . '.' . $type;
            if (Storage::disk('public')->put($folder . $fileName, file_get_contents($fileUpload))) {
                if ($user->avatar) {
                    Storage::disk('public')->delete($user->avatar);
                }
                $user->avatar = $folder . $fileName;
            };
        }
        $user->name = $request->name;
        $user->save();
        return response()->json(['message' => 'User information successfully updated', 'user' => $user]);
    }
}
