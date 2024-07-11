<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Team;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\User\UserUpdatePasswordRequest;
use App\Http\Requests\User\UserRegisterInfoRequest;

class AuthController extends Controller
{
    public function __construct(User $user)
    {
        // model as dependency injection
        $this->user = $user;
    }

    public function register(UserRegisterInfoRequest $request)
    {
        // if the request valid, create user

        $user = $this->user::create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password']),
        ]);

        // login the user immediately and generate the token
        $token = auth()->login($user);

        // return the response as json 
        return response()->json([
            'meta' => [
                'code' => 200,
                'status' => 'success',
                'message' => 'User created successfully!',
            ],
            'data' => [
                'user' => $user,
                'access_token' => [
                    'token' => $token,
                    'type' => 'Bearer',
                    'expires_in' => auth()->factory()->getTTL() * 60,    // get token expires in seconds
                ],
            ],
        ]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // attempt a login (validate the credentials provided)
        $token = auth()->attempt([
            'email' => $request->email,
            'password' => $request->password,
        ]);

        // if token successfully generated then display success response
        // if attempt failed then "unauthenticated" will be returned automatically
        if ($token) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Quote fetched successfully.',
                ],
                'data' => [
                    'user' => auth()->user(),
                    'team' => Team::find(auth()->user()->team_id),
                    'access_token' => [
                        'token' => $token,
                        'type' => 'Bearer',
                        'expires_in' => auth()->factory()->getTTL() * 60,
                    ],
                ],
            ]);
        }

        return response()->json(['message' => 'Wrong Email information or password'], 401);
    }

    public function logout()
    {
        // get token
        $token = JWTAuth::getToken();

        // invalidate token
        $invalidate = JWTAuth::invalidate($token);

        if ($invalidate) {
            return response()->json([
                'meta' => [
                    'code' => 200,
                    'status' => 'success',
                    'message' => 'Successfully logged out',
                ],
                'data' => [],
            ]);
        }
    }

    public function changePassword(UserUpdatePasswordRequest $request)
    {
        // Lấy người dùng hiện tại
        $user = auth()->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json(
                ['message' => 'Current password is incorrect'], 
                401
            );
        }

        // Cập nhật mật khẩu mới
        $user->password = Hash::make($request->new_password);
        $user->save();
        $this->logout();
        return response()->json(['message' => 'Password successfully changed']);
    }
}
