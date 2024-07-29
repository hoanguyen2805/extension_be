<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserResetPasswordRequest;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Otp;

class ResetPasswordOTPController extends Controller
{
    public function resetPassword(UserResetPasswordRequest $request)
    {
        $otpRecord = Otp::where('email', $request->email)
            ->where('otp', $request->otp)
            ->first();

        if (!$otpRecord || $otpRecord->expires_at->isPast()) {
            return response()->json(['message' => 'Invalid or expired OTP.'], 400);
        }

        $user = User::where('email', $request->email)->first();
        $user->password = Hash::make($request->password);
        $user->save();

        $otpRecord->delete();

        return response()->json(['message' => 'Password reset successful.'], 200);
    }
}
