<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserForgotPasswordRequest;
use Illuminate\Support\Facades\Mail;
use PragmaRX\Google2FA\Google2FA;
use App\Models\Otp;
use Carbon\Carbon;

class ForgotPasswordOTPController extends Controller
{
    public function sendOTP(UserForgotPasswordRequest $request)
    {
        $google2fa = new Google2FA();
        $otp = $google2fa->generateSecretKey(6, 3);

        $otpExpiryMinutes = config('auth.otp_expire_minutes', 10);

        Otp::create([
            'email' => $request->email,
            'otp' => $otp,
            'expires_at' => Carbon::now()->addMinutes($otpExpiryMinutes),
        ]);

        Mail::raw("Your OTP is: {$otp}", function ($message) use ($request) {
            $message->to($request->email)
                ->subject('Your OTP for password reset');
        });

        return response()->json(['message' => 'OTP sent to your email.'], 200);
    }
}
